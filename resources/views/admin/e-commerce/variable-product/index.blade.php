@extends('layouts.admin.app')

@section('title', 'Variable Products')

@section('content')
    {{-- Page header --}}
    <section class="mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Variable Products</h1>
                <p class="mt-1 text-sm text-slate-500">Products whose colour is a full variation — its own image, price and stock.</p>
            </div>
            <a href="{{ routeHelper('variable-products/create') }}"
                class="inline-flex h-10 items-center justify-center gap-2 rounded-lg bg-primary px-4 text-sm font-semibold text-white shadow-sm transition-all hover:opacity-90">
                <i class="fas fa-plus text-xs"></i> Add Variable Product
            </a>
        </div>
    </section>

    @if (session('success'))
        <div class="mb-4 rounded-lg border border-emerald-300 bg-emerald-50 px-4 py-2 text-sm text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    <section class="rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="border-b border-slate-200 bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-4 py-3">Product</th>
                        <th class="px-4 py-3">Colours</th>
                        <th class="px-4 py-3">From price</th>
                        <th class="px-4 py-3">Stock</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($products as $product)
                        <tr class="hover:bg-slate-50/60">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $product->hero_image_url }}" alt="" class="h-12 w-12 shrink-0 rounded-md object-cover">
                                    <div class="min-w-0">
                                        <p class="truncate font-medium text-slate-800">{{ $product->title }}</p>
                                        <p class="text-xs text-slate-400">{{ $product->sku ?: '—' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-1">
                                    @foreach ($product->colors->take(6) as $color)
                                        <span class="inline-block h-4 w-4 rounded-full border border-slate-200" style="background: {{ $color->code ?? '#ddd' }}" title="{{ $color->name }}"></span>
                                    @endforeach
                                    <span class="ml-1 text-xs text-slate-500">{{ $product->colors->count() }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-slate-700">৳ {{ number_format((float) $product->regular_price) }}</td>
                            <td class="px-4 py-3 text-slate-700">{{ (int) $product->quantity }}</td>
                            <td class="px-4 py-3">
                                @if ($product->status)
                                    <span class="rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-medium text-emerald-700">Active</span>
                                @else
                                    <span class="rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-500">Inactive</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ routeHelper('variable-products/' . $product->id . '/edit') }}"
                                        class="inline-flex h-9 items-center gap-1.5 rounded-lg border border-slate-300 px-3 text-xs font-medium text-slate-600 hover:border-primary hover:text-primary">
                                        <i class="fas fa-pen"></i> Edit
                                    </a>
                                    <form action="{{ routeHelper('variable-products/' . $product->id) }}" method="POST"
                                        onsubmit="return confirm('Delete this variable product?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex h-9 items-center gap-1.5 rounded-lg border border-slate-300 px-3 text-xs font-medium text-slate-600 hover:border-danger hover:text-danger">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center text-sm text-slate-500">
                                No variable products yet. Click <span class="font-medium">Add Variable Product</span> to create one.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($products->hasPages())
            <div class="border-t border-slate-200 px-4 py-3">
                {{ $products->links() }}
            </div>
        @endif
    </section>
@endsection
