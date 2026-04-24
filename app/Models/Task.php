<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    protected $fillable = [
        'goal_id',
        'task',
        'flg',
        'target_date',
        'detail',
    ];

    // tinyIntegerの厳格比較バグを防ぐ
    protected $casts = [
        'flg'         => 'integer',
        'target_date' => 'date',
    ];
    
    // Taskは一つのGoalに属している（逆の関係）
    public function goal(): BelongsTo
    {
        return $this->belongsTo(Goal::class);
    }
}