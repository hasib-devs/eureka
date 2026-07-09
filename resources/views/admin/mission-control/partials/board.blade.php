@php
    $mcStepIcons = [
        'awaiting_review' => 'bx-radar',
        'under_review' => 'bx-search-alt',
        'approved' => 'bx-badge-check',
        'in_progress' => 'bx-cog',
        'delivered' => 'bx-check-double',
    ];
    $mcStepLabels = [
        'awaiting_review' => 'Queue',
        'under_review' => 'Review',
        'approved' => 'Approve',
        'in_progress' => 'Build',
        'delivered' => 'Done',
    ];
@endphp

<div class="mc-tabs">
    <button type="button" class="mc-tab" :class="tab === 'active' && 'mc-tab-on'" @click="tab = 'active'">
        <i class='bx bx-broadcast'></i> Active <span class="mc-tab-count" x-text="activeTasks.length"></span>
    </button>
    <button type="button" class="mc-tab" :class="tab === 'delivered' && 'mc-tab-on'" @click="tab = 'delivered'">
        <i class='bx bx-check-double'></i> Delivered <span class="mc-tab-count" x-text="deliveredTasks.length"></span>
    </button>
</div>

<div class="mc-board">
    <template x-for="task in visibleTasks" :key="task.id">
        <article class="mc-card" :class="['mc-card--' + task.priority, 'mc-card--st-' + task.status]">
            <div class="mc-card-top">
                <span class="mc-priority" :class="'mc-priority--' + task.priority">
                    <i class="mc-dot"></i><span x-text="task.priority"></span>
                </span>
                <span class="mc-time" x-text="task.created_at_human"></span>
            </div>

            <h3 class="mc-card-title" x-text="task.title" @click="drawerId = task.id"></h3>
            <p class="mc-card-desc" x-text="task.description"></p>

            <div class="mc-thumb-wrap" x-show="task.image_url" @click="lightbox = task.image_url">
                <img class="mc-thumb" :src="task.image_url || ''" alt="Reference" loading="lazy">
                <span class="mc-thumb-zoom"><i class='bx bx-expand-alt'></i> View reference</span>
            </div>

            <div class="mc-meta" x-show="task.due_date">
                <span class="mc-chip"><i class='bx bx-calendar-event'></i> <span x-text="task.due_date"></span></span>
                <span class="mc-chip mc-chip--overdue" x-show="task.overdue"><i class='bx bx-error'></i> Overdue</span>
            </div>

            <div class="mc-status" :class="'mc-status--' + task.status">
                <span class="mc-status-icon"><i class='bx' :class="statusMeta(task.status).icon"></i></span>
                <div class="mc-status-copy">
                    <p class="mc-status-label" x-text="task.status_label"></p>
                    <span class="mc-ticker mc-shimmer" :class="tickerClass()" x-text="tickerLine(task)"></span>
                </div>
                <span class="mc-chip mc-chip--countdown" x-show="countdown(task)" x-text="countdown(task)"></span>
            </div>

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
                    <span x-text="task.reminder_wait_seconds > 0 ? remindWait(task) : 'Remind'"></span>
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
                    <i class='bx' :class="nextStep(task)?.icon"></i>
                    <span x-text="nextStep(task)?.label"></span>
                </button>
            </div>
        </article>
    </template>

    <div class="mc-empty" x-show="visibleTasks.length === 0">
        <span class="mc-empty-radar"><i class='bx bx-radar'></i></span>
        <p x-text="tab === 'active' ? 'No active missions — launch one.' : 'Nothing delivered yet.'"></p>
    </div>
</div>
