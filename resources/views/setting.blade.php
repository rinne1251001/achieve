@extends('layouts.app')
@section('title', '設定')
@section('content')

<div class="wrapper3">

    <aside class="sidebar">
        <svg width="700" height="700" style="color: var(--base-color); position: absolute; right: 10px; top: -250px;"><use xlink:href="#flower" /></svg>
        <svg width="150" height="150" style="color: var(--sub-color); position: absolute; right: 100px; top: 53vh;"><use xlink:href="#flower" /></svg>
        <svg width="550" height="550" style="color: var(--accent-color); position: absolute; left: 30px; bottom: -300px;"><use xlink:href="#flower" /></svg>
    </aside>

    <main style="padding: 0 clamp(10px, 5vw, 30px);">
        <form id="settings-form" style="display: grid; gap: 40px;">
            @csrf
            @method('PUT')

            <div>
                <h2 style="margin-top: 0;">プロフィール</h2>
                <div class="auth_form active"style="display: grid; gap: 20px;">
                    <div style="display: grid;">
                        <label for="name">name</label>
                        <input id="name" type="name" name="name" autocomplete="name" placeholder="なまえ" value="{{ Auth::user()->name }}" required>
                    </div>
                    <div style="display: grid;">
                        <label for="email">email</label>
                        <input id="email" type="email" name="email" autocomplete="email" placeholder="メールアドレス" value="{{ Auth::user()->email }}" required>
                    </div>
                </div>
            </div>

            <span style="width: 100%; height: 1.5px; background-color: var(--font-light-color);"></span>

            <div>
                <h2 style="margin-top: 0;">テーマカラー</h2>
                <input type="hidden" name="theme_color" id="selected-theme" value="aqua">
                <div class="color-options" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; padding: 0 20px;">
                    <div class="color-box {{ Auth::user()->theme_color == 'aqua' ? 'active' : '' }}" data-theme="aqua" style="aspect-ratio: 1 / 1; background-color: #87C8DE"></div>
                    <div class="color-box {{ Auth::user()->theme_color == 'pink' ? 'active' : '' }}" data-theme="pink" style="aspect-ratio: 1 / 1; background-color: #F7C3BF;"></div>
                    <div class="color-box {{ Auth::user()->theme_color == 'yellow' ? 'active' : '' }}" data-theme="yellow" style="aspect-ratio: 1 / 1; background-color: #ffcc00"></div>
                    <div class="color-box {{ Auth::user()->theme_color == 'blue' ? 'active' : '' }}" data-theme="blue" style="aspect-ratio: 1 / 1; background-color: #001D42"></div>
                </div>
            </div>

            <button type="submit" class="setting_save">保存</button>
        </form>
        <span style="display:block; width: 100%; height: 1.5px; background-color: var(--font-light-color); margin: 40px 0;"></span>
        {{-- ▼ パスワード変更フォーム --}}
        <form id="password-form" style="display: grid; gap: 40px; margin-bottom: 50px;">
            @csrf
            @method('PUT')

            <div>
                <h2 style="margin-top: 0;">パスワード変更</h2>
                <div class="auth_form active" style="display: grid; gap: 20px;">
                    
                    {{-- 現在のパスワード --}}
                    <div style="display: grid;">
                        <label for="current_password">現在のパスワード</label>
                        <input id="current_password" type="password" name="current_password" autocomplete="current-password" placeholder="現在のパスワード" required>
                    </div>

                    {{-- 新しいパスワード --}}
                    <div style="display: grid;">
                        <label for="password">新しいパスワード</label>
                        <input id="password" type="password" name="password" autocomplete="new-password" placeholder="新しいパスワード（８文字以上）" required>
                    </div>

                    {{-- 確認用パスワード --}}
                    <div style="display: grid;">
                        <label for="password_confirmation">新しいパスワード（確認）</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password" placeholder="もう一度入力してください" required>
                    </div>
                </div>
            </div>

            <button type="submit" class="setting_save">パスワードを更新</button>
        </form>
    </main>

    <aside class="sidebar">
        <div style="position: relative; height: 100vh;">
            <svg width="500" height="500" style="color: var(--accent-color); position: absolute; right: 10px; top: -250px;"><use xlink:href="#flower" /></svg>
            <svg width="200" height="200" style="color: var(--base-color); position: absolute; right: 42px; top: 29vh;"><use xlink:href="#flower" /></svg>
            <svg width="700" height="700" style="color: var(--sub-color); position: absolute; left: 0px; bottom: -300px;"><use xlink:href="#flower" /></svg>
        </div>
    </aside>

</div>

<div id="detailModal" class="modal_shadow">
    <div id="detailStep1" class="modal_narrow">
        <p>登録しました！</p>
    </div>
</div>

@endsection

