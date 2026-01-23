@extends('layouts.app')
@section('title', 'チャットのテストページ')
@section('content')

<main>
    <div>
        <div class="chat_container">
            <div class="chat_aicmt">AIのコメント。AIのコメント。AIのコメント。AIのコメント。AIのコメント。AIのコメント。</div>
            <div class="chat_mycmt">コメント</div>
            <div class="chat_aicmt">AIのコメント</div>
            <div class="chat_mycmt">コメント</div>
            <div class="chat_aicmt">AIのコメント</div>
            <div class="chat_mycmt">コメント</div>
            <div class="chat_aicmt">AIのコメント</div>
            <div class="chat_mycmt">コメント</div>
            <div class="chat_aicmt">AIのコメント</div>
            <div class="chat_mycmt">コメント</div>
        </div>

        <div id="input-bar" style="position: fixed; bottom: 0; left: 50%; transform: translateX(-50%); width: min(700px, 100% - 20px); padding: 10px 0 20px; background-color: var(--bg-color); box-shadow: 0 -10px 10px var(--bg-color); display: flex; align-items: flex-end; gap: 10px;">        
            <div style="flex-grow: 1;">
                <textarea id="chat-input" class="chat_input" placeholder="入力" rows="1" style="width: 100%;"></textarea>
            </div>
            <button style="background-color: var(--base-color); border-radius: 50%; border: none; width: 50px; height: 50px; display: flex; justify-content: center; align-items: center; cursor: pointer; flex-shrink: 0;">
                <span class="material-symbols-outlined" style="color: var(--bg-color);">send</span>
            </button>
        </div>
    </div>
</main>

@endsection
@push('scripts')
<script>
    const el = {
        input: document.getElementById('chat-input'),
        bar: document.getElementById('input-bar'),
        container: document.querySelector('.chat_container')
    };

    const adjust = () => {
        const MAX_H = 200;
        el.input.style.height = 'auto';
        const newH = Math.min(el.input.scrollHeight, MAX_H);
        el.input.style.height = `${newH}px`;
        el.input.style.overflowY = el.input.scrollHeight > MAX_H ? 'auto' : 'hidden';
        el.container.style.paddingBottom = `${el.bar.offsetHeight + 20}px`;
    };

    el.input.oninput = adjust;
    window.onload = adjust;
</script>
@endpush