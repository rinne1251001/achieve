<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>@yield('title', 'object')</title>
        <link rel="stylesheet" href="{{ asset('css/main.css') }}?v={{ time() }}">
        <link rel="icon" href="{{ asset('images/favicon.ico') }}">
    </head>

    <body>
        <header>
            @auth
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit">ログアウト</button>
                </form>
            @endauth
            @guest
                <a href="{{ route('login') }}"><button>ログイン</button></a>
            @endguest
        </header>

    @yield('content')

    </body>
</html>