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
        $goalId = $validated['goal_id'] ?? null;

        return DB::transaction(function () use ($user, $validated, $mode, $goalId): JsonResponse {
            // task_only モード: 既存の未完了ゴールを探す
            if ($mode === 'task_only') {
                if (!$goalId) {
                    return response()->json(['status' => 'error', 'message' => 'ゴールIDが必要です'], 422);
                }
                $goal = $user->goals()                  // user_id の一致を保証
                    ->where('id', $goalId)
                    ->where('flg', Goal::STATUS_INCOMPLETE)
                    ->lockForUpdate()
                    ->first();

                if (!$goal) {
                    return response()->json(['status' => 'error', 'message' => 'ゴールが見つかりません'], 404);
                }

            } elseif ($goalId) {
                // 既存ゴールへのタスク追加: 上限チェック不要
                $goal = $user->goals()
                    ->where('id', $goalId)
                    ->where('flg', Goal::STATUS_INCOMPLETE)
                    ->lockForUpdate()
                    ->first();

                if (!$goal) {
                    return response()->json(['status' => 'error', 'message' => 'ゴールが見つかりません'], 404);
                }

            } else {
                // goal_deciding モード: 上限チェックと重複チェックを1クエリで実行
                $incompleteGoals = $user->goals()
                    ->where('flg', Goal::STATUS_INCOMPLETE)
                    ->lockForUpdate()
                    ->get(['id', 'goal']);

                if ($incompleteGoals->count() >= 3) {
                    return response()->json(['status' => 'error', 'message' => 'ゴールは3つまでです'], 422);
                }

                // 同名の未完了ゴールが既に存在する場合は専用ステータスを返す
                if ($incompleteGoals->contains('goal', $validated['goal'])) {
                    return response()->json(['status' => 'duplicate_goal'], 409);
                }

                $goal = $user->goals()->create(['goal' => $validated['goal']]);
            }

            if (!empty($validated['tasks'])) {
                // 既存タスクと重複しないものだけ追加
                $existingTasks = $goal->tasks()->pluck('task')->all();
                $newTasks = array_values(array_filter(
                    $validated['tasks'],
                    fn($t) => !in_array($t, $existingTasks, true)
                ));

                if (!empty($newTasks)) {
                    $goal->tasks()->createMany(
                        array_map(fn($t) => ['task' => $t], $newTasks)
                    );
                }
            }

            return response()->json(['status' => 'success', 'message' => '保存完了しました']);
        });
    }
}