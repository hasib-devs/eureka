@extends('layouts.admin.app')

@section('title')
    @isset($page)
        Edit Page
    @else
        Add Page
    @endisset
@endsection

@push('css')
    <link rel="stylesheet" href="/assets/plugins/summernote/summernote-bs4.min.css">
@endpush

@section('content')

    <section class="mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">
                    @isset($page)
                        Edit Page
                    @else
                        Add Page
                    @endisset
                </h1>
                <p class="mt-1 text-sm text-slate-500">
                    @isset($page)
                        Update the content of this page
                    @else
                        Create a new static page for your storefront
                    @endisset
                </p>
            </div>
            <div class="flex items-center gap-3">
                <x-ui.button variant="outline" :href="route('admin.pages')">
                    <i class="fas fa-long-arrow-alt-left text-xs"></i> Back to List
                </x-ui.button>
                <ol class="flex items-center gap-1 text-sm text-slate-400">
                    <li><a href="{{ routeHelper('dashboard') }}" class="transition-colors hover:text-primary">Home</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li><a href="{{ route('admin.pages') }}" class="transition-colors hover:text-primary">Pages</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li class="font-medium text-slate-600">
                        @isset($page)
                            Edit
                        @else
                            Add
                        @endisset
                    </li>
                </ol>
            </div>
        </div>
    </section>

    <section class="mb-6">
        <div class="mx-auto w-full max-w-3xl">
            <form action="{{ isset($page) ? route('admin.page.update') : route('admin.page.make') }}" method="POST">
                @csrf
                @isset($page)
                    <input type="hidden" value="{{ $page->id }}" name="id">
                @endisset

                <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex items-center gap-3 border-b border-slate-200 px-4 py-4">
                        <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-slate-900">
                                @isset($page)
                                    Edit Page Details
                                @else
                                    Add New Page
                                @endisset
                            </h3>
                            <p class="text-xs text-slate-500">Name, title, content, position and visibility</p>
                        </div>
                    </div>

                    <div class="space-y-4 p-5">
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <x-ui.input name="name" label="Name" :value="$page->name ?? old('name')" />
                            <x-ui.input name="title" label="Title" :value="$page->title ?? old('title')" />
                        </div>

                        {{-- Description (Summernote — keep raw textarea, JS hooks preserved) --}}
                        <div>
                            <label for="full_description" class="mb-1 block text-sm font-medium text-slate-700">Description</label>
                            <textarea name="body" id="full_description" rows="5" placeholder="Write page description"
                                class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm transition-shadow focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">{{ $page->body ?? old('body') }}</textarea>
                            @error('body')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <x-ui.select name="position" label="Position">
                                <option value="0" @selected(isset($page) && $page->position == 0)>Nav</option>
                                <option value="1" @selected(isset($page) && $page->position == 1)>Bottom</option>
                                <option value="2" @selected(isset($page) && $page->position == 2)>Both</option>
                            </x-ui.select>

                            <x-ui.select name="status" label="Status">
                                <option value="1" @selected(isset($page) && $page->status == 1)>Active</option>
                                <option value="0" @selected(isset($page) && $page->status == 0)>Deactive</option>
                            </x-ui.select>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2 border-t border-slate-200 px-4 py-4">
                        <x-ui.button variant="outline" :href="route('admin.pages')">
                            Cancel
                        </x-ui.button>
                        <x-ui.button type="submit" variant="primary">
                            @isset($page)
                                <i class="fas fa-arrow-circle-up"></i>
                                Update
                            @else
                                <i class="fas fa-plus-circle"></i>
                                Create
                            @endisset
                        </x-ui.button>
                    </div>
                </div>
            </form>
        </div>
    </section>

@endsection

@push('js')
    <script src="/assets/plugins/summernote/summernote-bs4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#full_description').summernote();
        });
    </script>
@endpush
