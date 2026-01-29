<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GoalController extends Controller
{
    /**
     * 個別目標の詳細表示
     */
    public function show(Goal $goal)
    {
        // ログイン中のユーザーIDと一致するか確認
        if ($goal->user_id !== Auth::id()) {
            abort(403);
        }

        // 紐づくタスクも一緒に読み込む
        $goal->load('tasks');

        return view('goals_detail', compact('goal'));
    }

    /**
     * 旧：カレンダー専用テストページ用
     */
    public function calendar(Request $request)
    {
        $data = $this->getCalendarAndTaskData($request);
        return view('calender_test', $data);
    }

    /**
     * 新：タスク管理統合ページ用 (task.blade.php)
     */
    public function taskPage(Request $request)
    {
        $data = $this->getCalendarAndTaskData($request);
        return view('task', $data);
    }

    /**
     * タスクのチェック状態を更新 (Ajax用)
     */
    public function check(Request $request, Task $task)
    {
        // セキュリティチェック（自分のタスクか確認）
        // ※ Taskモデルから紐づくGoalのユーザーIDをチェック
        if ($task->goal->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // JSから送られてきた completed (true/false) を 1/0 に変換して保存
        $task->flg = $request->completed ? 1 : 0;
        $task->save();

        return response()->json(['success' => true]);
    }

    /**
     * 内部メソッド：カレンダーとタスクの共通データ取得ロジック
     */
    private function getCalendarAndTaskData(Request $request)
    {
        $user = Auth::user();

        // 1. カレンダー表示用：全タスク取得
        $allTasks = Task::whereHas('goal', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with('goal')->get();

        $taskMap = [];
        foreach ($allTasks as $task) {
            $date = $task->target_date;
            if ($date) {
                $taskMap[$date][] = $task;
            }
        }

        // 2. 左側リスト用：未完了のゴール(flg=0)と、その中の未完了のタスク(flg=0)
        $goals = Goal::where('user_id', $user->id)
            ->where('flg', 0)
            ->with(['tasks' => function($query) {
                $query->where('flg', 0);
            }])
            ->get();

        // 3. 右下リスト用：一部でも達成したタスク(flg=1)を持つゴールを取得
        $achievedGoals = Goal::where('user_id', $user->id)
            ->whereHas('tasks', function($query) {
                $query->where('flg', 1);
            })
            ->with(['tasks' => function($query) {
                $query->where('flg', 1);
            }])
            ->get();

        // 4. カレンダー日付計算
        $year = $request->query('year', date('Y'));
        $month = $request->query('month', date('m'));

        $dt = Carbon::createFromDate($year, $month, 1);
        $monthTitle = $dt->format('m月'); // 月のみ
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

        return [
            'calendarDates' => $calendarDates,
            'monthTitle'    => $monthTitle,
            'taskMap'       => $taskMap,
            'dt'            => $dt,
            'prev'          => $prev,
            'next'          => $next,
            'goals'         => $goals,
            'achievedGoals' => $achievedGoals,
        ];
    }
}