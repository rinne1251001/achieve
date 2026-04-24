<?php

namespace App\Observers;

use App\Models\Task;
use App\Models\User;

class TaskObserver
{
    public function updated(Task $task): void
    {
        // flg が変化していない場合はスキップ
        if (!$task->wasChanged('flg')) return;

        $oldFlg = (int) $task->getOriginal('flg');
        $newFlg = (int) $task->flg;

        // 0 → 1 で +1pt、1 → 0 で -1pt
        $delta = match (true) {
            $oldFlg === 0 && $newFlg === 1 =>  1,
            $oldFlg === 1 && $newFlg === 0 => -1,
            default                        =>  0,
        };

        if ($delta === 0) return;

        // goal経由でuser_idを取得（goal はリレーションで取得済みのはず）
        $task->loadMissing('goal');
        if (!$task->goal) return;

        $userId = $task->goal->user_id;

        if ($delta > 0) {
            User::where('id', $userId)
                ->increment('point', $delta);
        } else {
            // point が 0 を下回らないようにガード
            User::where('id', $userId)
                ->where('point', '>=', abs($delta))
                ->decrement('point', abs($delta));
        }
    }
}