<?php

namespace App\Actions\Chat;

use App\Models\UserAnalysis;
use App\Services\UserAnalysisService;
use Illuminate\Support\Facades\Auth;

class HandleAssessmentAction
{
    public function __construct(
        private UserAnalysisService $analysisService
    ) {}

    /**
     * @throws \InvalidArgumentException
     */
    public function execute(array $answers): array
    {
        // 例外はコントローラ側でcatchする。ここではthrowするだけ
        $result = $this->analysisService->analyze($answers);

        $bitValue = 0;
        foreach ($answers as $i => $answer) {
            if ($answer === 'B') {
                $bitValue |= (1 << (3 - $i));
            }
        }

        UserAnalysis::updateOrCreate(
            ['user_id' => Auth::id()],
            ['type_key' => $result['type_key'], 'type_bit' => $bitValue]
        );

        return [
            'status'  => 'success',
            'message' => $result['message'],
            'type'    => $result['type_name'],
        ];
    }
}