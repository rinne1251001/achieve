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
            <button id="chat_input_sendBtn">
                <span class="material-symbols-outlined">send</span>
            </button>
        </div>
    </div>
</main>

@endsection

@push('scripts')
<script type="module">
    import { processChecklist } from "{{ asset('js/task_gen.js') }}";

    let allAnswers = [];
    let currentStepIndex = 0;

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
            choices: ["やりたいことがわかる", "自信がつく", "行動できる"]
        },
        {
            title: "心理的な障壁はありますか？",
            choices: ["失敗が怖い", "続かない", "他人と比べてしまう","人前で話すのが苦手"]
        },
        {
            title: "あなたの強みや資質について教えてください",
            choices: ["コツコツ続けられる", "好奇心がある", "人の話を聞ける"]
        }
    ];

    function renderChoices(containerId, keys) {
        const container = document.getElementById(containerId);
        if (!container) return;
        container.innerHTML = keys.map(key => `
            <div class="chat_choice" data-value="${key}">${key}</div>
        `).join('');
    }

    // 初回表示
    document.addEventListener('DOMContentLoaded', () => {
        renderChoices('choiceContainer1', scenario[0].choices);
    });

    // 選択イベント
    document.addEventListener('mousedown', (e) => {
        const target = e.target.closest('.chat_choice');
        if (target) target.classList.toggle('is-selected');
    });

    // メインロジック（asyncを追加）
    window.nextStep = async function(step) {
        const currentContainer = document.querySelector(`#step${step}`);
        const selected = Array.from(currentContainer.querySelectorAll('.chat_choice.is-selected'))
                              .map(el => el.dataset.value);

        if (selected.length === 0) return alert('ひとつ以上選択してください');

        addChat(selected.join('、 '), 'my');
        allAnswers = [...allAnswers, ...selected];

        // ボタン無効化
        const btn = currentContainer.querySelector('button');
        if (btn) btn.style.display = 'none';

        currentStepIndex++;

        if (currentStepIndex < scenario.length) {
            // 次の質問へ
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
                window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
            }, 600);
        } else {
            // 診断実行
            addChat("診断中...", 'ai');
            
            try {
                // API通信を待機
                const finalResult = await processChecklist(allAnswers);

                setTimeout(() => {
                    // 安全にプロパティへアクセスするための記述
                    const step1 = finalResult.taskTree?.children?.[0]?.children?.[0]?.title || "ステップ1";
                    const step2 = finalResult.taskTree?.children?.[1]?.children?.[0]?.title || "ステップ2";
                    const step3 = finalResult.taskTree?.children?.[2]?.children?.[0]?.title || "ステップ3";

                    const resultHtml = `
                        <div class="result-box">
                            <p>診断が完了しました！</p>
                            <p><strong>最初の大きな目標:</strong><br>${finalResult.bigTask}</p>
                            <div style="margin-top: 15px; padding: 10px; background: rgba(255,255,255,0.5); border-radius: 8px;">
                                <strong>明日からの3ステップ:</strong>
                                <ol style="margin: 5px 0 0 20px; padding: 0;">
                                    <li>${step1}</li>
                                    <li>${step2}</li>
                                    <li>${step3}</li>
                                </ol>
                            </div>
                        </div>
                    `;
                    addChat(resultHtml, 'ai');
                }, 1000);
            } catch (error) {
                console.error(error);
                addChat("エラーが発生しました。時間を置いて再度お試しください。", 'ai');
            }
        }
    };

    function addChat(text, type) {
        const className = type === 'ai' ? 'chat_aicmt' : 'chat_mycmt';
        const html = `<div class="${className}">${text}</div>`;
        document.getElementById('chatContainer').insertAdjacentHTML('beforeend', html);
        window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
    }

    // 入力エリアの自動調整
    const el = {
        input: document.getElementById('chat-input'),
        bar: document.getElementById('input-bar'),
        container: document.querySelector('.chat_container')
    };

    const adjust = () => {
        if (!el.input || !el.bar) return;
        const MAX_H = 200;
        el.input.style.height = 'auto';
        const newH = Math.min(el.input.scrollHeight, MAX_H);
        el.input.style.height = `${newH}px`;
        el.input.style.overflowY = el.input.scrollHeight > MAX_H ? 'auto' : 'hidden';
        if (el.container) {
            el.container.style.paddingBottom = `${el.bar.offsetHeight + 40}px`;
        }
    };

    if (el.input) el.input.oninput = adjust;
    window.onload = adjust;

    /*
    本番で使うinputの送るボタン
        const sendBtn = document.getElementById('chat_input_sendBtn');
        if (sendBtn) {
            sendBtn.addEventListener('click', () => {
                const inputEl = document.getElementById('chat-input');
                const text = inputEl.value.trim(); // 空白を除去

                if (text !== "") {
                    // 自作のaddChat関数を呼び出す
                    addChat(text, 'my');
                    
                    // 入力欄を空にして高さをリセット
                    inputEl.value = "";
                    adjust(); 
                }
            });
        }
    */

    //今日のデモでのみ使うAIが考えた風リアルタイム返信
    // スクリプトの上のほう（グローバルな場所）にカウンターを用意
    let aiResponseCount = 0;

    // 送信ボタンのクリックイベント
    const sendBtn = document.getElementById('chat_input_sendBtn');
    if (sendBtn) {
        sendBtn.addEventListener('click', () => {
            const inputEl = document.getElementById('chat-input');
            const text = inputEl.value.trim();

            if (text !== "") {
                // 1. ユーザーのメッセージを表示
                addChat(text, 'my');
                
                inputEl.value = "";
                adjust();

                // 2. 返信内容のリストを作成
                const responses = [
                    "かしこまりました<br>一緒に考えていきましょう！<br>つきましては今あなたが興味をもっているものを教えてください",
                    "それは興味深いですね！<br>ちなみにフロントエンドとバックエンドのどちらが好きですか？",
                    "いいですね！<br>では、【cssを自在に使えるようにする】というゴールはいかがですか？"
                ];

                // 3. AIの返信（1秒後）
                setTimeout(() => {
                    // 現在の回数に応じたメッセージを選択（3回目以降は最後のを繰り返す設定）
                    let msgIndex = aiResponseCount;
                    if (msgIndex >= responses.length) {
                        msgIndex = responses.length - 1; // 3番目以降は「もう少し掘り下げて〜」を出す
                    }

                    addChat(responses[msgIndex], 'ai');

                    // 次回のためにカウンターを増やす
                    aiResponseCount++;
                }, 1000);
            }
        });
    }
</script>
@endpush