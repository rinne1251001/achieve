@extends('layouts.app')
@section('title', '性格指向テスト')
@section('content')

<main>
    <div>
        <div class="chat_container" id="chatContainer">
            <div class="chat_aicmt" id="step1">
                こんにちは！<br>あなたの性格指向について選択してください。
                <div class="chat_choice_wrapper">
                    <h3 id="questionTitle">あなたの悩みについて教えてください</h3>
                    <p>※複数選択可</p>
                    
                    <div id="choiceContainer1" class="chat_choices_container"></div>
                    
                    <button class="chat_sendBtn" onclick="nextStep(1)">送信</button>
                </div>
            </div>
        </div>
        <div id="input-bar" style="position: fixed; bottom: 0; left: 50%; transform: translateX(-50%); width: min(700px, 100% - 20px); padding: 10px 0 20px; background-color: var(--bg-color); box-shadow: 10px -10px 10px var(--bg-color); display: flex; align-items: flex-end; gap: 10px;">        
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
<script type="module">
    import { processChecklist } from "{{ asset('js/task_gen.js') }}";

    let allAnswers = [];

    // --- シナリオ設定：質問と選択肢のグループ分け ---
    const scenario = [
        {
            title: "あなたの悩みについて教えてください",
            choices: ["やりたいことがわからない", "自信がない", "行動できない", "将来が不安"]
        },
        {
            title: "現在の具体的な状況を教えてください",
            choices: ["何から始めればいいかわからない", "モチベーションが低い", "情報が多すぎて迷う"]
        },
        {
            title: "理想の状態について教えてください",
            choices: ["興味の方向性が見える", "自信がつく", "小さく行動できる"]
        },
        {
            title: "心理的な障壁はありますか？",
            choices: ["失敗が怖い", "続かない", "他人と比べてしまう"]
        },
        {
            title: "あなたの強みや資質について教えてください",
            choices: ["コツコツ続けられる", "好奇心がある", "人の話を聞ける"]
        }
    ];

    let currentStepIndex = 0;

    // 選択肢生成関数
    function renderChoices(containerId, keys) {
        const container = document.getElementById(containerId);
        container.innerHTML = keys.map(key => `
            <div class="chat_choice" data-value="${key}">${key}</div>
        `).join('');
    }

    // 初回表示
    document.addEventListener('DOMContentLoaded', () => {
        renderChoices('choiceContainer1', scenario[0].choices);
    });

    // クリックイベント
    document.addEventListener('mousedown', (e) => {
        const target = e.target.closest('.chat_choice');
        if (target) target.classList.toggle('is-selected');
    });

    // 次のステップへ進むメインロジック
    window.nextStep = function(step) {
        const currentContainer = document.querySelector(`#step${step}`);
        const selected = Array.from(currentContainer.querySelectorAll('.chat_choice.is-selected'))
                              .map(el => el.dataset.value);

        if (selected.length === 0) return alert('ひとつ以上選択してください');

        // 自分の回答を表示
        addChat(selected.join('、 '), 'my');
        allAnswers = [...allAnswers, ...selected];

        // ボタンを消す（連打防止）
        currentContainer.querySelector('button').style.display = 'none';

        currentStepIndex++;

        // 次の質問があるか確認
        if (currentStepIndex < scenario.length) {
            setTimeout(() => {
                const nextId = step + 1;
                const nextData = scenario[currentStepIndex];
                const aiMsg = `
                <div class="chat_aicmt" id="step${nextId}">
                    <div class="chat_choice_wrapper">
                        <h3>${nextData.title}</h3>
                        <p>※複数選択可</p>
                        <div id="choiceContainer${nextId}" class="chat_choices_container"></div>
                        <button class="chat_sendBtn" onclick="nextStep(${nextId})">送信</button>
                    </div>
                </div>`;
                document.getElementById('chatContainer').insertAdjacentHTML('beforeend', aiMsg);
                renderChoices(`choiceContainer${nextId}`, nextData.choices);
            }, 800);
        } else {
            // すべての質問が終わったら診断実行
            const finalResult = processChecklist(allAnswers);
            setTimeout(() => {
                const resultHtml = `
                    <p>診断が完了しました！あなたは<strong>【${finalResult.userType}】</strong>です。</p>
                    <p>最初の大きな目標:</strong>${finalResult.bigTask}</p>

                    <div>
                        <strong>具体的なステップ</strong>
                        <ol style="margin-top: 0;">
                            <li>${finalResult.taskTree.children[0].children[0].title}</li>
                            <li>${finalResult.taskTree.children[1].children[0].title}</li>
                            <li>${finalResult.taskTree.children[2].children[0].title}</li>
                        </ol>
                    </div>
                `;
                addChat(resultHtml, 'ai');
            }, 1000);
        }
    };

    function addChat(text, type) {
        const className = type === 'ai' ? 'chat_aicmt' : 'chat_mycmt';
        const html = `<div class="${className}">${text}</div>`;
        document.getElementById('chatContainer').insertAdjacentHTML('beforeend', html);
        window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
    }

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