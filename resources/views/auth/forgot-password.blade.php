@extends('layouts.app')
@section('title', 'パスワード再設定')

@section('content')
<div class="auth_wrapper" style="width: 100%; min-height: 100vh; display: flex; justify-content: center; align-items: center;">
    <div style="display: flex; flex-direction: column; justify-content: center; align-items: center; min-height: 100vh;">
        <div style="width: 80%; max-width: 400px;">
            
            <div class="auth_form active">
                <h2 style="text-align: center;">パスワード再設定</h2>
                <p style="text-align: center; font-size: 0.9em; margin-bottom: 20px;">
                    登録しているメールアドレスを入力してください。<br>パスワード再設定リンクを送信します。
                </p>

                @if (session('status'))
                    <div style="color: green; text-align: center; margin-bottom: 15px;">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" style="display: grid; gap: 20px;">
                    @csrf
                    
                    <div style="display: grid;">
                        <label for="email">email</label>
                        <input id="email" type="email" name="email" placeholder="メールアドレス" value="{{ old('email') }}" required autofocus>
                        <span class="error_msg" style="color: red;">
                            @error('email') {{ $message }} @enderror
                        </span>
                    </div>

                    <button type="submit">リンクを送信</button>
                    
                    <a href="{{ route('login') }}" style="text-align: center; margin-top: 10px; color: var(--font-light-color);">ログインに戻る</a>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection