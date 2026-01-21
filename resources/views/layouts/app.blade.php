<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>@yield('title', 'object')</title>
        <link rel="stylesheet" href="{{ asset('css/main.css') }}?v={{ time() }}">
        <link rel="icon" href="{{ asset('images/favicon.ico') }}">
    </head>

    <body>
        <header style="display: flex; position: sticky; top: 0; align-items: center; justify-content: space-between; padding: 0 clamp(10px, 5vw, 30px);; height: 50px; background-color: var(--bg-color);">
            <a href="{{ route('top') }}"><h1>achieve on step</h1></a>
            <div style="display: flex; align-items: center; gap: 20px;">
                @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit">ログアウト</button>
                    </form>
                @endauth
                @guest
                    <a href="{{ route('login') }}"><button>ログイン</button></a>
                @endguest
                <div style="position: relative; width: 30px; height: 30px;">
                    <span style="position: absolute; width: 100%; height: 3px; background-color: var(--font-light-color); top: 0;"></span>
                    <span style="position: absolute; width: 100%; height: 3px; background-color: var(--font-light-color); top: 50%; transform: translateY(-50%);"></span>
                    <span style="position: absolute; width: 100%; height: 3px; background-color: var(--font-light-color); bottom: 0;"></span>
                </div>
            </div>
        </header>

    @yield('content')

    <footer>
        ©ParaFt-Q
    </footer>
    @stack('scripts')
    </body>
</html>