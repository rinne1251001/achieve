<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GoalController extends Controller{
        public function show(Goal $goal)
        {
            // ログイン中のユーザーIDと一致するか確認（セキュリティ）
            if ($goal->user_id !== Auth::id()) {
                abort(403);
            }

            // 紐づくタスクも一緒に読み込む
            $goal->load('tasks');

            return view('goals_detail', compact('goal'));
        }

        // --- カレンダーコントロール ---
       public function calendar(Request $request)
{
    $user = Auth::user();

    // ログインユーザーのタスクを、ゴール情報と一緒に取得
    $tasks = \App\Models\Task::whereHas('goal', function($query) use ($user) {
        $query->where('user_id', $user->id);
    })->with('goal')->get();

    // 日付をキーにした連想配列に整理する [ '2026-01-26' => [タスク1, タスク2...], ... ]
    $taskMap = [];
    foreach ($tasks as $task) {
        $date = $task->target_date;
        if ($date) {
            $taskMap[$date][] = $task;
        }
    }

    $year = $request->query('year', date('Y'));
    $month = $request->query('month', date('m'));

    $dt = Carbon::createFromDate($year, $month, 1);
    $monthTitle = $dt->format('Y年m月');
    $prev = $dt->copy()->subMonth();
    $next = $dt->copy()->addMonth();

    $startOfCalendar = $dt->copy()->firstOfMonth()->startOfWeek(Carbon::SUNDAY);
    $endOfCalendar = $dt->copy()->lastOfMonth()->endOfWeek(Carbon::SATURDAY);

    $calendarDates = [];
    $currentDay = $startOfCalendar->copy();
    while ($currentDay <= $endOfCalendar) {
        $calendarDates[] = $currentDay->copy();
        $currentDay->addDay();
    }

    // taskDeadlines の代わりに taskMap を渡す
    return view('calender_test', compact(
        'calendarDates', 
        'monthTitle', 
        'taskMap', // 修正
        'dt', 
        'prev', 
        'next'
    ));
}
}