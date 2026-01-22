<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>@yield('title', 'object')</title>
        <link rel="stylesheet" href="{{ asset('css/main.css') }}?v={{ time() }}">
        <link rel="icon" href="{{ asset('images/favicon.ico') }}">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=check_circle,door_open,person,send,settings,sms" />
    </head>

    <body data-theme="{{ Auth::user()->theme_color ?? 'aqua' }}">
        <header style="display: flex; position: sticky; top: 0; align-items: center; justify-content: space-between; padding: 0 clamp(10px, 5vw, 30px); height: 50px; background-color: var(--bg-color); z-index: 80;">
            <a href="{{ route('top') }}" style="color: var(--font-color); text-decoration: none;"><h1 style="font-size: 1.2em;">achieve on step</h1></a>
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
                                <button type="submit" style="display: flex; background: none; border: none; color: var(--font-color); cursor: pointer;" title="ログアウト"><span class="material-symbols-outlined">door_open</span></button>
                            </form>
                        </div>
                        <nav>
                            <ul>
                                <li><a href="{{ route('chat_test') }}"><span class="material-symbols-outlined" style="margin-right: 15px;">sms</span>チャット</a></li>
                                <li><a href="#"><span class="material-symbols-outlined" style="margin-right: 15px">check_circle</span>タスク</a></li>
                                <li><a href="{{ route('setting') }}"><span class="material-symbols-outlined" style="margin-right: 15px;">settings</span>設定</a></li>
                            </ul>
                        </nav>
                    @endauth
                    @guest
                        <nav>
                            <ul>
                                <li><a href="{{ route('login') }}">ログイン</a></li>
                                <li><a href="{{ route('register') }}">会員登録</a></li>
                            </ul>
                        </nav>
                    @endguest
                </div>
            </div>
        </header>

    @yield('content')

    <script>
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