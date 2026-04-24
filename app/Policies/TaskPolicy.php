<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    public function update(User $user, Task $task): bool
    {
        $task->loadMissing('goal');
        return $task->goal !== null && $user->id === $task->goal->user_id;
    }

    public function delete(User $user, Task $task): bool
    {
        $task->loadMissing('goal');
        return $task->goal !== null
            && $user->id === $task->goal->user_id
            && $task->flg === 0;
    }
}