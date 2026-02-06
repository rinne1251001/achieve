<?php

namespace App\Services;

class UserAnalysisService
{
    /**
     * 回答配列 ['A', 'B', 'A', 'A'] から診断メッセージを生成する
     */
    public function analyze($answers)
    {
        $config = config('user_analysis');
        $typeKey = implode('', $answers); // 例: "AABA"

        // 1. タイプ名の決定
        $typeName = $config['types'][$typeKey] ?? $config['types']['default'];

        // 2. 共感・分析とアドバイスの組み立て
        $analysisParts = [];
        $adviceParts = [];

        foreach ($answers as $index => $answer) {
            $qKey = 'Q' . ($index + 1);
            // configの階層：traits > Q1 > options > A > analysis
            $analysisParts[] = $config['traits'][$qKey]['options'][$answer]['analysis'] ?? '';
            $adviceParts[] = $config['traits'][$qKey]['options'][$answer]['advice'] ?? '';
        }

        // 3. レスポンスデータの構築
        return [
            'type_key' => $typeKey,
            'type_name' => $typeName,
            'message' => "診断結果、あなたは…\n【{$typeName}】タイプです！\n\n" . 
                         implode(' ', $analysisParts) . "\n\n" .
                         "そんなあなたへのアドバイス：\n" . 
                         implode(' ', $adviceParts),
            'style_hints' => [
                'is_micro_task' => ($answers[2] === 'B'), // Q3がBなら細切れタスク推奨
                'needs_fixed_schedule' => ($answers[3] === 'A'), // Q4がAならルーティン推奨
            ]
        ];
    }
}