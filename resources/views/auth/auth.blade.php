@extends('layouts.app')
@section('title', 'ログインページ')
@section('content')
<div class="auth_wrapper">
    <div class="auth_left" style="position: relative;">
        <h1 style="color: var(--bg-color); text-align: center; font-size: 5vw; filter: url(#shadow);">achieve<br>on<br>step</h1>
        <div style="display: flex; align-items: flex-end; position: absolute; bottom: 0.5em; left: 0.8em; font-size: clamp(40px, 5vw, 50px);">
            <div class="robot_container" style="color: var(--bg-color); animation: pyokopyoko 4s steps(1) infinite alternate; filter: url(#shadow);">
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
                        <div class="robot_apperarm left" style="transform: rotate(-70deg);">
                            <div class="robot_elbow left">
                                <div class="robot_forearm left" style="transform: rotate(20deg);">
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
                        <div class="robot_apperarm right" style="transform: rotate(70deg);">
                            <div class="robot_elbow right">
                                <div class="robot_forearm right" style="transform: rotate(-20deg);">
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
            <div style="margin-left: 0.2em; filter: url(#shadow); display: flex; flex-direction: column; align-items: center; justify-content: flex-end; width: 0.8em; height: 0.8em; position: relative;">
                <svg style="color: var(--accent-color); width: 0.8em; height: 0.8em; animation: CW 30s linear infinite; position: absolute; bottom: 0.38em; z-index: 2;"><use xlink:href="#flower" /></svg>
                <svg style="color: color-mix(in srgb, var(--base-color), yellow 35%); width: 0.8em; height: 0.48em; position: absolute; z-index: 1;"><use xlink:href="#stem" /></svg>
            </div>
        </div>
    </div>

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
                    <a href="{{ route('password.request') }}" style="color: var(--font-light-color); text-align: center;">パスワードをお忘れの方</a>
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
                        <label for="register_password_confirmation">confirm password</label>
                        <input id="register_password_confirmation" type="password" name="password_confirmation" autocomplete="new-password" placeholder="もう一度入力" required>
                        <span class="error_msg js-error"></span>
                    </div>
                    <button>アカウント作成</button>
                </form>
            </div>

        </div>
    </div>
</div>

@endsection
@push('scripts')
<svg width="0" height="0">
    <defs>
        <filter id="shadow" x="-20%" y="-20%" width="140%" height="140%">
            <feDropShadow dx="0" dy="4" stdDeviation="5" flood-color="rgba(0,0,0,0.2)"/>
        </filter>
    </defs>
</svg>
<svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
    <symbol id="flower" viewBox="0 0 33 33">
		<path d="M16.58 0a6.7 6.7 0 0 1 5.18 2.44l.37.5.55-.09.69-.03a6.72 6.72 0 0 1 6.58 8.07v.03l.09.06a6.71 6.71 0 0 1 0 11.14h-.02l.09.56a6.72 6.72 0 0 1-7.37 7.37l-.62-.1-.05.09a6.71 6.71 0 0 1-10.76.52l-.34-.47-.65.1a6.72 6.72 0 0 1-7.29-7.95v-.03l-.07-.04a6.71 6.71 0 0 1 0-11.14L3 11l-.01-.05A6.75 6.75 0 0 1 9.57 2.9c.47 0 .92.05 1.36.14h.03l.04-.07A6.71 6.71 0 0 1 16.58 0z" fill="currentColor" fill-rule="evenodd"/>
		<path d="M10 16.5a6.5 6.5 0 1 1 13 0 6.5 6.5 0 0 1-13 0zm2.03 0a4.47 4.47 0 1 0 8.94 0 4.47 4.47 0 0 0-8.94 0z" fill="#FFFFFF" fill-rule="evenodd"/>
	</symbol>
</svg>
<svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
    <symbol id="stem" viewBox="0 0 50 33">
        <path d="M25 0H28.38v18.84l.1-.12A17.4 17.4 0 0 1 30 17.23c4.13-3.52 16.63-10.21 19.41-6.55 2.79 3.66-5.2 16.3-9.32 19.81a14.19 14.19 0 0 1-4.31 2.41l-.32.1H14.53l-.32-.1a14.2 14.2 0 0 1-4.3-2.4C5.76 26.96-2.22 14.33.57 10.67c2.78-3.66 15.28 3.03 19.4 6.55.53.44 1.04.94 1.54 1.49l.1.12V0H25z" fill="currentColor" fill-rule="evenodd"/>
    </symbol>
</svg>
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
        } else {
            // 2. バリデーションがOKならLoaderを表示
            if (window.Loader) Loader.show();

            // 3. 【重要】タイムアウト対策
            // 万が一ネットが切れて画面が切り替わらない時のために、10秒後にLoaderを消す
            setTimeout(() => {
                if (window.Loader) Loader.hide();
                alert("ログインタイムアウト\n通信環境が良いところでお試しください")
            }, 15000);
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