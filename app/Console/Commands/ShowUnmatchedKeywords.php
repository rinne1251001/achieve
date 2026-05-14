<?php

namespace App\Console\Commands;

use App\Services\Chat\UnmatchedKeywordLogger;
use Illuminate\Console\Command;

class ShowUnmatchedKeywords extends Command
{
    protected $signature   = 'keywords:unmatched {--min-count=1 : 最低ヒット数}';
    protected $description = '未マッチキーワードを頻度順に表示する';

    public function handle(UnmatchedKeywordLogger $logger): void
    {
        $items = array_values($logger->read());
        $min   = (int) $this->option('min-count');

        $items = array_filter($items, fn($i) => $i['count'] >= $min && !$i['added']);
        usort($items, fn($a, $b) => $b['count'] <=> $a['count']);

        if (empty($items)) {
            $this->info('未処理の未マッチキーワードはありません。');
            return;
        }

        $this->table(
            ['入力', 'ソース', '回数', '最終'],
            array_map(fn($i) => [
                $i['input'],
                $i['source'],
                $i['count'],
                $i['last_seen'],
            ], $items)
        );
    }
}