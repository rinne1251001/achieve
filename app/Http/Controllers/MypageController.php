<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;
use App\Models\Goal;

class MypageController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // 1. 累計ポイントの計算
        $completedTasksCount = Task::whereHas('goal', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->where('flg', 1)->count();

        $completedGoalsCount = Goal::where('user_id', $user->id)
            ->where('flg', 1)
            ->count();

        $totalPoints = ($completedTasksCount * 1) + ($completedGoalsCount * 10);

        // 2. レベルの判定
        if ($totalPoints < 2) {
            $level = 1;
        } else {
            $level = floor(($totalPoints - 2) / 5) + 2;
        }

       

        return view('mypage', compact(
            'completedTasksCount',
            'completedGoalsCount',
            'totalPoints',
            'level'
        ));
    }

    public function destroy(Task $task)
    {
        // セキュリティ: 自分のデータかチェック（プランBの継承）
        if ($task->goal->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // 仕様: 未達成(flg=0)のみ削除を許可
        if ($task->flg !== 0) {
            return response()->json(['error' => '完了済みのタスクは削除できません'], 400);
        }

        $task->delete();

        return response()->json(['success' => true]);
    }
}