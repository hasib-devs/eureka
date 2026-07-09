@extends('layouts.admin.app')

@section('title', 'Mission Control')

@push('css')
    @include('admin.mission-control.partials.styles')
@endpush

@section('content')
    <div class="mc-shell" x-data="missionControl(@js($boot))" x-cloak>
        <div class="mc-grid-bg" aria-hidden="true"></div>

        <header class="mc-header">
            <div>
                <h1 class="mc-title"><span class="mc-title-icon"><i class='bx bx-radar'></i></span> MISSION CONTROL</h1>
                <p class="mc-sub">
                    <span class="mc-live-dot"></span> SYSTEMS ONLINE
                    <template x-for="line in [headerLine()]" :key="'h' + tickerTick">
                        <span class="mc-ticker mc-shimmer" x-text="line"></span>
                    </template>
                </p>
            </div>
            <button type="button" class="mc-launch" x-show="!isExecutor" @click="openCreate()">
                <i class='bx bx-rocket'></i> New Task
            </button>
        </header>

        @include('admin.mission-control.partials.create-panel')
        @include('admin.mission-control.partials.board')
        @include('admin.mission-control.partials.drawer')
        @include('admin.mission-control.partials.overlay')

        <div class="mc-lightbox" x-show="lightbox" x-transition.opacity @click="lightbox = null">
            <img :src="lightbox" alt="Reference image">
        </div>
    </div>
@endsection

@push('js')
    @include('admin.mission-control.partials.scripts')
@endpush
