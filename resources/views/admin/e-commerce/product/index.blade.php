@extends('layouts.admin.app')

@section('title', 'All Product List')

@php
    $isPaginated = $products instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator;
@endphp

@section('content')

    <section class="mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Products</h1>
                <p class="mt-1 text-sm text-slate-500">Manage every product in your catalog</p>
            </div>
            <div class="flex items-center gap-3">
                <x-ui.button variant="primary" :href="routeHelper('product/create')">
                    <i class="fas fa-plus text-xs"></i>
                    Add Product
                </x-ui.button>
                <ol class="flex items-center gap-1 text-sm text-slate-400">
                    <li><a href="{{ routeHelper('dashboard') }}" class="transition-colors hover:text-primary">Home</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li class="font-medium text-slate-600">Products</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="mb-6 space-y-4">

        {{-- Filters --}}
        @isset($statuses)
            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm md:p-5">
                @php
                    $control = 'h-10 w-full rounded-lg border border-slate-300 bg-white px-3 text-sm text-slate-700 shadow-sm transition-shadow focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20';
                @endphp

                <form action="{{ route('admin.product.index') }}" method="GET" class="grid grid-cols-1 items-end gap-3 md:grid-cols-12">
                    <div class="md:col-span-4">
                        <label for="title" class="mb-1 block text-sm font-medium text-slate-700">Search</label>
                        <div class="relative">
                            <i class="fas fa-search pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-sm text-slate-400"></i>
                            <input
                                id="title"
                                name="title"
                                type="text"
                                value="{{ request('title') }}"
                                placeholder="Product title…"
                                class="{{ $control }} pl-9"
                            />
                        </div>
                    </div>

                    <div class="md:col-span-4">
                        <label for="status" class="mb-1 block text-sm font-medium text-slate-700">Status</label>
                        <select id="status" name="status" class="{{ $control }}">
                            <option value="">All statuses</option>
                            @foreach ($statuses as $key => $value)
                                <option value="{{ $key }}" {{ (string) request('status') === (string) $key ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="md:col-span-4">
                        <label for="filter" class="mb-1 block text-sm font-medium text-slate-700">Filter Type</label>
                        <select id="filter" name="filter" class="{{ $control }}">
                            <option value="">All</option>
                            <option value="inhouse" {{ request('filter') == 'inhouse' ? 'selected' : '' }}>Inhouse</option>
                            <option value="low_stock" {{ request('filter') == 'low_stock' ? 'selected' : '' }}>Low Stock</option>
                            <option value="reached" {{ request('filter') == 'reached' ? 'selected' : '' }}>Most Reached</option>
                            <option value="unapproved" {{ request('filter') == 'unapproved' ? 'selected' : '' }}>Unapproved</option>
                            <option value="approved" {{ request('filter') == 'approved' ? 'selected' : '' }}>Approved</option>
                        </select>
                    </div>

                    <div class="md:col-span-4">
                        <label for="brand" class="mb-1 block text-sm font-medium text-slate-700">Brand</label>
                        <select id="brand" name="brand" class="{{ $control }}">
                            <option value="">All brands</option>
                            @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}" {{ request('brand') == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="md:col-span-4">
                        <label for="category" class="mb-1 block text-sm font-medium text-slate-700">Category</label>
                        <select id="category" name="category" class="{{ $control }}">
                            <option value="">All categories</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex gap-2 md:col-span-4">
                        <button type="submit" class="inline-flex h-10 flex-1 items-center justify-center gap-2 rounded-lg bg-primary px-4 text-sm font-medium text-white shadow-sm transition-all hover:bg-primary-600 hover:shadow focus:outline-none focus-visible:ring-2 focus-visible:ring-primary/50">
                            <i class="fas fa-filter text-xs"></i> Filter
                        </button>
                        <a href="{{ route('admin.product.index', array_merge(request()->all(), ['export' => 'csv'])) }}"
                            title="Download filtered products as CSV"
                            class="inline-flex h-10 items-center justify-center gap-2 rounded-lg border border-slate-300 bg-white px-3 text-sm font-medium text-slate-600 shadow-sm transition-all hover:border-primary hover:text-primary hover:shadow">
                            <i class="fas fa-file-csv"></i> CSV
                        </a>
                        <a href="{{ route('admin.product.index') }}" title="Reset filters" class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-slate-300 bg-white text-slate-500 shadow-sm transition-all hover:border-slate-400 hover:text-slate-700 hover:shadow">
                            <i class="fas fa-undo"></i>
                        </a>
                    </div>
                </form>
            </div>
        @endisset

        {{-- Product list --}}
        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-200 px-4 py-4">
                <h2 class="text-base font-semibold text-slate-900">All Products</h2>
                @php $productCount = $isPaginated ? $products->total() : $products->count(); @endphp
                <span class="inline-flex items-center rounded-full bg-primary/10 px-2.5 py-1 text-xs font-semibold text-primary ring-1 ring-inset ring-primary/20">
                    {{ $productCount }} {{ Str::plural('product', $productCount) }}
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[1100px] text-left text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50/80 text-[11px] font-semibold uppercase tracking-wider text-slate-400">
                            <th class="w-14 px-4 py-3.5">SL</th>
                            <th class="px-4 py-3.5">Product</th>
                            <th class="px-4 py-3.5 text-right">Regular Price</th>
                            <th class="px-4 py-3.5 text-right">Discount Price</th>
                            <th class="px-4 py-3.5">Stock</th>
                            <th class="px-4 py-3.5">Categories</th>
                            <th class="px-4 py-3.5">Brand</th>
                            <th class="px-4 py-3.5">Status</th>
                            <th class="px-4 py-3.5 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($products as $product)
                            <tr class="group transition-colors hover:bg-slate-50/80">
                                <td class="px-4 py-4 text-slate-500">
                                    {{ $isPaginated ? $products->firstItem() + $loop->index : $loop->iteration }}
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ asset('uploads/product/' . $product->image) }}" alt="Product Image"
                                            class="h-10 w-10 rounded-lg object-cover ring-1 ring-slate-200">
                                        <span class="font-semibold text-slate-800">{{ $product->title ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-right tabular-nums font-semibold text-slate-800">
                                    {{ $product->regular_price !== null ? number_format($product->regular_price, 2) : 'N/A' }}
                                </td>
                                <td class="px-4 py-4 text-right tabular-nums text-slate-500">
                                    {{ $product->discount_price !== null ? number_format($product->discount_price, 2) : 'N/A' }}
                                </td>
                                <td class="px-4 py-4">
                                    <span class="mr-1 tabular-nums text-slate-500">{{ $product->quantity }}</span>
                                    @if ($product->quantity > 0)
                                        <x-ui.badge variant="success" dot>Available</x-ui.badge>
                                    @else
                                        <x-ui.badge variant="danger" dot>Unavailable</x-ui.badge>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-slate-500">
                                    @foreach ($product->categories as $category)
                                        {{ $category->name ?? '' }}@if (!$loop->last), @endif
                                    @endforeach
                                </td>
                                <td class="px-4 py-4 text-slate-500">{{ $product->brand->name ?? '' }}</td>
                                <td class="px-4 py-4">
                                    @if ($product->status)
                                        <x-ui.badge variant="success" dot>Active</x-ui.badge>
                                    @else
                                        <x-ui.badge variant="danger" dot>Disable</x-ui.badge>
                                    @endif
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('admin.product.order', $product->id) }}"
                                            title="Create order for this product"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 shadow-sm transition-all hover:-translate-y-px hover:border-primary hover:bg-primary-50 hover:text-primary hover:shadow">
                                            <i class="fas fa-cart-plus"></i>
                                        </a>
                                        @if ($product->status)
                                            <a href="{{ routeHelper('product/status/' . $product->id) }}"
                                                title="Disable product"
                                                class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 shadow-sm transition-all hover:-translate-y-px hover:border-danger hover:bg-danger/10 hover:text-danger hover:shadow">
                                                <i class="fas fa-ban"></i>
                                            </a>
                                        @else
                                            <a href="{{ routeHelper('product/status/' . $product->id) }}"
                                                title="Activate product"
                                                class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 shadow-sm transition-all hover:-translate-y-px hover:border-primary hover:bg-primary-50 hover:text-primary hover:shadow">
                                                <i class="fas fa-check"></i>
                                            </a>
                                        @endif
                                        <a href="{{ routeHelper('product/' . $product->id) }}"
                                            title="View product"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 shadow-sm transition-all hover:-translate-y-px hover:border-primary hover:bg-primary-50 hover:text-primary hover:shadow">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ routeHelper('product/' . $product->id . '/edit') }}"
                                            title="Edit product"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 shadow-sm transition-all hover:-translate-y-px hover:border-primary hover:bg-primary-50 hover:text-primary hover:shadow">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ routeHelper('product/duplicate/' . $product->id) }}"
                                            onclick="return confirm('Duplicate this product? The copy will start disabled.')"
                                            title="Duplicate product"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 shadow-sm transition-all hover:-translate-y-px hover:border-primary hover:bg-primary-50 hover:text-primary hover:shadow">
                                            <i class="fas fa-copy"></i>
                                        </a>
                                        @if (auth()->user()->desig != 3)
                                            <a href="javascript:void(0)" data-id="{{ $product->id }}" id="deleteData"
                                                title="Delete product"
                                                class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 shadow-sm transition-all hover:-translate-y-px hover:border-danger hover:bg-danger/10 hover:text-danger hover:shadow">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        @endif
                                        <form id="delete-data-form-{{ $product->id }}"
                                            action="{{ routeHelper('product/' . $product->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-5 py-20 text-center">
                                    <div class="mx-auto mb-4 grid h-14 w-14 place-items-center rounded-2xl bg-slate-50 ring-1 ring-slate-200">
                                        <i class="fas fa-box-open text-xl text-slate-300"></i>
                                    </div>
                                    <p class="font-semibold text-slate-700">No products found</p>
                                    <p class="mt-1 text-sm text-slate-500">
                                        Try adjusting your search or
                                        <a href="{{ route('admin.product.index') }}" class="text-primary hover:underline">reset the filters</a>.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($isPaginated && $products->hasPages())
                <div class="border-t border-slate-200 px-4 py-4">
                    {{ $products->appends(request()->query())->links('admin.partials.pagination') }}
                </div>
            @endif
        </div>

    </section>

@endsection
