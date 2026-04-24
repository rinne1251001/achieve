@extends('layouts.app')
@section('title', 'タスク')
@section('content')

    <main>
        <h1 style="display: flex; align-items: center; margin-bottom: 0; gap: 8px; margin-left: 10px;">
            <span class="material-symbols-outlined" style="font-size: 1.3em;">check_circle</span>タスク
        </h1>

        <div style="display: grid; padding: 0 clamp(10px, 5vw, 30px); gap: clamp(10px, 5vw, 30px);">

            <div class="task_container">
                <ul class="task_tab">
                    @foreach($goals as $index => $goal)
                        <li style="font-size: 1.5em; font-weight: bold; justify-content: center; align-items: center; display: flex;" class="{{ $index === 0 ? 'active' : '' }}">{{ $index + 1 }}</li>
                    @endforeach
                    @if(count($goals) < 3)
                        <li style="font-size: 2em; font-weight: bold; text-align: center;">+</li>
                    @endif
                </ul>

                @foreach($goals as $index => $goal)
                    <div class="task_ul {{ $index === 0 ? 'active' : '' }}" data-goal-id="{{ $goal->id }}">
                        <a href="{{ route('goals.show', $goal->id) }}"><h3>{{ $goal->goal }}</h3></a>

                        @foreach($goal->tasks as $task)
                            <div class="task_li">
                                <input type="checkbox"
                                        id="task_{{ $task->id }}"
                                        class="task_trigger" 
                                        data-id="{{ $task->id }}"
                                        data-title="{{ $task->task }}" 
                                        data-mode="complete" />

                                <div style="flex-grow: 1; cursor: pointer;"
                                    class="task_detail_trigger" 
                                    data-id="{{ $task->id }}" 
                                    data-title="{{ $task->task }}" 
                                    data-detail="{{ $task->detail }}" 
                                    data-date="{{ $task->target_date }}">
                                    {{ $task->task }}
                                </div>

                                <span class="material-symbols-outlined task_trigger" data-id="{{ $task->id }}" data-title="{{ $task->task }}" data-mode="delete">delete</span>
                            </div>
                        @endforeach
   
                        {{-- 修正点：data属性にゴール情報を追加 --}}
                        <div class="task_li add-trigger" data-goal-id="{{ $goal->id }}" data-goal-title="{{ $goal->goal }}">
                            <div class="circle task_plus">+</div>自分で追加する
                        </div>
                    </div>
                @endforeach
                @if(count($goals) < 3)

                <div class="task_ul" id="new_goalbtn">
                    <h3>ゴール追加</h3>
                    <div class="task_li">
                        <div class="circle task_plus">+</div><div style="font-weight: bold;">ゴールを追加する</div>
                    </div>
                </div>
                @endif
            </div>

            <div class="task_container2">
                @include('parts.calendar_table') 

                <div class="task_achieved">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                        <h3 style="margin: 0;">達成したタスク</h3>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <button id="btnSortAchieved" style="background: none; border: none; cursor: pointer; padding: 0;">
                                <span class="material-symbols-outlined" style="color: var(--bg-color); font-size: 2.5em;">swap_vert</span>
                            </button>
                        
                            <div id="filterBtn" style="position: relative; display: flex; align-items: center; justify-content: center; cursor: pointer; width: 40px; height: 40px;">
                                <span class="material-symbols-outlined" style="color: var(--bg-color); font-size: 2.5em;">filter_alt</span>
                                
                                <ul id="selectFilter">
                                    <li data-value="normal">
                                        <svg><use xlink:href="#check" /></svg>
                                        ノーマル
                                    </li>
                                    <li data-value="all">
                                        <svg><use xlink:href="#check" /></svg>
                                        全て再表示
                                    </li>
                                    <li data-value="ongoing">
                                        <svg><use xlink:href="#check" /></svg>
                                        進行中のゴール
                                    </li>
                                    <li data-value="achieved">
                                        <svg><use xlink:href="#check" /></svg>
                                        完了済みのゴール
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div id="achievedGoalList" style="display: grid; gap: 10px;">
                        @foreach($achievedGoals as $goal)
                            <div class="task_li achieved-goal-row" data-goal-row="{{ $goal->id }}" data-goal-flg="{{ $goal->flg == 1 ? '1' : '0' }}" style="flex-wrap: wrap; transition: all 0.3s ease; display: flex; align-items: center; justify-content: space-between;">
                                <div style="display: flex; align-items: center; flex-grow: 1;">
                                    <span class="material-symbols-outlined task_acc_btn" style="cursor: pointer; font-weight: bold;">keyboard_arrow_down</span>
                                    <a href="{{ route('goals.show', $goal->id) }}" style="font-size: 1.2em; font-weight: bold;">{{ $goal->goal }}</a>
                                </div>
                                <span class="material-symbols-outlined hide-goal-btn" style="cursor: pointer; color: #999; font-size: 1.2em; flex-shrink: 0; margin-left: auto; padding: 5px; "data-id="{{ $goal->id }}">×</span>
                                <div class="task_acc_menu">
                                    <ul>
                                        @foreach($goal->tasks as $task)
                                            <li style="display: flex; gap: 15px; position: relative; min-height: 40px;">
                                                <div style="display: flex; flex-direction: column; align-items: center; flex-shrink: 0; width: 20px;">
                                                    <div class="circle" style="width: 20px; height: 20px; background-color: var(--accent-color); z-index: 2;"></div>
                                                    @if (!$loop->last)
                                                        <div style="position: absolute; top: 14px; bottom: -10px; width: 3px; background-color: var(--accent-color); z-index: 1;"></div>
                                                    @endif
                                                </div>
                                                <div style="padding-bottom: 15px; line-height: 1.2; font-weight: 400;">
                                                    {{ $task->task }}
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </main>

