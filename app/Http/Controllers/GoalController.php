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

    /* テストページなので本番環境では消す */
    public function taskTestPage(Request $request)
    {
        $data = $this->getCalendarAndTaskData($request);
        return view('task_test', $data); // task_testを表示
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

    public function goalupdate(Request $request, $id)
    {
        try {
            // 1. 指定されたIDでゴールを探す
            $goal = \App\Models\Goal::findOrFail($id);

            // 2. セキュリティチェック（Authが使える状態か確認）
            if ($goal->user_id !== \Illuminate\Support\Facades\Auth::id()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            // 3. データの更新
            // ★重要★ ここで使っている 'target_date' や 'goal' がDBのカラム名と一致しているか確認してください
            $goal->goal        = $request->goal;
            
            // もしDBのカラム名が deadline などの場合は、左側を修正してください
            $goal->target_date = $request->target_date; 
            $goal->detail      = $request->detail;

            // 4. 保存
            $goal->save();

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            // エラーが発生した場合、その具体的な内容を返却するようにします
            return response()->json([
                'success' => false,
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    //■■■■■■■■■■■■■以下から指定するまで、旧MypageController移植■■■■■■■■■■■■■
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

    public function store(Request $request)
    {
        // 所有権チェック：指定されたゴールが自分のものか
        $goal = Goal::where('user_id', Auth::id())->findOrFail($request->goal_id);

        $task = new Task();
        $task->goal_id = $goal->id;
        $task->task = $request->title;
        $task->detail = $request->detail ?? '';
        $task->target_date = $request->target_date;
        $task->flg = 0; // 未完了で作成
        $task->save();

        return response()->json(['success' => true]);
    }

    public function taskupdate(Request $request, $id)
    {
        try {
            // 1. データベースから対象のタスクを探す
            $task = \App\Models\Task::findOrFail($id);

            // 2. データを上書きする
            // ★ここ重要！左側（$task->...）は、あなたのDBにある実際のカラム名にしてください
            $task->task        = $request->title; // DBが 'task' ならこれ
            // $task->title    = $request->title; // もしDBが 'title' ならこっち
            
            $task->detail      = $request->detail;
            $task->target_date = $request->target_date;

            // 3. データベースに保存
            $task->save();

            // 成功をJavaScriptに伝える
            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            // 失敗した場合は、エラーメッセージを返して原因を特定しやすくする
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    //■■■■■■■■■■■■■ここまで、旧MypageController移植■■■■■■■■■■■■■
}