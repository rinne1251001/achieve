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

    public function update(Request $request, $id)
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
}