{{-- Task Console: a centered command modal (replaces the old right drawer). --}}
<div class="mc-modal-scrim" x-show="drawerTask" x-transition.opacity @click.self="drawerId = null">
    <template x-if="drawerTask">
        <div class="mc-console" @keydown.escape.window="drawerId = null">
            <div class="mc-console-head" :class="'mc-status--' + drawerTask.status">
                <span class="mc-status-icon"><i class='bx' :class="statusMeta(drawerTask.status).icon"></i></span>
                <div class="mc-console-head-copy">
                    <h2 x-text="drawerTask.title"></h2>
                    <p>
                        <span class="mc-status-label" x-text="drawerTask.status_label"></span>
                        <span class="mc-ticker mc-shimmer" :class="tickerClass()" x-text="tickerLine(drawerTask)"></span>
                    </p>
                </div>
                <span class="mc-chip mc-chip--countdown" x-show="countdown(drawerTask)" x-text="countdown(drawerTask)"></span>
                <button type="button" class="mc-x" @click="drawerId = null"><i class='bx bx-x'></i></button>
            </div>

            <div class="mc-console-body">
                <div class="mc-console-brief">
                    <p class="mc-console-kicker">Briefing</p>
                    <p class="mc-console-desc" x-text="drawerTask.description"></p>

                    <div class="mc-thumb-wrap" x-show="drawerTask.image_url" @click="lightbox = drawerTask.image_url">
                        <img class="mc-thumb" :src="drawerTask.image_url || ''" alt="Reference" loading="lazy">
                        <span class="mc-thumb-zoom"><i class='bx bx-expand-alt'></i> View reference</span>
                    </div>

                    <div class="mc-meta">
                        <span class="mc-chip"><i class='bx bx-user'></i> <span x-text="drawerTask.created_by || 'Admin'"></span></span>
                        <span class="mc-chip"><i class='bx bx-time-five'></i> <span x-text="drawerTask.created_at_human"></span></span>
                        <span class="mc-chip" x-show="drawerTask.due_date"><i class='bx bx-calendar-event'></i> <span x-text="drawerTask.due_date"></span></span>
                        <span class="mc-chip mc-chip--overdue" x-show="drawerTask.overdue"><i class='bx bx-error'></i> Overdue</span>
                    </div>
                </div>

                <div class="mc-console-feed">
                    <p class="mc-console-kicker">Mission log</p>
                    <div class="mc-timeline">
                        <template x-for="a in displayActivities(drawerTask)" :key="a.id">
                            <div class="mc-event" :class="a.type === 'comment' && 'mc-event--comment'">
                                <p x-show="a.type === 'status'">
                                    <b x-text="a.user"></b> → <b x-text="a.status_label"></b>
                                    <span class="mc-ev-time" x-text="a.time_human"></span>
                                </p>
                                <div x-show="a.type === 'comment'">
                                    <p><b x-text="a.user"></b> <span class="mc-ev-time" x-text="a.time_human"></span></p>
                                    <div class="mc-bubble" x-text="a.body"></div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <form class="mc-comment-form" @submit.prevent="sendComment()">
                        <input class="mc-input" type="text" x-model="commentBody" maxlength="2000"
                            :placeholder="isExecutor ? 'Reply to the team…' : 'Write a note to Rajin…'">
                        <button type="submit" class="mc-btn-primary mc-btn-primary--sq"
                            :disabled="!commentBody.trim()"><i class='bx bx-send'></i></button>
                    </form>
                </div>
            </div>
        </div>
    </template>
</div>
