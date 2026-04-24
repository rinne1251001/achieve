<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Goal extends Model
{
    protected $fillable = ['goal', 'flg', 'target_date', 'detail'];
    protected $casts = [
        'flg'         => 'integer',
        'target_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // GoalはたくさんのTaskを持っている（1対多）
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    const STATUS_INCOMPLETE = 0;
    const STATUS_COMPLETE   = 1;

    public function scopeIncomplete(Builder $query): Builder
    {
        return $query->where('flg', self::STATUS_INCOMPLETE);
    }

    public function scopeComplete(Builder $query): Builder
    {
        return $query->where('flg', self::STATUS_COMPLETE);
    }
}