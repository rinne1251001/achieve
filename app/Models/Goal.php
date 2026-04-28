<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Goal extends Model
{
    //一括保存許可リスト
    protected $fillable = ['goal', 'target_date', 'detail'];
    //型変換
    protected $casts = [
        'flg'         => 'integer',
        'target_date' => 'date',
    ];

    //ゴールは一人のユーザーに紐づく
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // GoalはたくさんのTaskを持っている（1対多）
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    //flg=0はSTATUS_INCOMPLETE、flg=1はSTATUS_COMPLETE
    const STATUS_INCOMPLETE = 0;
    const STATUS_COMPLETE   = 1;

    //未完了検索スコープ（Goal::incomplete()->get()と書くと未完了のゴールだけを検索）
    public function scopeIncomplete(Builder $query): Builder
    {
        return $query->where('flg', self::STATUS_INCOMPLETE);
    }

    //完了検索スコープ（Goal::complete()->get()と書くと完了済みのゴールだけを検索）
    public function scopeComplete(Builder $query): Builder
    {
        return $query->where('flg', self::STATUS_COMPLETE);
    }
}