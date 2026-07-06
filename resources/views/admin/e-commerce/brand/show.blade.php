@extends('layouts.admin.app')

@section('title', 'Brand Information')

@section('content')

    <section class="mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Brand Information</h1>
                <p class="mt-1 text-sm text-slate-500">Details of the selected brand</p>
            </div>
            <div class="flex items-center gap-3">
                <x-ui.button variant="outline" :href="routeHelper('brand/' . $brand->id . '/edit')">
                    <i class="fas fa-edit text-xs"></i> Edit
                </x-ui.button>
                <x-ui.button variant="outline" :href="routeHelper('brand')">
                    <i class="fas fa-long-arrow-alt-left text-xs"></i> Back to List
                </x-ui.button>
                <ol class="flex items-center gap-1 text-sm text-slate-400">
                    <li><a href="{{ routeHelper('dashboard') }}" class="transition-colors hover:text-primary">Home</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li><a href="{{ routeHelper('brand') }}" class="transition-colors hover:text-primary">Brands</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li class="font-medium text-slate-600">Show</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="mb-6">
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
            {{-- Cover photo aside --}}
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center gap-3 border-b border-slate-200 px-4 py-4">
                    <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                        <i class="fas fa-image"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-semibold text-slate-900">Cover photo</h3>
                        <p class="text-xs text-slate-500">Brand artwork</p>
                    </div>
                </div>
                <div class="grid place-items-center p-5">
                    @if ($brand->cover_photo == 'default.png')
                        <img src="https://via.placeholder.com/150" alt="Cover Photo" class="h-40 w-40 rounded-xl object-cover ring-1 ring-slate-200">
                    @else
                        <img src="/uploads/brand/{{ $brand->cover_photo }}" alt="Cover Photo" class="h-40 w-40 rounded-xl object-cover ring-1 ring-slate-200">
                    @endif
                </div>
            </div>

            {{-- Details --}}
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm lg:col-span-2">
                <div class="flex items-center gap-3 border-b border-slate-200 px-4 py-4">
                    <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                        <i class="fas fa-copyright"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-semibold text-slate-900">{{ $brand->name }}</h3>
                        <p class="text-xs text-slate-500">Brand details and visibility</p>
                    </div>
                </div>
                <dl class="divide-y divide-slate-100 px-5 py-2">
                    <div class="flex justify-between gap-4 py-2 text-sm">
                        <dt class="text-slate-500">Name</dt>
                        <dd class="text-right font-medium text-slate-800">{{ $brand->name }}</dd>
                    </div>
                    <div class="flex justify-between gap-4 py-2 text-sm">
                        <dt class="text-slate-500">Description</dt>
                        <dd class="text-right font-medium text-slate-800">{{ $brand->description }}</dd>
                    </div>
                    <div class="flex justify-between gap-4 py-2 text-sm">
                        <dt class="text-slate-500">Status</dt>
                        <dd class="text-right">
                            @if ($brand->status)
                                <x-ui.badge variant="success" dot>Active</x-ui.badge>
                            @else
                                <x-ui.badge variant="danger" dot>Disable</x-ui.badge>
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </section>

@endsection
