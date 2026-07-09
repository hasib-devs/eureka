<div class="mc-overlay" x-show="overlay" x-transition.opacity>
    <div x-show="overlay === 'transmit'">
        <div class="mc-orbit"><i class='bx bx-paper-plane'></i></div>
        <h2 class="mc-overlay-title">Transmitting your task to Rajin…</h2>
        <span class="mc-ticker mc-shimmer" :class="overlayClass()" x-text="dispatchLine()"></span>
    </div>

    <div x-show="overlay === 'ack'">
        <div class="mc-radar-stage">
            <span class="mc-radar-ring"></span>
            <span class="mc-radar-ring mc-radar-ring--late"></span>
            <i class='bx bx-radar'></i>
        </div>
        <h2 class="mc-overlay-title">Awaiting Review</h2>
        <span class="mc-ticker mc-shimmer mc-ticker--a">Delivered to Rajin — awaiting acknowledgment</span>
    </div>
</div>
