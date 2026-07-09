<div class="mc-overlay" x-show="overlay" x-transition.opacity>
    <template x-if="overlay === 'transmit'">
        <div>
            <div class="mc-orbit" style="margin:0 auto"><i class='bx bx-paper-plane'></i></div>
            <h2 class="mc-overlay-title">Transmitting your task to Rajin…</h2>
            <template x-for="line in [dispatchLine()]" :key="'o' + overlayIdx">
                <span class="mc-ticker mc-shimmer" x-text="line"></span>
            </template>
        </div>
    </template>

    <template x-if="overlay === 'ack'">
        <div>
            <div class="mc-radar-stage" style="margin:0 auto">
                <span class="mc-radar-ring"></span>
                <span class="mc-radar-ring mc-radar-ring--late"></span>
                <i class='bx bx-radar'></i>
            </div>
            <h2 class="mc-overlay-title">Awaiting Review</h2>
            <span class="mc-ticker mc-shimmer">Delivered to Rajin — awaiting acknowledgment</span>
        </div>
    </template>
</div>