{{-- 各種モーダル（変更なし） --}}
<div id="checkModal" class="modal_shadow">
    <div id="modalStep1" class="modal_wide">
        <p style="line-height: 1.6;">
            <span id="modalTaskTitle" style="font-weight: bolder;"></span>を<br>
            <span id="modalActionText"></span>か？
        </p>
        <div>
            <button id="btnConfirm">実行する</button>
            <button id="btnCancel">戻る</button>
        </div>
    </div>
    <div id="modalStep2" style="display:none;" class="modal_narrow">
        <p id="modalSuccessMessage"></p>
    </div>
</div>

<div id="addModal" class="modal_shadow">
    <div id="addStep1" class="modal_wide">
        <h3 id="addGoalTitle"></h3>
        <div style="display: grid;">
            <label for="title">title</label>
            <input id="title" placeholder="タスクの名前" required maxlength="50">
        </div>
        <div style="display: grid;">
            <label for="deadline">期限</label>
            <input id="deadline" type="date" required>
        </div>
        <div style="display: grid;">
            <label for="detail">説明</label>
            <textarea id="detail" placeholder="タスクの詳細を入力" rows="5" class="chat_input" maxlength="1000"></textarea>
        </div>
        <div>
            <button id="btnSubmit">登録する</button>
            <button id="btnBack">戻る</button>
        </div>
    </div>
    <div id="addStep2" style="display:none;" class="modal_narrow">
        <p>登録しました！</p>
    </div>
</div>

<div id="goalModal" class="modal_shadow">  
    <div id="goalStep1" class="modal_wide">
        <h3 id="goalTitle">ゴール新規登録</h3>
        <div style="display: grid;">
            <label for="title3">title</label>
            <input id="title3" placeholder="目標の名前" required maxlength="35">
        </div>
        <div style="display: grid;">
            <label for="deadline3">期限</label>
            <input id="deadline3" type="date" required>
        </div>
        <div style="display: grid;">
            <label for="detail3">説明</label>
            <textarea id="detail3" placeholder="目標の詳細を入力" rows="5" class="chat_input" maxlength="1000"></textarea>
        </div>
        <div>
            <button id="btnGoalSubmit">登録する</button>
            <button id="btnGoalBack">戻る</button>
        </div>
    </div>
    <div id="goalStep2" style="display:none;" class="modal_narrow">
        <p>登録しました！</p>
    </div>
