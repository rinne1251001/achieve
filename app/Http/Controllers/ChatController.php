<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Goal;

class ChatController extends Controller
{
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

        return view('chat_test2', compact('greeting'));
    }

    public function analyzeInput($text)
    {
        $templates = config('task_templates.categories');
        if (!$templates) return null;
        
        foreach ($templates as $key => $data) {
            if ($text === ($data['name'] ?? '')) return $data;
            $allKeywords = array_merge($data['keywords'] ?? [], $data['synonyms'] ?? []);
            foreach ($allKeywords as $word) {
                if (mb_strpos($text, $word) !== false) return $data;
            }
            foreach ($allKeywords as $word) {
                if (levenshtein($text, $word) <= 3) return $data;
            }
        }
        return null; 
    }

    // ★修正箇所: index引数を追加し、候補リストから1つ選んで返すように変更
    public function invertDislike($text, $index = 0)
    {
        $config = include(config_path('goal_inversion.php'));
        $categories = $config['categories'];
        $taskTemplates = config('task_templates.categories');

        $no_dislike_words = ['ない', 'なし', '特にない', '嫌いなことはない', 'わからない'];
        foreach ($no_dislike_words as $word) {
            if (mb_strpos($text, $word) !== false) {
                return [
                    'status' => 'no_dislike',
                    'message' => $config['messages']['nothing_disliked']
                ];
            }
        }

        // 候補を一時保存する配列
        $candidates = [];

        foreach ($categories as $catKey => $data) {
            foreach ($data['keywords'] as $word) {
                if (mb_strpos($text, $word) !== false) {
                    
                    // そのカテゴリの全てのゴール案を候補に入れる
                    foreach ($data['suggested_goals'] as $sGoal) {
                        
                        // タスク検索ロジック
                        $tasks = ['まずは具体的な計画を立てる', '必要な情報を集める', '今日できる小さな一歩を決める'];
                        foreach ($taskTemplates as $tKey => $tData) {
                            $searchPool = array_merge([$tData['name']], $tData['keywords'] ?? [], $tData['synonyms'] ?? []);
                            foreach ($searchPool as $poolWord) {
                                if (mb_strpos($sGoal, $poolWord) !== false || mb_strpos($data['value_name'], $poolWord) !== false) {
                                    if (isset($tData['suggestions'][0])) {
                                        $tasks = $tData['suggestions'][0]['tasks'];
                                        break 2;
                                    }
                                }
                            }
                        }

                        $candidates[] = [
                            'goal' => $sGoal,
                            'tasks' => $tasks,
                            'message' => str_replace(':value', $data['value_name'], $config['messages']['found'])
                        ];
                    }
                    // 1つのカテゴリがヒットしたら、そのカテゴリ内のゴールを全て候補にしてループを抜ける
                    break 2; 
                }
            }
        }

        // インデックスに応じた候補を返す
        if (isset($candidates[$index])) {
            return [
                'status' => 'success', // JS側と合わせるため success に統一
                'goal' => $candidates[$index]['goal'],
                'tasks' => $candidates[$index]['tasks'],
                'message' => $candidates[$index]['message'],
                'has_more' => isset($candidates[$index + 1]) // 次があるか判定
            ];
        }

        if (!empty($candidates)) {
            return ['status' => 'no_more', 'message' => 'この方向性での提案は以上です。'];
        }

        return [
            'status' => 'not_found',
            'message' => $config['messages']['not_found']
        ];
    }

    // ★修正箇所: 既存ゴールがある場合は新規作成しないようにする
    public function saveProposedGoal(Request $request)
    {
        $goalTitle = $request->input('goal');
        $tasks = $request->input('tasks');
        $mode = $request->input('mode'); // JSから受け取る

        $user = Auth::user();
        $goal = null;

        // タスク決めモードなら、既存のゴールを探す
        if ($mode === 'task_only') {
            $goal = $user->goals()
                         ->where('goal', $goalTitle)
                         ->where('flg', 0)
                         ->first();
        }

        // ゴールがない（新規モード、または見つからない）場合は作成
        if (!$goal) {
            // firstOrCreate を使うと重複登録も防げます
            $goal = $user->goals()->firstOrCreate(
                ['goal' => $goalTitle, 'flg' => 0]
            );
        }

        if (!empty($tasks)) {
            foreach ($tasks as $taskContent) {
                $goal->tasks()->create([
                    'task' => $taskContent,
                    'flg'  => 0,
                ]);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => '保存完了しました'
        ]);
    }

    /* 性格タイプに基づいてタスクの内容をパーソナライズする */
    private function personalizeTasks($tasks, $typeKey)
    {
        if (!$typeKey) return $tasks;

        $personalized = [];
        // Q1〜Q4の回答を取得 (AかBか)
        $q1 = $typeKey[0] ?? 'A'; // 全体 vs 詳細
        $q3 = $typeKey[2] ?? 'A'; // 集中 vs 細切れ
        $q4 = $typeKey[3] ?? 'A'; // ガチガチ vs 余白

        foreach ($tasks as $task) {
            $tempTask = $task;

            // 例：具体的な計画を立てましょう -> 性格に合わせて書き換え
            if ($task === '具体的な計画を立てましょう') {
                if ($q1 === 'A') $tempTask = '全体のロードマップを作成する';
                if ($q1 === 'B') $tempTask = '今日やることを3つだけ書き出す';
            }

            // 例：今日できる一歩を決める -> 性格に合わせて書き換え
            if ($task === '今日できる一歩を決める') {
                if ($q3 === 'B') $tempTask = '5分で終わる超小型タスクを完了させる';
                if ($q4 === 'A') $tempTask = '明日のルーティンにこの作業を組み込む';
            }
            
            // 語尾の調整（戦略家っぽく or 自由人っぽく）
            if ($q1 === 'A' && $q4 === 'A') {
                $tempTask .= '（計画通りに進めましょう）';
            } else if ($q1 === 'B' && $q4 === 'B') {
                $tempTask .= '（まずは気楽にどうぞ！）';
            }

            $personalized[] = $tempTask;
        }

        return $personalized;
    }

    public function chat_test2(Request $request)
    {
        $text = $request->input('message');
        $mode = $request->input('mode');
        $index = (int)$request->input('index', 0);
        $message = $request->input('message');
        $taskOffset = (int)$request->input('task_offset', 0);

        if ($mode === 'assessment_submit') {
            $analysisService = new \App\Services\UserAnalysisService();
            // $request->input('message') が ['A', 'B', ...] という配列で届く想定
            $answers = $request->input('message'); 
            $result = $analysisService->analyze($answers);

            $bitValue = 0;
            if (is_array($answers)) {
                foreach ($answers as $index => $answer) {
                    if ($answer === 'B') {
                        // Q1なら 2^3(8), Q2なら 2^2(4)... を足していく
                        $bitValue |= (1 << (3 - $index));
                    }
                }

                // ログイン中のユーザーの性格診断データを更新、なければ作成
                \App\Models\UserAnalysis::updateOrCreate(
                    ['user_id' => \Auth::id()],
                    [
                        'type_key' => $result['type_key'], // 'ABAB' など
                        'type_bit' => $bitValue,           // 5 など
                    ]
                );
            }

            return response()->json([
                'status' => 'success',
                'message' => $result['message'],
                'type' => $result['type_name'],
            ]);
        }

        if ($mode === 'task_only' && $text !== 'タスクを決める') {
            $goal = \App\Models\Goal::where('user_id', \Auth::id())
                ->where('goal', $text)
                ->where('flg', 0)
                ->first();

            if ($goal) {
                $categoryData = $this->analyzeInput($text);

                $userAnalysis = Auth::user()->analysis;
                $typeKey = $userAnalysis ? $userAnalysis->type_key : null;
                
                if ($categoryData && isset($categoryData['suggestions'])) {
                    $suggestions = $categoryData['suggestions'];
                    
                    if (isset($suggestions[$index])) {
                        $aiMessage = ($index === 0) 
                            ? "「{$text}」ですね。では、こんなアプローチはいかがでしょう？" 
                            : "承知いたしました。では、こちらはいかがでしょうか？";

                        $originalTasks = $suggestions[$index]['tasks']; 
                        $allPersonalizedTasks = $this->personalizeTasks($originalTasks, $typeKey);
                        $displayTasks = array_slice($allPersonalizedTasks, $taskOffset, 3);
                        $hasMoreTasks = isset($allPersonalizedTasks[$taskOffset + 3]);

                        return response()->json([
                            'status' => 'success',
                            'goal' => $text, // ★ここを修正：テンプレートのゴール名ではなく、元のゴール名($text)を使う
                            'tasks' => $displayTasks,
                            'message' => $aiMessage,
                            'has_more' => $hasMoreTasks,
                            'current_index' => $index,
                            'task_offset' => $taskOffset
                        ]);
                    } else {
                        return response()->json(['status' => 'no_more', 'message' => '提案は以上です。']);
                    }
                } else {
                    $defaultTasks = ['具体的な計画を立てましょう', '必要な道具を揃える', '今日できる一歩を決める'];
                    $personalizedTasks = $this->personalizeTasks($defaultTasks, $typeKey);

                    return response()->json([
                        'status' => 'success',
                        'goal' => $text,
                        'tasks' => $personalizedTasks,
                        'has_more' => false
                    ]);
                }
            }
        }

        if ($mode === 'task_only' && $text === 'タスクを決める') {
            $goals = \App\Models\Goal::where('user_id', \Auth::id())
                ->where('flg', 0)
                ->get(['id', 'goal']);

            return response()->json([
                'status' => 'goal_list',
                'goals' => $goals
            ]);
        }
        
        // 1. 「嫌いなこと」を探すフェーズ（引数に $index を渡す！）
        if ($mode === 'interest_none') {
            $result = $this->invertDislike($text, $index);
            if (isset($result['tasks'])) {
                $allTasks = $result['tasks'];
                $result['tasks'] = array_slice($allTasks, $taskOffset, 3);
                $result['has_more'] = isset($allTasks[$taskOffset + 3]);
                $result['task_offset'] = $taskOffset;
            }
            return response()->json($result);
        } 
        
        // 2. 通常のカテゴリ選択
        $categoryData = $this->analyzeInput($text);

        if ($categoryData && isset($categoryData['suggestions'])) {

            $suggestions = $categoryData['suggestions'];
            
            if (isset($suggestions[$index])) {
                $aiMessage = "いいですね！「{$text}」に関連して、こんなゴールはいかがでしょうか？";
                if ($index > 0) {
                    $aiMessage = "承知いたしました。では、こちらの案はどうでしょう？";
                }

                $userAnalysis = Auth::user()->analysis;
                $typeKey = $userAnalysis ? $userAnalysis->type_key : null;
                
                $originalTasks = $suggestions[$index]['tasks']; 
                $allPersonalizedTasks = $this->personalizeTasks($originalTasks, $typeKey);
                
                $displayTasks = array_slice($allPersonalizedTasks, $taskOffset, 3);
                $hasMoreGoals = isset($suggestions[$index + 1]);

                return response()->json([
                    'status' => 'success',
                    'goal' => $suggestions[$index]['goal'],
                    'tasks' => $displayTasks,
                    'message' => $aiMessage,
                    'has_more' => $hasMoreGoals,
                    'current_index' => $index
                ]);
            } else {
                return response()->json(['status' => 'no_more', 'message' => 'このカテゴリの提案は以上です！']);
            }
        } 
        
        if (isset($categoryData['goal'])) {
            return response()->json([
                'status' => 'success',
                'goal' => $categoryData['goal'],
                'tasks' => $categoryData['tasks'],
                'has_more' => false
            ]);
        }

        return response()->json([
            'status' => 'unknown',
            'message' => config('task_templates.responses.unknown', 'すみません、うまく聞き取れませんでした。')
        ]);
    }
}