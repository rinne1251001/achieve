@extends('layouts.app')
@section('title', 'トップページ')
@section('content')

    <div class="top_container" style="background-color: var(--bg-color);">
        <div>イラスト</div>
        <div>
            <p>小さな「できた！」を大きな自信に</p>
            @auth
                <a href="{{ route('chat_test') }}">はじめる</a>
                <a href="{{ route('task') }}">タスク</a>
            @endauth
            @guest
                <a href="{{ route('register') }}">はじめる</a>
                <a href="{{ route('login') }}">ログイン</a>
            @endguest
        </div>
    </div>

    <section class="top_story_section">
        <div class="top_story_trigger" data-index="0"></div>
        <div class="top_story_trigger" data-index="1"></div>
        <div class="top_story_trigger" data-index="2"></div>

        <div class="top_story_wrapper">
            <div class="top_container top_story" style="background-color: var(--base-color); z-index: 3;">
                <div>イラスト</div>
                <div style="color: var(--bg-color)">説明</div>
            </div>
            <div class="top_container top_story" style="background-color: var(--accent-color); z-index: 2;">
                <div>イラスト</div>
                <div style="color: var(--bg-color)">説明</div>
            </div>
            <div class="top_container top_story" style="background-color: var(--sub-color); z-index: 1;">
                <div>イラスト</div>
                <div style="color: var(--bg-color)">説明</div>
            </div>
        </div>
    </section>

    <div class="top_container" style="background-color: var(--bg-color);">
        <div>イラスト</div>
        <div>
            <p>小さな「できた！」を大きな自信に</p>
            @auth
                <a href="{{ route('chat_test') }}">はじめる</a>
                <a href="{{ route('task_test') }}">タスク</a>
            @endauth
            @guest
                <a href="{{ route('register') }}">はじめる</a>
                <a href="{{ route('login') }}">ログイン</a>
            @endguest
        </div>
    </div>

@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const storys = document.querySelectorAll('.top_story');
    
    const updateUI = (idx) => {
        storys.forEach((story, i) => {
            // 現在のインデックスより前の要素は全て左へ飛ばす
            if (i < idx) {
                story.classList.add('is-passed');
            } else {
                story.classList.remove('is-passed');
            }
        });
    };

    // Intersection Observerの設定（画面の中央50%を通過した時に発火）
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const idx = parseInt(entry.target.dataset.index);
                updateUI(idx);
            }
        });
    }, { rootMargin: "-45% 0px -45% 0px" });

    document.querySelectorAll('.top_story_trigger').forEach(trigger => {
        observer.observe(trigger);
    });
});
</script>
@endpush

{{--<!--

    ～layoutの使い方～

    １．layoutの設定方法
    headerやfooterを書き、ページ毎に変えたい部分に@yield('content')や@stack('〇〇')を入れる

    ２．layoutの使い方
    @extends('layouts.app')
    @section('title', '〇〇')←タイトルを入れる
    @section('content')
        。。。。。←@yield('content')の部分
    @endsection

    @stack('〇〇')は、
    @push('〇〇')
        。。。。。←にコードを記入
    @endpush('〇〇')

-->--}}