@extends('layouts.admin.app')

@section('title', 'Product Gallery')

@section('content')

    <section class="mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Product Gallery</h1>
                <p class="mt-1 text-sm text-slate-500">Every gallery image uploaded across your products</p>
            </div>
            <ol class="flex items-center gap-1 text-sm text-slate-400">
                <li><a href="{{ routeHelper('dashboard') }}" class="transition-colors hover:text-primary">Home</a></li>
                <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                <li class="font-medium text-slate-600">Product Gallery</li>
            </ol>
        </div>
    </section>

    <section class="mb-6">
        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-200 px-4 py-4">
                <h2 class="text-base font-semibold text-slate-900">All Gallery Images</h2>
                <span class="inline-flex items-center rounded-full bg-primary/10 px-2.5 py-1 text-xs font-semibold text-primary ring-1 ring-inset ring-primary/20">
                    {{ $images->count() }} {{ Str::plural('image', $images->count()) }}
                </span>
            </div>

            @if ($images->count())
                <div class="grid grid-cols-2 gap-4 p-5 md:grid-cols-3 xl:grid-cols-4">
                    @foreach ($images as $img)
                        <div class="group overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm transition-all hover:-translate-y-0.5 hover:shadow-md">
                            <img src="{{ asset('uploads/product/' . $img->name) }}" alt="Product gallery image"
                                class="h-[230px] w-full bg-white object-contain p-2">
                            <div class="border-t border-slate-100 px-3 py-2 text-right">
                                <a href="{{ routeHelper('product/' . $img->product_id . '/edit') }}"
                                    class="inline-flex items-center gap-1.5 text-sm font-medium text-primary transition-colors hover:underline">
                                    <i class="fas fa-edit text-xs"></i> Edit or delete
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="px-5 py-20 text-center">
                    <div class="mx-auto mb-4 grid h-14 w-14 place-items-center rounded-2xl bg-slate-50 ring-1 ring-slate-200">
                        <i class="fas fa-image text-xl text-slate-300"></i>
                    </div>
                    <p class="font-semibold text-slate-700">No gallery images found</p>
                    <p class="mt-1 text-sm text-slate-500">Images added to product galleries will appear here.</p>
                </div>
            @endif
        </div>
    </section>

@endsection
