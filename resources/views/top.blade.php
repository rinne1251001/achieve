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
                <div>
                    <div style="position: relative; height: 60px; margin-bottom: 25px;">
                        <div style="position: absolute; top: 0; left: 30%; transform: rotate(-5deg);"><div class="top_callout"><span>・</span><span style="animation-delay: 0.5s;">・</span><span style="animation-delay: 1s;">・</span></div></div>
                        <div style="position: absolute; top: 80%; right: 0; transform: rotate(25deg);"><div class="top_callout" style="animation-delay: 0.5s;"><span>・</span><span style="animation-delay: 0.5s;">・</span><span style="animation-delay: 1s;">・</span></div></div>
                        <div style="position: absolute; top: 90%; left: 0; transform: rotate(-15deg);"><div class="top_callout" style="animation-delay: 1s;"><span>・</span><span style="animation-delay: 0.5s;">・</span><span style="animation-delay: 1s;">・</span></div></div>
                    </div>

                    <div style="display: grid; place-content: center; place-items: center; gap: 3px;">
                        <div style="display: grid; place-content: center; place-items: center;">
                            <div style="background-color: var(--bg-color); width: 20px; height: 20px; border-radius: 50%; animation: pikapika 1.2s ease-in-out infinite;"></div>
                            <div style="background-color: var(--bg-color); width: 6px; height: 12px;"></div>
                        </div>
                        <div style="display: flex; gap: 3px; align-items: center;">
                            <div style="background-color: var(--bg-color); width: 15px; height: 30px; border-radius: 50% 0 0 50%;"></div>
                            <div style="position: relative; background-color: var(--bg-color); width: 100px; height: 60px; border-radius: 20px;">
                                <div style="position: absolute; background-color: var(--font-color); width: 15px; height: 15px; border-radius: 50%; top: 50%; left: 20%;"></div>
                                <div style="position: absolute; background-color: var(--font-color); width: 10px; top: 70%; left: 50%; transform: translateX(-50%); animation: pakupaku 1s steps(2) infinite alternate;"></div>
                                <div style="position: absolute; background-color: var(--font-color); width: 15px; height: 15px; border-radius: 50%; top: 50%; right: 20%;"></div>
                            </div>
                            <div style="background-color: var(--bg-color); width: 15px; height: 30px; border-radius: 0 50% 50% 0;"></div>
                        </div>
                        <div style="display: flex; gap: 3px; align-items: flex-start; justify-content: center;">
                            <div style="position: relative; width: 45px; height: 30px;">
                                <div style="position: absolute; top: 10px; right: 0; background-color: var(--bg-color); width: 15px; height: 30px; border-radius: 100% 0 0 100% / 50%; z-index: 3;"></div>
                                <div style="position: absolute; top: 25px; right: 5px; background-color: var(--bg-color); width: 30px; height: 6px; transform-origin: right center; transform: rotate(-60deg); animation: left-arm 1s steps(2) infinite alternate;">
                                    <div style="position: absolute; left: -10px; top: 50%; transform: translateY(-50%); background-color: var(--bg-color); width: 18px; height: 18px; border-radius: 50%;">
                                        <div style="position: absolute; right: 10px; top: 40%; background-color: var(--bg-color); width: 30px; height: 6px; transform-origin: right center; transform: rotate(45deg); animation: left-forearm 1s steps(2) infinite alternate;">
                                            <div style="position: absolute; left: -10px; top: 50%; transform: translateY(-50%); background-color: var(--bg-color); width: 18px; height: 18px; border-radius: 50%;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div style="position: relative; background-color: var(--bg-color); width: 85px; height: 70px; border-radius: 20px; flex-shrink: 0;">
                                <div style="position: absolute; background-color: var(--font-color); width: 10px; height: 10px; border-radius: 50%; top: 20%; left: 50%; transform: translateX(-50%);"></div>
                                <div style="position: absolute; background-color: var(--font-color); width: 10px; height: 10px; border-radius: 50%; top: 40%; left: 50%; transform: translateX(-50%);"></div>
                            </div>
                            <div style="position: relative; width: 45px; height: 30px;">
                                <div style="position: absolute; top: 10px; left: 0; background-color: var(--bg-color); width: 15px; height: 30px; border-radius: 0 100% 100% 0 / 50%; z-index: 3;"></div>
                                <div style="position: absolute; top: 25px; left: 5px; background-color: var(--bg-color); width: 30px; height: 6px; transform-origin: left center; transform: rotate(60deg); animation: right-arm 1s steps(2) infinite alternate;">
                                    <div style="position: absolute; right: -10px; top: 50%; transform: translateY(-50%); background-color: var(--bg-color); width: 18px; height: 18px; border-radius: 50%;">
                                        <div style="position: absolute; left: 10px; top: 50%; background-color: var(--bg-color); width: 30px; height: 6px; transform-origin: left center; transform: rotate(-45deg); animation: right-forearm 1s steps(2) infinite alternate;">
                                            <div style="position: absolute; right: -10px; top: 40%; transform: translateY(-50%); background-color: var(--bg-color); width: 18px; height: 18px; border-radius: 50%;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div style="display: flex; gap: 18px;">
                            <div style="position: relative; background-color: var(--bg-color); width: 18px; height: 40px;"><div style="position: absolute; background-color: var(--bg-color); width: 30px; height: 25px; border-radius: 30px 30px 10px 10px; bottom: 0; left: 50%; transform: translateX(-50%);"></div></div>
                            <div style="position: relative; background-color: var(--bg-color); width: 18px; height: 40px;"><div style="position: absolute; background-color: var(--bg-color); width: 30px; height: 25px; border-radius: 30px 30px 10px 10px; bottom: 0; left: 50%; transform: translateX(-50%);"></div></div>
                        </div>
                    </div>
                </div>

                <div style="color: var(--bg-color)">
                    <h3>チャットボットが目標タスクを提案</h3>
                    <p></p>
                </div>
            </div>

            <div class="top_container top_story" style="background-color: var(--accent-color); z-index: 2;">
                <div>イラスト</div>
                <div style="color: var(--bg-color)">
                    <h3>タスク管理</h3>
                    <p>
                        提案されたタスク達成できたタスクは一目で確認できる
                    </p>
                </div>
            </div>
            <div class="top_container top_story" style="background-color: var(--sub-color); z-index: 1;">
                <div>イラスト</div>
                <div style="color: var(--bg-color)">
                    自分でもゴールタスク設定
                    <p>
                        慣れてきたら自分でもゴールやタスクを立てられる
                    </p>
                </div>
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
        if (isProcessing) {
            e.preventDefault(); // スクロールを無効化
            e.stopPropagation(); // イベントの伝播も止める（念の為）
            return; 
        }

        const r = section.getBoundingClientRect();
        if (r.top > 100 || r.bottom < 100) return;
        if (Math.abs(delta) > 10) { 
            const nextIdx = delta > 0 ? currentIdx + 1 : currentIdx - 1;

            if (nextIdx >= 0 && nextIdx < storys.length) {
                e.preventDefault(); // ここでもスクロールを無効化
                move(nextIdx);      // 自前の移動処理を実行
            }
        } else {
            e.preventDefault();
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