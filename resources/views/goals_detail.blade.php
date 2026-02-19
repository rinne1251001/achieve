@extends('layouts.app')
@section('title', $goal->goal)
@section('content')


<div class="wrapper3">
    <aside class="sidebar"></aside>
    <main style="padding: 0 clamp(5px, 2.5vw, 15px);">

        <div style="padding: 20px 20px 0; position: relative;">
            <h2 style="margin: 0; font-size: 3.5em; font-weight: 900; font-family: 'M PLUS Rounded 1c', sans-serif;">{{ $goal->goal }}</h2>
            <p style="padding: 0 10px;">
                期限：{{ $goal->target_date ?? '未設定' }}<br>
                {{ $goal->detail ?? '' }} 
            </p>
            <div style="position: absolute; top: 10px; right: 0; z-index: -1;">
                <div style="position: relative; width: 200px; height: 210px;">
                    <svg width="200" height="200" style="color: var(--sub-color); position: absolute; top: 0; right: 0; transform:rotate(15deg); animation: ACW 100s linear infinite;"><use xlink:href="#flower" /></svg>
                    <svg width="120" height="120" style="color: var(--accent-color); position: absolute; bottom: 0; right: 140px; transform:rotate(25deg); animation: CW 50s linear infinite;"><use xlink:href="#flower" /></svg>
                </div>
            </div>
        </div>

        <svg viewBox="0 0 100 3" preserveAspectRatio="none" style="width: 100%; height: auto; display: block; color: var(--sub-color); margin: 50px 0;">
            <use xlink:href="#wave"></use>
        </svg>


        <div style="display: flex; flex-direction: column; gap: 0; padding: 0 40px;">
            @php
                $sortedTasks = $goal->tasks->sortByDesc('flg');
            @endphp

            @forelse($sortedTasks as $index => $task)
                @php
                    $isCompleted = $task->flg;
                    $baseDelay = $index * 0.22;
                    $circleDelay = $baseDelay . 's';
                    $svgDelay = ($baseDelay + 0.1) . 's';
                    $lineDelay = ($baseDelay + 0.1) . 's';

                    $svgInitialColor = $isCompleted ? 'var(--accent-color)' : 'var(--font-light-color)';
                @endphp

            
                <div style="display: flex; align-items: stretch;">
                    <div style="display: flex; flex-direction: column; align-items: center; width: 70px;">
                        
                        {{-- 円形部分 --}}
                        <div @class([ 'goals_frame_ani' => $isCompleted, 'circle', 'check_trigger' ]) 
                            style="background-color: var(--font-light-color); width: 70px; height: 70px; position: relative; flex-shrink: 0; animation-delay: {{ $circleDelay }}; cursor: pointer;"
                            data-id="{{ $task->id }}"
                            data-title="{{ $task->task }}"
                            data-flg="{{ $task->flg }}">
                            
                            <div style="background-color: var(--bg-color); width: 50px; height: 50px; border-radius: 50%; position: absolute;"></div>
                            
                            <svg width="30" height="30" @class([ 'goals_check_ani' => $isCompleted ]) style="position: relative; z-index: 1; color: {{ $svgInitialColor }}; animation-delay: {{ $svgDelay }};">
                                <use xlink:href="#check"></use>
                            </svg>
                        </div>

                        {{-- 縦棒部分 --}}
                        @if(!$loop->last)
                            @php
                                $nextTask = $sortedTasks->values()->get($loop->index + 1);
                                $isLineActive = ($nextTask && $nextTask->flg);
                            @endphp
                            <div @class([ 'goals_frame_ani' => $isLineActive ]) 
                                style="background-color: var(--font-light-color); width: 4px; flex-grow: 1; min-height: 30px; animation-delay: {{ $lineDelay }};">
                            </div>
                        @endif
                    </div>

                    <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-grow: 1; padding: 20px 0 0 20px;">

                        {{-- タスク名 --}}
                        <div class="task_detail_trigger" 
                            style="font-weight: bold; font-size: 1.1em; cursor: pointer;"
                            data-id="{{ $task->id }}"
                            data-title="{{ $task->task }}"
                            data-detail="{{ $task->detail }}"
                            data-date="{{ $task->target_date }}">
                            {{ $task->task }}
                        </div>
                        {{-- 削除ボタン --}}
                        @if($task->flg != 1)
                            <div class="task_trigger" 
                                style="cursor: pointer; color: #bbb;"
                                data-id="{{ $task->id }}"
                                data-title="{{ $task->task }}"
                                data-mode="delete">
                                <span class="material-symbols-outlined">delete</span>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div style="text-align: center;">タスクはまだ登録されていません。</div>
            @endforelse
        </div>

        <svg viewBox="0 0 100 3" preserveAspectRatio="none" style="width: 100%; height: auto; display: block; color: var(--sub-color); margin: 50px 0;">
            <use xlink:href="#wave"></use>
        </svg>

        <div style="display: flex; gap: 20px; margin-bottom: 50px; justify-content: center;">
            <div><a href="{{ route('task') }}">タスク一覧に戻る</a></div>
        </div>

        <nav class="goal_menu_container">
            <input type="checkbox" id="goal-menu-open" class="goal_menu_open_input" style="display:none;"/>
            
            <label for="goal-menu-open" class="goal_plus goal_btn">
                <span></span>
                <span></span>
                <span></span>
            </label>
            
            <div id="btnGoalEdit" class="goal_btn goal_menu_item">
                <svg width="30" height="30">
                    <use xlink:href="#pen"></use>
                </svg>
            </div>
            <div id="btnGoalCheck" class="goal_btn goal_menu_item" data-id="{{ $goal->id }}" data-title="{{ $goal->goal }}" data-flg="{{ $goal->flg }}">
                <svg width="30" height="30">
                    <use xlink:href="#check"></use>
                </svg>
            </div>
            <div id="btnTaskAdd" class="goal_btn goal_menu_item">
                <div width="30" height="30">
                    <span></span>
                    <span></span>
                </div>
            </div>
        </nav>
    </main>
    <aside class="sidebar"></aside>
