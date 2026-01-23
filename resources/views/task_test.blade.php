@extends('layouts.app')
@section('title', 'タスクのテストページ')
@section('content')

    <main>
        <div style="display: grid; gap: 10px; padding: 10px; background-color: var(--base-color);">

            <div class="task_li">
                <input type="checkbox" id="1" />
                <a style="flex-grow: 1;">〇〇をしよう</a>
                <span class="material-symbols-outlined">delete</span>
            </div>

            <div class="task_li">
                <input type="checkbox" id="2" />
                <a style="flex-grow: 1;">△△をしよう</a>
                <span class="material-symbols-outlined">delete</span>
            </div>

            <div class="task_li">
                <input type="checkbox" id="3" />
                <a style="flex-grow: 1;">□□をしよう</a>
                <span class="material-symbols-outlined">delete</span>
            </div>

            <div class="task_li" style="justify-content: center;">
                <div class="circle" style="width: 24px; height: 24px; background-color: var(--sub-color); color: var(--bg-color); font-weight: bold;">+</div>自分で追加する
            </div>

        </div>
    </main>

@endsection