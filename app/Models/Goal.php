<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Goal extends Model
{
    // GoalはたくさんのTaskを持っている（1対多）
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
}