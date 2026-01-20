@extends('layouts.app')
@section('title', 'トップページ')
@section('content')

    achive on stepのトップページ

@endsection

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