@extends('layouts.app')

@section('title', 'ゴール詳細')

@section('content')
<div class="container" style="max-width: 600px; margin-top: 50px;">
    {{-- ゴール情報 --}}
    <div class="card mb-4">
        <div class="card-body">
            <h2 class="card-title">{{ $goal->goal }}</h2>
            <p class="text-muted">期限: {{ $goal->target_date ?? '未設定' }}</p>
        </div>
    </div>

    {{-- タスク一覧 --}}
    <h3>タスク一覧</h3>
    <ul class="list-group">
        @forelse($goal->tasks as $task)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                {{ $task->task }}
                
                @if($task->flg == 2)
                    <span class="badge bg-success">完了</span>
                @elseif($task->flg == 1)
                    <span class="badge bg-warning text-dark">進行中</span>
                @else
                    <span class="badge bg-secondary">未着手</span>
                @endif
            </li>
        @empty
            <li class="list-group-item">タスクはまだ登録されていません。</li>
        @endforelse
    </ul>

    <div class="mt-4">
        <a href="{{ route('mypage') }}" class="btn btn-outline-primary">マイページへ戻る</a>
    </div>
</div>
@endsection