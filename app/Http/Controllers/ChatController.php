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

    // 入力を解析して「カテゴリデータ全体」を返すように修正
    public function analyzeInput($text)
    {
        $templates = config('task_templates.categories');
        if (!$templates) return null;
        
        foreach ($templates as $key => $data) {
            // 1. カテゴリ名（name）そのものと一致するか
            if ($text === ($data['name'] ?? '')) return $data;

            // 2. キーワード/類義語の直接一致チェック
            $allKeywords = array_merge($data['keywords'] ?? [], $data['synonyms'] ?? []);
            foreach ($allKeywords as $word) {
                if (mb_strpos($text, $word) !== false) return $data;
            }

            // 3. Levenshtein距離（打ち間違い救済）
            foreach ($allKeywords as $word) {
                if (levenshtein($text, $word) <= 3) return $data;
            }
        }
        return null; 
    }

    public function invertDislike($text)
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

        foreach ($categories as $catKey => $data) {
            foreach ($data['keywords'] as $word) {
                if (mb_strpos($text, $word) !== false) {

                    // 1. inversion設定から最初のゴール案を取得
                    $suggestedGoalTitle = $data['suggested_goals'][0]; 
                    
                    // 2. デフォルトのタスク（検索にヒットしなかった場合用）
                    $finalGoal = $suggestedGoalTitle;
                    $finalTasks = ['まずは具体的な計画を立てる', '必要な情報を集める', '今日できる小さな一歩を決める'];

                    // 3. 【検索ロジック】task_templateの中から、このキーワードに関連するものを探す
                    // カテゴリ名、キーワード、類義語の中に一致があるかループ
                    foreach ($taskTemplates as $tKey => $tData) {
                        $searchPool = array_merge(
                            [$tData['name']], 
                            $tData['keywords'] ?? [], 
                            $tData['synonyms'] ?? []
                        );

                        foreach ($searchPool as $poolWord) {
                            // 提案されたゴール文字列の中に、テンプレートの単語が含まれているか、
                            // または、価値観名(value_name)に関連するかで検索
                            if (mb_strpos($suggestedGoalTitle, $poolWord) !== false || mb_strpos($data['value_name'], $poolWord) !== false) {
                                if (isset($tData['suggestions'][0])) {
                                    $finalGoal = $tData['suggestions'][0]['goal'];
                                    $finalTasks = $tData['suggestions'][0]['tasks'];
                                    break 2; // ヒットしたら2重ループを抜ける
                                }
                            }
                        }
                    }

                    // 4. 価値観メッセージをセットして返す
                    return [
                        'status' => 'success', // JS側の保存・表示処理を共通化するためにsuccessにする
                        'goal' => $finalGoal,
                        'tasks' => $finalTasks,
                        'message' => str_replace(':value', $data['value_name'], $config['messages']['found']),
                        'has_more' => false
                    ];
                }
            }
        }

        return [
            'status' => 'not_found',
            'message' => $config['messages']['not_found']
        ];
    }

    //ゴールタスク保存
    public function saveProposedGoal(Request $request)
    {
        $goalTitle = $request->input('goal');
        $tasks = $request->input('tasks');

        // 1. ゴールの保存（Userモデルとのリレーションがある前提）
        $goal = Auth::user()->goals()->create([
            'goal' => $goalTitle,
            'flg'  => 0,
        ]);

        // 2. タスクの保存
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

    // メインの処理口
    public function chat_test2(Request $request)
    {
        $text = $request->input('message');
        $mode = $request->input('mode');
        $index = (int)$request->input('index', 0); // 何番目の案かを取得

        if ($mode === 'task_only' && $text !== 'タスクを決める') {
            $goal = \App\Models\Goal::where('user_id', \Auth::id())
                ->where('goal', $text)
                ->where('flg', 0)
                ->first();

            if ($goal) {
                $categoryData = $this->analyzeInput($text);
                
                if ($categoryData && isset($categoryData['suggestions'])) {
                    $suggestions = $categoryData['suggestions'];
                    $idx = 0; // 最初は0番目を出す
                    
                    return response()->json([
                        'status' => 'success',
                        'goal' => $suggestions[$idx]['goal'],
                        'tasks' => $suggestions[$idx]['tasks'],
                        'has_more' => isset($suggestions[$idx + 1]),
                        'current_index' => $idx
                    ]);
                } else {
                    // 追加：テンプレートにない独自のゴールのための返答
                    return response()->json([
                        'status' => 'success',
                        'goal' => $text,
                        'tasks' => ['まずは具体的な計画を立てましょう', '必要な道具を揃える', '今日できる一歩を決める'],
                        'has_more' => false
                    ]);
                }
            }
        }

        if ($mode === 'task_only' && $text === 'タスクを決める') {
            $goals = \App\Models\Goal::where('user_id', \Auth::id())
                ->where('flg', 0) // 未完了のゴールのみ
                ->get(['id', 'goal']); // idと目標名のみ取得

            return response()->json([
                'status' => 'goal_list',
                'goals' => $goals
            ]);
        }
        
        // 1. 「嫌いなこと」を探すフェーズ
        if ($mode === 'interest_none') {
            return response()->json($this->invertDislike($text));
        } 
        
        // 2. 通常のカテゴリ選択・提案フェーズ
        $categoryData = $this->analyzeInput($text);

        if ($categoryData && isset($categoryData['suggestions'])) {
            // suggestions配列があるかチェック（新しい構造）
            if (isset($categoryData['suggestions'])) {
                $suggestions = $categoryData['suggestions'];
                
                if (isset($suggestions[$index])) {
                    return response()->json([
                        'status' => 'success',
                        'goal' => $suggestions[$index]['goal'],
                        'tasks' => $suggestions[$index]['tasks'],
                        'has_more' => isset($suggestions[$index + 1]), // 次の案があるか
                        'current_index' => $index
                    ]);
                } else {
                    return response()->json([
                        'status' => 'no_more',
                        'message' => 'このカテゴリの提案は以上です！他のカテゴリも見てみますか？'
                    ]);
                }
            } 
            
            // 旧構造（goalが直下にある場合）の救済策
            if (isset($categoryData['goal'])) {
                return response()->json([
                    'status' => 'success',
                    'goal' => $categoryData['goal'],
                    'tasks' => $categoryData['tasks'],
                    'has_more' => false
                ]);
            }
        }

        // 3. どちらにもヒットしなければ「わからない」
        return response()->json([
            'status' => 'unknown',
            'message' => config('task_templates.responses.unknown', 'すみません、うまく聞き取れませんでした。')
        ]);
    }
}