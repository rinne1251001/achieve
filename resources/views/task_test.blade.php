@extends('layouts.app')
@section('title', 'タスクのテストページ')
@section('content')

    <main>
        <div style="display: grid; padding: 0 clamp(10px, 5vw, 30px); gap: clamp(10px, 5vw, 30px);">

            <div style="display: grid; grid-template-columns: repeat(2, 1fr); padding: clamp(10px, 5vw, 30px) 0; gap: clamp(10px, 5vw, 30px);">            
                <div style="display: grid; gap: 10px; padding: 10px; background-color: var(--base-color); border-radius: 10px;">
                    <h3 style="color: var(--bg-color); margin: 0;">ゴール2</h3>
                    <div class="task_li">
                        <input type="checkbox" id="2_2" />
                        <a style="flex-grow: 1;">〇〇をしよう</a>
                        <span class="material-symbols-outlined">delete</span>
                    </div>
                    <div class="task_li">
                        <input type="checkbox" id="2_3" />
                        <a style="flex-grow: 1;">△△をしよう</a>
                        <span class="material-symbols-outlined">delete</span>
                    </div>
                    <div class="task_li">
                        <input type="checkbox" id="2_4" />
                        <a style="flex-grow: 1;">□□をしよう</a>
                        <span class="material-symbols-outlined">delete</span>
                    </div>
                    <div class="task_li" style="justify-content: center;">
                        <div class="circle" style="width: 24px; height: 24px; background-color: var(--font-color); color: var(--bg-color); font-weight: bold;">+</div>自分で追加する
                    </div>
                </div>

                <div style="display: grid; gap: 10px; padding: 10px; background-color: var(--accent-color); border-radius: 10px;">
                    <h3 style="color: var(--bg-color); margin: 0;">ゴール3</h3>
                    <div class="task_li">
                        <input type="checkbox" id="3_1" />
                        <a style="flex-grow: 1;">〇〇をしよう</a>
                        <span class="material-symbols-outlined">delete</span>
                    </div>
                    <div class="task_li">
                        <input type="checkbox" id="3_2" />
                        <a style="flex-grow: 1;">△△をしよう</a>
                        <span class="material-symbols-outlined">delete</span>
                    </div>
                    <div class="task_li">
                        <input type="checkbox" id="3_3" />
                        <a style="flex-grow: 1;">□□をしよう</a>
                        <span class="material-symbols-outlined">delete</span>
                    </div>
                    <div class="task_li" style="justify-content: center;">
                        <div class="circle" style="width: 24px; height: 24px; background-color: var(--font-color); color: var(--bg-color); font-weight: bold;">+</div>自分で追加する
                    </div>
                </div>

                <div style="display: grid; gap: 10px; padding: 10px; background-color: var(--sub-color); border-radius: 10px;">
                    <h3 style="color: var(--bg-color); margin: 0;">ゴール4</h3>
                    <div class="task_li">
                        <input type="checkbox" id="4_1" />
                        <a style="flex-grow: 1;">〇〇をしよう</a>
                        <span class="material-symbols-outlined">delete</span>
                    </div>
                    <div class="task_li">
                        <input type="checkbox" id="4_2" />
                        <a style="flex-grow: 1;">△△をしよう</a>
                        <span class="material-symbols-outlined">delete</span>
                    </div>
                    <div class="task_li">
                        <input type="checkbox" id="4_3" />
                        <a style="flex-grow: 1;">□□をしよう</a>
                        <span class="material-symbols-outlined">delete</span>
                    </div>
                    <div class="task_li" style="justify-content: center;">
                        <div class="circle" style="width: 24px; height: 24px; background-color: var(--font-color); color: var(--bg-color); font-weight: bold;">+</div>自分で追加する
                    </div>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 30% 70%;">
                {{-- カレンダーはCarbonを使うと良いらしい --}}
                <div style="display: grid; place-content: center; place-items: center; width: 100%;">
                    <div style="display: flex; align-items: center;">
                        <div>◀</div>
                        <div style="font-size: 1.5em; font-weight: bold;">１月</div>
                        <div>▶</div>
                    </div>
                    <table class="task_calendar">
                        <thead>
                            <tr><th>Sun</th><th>Mon</th><th>Tue</th><th>Wed</th><th>Thu</th><th>Fri</th><th>Sat</th></tr>
                        </thead>
                        <tbody>
                            <tr><td></td><td></td><td>1</td><td>2</td><td>3</td><td>4</td><td>5</td></tr>
                            <tr><td>6</td><td>7</td><td>8</td><td>9</td><td>10</td><td>11</td><td>12</td></tr>
                            <tr><td>13</td><td>14</td><td>15</td><td>16</td><td>17</td><td>18</td><td>19</td></tr>
                            <tr><td>20</td><td>21</td><td>22</td><td>23</td><td>24</td><td>25</td><td>26</td></tr>
                            <tr><td>27</td><td>28</td><td>29</td><td>30</td><td>31</td><td></td><td></td></tr>
                        </tbody>
                    </table>
                </div>

                <div style="display: grid; gap: 10px; padding: 10px; background-color: var(--base-color); border-radius: 10px;">
                    <h3 style="color: var(--bg-color); margin: 0;">達成したタスク</h3>
                    <div class="task_li">
                        <div class="circle" style="width: 24px; height: 24px; background-color: var(--font-color); color: var(--bg-color); font-weight: bold;">+</div>ゴール1
                    </div>
                    <div class="task_li">
                        <div class="circle" style="width: 24px; height: 24px; background-color: var(--font-color); color: var(--bg-color); font-weight: bold;">+</div>ゴール2
                    </div>
                </div>
            </div>

        </div>
    </main>

@endsection