</div>

<div id="detailModal" class="modal_shadow">
    <div id="detailStep1" class="modal_wide">
        <h3 id="detailTitle">タイトル</h3>
        <p id="detailText" style="white-space: pre-wrap;">説明</p>
        <div>
            <button id="btnEdit">編集</button>
            <button id="btnDetailClose">とじる</button>
        </div>
    </div>
    <div id="detailStep2" style="display:none;" class="modal_wide">
        <h3 id="detailGoalTitle">タスク編集</h3>
        <div style="display: grid;">
            <label for="title2">title</label>
            <input id="title2" placeholder="タスクの名前" required maxlength="50">
        </div>
        <div style="display: grid;">
            <label for="deadline2">期限</label>
            <input id="deadline2" type="date" required>
        </div>
        <div style="display: grid;">
            <label for="detail2">説明</label>
            <textarea id="detail2" placeholder="タスクの詳細を入力" rows="5" class="chat_input" maxlength="1000"></textarea>
        </div>
        <div>
            <button id="btnDetailSubmit">登録する</button>
            <button id="btnDetailback">戻る</button>
        </div>
    </div>
    <div id="detailStep3" style="display:none;" class="modal_narrow">
        <p>登録しました！</p>
    </div>
</div>
@endsection

@push('scripts')
<svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
    <symbol id="check" viewBox="0 0 27 24">
        <path d="m25.05.26.17.09c.47.38.74.8.84 1.4.05.98-.56 1.63-1.12 2.39l-.09.12a408.78 408.78 0 0 1-1.83 2.5 102.6 102.6 0 0 1-1.35 1.84 110.22 110.22 0 0 1-1.44 1.97 111.97 111.97 0 0 1-1.44 1.96l-1.18 1.62a144.68 144.68 0 0 0-1.81 2.47 88.68 88.68 0 0 0-1.44 1.96l-.63.86-1 1.36-.29.4-.1.13-.19.26c-.88 1.2-.88 1.2-1.63 1.4-.55.04-1.05.05-1.52-.3-.1-.09-.22-.19-.32-.3l-.32-.31c-.67-.66-.67-.66-.9-.93-.27-.31-.57-.6-.86-.89-.66-.64-.66-.64-.86-.87-.27-.32-.58-.61-.88-.9-.68-.68-.68-.68-.96-1.01-.25-.28-.53-.54-.8-.8l-.87-.9a8.75 8.75 0 0 0-.52-.55l-.09-.09-.37-.36C.11 13.66.11 13.66.07 12.76c.01-.58.23-1.02.62-1.44a2 2 0 0 1 2.7.03l.55.4.12.09a94.5 94.5 0 0 0 1.88 1.34 81.56 81.56 0 0 0 1.74 1.23l1.39.97.3.22c.24.16.4.17.66.12.33-.14.52-.44.73-.71l.37-.44a26.96 26.96 0 0 0 1-1.2c.2-.24.4-.48.58-.73.24-.3.49-.6.75-.88l.73-.88.76-.92c.2-.23.4-.47.59-.72.23-.3.48-.59.73-.87.2-.22.38-.44.55-.67.24-.3.48-.6.74-.89.25-.28.49-.57.73-.87l.76-.92c.2-.23.4-.47.59-.72.23-.3.48-.59.73-.88.2-.21.38-.44.55-.67.24-.3.48-.59.74-.88l.5-.6c.75-.91 1.66-1.68 2.9-1.01z" fill="currentColor" fill-rule="evenodd"/>
    </symbol>
</svg>


