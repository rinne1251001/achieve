@extends('layouts.app')
@section('title', 'タスクのテストページ')
@section('content')

    <main>
        <h1 style="display: flex; align-items: center; margin-bottom: 0; gap: 8px;">
            <span class="material-symbols-outlined" style="font-size: 1.3em;">check_circle</span>タスク
        </h1>

        <div style="display: grid; padding: 0 clamp(10px, 5vw, 30px); gap: clamp(10px, 5vw, 30px);">

            <div class="task_container">
                <ul class="task_tab">
                    @foreach($goals as $index => $goal)
                        <li class="{{ $index === 0 ? 'active' : '' }}">{{ $goal->goal }}</li>
                    @endforeach
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
            </div>

            <div class="task_container2">
                @include('parts.calendar_table') 

                <div class="task_achieved">
                    <h3>達成したタスク</h3>
                    @foreach($achievedGoals as $goal)
                        <div class="task_li" style="flex-wrap: wrap; transition: all 0.3s ease;">
                            <span class="material-symbols-outlined task_acc_btn" style="cursor: pointer; font-weight: bold;">keyboard_arrow_down</span>
                            <a href="#" style="font-weight: bold;">{{ $goal->goal }}</a>
                            <div class="task_acc_menu">
                                <ul>
                                    @foreach($goal->tasks as $task)
                                        <li>{{ $task->task }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </main>

{{-- 各種モーダル（変更なし） --}}
<div id="checkModal">
    <div id="modalStep1">
        <p style="line-height: 1.6;">
            <span id="modalTaskTitle" style="font-weight: bolder;"></span>を<br>
            <span id="modalActionText"></span>か？
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

<div id="addModal">
    <div id="addStep1">
        <h3 id="addGoalTitle"></h3>
        <div style="display: grid;">
            <label for="title">title</label>
            <input id="title" placeholder="タスクの名前" required>
        </div>
        <div style="display: grid;">
            <label for="deadline">期限</label>
            <input id="deadline" type="date" required>
        </div>
        <div style="display: grid;">
            <label for="detail">説明</label>
            <textarea id="detail" placeholder="タスクの詳細を入力" rows="5" class="chat_input"></textarea>
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
        <h3 id="detailGoalTitle"></h3>
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
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const getEl = id => document.getElementById(id);
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
        }
    };

    let currentTarget = {
        element: null, id: null, mode: null, goalId: null, title: null, date: null, detail: null
    };

    const configs = {
        complete: { action: '完了にします', btn: '完了する', msg: '登録しました！', method: 'PATCH' },
        delete: { action: '削除します', btn: '削除する', msg: '削除しました！', method: 'DELETE' }
    };

    const setModal = (m, show, step = 0) => {
        if (!m.base) return;
        m.base.style.display = show ? 'flex' : 'none';
        m.steps.forEach((s, i) => {
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
        const c = configs[currentTarget.mode];
        const url = `/tasks/${currentTarget.id}/${currentTarget.mode === 'complete' ? 'check' : ''}`;
        
        fetch(url, {
            method: c.method,
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ completed: true })
        })
        .then(res => res.json())
        .then(() => {
            setModal(modals.check, true, 1);
            setTimeout(() => location.reload(), 800);
        })
        .catch(err => alert('エラーが発生しました'));
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

        if (!titleVal) return alert('タイトルを入力してください');
        if(deadline){
            if (selectedDate < today) return alert('日付は今日以降を選択肢てください。')
        }

        fetch('/tasks', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({
                goal_id: currentTarget.goalId,
                title: titleVal,
                detail: detailVal,
                target_date: deadlineVal
            })
        })
        .then(res => res.json())
        .then(() => {
            setModal(modals.add, true, 1);
            setTimeout(() => location.reload(), 800);
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
                detail = detail.replace(/"/g, '')
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
        const titleVal = modals.detail.titleIn.value.trim(); // 空白のみも防ぐ
        const dateVal = modals.detail.dateIn.value;
        const descVal = modals.detail.descIn.value;
        const today = new Date();
        today.setHours(0, 0, 0, 0); // 時間をリセットして日付のみで比較
        const selectedDate = new Date(dateVal);

        // --- 1. タイトルの入力チェック ---
        if (!titleVal) {
            return alert('タイトルを入力してください');
        }

        // --- 2. 日付のチェック ---
        if (dateVal) {
            if(selectedDate < today) {
                return alert('日付は今日以降を選択してください。');
            }
        }

        fetch(`/tasks/${currentTarget.id}`, {
            method: 'PATCH',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({
                title: modals.detail.titleIn.value,
                detail: modals.detail.descIn.value,
                target_date: modals.detail.dateIn.value
            })
        })
        .then(res => res.json())
        .then(() => {
            setModal(modals.detail, true, 2);
            setTimeout(() => location.reload(), 800);
        });
    };

    modals.detail.back.onclick = () => setModal(modals.detail, true, 0);
    modals.detail.close.onclick = () => setModal(modals.detail, false);
});
</script>
@endpush