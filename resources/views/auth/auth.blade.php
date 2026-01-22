@extends('layouts.app')
@section('title', 'ログインページ')
@section('content')
<div class="auth_wrapper">
    <div class="auth_left"><h1 style="color: var(--bg-color); text-align: center; font-size: 5vw;">achieve<br>on<br>step</h1></div>

    <div style="display: flex; flex-direction: column; justify-content: center; align-items: center;">
        <div style="width: 80%; min-height: 568px; display: flex; flex-direction: column;">

            <div>
                <ul style="display: flex;">
                    <li id="login_tab" class="auth_tab {{ request()->is('login') ? 'active' : '' }}" onclick="switchTab('login')">ログイン</li>
                    <li id="register_tab" class="auth_tab {{ request()->is('register') ? 'active' : '' }}" onclick="switchTab('register')">会員登録</li>
                </ul>
            </div>

            <div id="login_form" class="auth_form {{ request()->is('login') ? 'active' : '' }}">
                <h2 style="text-align: center;">ログイン</h2>
                <form method="POST" action="{{ route('login') }}" style="display: grid; gap: 20px;" novalidate>
                    @csrf
                    <div style="display: grid;">
                        <label for="login_email">email</label>
                        <input id="login_email" type="email" name="email" autocomplete="email" placeholder="メールアドレス" value="{{ old('email') }}" required>
                        <span class="error_msg js-error">
                            @error('email') {{ $message }} @enderror
                        </span>
                    </div>
                    <div style="display: grid;">
                        <label for="login_password">password</label>
                        <input id="login_password" type="password" name="password" autocomplete="current-password" placeholder="パスワード" required>
                        <span class="error_msg js-error">
                            @error('password') {{ $message }} @enderror
                        </span>
                    </div>
                    <button>ログイン</button>
                </form>
            </div>

            <div id="register_form" class="auth_form {{ request()->is('register') ? 'active' : '' }}">
                <h2 style="text-align: center;">会員登録</h2>
                <form method="POST" action="{{ route('register') }}" style="display: grid; gap: 20px;" novalidate>
                    @csrf
                    <div style="display: grid;">
                        <label for="register_name">name</label>
                        <input id="register_name" type="text" name="name" autocomplete="name" placeholder="名前" value="{{ old('name') }}" required>
                        <span class="error_msg js-error">
                            @error('name') {{ $message }} @enderror
                        </span>
                    </div>
                    <div style="display: grid;">
                        <label for="register_email">email</label><input id="register_email" type="email" name="email" autocomplete="email" placeholder="メールアドレス" value="{{ old('email') }}" required>
                        <span class="error_msg js-error">
                            @error('email') {{ $message }} @enderror
                        </span>
                    </div>
                    <div style="display: grid;">
                        <label for="register_password">password</label>
                        <input id="register_password" type="password" name="password" autocomplete="new-password" placeholder="パスワード（８文字以上）" required>
                        <span class="error_msg js-error">
                            @error('password') {{ $message }} @enderror
                        </span>
                    </div>
                    <div style="display: grid;">
                        <label for="register_password_confirmation">password</label>
                        <input id="register_password_confirmation" type="password" name="password_confirmation" autocomplete="new-password" placeholder="もう一度入力" required>
                        <span class="error_msg js-error"></span>
                    </div>
                    <button>会員登録</button>
                </form>
            </div>

        </div>
    </div>
</div>

@endsection
@push('scripts')
<script>
    function switchTab(auth) {
        ['login', 'register'].forEach(name => {
            const active = (name === auth);
            document.getElementById(`${name}_tab`).classList.toggle('active', active);
            document.getElementById(`${name}_form`).classList.toggle('active', active);
        });
    }
    document.addEventListener('submit', error => {
        const form = error.target;
        form.classList.add('incomplete');
        if (!form.checkValidity()) {
            error.preventDefault();
            form.querySelectorAll('.js-error').forEach(span => 
                span.textContent = span.previousElementSibling.validationMessage
            );
        }
    });
</script>
@endpush

{{--<!--

１．ログイン後の遷移ページを変える
config/fortify.phpの'home' => '/',を変更

２．ログインしないとそのページには遷移できないようにするroutes/web.phpの書き方
Route::middleware(['auth'])->group(function () {
    //ここに書く
});

-->--}}