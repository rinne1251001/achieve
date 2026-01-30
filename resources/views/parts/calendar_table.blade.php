<div class="calendar_container">
    <div style="display: flex; align-items: center; margin-bottom: clamp(5px, 1vw, 10px); gap: clamp(20px, 4vw, 40px);">
        <a href="{{ request()->fullUrlWithQuery(['year' => $prev->year, 'month' => $prev->month]) }}" style="text-decoration: none; cursor: pointer;">◀</a>
        <div style="font-size: clamp(2em, 3.5vw, 2.5em); font-weight: bold;">{{ $monthTitle }}</div>
        <a href="{{ request()->fullUrlWithQuery(['year' => $next->year, 'month' => $next->month]) }}" style="text-decoration: none; cursor: pointer;">▶</a>
    </div>
    
    <table class="calendar">
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
                        $daysTasks = collect($taskMap[$dateStr] ?? [])->where('flg', 0);
                        $isCurrentMonth = $date->month == $dt->month;
                        $hasTasks = count($daysTasks) > 0;
                    @endphp

                    {{-- カレンダーの1セル --}}
                    <td @class([ 'calender_complete' => $hasTasks && $isCurrentMonth ]) @style([ 'color: var(--font-light-color)' => !$isCurrentMonth ])>
                    
                        {{ $date->day }}

                        @if($hasTasks && $isCurrentMonth)

                            <div class="calender_tooltip">

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
    document.addEventListener('click', (e) => {
        const cell = e.target.closest('.calender_complete');
        const tooltip = cell?.querySelector('.calender_tooltip');
        const activeTooltips = document.querySelectorAll('.calender_tooltip.active');

        activeTooltips.forEach(el => {
            if (el !== tooltip) {
                el.classList.remove('active');
                el.closest('.calender_complete').classList.remove('tooltip-open');
            }
        });

        if (tooltip) {
            tooltip.classList.toggle('active');
            cell.classList.toggle('tooltip-open');
        }
    });
</script>