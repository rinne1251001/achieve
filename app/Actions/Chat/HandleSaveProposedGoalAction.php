<?php

namespace App\Actions\Chat;

use App\Models\Goal;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;

class HandleSaveProposedGoalAction
{
    public function execute(array $validated, User $user): JsonResponse
    {
        $mode = $validated['mode'] ?? null;

        return DB::transaction(function () use ($user, $validated, $mode): JsonResponse {
            // task_only モード: 既存の未完了ゴールを探す
            if ($mode === 'task_only') {
                $goal = $user->goals()
                    ->where('goal', $validated['goal'])
                    ->where('flg', Goal::STATUS_INCOMPLETE)
                    ->lockForUpdate()
                    ->first();

                if (!$goal) {
                    return response()->json(['status' => 'error', 'message' => 'ゴールが見つかりません'], 404);
                }
            } else {
                // goal_deciding モード: 上限チェック後に新規作成
                $count = $user->goals()
                    ->where('flg', Goal::STATUS_INCOMPLETE)
                    ->lockForUpdate()
                    ->count();

                if ($count >= 3) {
                    return response()->json(['status' => 'error', 'message' => 'ゴールは3つまでです'], 422);
                }

                // 既存の未完了ゴールを探し、なければ作成（完了済みには触れない）
                $goal = $user->goals()
                    ->where('goal', $validated['goal'])
                    ->where('flg', Goal::STATUS_INCOMPLETE)
                    ->lockForUpdate()
                    ->firstOr(fn() => $user->goals()->create([
                        'goal' => $validated['goal'],
                        'flg'  => Goal::STATUS_INCOMPLETE,
                    ]));
            }

            if (!empty($validated['tasks'])) {
                $goal->tasks()->createMany(
                    array_map(fn($t) => ['task' => $t, 'flg' => 0], $validated['tasks'])
                );
            }

            return response()->json(['status' => 'success', 'message' => '保存完了しました']);
        });
    }
}