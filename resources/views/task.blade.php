@extends('layouts.app')
@section('title', 'タスク')
@section('content')

{{-- CSRFトークン --}}
<meta name="csrf-token" content="{{ csrf_token() }}">

<main>
    <h1 style="display: flex; align-items: center; margin-bottom: 0; gap: 8px;">
        <span class="material-symbols-outlined" style="font-size: 1.3em;">check_circle</span>
        タスク
    </h1>
    <div style="display: grid; padding: 0 clamp(10px, 5vw, 30px); gap: clamp(10px, 5vw, 30px);">

        {{-- 左側：ゴール・タスクコンテナ --}}
        <div class="task_container">
            <ul class="task_tab">
                @foreach($goals as $index => $goal)
                    <li class="{{ $index === 0 ? 'active' : '' }}">{{ $goal->goal }}</li>
                @endforeach
            </ul>

            @foreach($goals as $index => $goal)
                <div class="task_ul {{ $index === 0 ? 'active' : '' }}">
                    <a href="{{ route('goals.show', $goal->id) }}"><h3>{{ $goal->goal }}</h3></a>
                    @foreach($goal->tasks as $task)
                        <div class="task_li">
                            <input type="checkbox" id="task_{{ $task->id }}" class="task-check" data-id="{{ $task->id }}" />
                            <a href="#" style="flex-grow: 1;">{{ $task->task }}</a>
                            {{-- 削除アイコン --}}
                            <span class="material-symbols-outlined delete-task" style="cursor: pointer;" data-id="{{ $task->id }}">delete</span>
                        </div>
                    @endforeach
                    <div class="task_li">
                        <div class="circle task_plus">+</div>自分で追加する
                    </div>
                </div>
            @endforeach
        </div>

        {{-- 右側：カレンダーと達成済みリスト --}}
        <div class="task_container2">
                @include('parts.calendar_table') 

            <div class="task_achieved">
                <h3>達成したタスク</h3>
                @foreach($achievedGoals as $goal)
                    <div class="task_li" style="flex-wrap: wrap; transition: all 0.3s ease;">
                        <span class="material-symbols-outlined task_acc_btn" style="cursor: pointer; font-weight: bold;">keyboard_arrow_down</span>
                        <a href="#" style="font-weight: bold;">{{ $goal->goal }}</a>
                        <div class="task_acc_menu">
                            <ul>
                                @foreach($goal->tasks as $task)
                                    <li>{{ $task->task }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        </div>
    </div>
</main>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // 1. タブ切り替え
        const tabs = document.querySelectorAll('.task_tab li');
        const contents = document.querySelectorAll('.task_ul');
        tabs.forEach((tab, i) => {
            tab.addEventListener('click', () => {
                document.querySelector('.task_tab .active')?.classList.remove('active');
                document.querySelector('.task_ul.active')?.classList.remove('active');
                tab.classList.add('active');
                contents[i].classList.add('active');
            });
        });

        // 2. アコーディオン
        document.querySelectorAll('.task_acc_btn').forEach(btn => {
            btn.addEventListener('click', () => {
                btn.closest('.task_li').classList.toggle('open');
            });
        });

        // 3. チェックボックス（Ajax）
        document.querySelectorAll('.task-check').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                updateTaskStatus(this);
            });
        });

        function updateTaskStatus(checkbox) {
            const taskId = checkbox.dataset.id;
            const isCompleted = checkbox.checked;
            const taskElement = checkbox.closest('.task_li');
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch(`/tasks/${taskId}/check`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({ completed: isCompleted })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && isCompleted) {
                    taskElement.style.transition = '0.3s';
                    taskElement.style.opacity = '0';
                    setTimeout(() => { location.reload(); }, 300);
                }
            })
            .catch(error => console.error('Error:', error));
        }
    });
</script>
@endsection