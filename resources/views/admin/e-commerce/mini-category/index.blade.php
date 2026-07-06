@extends('layouts.admin.app')

@section('title', 'Mini Category List')

@section('content')

    <section class="mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Mini Categories</h1>
                <p class="mt-1 text-sm text-slate-500">Manage mini categories nested under sub categories</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.minicategory') }}"
                    class="inline-flex h-10 items-center justify-center gap-2 rounded-lg bg-primary px-4 text-sm font-medium text-white shadow-sm transition-all hover:bg-primary-600 hover:shadow focus:outline-none focus-visible:ring-2 focus-visible:ring-primary/50">
                    <i class="fas fa-plus text-xs"></i> Add Mini Category
                </a>
                <ol class="flex items-center gap-1 text-sm text-slate-400">
                    <li><a href="{{ routeHelper('dashboard') }}" class="transition-colors hover:text-primary">Home</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li class="font-medium text-slate-600">Mini Categories</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="mb-6">
        @if (!empty(Session::get('massage2')))
            <x-ui.alert variant="success" class="mb-4 text-center">
                {{ Session::get('massage2') }}
            </x-ui.alert>
        @endif

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-200 px-4 py-4">
                <h2 class="text-base font-semibold text-slate-900">All Mini Categories</h2>
                <span class="inline-flex items-center rounded-full bg-primary/10 px-2.5 py-1 text-xs font-semibold text-primary ring-1 ring-inset ring-primary/20">
                    {{ count($mini_categories) }} {{ Str::plural('mini category', count($mini_categories)) }}
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[860px] text-left text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50/80 text-[11px] font-semibold uppercase tracking-wider text-slate-400">
                            <th class="w-14 px-4 py-3.5">SL</th>
                            <th class="px-4 py-3.5">Mini Category</th>
                            <th class="px-4 py-3.5">Sub Category</th>
                            <th class="px-4 py-3.5">Status</th>
                            <th class="px-4 py-3.5 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($mini_categories as $key => $data)
                            <tr class="group transition-colors hover:bg-slate-50/80">
                                <td class="px-4 py-4 text-slate-500">{{ $loop->iteration }}</td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-3">
                                        @if ($data->cover_photo == 'default.png')
                                            <img src="https://via.placeholder.com/150" alt="Cover Photo" class="h-10 w-10 rounded-lg object-cover ring-1 ring-slate-200">
                                        @else
                                            <img src="/uploads/mini-category/{{ $data->cover_photo }}" alt="Cover Photo" class="h-10 w-10 rounded-lg object-cover ring-1 ring-slate-200">
                                        @endif
                                        <div>
                                            <p class="font-semibold text-slate-800">{{ $data->name }}</p>
                                            <p class="mt-0.5 text-slate-500">{{ $data->slug }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-slate-500">{{ $data->subCategory->name ?? '' }}</td>
                                <td class="px-4 py-4">
                                    @if ($data->status)
                                        <x-ui.badge variant="success" dot>Active</x-ui.badge>
                                    @else
                                        <x-ui.badge variant="danger" dot>Disable</x-ui.badge>
                                    @endif
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('miniCategory.product', ['slug' => $data->slug]) }}"
                                            title="View products"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 shadow-sm transition-all hover:-translate-y-px hover:border-primary hover:bg-primary-50 hover:text-primary hover:shadow">
                                            <i class="fas fa-box"></i>
                                        </a>
                                        <a href="{{ route('admin.minicategory.edit', ['edit' => $data->id]) }}"
                                            title="Edit mini category"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 shadow-sm transition-all hover:-translate-y-px hover:border-primary hover:bg-primary-50 hover:text-primary hover:shadow">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('admin.minicategory.delete', ['did' => $data->id]) }}"
                                            title="Delete mini category"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 shadow-sm transition-all hover:-translate-y-px hover:border-danger hover:bg-danger/10 hover:text-danger hover:shadow">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-20 text-center">
                                    <div class="mx-auto mb-4 grid h-14 w-14 place-items-center rounded-2xl bg-slate-50 ring-1 ring-slate-200">
                                        <i class="fas fa-folder-open text-xl text-slate-300"></i>
                                    </div>
                                    <p class="font-semibold text-slate-700">No mini categories found</p>
                                    <p class="mt-1 text-sm text-slate-500">
                                        Get started by
                                        <a href="{{ route('admin.minicategory') }}" class="text-primary hover:underline">adding your first mini category</a>.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>

@endsection
