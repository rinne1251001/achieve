@extends('layouts.app')
@section('title', 'チャットボット')
@section('content')

<main>
    <div class="chat_wrapper">
        <div class="chat_container" id="chatContainer">
            <div class="chat_aicmt_container">
                <div class="robot_container chat_robot">
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
                </div>
                <div class="chat_aicmt">
                    <p>
                        {{ $greeting }}<br>
                        今日は何をしますか？
                    </p>
                    <div class="chat_3btn_container">
                        <div class="chat_btn">ゴールを決める</div>
                        <div class="chat_btn">タスクを決める</div>
                        <div class="chat_btn">性格診断をする</div>
                    </div>
                </div>
            </div>
        </div>

        <div id="input-bar" class="chat_input_container">        
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
    let assessmentStep = 0;
    let assessmentAnswers = [];
    let currentGoalId = null;

    const sendBtn = document.getElementById('chat_input_sendBtn');
    const inputEl = document.getElementById('chat-input');
    const chatContainer = document.getElementById('chatContainer');
    const inputBar = document.getElementById('input-bar');

    const robotIcon = `
        <div class="robot_container chat_robot">
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
        </div>
    `;

    function addChat(text, type) {
        let html = '';
        if (type === 'ai') {
            html = `
            <div class="chat_aicmt_container">
                ${robotIcon}
                <div class="chat_aicmt">${text}</div>
            </div>`;
        } else {
            html = `<div class="chat_mycmt">${text}</div>`;
        }
        chatContainer.insertAdjacentHTML('beforeend', html);
        window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
    }

    function showMainMenu() {
        const menuHtml = `
            <div class="chat_aicmt_container">
                ${robotIcon}
                <div class="chat_aicmt">
                    <p>次はどうしますか？</p>
                    <div class="chat_3btn_container">
                        <div class="chat_btn">ゴールを決める</div>
                        <div class="chat_btn">タスクを決める</div>
                        <div class="chat_btn">性格診断をする</div>
                    </div>
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
    let currentTaskOffset = 0;
    async function handleServerCommunication(text, goalId = null) {
        if (text === '他の案も見たい') {
            if (currentMode === 'task_only') {
                if (lastProposedData?.hasMoreTasks) {
                    currentTaskOffset += 3;
                } else {
                    suggestionIndex += 1;
                    currentTaskOffset = 0;
                }
                // task_only モードでは保存済みの goalId を使用
                goalId = currentGoalId;
            } else {
                suggestionIndex += 1;
                currentTaskOffset = 0;
            }
            text = currentCategory;
        } else {
            suggestionIndex = 0;
            currentTaskOffset = 0;
            currentCategory = text;
        }

        try {
            const response = await fetch('/chat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ 
                    message: text,
                    goal_id: goalId,
                    mode: currentMode,
                    index: suggestionIndex,
                    task_offset: currentTaskOffset
                })
            });
            if (!response.ok) throw new Error(`HTTP ${response.status}`);
            const data = await response.json();

            // 1. 既存ゴール一覧表示（タスク決めモードの入り口）
            if (data.status === 'goal_list') {
                if (!data.goals || data.goals.length === 0) {
                    addChat('現在、未完了のゴールがありません。まずはゴールを決めましょう！', 'ai');
                } else {
                    let reply = 'どのゴールのタスクを決めますか？';
                    reply += '<div class="chat_3btn_container" style="margin-top:10px;">';
                    data.goals.forEach(g => {
                        reply += `<div class="chat_btn" data-goal-id="${g.id}">${Html.escape(g.goal)}</div>`;
                    });
                    reply += '</div>';
                    addChat(reply, 'ai');
                }
                return;
            }

            if (data.status === 'no_dislike') {
                addChat(data.message, 'ai');
                // 3つの選択肢を表示して、ポジティブな提案モードへ誘導
                setTimeout(() => {
                    const reply = 'では、前向きにやりたいことを探してみましょうか。';
                    const buttons = `<div class="chat_3btn_container">
                                        <div class="chat_btn">新しい趣味</div>
                                        <div class="chat_btn">収入を増やす</div>
                                        <div class="chat_btn">自分を磨く</div>
                                     </div>`;
                    addChat(reply + buttons, 'ai');
                }, 500);
                return;
            }

            // 2. 提案ロジック（ゴール新規作成 or タスクのみ提案）
            if (data.status === 'success') {
                // inversion(逆算)の場合は配列 data.goals から取得、通常は data.goal から取得
                const isTaskOnly = (currentMode === 'task_only');
                let displayGoal = data.goal || (data.goals ? data.goals[suggestionIndex] : "");
                let displayTasks = data.tasks || [];
                
                // 次の案があるか判定（has_moreキー、または逆算配列の次があるか）
                let hasNext = data.has_more;

                // 保存用データの作成
                lastProposedData = {
                    goal              : displayGoal,
                    tasks             : displayTasks,
                    mode              : currentMode, // モードを含めることで、保存側で新規か更新か判断させる
                    goal_id           : currentGoalId ?? null,
                    hasMoreTasks      : data.has_more_tasks       ?? false,
                    hasMoreSuggestions: data.has_more_suggestions ?? false,
                };

                let reply = "";

                if (data.message) {
                    reply += `<p style="margin-bottom:10px;">${Html.escape(data.message)}</p>`;
                }

                // --- 表示エリア ---
                if (isTaskOnly) {
                    // タスク決めモード：タスクリストのみ強調
                    reply += `<p>「<strong>${Html.escape(displayGoal)}</strong>」におすすめのタスク：</p>`;
                } else {
                    // ゴール決めモード：ゴール名もカード内に表示
                    reply += `<div style="background: var(--bg-color); color: var(--font-color); padding: 15px; border-radius: 10px; margin: 10px 0;">
                                <strong style="font-size: 1.1em;">目標：${Html.escape(displayGoal)}</strong>`;
                }

                reply += `<ul style="padding-left:20px; margin-top:10px; list-style-type: decimal;">`;
                displayTasks.forEach(task => { 
                    reply += `<li style="margin-bottom: 5px;">${Html.escape(task)}</li>`; 
                });
                reply += `</ul>${isTaskOnly ? '' : '</div>'}`;

                // --- ボタンエリア ---
                reply += `<div class="chat_2btn_container" style="margin-top:15px;">
                            <div class="chat_btn">これで頑張る</div>`;
                
                if (hasNext) {
                // まだ次の3件があるなら「他の案」ボタンを出す
                    reply += `<div class="chat_btn">他の案も見たい</div>`;
                } else if (suggestionIndex === 0 && currentTaskOffset >= 6) {
                    // もし今の提案例を出し切ったら、次の提案例（suggestionIndex+1）へ促す処理を追加してもOK
                }
                reply += `</div>`;
                
                addChat(reply, 'ai');

                // 重要：ここで currentMode をリセットしない！
                // 「これで頑張る」を押すまでモードを維持しないと、保存処理が「新規」か「既存」か判別できません。
                return;
            }

            // 3. その他
            if (data.status === 'no_more') {
                addChat(Html.escape(data.message), 'ai');
                suggestionIndex = 0;
            } else {
                addChat(Html.escape(data.message), 'ai');
            }

        } catch (error) {
            console.error('通信エラー:', error);
            addChat('すみません、エラーが発生しました。', 'ai');
        }
    }

    const traitsConfig = @json(config('user_analysis.traits'));

    // JSで使いやすいように質問配列に変換
    const assessmentQuestions = Object.keys(traitsConfig).map(key => {
        return {
            id: key,
            text: traitsConfig[key].question,
            options: [
                traitsConfig[key].options.A.label,
                traitsConfig[key].options.B.label
            ]
        };
    });

    function showNextAssessmentQuestion() {
        if (assessmentStep < assessmentQuestions.length) {
            const q = assessmentQuestions[assessmentStep];
            let buttons = '<div class="chat_2btn_container" style="margin-top:10px;">';
            q.options.forEach(opt => {
                buttons += `<div class="chat_btn">${opt}</div>`;
            });
            buttons += '</div>';
            
            setTimeout(() => {
                addChat(q.text + buttons, 'ai');
            }, 500);
        } else {
            // 全問回答後の処理
            finishAssessment();
        }
    }

    // 最後にまとめてサーバーへ送る
    async function finishAssessment() {
        addChat('分析中...<br>あなたにぴったりのタスクの立て方を考えています。', 'ai');
        
        try {
            // PHP側へ回答を送信
            const response = await fetch('/chat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ 
                    message: assessmentAnswers, // ['A', 'B', 'A', 'A']
                    mode: 'assessment_submit'
                })
            });
            if (!response.ok) throw new Error(`HTTP ${response.status}`);
            const data = await response.json();
            
            if (data.status === 'success') {
                const safeMessage = Html.escape(data.message).replace(/\n/g, '<br>');
                addChat(safeMessage, 'ai');
                setTimeout(showMainMenu, 1500); // 結果を読ませてからメニュー表示
            } else {
                addChat('診断結果の取得に失敗しました。もう一度お試しください。', 'ai');
            }
        } catch (error) {
            console.error(error);
            addChat('通信エラーが発生しました。もう一度お試しください。', 'ai');
        } finally {
            currentMode = 'default'; // 成功・失敗問わず必ずリセット
        }
    }

    // 3. クリックイベント
    document.addEventListener('click', async (e) => {
        if (e.target.classList.contains('chat_btn')) {
            const container = e.target.closest('.chat_3btn_container, .chat_2btn_container');
            
            if (!container || container.style.pointerEvents === 'none') return;

            // ボタン無効化と消去
            container.style.pointerEvents = 'none';
            e.target.classList.add('is-selected');

            setTimeout(() => {
                container.style.opacity = '0';
                container.style.transition = 'opacity 0.5s ease';
                setTimeout(() => container.remove(), 500);
            }, 500);

            const buttonText = e.target.innerText;
            const goalId = e.target.dataset.goalId ?? null;

            if (buttonText === 'これで頑張る') {
                if (!lastProposedData) {
                    addChat('保存するデータがありません。もう一度お試しください。', 'ai');
                    return;
                }

                // await で保存完了を待ってからメッセージを表示
                const success = await saveGoalAndTasks(lastProposedData);

                if (success) {
                    addChat('応援しています！DBに保存しました。一緒に頑張りましょう。', 'ai');
                    currentMode = 'default';
                    setTimeout(showMainMenu, 1500);
                }
                // 失敗時は saveGoalAndTasks 内で既にエラーメッセージを表示
                return;
            }

            // --- 修正箇所：他の案も見たい ---
            if (buttonText === '他の案も見たい') {
                handleServerCommunication(buttonText, goalId);
                return; // ここで終了
            }

            // これ以降は「自分の発言」として処理されるもの
            addChat(buttonText, 'my');

            // --- モードに応じた分岐処理 ---
            if (currentMode === 'task_only' && buttonText !== 'タスクを決める') {
                currentGoalId = goalId;
                handleServerCommunication(buttonText, goalId);
                return;
            }

            // --- 性格診断の進行管理 ---
            if (currentMode === 'assessment_only') {
                // ユーザーの回答を表示（addChatは既に上でされている場合は不要、されてないならここで）
                // assessmentAnswersには「A」か「B」の識別子だけ入れると後が楽
                const answerKey = buttonText.startsWith('A') ? 'A' : 'B';
                assessmentAnswers.push(answerKey);
                assessmentStep++;
                
                if (assessmentStep < assessmentQuestions.length) {
                    showNextAssessmentQuestion();
                } else {
                    finishAssessment();
                }
                return;
            }

            // --- 初期メニューからの分岐 ---
            if (buttonText === '性格診断をする') {
                currentMode = 'assessment_only';
                assessmentStep = 0;
                assessmentAnswers = [];
                addChat('あなたの思考タイプに合わせてタスクを提案するための診断を始めます（全4問）。', 'ai');
                showNextAssessmentQuestion(); // 確実に呼び出す
                return;
            } else if (buttonText === 'タスクを決める') {
                currentMode = 'task_only';
                handleServerCommunication(buttonText);
            } else if (buttonText === 'ゴールを決める') {
                try {
                    const checkRes = await fetch('/check-goal-limit');
                    const checkData = await checkRes.json();
                    if (checkData.isLimit) {
                        addChat('ゴールは最大3つまで登録可能です。<br>今あるゴールを頑張りましょう！', 'ai');
                        currentMode = 'default'; 
                        setTimeout(showMainMenu, 1500);
                        return;
                    }
                } catch (error) {
                    console.error('上限チェック通信エラー:', error);
                }

                currentMode = 'goal_deciding';
                setTimeout(() => {
                    addChat('<p>では今興味を持っていることはありますか？</p><div class="chat_2btn_container"><div class="chat_btn">ある</div><div class="chat_btn">ない</div></div>', 'ai');
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
                        nextButtons = '<div class="chat_3btn_container"><div class="chat_btn">家でまったり</div><div class="chat_btn">外でアクティブ</div><div class="chat_btn">何かを作る</div></div>';
                    } else if (buttonText === '収入を増やす') {
                        replyText = '現実的で素敵です。どの方向に興味がありますか？';
                        nextButtons = '<div class="chat_3btn_container"><div class="chat_btn">今の仕事を頑張る</div><div class="chat_btn">副業を始める</div><div class="chat_btn">ポイ活・節約</div></div>';
                    } else if (buttonText === '自分を磨く') {
                        replyText = '最高ですね！どのあたりから整えたいですか？';
                        nextButtons = '<div class="chat_3btn_container"><div class="chat_btn">見た目・健康</div><div class="chat_btn">知識・スキル</div><div class="chat_btn">考え方・メンタル</div></div>';
                    }
                    addChat(replyText + nextButtons, 'ai');
                }, 500);
            } else if (['家でまったり', '外でアクティブ', '何かを作る', '今の仕事を頑張る', '副業を始める', 'ポイ活・節約', '見た目・健康', '知識・スキル', '考え方・メンタル'].includes(buttonText)) {
                currentMode = 'category_selected';
                handleServerCommunication(buttonText);
            }
        }
    });

    async function saveGoalAndTasks(data) {
        try {
            const response = await fetch('/chat_save', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify(data)
            });
            const result = await response.json();

            if (result.status === 'duplicate_goal') {
                addChat('同じ未完了ゴールがあります。<br>まずはそちらを終わらせましょう。', 'ai');
                currentMode = 'default';
                setTimeout(showMainMenu, 1500);
                return false;
            }

            if (result.status !== 'success') {
                addChat('保存に失敗しました。もう一度お試しください。', 'ai');
                return false;
            }
            return true;
        } catch (error) {
            console.error('保存失敗:', error);
            addChat('通信エラーが発生しました。もう一度お試しください。', 'ai');
            return false;
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
    // Enterキーで送信 (Shift+Enterは改行)
    if (inputEl) {
        inputEl.addEventListener('keydown', (e) => {
            // IME確定時のEnter（変換中のEnter）を無視するための判定
            if (e.isComposing || e.keyCode === 229) {
                return;
            }

            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault(); // デフォルトの改行動作を止める
                sendBtn.click();    // 送信ボタンのクリックイベントを発火
            }
        });
    }
</script>
@endpush