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
    ];
    
    // Taskは一つのGoalに属している（逆の関係）
    public function goal(): BelongsTo
    {
        return $this->belongsTo(Goal::class);
    }
}