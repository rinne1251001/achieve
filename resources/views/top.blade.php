@extends('layouts.app')
@section('title', 'トップページ')
@section('content')

    <div class="top_container" style="background-color: var(--bg-color);">
        <div>イラスト</div>
        <div>
            <p>小さな「できた！」を大きな自信に</p>
            @auth
                <a href="{{ route('chat_test') }}">チャットボットと話す</a>
                <a href="{{ route('task') }}">タスク管理</a>
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
                    <div class="top_callout_wrapper">
                        <div class="top_callout_container" style="position: absolute; top: 0; left: 30%; animation-delay: 0.6s;"><div class="top_callout" style="animation-delay: 2.2s;"><span style="animation: dot1 5s ease-in-out infinite;"></span><span style="animation: dot2 5s ease-in-out infinite;"></span><span style="animation: dot3 5s ease-in-out infinite;"></span></div></div>
                        <div class="top_callout_container" style="position: absolute; top: 80%; right: 0; animation-delay: 0.8s;"><div class="top_callout" style="animation-delay: 3.5s;"><span style="animation: dot1 5s ease-in-out infinite; animation-delay: 1s;"></span><span style="animation: dot2 5s ease-in-out infinite; animation-delay: 1s;"></span><span style="animation: dot3 5s ease-in-out infinite; animation-delay: 1s;"></span></div></div>
                        <div class="top_callout_container" style="position: absolute; top: 90%; left: 0; animation-delay: 1s;"><div class="top_callout" style="animation-delay: 5.4s;"><span style="animation: dot1 5s ease-in-out infinite; animation-delay: 1.8s;"></span><span style="animation: dot2 5s ease-in-out infinite; animation-delay: 1.8s;"></span><span style="animation: dot3 5s ease-in-out infinite; animation-delay: 1.88s;"></span></div></div>
                    </div>

                    <div class="robot_container robot1">
                        <div class="robot_antenna">
                            <div></div>
                            <div></div>
                        </div>
                        <div class="robot_head_container">
                            <div class="robot_ear"></div>
                            <div class="robot_head">
                                <div></div>
                                <div></div>
                                <div></div>
                            </div>
                            <div class="robot_ear"></div>
                        </div>
                        <div class="robot_body_container">
                            <div class="robot_apperarm_container">
                                <div class="robot_shoulder left"></div>
                                <div class="robot_apperarm left" style="animation: left-arm 1s steps(2) infinite alternate;">
                                    <div class="robot_elbow left">
                                        <div class="robot_forearm left" style="animation: left-forearm 1s steps(2) infinite alternate;">
                                            <div class="robot_hand left"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="robot_body">
                                <div></div>
                                <div></div>
                            </div>
                            <div class="robot_apperarm_container">
                                <div class="robot_shoulder right"></div>
                                <div class="robot_apperarm right" style="animation: right-arm 1s steps(2) infinite alternate;">
                                    <div class="robot_elbow right">
                                        <div class="robot_forearm right" style="animation: right-forearm 1s steps(2) infinite alternate;">
                                            <div class="robot_hand right"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="robot_legs_container">
                            <div class="robot_leg"><div class="robot_foot"></div></div>
                            <div class="robot_leg"><div class="robot_foot"></div></div>
                        </div>
                    </div>
                </div>

                <div class="top_pres">
                    <h3>チャットボットがゴール・タスクを提案</h3>
                    <p>
                        <span style="font-weight: bolder;">「やりたいことが分からない」「すべきことが見つからない」</span><br>
                        そんなあなたにおすすめのアプリです<br>
                        チャットボットが<span style="font-weight: bolder;">あなたに最適な目標やゴールを見つけてくれる</span>のでもう迷いません
                    </p>
                </div>
            </div>

            <div class="top_container top_story" style="background-color: var(--accent-color); z-index: 2;">
                <div style="direction: ltr;">
                    <div style="font-size: 100px; position: relative;">
                        <div>
                            <svg style="width: 6em; height: 4.53em;"><use xlink:href="#fuwafuwa" style="color: var(--base-color);"></use></svg>
                            <svg style="width: 6em; height: 4.53em; position: absolute; top: 0.1em; left: 0.1em; z-index: -5;"><use xlink:href="#fuwafuwa" style="color: var(--bg-color);"></use></svg>
                        </div>
                        
                        <div class="top_bord_container" style="bottom: 1.5em; left: 62%; color: var(--bg-color);">
                            <div class="top_bord_list">
                                <svg><use xlink:href="#check"></use></svg>
                                <div class="top_bord_memo">
                                    <span></span>
                                    <span></span>
                                </div>
                            </div>
                            <div class="top_bord_list">
                                <svg><use xlink:href="#check"></use></svg>
                                <div class="top_bord_memo">
                                    <span></span>
                                    <span></span>
                                </div>
                            </div>
                            <div class="top_bord_list">
                                <svg><use xlink:href="#check"></use></svg>
                                <div class="top_bord_memo">
                                    <span></span>
                                    <span></span>
                                </div>
                            </div>
                        </div>

                        <div class="robot_container" style="position: absolute; bottom: 1.5em; left: 19%; font-size: 0.8em; color: var(--bg-color);">
                            <div class="robot_antenna">
                                <div></div>
                                <div></div>
                            </div>
                            <div class="robot_head_container">
                                <div class="robot_ear"></div>
                                <div class="robot_head">
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                </div>
                                <div class="robot_ear"></div>
                            </div>
                            <div class="robot_body_container">
                                <div class="robot_apperarm_container">
                                    <div class="robot_shoulder left"></div>
                                    <div class="robot_apperarm left" style="transform: rotate(60deg);">
                                        <div class="robot_elbow left">
                                            <div class="robot_forearm left" style="transform: rotate(0deg);">
                                                <div class="robot_hand left">
                                                    <svg style="width: 0.8em; height: 0.8em; position: absolute; transform: rotate(100deg); transform-origin: center center; top: -0.3em; right: -0.1em;">
                                                        <use xlink:href="#pen" style="color: var(--bg-color); stroke: var(--base-color); stroke-width: 0.2em; paint-order: stroke fill;"></use>
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="robot_body">
                                    <div></div>
                                    <div></div>
                                </div>
                                <div class="robot_apperarm_container">
                                    <div class="robot_shoulder right"></div>
                                    <div class="robot_apperarm right" style="animation: right-arm 2s steps(2) infinite alternate;">
                                        <div class="robot_elbow right">
                                            <div class="robot_forearm right">
                                                <div class="robot_hand right"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="robot_legs_container">
                                <div class="robot_leg"><div class="robot_foot"></div></div>
                                <div class="robot_leg"><div class="robot_foot"></div></div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="top_pres">
                    <h3>タスク管理</h3>
                    <p>
                        提案されたタスク達成できたタスクは一目で確認できます<br>
                    </p>
                </div>
            </div>

            <div class="top_container top_story" style="background-color: var(--sub-color); z-index: 1;">
                <div>
                    <div class="top_bord_wrapper" style="font-size: 100px;">
                        <div class="top_clip_container">
                            <div></div>
                            <div></div>
                        </div>
                        <div class="top_bord_heading">TASKS</div>
                        <div class="top_bord_container">
                            <div class="top_bord_list">
                                <svg width="35" height="35"><use xlink:href="#check"></use></svg>
                                <div class="top_bord_memo">
                                    <span></span>
                                    <span></span>
                                </div>
                            </div>
                            <div class="top_bord_list">
                                <svg width="35" height="35"><use xlink:href="#check"></use></svg>
                                <div class="top_bord_memo">
                                    <span></span>
                                    <span></span>
                                </div>
                            </div>
                            <div class="top_bord_list">
                                <svg width="35" height="35"><use xlink:href="#check"></use></svg>
                                <div class="top_bord_memo">
                                    <span></span>
                                    <span></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="top_pres">
                    <h3>自分でもゴール・タスクを追加</h3>
                    <p>
                        慣れてきたら自分でもゴールやタスクを立てることができます<br>

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
                <a href="{{ route('chat_test') }}">チャットボットと話す</a>
                <a href="{{ route('task') }}">タスク管理</a>
            @endauth
            @guest
                <a href="{{ route('register') }}">はじめる</a>
                <a href="{{ route('login') }}">ログイン</a>
            @endguest
        </div>
    </div>

