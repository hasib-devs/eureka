@extends('layouts.admin.app')

@section('title', 'Wedevs AI')

@push('css')
    @include('admin.mission-control.partials.styles')
@endpush

@section('content')
    <div class="mc-shell" x-data="missionControl(@js($boot))" x-cloak>
        <div class="mc-grid-bg" aria-hidden="true"></div>

        <div class="mc-topbar">
            <div class="mc-brand">
                @include('admin.mission-control.partials.worker', ['size' => 42])
                <div>
                    <div class="mc-word">WEDEVS <em>AI</em></div>
                    <div class="mc-brand-status">
                        <span class="mc-live-dot"></span>
                        <span x-text="brandLine()"></span>
                    </div>
                </div>
            </div>
            <div class="mc-topbar-right">
                <span class="mc-meta"><b x-text="activeTasks.length"></b> in motion &nbsp;·&nbsp; <b x-text="deliveredTasks.length"></b> shipped</span>
                <span class="mc-clock" x-text="clock"></span>
                <button type="button" class="mc-launch" x-show="!isExecutor" @click="openCreate()">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.4" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Assign Task
                </button>
            </div>
        </div>

        @include('admin.mission-control.partials.hero')
        @include('admin.mission-control.partials.metro')
        @include('admin.mission-control.partials.board')
        @include('admin.mission-control.partials.create-panel')
        @include('admin.mission-control.partials.drawer')
        @include('admin.mission-control.partials.overlay')

        <div class="mc-lightbox" x-show="lightbox" x-transition.opacity @click="lightbox = null">
            <img :src="lightbox || ''" alt="Reference image">
        </div>
    </div>
@endsection

@push('js')
    @include('admin.mission-control.partials.scripts')
@endpush
