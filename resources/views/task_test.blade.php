@extends('layouts.app')
@section('title', 'タスクのテストページ')
@section('content')

    <main>
        <h1 style="display: flex; align-items: center; margin-bottom: 0; gap: 8px;">
            <span class="material-symbols-outlined" style="font-size: 1.3em;">
                check_circle
            </span>
            タスク
        </h1>
        <div style="display: grid; padding: 0 clamp(10px, 5vw, 30px); gap: clamp(10px, 5vw, 30px);">

            <div class=task_container>
                <ul class="task_tab">
                    <li class="active">ゴール2</li>
                    <li>ゴール3</li>
                    <li>ゴール4</li>
                </ul>

                <div class="task_ul active">
                    <a href="#"><h3>ゴール2</h3></a>
                    <div class="task_li">
                        <input type="checkbox" id="2_2" class="task_trigger" data-title="〇〇をしよう" data-mode="complete"/>
                        <a href="#" style="flex-grow: 1;">〇〇をしよう</a>
                        <span class="material-symbols-outlined task_trigger" data-title="〇〇をしよう" data-mode="delete">delete</span>
                    </div>
                    <div class="task_li">
                        <input type="checkbox" id="2_3" class="task_trigger" data-title="△△をしよう" data-mode="complete"/>
                        <a href="#" style="flex-grow: 1;">△△をしよう</a>
                        <span class="material-symbols-outlined task_trigger" data-title="△△をしよう" data-mode="delete">delete</span>
                    </div>
                    <div class="task_li">
                        <input type="checkbox" id="2_4" class="task_trigger" data-title="〇〇をしよう" data-mode="complete" />
                        <a href="#" style="flex-grow: 1;">□□をしよう</a>
                        <span class="material-symbols-outlined task_trigger" data-title="〇〇をしよう" data-mode="delete">delete</span>
                    </div>
                    <div class="task_li add-trigger">
                        <div class="circle task_plus">+</div>自分で追加する
                    </div>
                </div>

                <div class="task_ul">
                    <a href="#"><h3>ゴール3</h3></a>
                    <div class="task_li">
                        <input type="checkbox" id="3_1" class="task_trigger" data-title="〇〇をしよう" data-mode="complete" />
                        <a href="#" style="flex-grow: 1;">〇〇をしよう</a>
                        <span class="material-symbols-outlined task_trigger" data-title="〇〇をしよう" data-mode="delete">delete</span>
                    </div>
                    <div class="task_li">
                        <input type="checkbox" id="3_2" class="task_trigger" data-title="〇〇をしよう" data-mode="complete" />
                        <a href="#" style="flex-grow: 1;">△△をしよう</a>
                        <span class="material-symbols-outlined task_trigger" data-title="〇〇をしよう" data-mode="delete">delete</span>
                    </div>
                    <div class="task_li add-trigger">
                        <div class="circle task_plus task_trigger">+</div>自分で追加する
                    </div>
                </div>

                <div class="task_ul">
                    <a href="#"><h3>ゴール4</h3></a>
                    <div class="task_li">
                        <input type="checkbox" id="4_1" class="task_trigger" data-title="〇〇をしよう" data-mode="complete" />
                        <a href="#" style="flex-grow: 1;">〇〇をしよう</a>
                        <span class="material-symbols-outlined task_trigger" data-title="〇〇をしよう" data-mode="delete">delete</span>
                    </div>
                    <div class="task_li">
                        <input type="checkbox" id="4_2" class="task_trigger" data-title="〇〇をしよう" data-mode="complete" />
                        <a href="#" style="flex-grow: 1;">△△をしよう</a>
                        <span class="material-symbols-outlined task_trigger" data-title="〇〇をしよう" data-mode="delete">delete</span>
                    </div>
                    <div class="task_li">
                        <input type="checkbox" id="4_3" class="task_trigger" data-title="〇〇をしよう" data-mode="complete" />
                        <a href="#" style="flex-grow: 1;">□□をしよう</a>
                        <span class="material-symbols-outlined task_trigger" data-title="〇〇をしよう" data-mode="delete">delete</span>
                    </div>
                    <div class="task_li add-trigger">
                        <div class="circle task_plus">+</div>自分で追加する
                    </div>
                </div>
            </div>

            <div class="task_container2">
                <div class="task_calendar_container">
                    <div style="display: flex; align-items: center; margin-bottom: clamp(5px, 1vw, 10px); gap: clamp(20px, 4vw, 40px);">
                        <div style="cursor: pointer;">◀</div>
                        <div style="font-size: clamp(2em, 3.5vw, 2.5em); font-weight: bold;">１月</div>
                        <div style="cursor: pointer;">▶</div>
                    </div>
                    <table class="task_calendar">
                        <thead>
                            <tr><th>Sun</th><th>Mon</th><th>Tue</th><th>Wed</th><th>Thu</th><th>Fri</th><th>Sat</th></tr>
                        </thead>
                        <tbody>
                            <tr><td class="task_notMonth">30</td><td class="task_notMonth">31</td><td>1</td><td>2</td><td>3</td><td>4</td><td>5</td></tr>
                            <tr><td>6</td><td>7</td><td>8</td><td>9</td><td>10</td><td>11</td><td>12</td></tr>
                            <tr><td>13</td><td>14</td><td class="task_complete">15<div class="task_tooltip"><a href="#">〇〇達成</a></div></td><td>16</td><td>17</td><td>18</td><td>19</td></tr>
                            <tr><td>20</td><td>21</td><td>22</td><td>23</td><td>24</td><td>25</td><td class="task_complete">26<div class="task_tooltip"><a href="#">□□達成！</a><br><a href="#">〇〇達成！</a></div></td></tr>
                            <tr><td>27</td><td>28</td><td>29</td><td>30</td><td>31</td><td class="task_notMonth">1</td><td class="task_notMonth">2</td></tr>
                        </tbody>
                    </table>
                </div>

                <div class="task_achieved">
                    <h3>達成したタスク</h3>
                    <div class="task_li" style="flex-wrap: wrap; transition: all 0.3s ease;">
                        <span class="material-symbols-outlined task_acc_btn" style="cursor: pointer; font-weight: bold;">keyboard_arrow_down</span><a href="#" style="font-weight: bold;">ゴール1</a>
                        <div class="task_acc_menu">
                            <ul>
                                <li>〇〇をしよう</li>
                                <li>△△をしよう</li>
                                <li>□□をしよう</li>
                            </ul>
                        </div>
                    </div>
                    <div class="task_li" style="flex-wrap: wrap; transition: all 0.3s ease;">
                        <span class="material-symbols-outlined task_acc_btn" style="cursor: pointer; font-weight: bold;">keyboard_arrow_down</span><a href="#" style="font-weight: bold;">ゴール3</a>
                        <div class="task_acc_menu">
                            <ul>
                                <li>〇〇をしよう</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>
