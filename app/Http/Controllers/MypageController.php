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

        // 1. 累計完了タスク・ゴールの集計
        $completedTasksCount = Task::whereHas('goal', fn($q) => $q->where('user_id', $user->id))
            ->where('flg', 1)
            ->count();

        $completedGoalsCount = Goal::where('user_id', $user->id)
            ->where('flg', 1)
            ->count();

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