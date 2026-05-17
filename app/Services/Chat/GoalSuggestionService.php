<?php
namespace App\Services\Chat;

class GoalSuggestionService
{
    public function analyzeInput(string $text): ?array
    {
        $templates = config('task_templates.categories');
        if (!$templates) return null;

        foreach ($templates as $key => $data) {
            if ($text === ($data['name'] ?? '')) return $data;
            $allKeywords = array_merge($data['keywords'] ?? [], $data['synonyms'] ?? []);
            foreach ($allKeywords as $word) {
                if (mb_strpos($text, $word) !== false) return $data;
            }
        }
        return null;
    }

    public function invertDislike(string $text, int $index = 0): array
    {
        $config        = config('goal_inversion');
        $categories    = $config['categories'];
        $taskTemplates = config('task_templates.categories');

        $no_dislike_words = ['ない', 'なし', '特にない', '嫌いなことはない', 'わからない'];
        foreach ($no_dislike_words as $word) {
            if (mb_strpos($text, $word) !== false) {
                return [
                    'status'  => 'no_dislike',
                    'message' => $config['messages']['nothing_disliked'],
                ];
            }
        }

        $matchedCategory = $this->findMatchedCategory($categories, $text);

        if ($matchedCategory === null) {
            return [
                'status'  => 'not_found',
                'message' => $config['messages']['not_found'],
            ];
        }

        $candidates = $this->buildCandidates($matchedCategory, $taskTemplates, $config);

        if (!isset($candidates[$index])) {
            return empty($candidates)
                ? ['status' => 'not_found', 'message' => $config['messages']['not_found']]
                : ['status' => 'no_more',   'message' => 'この方向性での提案は以上です。'];
        }

        return array_merge($candidates[$index], [
            'status'   => 'success',
            'has_more' => isset($candidates[$index + 1]),
        ]);
    }

    // カテゴリをキーワードで検索
    public function findSuggestionByGoalName(string $goalName): ?array
    {
        foreach (config('task_templates.categories') as $category) {
            if (!isset($category['suggestions'])) {
                continue;
            }
            foreach ($category['suggestions'] as $suggestion) {
                if (isset($suggestion['goal']) && $suggestion['goal'] === $goalName) {
                    // カテゴリ全体の suggestions を返す（「他の案も見たい」対応のため）
                    return [
                        'suggestions'  => $category['suggestions'],
                        'matched_index' => array_search($suggestion, $category['suggestions']),
                    ];
                }
            }
        }
        return null;
    }

    // ゴール候補リストを構築（ゴール名でタスクを検索）
    private function buildCandidates(array $categoryData, array $taskTemplates, array $config): array
    {
        $candidates = [];
        $message    = str_replace(':value', $categoryData['value_name'], $config['messages']['found']);

        foreach ($categoryData['suggested_goals'] as $suggestedGoal) {
            $tasks = $this->findTasksForGoal($suggestedGoal, $categoryData['value_name'], $taskTemplates);

            $candidates[] = [
                'goal'    => $suggestedGoal,
                'tasks'   => $tasks,
                'message' => $message,
            ];
        }

        return $candidates;
    }

    // ゴール名にマッチするタスクテンプレートを探す（スコアリング方式）
    private function findTasksForGoal(string $goalText, string $valueName, array $taskTemplates): array
    {
        $defaultTasks = ['まずは具体的な計画を立てる', '必要な情報を集める', '今日できる小さな一歩を決める'];

        // ① ゴール名の完全一致を最優先で探す
        foreach ($taskTemplates as $tData) {
            foreach ($tData['suggestions'] ?? [] as $suggestion) {
                if (($suggestion['goal'] ?? '') === $goalText && !empty($suggestion['tasks'])) {
                    return $suggestion['tasks'];
                }
            }
        }

        // ② 一致しない場合は既存のスコアリング方式にフォールバック
        $bestScore    = 0;
        $bestTasks    = $defaultTasks;

        foreach ($taskTemplates as $tData) {
            $searchPool = array_merge(
                [$tData['name'] ?? ''],
                $tData['keywords'] ?? [],
                $tData['synonyms'] ?? []
            );

            $score = 0;
            foreach ($searchPool as $poolWord) {
                if ($poolWord === '') continue;
                if (mb_strpos($goalText, $poolWord) !== false) $score += 2;   // ゴール名一致は高スコア
                if (mb_strpos($valueName, $poolWord) !== false) $score += 1;  // 価値観名一致は低スコア
            }

            if ($score > $bestScore && isset($tData['suggestions'][0]['tasks'])) {
                $bestScore = $score;
                $bestTasks = $tData['suggestions'][0]['tasks'];
            }
        }

        return $bestTasks;
    }
}