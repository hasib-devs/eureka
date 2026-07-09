<div class="mc-overlay" x-show="overlay" x-transition.opacity>
    <div x-show="overlay === 'transmit'">
        @include('admin.mission-control.partials.worker', ['size' => 84, 'busy' => true])
        <h2 class="mc-overlay-title">Sending your task to Wedevs AI…</h2>
        <span class="mc-ticker mc-shimmer" :class="tickerClass()" x-text="dispatchLine()"></span>
    </div>

    <div x-show="overlay === 'ack'">
        @include('admin.mission-control.partials.worker', ['size' => 84])
        <h2 class="mc-overlay-title">In Queue</h2>
        <span class="mc-ticker mc-shimmer mc-ticker--a">Received — the worker will pick this up shortly</span>
    </div>
</div>
