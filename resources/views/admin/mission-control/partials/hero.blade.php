{{-- Live hero — the task the worker is on right now (or an idle state). --}}
<div class="mc-live" x-show="heroTask">
    <template x-if="heroTask">
        <div class="mc-live-main">
            <div class="mc-live-kick">
                <span class="mc-live-dot" style="width:7px;height:7px"></span>
                <span class="k" x-text="heroKick(heroTask)"></span>
                <span class="mc-chip-urgent" x-show="heroTask.priority === 'urgent'">Urgent</span>
            </div>
            <h3 class="mc-live-title" x-text="heroTask.title" @click="drawerId = heroTask.id"></h3>
            <div class="mc-live-with">
                @include('admin.mission-control.partials.worker', ['size' => 26, 'busy' => true])
                <span x-text="heroWith(heroTask)"></span>
            </div>
            <div class="mc-console">
                <template x-for="(line, i) in heroLines(heroTask)" :key="i">
                    <div>
                        <span :class="line.kind" x-text="line.kind === 'ok' ? '✓' : '▸'"></span>
                        <span x-show="!line.type" x-text="' ' + line.text"></span>
                        <span x-show="line.type" class="mc-typeline" x-text="line.text"></span>
                    </div>
                </template>
            </div>
            <div class="mc-live-actions">
                <button type="button" class="mc-act" x-show="!isExecutor" :disabled="heroTask.reminder_wait_seconds > 0"
                    @click="remind(heroTask)">
                    <span x-text="heroTask.reminder_wait_seconds > 0 ? remindWait(heroTask) : 'Nudge'"></span>
                </button>
                <button type="button" class="mc-act" @click="drawerId = heroTask.id">
                    Open log · <span x-text="heroTask.activities.length"></span>
                </button>
                <span class="mc-act-spacer"></span>
                <button type="button" class="mc-cta" x-show="isExecutor && nextStep(heroTask)" @click="advance(heroTask)">
                    <span x-text="nextStep(heroTask)?.label"></span>
                </button>
            </div>
        </div>
    </template>
    <template x-if="heroTask">
        <div class="mc-live-side">
            <div class="mc-ring" :style="'--pct:' + statusPercent(heroTask)">
                <b><span x-text="statusPercent(heroTask)"></span><small>%</small></b>
            </div>
            <div class="mc-side-rows">
                <div class="mc-side-row" x-show="heroElapsed(heroTask)"><span>elapsed</span><b class="sol" x-text="heroElapsed(heroTask)"></b></div>
                <div class="mc-side-row"><span>state</span><b x-text="heroTask.status_label.toLowerCase()"></b></div>
                <div class="mc-side-row" x-show="heroTask.due_date"><span>deadline</span><b x-text="heroTask.due_date"></b></div>
                <div class="mc-side-row" x-show="countdown(heroTask)"><span>auto-start</span><b class="sol" x-text="countdown(heroTask)"></b></div>
            </div>
        </div>
    </template>
</div>

<div class="mc-live" x-show="!heroTask">
    <div class="mc-idle" style="grid-column:1/-1">
        @include('admin.mission-control.partials.worker', ['size' => 56])
        <div>
            <p class="t">Worker idle — no active mission</p>
            <p><span class="mc-ticker mc-shimmer" :class="tickerClass()" x-text="'assign a task and watch it go to work…'"></span></p>
        </div>
    </div>
</div>
