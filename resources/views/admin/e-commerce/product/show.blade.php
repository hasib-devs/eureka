@extends('layouts.admin.app')

@section('title', 'Product Information')

@section('content')

    {{-- Page header --}}
    <section class="mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Product Information</h1>
                <p class="mt-1 text-sm text-slate-500">Full detail, media, variations and customer feedback</p>
            </div>
            <div class="flex items-center gap-3">
                <x-ui.button variant="primary" :href="routeHelper('product/' . $product->id . '/edit')">
                    <i class="fas fa-edit text-xs"></i>
                    Edit
                </x-ui.button>
                <a href="{{ routeHelper('product') }}"
                    class="inline-flex h-10 items-center justify-center gap-2 rounded-lg border border-slate-300 bg-white px-4 text-sm font-medium text-slate-600 shadow-sm transition-all hover:border-primary hover:text-primary hover:shadow">
                    <i class="fas fa-long-arrow-alt-left text-xs"></i>
                    Back to List
                </a>
                <ol class="flex items-center gap-1 text-sm text-slate-400">
                    <li><a href="{{ routeHelper('dashboard') }}" class="transition-colors hover:text-primary">Home</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li class="font-medium text-slate-600">Show Product</li>
                </ol>
            </div>
        </div>
    </section>

    {{-- Main content --}}
    <section class="mb-6 space-y-4">

        <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">

            {{-- Overview --}}
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm lg:col-span-2">
                <div class="flex items-center gap-3 border-b border-slate-200 px-4 py-4">
                    <span class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                        <i class="fas fa-box"></i>
                    </span>
                    <div>
                        <h3 class="text-base font-semibold text-slate-900">Product Details</h3>
                        <p class="text-xs text-slate-500">Media, description and available variations</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6 p-5 md:grid-cols-2">
                    {{-- Left: image + thumbnails --}}
                    <div>
                        <div class="mb-3 overflow-hidden rounded-xl border border-slate-200 bg-slate-50/50">
                            <img src="{{ asset('uploads/product/' . $product->image) }}"
                                class="product-image max-h-80 w-full object-contain"
                                alt="Product Image">
                        </div>
                        <div class="product-image-thumbs flex flex-wrap gap-2">
                            <div class="product-image-thumb active h-16 w-16 overflow-hidden rounded-lg ring-2 ring-primary/40">
                                <img src="{{ asset('uploads/product/' . $product->image) }}"
                                    alt="Product Image" class="h-full w-full object-cover">
                            </div>
                            @foreach ($product->images as $image)
                                <div class="product-image-thumb active h-16 w-16 overflow-hidden rounded-lg ring-1 ring-slate-200">
                                    <img src="{{ asset('uploads/product/' . $image->name) }}"
                                        alt="Product Image" class="h-full w-full object-cover">
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Right: product info --}}
                    <div>
                        <h3 class="text-xl font-bold tracking-tight text-slate-900">{{ $product->title }}</h3>
                        <p class="mt-2 text-sm text-slate-600">{{ $product->short_description }}</p>

                        <hr class="my-4 border-slate-200">

                        <h4 class="mb-2 text-sm font-semibold uppercase tracking-wider text-slate-400">Available Colors</h4>
                        <div class="flex flex-wrap gap-2" data-toggle="buttons">
                            @forelse ($colors_product as $color)
                                <div class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm">
                                    <span class="h-6 w-6 rounded-full ring-2 ring-white shadow" style="background: {{ $color->code }}"></span>
                                    <span>
                                        <span class="block font-medium text-slate-800">{{ $color->name }}</span>
                                        <span class="block text-xs text-slate-500">Price: {{ $color->price }} · Qty: {{ $color->qnty }}</span>
                                    </span>
                                </div>
                            @empty
                                <p class="text-sm text-slate-500">No color variations.</p>
                            @endforelse
                        </div>

                        @foreach ($attributes as $attribute)
                            <?php
                            $attribute_prouct = DB::table('attribute_product')->select('*')->join('attribute_values', 'attribute_values.id', '=', 'attribute_product.attribute_value_id')->addselect('attribute_values.name as vName')->addselect('attribute_product.id as vid')->join('attributes', 'attributes.id', '=', 'attribute_values.attributes_id')->where('attribute_product.product_id', $product->id)->where('attributes.id', $attribute->id)->get();
                            ?>
                            @if ($attribute_prouct->count())
                                <h4 class="mt-4 mb-2 text-sm font-semibold uppercase tracking-wider text-slate-400">Available {{ $attribute->name }}</h4>
                                <div class="flex flex-wrap gap-2" data-toggle="buttons">
                                    @foreach ($attribute_prouct as $pro_color)
                                        <div class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm">
                                            <span>
                                                <span class="block font-medium text-slate-800">{{ $pro_color->vName }}</span>
                                                <span class="block text-xs text-slate-500">Price: {{ $pro_color->price }} · Qty: {{ $pro_color->qnty }}</span>
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Pricing & stock aside --}}
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center gap-3 border-b border-slate-200 px-4 py-4">
                    <span class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                        <i class="fas fa-coins"></i>
                    </span>
                    <div>
                        <h3 class="text-base font-semibold text-slate-900">Pricing &amp; Stock</h3>
                        <p class="text-xs text-slate-500">Current prices and inventory</p>
                    </div>
                </div>
                <dl class="divide-y divide-slate-100 px-5 py-2">
                    <div class="flex justify-between gap-4 py-2 text-sm">
                        <dt class="text-slate-500">Regular Price</dt>
                        <dd class="text-right font-semibold tabular-nums text-slate-800">
                            {{ $product->regular_price !== null ? number_format($product->regular_price, 2) : '—' }}
                        </dd>
                    </div>
                    <div class="flex justify-between gap-4 py-2 text-sm">
                        <dt class="text-slate-500">Discount Price</dt>
                        <dd class="text-right font-medium tabular-nums text-slate-800">
                            {{ $product->discount_price !== null ? number_format($product->discount_price, 2) : '—' }}
                        </dd>
                    </div>
                    <div class="flex justify-between gap-4 py-2 text-sm">
                        <dt class="text-slate-500">Shipping Charge</dt>
                        <dd class="text-right font-medium text-slate-800">
                            @if ($product->shipping_charge)
                                Dummy
                            @else
                                Free
                            @endif
                        </dd>
                    </div>
                    <div class="flex justify-between gap-4 py-2 text-sm">
                        <dt class="text-slate-500">Quantity</dt>
                        <dd class="text-right font-medium tabular-nums text-slate-800">{{ $product->quantity }}</dd>
                    </div>
                    <div class="flex justify-between gap-4 py-2 text-sm">
                        <dt class="text-slate-500">Stock</dt>
                        <dd class="text-right">
                            @if ($product->quantity > 0)
                                <x-ui.badge variant="success" dot>Available</x-ui.badge>
                            @else
                                <x-ui.badge variant="danger" dot>Unavailable</x-ui.badge>
                            @endif
                        </dd>
                    </div>
                    @isset($product->brand->name)
                        <div class="flex justify-between gap-4 py-2 text-sm">
                            <dt class="text-slate-500">Brand</dt>
                            <dd class="text-right font-medium text-slate-800">{{ $product->brand->name }}</dd>
                        </div>
                    @endisset
                </dl>
            </div>
        </div>

        {{-- Tabs --}}
        <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
            <nav class="w-full px-5 pt-2">
                <div class="product-tab-nav nav flex flex-wrap border-b border-slate-200" id="product-tab" role="tablist">
                    <a class="nav-item nav-link -mb-px border-b-2 border-transparent px-4 py-2.5 text-sm font-medium text-slate-500 transition-colors hover:text-primary [&.active]:border-primary [&.active]:font-semibold [&.active]:text-primary active"
                        id="product-desc-tab" data-toggle="tab" href="#product-desc"
                        role="tab" aria-controls="product-desc" aria-selected="true">Description</a>
                    <a class="nav-item nav-link -mb-px border-b-2 border-transparent px-4 py-2.5 text-sm font-medium text-slate-500 transition-colors hover:text-primary [&.active]:border-primary [&.active]:font-semibold [&.active]:text-primary"
                        id="product-comments-tab" data-toggle="tab"
                        href="#product-comments" role="tab" aria-controls="product-comments"
                        aria-selected="false">Comments</a>
                    <a class="nav-item nav-link -mb-px border-b-2 border-transparent px-4 py-2.5 text-sm font-medium text-slate-500 transition-colors hover:text-primary [&.active]:border-primary [&.active]:font-semibold [&.active]:text-primary"
                        id="product-rating-tab" data-toggle="tab" href="#product-rating"
                        role="tab" aria-controls="product-rating" aria-selected="false">Rating</a>
                    <a class="nav-item nav-link -mb-px border-b-2 border-transparent px-4 py-2.5 text-sm font-medium text-slate-500 transition-colors hover:text-primary [&.active]:border-primary [&.active]:font-semibold [&.active]:text-primary"
                        id="product-categories-tab" data-toggle="tab"
                        href="#product-categories" role="tab" aria-controls="product-categories"
                        aria-selected="false">Categories</a>
                    <a class="nav-item nav-link -mb-px border-b-2 border-transparent px-4 py-2.5 text-sm font-medium text-slate-500 transition-colors hover:text-primary [&.active]:border-primary [&.active]:font-semibold [&.active]:text-primary"
                        id="product-sub-categories-tab" data-toggle="tab"
                        href="#product-sub-categories" role="tab" aria-controls="product-sub-categories"
                        aria-selected="false">Sub Categories</a>
                    <a class="nav-item nav-link -mb-px border-b-2 border-transparent px-4 py-2.5 text-sm font-medium text-slate-500 transition-colors hover:text-primary [&.active]:border-primary [&.active]:font-semibold [&.active]:text-primary"
                        id="product-tags-tab" data-toggle="tab" href="#product-tags"
                        role="tab" aria-controls="product-tags" aria-selected="false">Tags</a>
                    @if ($product->downloads->count() > 0)
                        <a class="nav-item nav-link -mb-px border-b-2 border-transparent px-4 py-2.5 text-sm font-medium text-slate-500 transition-colors hover:text-primary [&.active]:border-primary [&.active]:font-semibold [&.active]:text-primary"
                            id="product-downloads-tab" data-toggle="tab"
                            href="#product-downloads" role="tab" aria-controls="product-downloads"
                            aria-selected="false">Downloadable Files</a>
                    @endif
                </div>
            </nav>

            <div class="tab-content w-full p-5" id="nav-tabContent">

                {{-- Description --}}
                <div class="tab-pane fade show active" id="product-desc" role="tabpanel"
                    aria-labelledby="product-desc-tab">
                    {!! $product->full_description !!}
                </div>

                {{-- Comments --}}
                <div class="tab-pane fade" id="product-comments" role="tabpanel"
                    aria-labelledby="product-comments-tab">
                    <div class="space-y-5">
                        @forelse ($product->comments->where('parent_id', null) as $comment)
                            <div class="media flex gap-3 rounded-xl border border-slate-100 p-4">
                                <img class="h-10 w-10 shrink-0 rounded-lg object-cover ring-1 ring-slate-200" alt="Avatar"
                                    src="{{ $comment->user->avatar == 'default.png' ? '/default/default.png' : '/uploads/admin/' . $comment->user->avatar }}" />
                                <div class="media-body min-w-0 flex-1">
                                    <div class="flex items-center justify-between gap-2">
                                        <h5 class="font-semibold text-slate-800">{{ $comment->user->name }}</h5>
                                        <a href="{{ route('admin.comment', $comment->id) }}"
                                            title="Delete comment"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 shadow-sm transition-all hover:-translate-y-px hover:border-danger hover:bg-danger/10 hover:text-danger hover:shadow">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                    <p class="text-sm text-slate-700">{{ $comment->body }}</p>
                                    <p class="mt-0.5 text-xs text-slate-400">{{ $comment->created_at->diffForHumans() }}</p>

                                    @forelse ($comment->replies as $reply)
                                        <div class="media mt-4 flex gap-3 border-l-2 border-slate-100 pl-4">
                                            <a class="shrink-0" href="#">
                                                <img class="h-10 w-10 rounded-lg object-cover ring-1 ring-slate-200" alt="Avatar"
                                                    src="{{ $reply->user->avatar == 'default.png' ? '/default/default.png' : '/uploads/admin/' . $reply->user->avatar }}" />
                                            </a>
                                            <div class="media-body min-w-0 flex-1">
                                                <div class="flex items-center justify-between gap-2">
                                                    <h5 class="font-semibold text-slate-800">{{ $reply->user->name }}</h5>
                                                    <a href="{{ route('admin.comment', $reply->id) }}"
                                                        title="Delete reply"
                                                        class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 shadow-sm transition-all hover:-translate-y-px hover:border-danger hover:bg-danger/10 hover:text-danger hover:shadow">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </div>
                                                <p class="text-sm text-slate-700">{{ $reply->body }}</p>
                                                <p class="mt-0.5 text-xs text-slate-400">{{ $reply->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                    @empty
                                    @endforelse

                                    <div class="reply-box"></div>
                                </div>
                            </div>
                        @empty
                            <div class="px-5 py-16 text-center">
                                <div class="mx-auto mb-4 grid h-14 w-14 place-items-center rounded-2xl bg-slate-50 ring-1 ring-slate-200">
                                    <i class="fas fa-comments text-xl text-slate-300"></i>
                                </div>
                                <p class="font-semibold text-slate-700">Comments not available</p>
                                <p class="mt-1 text-sm text-slate-500">Customer comments on this product will appear here.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Rating --}}
                <div class="tab-pane fade" id="product-rating" role="tabpanel"
                    aria-labelledby="product-rating-tab">
                    <div class="space-y-5">
                        @forelse ($product->reviews as $review)
                            <div class="flex flex-wrap gap-3 rounded-xl border border-slate-100 p-4">
                                <div class="review-head w-12 shrink-0">
                                    @if ($review->user->avatar)
                                        <img src="{{ $review->user->avatar == 'default.png' ? '/default/default.png' : '/uploads/admin/' . $review->user->avatar }}"
                                            alt="" class="h-10 w-10 rounded-full object-cover ring-1 ring-slate-200">
                                    @endif
                                </div>
                                <div class="side-2 min-w-0 flex-1">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <b class="text-slate-800">{{ $review->user->name }}</b>
                                        <a href="{{ route('admin.rating', $review->id) }}"
                                            title="Delete rating"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 shadow-sm transition-all hover:-translate-y-px hover:border-danger hover:bg-danger/10 hover:text-danger hover:shadow">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                        <a href="{{ route('admin.rating.edit', $review->id) }}"
                                            title="Edit rating"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 shadow-sm transition-all hover:-translate-y-px hover:border-primary hover:bg-primary-50 hover:text-primary hover:shadow">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>

                                    <div class="rating1 mt-1 flex items-center gap-1 text-sm text-warning">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i class="{{ $i <= $review->rating ? 'fas' : 'far' }} fa-star"></i>
                                        @endfor
                                        <span class="ml-1 text-slate-600">{{ $review->rating }} rating</span>
                                    </div>

                                    <p class="mt-1 text-sm text-slate-700">{{ $review->body }}</p>
                                    <div class="crv mt-2 flex flex-wrap gap-2">
                                        @foreach ([$review->file, $review->file2, $review->file3, $review->file4, $review->file5] as $reviewFile)
                                            @if ($reviewFile)
                                                <a href="{{ asset('/') }}uploads/review/{{ $reviewFile }}">
                                                    <img class="h-20 w-20 rounded-lg object-cover ring-1 ring-slate-200"
                                                        src="{{ asset('/') }}uploads/review/{{ $reviewFile }}" alt="Review image">
                                                </a>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="px-5 py-16 text-center">
                                <div class="mx-auto mb-4 grid h-14 w-14 place-items-center rounded-2xl bg-slate-50 ring-1 ring-slate-200">
                                    <i class="fas fa-star text-xl text-slate-300"></i>
                                </div>
                                <p class="font-semibold text-slate-700">Reviews not available</p>
                                <p class="mt-1 text-sm text-slate-500">Customer reviews for this product will appear here.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Categories --}}
                <div class="tab-pane fade" id="product-categories" role="tabpanel"
                    aria-labelledby="product-categories-tab">
                    <div class="flex flex-wrap gap-2">
                        @forelse ($product->categories as $category)
                            <x-ui.badge variant="primary">{{ $category->name }}</x-ui.badge>
                        @empty
                            <strong class="text-sm text-slate-500">Not Available</strong>
                        @endforelse
                    </div>
                </div>

                {{-- Sub Categories --}}
                <div class="tab-pane fade" id="product-sub-categories" role="tabpanel"
                    aria-labelledby="product-sub-categories-tab">
                    <div class="flex flex-wrap gap-2">
                        @forelse ($product->sub_categories as $sub_category)
                            <x-ui.badge variant="primary">{{ $sub_category->name }}</x-ui.badge>
                        @empty
                            <strong class="text-sm text-slate-500">Not Available</strong>
                        @endforelse
                    </div>
                </div>

                {{-- Tags --}}
                <div class="tab-pane fade" id="product-tags" role="tabpanel" aria-labelledby="product-tags-tab">
                    <div class="flex flex-wrap gap-2">
                        @forelse ($product->tags as $key => $tag)
                            <x-ui.badge variant="neutral">{{ $tag->name }}</x-ui.badge>
                        @empty
                            <strong class="text-sm text-slate-500">Not Available</strong>
                        @endforelse
                    </div>
                </div>

                {{-- Downloads --}}
                <div class="tab-pane fade" id="product-downloads" role="tabpanel"
                    aria-labelledby="product-downloads-tab">
                    @foreach ($product->downloads as $key => $download)
                        <div class="mb-3 text-sm text-slate-700">
                            <p><strong>File Name: </strong><a href="" class="text-primary hover:underline">{{ $download->name }}</a></p>
                            @if ($download->url != null)
                                <p><strong>File URL: </strong><a href="" class="text-primary hover:underline">{{ $download->url }}</a></p>
                            @else
                                <p><strong>File URL: </strong><a href=""
                                        class="text-primary hover:underline">{{ asset('uploads/product/download/' . $download->url) }}</a>
                                </p>
                            @endif
                        </div>
                    @endforeach
                </div>

            </div>
        </div>

    </section>

@endsection
