@extends('layouts.admin.app')

@section('title')
    @isset($tag)
        Edit Tag
    @else
        Add Tag
    @endisset
@endsection

@section('content')

    <section class="mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">
                    @isset($tag)
                        Edit Tag
                    @else
                        Add Tag
                    @endisset
                </h1>
                <p class="mt-1 text-sm text-slate-500">
                    @isset($tag)
                        Update the details of this tag
                    @else
                        Create a new product tag
                    @endisset
                </p>
            </div>
            <div class="flex items-center gap-3">
                <x-ui.button variant="outline" :href="routeHelper('tag')">
                    <i class="fas fa-long-arrow-alt-left text-xs"></i> Back to List
                </x-ui.button>
                <ol class="flex items-center gap-1 text-sm text-slate-400">
                    <li><a href="{{ routeHelper('dashboard') }}" class="transition-colors hover:text-primary">Home</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li><a href="{{ routeHelper('tag') }}" class="transition-colors hover:text-primary">Tags</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li class="font-medium text-slate-600">
                        @isset($tag)
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
            <form action="{{ isset($tag) ? routeHelper('tag/' . $tag->id) : routeHelper('tag') }}" method="POST">
                @csrf
                @isset($tag)
                    @method('PUT')
                @endisset

                <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex items-center gap-3 border-b border-slate-200 px-4 py-4">
                        <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                            <i class="fas fa-tags"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-slate-900">
                                @isset($tag)
                                    Edit Tag Details
                                @else
                                    Add New Tag
                                @endisset
                            </h3>
                            <p class="text-xs text-slate-500">Name, description and visibility</p>
                        </div>
                    </div>

                    <div class="space-y-4 p-5">
                        <x-ui.input
                            name="name"
                            label="Tag Name"
                            :value="$tag->name ?? null"
                            placeholder="Write tag name"
                            required
                            autocomplete="off"
                        />

                        <x-ui.textarea
                            name="description"
                            label="Description"
                            :rows="5"
                            placeholder="Write tag description"
                        >{{ $tag->description ?? old('description') }}</x-ui.textarea>

                        <div>
                            <label class="inline-flex cursor-pointer items-center gap-2">
                                <input
                                    type="checkbox"
                                    name="status"
                                    id="status"
                                    class="h-4 w-4 rounded border-slate-300 text-primary focus:ring-primary"
                                    @checked($tag->status ?? true)
                                >
                                <span class="text-sm font-medium text-slate-700">Status</span>
                            </label>
                            @error('status')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2 border-t border-slate-200 px-4 py-4">
                        <x-ui.button variant="outline" :href="routeHelper('tag')">
                            Cancel
                        </x-ui.button>
                        <x-ui.button type="submit" variant="primary">
                            @isset($tag)
                                <i class="fas fa-arrow-circle-up"></i>
                                Update
                            @else
                                <i class="fas fa-plus-circle"></i>
                                Submit
                            @endisset
                        </x-ui.button>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection
