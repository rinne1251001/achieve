@extends('layouts.app')
@section('title', '設定')
@section('content')

<div class="wrapper3">
    <aside class="sidebar"></aside>
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
                    <div class="color-box {{ Auth::user()->theme_color == 'aqua' ? 'active' : '' }}" data-theme="aqua" style="aspect-ratio: 1 / 1; background-color: #BAE1E8;"></div>
                    <div class="color-box {{ Auth::user()->theme_color == 'pink' ? 'active' : '' }}" data-theme="pink" style="aspect-ratio: 1 / 1; background-color: #F7C3BF;"></div>
                    <div class="color-box {{ Auth::user()->theme_color == 'yellow' ? 'active' : '' }}" data-theme="yellow" style="aspect-ratio: 1 / 1; background-color: #ffcc00"></div>
                    <div class="color-box {{ Auth::user()->theme_color == 'blue' ? 'active' : '' }}" data-theme="blue" style="aspect-ratio: 1 / 1; background-color: #001D42"></div>
                </div>
            </div>

            <button type="submit" style="margin-top: 30px; background-color: var(--font-color);">保存</button>
        </form>
    </main>
    <aside class="sidebar"></aside>
</div>

@endsection

@push('scripts')
<script>
    const form = document.getElementById('settings-form');
    const themeInput = document.getElementById('selected-theme');
    const colorBoxes = document.querySelectorAll('.color-box');

    // 1. 選択状態の切り替え（枠線のみ）
    colorBoxes.forEach(box => {
        box.onclick = () => {
            const themeName = box.dataset.theme;
            
            // すべてのボックスから active クラスを削除して、クリックしたものだけに付与
            colorBoxes.forEach(b => b.classList.remove('active'));
            box.classList.add('active');

            // 隠し入力欄（hidden）に値をセット（ここではまだプレビューしない）
            themeInput.value = themeName;
        };
    });

    // 2. 保存機能
    form.onsubmit = async (e) => {
        e.preventDefault();
        
        try {
            const response = await fetch("{{ route('user-profile-information.update') }}", {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: new FormData(form)
            });

            if (response.ok) {
                alert('設定を保存しました！');
                // 保存が成功した後に色を反映させたい場合は、ここでリロードするか、
                // document.body.setAttribute('data-theme', themeInput.value); を実行します。
                location.reload(); // 保存完了後に画面をリロードして最新状態を反映
            } else {
                alert('エラーが発生しました。入力内容を確認してください。');
            }
        } catch (error) {
            console.error('通信エラー:', error);
        }
    };
</script>
@endpush