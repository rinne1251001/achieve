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
                        <input type="checkbox" id="2_2" />
                        <a href="#" style="flex-grow: 1;">〇〇をしよう</a>
                        <span class="material-symbols-outlined">delete</span>
                    </div>
                    <div class="task_li">
                        <input type="checkbox" id="2_3" />
                        <a href="#" style="flex-grow: 1;">△△をしよう</a>
                        <span class="material-symbols-outlined">delete</span>
                    </div>
                    <div class="task_li">
                        <input type="checkbox" id="2_4" />
                        <a href="#" style="flex-grow: 1;">□□をしよう</a>
                        <span class="material-symbols-outlined">delete</span>
                    </div>
                    <div class="task_li">
                        <div class="circle task_plus">+</div>自分で追加する
                    </div>
                </div>

                <div class="task_ul">
                    <a href="#"><h3>ゴール3</h3></a>
                    <div class="task_li">
                        <input type="checkbox" id="3_1" />
                        <a href="#" style="flex-grow: 1;">〇〇をしよう</a>
                        <span class="material-symbols-outlined">delete</span>
                    </div>
                    <div class="task_li">
                        <input type="checkbox" id="3_2" />
                        <a href="#" style="flex-grow: 1;">△△をしよう</a>
                        <span class="material-symbols-outlined">delete</span>
                    </div>
                    <div class="task_li">
                        <div class="circle task_plus">+</div>自分で追加する
                    </div>
                </div>

                <div class="task_ul">
                    <a href="#"><h3>ゴール4</h3></a>
                    <div class="task_li">
                        <input type="checkbox" id="4_1" />
                        <a href="#" style="flex-grow: 1;">〇〇をしよう</a>
                        <span class="material-symbols-outlined">delete</span>
                    </div>
                    <div class="task_li">
                        <input type="checkbox" id="4_2" />
                        <a href="#" style="flex-grow: 1;">△△をしよう</a>
                        <span class="material-symbols-outlined">delete</span>
                    </div>
                    <div class="task_li">
                        <input type="checkbox" id="4_3" />
                        <a href="#" style="flex-grow: 1;">□□をしよう</a>
                        <span class="material-symbols-outlined">delete</span>
                    </div>
                    <div class="task_li">
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
<script>
    document.addEventListener('DOMContentLoaded', () => {
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
    });
</script>
@endpush