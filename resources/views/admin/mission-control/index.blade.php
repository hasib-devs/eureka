@extends('layouts.admin.app')

@section('title', 'Mission Control')

@push('css')
    @include('admin.mission-control.partials.styles')
@endpush

@section('content')
    <div class="mc-shell" x-data="missionControl(@js($boot))" x-cloak>
        <div class="mc-grid-bg" aria-hidden="true"></div>
        <div class="mc-aurora" aria-hidden="true"></div>

        <div class="mc-topbar">
            <div class="mc-topbar-left">
                <span class="mc-emblem"><i class='bx bx-radar'></i></span>
                <div>
                    <h1 class="mc-title">Mission Control</h1>
                    <p class="mc-sub">
                        <span class="mc-live-dot"></span>
                        <span class="mc-sub-cap">Systems online</span>
                        <span class="mc-ticker mc-shimmer" :class="tickerClass()" x-text="headerLine()"></span>
                    </p>
                </div>
            </div>
            <div class="mc-topbar-right">
                <div class="mc-stats">
                    <span class="mc-stat"><i class="mc-stat-dot mc-stat-dot--cyan"></i> Active <b x-text="activeTasks.length"></b></span>
                    <span class="mc-stat"><i class="mc-stat-dot mc-stat-dot--violet"></i> In review <b x-text="reviewCount"></b></span>
                    <span class="mc-stat"><i class="mc-stat-dot mc-stat-dot--gold"></i> Delivered <b x-text="deliveredTasks.length"></b></span>
                </div>
                <span class="mc-clock" x-text="clock"></span>
                <button type="button" class="mc-launch" x-show="!isExecutor" @click="openCreate()">
                    <i class='bx bx-rocket'></i> New Task
                </button>
            </div>
        </div>

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
