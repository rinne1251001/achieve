<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <meta name="description" content="タスク提案アプリ❝achieve on step❞｜小さな「できた！」を大きな自信に">
        <meta property="og:site_name" content="achieve on step">
        <meta property="og:image" content="{{ asset('images/ogp.png') }}">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title', 'achieve on step')</title>
        <link rel="stylesheet" href="{{ asset('css/main.css') }}?v={{ time() }}">
        <link rel="icon" href="{{ asset('images/favicon.ico') }}">
        <link rel="apple-touch-icon" href="{{ asset('images/apple-touch-icon.png') }}">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=check_circle,delete,door_open,filter_alt,help,keyboard_arrow_down,login,person,person_add,send,settings,sms,swap_vert" />
        <script src="{{ asset('js/api-client.js') }}"></script>
        <script src="{{ asset('js/chat-utils.js') }}"></script>
    </head>

    @php
        $themeColor = Auth::user()?->theme_color ?? 'aqua';
        $isFlower = in_array($themeColor, ['aqua', 'pink', 'yellow', 'red']);
        $themeClass = $isFlower ? 'theme-flower' : 'theme-circle';
    @endphp
    <body class="{{ $themeClass }}" data-theme="{{ $themeColor }}">
        <header style="display: flex; position: sticky; top: 0; align-items: center; justify-content: space-between; padding: 0 clamp(10px, 5vw, 30px); height: 50px; background-color: var(--bg-color); z-index: 80;">
            
            <a href="{{ route('top') }}" style="color: var(--font-color); text-decoration: none;">
                <h1 style="font-size: 1.2em;">achieve on step</h1>
            </a>

            <div style="display: flex; align-items: center; gap: 20px;">

                <a href="{{ route('mypage') }}" style="color: var(--font-color); padding-top: 2px;" title="マイページ"><span class="material-symbols-outlined" style="font-size: 30px;">person</span></a>
                    
                <div id="hamb_btn" data-target="#acc_wrapper" data-overlay=".overlay">
                    <span class="hamb_line"></span>
                    <span class="hamb_line"></span>
                    <span class="hamb_line"></span>
                </div>
                <div class="overlay"></div>
                <div id="acc_wrapper" class="acc_wrapper">
                    @auth
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                            <a href="{{ route('mypage') }}" style="color: var(--font-color); text-decoration: none;"><h2>{{ Auth::user()->name }}</h2></a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button id="logoutBtn" type="submit" style="display: flex; background: none; border: none; color: var(--font-color); cursor: pointer;" title="ログアウト"><span class="material-symbols-outlined">door_open</span></button>
                            </form>
                        </div>
                        <nav>
                            <ul>
                                <li><a href="{{ route('chat') }}"><span class="material-symbols-outlined" style="margin-right: 15px;">sms</span>チャットボット</a></li>
                                <li><a href="{{ route('task') }}"><span class="material-symbols-outlined" style="margin-right: 15px">check_circle</span>タスク</a></li>
                                <li><a href="{{ route('setting') }}"><span class="material-symbols-outlined" style="margin-right: 15px;">settings</span>設定</a></li>
                                <li><a href="{{ route('faq') }}"><span class="material-symbols-outlined" style="margin-right: 15px;">help</span>Ｑ＆Ａ</a></li>
                            </ul>
                        </nav>
                    @endauth
                    @guest
                        <nav>
                            <ul>
                                <li><a href="{{ route('login') }}"><span class="material-symbols-outlined" style="margin-right: 15px;">login</span>ログイン</a></li>
                                <li><a href="{{ route('register') }}"><span class="material-symbols-outlined" style="margin-right: 15px;">person_add</span>会員登録</a></li>
                                <li><a href="{{ route('faq') }}"><span class="material-symbols-outlined" style="margin-right: 15px;">help</span>Ｑ＆Ａ</a></li>
                            </ul>
                        </nav>
                    @endguest
                </div>
            </div>
        </header>

    @yield('content')
    @include('parts.loader')
    <script>
        const logoutBtn = document.getElementById('logoutBtn');
        if(logoutBtn){
            logoutBtn.addEventListener('click', (e) => {
                // 1. 確認ダイアログを表示（「OK」なら true, 「キャンセル」なら false が返る）
                const result = confirm('ログアウトしますか？');
                if (!result) {
                    // 2. 「キャンセル（いいえ）」が押されたら送信を中止する
                    e.preventDefault();
                } else {
                    if (window.Loader) Loader.show();
                }
            });
        }
        const hamb_btn = document.getElementById('hamb_btn');
        const overlay = document.querySelector('.overlay');
        const menu = document.querySelector(hamb_btn.dataset.target);
        const toggle = () => {
            hamb_btn.classList.toggle('active');
            menu.classList.toggle('open');
            overlay.classList.toggle('show');
        };
        hamb_btn.onclick = toggle;
        overlay.onclick = toggle;
    </script>
    @stack('scripts')
    </body>
</html>