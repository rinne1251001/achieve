<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Goal extends Model
{
    protected $fillable = ['user_id', 'goal', 'flg', 'target_date', 'detail'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // GoalはたくさんのTaskを持っている（1対多）
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
}