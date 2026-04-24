<?php

namespace App\Observers;

use App\Models\Goal;
use App\Models\User;

class GoalObserver
{
    public function updated(Goal $goal): void
    {
        if (!$goal->wasChanged('flg')) return;

        $oldFlg = (int) $goal->getOriginal('flg');
        $newFlg = (int) $goal->flg;

        $delta = match (true) {
            $oldFlg === 0 && $newFlg === 1 =>  10,
            $oldFlg === 1 && $newFlg === 0 => -10,
            default                        =>   0,
        };

        if ($delta === 0) return;

        $userId = $goal->user_id;

        if ($delta > 0) {
            User::where('id', $userId)
                ->increment('point', $delta);
        } else {
            User::where('id', $userId)
                ->where('point', '>=', abs($delta))
                ->decrement('point', abs($delta));
        }
    }
}