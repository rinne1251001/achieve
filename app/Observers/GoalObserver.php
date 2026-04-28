<?php

namespace App\Observers;

use App\Models\Goal;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class GoalObserver
{
    public function updated(Goal $goal): void
    {
        if (!$goal->wasChanged('flg')) return;

        $delta = match (true) {
            $goal->getOriginal('flg') === 0 && $goal->flg === 1 =>  10,
            $goal->getOriginal('flg') === 1 && $goal->flg === 0 => -10,
            default => 0,
        };

        if ($delta === 0) return;

        $userId = $goal->user_id;

        if ($delta > 0) {
            User::where('id', $userId)->increment('point', $delta);
        } else {
            // 確認更新をひととまとめにしてDBの中でに完結させることで累計ポイントのマイナス防ぐ
            User::where('id', $userId)->update([
                'point' => DB::raw('GREATEST(0, point - ' . abs($delta) . ')')
            ]);
        }
    }
}