</div>


{{-- モーダル --}}
<div id="checkModal">
    <div id="modalStep1">
        <p style="line-height: 1.6;">
            <span id="modalTaskTitle" style="font-weight: bolder;"></span>を<br>
            <span id="modalActionText"></span>
        </p>
        <div>
            <button id="btnConfirm">実行する</button>
            <button id="btnCancel">戻る</button>
        </div>
    </div>
    <div id="modalStep2" style="display:none;">
        <p id="modalSuccessMessage"></p>
    </div>
</div>

<div id="detailModal">
    <div id="detailStep1">
        <h3 id="detailTitle">タイトル</h3>
        <p id="detailText" style="white-space: pre-wrap;">説明</p>
        <div>
            <button id="btnEdit">編集</button>
            <button id="btnDetailClose">とじる</button>
        </div>
    </div>
    <div id="detailStep2" style="display:none;">
        <h3 id="detailGoalTitle">タスク編集</h3>
        <div style="display: grid;">
            <label for="title2">title</label>
            <input id="title2" placeholder="タスクの名前" required>
        </div>
        <div style="display: grid;">
            <label for="deadline2">期限</label>
            <input id="deadline2" type="date" required>
        </div>
        <div style="display: grid;">
            <label for="detail2">説明</label>
            <textarea id="detail2" placeholder="タスクの詳細を入力" rows="5" class="chat_input"></textarea>
        </div>
        <div>
            <button id="btnDetailSubmit">登録する</button>
            <button id="btnDetailback">戻る</button>
        </div>
    </div>
    <div id="detailStep3" style="display:none;">
        <p>登録しました！</p>
    </div>
</div>

<div id="editModal">
    <div id="editStep1">
        <h3 id="editGoalTitle">ゴール編集</h3>
        <div style="display: grid;">
            <label for="edit_title">title</label>
            <input id="edit_title" placeholder="ゴールの名前" required>
        </div>
        <div style="display: grid;">
            <label for="deadline">期限</label>
            <input id="deadline" type="date" required>
        </div>
        <div style="display: grid;">
            <label for="detail">説明</label>
            <textarea id="detail" placeholder="ゴールの詳細を入力" rows="5" class="chat_input"></textarea>
        </div>
        <div>
            <button id="btnEditSubmit">登録する</button>
            <button id="btnEditBack">戻る</button>
        </div>
    </div>
    <div id="editStep2" style="display:none;">
        <p>登録しました！</p>
    </div>
</div>

