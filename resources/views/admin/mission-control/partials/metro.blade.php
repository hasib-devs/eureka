{{-- Metro pipeline map — every task hangs at its station. --}}
@php
    $mcStations = [
        'awaiting_review' => ['icon' => 'bx-time-five', 'label' => 'Queue', 'color' => '#8b9781'],
        'under_review' => ['icon' => 'bx-book-open', 'label' => 'Reading', 'color' => '#c4f04a'],
        'approved' => ['icon' => 'bx-calendar-check', 'label' => 'Scheduled', 'color' => '#5fe87b'],
        'in_progress' => ['icon' => 'bx-cog', 'label' => 'Working', 'color' => '#9efb25'],
        'delivered' => ['icon' => 'bx-check-double', 'label' => 'Shipped', 'color' => '#e9f5d0'],
    ];
@endphp

<div class="mc-metro">
    <div class="mc-metro-line">
        @foreach ($mcStations as $key => $st)
            <div class="mc-station" style="--st: {{ $st['color'] }}">
                <span class="mc-st-node" :class="stationBusy('{{ $key }}') && 'mc-st-node--busy'">
                    <i class="bx {{ $st['icon'] }}"></i>
                </span>
                <span class="mc-st-lbl">{{ $st['label'] }}</span>
                @if ($key === 'delivered')
                    <button type="button" class="mc-st-chip" x-show="deliveredTasks.length" @click="tab = 'delivered'"
                        x-text="deliveredTasks.length + ' shipped ✦'"></button>
                @else
                    <template x-for="t in stationTasks('{{ $key }}')" :key="t.id">
                        <button type="button" class="mc-st-chip" x-text="t.title" @click="drawerId = t.id"></button>
                    </template>
                @endif
            </div>
        @endforeach
    </div>
</div>
