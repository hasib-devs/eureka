<script>
    // Per-status meta: label, icon, stepper caption, CTA for the executor,
    // and the AI-console ticker lines. One place to tune all copy.
    const MC_STATUS_META = {
        awaiting_review: {
            label: 'Awaiting Review',
            icon: 'bx-radar',
            step: 'Queue',
            cta: null,
            lines: ['Signal delivered', "Pinging Rajin's console…", 'Awaiting acknowledgment…'],
        },
        under_review: {
            label: 'Under Review',
            icon: 'bx-search-alt',
            step: 'Review',
            cta: { label: 'Start Review', icon: 'bx-search-alt' },
            lines: ['Rajin is examining the brief…', 'Scanning reference material…', 'Assessing scope…'],
        },
        approved: {
            label: 'Approved',
            icon: 'bx-badge-check',
            step: 'Approve',
            cta: { label: 'Approve', icon: 'bx-badge-check' },
            lines: ['Green light received', 'Scheduling execution window…'],
        },
        in_progress: {
            label: 'In Progress',
            icon: 'bx-cog',
            step: 'Build',
            cta: { label: 'Start Now', icon: 'bx-play' },
            lines: ['Rajin is on it…', 'Work in motion…', 'Progress compiling…'],
        },
        delivered: {
            label: 'Delivered',
            icon: 'bx-check-double',
            step: 'Done',
            cta: { label: 'Mark Delivered', icon: 'bx-check-double' },
            lines: ['Mission accomplished ✦', 'Delivered & verified'],
        },
    };

    const MC_DISPATCH_LINES = ['Encrypting payload…', 'Establishing secure channel…', 'Transmitting to Rajin…'];
    const MC_HEADER_LINES = ['All channels stable', 'Monitoring task pipeline…', 'Live sync active'];

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
            get reviewCount() { return this.tasks.filter(t => t.status === 'under_review').length; },
            get drawerTask() { return this.tasks.find(t => t.id === this.drawerId) || null; },

            statusMeta(status) { return MC_STATUS_META[status] || MC_STATUS_META.awaiting_review; },

            // The rotating micro-copy line. The element itself is never
            // recreated — a parity class re-triggers the entry animation.
            tickerLine(task) {
                const lines = this.statusMeta(task.status).lines;
                return lines[this.tickerTick % lines.length];
            },

            tickerClass() { return this.tickerTick % 2 ? 'mc-ticker--b' : 'mc-ticker--a'; },

            headerLine() { return MC_HEADER_LINES[this.tickerTick % MC_HEADER_LINES.length]; },

            dispatchLine() { return MC_DISPATCH_LINES[this.overlayIdx % MC_DISPATCH_LINES.length]; },

            overlayClass() { return this.overlayIdx % 2 ? 'mc-ticker--b' : 'mc-ticker--a'; },

            commentCount(task) { return task.activities.filter(a => a.type === 'comment').length; },

            // Mission-log view: chronological, with consecutive duplicate
            // status events collapsed so accidental double submissions never
            // clutter the console.
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

            nextStep(task) {
                const next = this.statuses[this.stageIndex(task.status) + 1];
                return next ? { status: next, ...MC_STATUS_META[next].cta } : null;
            },

            countdown(task) {
                if (task.status !== 'approved' || task.auto_start_seconds_left <= 0) return null;
                const s = task.auto_start_seconds_left;
                return 'T− ' + String(Math.floor(s / 60)).padStart(2, '0') + ':' + String(s % 60).padStart(2, '0') + ' to auto-start';
            },

            remindWait(task) {
                return 'Wait ' + Math.ceil(task.reminder_wait_seconds / 60) + 'm';
            },

            // One heartbeat drives every live number: the wall clock, the
            // approval countdown (optimistic flip at zero — the next poll
            // confirms) and the reminder cooldown.
            tickSeconds() {
                this.clock = this.timeNow();
                this.tasks.forEach(t => {
                    if (t.status === 'approved' && t.auto_start_seconds_left > 0) {
                        t.auto_start_seconds_left--;
                        if (t.auto_start_seconds_left === 0) {
                            t.status = 'in_progress';
                            t.status_label = 'In Progress';
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
            // presentation. Phase 1 "transmit" (~2.2s), phase 2 "ack" (~2.2s),
            // then the new card drops onto the board.
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
