<?php

namespace App\Actions\Chat;

use App\Models\Goal;
use App\Models\User;
use App\Services\Chat\GoalSuggestionService;
use App\Services\Chat\TaskPersonalizationService;
use App\Services\Chat\UnmatchedKeywordLogger;

class HandleTaskOnlyAction
{
    public function __construct(
        private GoalSuggestionService      $goalService,
        private TaskPersonalizationService $taskService,
    ) {}

    /**
     * @param string $text  ユーザーが選択したゴール名（またはトリガーワード）
     * @param int    $index 提案インデックス
     * @param int    $taskOffset タスクのページングオフセット
     * @return array レスポンス用配列
     */
    public function execute(string $text, int $index, int $taskOffset, User $user, ?int $goalId = null): array
    {
        // 「タスクを決める」ボタン押下時：ゴール一覧を返す
        if ($text === 'タスクを決める') {
            $goals = Goal::where('user_id', $user->id)
                ->incomplete()
                ->get(['id', 'goal']);

            return ['status' => 'goal_list', 'goals' => $goals];
        }

        // ゴールIDを受け取った場合：タスク提案を返す
        $goal = Goal::where('id', $goalId)
                ->where('user_id', $user->id)
                ->where('flg', Goal::STATUS_INCOMPLETE)
                ->first();

        if (!$goal) {
            return ['status' => 'not_found', 'message' => '指定されたゴールが見つかりません。'];
        }

        $typeKey      = $user->analysis?->type_key;
        $categoryData = $this->goalService->analyzeInput($text);

        // カテゴリが見つからない場合はデフォルトタスクを返す
        if (!$categoryData || !isset($categoryData['suggestions'])) {
            $this->unmatchedLogger->log($text, 'task_templates');
            $defaultTasks = ['具体的な計画を立てましょう', '必要な道具を揃える', '今日できる一歩を決める'];
            return [
                'status'   => 'success',
                'goal'     => $goal->goal,
                'tasks'    => $this->taskService->personalizeTasks($defaultTasks, $typeKey),
                'has_more' => false,
            ];
        }

        $suggestions = $categoryData['suggestions'];

        if (!isset($suggestions[$index])) {
            return ['status' => 'no_more', 'message' => '提案は以上です。'];
        }

        $allPersonalizedTasks = $this->taskService->personalizeTasks(
            $suggestions[$index]['tasks'],
            $typeKey
        );

        // ① 現在の提案内に続きのタスクがある OR ② 次の提案インデックスが存在する
        $hasMoreTasks       = isset($allPersonalizedTasks[$taskOffset + 3]);
        $hasMoreSuggestions = isset($suggestions[$index + 1]);

        return [
            'status'        => 'success',
            'goal'          => $goal->goal,
            'tasks'         => array_slice($allPersonalizedTasks, $taskOffset, 3),
            'message'       => $index === 0
                ? "「{$text}」ですね。では、こんなアプローチはいかがでしょう？"
                : '承知いたしました。では、こちらはいかがでしょうか？',
            'has_more'      => $hasMoreTasks || $hasMoreSuggestions,
            'has_more_tasks'       => $hasMoreTasks,
            'has_more_suggestions' => $hasMoreSuggestions,
            'current_index' => $index,
            'task_offset'   => $taskOffset,
        ];
    }
}