<div id="addModal">
    <div id="addStep1">
        <h3 id="addGoalTitle">タスクの追加</h3>
        <div style="display: grid;">
            <label for="add_title">title</label>
            <input id="add_title" placeholder="タスクの名前" required>
        </div>
        <div style="display: grid;">
            <label for="add_deadline">期限</label>
            <input id="add_deadline" type="date" required>
        </div>
        <div style="display: grid;">
            <label for="add_detail">説明</label>
            <textarea id="add_detail" placeholder="タスクの詳細を入力" rows="5" class="chat_input"></textarea>
        </div>
        <div>
            <button id="btnSubmit">登録する</button>
            <button id="btnBack">戻る</button>
        </div>
    </div>
    <div id="addStep2" style="display:none;">
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
<svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
    <symbol id="check" viewBox="-30 140 490 200">
        <path d="M438 38l3.1 1.7c9 7 14 15 16 26.3 1 18.5-10.6 30.8-21 45l-1.7 2.4a7813 7813 0 01-14 19c-7 9.2-13.7 18.5-20.4 27.8L386 179a1663 1663 0 00-11.7 16 2065 2065 0 01-15.3 21 1663 1663 0 00-11.7 16 2065 2065 0 01-15.3 21A1666 1666 0 00320.5 268.9c-7.3 10.2-14.8 20.3-22.3 30.4L278 327.2 264 346a1663 1663 0 00-11.5 16 2064.7 2064.7 0 01-15.3 21 1666 1666 0 00-11.7 16l-18.7 25.6-5.6 7.5-1.8 2.7a4946.4 4946.4 0 00-3.5 4.7C179.2 462 179.2 462 165 465.8c-10.4.8-19.7.9-28.5-5.4a114.6 114.6 0 01-6-5.8l-6-6c-12.7-12.4-12.7-12.4-17-17.4-5-5.9-10.7-11.2-16.2-16.7-12.3-12-12.3-12-16-16.5-5-6-10.9-11.4-16.5-17-12.8-12.6-12.8-12.6-18-18.9-4.8-5.1-10-10-15-15-12.8-12.6-12.8-12.6-16.5-17-3-3.6-6.3-6.9-9.7-10.2l-1.6-1.7-7-6.7c-21.5-21.1-21.5-21.1-22.3-38a39 39 0 0111.7-27.1 37.6 37.6 0 0150.6.5l10.3 7.6 2.4 1.6 17.6 12.5 17.7 12.6a1528 1528 0 0017.3 12.5 923.5 923.5 0 0041.3 29l5.8 4.3c4.3 3 7.2 3.1 12.2 2.2 6.1-2.7 9.7-8.3 13.7-13.4 2.2-2.8 4.6-5.5 6.9-8.2a508.8 508.8 0 0011.3-13.8c2.3-3 4.8-6 7.3-8.8a366 366 0 0011-13.7 366 366 0 0114-16.6A546 546 0 00234 238a925 925 0 0114.4-17.3 370.2 370.2 0 0011-13.6c4.4-5.6 9-11 13.8-16.4a268.2 268.2 0 0010.3-12.6 352 352 0 0113.8-16.6c4.7-5.4 9.2-10.9 13.7-16.4a925 925 0 0114.4-17.3 370.2 370.2 0 0011-13.6c4.4-5.6 9-11 13.8-16.4A268.2 268.2 0 00360.5 85c4.4-5.7 9-11.2 13.8-16.6a374.3 374.3 0 009.5-11.2c14-17.3 31-31.7 54.2-19.2z" fill="currentColor"/>
    </symbol>
</svg>
<svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
    <symbol id="wave" viewBox="0 0 100 3">
        <path d="M0 2.2
            C 1.4 2.2, 1.4 0.8, 2.8 0.8 S 4.2 2.2, 5.6 2.2
            S 7.0 0.8, 8.4 0.8 S 9.8 2.2, 11.2 2.2
            S 12.6 0.8, 14.0 0.8 S 15.4 2.2, 16.8 2.2
            S 18.2 0.8, 19.6 0.8 S 21.0 2.2, 22.4 2.2
            S 23.8 0.8, 25.2 0.8 S 26.6 2.2, 28.0 2.2
            S 29.4 0.8, 30.8 0.8 S 32.2 2.2, 33.6 2.2
            S 35.0 0.8, 36.4 0.8 S 37.8 2.2, 39.2 2.2
            S 40.6 0.8, 42.0 0.8 S 43.4 2.2, 44.8 2.2
            S 46.2 0.8, 47.6 0.8 S 49.0 2.2, 50.4 2.2
            S 51.8 0.8, 53.2 0.8 S 54.6 2.2, 56.0 2.2
            S 57.4 0.8, 58.8 0.8 S 60.2 2.2, 61.6 2.2
            S 63.0 0.8, 64.4 0.8 S 65.8 2.2, 67.2 2.2
            S 68.6 0.8, 70.0 0.8 S 71.4 2.2, 72.8 2.2
            S 74.2 0.8, 75.6 0.8 S 77.0 2.2, 78.4 2.2
            S 79.8 0.8, 81.2 0.8 S 82.6 2.2, 84.0 2.2
            S 85.4 0.8, 86.8 0.8 S 88.2 2.2, 89.6 2.2
            S 91.0 0.8, 92.4 0.8 S 93.8 2.2, 95.2 2.2
            S 96.6 0.8, 98.0 0.8 S 99.4 2.2, 100 2.2" 
        stroke-width="0.3" fill="none" stroke="currentColor" stroke-linecap="round"/>
    </symbol>