@endsection
@push('scripts')
<svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
    <symbol id="check" viewBox="-30 140 490 200">
        <path d="M438 38l3.1 1.7c9 7 14 15 16 26.3 1 18.5-10.6 30.8-21 45l-1.7 2.4a7813 7813 0 01-14 19c-7 9.2-13.7 18.5-20.4 27.8L386 179a1663 1663 0 00-11.7 16 2065 2065 0 01-15.3 21 1663 1663 0 00-11.7 16 2065 2065 0 01-15.3 21A1666 1666 0 00320.5 268.9c-7.3 10.2-14.8 20.3-22.3 30.4L278 327.2 264 346a1663 1663 0 00-11.5 16 2064.7 2064.7 0 01-15.3 21 1666 1666 0 00-11.7 16l-18.7 25.6-5.6 7.5-1.8 2.7a4946.4 4946.4 0 00-3.5 4.7C179.2 462 179.2 462 165 465.8c-10.4.8-19.7.9-28.5-5.4a114.6 114.6 0 01-6-5.8l-6-6c-12.7-12.4-12.7-12.4-17-17.4-5-5.9-10.7-11.2-16.2-16.7-12.3-12-12.3-12-16-16.5-5-6-10.9-11.4-16.5-17-12.8-12.6-12.8-12.6-18-18.9-4.8-5.1-10-10-15-15-12.8-12.6-12.8-12.6-16.5-17-3-3.6-6.3-6.9-9.7-10.2l-1.6-1.7-7-6.7c-21.5-21.1-21.5-21.1-22.3-38a39 39 0 0111.7-27.1 37.6 37.6 0 0150.6.5l10.3 7.6 2.4 1.6 17.6 12.5 17.7 12.6a1528 1528 0 0017.3 12.5 923.5 923.5 0 0041.3 29l5.8 4.3c4.3 3 7.2 3.1 12.2 2.2 6.1-2.7 9.7-8.3 13.7-13.4 2.2-2.8 4.6-5.5 6.9-8.2a508.8 508.8 0 0011.3-13.8c2.3-3 4.8-6 7.3-8.8a366 366 0 0011-13.7 366 366 0 0114-16.6A546 546 0 00234 238a925 925 0 0114.4-17.3 370.2 370.2 0 0011-13.6c4.4-5.6 9-11 13.8-16.4a268.2 268.2 0 0010.3-12.6 352 352 0 0113.8-16.6c4.7-5.4 9.2-10.9 13.7-16.4a925 925 0 0114.4-17.3 370.2 370.2 0 0011-13.6c4.4-5.6 9-11 13.8-16.4A268.2 268.2 0 00360.5 85c4.4-5.7 9-11.2 13.8-16.6a374.3 374.3 0 009.5-11.2c14-17.3 31-31.7 54.2-19.2z" fill="currentColor"/>
    </symbol>
