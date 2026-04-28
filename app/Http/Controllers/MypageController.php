<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MypageController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // 1クエリで両方集計
        $stats = $user->goals()
            ->leftJoin('tasks', fn($j) => $j
                ->on('tasks.goal_id', '=', 'goals.id')
                ->where('tasks.flg', 1))
            ->selectRaw('
                COUNT(DISTINCT CASE WHEN goals.flg = 1 THEN goals.id END) as completed_goals,
                COUNT(tasks.id) as completed_tasks
            ')
            ->first();

        // 1. 累計完了タスク・ゴールの集計
        $completedGoalsCount = (int) ($stats->completed_goals ?? 0);
        $completedTasksCount = (int) ($stats->completed_tasks ?? 0);

        // レベル決定
        $totalPoints = $user->point;
        $level       = $user->calculateLevel($totalPoints);

        return view('mypage', compact(
            'completedTasksCount',
            'completedGoalsCount',
            'totalPoints',
            'level'
        ));
    }
}