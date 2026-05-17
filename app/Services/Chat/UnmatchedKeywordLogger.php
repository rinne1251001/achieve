<?php

// app/Services/Chat/UnmatchedKeywordLogger.php

namespace App\Services\Chat;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UnmatchedKeywordLogger
{
    private string $filename = 'unmatched_keywords.json';

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
                    'added'      => false,
                ];
            }

            Storage::put(
                $this->filename,
                json_encode(array_values($data), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
            );
        } catch (\Throwable $e) {
            Log::warning('UnmatchedKeywordLogger failed', ['error' => $e->getMessage()]);
        }
    }

    public function read(): array
    {
        if (!Storage::exists($this->filename)) {
            return [];
        }

        $decoded = json_decode(Storage::get($this->filename), true);

        $result = [];
        foreach ($decoded ?? [] as $item) {
            if (isset($item['input'], $item['source'])) {
                $result[md5($item['input'] . $item['source'])] = $item;
            }
        }
        return $result;
    }
}