</svg>
<svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
    <symbol id="fuwafuwa" viewBox="0 0 100 75.5">
        <path d="M56.2 .3C70.9 .8 82 9 81.5 19.4l-.2 1.6 2.8 0c6.4 .6 12 4 14.7 9.7 4.2 9.1-.8 20.4-11.3 25.3l-1.2 .4-.2 2c-1.4 6-5.8 11.5-12.4 14.5A23.1 23.1 0 0 1 61.8 75.2l-1.2-.2-1.6 0a48.2 48.2 0 0 1-14.1-3.5l-3.1-1.5-2.8 2.1c-7.1 4.5-15.7 5.1-21.7 1-6.1-4.1-7.9-11.8-5.3-19.3l.2-.5-2.1-.5c-1.1-.4-2.2-1-3.3-1.7-8.1-5.5-8.6-17.4-1.2-26.6 4.7-5.7 11.4-9.1 17.7-9.4l1.7 .1 .8-1.4C30.6 6.2 41.2 .7 53.2 .3c1-.1 2-.1 3 0z" fill="currentColor"/>
    </symbol>
</svg>
<svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
    <symbol id="pen" viewBox="3 4 123 124">
        <path d="m16.6 88.6 24.7 24.7-37.5 12.9zm68.9-71.8 27.4 27.5L46 111 18.8 83.5zm17-12.7c2.4.2 5 1.2 7 3.3L123 20.7c3 3 4 7.3 2.6 10.9l-1.3 2v.1l-7.7 7.8L95 20l-6-6.3L96.6 6l2-1.3c1.2-.5 2.4-.7 3.7-.6z" fill="currentColor" fill-rule="evenodd"/>
    </symbol>
</svg>
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