@extends('layouts.app')
@section('title', 'トップページ')
@section('content')

    <div class="top_container" style="background-color: var(--bg-color);">
        <div>イラスト</div>
        <div>
            <p>小さな「できた！」を大きな自信に</p>
            @auth
                <a href="{{ route('chat_test') }}">AIと話す</a>
                <a href="{{ route('task_test') }}">タスク</a>
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
                <a href="{{ route('chat_test') }}">AIと話す</a>
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
    const storys = document.querySelectorAll('.top_story'),
          triggers = document.querySelectorAll('.top_story_trigger'),
          section = document.querySelector('.top_story_section');
    
    let currentIdx = 0, isProcessing = false, touchStartY = 0;

    const updateUI = (idx) => {
        if (idx < 0 || idx >= storys.length) return;
        currentIdx = idx;
        storys.forEach((s, i) => s.classList.toggle('is-passed', i < idx));
    };

    const move = (idx) => {
        if (idx < 0 || idx >= storys.length || isProcessing) return;
        isProcessing = true;
        updateUI(idx);

        const top = triggers[idx].getBoundingClientRect().top + window.pageYOffset;
        window.scrollTo({ top, behavior: 'smooth' });

        setTimeout(() => isProcessing = false, 1000);
    };

    const handleScroll = (delta, e) => {
        const r = section.getBoundingClientRect();
        if (isProcessing || r.top > 100 || r.bottom < 100) return;

        if (Math.abs(delta) > 30) {
            const nextIdx = delta > 0 ? currentIdx + 1 : currentIdx - 1;
            if (nextIdx >= 0 && nextIdx < storys.length) {
                e.preventDefault();
                move(nextIdx);
            }
        }
    };

    window.addEventListener('wheel', e => handleScroll(e.deltaY, e), { passive: false });
    window.addEventListener('touchstart', e => touchStartY = e.touches[0].clientY);
    window.addEventListener('touchmove', e => handleScroll(touchStartY - e.touches[0].clientY, e), { passive: false });

    const observer = new IntersectionObserver(entries => {
        entries.forEach(e => {
            if (e.isIntersecting && !isProcessing) updateUI(+e.target.dataset.index);
        });
    }, { rootMargin: "-45% 0px" });

    triggers.forEach(t => observer.observe(t));
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