@endsection
@push('scripts')

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

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // 1. 要素取得の共通化
        const getEl = id => document.getElementById(id);
        
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
                descIn:  getEl('detail')
            }
        };

        let currentTrigger = null;
        const configs = {
            complete: { action: '完了しました', btn: '完了した', msg: '登録しました！' },
            delete:   { action: '削除します',   btn: '削除する', msg: '削除しました！' }
        };

        // 2. モーダル制御関数
        const setModal = (m, show, step = 0) => {
            if (!m.base) return;
            m.base.style.display = show ? 'flex' : 'none';
            m.steps.forEach((s, i) => {
                if (s) s.style.display = (show && i === step) ? 'grid' : 'none';
            });
        };

        // --- A. タブ切り替え（ご指定のロジック） ---
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

        // --- B. アコーディオン（ご指定のロジック） ---
        document.querySelectorAll('.task_acc_btn').forEach(btn => {
            btn.addEventListener('click', () => {
                btn.closest('.task_li').classList.toggle('open');
            });
        });

        // --- C. 完了・削除モーダルの処理 ---
        document.querySelectorAll('.task_trigger').forEach(trigger => {
            const type = trigger.tagName === 'INPUT' ? 'change' : 'click';
            trigger.addEventListener(type, ({ target }) => {
                if (target.tagName === 'INPUT' && !target.checked) return;
                
                currentTrigger = target;
                const c = configs[target.dataset.mode];
                
                modals.check.title.innerText = `「${target.dataset.title}」`;
                modals.check.action.innerText = c.action;
                modals.check.confirm.innerText = c.btn;
                modals.check.msg.innerText = c.msg;
                setModal(modals.check, true);
            });
        });

        modals.check.confirm.onclick = () => {
            setModal(modals.check, true, 1);
            setTimeout(() => setModal(modals.check, false), 1200);
        };

        getEl('btnCancel').onclick = () => {
            setModal(modals.check, false);
            if (currentTrigger?.tagName === 'INPUT') currentTrigger.checked = false;
        };

        // --- D. 新規追加モーダルの処理 ---
        document.querySelectorAll('.add-trigger').forEach(btn => {
            btn.onclick = () => {
                const goalHeader = btn.closest('.task_ul').querySelector('h3');
                modals.add.goal.innerText = goalHeader ? goalHeader.innerText : '';
                setModal(modals.add, true);
            };
        });

        getEl('btnSubmit').onclick = () => {
            if (!modals.add.titleIn.value) return alert('タイトルを入力してください');
            setModal(modals.add, true, 1);
            setTimeout(() => {
                setModal(modals.add, false);
                modals.add.titleIn.value = modals.add.descIn.value = '';
            }, 1200);
        };

        getEl('btnBack').onclick = () => setModal(modals.add, false);
    });
</script>
@endpush