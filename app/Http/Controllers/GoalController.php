<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use App\Models\Task;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class GoalController extends Controller
{
    use AuthorizesRequests;

    //個別目標の詳細表示
    public function show(Goal $goal): View
    {
        // ログイン中のユーザーIDと一致するか確認
        $this->authorize('view', $goal);
        $goal->load('tasks');
        return view('goals_detail', compact('goal'));
    }

    //ゴール更新
    public function update(Request $request, Goal $goal)
    {
        $this->authorize('update', $goal);

        //入力値のルールチェック
        $request->validate([
            'goal'        => ['required', 'string', 'max:35'],
            'detail'      => ['nullable', 'string', 'max:1000'],
            'target_date' => ['nullable', 'date'],
        ]);

        try {
            //データを上書きして保存
            $goal->fill([
                'goal'        => $request->goal,
                'detail'      => $request->detail,
                'target_date' => $request->filled('target_date') ? $request->target_date : null,
            ])->save();

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            //失敗したらログにエラー内容を書き込む
            Log::error('Goal update failed', [
                'goal_id' => $goal->id,
                'error'   => $e->getMessage(),
            ]);
            return response()->json(['success' => false, 'error' => '更新に失敗しました。'], 500);
        }
    }

    //ゴールの新規作成
    public function store(Request $request): JsonResponse
    {
        //入力バリデーション
        $validated = $request->validate([
            'goal'        => ['required', 'string', 'max:35'],
            'detail'      => ['nullable', 'string', 'max:1000'],
            'target_date' => ['nullable', 'date'],
        ]);

        return DB::transaction(function () use ($validated): JsonResponse {
            // SELECT FOR UPDATE でロック（同時リクエスト対策）
            $count = Auth::user()
                ->goals()
                ->where('flg', 0)
                ->lockForUpdate()
                ->count();

            //未完了ゴールは３つまで
            if ($count >= 3) {
                return response()->json(
                    ['success' => false, 'error' => 'ゴールは3つまでです'], 
                    422
                );
            }

            //保存
            $goal = new Goal();
            $goal->user_id    = Auth::id();
            $goal->goal       = $validated['goal'];
            $goal->target_date = $validated['target_date'] ?? null;
            $goal->detail      = $validated['detail'] ?? null;
            $goal->save();

            return response()->json(['success' => true]);
        });
    }

    //ゴールの完了状態（達成）を更新する (Ajax用)
    public function check(Request $request, Goal $goal)
    {
        $request->validate(['flg' => ['required', 'integer', 'in:0,1']]);

        // セキュリティチェック：自分のゴールか確認
        $this->authorize('update', $goal);

        // ガードロジック：未完了のタスクが残っていないかサーバー側でも再確認
        // goalCheck を呼ぶ際（flgを1にしようとしている時）のみチェック
        if ((int)$request->input('flg') === 1) {
            // 1クエリで集計
            $stats = $goal->tasks()
                ->selectRaw('COUNT(*) as total, COALESCE(SUM(flg = 0), 0) as incomplete')
                ->first();

            $total      = (int)($stats->total ?? 0);
            $incomplete = (int)($stats->incomplete ?? 0);

            // A. タスクが1つも登録されていない場合
            if ($total === 0) {
                return response()->json(['success' => false, 'error' => 'タスクが登録されていません。まずはタスクを作成してください。'], 422);
            }

            // B. 未完了のタスクが残っている場合
            if ($incomplete > 0) {
                return response()->json(['success' => false, 'error' => "未完了のタスクが {$incomplete} 件残っています。"], 422);
            }
        }

        // JSから送られてきた flg (1) を保存
        // $request->input('flg') が無い場合はデフォルトで 1 にする
        $goal->flg = (int)$request->input('flg');
        $goal->save();

        return response()->json(['success' => true]);
    }
}