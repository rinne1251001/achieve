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
                    <div class="task_ul {{ $index === 0 ? 'active' : '' }}">
                        <a href="{{ route('goals.show', $goal->id) }}"><h3>{{ $goal->goal }}</h3></a>

                        @foreach($goal->tasks as $task)
                            <div class="task_li">

                                <input type="checkbox"
                                        id="task_{{ $task->id }}"
                                        class="task_trigger" 
                                        data-id="{{ $task->id }}"
                                        data-title="{{ $task->task }}" 
                                        data-mode="complete" />

                                <a href="#" style="flex-grow: 1;">{{ $task->task }}</a>

                                <span class="material-symbols-outlined task_trigger" data-id="{{ $task->id }}" data-title="{{ $task->task }}" data-mode="delete">delete</span>
                            </div>
                        @endforeach
   
                        <div class="task_li add-trigger">
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
            <label for="detail">説明</label>
            <textarea id="detail" placeholder="詳細を入力" rows="5"></textarea>
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


<script>
    document.addEventListener('DOMContentLoaded', () => {
        // ==========================================
        // 1. 共通設定・要素取得
        // ==========================================
        const getEl = id => document.getElementById(id);
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const modals = {
            check: {
                base:    getEl('checkModal'),
                steps:   [getEl('modalStep1'), getEl('modalStep2')],
                title:   getEl('modalTaskTitle'),
                action:  getEl('modalActionText'),
                confirm: getEl('btnConfirm'),
                msg:     getEl('modalSuccessMessage')
            },
            add: {
                base:    getEl('addModal'),
                steps:   [getEl('addStep1'), getEl('addStep2')],
                goal:    getEl('addGoalTitle'),
                titleIn: getEl('title'),
                descIn:  getEl('detail'),
                submit:  getEl('btnSubmit'),
                back:    getEl('btnBack')
            }
        };

        // 現在操作中のタスク情報を保持する変数
        let currentTarget = {
            element: null,  // 押された要素（チェックボックス or 削除アイコン）
            id: null,       // タスクID
            mode: null,     // 'complete' or 'delete'
            goalId: null    // 追加時のゴールID
        };

        const configs = {
            complete: { action: '完了にします', btn: '完了する', msg: '登録しました！', method: 'PATCH' },
            delete:   { action: '削除します',   btn: '削除する', msg: '削除しました！', method: 'DELETE' }
        };

        // モーダル表示切り替え関数
        const setModal = (m, show, step = 0) => {
            if (!m.base) return;
            m.base.style.display = show ? 'flex' : 'none';
            m.steps.forEach((s, i) => {
                if (s) s.style.display = (show && i === step) ? 'grid' : 'none';
            });
        };

        // ==========================================
        // 2. タブ・アコーディオン（UI動作）
        // ==========================================
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

        document.querySelectorAll('.task_acc_btn').forEach(btn => {
            btn.addEventListener('click', () => {
                btn.closest('.task_li').classList.toggle('open');
            });
        });

        // 3. 完了・削除アクション
document.querySelectorAll('.task_trigger').forEach(trigger => {
    // type を 'click' に統一すると制御しやすくなります
    trigger.addEventListener('click', (e) => {
        const target = e.currentTarget; // e.target より currentTarget が確実

        // チェックボックスの場合、勝手にチェックさせない（モーダルで確定してから変える）
        if (target.tagName === 'INPUT') {
            e.preventDefault(); 
        }

        currentTarget.element = target;
        currentTarget.id = target.getAttribute('data-id'); // dataset.id でもOK
        currentTarget.mode = target.getAttribute('data-mode');

        const c = configs[currentTarget.mode];
        if (!c) return;

        modals.check.title.innerText = `「${target.getAttribute('data-title')}」`;
        modals.check.action.innerText = c.action;
        modals.check.confirm.innerText = c.btn;
        modals.check.msg.innerText = c.msg;

        setModal(modals.check, true, 0);
    });
});

        // ------------------------------------------
        // 実行ボタンが押されたらAjax通信
        // ------------------------------------------
        modals.check.confirm.onclick = () => {
            if (!currentTarget.id || !currentTarget.mode) return;

            const c = configs[currentTarget.mode];
            const url = `/tasks/${currentTarget.id}/${currentTarget.mode === 'complete' ? 'check' : ''}`; // ルーティングに合わせて調整
            
            // データ送信
            fetch(url, {
                method: c.method === 'DELETE' ? 'DELETE' : 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ completed: true }) // 削除時はbody無視されるのでこれでOK
            })
            .then(response => response.json())
            .then(data => {
                // 成功したら画面更新
                setModal(modals.check, true, 1); // 成功メッセージ表示
                setTimeout(() => {
                    location.reload(); // リロードして反映
                }, 1000);
            })
            .catch(error => {
                console.error('Error:', error);
                alert('エラーが発生しました');
                setModal(modals.check, false);
            });
        };

        // キャンセルボタン
        getEl('btnCancel').onclick = () => {
            setModal(modals.check, false);
            // チェックボックスだった場合、チェックを元に戻す
            if (currentTarget.element?.tagName === 'INPUT') {
                currentTarget.element.checked = false;
            }
        };

        // ==========================================
        // 4. 新規追加アクション
        // ==========================================
        document.querySelectorAll('.add-trigger').forEach(btn => {
            btn.onclick = () => {
                const ul = btn.closest('.task_ul');
                const goalHeader = ul.querySelector('h3');
                
                // 追加先のゴールIDを取得（HTML側で data-goal-id を設定してください）
                // 例: <div class="task_ul" data-goal-id="1">...
                currentTarget.goalId = ul.dataset.goalId; 

                modals.add.goal.innerText = goalHeader ? goalHeader.innerText : '新規タスク';
                setModal(modals.add, true, 0);
            };
        });

        modals.add.submit.onclick = () => {
            const title = modals.add.titleIn.value;
            const detail = modals.add.descIn.value;

            if (!title) return alert('タイトルを入力してください');
            if (!currentTarget.goalId) return alert('ゴールIDが見つかりません');

            // 新規登録のAjax
            fetch('/tasks', { // ルーティングに合わせて調整
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    goal_id: currentTarget.goalId,
                    title: title,
                    detail: detail
                })
            })
            .then(response => response.json())
            .then(data => {
                setModal(modals.add, true, 1); // 成功メッセージ
                setTimeout(() => {
                    location.reload();
                }, 1000);
            })
            .catch(error => {
                console.error('Error:', error);
                alert('登録に失敗しました');
            });
        };

        modals.add.back.onclick = () => setModal(modals.add, false);
    });
</script>
@endpush