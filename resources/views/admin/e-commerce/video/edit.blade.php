@extends('layouts.admin.app')

@section('title', 'Edit Video')

@section('content')

    <section class="mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Edit Video</h1>
                <p class="mt-1 text-sm text-slate-500">Update the details of this homepage video</p>
            </div>
            <div class="flex items-center gap-3">
                <x-ui.button variant="outline" :href="route('admin.video.index')">
                    <i class="fas fa-long-arrow-alt-left text-xs"></i> Back to List
                </x-ui.button>
                <ol class="flex items-center gap-1 text-sm text-slate-400">
                    <li><a href="{{ routeHelper('dashboard') }}" class="transition-colors hover:text-primary">Home</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li><a href="{{ route('admin.video.index') }}" class="transition-colors hover:text-primary">Videos</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li class="font-medium text-slate-600">Edit</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="mb-6">
        <div class="mx-auto w-full max-w-3xl">
            <form action="{{ route('admin.video.update', $video->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex items-center gap-3 border-b border-slate-200 px-4 py-4">
                        <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                            <i class="fas fa-video"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-slate-900">Edit Video Details</h3>
                            <p class="text-xs text-slate-500">Title, call-to-action, media files and visibility</p>
                        </div>
                    </div>

                    <div class="space-y-4 p-5">
                        <x-ui.input name="title" label="Title" :value="$video->title" />

                        <x-ui.textarea name="description" label="Description" :rows="3">{{ $video->description }}</x-ui.textarea>

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <x-ui.input name="button_text" label="Button Text" :value="$video->button_text" />
                            <x-ui.input name="button_url" label="Button URL" :value="$video->button_url" />
                        </div>

                        {{-- Change Video --}}
                        <div>
                            <span class="mb-1 block text-sm font-medium text-slate-700">Change Video</span>
                            <label class="block cursor-pointer rounded-xl border-2 border-dashed border-slate-200 px-4 py-6 text-center transition-colors hover:border-primary/50 hover:bg-primary-50/30">
                                <i class="fas fa-cloud-upload-alt mb-2 text-xl text-slate-400"></i>
                                <span class="block text-sm font-medium text-slate-700">Click to upload a new video</span>
                                <span class="mt-0.5 block text-xs text-slate-500">MP4, WebM or QuickTime — leave empty to keep the current one</span>
                                <input type="file" name="video" accept="video/mp4,video/webm,video/quicktime"
                                    class="mx-auto mt-3 block w-full max-w-xs text-sm text-slate-500">
                            </label>
                            @error('video')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror

                            @if ($video->video)
                                <video controls class="mt-3 w-56 rounded-lg ring-1 ring-slate-200">
                                    <source src="{{ asset('storage/' . $video->video) }}">
                                </video>
                            @endif
                        </div>

                        {{-- Change Thumbnail --}}
                        <div>
                            <span class="mb-1 block text-sm font-medium text-slate-700">Change Thumbnail</span>
                            <label class="block cursor-pointer rounded-xl border-2 border-dashed border-slate-200 px-4 py-6 text-center transition-colors hover:border-primary/50 hover:bg-primary-50/30">
                                <i class="fas fa-image mb-2 text-xl text-slate-400"></i>
                                <span class="block text-sm font-medium text-slate-700">Click to upload a new thumbnail</span>
                                <span class="mt-0.5 block text-xs text-slate-500">JPG, PNG or WebP — leave empty to keep the current one</span>
                                <input type="file" name="thumbnail" accept="image/*"
                                    class="mx-auto mt-3 block w-full max-w-xs text-sm text-slate-500">
                            </label>
                            @error('thumbnail')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror

                            @if ($video->thumbnail)
                                <img src="{{ asset('storage/' . $video->thumbnail) }}" alt="Current thumbnail"
                                    class="mt-3 w-44 rounded-lg object-cover ring-1 ring-slate-200">
                            @endif
                        </div>

                        {{-- Status toggle --}}
                        <div>
                            <label class="inline-flex cursor-pointer items-center gap-3">
                                <input type="checkbox" class="peer sr-only" name="status" id="status" @checked($video->status)>
                                <div class="peer relative h-6 w-11 rounded-full bg-slate-200 transition-colors after:absolute after:left-0.5 after:top-0.5 after:h-5 after:w-5 after:rounded-full after:border after:border-slate-300 after:bg-white after:shadow after:transition-all peer-checked:bg-primary peer-checked:after:translate-x-5 peer-checked:after:border-white peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-primary/50"></div>
                                <span class="text-sm font-medium text-slate-700">Active</span>
                            </label>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2 border-t border-slate-200 px-4 py-4">
                        <x-ui.button variant="outline" :href="route('admin.video.index')">
                            Cancel
                        </x-ui.button>
                        <x-ui.button type="submit" variant="primary">
                            <i class="fas fa-arrow-circle-up"></i>
                            Update Video
                        </x-ui.button>
                    </div>
                </div>
            </form>
        </div>
    </section>

@endsection
