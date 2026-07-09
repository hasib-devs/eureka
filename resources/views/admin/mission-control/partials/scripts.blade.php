<script>
    // Per-status micro-copy for the AI-console ticker. One place to tune all copy.
    const MC_STATUS_META = {
        awaiting_review: {
            label: 'Awaiting Review',
            icon: 'bx-radar',
            lines: ['Signal delivered', "Pinging Rajin's console…", 'Awaiting acknowledgment…'],
        },
        under_review: {
            label: 'Under Review',
            icon: 'bx-search-alt',
            lines: ['Rajin is examining the brief…', 'Scanning reference material…', 'Assessing scope…'],
        },
        approved: {
            label: 'Approved',
            icon: 'bx-badge-check',
            lines: ['Green light received', 'Scheduling execution window…'],
        },
        in_progress: {
            label: 'In Progress',
            icon: 'bx-cog',
            lines: ['Rajin is on it…', 'Work in motion…', 'Progress compiling…'],
        },
        delivered: {
            label: 'Delivered',
            icon: 'bx-check-double',
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

            init() {
                setInterval(() => this.tickerTick++, 3200);
                setInterval(() => this.tickSeconds(), 1000);
                setInterval(() => this.refresh(), 12000);
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

            statusMeta(status) { return MC_STATUS_META[status] || MC_STATUS_META.awaiting_review; },

            tickerLine(task) {
                const lines = this.statusMeta(task.status).lines;
                return lines[this.tickerTick % lines.length];
            },

            headerLine() { return MC_HEADER_LINES[this.tickerTick % MC_HEADER_LINES.length]; },

            dispatchLine() { return MC_DISPATCH_LINES[this.overlayIdx % MC_DISPATCH_LINES.length]; },

            commentCount(task) { return task.activities.filter(a => a.type === 'comment').length; },

            countdown(task) {
                if (task.status !== 'approved' || task.auto_start_seconds_left <= 0) return null;
                const s = task.auto_start_seconds_left;
                return 'T− auto-start ' + String(Math.floor(s / 60)).padStart(2, '0') + ':' + String(s % 60).padStart(2, '0');
            },

            remindWait(task) {
                const m = Math.ceil(task.reminder_wait_seconds / 60);
                return 'Wait ' + m + 'm';
            },

            // One heartbeat drives every live number: the approval countdown
            // (optimistic flip at zero — the next poll confirms) and the
            // reminder cooldown.
            tickSeconds() {
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

            async refresh() {
                try {
                    const res = await fetch(this.urls.feed, { headers: { Accept: 'application/json' } });
                    if (!res.ok) return;
                    const data = await res.json();
                    this.tasks = data.tasks;
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
                        this.tasks = this.tasks.map(t => (t.id === data.task.id ? data.task : t));
                        this.createOpen = false;
                    } else {
                        this.createOpen = false;
                        this.runDispatchTheatre(data.task);
                    }
                } finally {
                    this.sending = false;
                }
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
                    this.tasks.unshift(task);
                }, 4400);
            },

            async setStatus(task, status) {
                if (task.status === status) return;
                const res = await fetch(this.urls.base + '/' + task.id + '/status', {
                    method: 'PATCH',
                    headers: this.headers(true),
                    body: JSON.stringify({ status }),
                });
                if (!res.ok) return;
                const data = await res.json();
                this.tasks = this.tasks.map(t => (t.id === data.task.id ? data.task : t));
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
