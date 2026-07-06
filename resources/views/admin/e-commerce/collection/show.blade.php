@extends('layouts.admin.app')

@section('title', 'Collection Information')

@section('content')

    <section class="mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Collection Information</h1>
                <p class="mt-1 text-sm text-slate-500">Details of this collection</p>
            </div>
            <div class="flex items-center gap-3">
                <x-ui.button variant="outline" :href="routeHelper('collection/' . $collection->id . '/edit')">
                    <i class="fas fa-edit text-xs"></i> Edit
                </x-ui.button>
                <x-ui.button variant="outline" :href="routeHelper('collection')">
                    <i class="fas fa-long-arrow-alt-left text-xs"></i> Back to List
                </x-ui.button>
                <ol class="flex items-center gap-1 text-sm text-slate-400">
                    <li><a href="{{ routeHelper('dashboard') }}" class="transition-colors hover:text-primary">Home</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li><a href="{{ routeHelper('collection') }}" class="transition-colors hover:text-primary">Collections</a></li>
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
                    @if ($collection->cover_photo == 'default.png')
                        <img src="https://via.placeholder.com/150" alt="Cover Photo" class="h-12 w-12 rounded-lg object-cover ring-1 ring-slate-200">
                    @else
                        <img src="/uploads/collection/{{ $collection->cover_photo }}" alt="Cover Photo" class="h-12 w-12 rounded-lg object-cover ring-1 ring-slate-200">
                    @endif
                    <div>
                        <h2 class="text-base font-semibold text-slate-900">{{ $collection->name }}</h2>
                        <p class="text-xs text-slate-500">{{ $collection->slug }}</p>
                    </div>
                </div>

                <div class="p-5">
                    <dl class="divide-y divide-slate-100">
                        <div class="flex justify-between gap-4 py-2 text-sm">
                            <dt class="text-slate-500">Name</dt>
                            <dd class="text-right font-medium text-slate-800">{{ $collection->name }}</dd>
                        </div>
                        <div class="flex justify-between gap-4 py-2 text-sm">
                            <dt class="text-slate-500">Slug</dt>
                            <dd class="text-right font-medium text-slate-800">{{ $collection->slug }}</dd>
                        </div>
                        <div class="flex items-start justify-between gap-4 py-2 text-sm">
                            <dt class="text-slate-500">Categories</dt>
                            <dd class="flex flex-wrap justify-end gap-1.5">
                                @forelse ($collection->categories as $category)
                                    <x-ui.badge variant="primary">{{ $category->name }}</x-ui.badge>
                                @empty
                                    <span class="font-medium text-slate-800">—</span>
                                @endforelse
                            </dd>
                        </div>
                        <div class="flex items-center justify-between gap-4 py-2 text-sm">
                            <dt class="text-slate-500">Status</dt>
                            <dd class="text-right">
                                @if ($collection->status)
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
