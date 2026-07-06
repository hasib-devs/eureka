@extends('layouts.admin.app')

@section('title', 'Edit Author')

@push('css')
    <link rel="stylesheet" href="/assets/plugins/summernote/summernote-bs4.min.css">
@endpush

@section('content')

    <section class="mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Edit Author</h1>
                <p class="mt-1 text-sm text-slate-500">Update the profile of "{{ $author->name }}"</p>
            </div>
            <div class="flex items-center gap-3">
                <x-ui.button variant="outline" :href="routeHelper('author')">
                    <i class="fas fa-long-arrow-alt-left text-xs"></i> Back to List
                </x-ui.button>
                <ol class="flex items-center gap-1 text-sm text-slate-400">
                    <li><a href="{{ routeHelper('dashboard') }}" class="transition-colors hover:text-primary">Home</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li><a href="{{ routeHelper('author') }}" class="transition-colors hover:text-primary">Authors</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li class="font-medium text-slate-600">Edit</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="mb-6">
        <div class="mx-auto w-full max-w-3xl">
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <x-ui.alert variant="danger" class="mb-2">{{ $error }}</x-ui.alert>
                @endforeach
            @endif

            <form action="{{ route('admin.author.update', $author->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex items-center gap-3 border-b border-slate-200 px-4 py-4">
                        <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                            <i class="fas fa-user-edit"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-slate-900">Author details</h3>
                            <p class="text-xs text-slate-500">Name, profile photo and bio</p>
                        </div>
                    </div>

                    <div class="p-5">
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <x-ui.input
                                name="name"
                                label="Name"
                                type="text"
                                placeholder="Write author name"
                                :value="$author->name ?? old('name')"
                                required
                            />

                            <div>
                                <label for="profile" class="mb-1 block text-sm font-medium text-slate-700">Profile</label>
                                <div class="mb-2 flex items-center gap-3">
                                    <img src="{{ asset('/') }}/uploads/admin/{{ $author->img }}" alt="{{ $author->name }}"
                                        class="h-12 w-12 rounded-full object-cover ring-1 ring-slate-200">
                                    <span class="text-xs text-slate-500">Current photo — upload a new file to replace it</span>
                                </div>
                                <input
                                    class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm transition-shadow focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20"
                                    type="file"
                                    name="profile"
                                    id="profile"
                                >
                                @error('profile')
                                    <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="bio" class="mb-1 block text-sm font-medium text-slate-700">Bio</label>
                                <textarea
                                    name="bio"
                                    id="bio"
                                    rows="3"
                                    placeholder="Write bio"
                                    class="block w-full rounded-lg border px-3 py-2 text-sm shadow-sm transition-shadow focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 @error('bio') border-danger @else border-slate-300 @enderror"
                                >{{ $author->bio ?? old('bio') }}</textarea>
                                @error('bio')
                                    <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2 border-t border-slate-200 px-4 py-4">
                        <x-ui.button variant="outline" :href="routeHelper('author')">
                            Cancel
                        </x-ui.button>
                        <x-ui.button type="submit" variant="primary">
                            <i class="fas fa-arrow-circle-up"></i>
                            Update
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

            $('#bio').summernote()
        })
    </script>
@endpush
