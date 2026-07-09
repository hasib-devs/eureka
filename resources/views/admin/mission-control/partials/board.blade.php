@php
    $mcStepIcons = [
        'awaiting_review' => 'bx-time-five',
        'under_review' => 'bx-book-open',
        'approved' => 'bx-calendar-check',
        'in_progress' => 'bx-cog',
        'delivered' => 'bx-check-double',
    ];
    $mcStepLabels = [
        'awaiting_review' => 'Queue',
        'under_review' => 'Read',
        'approved' => 'Approve',
        'in_progress' => 'Build',
        'delivered' => 'Ship',
    ];
@endphp

<div class="mc-tabs">
    <button type="button" class="mc-tab" :class="tab === 'active' && 'mc-tab-on'" @click="tab = 'active'">
        Live board <span class="mc-tab-count" x-text="activeTasks.length"></span>
    </button>
    <button type="button" class="mc-tab" :class="tab === 'delivered' && 'mc-tab-on'" @click="tab = 'delivered'">
        Shipped <span class="mc-tab-count" x-text="deliveredTasks.length"></span>
    </button>
</div>

<div class="mc-board">
    <template x-for="task in visibleTasks" :key="task.id">
        <article class="mc-card" :class="['mc-card--st-' + task.status, task.priority === 'urgent' && 'mc-card--urgent']">
            <div class="mc-state-row">
                <span class="mc-state"><i></i><span x-text="task.status_label"></span></span>
                <span class="mc-prio" :class="'mc-prio--' + task.priority" x-text="task.priority"></span>
                <span class="mc-time" x-text="task.created_at_human"></span>
            </div>

            <h3 class="mc-card-title" x-text="task.title" @click="drawerId = task.id"></h3>
            <p class="mc-card-desc" x-text="task.description"></p>

            <div class="mc-thumb-wrap" x-show="task.image_url" @click="lightbox = task.image_url">
                <img class="mc-thumb" :src="task.image_url || ''" alt="Reference" loading="lazy">
                <span class="mc-thumb-zoom"><i class='bx bx-expand-alt'></i> View reference</span>
            </div>

            <div class="mc-meta-row" x-show="task.due_date || countdown(task)">
                <span class="mc-chip" x-show="task.due_date"><i class='bx bx-calendar-event'></i> <span x-text="task.due_date"></span></span>
                <span class="mc-chip mc-chip--overdue" x-show="task.overdue"><i class='bx bx-error'></i> Overdue</span>
                <span class="mc-chip mc-chip--countdown" x-show="countdown(task)" x-text="countdown(task)"></span>
            </div>

            <span class="mc-ticker mc-shimmer" :class="tickerClass()" x-text="tickerLine(task)"></span>
            <div class="mc-thin-bar"><i :style="'width:' + statusPercent(task) + '%'"></i></div>

            <div class="mc-pipe" :class="isExecutor && 'mc-pipe--live'">
                <div class="mc-pipe-rail"><div class="mc-pipe-fill" :style="'width:' + pipeProgress(task)"></div></div>
                <div class="mc-pipe-steps">
                    @foreach ($boot['statuses'] as $s)
                        <button type="button" class="mc-step" :class="'mc-step--' + stageState(task, '{{ $s }}')"
                            title="{{ \App\Models\Task::statusLabel($s) }}" :disabled="!isExecutor"
                            @click="setStatus(task, '{{ $s }}')">
                            <span class="mc-step-node"><i class='bx {{ $mcStepIcons[$s] }}'></i></span>
                            <span class="mc-step-lbl">{{ $mcStepLabels[$s] }}</span>
                        </button>
                    @endforeach
                </div>
            </div>

            <div class="mc-actions">
                <button type="button" class="mc-act" x-show="!isExecutor" :disabled="task.reminder_wait_seconds > 0"
                    @click="remind(task)">
                    <i class='bx bx-bell'></i>
                    <span x-text="task.reminder_wait_seconds > 0 ? remindWait(task) : 'Nudge'"></span>
                </button>
                <button type="button" class="mc-act" @click="drawerId = task.id">
                    <i class='bx bx-message-square-dots'></i>
                    <span x-text="commentCount(task)"></span>
                </button>
                <span class="mc-act-spacer"></span>
                <button type="button" class="mc-act mc-act--icon" x-show="!isExecutor && task.editable"
                    title="Edit" @click="openCreate(task)"><i class='bx bx-edit-alt'></i></button>
                <button type="button" class="mc-act mc-act--icon mc-act--danger" x-show="!isExecutor && task.editable"
                    title="Delete" @click="removeTask(task)"><i class='bx bx-trash'></i></button>
                <button type="button" class="mc-cta" x-show="isExecutor && nextStep(task)" @click="advance(task)">
                    <span x-text="nextStep(task)?.label"></span>
                </button>
            </div>
        </article>
    </template>

    <div class="mc-empty" x-show="visibleTasks.length === 0">
        @include('admin.mission-control.partials.worker', ['size' => 46])
        <p x-text="tab === 'active' ? 'The board is clear — assign a task to wake the worker.' : 'Nothing shipped yet.'"></p>
    </div>
</div>
