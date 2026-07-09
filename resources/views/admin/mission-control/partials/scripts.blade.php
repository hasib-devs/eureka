<script>
    // Per-status meta: label, stepper CTA, and the AI-console ticker lines.
    // One place to tune all product copy.
    const MC_STATUS_META = {
        awaiting_review: {
            label: 'In Queue',
            cta: null,
            lines: ['Waiting for pickup…', 'Signal sent to the worker…', 'Queued — next in line'],
        },
        under_review: {
            label: 'Reading Brief',
            cta: { label: 'Start Reading' },
            lines: ['Parsing requirements…', 'Inspecting reference…', 'Scoping the brief…'],
        },
        approved: {
            label: 'Scheduled',
            cta: { label: 'Approve' },
            lines: ['Slot reserved', 'Auto-start scheduled…'],
        },
        in_progress: {
            label: 'Working',
            cta: { label: 'Start Now' },
            lines: ['Wiring things up…', 'Work in motion…', 'Building — steady progress', 'Assembling components…'],
        },
        delivered: {
            label: 'Shipped ✦',
            cta: { label: 'Mark Shipped' },
            lines: ['Shipped ✦', 'Delivered for your review'],
        },
    };

    const MC_DISPATCH_LINES = ['Encrypting payload…', 'Opening a channel…', 'Transmitting to the worker…'];
    const MC_BRAND_LINES = ['teammate online', 'all systems steady', 'live sync active'];
    const MC_STAGE_PCT = { awaiting_review: 8, under_review: 32, approved: 55, in_progress: 68, delivered: 100 };

    function missionControl(boot) {
        return {
            tasks: boot.tasks,
            isExecutor: boot.isExecutor,
            statuses: boot.statuses,
            urls: boot.urls,

            tab: 'active',
            drawerId: null,
            lightbox: null,
            overlay: null, // null | 'transmit' | 'ack'
            overlayIdx: 0,
            overlayTimer: null,
            createOpen: false,
            editingId: null,
            sending: false,
            commentBody: '',
            form: { title: '', description: '', priority: 'normal', due_date: '', image: null, preview: null },
            errors: {},
            tickerTick: 0,
            clock: '',
            nowMs: Date.now(),

            init() {
                this.clock = this.timeNow();
                setInterval(() => this.tickerTick++, 3200);
                setInterval(() => this.tickSeconds(), 1000);
                setInterval(() => this.refresh(), 12000);
            },

            timeNow() {
                return new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            },

            csrf() {
                return document.querySelector('meta[name=csrf-token]').content;
            },

            headers(json = false) {
                const h = { 'X-CSRF-TOKEN': this.csrf(), Accept: 'application/json' };
                if (json) h['Content-Type'] = 'application/json';
                return h;
            },

            get activeTasks() { return this.tasks.filter(t => t.status !== 'delivered'); },
            get deliveredTasks() { return this.tasks.filter(t => t.status === 'delivered'); },
            get visibleTasks() { return this.tab === 'active' ? this.activeTasks : this.deliveredTasks; },
            get drawerTask() { return this.tasks.find(t => t.id === this.drawerId) || null; },

            // The task the worker "is on" right now: prefer working, then the
            // earliest pipeline states, newest first within a state.
            get heroTask() {
                for (const s of ['in_progress', 'under_review', 'approved', 'awaiting_review']) {
                    const t = this.tasks.find(x => x.status === s);
                    if (t) return t;
                }
                return null;
            },

            statusMeta(status) { return MC_STATUS_META[status] || MC_STATUS_META.awaiting_review; },

            tickerLine(task) {
                const lines = this.statusMeta(task.status).lines;
                return lines[this.tickerTick % lines.length];
            },

            tickerClass() { return this.tickerTick % 2 ? 'mc-ticker--b' : 'mc-ticker--a'; },

            brandLine() { return MC_BRAND_LINES[this.tickerTick % MC_BRAND_LINES.length]; },

            dispatchLine() { return MC_DISPATCH_LINES[this.overlayIdx % MC_DISPATCH_LINES.length]; },

            commentCount(task) { return task.activities.filter(a => a.type === 'comment').length; },

            // Work-log view: chronological, with consecutive duplicate status
            // events collapsed so accidental double submissions never clutter it.
            displayActivities(task) {
                const sorted = [...task.activities].sort((a, b) => a.id - b.id);
                return sorted.filter((a, i) => {
                    if (a.type !== 'status') return true;
                    const prev = sorted[i - 1];
                    return !(prev && prev.type === 'status' && prev.status === a.status);
                });
            },

            stageIndex(status) { return Math.max(0, this.statuses.indexOf(status)); },

            stageState(task, status) {
                const current = this.stageIndex(task.status);
                const mine = this.stageIndex(status);
                if (mine < current || task.status === 'delivered') return 'done';
                if (mine === current) return 'now';
                return 'next';
            },

            pipeProgress(task) {
                return (this.stageIndex(task.status) / (this.statuses.length - 1)) * 100 + '%';
            },

            statusPercent(task) {
                if (task.status === 'in_progress') {
                    const started = task.working_since ? Date.parse(task.working_since) : null;
                    const mins = started ? Math.max(0, (this.nowMs - started) / 60000) : 0;
                    return Math.min(96, 60 + Math.floor(mins));
                }
                return MC_STAGE_PCT[task.status] ?? 8;
            },

            heroKick(task) {
                return { in_progress: 'Live — working now', under_review: 'Live — reading your brief', approved: 'Scheduled — about to start', awaiting_review: 'In queue — next up' }[task.status] || 'Live';
            },

            heroWith(task) {
                if (task.status === 'in_progress') {
                    const el = this.heroElapsed(task);
                    return 'Wedevs AI is on it' + (el ? ' — running ' + el : '');
                }
                return { under_review: 'Wedevs AI is reading the brief', approved: 'Wedevs AI reserved a slot for this', awaiting_review: 'Wedevs AI will pick this up next' }[task.status] || 'Wedevs AI';
            },

            heroLines(task) {
                const tick = { kind: 'arrow', type: true, text: this.tickerLine(task) };
                if (task.status === 'in_progress') {
                    return [
                        { kind: 'ok', text: 'Brief parsed — requirements identified' },
                        { kind: 'ok', text: task.image_url ? 'Reference image analyzed' : 'Workspace prepared' },
                        tick,
                    ];
                }
                if (task.status === 'under_review') {
                    return [{ kind: 'ok', text: 'Task picked up' }, tick];
                }
                if (task.status === 'approved') {
                    return [
                        { kind: 'ok', text: 'Approved — slot reserved' },
                        { kind: 'arrow', text: (this.countdown(task) || 'starting soon…') },
                    ];
                }
                return [tick];
            },

            heroElapsed(task) {
                if (task.status !== 'in_progress' || !task.working_since) return null;
                let s = Math.max(0, Math.floor((this.nowMs - Date.parse(task.working_since)) / 1000));
                const h = Math.floor(s / 3600); s -= h * 3600;
                const m = Math.floor(s / 60); s -= m * 60;
                const pad = n => String(n).padStart(2, '0');
                return pad(h) + ':' + pad(m) + ':' + pad(s);
            },

            stationTasks(status) { return this.tasks.filter(t => t.status === status); },

            stationBusy(status) { return this.tasks.some(t => t.status === status); },

            nextStep(task) {
                const next = this.statuses[this.stageIndex(task.status) + 1];
                return next ? { status: next, ...MC_STATUS_META[next].cta } : null;
            },

            countdown(task) {
                if (task.status !== 'approved' || task.auto_start_seconds_left <= 0) return null;
                const s = task.auto_start_seconds_left;
                return 'T− ' + String(Math.floor(s / 60)).padStart(2, '0') + ':' + String(s % 60).padStart(2, '0');
            },

            remindWait(task) {
                return 'Wait ' + Math.ceil(task.reminder_wait_seconds / 60) + 'm';
            },

            // One heartbeat drives every live number: the wall clock, elapsed
            // timers, the approval countdown (optimistic flip at zero — the
            // next poll confirms) and the reminder cooldown.
            tickSeconds() {
                this.clock = this.timeNow();
                this.nowMs = Date.now();
                this.tasks.forEach(t => {
                    if (t.status === 'approved' && t.auto_start_seconds_left > 0) {
                        t.auto_start_seconds_left--;
                        if (t.auto_start_seconds_left === 0) {
                            t.status = 'in_progress';
                            t.status_label = MC_STATUS_META.in_progress.label;
                        }
                    }
                    if (t.reminder_wait_seconds > 0) t.reminder_wait_seconds--;
                });
            },

            // Patch the existing task objects in place instead of replacing
            // the array: Alpine keeps the same DOM nodes, so nothing is ever
            // re-created (or worse, duplicated) on refresh.
            syncTasks(list) {
                const seen = new Set();
                list.forEach(fresh => {
                    seen.add(fresh.id);
                    const mine = this.tasks.find(t => t.id === fresh.id);
                    if (mine) {
                        Object.assign(mine, fresh);
                    } else {
                        this.tasks.push(fresh);
                    }
                });
                for (let i = this.tasks.length - 1; i >= 0; i--) {
                    if (!seen.has(this.tasks[i].id)) this.tasks.splice(i, 1);
                }
                this.tasks.sort((a, b) => b.id - a.id);
            },

            async refresh() {
                try {
                    const res = await fetch(this.urls.feed, { headers: { Accept: 'application/json' } });
                    if (!res.ok) return;
                    const data = await res.json();
                    this.syncTasks(data.tasks);
                } catch (e) {
                    // Offline / navigating away — keep the last known state.
                }
            },

            openCreate(task = null) {
                this.errors = {};
                this.editingId = task ? task.id : null;
                this.form = task
                    ? { title: task.title, description: task.description, priority: task.priority, due_date: task.due_date || '', image: null, preview: task.image_url }
                    : { title: '', description: '', priority: 'normal', due_date: '', image: null, preview: null };
                this.createOpen = true;
            },

            pickImage(event) {
                const file = event.target.files[0];
                if (!file) return;
                this.form.image = file;
                this.form.preview = URL.createObjectURL(file);
            },

            async submitTask() {
                this.sending = true;
                this.errors = {};

                const fd = new FormData();
                fd.append('title', this.form.title);
                fd.append('description', this.form.description);
                fd.append('priority', this.form.priority);
                if (this.form.due_date) fd.append('due_date', this.form.due_date);
                if (this.form.image) fd.append('image', this.form.image);

                let url = this.urls.store;
                if (this.editingId) {
                    url = this.urls.base + '/' + this.editingId;
                    fd.append('_method', 'PUT');
                }

                try {
                    const res = await fetch(url, { method: 'POST', body: fd, headers: this.headers() });

                    if (res.status === 422) {
                        const data = await res.json();
                        this.errors = data.errors || { title: [data.message] };
                        return;
                    }
                    if (!res.ok) return;

                    const data = await res.json();

                    if (this.editingId) {
                        this.patchTask(data.task);
                        this.createOpen = false;
                    } else {
                        this.createOpen = false;
                        this.runDispatchTheatre(data.task);
                    }
                } finally {
                    this.sending = false;
                }
            },

            patchTask(fresh) {
                const mine = this.tasks.find(t => t.id === fresh.id);
                if (mine) Object.assign(mine, fresh);
                else this.tasks.unshift(fresh);
            },

            // The cinematic part: the task is already saved — this is pure
            // presentation. Phase 1 "transmit" (~2.2s), phase 2 "queued"
            // (~2.2s), then the new card drops onto the board.
            runDispatchTheatre(task) {
                this.overlay = 'transmit';
                this.overlayIdx = 0;
                this.overlayTimer = setInterval(() => this.overlayIdx++, 700);

                setTimeout(() => { this.overlay = 'ack'; }, 2200);
                setTimeout(() => {
                    clearInterval(this.overlayTimer);
                    this.overlay = null;
                    this.tab = 'active';
                    this.patchTask(task);
                }, 4400);
            },

            async setStatus(task, status) {
                if (!this.isExecutor || task.status === status) return;
                const res = await fetch(this.urls.base + '/' + task.id + '/status', {
                    method: 'PATCH',
                    headers: this.headers(true),
                    body: JSON.stringify({ status }),
                });
                if (!res.ok) return;
                const data = await res.json();
                this.patchTask(data.task);
            },

            advance(task) {
                const next = this.nextStep(task);
                if (next) this.setStatus(task, next.status);
            },

            async removeTask(task) {
                if (!confirm('Abort this task permanently?')) return;
                const res = await fetch(this.urls.base + '/' + task.id, {
                    method: 'DELETE',
                    headers: this.headers(),
                });
                if (!res.ok) return;
                this.tasks = this.tasks.filter(t => t.id !== task.id);
                if (this.drawerId === task.id) this.drawerId = null;
            },

            async sendComment() {
                const body = this.commentBody.trim();
                if (!body || !this.drawerTask) return;
                const res = await fetch(this.urls.base + '/' + this.drawerTask.id + '/comment', {
                    method: 'POST',
                    headers: this.headers(true),
                    body: JSON.stringify({ body }),
                });
                if (!res.ok) return;
                const data = await res.json();
                this.drawerTask.activities.push(data.activity);
                this.commentBody = '';
            },

            async remind(task) {
                const res = await fetch(this.urls.base + '/' + task.id + '/remind', {
                    method: 'POST',
                    headers: this.headers(true),
                });
                const data = await res.json().catch(() => ({}));
                if (res.ok || res.status === 429) {
                    task.reminder_wait_seconds = data.wait_seconds || 1800;
                }
            },
        };
    }
</script>