<script>
document.addEventListener('DOMContentLoaded', () => {
    // --- 追加するヘルパー関数 ---
    const STORAGE_KEY = 'hidden_goal_ids'; // 定数を定義
    const getHiddenIds = () => {
        const data = localStorage.getItem(STORAGE_KEY);
        return data ? JSON.parse(data) : [];
    };

    const getEl = id => document.getElementById(id);
    const allModals = ['checkModal', 'addModal', 'detailModal', 'goalModal'];
    allModals.forEach(id => {
        const el = getEl(id);
        if (el) el.style.display = 'none';
    });
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    const modals = {
        check: {
            base: getEl('checkModal'),
            steps: [getEl('modalStep1'), getEl('modalStep2')],
            title: getEl('modalTaskTitle'),
            action: getEl('modalActionText'),
            confirm: getEl('btnConfirm'),
            msg: getEl('modalSuccessMessage')
        },
        add: {
            base: getEl('addModal'),
            steps: [getEl('addStep1'), getEl('addStep2')],
            goal: getEl('addGoalTitle'),
            titleIn: getEl('title'),
            descIn: getEl('detail'),
            submit: getEl('btnSubmit'),
            back: getEl('btnBack')
        },
        detail: {
            base: getEl('detailModal'),
            steps: [getEl('detailStep1'), getEl('detailStep2'), getEl('detailStep3')], 
            title: getEl('detailTitle'),
            text: getEl('detailText'),
            close: getEl('btnDetailClose'),
            editBtn: getEl('btnEdit'),
            submit: getEl('btnDetailSubmit'),
            back: getEl('btnDetailback'),
            titleIn: getEl('title2'),
            dateIn: getEl('deadline2'),
            descIn: getEl('detail2')
        },
        goal: {
            base: getEl('goalModal'),
            steps: [getEl('goalStep1'), getEl('goalStep2')],
            titleIn: getEl('title3'),
            dateIn: getEl('deadline3'),
            descIn: getEl('detail3'),
            submit: getEl('btnGoalSubmit'),
            back: getEl('btnGoalBack')
        }
    };

    let currentTarget = {
        element: null, id: null, mode: null, goalId: null, title: null, date: null, detail: null
    };

    const configs = {
        complete: { action: '完了にします', btn: '完了する', msg: '登録しました！', method: 'PATCH' },
        delete: { action: '削除します', btn: '削除する', msg: '削除しました！', method: 'DELETE' },
        hide: { action: '一覧から非表示にします', btn: '非表示にする', msg: '非表示にしました', method: 'LOCAL' },
        reset_visibility: { action: 'すべて再表示します', btn: '再表示する', msg: '再表示しました', method: 'LOCAL' }
    };

    const setModal = (m, show, step = 0) => {
        if (!m.base) return;
        m.base.style.display = show ? 'flex' : 'none';
        m.steps.forEach((s, i) => {
            Loader.hide();
            if (s) s.style.display = (show && i === step) ? 'grid' : 'none';
        });
    };

    // タブ切り替え
    const tabs = document.querySelectorAll('.task_tab li');
    const contents = document.querySelectorAll('.task_ul');
    tabs.forEach((tab, i) => {
        tab.addEventListener('click', () => {
            document.querySelector('.task_tab .active')?.classList.remove('active');
            document.querySelector('.task_ul.active')?.classList.remove('active');
            tab.classList.add('active');
            contents[i].classList.add('active');
        });
    });

    // アコーディオン
    document.querySelectorAll('.task_acc_btn').forEach(btn => {
        btn.addEventListener('click', () => {
            btn.closest('.task_li').classList.toggle('open');
        });
    });

    // 完了・削除
    document.querySelectorAll('.task_trigger').forEach(trigger => {
        trigger.addEventListener('click', (e) => {
            const target = e.currentTarget;
            if (target.tagName === 'INPUT') e.preventDefault(); 

            currentTarget.element = target;
            currentTarget.id = target.dataset.id;
            currentTarget.mode = target.dataset.mode;

            const c = configs[currentTarget.mode];
            if (!c) return;

            modals.check.title.innerText = `「${target.dataset.title}」`;
            modals.check.action.innerText = c.action;
            modals.check.confirm.innerText = c.btn;
            modals.check.msg.innerText = c.msg;
            setModal(modals.check, true, 0);
        });
    });

    modals.check.confirm.onclick = () => {
        const mode = currentTarget.mode;
        const c = configs[mode];
        Loader.show();
        // ローカル操作（非表示・リセット）の場合
        if (c.method === 'LOCAL') {
            if (mode === 'hide') {
                let hiddenIds = getHiddenIds();
                const idStr = String(currentTarget.id);
                if (!hiddenIds.includes(idStr)) {
                    hiddenIds.push(idStr);
                    localStorage.setItem(STORAGE_KEY, JSON.stringify(hiddenIds));
                }
            } else if (mode === 'reset_visibility') {
                localStorage.removeItem(STORAGE_KEY);
            }
            
            setModal(modals.check, true, 1); // 完了メッセージ表示
            setTimeout(() => {
                setModal(modals.check, false);
                applyAllFilters(); // リロードせずに即時反映
            }, 800);
            return;
        }

        // サーバー通信が必要な操作（完了・削除）の場合
        const url = currentTarget.mode === 'complete'
            ? `/tasks/${currentTarget.id}/check`
            : `/tasks/${currentTarget.id}`;
        
        fetch(url, {
            method: c.method,
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
            body: JSON.stringify({ completed: true }),
            signal: getTimeoutSignal()//タイムアウト処理
        })
       .then(async res => {
            if (!res.ok) {
                let message = `エラーが発生しました (${res.status})`;
                try {
                    const data = await res.json();
                    message = data.error || data.message || message;
                } catch {}
                throw new Error(message);
            }
            return res.json();
        })
        .then(() => {
            setModal(modals.check, true, 1);
            setTimeout(() => location.reload(), 800);
        })
        .catch(err => {
            Loader.hide();
            alert(parseError(err));
        });
    };

    getEl('btnCancel').onclick = () => setModal(modals.check, false);

    // 新規追加
    document.querySelectorAll('.add-trigger').forEach(btn => {
        btn.onclick = (e) => {
            const target = e.currentTarget;
            currentTarget.goalId = target.dataset.goalId;
            modals.add.goal.innerText = target.dataset.goalTitle || '新規タスク';
            setModal(modals.add, true, 0);
        };
    });

    modals.add.submit.onclick = () => {
        const titleVal = modals.add.titleIn.value;
        const detailVal = modals.add.descIn.value;
        const deadlineVal = getEl('deadline').value;
        const today = new Date();
        today.setHours(0,0,0,0);
        const selectedDate = new Date(deadlineVal);

        // --- 文字数制限追加 ---
        if (titleVal.length > 50) {
            return alert('タスク名は50文字以内で入力してください');
        }
        if (detailVal.length > 1000) {
            return alert('詳細は1000文字以内で入力してください');
        }

        if (!titleVal) return alert('タイトルを入力してください');
        if(deadlineVal && selectedDate < today) return alert('日付は今日以降を選択してください。');

        Loader.show();
        fetch('/tasks', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
            body: JSON.stringify({
                goal_id: currentTarget.goalId,
                title: titleVal,
                detail: detailVal,
                target_date: deadlineVal
            }),
            signal: getTimeoutSignal()//タイムアウト処理
        })
        .then(async res => {
            if (!res.ok) {
                let message = `エラーが発生しました (${res.status})`;
                try {
                    const data = await res.json();
                    message = data.error || data.message || message;
                } catch {}
                throw new Error(message);
            }
            return res.json();
        })
        .then(() => {
            setModal(modals.add, true, 1);
            setTimeout(() => location.reload(), 800);
        })
        .catch(err => {
            Loader.hide();
            alert(parseError(err));
        });
    };

    modals.add.back.onclick = () => setModal(modals.add, false);

    // 詳細・編集
    document.querySelectorAll('.task_detail_trigger').forEach(trigger => {
        trigger.addEventListener('click', (e) => {
            const d = e.currentTarget.dataset;
            currentTarget.id = d.id;
            currentTarget.title = d.title;
            currentTarget.date = d.date;
            currentTarget.detail = d.detail || '';

            let detail = d.detail || '';
            if (detail) {
                detail = detail/*.replace(/"/g, '') ""が表示されない為無効化*/
                            .replace(/\\n/g, '\n')
                            .replace(/<br\s*\/?>/gi, '\n');
            }
            currentTarget.detail = detail;

            modals.detail.title.innerText = currentTarget.title;
            modals.detail.text.textContent = currentTarget.detail || '詳細はありません';
            setModal(modals.detail, true, 0);
        });
    });

    modals.detail.editBtn.onclick = () => {
        modals.detail.titleIn.value = currentTarget.title;
        modals.detail.descIn.value = currentTarget.detail;
        modals.detail.dateIn.value = currentTarget.date;
        setModal(modals.detail, true, 1);
    };

    modals.detail.submit.onclick = () => {
        const titleVal = modals.detail.titleIn.value.trim();
        const dateVal = modals.detail.dateIn.value;
        const descVal = modals.detail.descIn.value;
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        const selectedDate = new Date(dateVal);

        // --- 文字数制限追加 ---
        if (titleVal.length > 50) {
            return alert('タスク名は50文字以内で入力してください');
        }
        if (descVal.length > 1000) {
            return alert('詳細は1000文字以内で入力してください');
        }

        if (!titleVal) return alert('タイトルを入力してください');
        if (dateVal && selectedDate < today) return alert('日付は今日以降を選択してください。');

        Loader.show();
        fetch(`/tasks/${currentTarget.id}`, {
            method: 'PATCH',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
            body: JSON.stringify({
                title: titleVal,
                detail: descVal,
                target_date: dateVal
            }),
            signal: getTimeoutSignal()//タイムアウト処理
        })
        .then(async res => {
            if (!res.ok) {
                let message = `エラーが発生しました (${res.status})`;
                try {
                    const data = await res.json();
                    message = data.error || data.message || message;
                } catch {}
                throw new Error(message);
            }
            return res.json();
        })
        .then(() => {
            setModal(modals.detail, true, 2);
            setTimeout(() => location.reload(), 800);
        })
        .catch(err => {
            Loader.hide();
            alert(parseError(err));
        });
    };

    modals.detail.back.onclick = () => setModal(modals.detail, true, 0);
    modals.detail.close.onclick = () => setModal(modals.detail, false);

    //ゴールの追加
    const new_goalbtn = getEl('new_goalbtn');
    if (new_goalbtn) {
        new_goalbtn.onclick = () => {
            // 入力値をリセット
            modals.goal.titleIn.value = '';
            modals.goal.dateIn.value = '';
            modals.goal.descIn.value = '';
            setModal(modals.goal, true, 0);
        };
    }

    // ゴールモーダルの「戻る」ボタン
    if (modals.goal.back) {
        modals.goal.back.onclick = () => setModal(modals.goal, false);
    }

    // ゴールモーダルの「登録する」ボタン
    if (modals.goal.submit) {
        modals.goal.submit.onclick = () => {
            const titleVal = modals.goal.titleIn.value.trim();
            const dateVal = modals.goal.dateIn.value;
            const descVal = modals.goal.descIn.value;

            // --- バリデーション ---
            if (titleVal.length > 35) {
                return alert('ゴール名は35文字以内で入力してください');
            }
            if (!titleVal) return alert('目標のタイトルを入力してください');
            if (descVal.length > 1000) {
                return alert('詳細は1000文字以内で入力してください');
            }

            const today = new Date();
            today.setHours(0, 0, 0, 0);
            if(!dateVal){
                if(!confirm("日付が未設定ですが、このまま登録してよろしいですか？")) return;
            } else if(new Date(dateVal) < today) return alert('今日以降の日付を選択してください');

            Loader.show(); // ゴール作成時もローダーを表示
            fetch('/goals', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                body: JSON.stringify({
                    goal: titleVal,
                    target_date: dateVal === "" ? null : dateVal,
                    detail: descVal === "" ? null : descVal
                }),
                signal: getTimeoutSignal()//タイムアウト処理
            })
            .then(res => {
                if (!res.ok) return res.json().then(data => { throw new Error(data.error || data.message || '保存に失敗しました'); }).catch(() => { throw new Error('サーバーエラーが発生しました'); });
                return res.json();
            })
            .then(data => {
                setModal(modals.goal, true, 1);
                setTimeout(() => location.reload(), 800);
            })
            .catch(err => {
                Loader.hide();
                console.error(err);
                alert(parseError(err));
            });
        };
    }

    // 個別非表示（×ボタン）
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.hide-goal-btn');
        if (!btn) return;

        currentTarget.id = btn.dataset.id;
        currentTarget.mode = 'hide';
        currentTarget.title = btn.closest('.achieved-goal-row')
                                .querySelector('a').innerText;

        modals.check.title.innerText = `「${currentTarget.title}」`;
        modals.check.action.innerText = configs.hide.action;
        modals.check.confirm.innerText = configs.hide.btn;
        modals.check.msg.innerText = configs.hide.msg;

        setModal(modals.check, true, 0);
    });

    const filterBtn = document.getElementById('filterBtn');
    const filterMenu = document.getElementById('selectFilter');

    if (filterBtn && filterMenu) {
        filterBtn.addEventListener('click', (e) => {
            e.stopPropagation();

            if (filterMenu.style.display === 'block') {
                filterMenu.style.display = 'none';
            } else {
                filterMenu.style.display = 'block';
            }
        });

        // 外クリックで閉じる
        document.addEventListener('click', () => {
            filterMenu.style.display = 'none';
        });
    }

    // --- フィルター適用関数 ---
    const applyFilterByMode = (mode) => {
        const rows = document.querySelectorAll('.achieved-goal-row');
        const hiddenIds = getHiddenIds();

        rows.forEach(row => {
            const goalFlg = String(row.dataset.goalFlg);
            const goalId = String(row.dataset.goalRow);

            let shouldShow = true;

            // ×で非表示にしたものは常に非表示
            if (hiddenIds.includes(goalId)) {
                shouldShow = false;
            }

            // モード別フィルター
            if (mode === 'ongoing') {
                shouldShow = shouldShow && (goalFlg === '0');

            } else if (mode === 'achieved') {
                shouldShow = shouldShow && (goalFlg === '1');

            } else if (mode === 'normal') {
                // hidden以外すべて表示（何もしない）

            } else if (mode === 'all') {
                localStorage.removeItem(STORAGE_KEY);
                shouldShow = true;
            }

            row.style.display = shouldShow ? 'flex' : 'none';
        });
    };

    const filterList = document.getElementById('selectFilter');
    let currentMode = 'normal';

    // フィルターのリストクリック時の処理を修正
    if (filterList) {
        filterList.querySelector('[data-value="normal"]').classList.add('active');

        filterList.addEventListener('click', (e) => {
            const targetLi = e.target.closest('li');
            if (targetLi) {
                // 他のactiveを削除
                filterList.querySelectorAll('li').forEach(li => li.classList.remove('active'));
                // クリックされたものにactiveを付与
                targetLi.classList.add('active');

                currentMode = targetLi.dataset.value;
                applyFilterByMode(currentMode);
            }
        });
    }

    const applyAllFilters = () => {
        applyFilterByMode(currentMode);
    };

    //並び替え
    let isNewestFirst = false;
    const sortBtn = getEl('btnSortAchieved'), list = getEl('achievedGoalList');
    if (sortBtn && list) {
        sortBtn.onclick = () => {
            isNewestFirst = !isNewestFirst;
            [...list.querySelectorAll('.achieved-goal-row')]
                .sort((a, b) => (isNewestFirst ? b.dataset.goalRow - a.dataset.goalRow : a.dataset.goalRow - b.dataset.goalRow))
                .forEach(row => list.appendChild(row));
        };
    }

    applyAllFilters();
});
</script>
@endpush