</svg>
<svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
    <symbol id="pen" viewBox="3 4 123 124">
        <path d="m16.6 88.6 24.7 24.7-37.5 12.9zm68.9-71.8 27.4 27.5L46 111 18.8 83.5zm17-12.7c2.4.2 5 1.2 7 3.3L123 20.7c3 3 4 7.3 2.6 10.9l-1.3 2v.1l-7.7 7.8L95 20l-6-6.3L96.6 6l2-1.3c1.2-.5 2.4-.7 3.7-.6z" fill="currentColor" fill-rule="evenodd"/>
    </symbol>
</svg>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const getEl = id => document.getElementById(id);
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // --- 0. モーダル要素の定義 ---
    const modals = {
        check: {
            base: getEl('checkModal'),
            steps: [getEl('modalStep1'), getEl('modalStep2')],
            taskTitle: getEl('modalTaskTitle'),
            actionText: getEl('modalActionText'),
            confirmBtn: getEl('btnConfirm')
        },
        detail: {
            base: getEl('detailModal'),
            steps: [getEl('detailStep1'), getEl('detailStep2'), getEl('detailStep3')],
            title: getEl('detailTitle'),
            text: getEl('detailText'),
            titleIn: getEl('title2'),
            dateIn: getEl('deadline2'),
            descIn: getEl('detail2')
        },
        editGoal: {
            base: getEl('editModal'),
            steps: [getEl('editStep1'), getEl('editStep2')],
            titleIn: getEl('editStep1')?.querySelector('#edit_title'),
            dateIn: getEl('editStep1')?.querySelector('#deadline'),
            descIn: getEl('editStep1')?.querySelector('#detail')
        },
        add: {
        base: getEl('addModal'),
        steps: [getEl('addStep1'), getEl('addStep2')],
        titleIn: getEl('add_title'),
        dateIn: getEl('add_deadline'),
        descIn: getEl('add_detail'),
        submit: getEl('btnSubmit'),
        back: getEl('btnBack')
        }
    };

    const configs = {
        complete: { action: '完了にします', btn: '完了する', msg: '登録しました！', method: 'PATCH', suffix: '/check' },
        delete: { action: '削除します', btn: '削除する', msg: '削除しました！', method: 'DELETE', suffix: '' }
    };

    const checkModalEls = {
        title: getEl('modalTaskTitle'),
        action: getEl('modalActionText'),
        confirm: getEl('btnConfirm'),
        successMsg: getEl('modalSuccessMessage')
    };

    // 現在操作中のデータを保持
    let currentMode = 'complete';
    let currentTaskId = null;

    // モーダル切り替え共通関数
    const setModal = (m, show, step = 0) => {
        if (!m || !m.base) return;
        m.base.style.display = show ? 'flex' : 'none';
        m.steps.forEach((s, i) => {
            if (s) s.style.display = (show && i === step) ? 'grid' : 'none';
        });
    };

    // --- 1. 完了（チェック） 削除 (デリート)処理 ---
    document.querySelectorAll('.check_trigger, .task_trigger').forEach(trigger => {
        trigger.addEventListener('click', (e) => {
            const d = e.currentTarget.dataset;
        
            // 既に完了しているタスクの「円」をクリックした場合は無視
            if (d.flg == "1" && !d.mode) return;

            currentTaskId = d.id;
            currentMode = d.mode || 'complete'; 
            const c = configs[currentMode];

            // テキストをセット
            modals.check.taskTitle.innerText = `「${d.title}」`;
            modals.check.actionText.innerText = c.action;
        
            // ボタンの文言を調整
            modals.check.confirmBtn.innerText = c.btn;

            setModal(modals.check, true, 0);
        });
    });
    // 完了実行  5のゴール完了処理もここで行う
    getEl('btnConfirm').onclick = () => {
        if (!currentTaskId) return;
        const c = configs[currentMode];

        if (window.Loader) Loader.show();

        // ゴール完了モードの場合
        if (currentMode === 'goalComplete') {
            fetch(`/goals/${currentTaskId}/check`, { // ※ルートに合わせて調整
                method: 'PATCH',
                headers: { 
                    'Content-Type': 'application/json', 
                    'X-CSRF-TOKEN': csrfToken 
                },
                body: JSON.stringify({ flg: 1 })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    window.Loader.hide();
                    getEl('modalSuccessMessage').innerText = '目標達成おめでとうございます！';
                    setModal(modals.check, true, 1);
                    setTimeout(() => location.reload(), 1000);
                }
            })
            .catch(err => {
                window.Loader.hide();
                alert('通信に失敗しました');});
            return; // ゴール完了の処理を終えたらここで抜ける
        }

        //タスク完了・削除処理
        fetch(`/tasks/${currentTaskId}${c.suffix}`, {
            method: c.method,
            headers: { 
                'Content-Type': 'application/json', 
                'X-CSRF-TOKEN': csrfToken 
            },
            body: JSON.stringify({
                completed: true 
            })
        })
        .then(res => {
            if (!res.ok) throw new Error('通信エラー');
            return res.json();
        })
        .then(data => {
            if (data.success) {
                Loader.hide();
                getEl('modalSuccessMessage').innerText = c.msg;
                setModal(modals.check, true, 1); // 成功ステップへ
                setTimeout(() => location.reload(), 800);
            } else {
                alert('更新に失敗しました');
            }
        })
        .catch(err => {
            console.error(err);
            Loader.hide();
            alert('サーバーとの通信に失敗しました');
        });
    };

    getEl('btnCancel').onclick = () => setModal(modals.check, false);

    // --- 2. タスク詳細・編集処理 ---
    document.querySelectorAll('.task_detail_trigger').forEach(trigger => {
        trigger.addEventListener('click', (e) => {
            const d = e.currentTarget.dataset;
            currentTaskId = d.id;

            let detail = d.detail || '';
            if (detail) {
                detail = detail.replace(/"/g, '').replace(/\\n/g, '\n').replace(/<br\s*\/?>/gi, '\n');
            }

            modals.detail.title.innerText = d.title;
            modals.detail.text.innerText = detail || '詳細説明はありません。';
            
            // 編集用フォームに値をセット
            modals.detail.titleIn.value = d.title;
            modals.detail.dateIn.value = d.date;
            modals.detail.descIn.value = detail;

            setModal(modals.detail, true, 0);
        });
    });

    // 詳細モーダル内のボタン制御
    getEl('btnEdit').onclick = () => setModal(modals.detail, true, 1);
    getEl('btnDetailback').onclick = () => setModal(modals.detail, true, 0);
    getEl('btnDetailClose').onclick = () => setModal(modals.detail, false);

    // タスク更新実行
    // ※HTMLの更新ボタンIDを 'btnDetailSubmit' と仮定しています
    const btnDetailSubmit = getEl('btnDetailSubmit');
    if (btnDetailSubmit) {
        btnDetailSubmit.onclick = () => {
            const titleVal = modals.detail.titleIn.value.trim();
            const dateVal = modals.detail.dateIn.value;
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            if (!titleVal) return alert('タイトルを入力してください');
            if (dateVal){
                if (new Date(dateVal) < today) return alert('日付は今日以降を選択してください');
            }

            Loader.show();
            fetch(`/tasks/${currentTaskId}`, {
                method: 'PATCH',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({
                    title: titleVal,
                    target_date: dateVal,
                    detail: modals.detail.descIn.value
                })
            })
            .then(res => res.json())
            .then(() => {
                Loader.hide();
                setModal(modals.detail, true, 2);
                setTimeout(() => location.reload(), 800);
            });
        };
    }

    // --- 3. ゴール編集処理 ---
    const btnGoalEdit = getEl('btnGoalEdit');
    if (btnGoalEdit) {
        btnGoalEdit.onclick = () => {
            // Blade変数から値をセット（初期表示用）
            modals.editGoal.titleIn.value = "{{ $goal->goal ?? '' }}";
            modals.editGoal.dateIn.value = "{{ $goal->target_date ?? '' }}";
            modals.editGoal.descIn.value = "{{ $goal->detail ?? '' }}"; 
            setModal(modals.editGoal, true, 0);
        };
    }

    getEl('btnEditBack').onclick = () => setModal(modals.editGoal, false);

    // ゴール更新実行
    // ※HTMLの更新ボタンIDを 'btnGoalSubmit' と仮定しています
    const btnGoalSubmit = getEl('btnEditSubmit');
    if (btnGoalSubmit) {
        btnGoalSubmit.onclick = () => {

            const goalTitle = modals.editGoal.titleIn.value.trim();
            const goalDate = modals.editGoal.dateIn.value;
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            //タイトルチェック
            if(!goalTitle) {
                alert("目標のタイトルを入力してください");
                return;
            }
            //日付の整合性
            if(goalDate){
                if(goalDate<today){
                    alert("今日以降の日付を選択してください")
                    return;
                }
            }else{
                if(!confirm("日付が入力されていませんがよろしいですか？")){
                    return;
                }
            }

            Loader.show();
            fetch(`/goals/{{ $goal->id ?? 0 }}`, {
                method: 'PATCH',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({
                    goal: modals.editGoal.titleIn.value,
                    target_date: modals.editGoal.dateIn.value,
                    detail: modals.editGoal.descIn.value
                })
            })
            .then(res => res.json())
            .then(() => {
                Loader.hide();
                setModal(modals.editGoal, true, 1);
                setTimeout(() => location.reload(), 800);
            });
        };
    }

    // --- 4. タスク新規追加処理 ---
    const btnTaskAdd = getEl('btnTaskAdd');
    if (btnTaskAdd) {
        btnTaskAdd.onclick = () => {
        // フォームをリセット
            modals.add.titleIn.value = '';
            modals.add.dateIn.value = '';
            modals.add.descIn.value = '';
            setModal(modals.add, true, 0);
        };
    }

    // 登録実行
    modals.add.submit.onclick = () => {
        const titleVal = modals.add.titleIn.value.trim();
        const deadlineVal = modals.add.dateIn.value;
        const detailVal = modals.add.descIn.value;
        const today = new Date();
        today.setHours(0, 0, 0, 0);

        if (!titleVal) return alert('タイトルを入力してください');
    
        // 日付バリデーション
        if (deadlineVal && new Date(deadlineVal) < today) {
            return alert('日付は今日以降を選択してください');
        }
        Loader.show();
        fetch('/tasks', {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json', 
                'X-CSRF-TOKEN': csrfToken 
            },
            body: JSON.stringify({
                goal_id: "{{ $goal->id }}", // 現在表示中のゴールID
                title: titleVal,
                detail: detailVal,
                target_date: deadlineVal
            })
        })
        .then(res => {
            if (!res.ok) throw new Error();
            return res.json();
        })
        .then(() => {
            Loader.hide();
            setModal(modals.add, true, 1); // 成功表示
            setTimeout(() => location.reload(), 800);
        })
        .catch(err => {
            Loader.hide();
            alert('保存に失敗しました');});
    };

    modals.add.back.onclick = () => setModal(modals.add, false);

    // --- 5.ゴール完了のトリガー ---
    const btnGoalCheck = getEl('btnGoalCheck');
    if (btnGoalCheck) {
        btnGoalCheck.onclick = (e) => {
            const d = e.currentTarget.dataset;

            // 既に完了(flg=1)なら何もしない
            if (d.flg == "1") return;

            currentTaskId = d.id;   // ゴールIDをセット
            currentMode = 'goalComplete'; // ゴール完了モードとして定義

            // モーダルのテキストをセット
            modals.check.taskTitle.innerText = `目標「${d.title}」`;
            modals.check.actionText.innerText = '完了（目標達成）にしますか？';
            modals.check.confirmBtn.innerText = '達成！';

            setModal(modals.check, true, 0); // モーダルを表示
        };
    }

    // --- 6. 共通：背景クリックで閉じる ---
    window.onclick = (event) => {
        if (event.target.id && event.target.id.endsWith('Modal')) {
            event.target.style.display = "none";
        }
    };
});
</script>
@endpush