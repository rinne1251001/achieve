<?php

namespace App\Actions\Chat;

use App\Services\Chat\GoalSuggestionService;
use App\Services\Chat\UnmatchedKeywordLogger;

class HandleDislikeInversionAction
{
    public function __construct(
        private GoalSuggestionService $goalService,
        private UnmatchedKeywordLogger $unmatchedLogger,
    ) {}

    public function execute(string $text, int $index, int $taskOffset): array
    {
        $result = $this->goalService->invertDislike($text, $index);

        // マッチしなかった場合のステータスを GoalSuggestionService 側で返す想定
        if ($result['status'] === 'not_found') {
            $this->unmatchedLogger->log($text, 'goal_inversion');
        }

        if (isset($result['tasks'])) {
            $allTasks = $result['tasks'];
            $hasMoreFromService     = $result['has_more'] ?? false; // 元の値を先に保存
            $hasMoreTasks           = isset($allTasks[$taskOffset + 3]);
            $hasMoreSuggestions     = $hasMoreFromService;

            $result['tasks']               = array_slice($allTasks, $taskOffset, 3);
            $result['has_more_tasks']      = $hasMoreTasks;
            $result['has_more_suggestions']= $hasMoreSuggestions;
            $result['has_more']            = $hasMoreTasks || $hasMoreSuggestions;
            $result['task_offset']         = $taskOffset;
        }

        return $result;
    }
}