@extends('layouts.app')
@section('title', '設定')
@section('content')

<div class="wrapper3">

    <aside class="sidebar">
        <div class="svg_icon" style="position: relative; height: 100vh;">
            <svg class="flower_icon" width="650" height="650" style="color: var(--base-color); position: absolute; right: 10px; top: -250px;"><use xlink:href="#flower" /></svg>
            <svg class="flower_icon" width="150" height="150" style="color: var(--sub-color); position: absolute; right: 100px; top: 53vh;"><use xlink:href="#flower" /></svg>
            <svg class="flower_icon" width="500" height="500" style="color: var(--accent-color); position: absolute; left: 30px; bottom: -300px;"><use xlink:href="#flower" /></svg>
    
            <svg class="circle_icon" width="650" height="650" style="color: var(--base-color); position: absolute; right: 10px; top: -250px;"><use xlink:href="#circle" /></svg>
            <svg class="circle_icon" width="150" height="150" style="color: var(--sub-color); position: absolute; right: 100px; top: 53vh;"><use xlink:href="#circle" /></svg>
            <svg class="circle_icon" width="500" height="500" style="color: var(--accent-color); position: absolute; left: 30px; bottom: -300px;"><use xlink:href="#circle" /></svg>
        </div>
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

            <div>
                <h2 style="margin-top: 0;">テーマカラー</h2>
                <input type="hidden" name="theme_color" id="selected-theme" value="aqua">
                <div class="color-options" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; padding: 0 20px;">
                    <div class="color-box {{ Auth::user()->theme_color == 'aqua' ? 'active' : '' }}" data-theme="aqua" style="aspect-ratio: 1 / 1; background-color: #87C8DE"></div>
                    <div class="color-box {{ Auth::user()->theme_color == 'pink' ? 'active' : '' }}" data-theme="pink" style="aspect-ratio: 1 / 1; background-color: #F7C3BF;"></div>
                    <div class="color-box {{ Auth::user()->theme_color == 'yellow' ? 'active' : '' }}" data-theme="yellow" style="aspect-ratio: 1 / 1; background-color: #ffcc00"></div>
                    <div class="color-box {{ Auth::user()->theme_color == 'red' ? 'active' : '' }}" data-theme="red" style="aspect-ratio: 1 / 1; background-color: #C41D19"></div>

                    <div class="color-box {{ Auth::user()->theme_color == 'blue' ? 'active' : '' }}" data-theme="blue" style="aspect-ratio: 1 / 1; background-color: #001D42"></div>
                    <div class="color-box {{ Auth::user()->theme_color == 'green' ? 'active' : '' }}" data-theme="green" style="aspect-ratio: 1 / 1; background-color: #4A5D67"></div>
                    <div class="color-box {{ Auth::user()->theme_color == 'rose' ? 'active' : '' }}" data-theme="rose" style="aspect-ratio: 1 / 1; background-color: #DFAEC9"></div>
                    <div class="color-box {{ Auth::user()->theme_color == 'purple' ? 'active' : '' }}" data-theme="purple" style="aspect-ratio: 1 / 1; background-color: #8E64E5"></div>
                </div>
            </div>

            <button id="submitBtn" type="submit" class="setting_save">保存</button>
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
        <div class="svg_icon" style="position: relative; height: 100vh;">
            <svg class="flower_icon" width="450" height="450" style="color: var(--accent-color); position: absolute; right: 10px; top: -250px;"><use xlink:href="#flower" /></svg>
            <svg class="flower_icon" width="180" height="200" style="color: var(--base-color); position: absolute; right: 42px; top: 26vh;"><use xlink:href="#flower" /></svg>
            <svg class="flower_icon" width="650" height="650" style="color: var(--sub-color); position: absolute; left: 0px; bottom: -300px;"><use xlink:href="#flower" /></svg>
        
            <svg class="circle_icon" width="450" height="450" style="color: var(--accent-color); position: absolute; right: 10px; top: -250px;"><use xlink:href="#circle" /></svg>
            <svg class="circle_icon" width="180" height="200" style="color: var(--base-color); position: absolute; right: 42px; top: 26vh;"><use xlink:href="#circle" /></svg>
            <svg class="circle_icon" width="650" height="650" style="color: var(--sub-color); position: absolute; left: 0px; bottom: -300px;"><use xlink:href="#circle" /></svg>
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
    <symbol id="flower" viewBox="0 0 33 33">
        <path d="M16.5 12a4.5 4.5 0 110 9 4.5 4.5 0 010-9zm0-2.02a6.52 6.52 0 100 13.03 6.52 6.52 0 000-13.03zM16.58 0a6.7 6.7 0 015.18 2.44l.37.5.55-.09.69-.03a6.72 6.72 0 016.58 8.07v.03l.09.06a6.71 6.71 0 010 11.14h-.02l.09.56a6.72 6.72 0 01-7.37 7.37l-.62-.1-.05.09a6.71 6.71 0 01-10.76.52l-.34-.47-.65.1a6.72 6.72 0 01-7.29-7.95v-.03l-.07-.04a6.71 6.71 0 010-11.14L3 11l-.01-.05A6.75 6.75 0 019.57 2.9c.47 0 .92.05 1.36.14h.03l.04-.07A6.71 6.71 0 0116.58 0z" fill="currentColor" fill-rule="evenodd"/>
    </symbol>
</svg>
<svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" style="display: none;">
    <symbol id="circle" viewBox="0 0 33 33">
        <path d="m28.68 27.56-.5.6c-1.5 1.5-3.28 2.7-5.26 3.54l-1.08.34zm3.17-5.05-.13.41-.82 1.51-12.85 8.41-1.56.16-.58-.06zm.89-3.24-.06.55-.3.99-18.26 11.95-.96-.1-.6-.18zm.26-2.6-.14 1.4L11.4 32.12l-1.35-.42zm-.22-2.55.12 1.22L8.88 31.07l-1.09-.6zm-.53-2.35.33 1.08L6.75 29.77l-.88-.73zm-.68-1.97.15.27.24.78L5.1 28.43l-.31-.26-.45-.55zm-1.1-2 .53.95L3.6 26.7l-.7-.84zm-1.22-1.67.7.85L2.4 25.02l-.52-.96zm-1.63-1.76.56.46.25.3-27.17 17.8-.34-1.08zm-1.74-1.43.89.73L.6 20.8l-.3-.98-.02-.11zM23.86 1.8l1.1.6L.16 18.61.05 17.4zm-12.92-.78L4.42 5.28l.38-.45c1.5-1.5 3.28-2.7 5.25-3.54zM21.34.8l1.35.42L0 16.07l.14-1.4zm-3.1-.63 1.58.16.1.03L.3 13.23v-.06l.5-1.57zM16.41 0 1.38 9.85l1.1-2.02L14.07.23z" fill="currentColor" fill-rule="evenodd"/>
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
            const isFlower = ['aqua', 'pink', 'yellow', 'red'].includes(themeName);
            document.body.classList.remove('theme-flower', 'theme-circle');
            document.body.classList.add(isFlower ? 'theme-flower' : 'theme-circle');
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
            updateSidebarIcons(themeName);
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

    //パスワード変更押下で読込画面
    const submitBtn = document.getElementById('submitBtn');
    if(submitBtn){
        submitBtn.addEventListener('click', (e) => {
            if (window.Loader) Loader.show();
        });
    }
</script>
@endpush