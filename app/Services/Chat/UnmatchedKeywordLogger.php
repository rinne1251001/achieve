<?php

namespace App\Services\Chat;

use Illuminate\Support\Facades\Log;

class UnmatchedKeywordLogger
{
    private string $path;

    public function __construct()
    {
        $this->path = storage_path('app/unmatched_keywords.json');
    }

    /**
     * @param string $input  ユーザーの入力テキスト
     * @param string $source 'task_templates' | 'goal_inversion'
     */
    public function log(string $input, string $source): void
    {
        try {
            $data = $this->read();
            $key  = md5($input . $source);

            if (isset($data[$key])) {
                $data[$key]['count']++;
                $data[$key]['last_seen'] = now()->toDateTimeString();
            } else {
                $data[$key] = [
                    'input'      => $input,
                    'source'     => $source,
                    'count'      => 1,
                    'first_seen' => now()->toDateTimeString(),
                    'last_seen'  => now()->toDateTimeString(),
                    'added'      => false, // 設定ファイルへの追記済みフラグ
                ];
            }

            file_put_contents(
                $this->path,
                json_encode(array_values($data), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
            );
        } catch (\Throwable $e) {
            // ロギング失敗がメイン処理を止めないようにする
            Log::warning('UnmatchedKeywordLogger failed', ['error' => $e->getMessage()]);
        }
    }

    public function read(): array
    {
        if (!file_exists($this->path)) {
            return [];
        }
        $decoded = json_decode(file_get_contents($this->path), true);

        // キーでアクセスできるよう md5 をキーに再構築
        $result = [];
        foreach ($decoded ?? [] as $item) {
            $result[md5($item['input'] . $item['source'])] = $item;
        }
        return $result;
    }
}