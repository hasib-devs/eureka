@extends('layouts.admin.app')

@section('title', 'Edit Rating')

@section('content')
    {{-- Page header --}}
    <section class="mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Edit Rating</h1>
                <p class="mt-1 text-sm text-slate-500">Update a customer review, its score and attached images</p>
            </div>
            <ol class="flex items-center gap-1 text-sm text-slate-400">
                <li><a href="{{ routeHelper('dashboard') }}" class="transition-colors hover:text-primary">Home</a></li>
                <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                <li class="font-medium text-slate-600">Edit Rating</li>
            </ol>
        </div>
    </section>

    {{-- Main content --}}
    <section class="mb-6">
        <form action="{{ route('admin.rating.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <input type="hidden" value="{{ $rating->id }}" name="id">

            {{-- Rating & review --}}
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center gap-3 border-b border-slate-200 px-4 py-4">
                    <span class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                        <i class="fas fa-star"></i>
                    </span>
                    <div>
                        <h3 class="text-base font-semibold text-slate-900">Rating &amp; Review</h3>
                        <p class="text-xs text-slate-500">Score given by the customer and their written review</p>
                    </div>
                </div>

                <div class="p-5">
                    <p class="mb-2 text-sm font-medium text-slate-700">Give Rating</p>
                    <div class="rating flex flex-wrap gap-2">
                        @foreach ([5, 4, 3, 2, 1] as $star)
                            <label for="{{ $star }}"
                                class="inline-flex cursor-pointer items-center gap-2 rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-700 shadow-sm transition-all hover:-translate-y-px hover:border-primary hover:text-primary hover:shadow">
                                <input @if ($rating->rating == $star) checked @endif type="radio" name="rating"
                                    value="{{ $star }}" id="{{ $star }}">
                                {{ $star }} <i class="fas fa-star text-xs text-warning"></i>
                            </label>
                        @endforeach
                    </div>

                    <div class="mt-4">
                        <label for="review" class="mb-1 block text-sm font-medium text-slate-700">Review</label>
                        <textarea required id="review" rows="4"
                            class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm transition-shadow focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20"
                            name="review" placeholder="what is your view?">{{ $rating->body }}</textarea>
                        @error('review')
                            <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Review images --}}
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <button class="flex w-full items-center gap-3 px-4 py-4 text-left" type="button" data-toggle="collapse"
                    data-target="#BookOpen" aria-expanded="false" aria-controls="BookOpen">
                    <span class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                        <i class="fas fa-images"></i>
                    </span>
                    <span class="flex-1">
                        <span class="block font-semibold text-slate-900">Review Images</span>
                        <span class="block text-xs text-slate-500">Upload or replace up to five images attached to this review</span>
                    </span>
                    <i class="fas fa-chevron-down text-xs text-slate-400"></i>
                </button>

                <div class="spec collapse" id="BookOpen">
                    <div class="grid grid-cols-1 gap-4 border-t border-slate-200 p-5 md:grid-cols-2 xl:grid-cols-3">
                        @foreach (['report' => $rating->file, 'report2' => $rating->file2, 'report3' => $rating->file3, 'report4' => $rating->file4, 'report5' => $rating->file5] as $field => $file)
                            <div class="rounded-xl border-2 border-dashed border-slate-200 px-4 py-5 text-center transition-colors hover:border-primary/50 hover:bg-primary-50/30">
                                <i class="fas fa-cloud-upload-alt mb-2 text-xl text-slate-300"></i>
                                <label class="mb-2 block text-sm font-medium text-slate-700">Upload Image</label>
                                <input type="file" name="{{ $field }}" class="mx-auto block w-full text-sm text-slate-600">
                                @if ($file)
                                    <a target="_blank" href="{{ asset('/') }}uploads/review/{{ $file }}"
                                        class="mt-3 inline-block">
                                        <img class="h-20 w-20 rounded-lg object-cover ring-1 ring-slate-200"
                                            src="{{ asset('/') }}uploads/review/{{ $file }}" alt="Review image">
                                    </a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Submit --}}
            <div class="flex items-center justify-end gap-2 border-t border-slate-200 pt-4">
                <a href="{{ routeHelper('product/' . $rating->product_id) }}"
                    class="inline-flex h-10 items-center justify-center rounded-lg border border-slate-300 bg-white px-4 text-sm font-medium text-slate-600 shadow-sm transition-all hover:border-slate-400 hover:text-slate-800 hover:shadow">
                    Cancel
                </a>
                <x-ui.button variant="primary" type="submit">
                    <i class="fas fa-arrow-circle-up"></i>
                    Update
                </x-ui.button>
            </div>
        </form>
    </section>
@endsection
