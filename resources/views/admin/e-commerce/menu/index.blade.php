@extends('layouts.admin.app')

@section('title', 'Header Menu')

@php
    $inp = 'w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary';
    $lbl = 'mb-1.5 block text-[11px] font-semibold uppercase tracking-wide text-slate-500';
@endphp

@section('content')
    <div class="mx-auto max-w-4xl space-y-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Header Menu</h1>
            <p class="mt-1 text-sm text-slate-500">Manage the desktop header navigation links. Lower “order” shows first. The Categories mega-menu and account links stay automatic.</p>
        </div>

        @if ($errors->any())
            <div class="rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                <ul class="list-inside list-disc">
                    @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        {{-- Add new link --}}
        <form method="POST" action="{{ route('admin.menu.store') }}" class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
            @csrf
            <h3 class="mb-3 text-base font-semibold text-slate-900">Add Link</h3>
            <div class="flex flex-wrap items-end gap-3">
                <div class="min-w-[140px] flex-1">
                    <label class="{{ $lbl }}">Label</label>
                    <input type="text" name="label" class="{{ $inp }}" placeholder="Shop" required>
                </div>
                <div class="min-w-[200px] flex-[2]">
                    <label class="{{ $lbl }}">URL</label>
                    <input type="text" name="url" class="{{ $inp }}" placeholder="/product" required>
                </div>
                <div class="w-20">
                    <label class="{{ $lbl }}">Order</label>
                    <input type="number" name="sort_order" class="{{ $inp }}" value="0">
                </div>
                <label class="flex items-center gap-1.5 pb-2 text-xs text-slate-600">
                    <input type="checkbox" name="new_tab" value="1"> New tab
                </label>
                <x-ui.button variant="primary" type="submit"><i class="fas fa-plus"></i> Add</x-ui.button>
            </div>
        </form>

        {{-- Existing links --}}
        <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 px-4 py-3">
                <h3 class="text-base font-semibold text-slate-900">Links ({{ $items->count() }})</h3>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse ($items as $item)
                    <div class="flex flex-wrap items-end gap-3 px-4 py-3">
                        <form method="POST" action="{{ route('admin.menu.update', $item->id) }}" class="flex flex-1 flex-wrap items-end gap-3">
                            @csrf
                            @method('PUT')
                            <div class="min-w-[130px] flex-1">
                                <label class="{{ $lbl }}">Label</label>
                                <input type="text" name="label" class="{{ $inp }}" value="{{ $item->label }}" required>
                            </div>
                            <div class="min-w-[180px] flex-[2]">
                                <label class="{{ $lbl }}">URL</label>
                                <input type="text" name="url" class="{{ $inp }}" value="{{ $item->url }}" required>
                            </div>
                            <div class="w-20">
                                <label class="{{ $lbl }}">Order</label>
                                <input type="number" name="sort_order" class="{{ $inp }}" value="{{ $item->sort_order }}">
                            </div>
                            <div class="flex flex-col gap-1 pb-1">
                                <label class="flex items-center gap-1.5 text-xs text-slate-600">
                                    <input type="checkbox" name="status" value="1" @checked($item->status)> Active
                                </label>
                                <label class="flex items-center gap-1.5 text-xs text-slate-600">
                                    <input type="checkbox" name="new_tab" value="1" @checked($item->new_tab)> New tab
                                </label>
                            </div>
                            <x-ui.button variant="primary" type="submit"><i class="fas fa-save"></i> Save</x-ui.button>
                        </form>
                        <form method="POST" action="{{ route('admin.menu.destroy', $item->id) }}" onsubmit="return confirm('Delete this link?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="rounded-lg border border-rose-200 bg-rose-50 px-3 py-2 text-sm text-rose-600 hover:bg-rose-100">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                @empty
                    <p class="px-4 py-6 text-center text-sm text-slate-500">No menu links yet — add one above. (The storefront falls back to default links until you do.)</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
