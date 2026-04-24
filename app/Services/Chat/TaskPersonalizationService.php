<?php
namespace App\Services\Chat;

class TaskPersonalizationService
{
    /* 性格タイプに基づいてタスクの内容をパーソナライズする */
    public function personalizeTasks(array $tasks, ?string $typeKey): array
    {
        if (!$typeKey || strlen($typeKey) < 4) return $tasks;

        // Q1〜Q4の回答を取得 (AかBか)
        $q1 = $typeKey[0]; // 全体 vs 詳細
        $q3 = $typeKey[2]; // 集中 vs 細切れ
        $q4 = $typeKey[3]; // ガチガチ vs 余白

        return array_map(function (string $task) use ($q1, $q3, $q4): string {
            // 例：具体的な計画を立てましょう -> 性格に合わせて書き換え
            if ($task === '具体的な計画を立てましょう') {
                if ($q1 === 'A') return '全体のロードマップを作成する';
                if ($q1 === 'B') return '今日やることを3つだけ書き出す';
            }

            // 例：今日できる一歩を決める -> 性格に合わせて書き換え
            if ($task === '今日できる一歩を決める') {
                if ($q3 === 'B') return '5分で終わる超小型タスクを完了させる';
                if ($q4 === 'A') return '明日のルーティンにこの作業を組み込む';
            }

            return $task;
        }, $tasks);
    }
}