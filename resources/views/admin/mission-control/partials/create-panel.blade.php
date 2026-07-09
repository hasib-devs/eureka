<div class="mc-modal-scrim" x-show="createOpen" x-transition.opacity @click.self="createOpen = false">
    <div class="mc-modal" @keydown.escape.window="createOpen = false">
        <h2 x-text="editingId ? 'Refine Task' : 'Launch a New Task'"></h2>
        <p class="mc-hint" x-text="editingId
            ? 'Adjust the brief — Rajin has not started on it yet.'
            : 'Brief Rajin. The task is transmitted the moment you hit launch.'"></p>

        <form @submit.prevent="submitTask()">
            <div class="mc-field">
                <label for="mc-title">Title</label>
                <input id="mc-title" class="mc-input" type="text" x-model="form.title" maxlength="150"
                    placeholder="e.g. Rebuild the homepage hero slider">
                <p class="mc-error" x-show="errors.title" x-text="errors.title && errors.title[0]"></p>
            </div>

            <div class="mc-field">
                <label for="mc-desc">Briefing</label>
                <textarea id="mc-desc" class="mc-textarea" x-model="form.description"
                    placeholder="Describe exactly what needs to happen…"></textarea>
                <p class="mc-error" x-show="errors.description" x-text="errors.description && errors.description[0]"></p>
            </div>

            <div class="mc-field">
                <label>Priority</label>
                <div class="mc-seg">
                    <template x-for="p in ['low', 'normal', 'urgent']" :key="p">
                        <button type="button" :class="form.priority === p && ('mc-seg-on--' + p)"
                            @click="form.priority = p" x-text="p"></button>
                    </template>
                </div>
            </div>

            <div class="mc-field">
                <label for="mc-due">Deadline <span style="opacity:.5">(optional)</span></label>
                <input id="mc-due" class="mc-input" type="date" x-model="form.due_date">
                <p class="mc-error" x-show="errors.due_date" x-text="errors.due_date && errors.due_date[0]"></p>
            </div>

            <div class="mc-field">
                <label>Reference image <span style="opacity:.5">(optional)</span></label>
                <div class="mc-drop" @click="$refs.mcFile.click()">
                    <template x-if="form.preview">
                        <img :src="form.preview" alt="Preview">
                    </template>
                    <template x-if="!form.preview">
                        <span><i class='bx bx-image-add'></i> Click to attach a reference</span>
                    </template>
                </div>
                <input type="file" x-ref="mcFile" accept="image/*" style="display:none" @change="pickImage($event)">
                <p class="mc-error" x-show="errors.image" x-text="errors.image && errors.image[0]"></p>
            </div>

            <div class="mc-modal-actions">
                <button type="button" class="mc-btn-ghost" @click="createOpen = false">Cancel</button>
                <button type="submit" class="mc-btn-primary" :disabled="sending">
                    <i class='bx' :class="editingId ? 'bx-save' : 'bx-send'"></i>
                    <span x-text="sending ? 'Working…' : (editingId ? 'Save Changes' : 'Launch Task')"></span>
                </button>
            </div>
        </form>
    </div>
</div>
