@extends('layouts.app')
@section('title', $goal->goal)
@section('content')

<div>
    <div>
        <div>
            <h2>{{ $goal->goal }}</h2>
            <p>期限: {{ $goal->target_date ?? '未設定' }}</p>
        </div>
    </div>

    <h3>タスク一覧</h3>
    <ul>
        @forelse($goal->tasks as $task)
            <li>
                {{ $task->task }}
                
                @if($task->flg == true)
                    <span class="badge bg-success">完了</span>
                @else
                    <span class="badge bg-secondary">進行中</span>
                @endif
            </li>
        @empty
            <li>タスクはまだ登録されていません。</li>
        @endforelse
    </ul>


    <div>
        <a href="{{ route('mypage') }}">タスク一覧に戻る</a>
    </div>
</div>



<div style="display: flex; flex-direction: column; gap: 0;">

    <div style="display: flex; align-items: stretch;">
        <div style="display: flex; flex-direction: column; align-items: center; width: 70px;">
            <div style="background-color: var(--accent-color); width: 70px; height: 70px; border-radius: 50%; display: flex; align-items: center; justify-content: center; position: relative; flex-shrink: 0;">
                <div style="background-color: var(--bg-color); width: 50px; height: 50px; border-radius: 50%; position: absolute;"></div>
                <svg width="30" height="30" style="position: relative; z-index: 1; color: var(--accent-color);"><use xlink:href="#check"></use></svg>
            </div>
            <div style="background-color: var(--accent-color); width: 4px; flex-grow: 1; min-height: 30px;"></div>
        </div>
        <div style="padding: 20px 0 0 15px;">●●をしよう</div>
    </div>

    <div style="display: flex; align-items: stretch;">
        <div style="display: flex; flex-direction: column; align-items: center; width: 70px;">
            <div style="background-color: var(--accent-color); width: 70px; height: 70px; border-radius: 50%; display: flex; align-items: center; justify-content: center; position: relative; flex-shrink: 0;">
                <div style="background-color: var(--bg-color); width: 50px; height: 50px; border-radius: 50%; position: absolute;"></div>
                <svg width="30" height="30" style="position: relative; z-index: 1; color: var(--accent-color);"><use xlink:href="#check"></use></svg>
            </div>
            <div style="background-color: var(--accent-color); width: 4px; flex-grow: 1; min-height: 30px;"></div>
        </div>
        <div style="padding: 20px 0 0 15px;">△△をしよう</div>
    </div>

    <div style="display: flex; align-items: stretch;">
        <div style="display: flex; flex-direction: column; align-items: center; width: 70px;">
            <div style="background-color: var(--accent-color); width: 70px; height: 70px; border-radius: 50%; display: flex; align-items: center; justify-content: center; position: relative; flex-shrink: 0;">
                <div style="background-color: var(--bg-color); width: 50px; height: 50px; border-radius: 50%; position: absolute;"></div>
                <svg width="30" height="30" style="position: relative; z-index: 1; color: var(--accent-color);"><use xlink:href="#check"></use></svg>
            </div>
            <div style="background-color: var(--font-light-color); width: 4px; flex-grow: 1; min-height: 30px;"></div>
        </div>
        <div style="padding: 20px 0 0 15px;">◇◇をしよう</div>
    </div>

    <div style="display: flex; align-items: flex-start;">
        <div style="display: flex; flex-direction: column; align-items: center; width: 70px;">
            <div style="background-color: var(--font-light-color); width: 70px; height: 70px; border-radius: 50%; display: flex; align-items: center; justify-content: center; position: relative; flex-shrink: 0;">
                <div style="background-color: var(--bg-color); width: 50px; height: 50px; border-radius: 50%; position: absolute;"></div>
                <svg width="30" height="30" style="position: relative; z-index: 1; color: var(--font-light-color);"><use xlink:href="#check"></use></svg>
            </div>
        </div>
        <div style="padding: 20px 0 0 15px;">■■をしよう</div>
    </div>

</div>

@endsection
@push('scripts')
<svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
    <symbol id="check" viewBox="-30 140 490 200">
        <path d="M438 38l3.1 1.7c9 7 14 15 16 26.3 1 18.5-10.6 30.8-21 45l-1.7 2.4a7813 7813 0 01-14 19c-7 9.2-13.7 18.5-20.4 27.8L386 179a1663 1663 0 00-11.7 16 2065 2065 0 01-15.3 21 1663 1663 0 00-11.7 16 2065 2065 0 01-15.3 21A1666 1666 0 00320.5 268.9c-7.3 10.2-14.8 20.3-22.3 30.4L278 327.2 264 346a1663 1663 0 00-11.5 16 2064.7 2064.7 0 01-15.3 21 1666 1666 0 00-11.7 16l-18.7 25.6-5.6 7.5-1.8 2.7a4946.4 4946.4 0 00-3.5 4.7C179.2 462 179.2 462 165 465.8c-10.4.8-19.7.9-28.5-5.4a114.6 114.6 0 01-6-5.8l-6-6c-12.7-12.4-12.7-12.4-17-17.4-5-5.9-10.7-11.2-16.2-16.7-12.3-12-12.3-12-16-16.5-5-6-10.9-11.4-16.5-17-12.8-12.6-12.8-12.6-18-18.9-4.8-5.1-10-10-15-15-12.8-12.6-12.8-12.6-16.5-17-3-3.6-6.3-6.9-9.7-10.2l-1.6-1.7-7-6.7c-21.5-21.1-21.5-21.1-22.3-38a39 39 0 0111.7-27.1 37.6 37.6 0 0150.6.5l10.3 7.6 2.4 1.6 17.6 12.5 17.7 12.6a1528 1528 0 0017.3 12.5 923.5 923.5 0 0041.3 29l5.8 4.3c4.3 3 7.2 3.1 12.2 2.2 6.1-2.7 9.7-8.3 13.7-13.4 2.2-2.8 4.6-5.5 6.9-8.2a508.8 508.8 0 0011.3-13.8c2.3-3 4.8-6 7.3-8.8a366 366 0 0011-13.7 366 366 0 0114-16.6A546 546 0 00234 238a925 925 0 0114.4-17.3 370.2 370.2 0 0011-13.6c4.4-5.6 9-11 13.8-16.4a268.2 268.2 0 0010.3-12.6 352 352 0 0113.8-16.6c4.7-5.4 9.2-10.9 13.7-16.4a925 925 0 0114.4-17.3 370.2 370.2 0 0011-13.6c4.4-5.6 9-11 13.8-16.4A268.2 268.2 0 00360.5 85c4.4-5.7 9-11.2 13.8-16.6a374.3 374.3 0 009.5-11.2c14-17.3 31-31.7 54.2-19.2z" fill="currentColor"/>
    </symbol>
</svg>
@endpush