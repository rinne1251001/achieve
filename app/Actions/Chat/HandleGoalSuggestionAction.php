<?php

namespace App\Actions\Chat;

use App\Services\Chat\GoalSuggestionService;
use App\Services\Chat\TaskPersonalizationService;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class HandleGoalSuggestionAction
{
    public function __construct(
        private GoalSuggestionService      $goalService,
        private TaskPersonalizationService $taskService,
    ) {}

    /**
     * interest_exist / category_selected モード共用
     */
    public function execute(string $text, int $index, int $taskOffset, User $user): array
    {
        $categoryData = $this->goalService->analyzeInput($text);

        if ($categoryData && isset($categoryData['suggestions'])) {
            $suggestions = $categoryData['suggestions'];

            if (!isset($suggestions[$index])) {
                return ['status' => 'no_more', 'message' => 'このカテゴリの提案は以上です！'];
            }

            $typeKey = $user->analysis?->type_key;
            $allPersonalizedTasks = $this->taskService->personalizeTasks(
                $suggestions[$index]['tasks'],
                $typeKey
            );

            $hasMoreTasks       = isset($allPersonalizedTasks[$taskOffset + 3]);
            $hasMoreSuggestions = isset($suggestions[$index + 1]);

            return [
                'status'        => 'success',
                'goal'          => $suggestions[$index]['goal'],
                'tasks'         => array_slice($allPersonalizedTasks, $taskOffset, 3),
                'message'       => $index === 0
                    ? "いいですね！「{$text}」に関連して、こんなゴールはいかがでしょうか？"
                    : '承知いたしました。では、こちらの案はどうでしょう？',
                'has_more'      => $hasMoreTasks || $hasMoreSuggestions,
                'current_index' => $index,
            ];
        }

        // suggestions がない場合でも goal キーがあれば返す
        if (isset($categoryData['goal'])) {
            return [
                'status'   => 'success',
                'goal'     => $categoryData['goal'],
                'tasks'    => $categoryData['tasks'] ?? [],
                'has_more' => false,
            ];
        }

        return [
            'status'  => 'unknown',
            'message' => config('task_templates.responses.unknown', 'すみません、うまく聞き取れませんでした。'),
        ];
    }
}