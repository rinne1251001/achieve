@extends('layouts.app')
@section('content')
<h1>ログイン</h1>
<form method="POST" action="{{ route('login') }}">
    @csrf
    <p>email</p><input type="email" name="email">
    <p>password</p><input type="password" name="password">
    <button>ログイン</button>
</form>

<h1>会員登録</h1>
<form method="POST" action="{{ route('register') }}">
    @csrf
    <p>name</p><input type="text" name="name">
    <p>email</p><input type="email" name="email">
    <p>password</p><input type="password" name="password">
    <p>password</p><input type="password" name="password_confirmation">
    <button>会員登録</button>
</form>

@endsection()