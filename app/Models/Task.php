<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    //flg=0はSTATUS_INCOMPLETE、flg=1はSTATUS_COMPLETE
    const STATUS_INCOMPLETE = 0;
    const STATUS_COMPLETE   = 1;

    //一括保存許可リスト
    protected $fillable = [
        'goal_id',
        'task',
        'target_date',
        'detail',
        'flg',
    ];

    // tinyIntegerの厳格比較バグを防ぐ
    protected $casts = [
        'flg'         => 'integer',
        'target_date' => 'date',
    ];
    
    // Taskは一つのGoalに属している（逆の関係）
    //$task->goal->goalと書くとそのタスクの親のゴール名が取得できるようになる
    public function goal(): BelongsTo
    {
        return $this->belongsTo(Goal::class);
    }
}