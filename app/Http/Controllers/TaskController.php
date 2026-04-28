<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use App\Models\Task;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TaskController extends Controller
{
    use AuthorizesRequests;

    //タスク一覧ページ
    public function index(Request $request)
    {
        $data = $this->getCalendarAndTaskData($request);
        return view('task', $data);
    }

    //タスク新規作成
    public function store(Request $request): JsonResponse
    {
        //送られてきたデータのルールチェック
        $request->validate([
            'goal_id'     => ['required', 'integer'],
            'title'       => ['required', 'string', 'max:50'],
            'detail'      => ['nullable', 'string', 'max:1000'],
            'target_date' => ['nullable', 'date'],
        ]);

        $goal = Goal::findOrFail($request->goal_id);
        $this->authorize('update', $goal);

        $goal->tasks()->create([
            'task'        => $request->title,
            'detail'      => $request->detail,
            'target_date' => $request->target_date ?: null,
            'flg'         => Task::STATUS_INCOMPLETE,
        ]);

        return response()->json(['success' => true]);
    }

    //タスク完了
    public function check(Request $request, Task $task): JsonResponse
    {
        $this->authorize('update', $task);
        $request->validate(['completed' => 'required|boolean']);

        $task->flg = $request->boolean('completed') ? Task::STATUS_COMPLETE : Task::STATUS_INCOMPLETE;
        $task->save();

        return response()->json(['success' => true]);
    }

    //更新
    public function update(Request $request, Task $task): JsonResponse
    {
        $this->authorize('update', $task);

        $request->validate([
            'title'       => ['required', 'string', 'max:50'],
            'detail'      => ['nullable', 'string', 'max:1000'],
            'target_date' => ['nullable', 'date'],
        ]);

        $task->task        = $request->title;
        $task->detail      = $request->detail;
        $task->target_date = $request->target_date;
        $task->save();

        return response()->json(['success' => true]);
    }

    //削除
    public function destroy(Task $task): JsonResponse
    {
        $this->authorize('delete', $task);
        $task->delete();

        return response()->json(['success' => true]);
    }

    /**
     * 内部メソッド：カレンダーとタスクの共通データ取得ロジック
     */
    private function getCalendarAndTaskData(Request $request)
    {
        $request->validate([
            'year'  => ['nullable', 'integer', 'between:2000,2100'],
            'month' => ['nullable', 'integer', 'between:1,12'],
        ]);

        $user = Auth::user();
        // 4. カレンダー日付計算
        $year = $request->query('year', date('Y'));
        $month = $request->query('month', date('m'));

        $dt = Carbon::createFromDate($year, $month, 1);
        $startOfCalendar = $dt->copy()->firstOfMonth()->startOfWeek(Carbon::SUNDAY);
        $endOfCalendar = $dt->copy()->lastOfMonth()->endOfWeek(Carbon::SATURDAY);

        // 1. カレンダー表示用：全タスク取得
        $allTasks = Task::whereHas('goal', fn($q) => $q->where('user_id', $user->id))
            ->whereBetween('target_date', [
                $startOfCalendar->toDateString(),
                $endOfCalendar->toDateString(),
            ])
            ->with('goal')
            ->get();

        $taskMap = [];
        foreach ($allTasks as $task) {
            $date = $task->target_date?->format('Y-m-d');
            if ($date) {
                $taskMap[$date][] = $task;
            }
        }

        // 2. 左側リスト用：未完了のゴール(flg=0)と、その中の未完了のタスク(flg=0)
        $goals = Goal::where('user_id', $user->id)
            ->incomplete() // ゴール自体が進行中（0）のもの
            ->with(['tasks' => function($query) {
                $query->where('flg', 0); // 左側には「未達成」のタスクだけを渡す
            }])
            ->get();

        // 3. 右下リスト用：一部でも達成したタスク(flg=1)を持つゴールを取得
        $achievedGoals = Goal::where('user_id', $user->id)
            ->whereHas('tasks', function($query) {
                $query->where('flg', 1); // 達成済みタスクを1つ以上持っているゴールのみ
            })
            ->with(['tasks' => function($query) {
                $query->where('flg', 1); 
            }])
            ->get();

        $monthTitle = $dt->format('m月'); // 月のみ
        $prev = $dt->copy()->subMonth();
        $next = $dt->copy()->addMonth();

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