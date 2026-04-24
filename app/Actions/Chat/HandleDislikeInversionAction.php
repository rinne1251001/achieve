<?php

namespace App\Actions\Chat;

use App\Services\Chat\GoalSuggestionService;

class HandleDislikeInversionAction
{
    public function __construct(
        private GoalSuggestionService $goalService,
    ) {}

    public function execute(string $text, int $index, int $taskOffset): array
    {
        $result = $this->goalService->invertDislike($text, $index);

        if (isset($result['tasks'])) {
            $allTasks         = $result['tasks'];
            $result['tasks']       = array_slice($allTasks, $taskOffset, 3);
            $result['has_more']    = isset($allTasks[$taskOffset + 3]);
            $result['task_offset'] = $taskOffset;
        }

        return $result;
    }
}