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
        <article class="mc-card" :class="'mc-card--' + task.priority">
            <div class="mc-card-top">
                <span class="mc-priority" :class="'mc-priority--' + task.priority" x-text="task.priority"></span>
                <span class="mc-time" x-text="task.created_at_human"></span>
            </div>

            <h3 class="mc-card-title" x-text="task.title" @click="drawerId = task.id"></h3>
            <p class="mc-card-desc" x-text="task.description"></p>

            <template x-if="task.image_url">
                <img class="mc-thumb" :src="task.image_url" alt="Reference" @click="lightbox = task.image_url">
            </template>

            <div class="mc-due" x-show="task.due_date">
                <i class='bx bx-calendar-event'></i>
                <span x-text="task.due_date"></span>
                <span class="mc-overdue" x-show="task.overdue">Overdue</span>
            </div>

            <div class="mc-status" :class="'mc-status--' + task.status">
                <span class="mc-status-icon"><i class='bx' :class="statusMeta(task.status).icon"></i></span>
                <div class="mc-status-copy">
                    <p class="mc-status-label" x-text="task.status_label"></p>
                    <template x-for="line in [tickerLine(task)]" :key="task.id + '-' + tickerTick">
                        <span class="mc-ticker mc-shimmer" x-text="line"></span>
                    </template>
                    <p class="mc-countdown" x-show="countdown(task)" x-text="countdown(task)"></p>
                </div>
            </div>

            <div class="mc-controls" x-show="isExecutor">
                <template x-for="s in statuses" :key="s">
                    <button type="button" class="mc-ctl" :class="task.status === s && 'mc-ctl-on'"
                        :title="statusMeta(s).label" @click="setStatus(task, s)">
                        <i class='bx' :class="statusMeta(s).icon"></i>
                    </button>
                </template>
            </div>

            <div class="mc-actions">
                <button type="button" class="mc-act" x-show="!isExecutor && task.editable" @click="openCreate(task)">
                    <i class='bx bx-edit-alt'></i> Edit
                </button>
                <button type="button" class="mc-act mc-act--danger" x-show="!isExecutor && task.editable"
                    @click="removeTask(task)">
                    <i class='bx bx-trash'></i>
                </button>
                <button type="button" class="mc-act" x-show="!isExecutor" :disabled="task.reminder_wait_seconds > 0"
                    @click="remind(task)">
                    <i class='bx bx-bell'></i>
                    <span x-text="task.reminder_wait_seconds > 0 ? remindWait(task) : 'Remind'"></span>
                </button>
                <button type="button" class="mc-act" style="margin-left:auto" @click="drawerId = task.id">
                    <i class='bx bx-message-square-dots'></i>
                    <span x-text="commentCount(task)"></span>
                </button>
            </div>
        </article>
    </template>

    <p class="mc-empty" x-show="visibleTasks.length === 0"
        x-text="tab === 'active' ? 'No active missions — launch one.' : 'Nothing delivered yet.'"></p>
</div>
