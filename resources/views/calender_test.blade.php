@extends('layouts.app')

@section('content')
<div class="container" style="margin-top: 30px;">
    <h1>タスクスケジュール</h1>
    <hr>
    
    {{-- 月の切り替えナビゲーション --}}
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <a href="{{ route('calendar', ['year' => $prev->year, 'month' => $prev->month]) }}" style="text-decoration: none;">&lt; 前の月</a>
        <h2 style="margin: 0;">{{ $monthTitle }}</h2>
        <a href="{{ route('calendar', ['year' => $next->year, 'month' => $next->month]) }}" style="text-decoration: none;">次の月 &gt;</a>
    </div>
    
    <table border="1" style="width: 100%; border-collapse: collapse; text-align: center;">
        <thead>
            <tr style="background-color: #f8f9fa;">
                <th style="color: red; width: 14%;">日</th>
                <th style="width: 14%;">月</th>
                <th style="width: 14%;">火</th>
                <th style="width: 14%;">水</th>
                <th style="width: 14%;">木</th>
                <th style="width: 14%;">金</th>
                <th style="color: blue; width: 14%;">土</th>
            </tr>
        </thead>
        <tbody>
            <tr>
               @foreach($calendarDates as $key => $date)
    @if($key > 0 && $key % 7 == 0)
        </tr><tr>
    @endif

    @php
        $dateStr = $date->format('Y-m-d');
        $daysTasks = $taskMap[$dateStr] ?? [];
        $isCurrentMonth = $date->month == $dt->month;
        $hasTasks = count($daysTasks) > 0;
    @endphp

    <td style="height: 100px; width: 14%; vertical-align: top; padding: 0; position: relative; {{-- position: relative が重要 --}}
        {{ $hasTasks ? 'background-color: #fff0f0;' : '' }}
        {{ !$isCurrentMonth ? 'color: #ccc; background-color: #fcfcfc;' : '' }}">
        
        <div style="padding: 5px;">{{ $date->day }}</div>

        @if($hasTasks && $isCurrentMonth)
            {{-- マウスを合わせる対象の目印 --}}
            <div class="task-trigger" style="color: red; font-size: 0.8em; cursor: pointer; text-align: center;">
            </div>

            {{-- ホバー時に表示されるメニュー（CSSの .task-menu で制御） --}}
            <div class="task-menu">
                <div style="font-weight: bold; font-size: 0.8em; margin-bottom: 5px; border-bottom: 1px solid #eee;">
                    {{ $date->format('n/j') }} のタスク
                </div>
                <ul style="list-style: none; padding: 0; margin: 0; text-align: left;">
                    @foreach($daysTasks as $task)
                        <li style="margin-bottom: 5px;">
                            <a href="{{ route('goals.show', $task->goal_id) }}" 
                               style="font-size: 0.8em; color: #007bff; text-decoration: none; display: block;">
                                ・{{ $task->task }}
                                <div style="font-size: 0.7em; color: #666; padding-left: 8px;">({{ $task->goal->goal }})</div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </td>
@endforeach
            </tr>
        </tbody>
    </table>

    <div style="margin-top: 20px;">
        <a href="{{ route('mypage') }}">マイページへ戻る</a>
    </div>
</div>

<style>
    /* 通常時は非表示 */
    .task-menu {
        display: none;
        position: absolute;
        bottom: 100%;       /* マスの少し下から表示 */
        left: 10%;
        width: 200px;   /* ポップアップの幅 */
        background: white;
        border: 1px solid #ccc;
        box-shadow: 0px -2px 10px rgba(0,0,0,0.2);
        z-index: 100;    /* 他のマスより上に表示 */
        padding: 10px;
        border-radius: 5px;
        margin-bottom: 0px;
    }

    /* マス(td)にマウスが乗ったら .task-menu を表示 */
    td:hover .task-menu {
        display: block;
    }

    /* マウスが乗った時に少し色を変えて分かりやすくする */
    td:hover {
        /*background-color: #ffe0e0 !important;*/
    }

    /* リンクのホバー時 */
    .task-menu a:hover {
        text-decoration: underline !important;
        background-color: #f0f7ff;
    }
</style>
@endsection