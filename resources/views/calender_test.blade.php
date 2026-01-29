@extends('layouts.app')
@section('content')
<div class="container" style="margin-top: 30px;">
    <h1>タスクスケジュール</h1>
    <hr>
@include('parts.calendar_table') {{-- カレンダーパーツ呼び出し --}}

    <div style="margin-top: 20px;">
        <a href="{{ route('mypage') }}">マイページへ戻る</a>
    </div>

@endsection