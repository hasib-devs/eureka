@extends('layouts.admin.app')

@section('title', 'Color Information')

@section('content')

    <section class="mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Color Information</h1>
                <p class="mt-1 text-sm text-slate-500">Details of the "{{ $color->name }}" color</p>
            </div>
            <div class="flex items-center gap-3">
                <x-ui.button variant="outline" :href="routeHelper('color/' . $color->id . '/edit')">
                    <i class="fas fa-edit text-xs"></i> Edit
                </x-ui.button>
                <x-ui.button variant="outline" :href="routeHelper('color')">
                    <i class="fas fa-long-arrow-alt-left text-xs"></i> Back to List
                </x-ui.button>
                <ol class="flex items-center gap-1 text-sm text-slate-400">
                    <li><a href="{{ routeHelper('dashboard') }}" class="transition-colors hover:text-primary">Home</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li><a href="{{ routeHelper('color') }}" class="transition-colors hover:text-primary">Colors</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li class="font-medium text-slate-600">Show</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="mb-6">
        <div class="mx-auto w-full max-w-3xl">
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center gap-3 border-b border-slate-200 px-4 py-4">
                    <span class="h-9 w-9 shrink-0 rounded-lg ring-1 ring-inset ring-slate-200" style="background: {{ $color->code }}"></span>
                    <div>
                        <h2 class="text-base font-semibold text-slate-900">{{ $color->name }}</h2>
                        <p class="text-xs uppercase text-slate-500">{{ $color->code }}</p>
                    </div>
                </div>

                <div class="p-5">
                    <dl class="divide-y divide-slate-100">
                        <div class="flex justify-between gap-4 py-2 text-sm">
                            <dt class="text-slate-500">Color Name</dt>
                            <dd class="text-right font-medium text-slate-800">{{ $color->name }}</dd>
                        </div>
                        <div class="flex justify-between gap-4 py-2 text-sm">
                            <dt class="text-slate-500">Color Code</dt>
                            <dd class="text-right font-medium uppercase tabular-nums text-slate-800">{{ $color->code }}</dd>
                        </div>
                        <div class="flex items-center justify-between gap-4 py-2 text-sm">
                            <dt class="text-slate-500">Color Overview</dt>
                            <dd class="flex justify-end">
                                <span class="inline-block h-10 w-32 rounded-lg shadow-sm ring-1 ring-inset ring-slate-200" style="background: {{ $color->code }}"></span>
                            </dd>
                        </div>
                        <div class="flex justify-between gap-4 py-2 text-sm">
                            <dt class="text-slate-500">Description</dt>
                            <dd class="text-right font-medium text-slate-800">{{ $color->description }}</dd>
                        </div>
                        <div class="flex items-center justify-between gap-4 py-2 text-sm">
                            <dt class="text-slate-500">Status</dt>
                            <dd class="text-right">
                                @if ($color->status)
                                    <x-ui.badge variant="success" dot>Active</x-ui.badge>
                                @else
                                    <x-ui.badge variant="danger" dot>Disable</x-ui.badge>
                                @endif
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </section>

@endsection
