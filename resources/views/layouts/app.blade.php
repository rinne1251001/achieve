<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>@yield('title', 'object')</title>
        <link rel="stylesheet" href="{{ asset('css/main.css') }}?v={{ time() }}">
        <link rel="icon" href="{{ asset('images/favicon.ico') }}">
    </head>

    <body>

    @yield('content')

    </body>
</html>