@extends('layouts.app')
@section('title', 'チャットテスト')
@section('content')

<main>
    <div>
        <div class="chat_container" id="chatContainer">
            <div class="chat_aicmt">
                <p>
                    {{ $greeting }}<br>
                    今日は何をしますか？
                </p>
                <div class="chat_3btn_container">
                    <div class="chat_firstbtn">ゴールを決める</div>
                    <div class="chat_firstbtn">タスクを決める</div>
                    <div class="chat_firstbtn">性格診断をする</div>
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
    let currentMode = 'default';
    let suggestionIndex = 0;
    let currentCategory = "";
    let lastProposedData = null;

    const sendBtn = document.getElementById('chat_input_sendBtn');
    const inputEl = document.getElementById('chat-input');
    const chatContainer = document.getElementById('chatContainer');
    const inputBar = document.getElementById('input-bar');

    function addChat(text, type) {
        const className = type === 'ai' ? 'chat_aicmt' : 'chat_mycmt';
        const html = `<div class="${className}">${text}</div>`;
        chatContainer.insertAdjacentHTML('beforeend', html);
        window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
    }

    function showMainMenu() {
        const menuHtml = `
            <div class="chat_aicmt">
                <p>次はどうしますか？</p>
                <div class="chat_3btn_container">
                    <div class="chat_firstbtn">ゴールを決める</div>
                    <div class="chat_firstbtn">タスクを決める</div>
                    <div class="chat_firstbtn">性格診断をする</div>
                </div>
            </div>`;
        chatContainer.insertAdjacentHTML('beforeend', menuHtml);
        window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
    }

    const adjust = () => {
        if (!inputEl || !inputBar) return;
        const MAX_H = 200;
        inputEl.style.height = 'auto';
        const newH = Math.min(inputEl.scrollHeight, MAX_H);
        inputEl.style.height = `${newH}px`;
        inputEl.style.overflowY = inputEl.scrollHeight > MAX_H ? 'auto' : 'hidden';
        if (chatContainer) {
            chatContainer.style.paddingBottom = `${inputBar.offsetHeight + 40}px`;
        }
    };

    if (inputEl) inputEl.oninput = adjust;
    window.onload = adjust;

    // 2. サーバー通信用の共通関数 (ここから修正)
    async function handleServerCommunication(text) {
        if (text === '他の案も見たい') {
            suggestionIndex++;
            text = currentCategory;
        } else {
            suggestionIndex = 0;
            currentCategory = text;
        }

        try {
            const response = await fetch('/chat_test2', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ 
                    message: text,
                    mode: currentMode,
                    index: suggestionIndex
                })
            });

            const data = await response.json();

            // ゴール一覧が返ってきた場合
            if (data.status === 'goal_list') {
                if (data.goals.length === 0) {
                    addChat('現在、登録されている未完了のゴールがありません。まずはゴールを決めてみませんか？', 'ai');
                } else {
                    let reply = 'かしこまりました！<br>どのゴールのタスクを決めますか？';
                    reply += '<div class="chat_3btn_container" style="margin-top:10px;">';
                    data.goals.forEach(g => {
                        reply += `<div class="chat_firstbtn">${g.goal}</div>`;
                    });
                    reply += '</div>';
                    addChat(reply, 'ai');
                }
                return;
            }

            // 成功（タスク提案）の場合
            if (data.status === 'success') {
                lastProposedData = {
                    goal: data.goal,
                    tasks: data.tasks
                };
                let reply = "";
                if (data.message) {
                    reply += `<p style="margin-bottom:10px;">${data.message}</p>`;
                }
                // タスク決めモードだった場合
                if (currentMode === 'task_only') {
                    reply = `<p>「${data.goal}」のタスクですね。こんなのはいかがですか？</p><ul style="padding: 0 20px; list-style-type: none;">`;
                    data.tasks.forEach(task => { reply += `<li style="margin-bottom: 8px;">・ ${task}</li>`; });
                    reply += '</ul>';
                    reply += `
                        <div class="chat_2btn_container" style="margin-top:15px;">
                            <div class="chat_firstbtn">これで頑張る</div>
                            <div class="chat_firstbtn">他の案も見たい</div>
                        </div>`;
                    currentMode = 'default' 
                } else {
                    // 通常のゴール提案
                    reply += `
                        <div style="background: var(--font-color); color: var(--bg-color); padding: 15px; border-radius: 10px; margin: 20px 10px 10px;">
                            <strong style="font-size: 1.1em;">目標：${data.goal}</strong>
                            <ul style="padding-left:20px; list-style-type: decimal;">
                    `;
                    data.tasks.forEach(task => { 
                        reply += `<li style="margin-bottom: 5px;">${task}</li>`; 
                    });
                    reply += `</ul></div>`;

                    // 3. 「これで頑張る」ボタンを必ず表示
                    reply += `<div class="chat_2btn_container" style="margin-top:15px;">
                                <div class="chat_firstbtn">これで頑張る</div>`;
                    
                    // 次の案がある場合のみ「他の案」を表示
                    if (data.has_more) {
                        reply += `<div class="chat_firstbtn">他の案も見たい</div>`;
                    }
                    
                    reply += `</div>`;
                }               
                addChat(reply, 'ai');
            } else if (data.status === 'no_more') {
                addChat(data.message, 'ai');
                suggestionIndex = 0;
            } else if (data.status === 'no_dislike') {
                addChat(data.message, 'ai');
                setTimeout(() => {
                    addChat('<p>例えば、こんな方向性ではどうでしょう？</p><div class="chat_3btn_container"><div class="chat_firstbtn">新しい趣味</div><div class="chat_firstbtn">収入を増やす</div><div class="chat_firstbtn">自分を磨く</div></div>', 'ai');
                }, 1000);
            } else if (data.status === 'inversion') {
                let reply = `${data.message}<br><ul style="padding-left:20px;">`;
                data.goals.forEach(g => { reply += `<li>${g}</li>`; });
                reply += '</ul>';
                addChat(reply, 'ai');
            } else {
                addChat(data.message, 'ai');
            }
        } catch (error) {
            console.error('通信エラー:', error);
            addChat('すみません、エラーが発生しました。', 'ai');
        }
    }

    // 3. クリックイベント
    document.addEventListener('click', (e) => {
        if (e.target.classList.contains('chat_firstbtn')) {
            const container = e.target.closest('.chat_3btn_container, .chat_2btn_container');
            
            // すでにクリック済み（pointerEventsがnone）なら何もしない
            if (!container || container.style.pointerEvents === 'none') return;

            // --- ボタンを無効化し、アニメーションで消す処理 ---
            container.style.pointerEvents = 'none';
            e.target.classList.add('is-selected'); // 選択されたボタンを強調（CSSがあれば）

            setTimeout(() => {
                container.style.opacity = '0';
                container.style.transition = 'opacity 0.5s ease';
                setTimeout(() => container.remove(), 500);
            }, 500);

            const buttonText = e.target.innerText;
            addChat(buttonText, 'my');

            // --- モードに応じた分岐処理 ---
            
            // A. タスク決めモード中に、具体的なゴール名が選ばれた場合
            if (currentMode === 'task_only' && buttonText !== 'タスクを決める') {
                handleServerCommunication(buttonText);
                return;
            }

            // B. 通常の分岐
            if (buttonText === 'タスクを決める') {
                currentMode = 'task_only';
                handleServerCommunication(buttonText);
            } else if (buttonText === 'ゴールを決める') {
                currentMode = 'goal_deciding';
                setTimeout(() => {
                    addChat('<p>では今興味を持っていることはありますか？</p><div class="chat_2btn_container"><div class="chat_firstbtn">ある</div><div class="chat_firstbtn">ない</div></div>', 'ai');
                }, 500);
            } else if (buttonText === 'ある') {
                currentMode = 'interest_exist';
                setTimeout(() => addChat('素敵ですね！具体的にどんなことに興味がありますか？', 'ai'), 500);
            } else if (buttonText === 'ない') {
                currentMode = 'interest_none';
                setTimeout(() => addChat('大丈夫ですよ。では、逆に「嫌いなこと」を教えてください。', 'ai'), 500);
            } else if (['新しい趣味', '収入を増やす', '自分を磨く'].includes(buttonText)) {
                setTimeout(() => {
                    let replyText = "";
                    let nextButtons = "";
                    if (buttonText === '新しい趣味') {
                        replyText = 'いいですね！理想の過ごし方を選んでみてください。';
                        nextButtons = '<div class="chat_3btn_container"><div class="chat_firstbtn">家でまったり</div><div class="chat_firstbtn">外でアクティブ</div><div class="chat_firstbtn">何かを作る</div></div>';
                    } else if (buttonText === '収入を増やす') {
                        replyText = '現実的で素敵です。どの方向に興味がありますか？';
                        nextButtons = '<div class="chat_3btn_container"><div class="chat_firstbtn">今の仕事を頑張る</div><div class="chat_firstbtn">副業を始める</div><div class="chat_firstbtn">ポイ活・節約</div></div>';
                    } else if (buttonText === '自分を磨く') {
                        replyText = '最高ですね！どのあたりから整えたいですか？';
                        nextButtons = '<div class="chat_3btn_container"><div class="chat_firstbtn">見た目・健康</div><div class="chat_firstbtn">知識・スキル</div><div class="chat_firstbtn">考え方・メンタル</div></div>';
                    }
                    addChat(replyText + nextButtons, 'ai');
                }, 500);
            } else if (['家でまったり', '外でアクティブ', '何かを作る', '今の仕事を頑張る', '副業を始める', 'ポイ活・節約', '見た目・健康', '知識・スキル', '考え方・メンタル'].includes(buttonText)) {
                currentMode = 'category_selected';
                handleServerCommunication(buttonText);
            } else if (buttonText === '他の案も見たい') {
                handleServerCommunication(buttonText);
            } else if (buttonText === 'これで頑張る') {
                if (lastProposedData) {
                    saveGoalAndTasks(lastProposedData);
                }
                setTimeout(() => {
                    addChat('応援しています！DBに保存しました。一緒に頑張りましょう。', 'ai');
                    setTimeout(showMainMenu, 1000); 
                }, 500);
            } else if (buttonText === '性格診断をする') {
                currentMode = 'assessment_only';
                setTimeout(() => addChat('かしこまりました！準備ができたら教えてくださいね。', 'ai'), 500);
            }
        }
    });

    async function saveGoalAndTasks(data) {
        try {
            const response = await fetch('/chat_test2_save', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();
            if (result.status === 'success') {
                console.log('保存成功');
            }
        } catch (error) {
            console.error('保存失敗:', error);
        }
    }

    // 4. 送信ボタン (ここはそのままでOK)
    if (sendBtn) {
        sendBtn.addEventListener('click', async () => {
            const text = inputEl.value.trim();
            if (text !== "") {
                addChat(text, 'my');
                inputEl.value = "";
                adjust();
                await handleServerCommunication(text);
            }
        });
    }
</script>
@endpush