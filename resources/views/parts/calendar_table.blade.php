<div class="task_calendar_container">
    <div style="display: flex; align-items: center; margin-bottom: clamp(5px, 1vw, 10px); gap: clamp(20px, 4vw, 40px);">
        <a href="{{ request()->fullUrlWithQuery(['year' => $prev->year, 'month' => $prev->month]) }}" style="text-decoration: none; cursor: pointer;">◀</a>
        <div style="font-size: clamp(2em, 3.5vw, 2.5em); font-weight: bold;">{{ $monthTitle }}</div>
        <a href="{{ request()->fullUrlWithQuery(['year' => $next->year, 'month' => $next->month]) }}" style="text-decoration: none; cursor: pointer;">▶</a>
    </div>
    
    <table class="task_calendar">
        <thead>
            <tr><th>Sun</th><th>Mon</th><th>Tue</th><th>Wed</th><th>Thu</th><th>Fri</th><th>Sat</th></tr>
        </thead>
        <tbody>
            <tr>
                @foreach($calendarDates as $key => $date)
                    @if($key > 0 && $key % 7 == 0)
                        </tr><tr>
                    @endif

                    @php
                        $dateStr = $date->format('Y-m-d');
                        $daysTasks = $taskMap[$dateStr] ?? [];
                        $isCurrentMonth = $date->month == $dt->month;
                        $hasTasks = count($daysTasks) > 0;
                    @endphp

                    {{-- カレンダーの1セル --}}
                    <td @class([ 'task_complete' => $hasTasks && $isCurrentMonth ]) @style([ 'color: var(--font-light-color)' => !$isCurrentMonth ])>
                    
                        {{ $date->day }}

                        @if($hasTasks && $isCurrentMonth)

                            <div class="task_tooltip">

                                <ul>
                                    @foreach($daysTasks as $task)
                                        <li><a href="{{ route('goals.show', $task->goal_id) }}">{{ $task->task }}達成！</a></li>
                                    @endforeach
                                </ul>
                            
                            </div>

                        @endif
                    </td>
                @endforeach
            </tr>
        </tbody>
    </table>
</div>

<script>
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
</script>