@push('scripts')
<svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
    <symbol id="flower" viewBox="-5 -5 110 110">
        <path d="M50 37a13 13 0 110 26 13 13 0 010-26zm0-7a20 20 0 100 40 20 20 0 000-40zm0-30c5 0 10 2 14 6l3 4 5-1a20 20 0 0120 20l-1 5 5 3a20 20 0 010 28l-4 3 1 5a20 20 0 01-20 20l-5-1-3 4a20 20 0 01-28 0l-3-4-5 1a20 20 0 01-20-20l1-5-4-3a20 20 0 010-28l4-3-1-5a20 20 0 0120-20l5 1 3-4c1-2 3-4 5-5z" fill="currentColor" fill-rule="evenodd"/>
    </symbol>
</svg>

<script>
    const form = document.getElementById('settings-form');
    const themeInput = document.getElementById('selected-theme');
    const colorBoxes = document.querySelectorAll('.color-box');

    const toggleDetailModal = (show) => {
        const modal = document.getElementById('detailModal');
        const step1 = document.getElementById('detailStep1');

        if (show) {
            if (modal) modal.style.display = 'grid';
            if (step1) step1.style.display = 'grid';
        } else {
            if (modal) modal.style.display = 'none';
            if (step1) step1.style.display = 'none';
        }
    };

    // 1. 選択状態の切り替え（枠線のみ）
    colorBoxes.forEach(box => {
        box.onclick = () => {
            const themeName = box.dataset.theme;
            colorBoxes.forEach(b => b.classList.remove('active'));
            box.classList.add('active');
            themeInput.value = themeName;
            document.body.setAttribute('data-theme', themeName);
            const svgs = document.querySelectorAll('.sidebar svg');
            svgs.forEach(svg => {
                svg.classList.remove('is-animating');
                void svg.offsetWidth; 
                svg.classList.add('is-animating');
                svg.addEventListener('animationend', () => {
                    svg.classList.remove('is-animating');
                }, { once: true });
            });
        };
    });

    // 2. 保存機能
    form.onsubmit = async (e) => {
        e.preventDefault();
        if (window.Loader) Loader.show();
        try {
            const response = await fetch("{{ route('user-profile-information.update') }}", {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: new FormData(form),
                signal: getTimeoutSignal(10)
            });

            if (response.ok) {
                if (window.Loader) Loader.hide();
                toggleDetailModal(true);
                // 保存が成功した後に色を反映させたい場合は、ここでリロードするか、
                // document.body.setAttribute('data-theme', themeInput.value); を実行します。
                location.reload(); // 保存完了後に画面をリロードして最新状態を反映
            } else {
                if (window.Loader) Loader.hide();
                alert('エラーが発生しました。入力内容を確認してください。');
            }
        } catch (error) {
            if (window.Loader) Loader.hide();
            console.error('通信エラー:', error);
            alert(parseError("通信エラーが発生しました"));
        }
    };

    // --- ▼ パスワード変更処理 ---
    const passwordForm = document.getElementById('password-form');

    passwordForm.onsubmit = async (e) => {
        e.preventDefault();
        
        // 簡単なクライアントバリデーション（空チェック以外に行いたい場合）
        const current = passwordForm.querySelector('[name="current_password"]').value;
        const pass = passwordForm.querySelector('[name="password"]').value;
        const confirm = passwordForm.querySelector('[name="password_confirmation"]').value;

        if (pass !== confirm) {
            alert('新しいパスワードと確認用パスワードが一致しません。');
            return;
        }
        if (window.Loader) Loader.show();
        try {
            // Fortifyのデフォルトルートへ送信
            const response = await fetch("{{ route('user-password.update') }}", {
                method: 'POST', // Fortifyは内部でPUTを期待しますが、FormDataを使う場合POSTで _method=PUT を送るのが一般的です(HTML側に記述済)
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json' // エラーをJSONで受け取るために重要
                },
                body: new FormData(passwordForm)
            });

            const data = await response.json();

            if (response.ok) {
                if (window.Loader) Loader.hide();
                toggleDetailModal(true);
                passwordForm.reset(); // フォームをクリア
                setTimeout(() => {
                    toggleDetailModal(false);
                }, 1500);
            } else {
                // エラー処理 (バリデーションエラーなど)
                if (window.Loader) Loader.hide();
                let errorMessage = 'エラーが発生しました。\n';
                
                if (data.errors) {
                    // Laravelからのバリデーションエラーメッセージを展開
                    Object.values(data.errors).forEach(err => {
                        errorMessage += `・${err}\n`;
                    });
                } else if (data.message) {
                    errorMessage += data.message;
                }
                
                alert(errorMessage);
            }
        } catch (error) {
            if (window.Loader) Loader.hide();
            console.error('通信エラー:', error);
            alert('通信エラーが発生しました。');
        }
    };
</script>
@endpush