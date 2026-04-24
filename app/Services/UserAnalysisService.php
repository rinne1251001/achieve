<?php

namespace App\Services;

class UserAnalysisService
{
    /**
     * 回答配列 ['A', 'B', 'A', 'A'] から診断メッセージを生成する
     */
    public function analyze(array $answers): array
    {
        if (count($answers) !== 4) {
            throw new \InvalidArgumentException('回答は4問分の配列が必要です');
        }

        $validOptions = ['A', 'B'];
        foreach ($answers as $i => $answer) {
            if (!in_array($answer, $validOptions, true)) {
                throw new \InvalidArgumentException("Q" . ($i + 1) . "の回答が不正です: {$answer}");
            }
        }

        $normalizedAnswers = array_slice(array_values($answers), 0, 4);

        $config   = config('user_analysis');
        $typeKey  = implode('', $normalizedAnswers);
        $typeName = $config['types'][$typeKey] ?? $config['types']['default'];

        $analysisParts = [];
        $adviceParts   = [];

        foreach ($normalizedAnswers as $index => $answer) {
            $qKey            = 'Q' . ($index + 1);
            $analysisParts[] = $config['traits'][$qKey]['options'][$answer]['analysis'] ?? '';
            $adviceParts[]   = $config['traits'][$qKey]['options'][$answer]['advice']   ?? '';
        }

        return [
            'type_key'    => $typeKey,
            'type_name'   => $typeName,
            'message'     => "診断結果、あなたは…\n【{$typeName}】タイプです！\n\n"
                            . implode(' ', $analysisParts) . "\n\n"
                            . "そんなあなたへのアドバイス：\n"
                            . implode(' ', $adviceParts),
            'style_hints' => [
                'is_micro_task'       => ($normalizedAnswers[2] === 'B'),
                'needs_fixed_schedule'=> ($normalizedAnswers[3] === 'A'),
            ],
        ];
    }
}