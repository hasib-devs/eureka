<div class="mc-drawer-scrim" x-show="drawerTask" x-transition.opacity @click="drawerId = null"></div>

<aside class="mc-drawer" x-show="drawerTask"
    x-transition:enter="mc-drawer-anim" x-transition:enter-start="mc-drawer-closed"
    x-transition:leave="mc-drawer-anim" x-transition:leave-end="mc-drawer-closed">
    <template x-if="drawerTask">
        <div>
            <div class="mc-drawer-head">
                <h2 x-text="drawerTask.title"></h2>
                <button type="button" class="mc-x" @click="drawerId = null"><i class='bx bx-x'></i></button>
            </div>

            <div class="mc-status" :class="'mc-status--' + drawerTask.status" style="margin-bottom:14px">
                <span class="mc-status-icon"><i class='bx' :class="statusMeta(drawerTask.status).icon"></i></span>
                <div class="mc-status-copy">
                    <p class="mc-status-label" x-text="drawerTask.status_label"></p>
                    <template x-for="line in [tickerLine(drawerTask)]" :key="'d' + drawerTask.id + '-' + tickerTick">
                        <span class="mc-ticker mc-shimmer" x-text="line"></span>
                    </template>
                    <p class="mc-countdown" x-show="countdown(drawerTask)" x-text="countdown(drawerTask)"></p>
                </div>
            </div>

            <p class="mc-drawer-desc" x-text="drawerTask.description"></p>

            <template x-if="drawerTask.image_url">
                <img class="mc-drawer-img" :src="drawerTask.image_url" alt="Reference"
                    @click="lightbox = drawerTask.image_url">
            </template>

            <div class="mc-timeline">
                <template x-for="a in drawerTask.activities" :key="a.id">
                    <div class="mc-event" :class="a.type === 'comment' && 'mc-event--comment'">
                        <template x-if="a.type === 'status'">
                            <p>
                                <b x-text="a.user"></b> → <b x-text="a.status_label"></b>
                                <span class="mc-ev-time" x-text="a.time_human"></span>
                            </p>
                        </template>
                        <template x-if="a.type === 'comment'">
                            <div>
                                <p><b x-text="a.user"></b> <span class="mc-ev-time" x-text="a.time_human"></span></p>
                                <div class="mc-bubble" x-text="a.body"></div>
                            </div>
                        </template>
                    </div>
                </template>
            </div>

            <form class="mc-comment-form" @submit.prevent="sendComment()">
                <input class="mc-input" type="text" x-model="commentBody" maxlength="2000"
                    :placeholder="isExecutor ? 'Reply to the team…' : 'Write a note to Rajin…'">
                <button type="submit" class="mc-btn-primary" style="flex:0 0 auto"
                    :disabled="!commentBody.trim()"><i class='bx bx-send'></i></button>
            </form>
        </div>
    </template>
</aside>
