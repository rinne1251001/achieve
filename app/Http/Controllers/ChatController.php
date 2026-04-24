<?php

namespace App\Http\Controllers;

use App\Actions\Chat\HandleAssessmentAction;
use App\Actions\Chat\HandleDislikeInversionAction;
use App\Actions\Chat\HandleTaskOnlyAction;
use App\Actions\Chat\HandleGoalSuggestionAction;
use App\Actions\Chat\HandleSaveProposedGoalAction;
use App\Models\Goal; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ChatController extends Controller
{
    public function __construct(
        private readonly HandleAssessmentAction      $handleAssessment,
        private readonly HandleTaskOnlyAction        $handleTaskOnly,
        private readonly HandleGoalSuggestionAction  $handleGoalSuggestion,
        private readonly HandleDislikeInversionAction $handleDislikeInversion,
        private readonly HandleSaveProposedGoalAction $handleSaveProposedGoal,
    ) {}
    
    public function index()
    {
        $hour = now()->hour;
        if ($hour >= 5 && $hour < 11) {
            $greeting = "おはようございます！";
        } elseif ($hour >= 11 && $hour < 18) {
            $greeting = "こんにちは！";
        } else {
            $greeting = "こんばんは！";
        }

        return view('chat', compact('greeting'));
    }

    public function checkGoalLimit()
    {
        // 未完了（flg = 0）のゴール数をカウント
        $count = Goal::where('user_id', Auth::id())
                                ->where('flg', 0)
                                ->count();

        // 3つ以上なら true を返す
        return response()->json([
            'isLimit' => $count >= 3
        ]);
    }

    // goal_deciding 扱いにすべき全モードを定義
    const GOAL_DECIDING_MODES = ['goal_deciding', 'interest_exist', 'category_selected', 'interest_none'];

    // 既存ゴールがある場合は新規作成しないようにする
    public function saveProposedGoal(Request $request)
    {
        $validated = $request->validate([
            'goal'    => ['required', 'string', 'max:100'],
            'tasks'   => ['nullable', 'array', 'max:20'],
            'tasks.*' => ['string', 'max:100'],
            'mode'    => ['nullable', 'string', Rule::in(array_merge(['task_only'], self::GOAL_DECIDING_MODES))],
        ]);

        return $this->handleSaveProposedGoal->execute($validated, Auth::user());
    }

    public function chat(Request $request)
    {
        $mode = $request->string('mode')->value();

        // assessment_submit は message が配列なので先に処理（string()変換を避ける）
        if ($mode === 'assessment_submit') {
            return $this->runAssessment($request);
        }

        $text       = $request->string('message')->value();
        $index      = $request->integer('index');
        $taskOffset = $request->integer('task_offset');
        $goalId     = $request->integer('goal_id') ?: null;

        // analysis を事前にロードしてクエリを1回に集約
        $user = Auth::user()->loadMissing('analysis');

        return match(true) {
            // アセスメント提出時
            $mode === 'assessment_submit' => $this->runAssessment($request),
            
            // タスク決めモード
            $mode === 'task_only'         => response()->json(
                $this->handleTaskOnly->execute($text, $index, $taskOffset, $user, $goalId)
            ),

            // 興味あり・カテゴリ選択
            in_array($mode, ['interest_exist', 'category_selected']) => response()->json(
                $this->handleGoalSuggestion->execute($text, $index, $taskOffset, $user)
            ),

            // 嫌いなことの反転
            $mode === 'interest_none'     => response()->json(
                $this->handleDislikeInversion->execute($text, $index, $taskOffset)
            ),

            // どれにも当てはまらない場合
            default => response()->json([
                'status'  => 'invalid_flow',
                'message' => '上の選択肢から選んでいただくか、メニューに沿って入力してくださいね。',
            ]),
        };
    }

    private function runAssessment(Request $request): \Illuminate\Http\JsonResponse
    {
        $answers = $request->input('message');

        if (!is_array($answers) || count($answers) !== 4) {
            return response()->json([
                'status'  => 'error',
                'message' => '回答データが不正です。もう一度お試しください。',
            ], 422);
        }

        try {
            $result = $this->handleAssessment->execute($answers);
            return response()->json($result);
        } catch (\InvalidArgumentException | \TypeError $e) {
            return response()->json([
                'status'  => 'error',
                'message' => '回答データが不正です。もう一度お試しください。',
            ], 422);
        } catch (\Exception $e) {
            // DB例外など予期しないエラーもJSONで返す
            \Illuminate\Support\Facades\Log::error('Assessment failed', [
                'error' => $e->getMessage(),
                'user'  => \Illuminate\Support\Facades\Auth::id(),
            ]);
            return response()->json([
                'status'  => 'error',
                'message' => 'サーバーエラーが発生しました。もう一度お試しください。',
            ], 500);
        }
    }
}