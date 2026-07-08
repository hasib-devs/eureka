@extends('layouts.admin.app')

@section('title', 'Related Products')

@section('content')
    @php
        $relatedCount = $products->where('is_related', true)->count();
    @endphp

    {{-- Page header --}}
    <section class="mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Related Products</h1>
                <p class="mt-1 text-sm text-slate-500">
                    Tick the products that should appear in the "Related Products" section at the bottom of every product page.
                </p>
            </div>
        </div>
    </section>

    @if (session('success'))
        <div class="mb-4 rounded-lg border border-emerald-300 bg-emerald-50 px-4 py-2 text-sm text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ routeHelper('related-products') }}" method="POST">
        @csrf

        <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
            {{-- Toolbar --}}
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 px-4 py-4">
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <i class="fas fa-search pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-xs text-slate-400"></i>
                        <input type="text" id="relatedSearch" placeholder="Search products…"
                            class="h-10 w-64 rounded-lg border border-slate-300 pl-9 pr-3 text-sm shadow-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                    </div>
                    <button type="button" id="selectAllBtn"
                        class="inline-flex h-10 items-center rounded-lg border border-slate-300 bg-white px-3 text-sm font-medium text-slate-600 hover:border-primary hover:text-primary">
                        Select all
                    </button>
                    <button type="button" id="clearAllBtn"
                        class="inline-flex h-10 items-center rounded-lg border border-slate-300 bg-white px-3 text-sm font-medium text-slate-600 hover:border-primary hover:text-primary">
                        Clear
                    </button>
                </div>
                <div class="text-sm text-slate-500">
                    <span id="selectedCount" class="font-semibold text-slate-900">{{ $relatedCount }}</span> selected
                </div>
            </div>

            {{-- Product list --}}
            <div class="max-h-[70vh] overflow-y-auto p-3">
                <div class="grid grid-cols-1 gap-2 sm:grid-cols-2 lg:grid-cols-3">
                    @forelse ($products as $product)
                        @php $price = $product->discount_price ?: $product->regular_price; @endphp
                        <label data-title="{{ \Illuminate\Support\Str::lower($product->title) }}"
                            class="related-row flex cursor-pointer items-center gap-3 rounded-lg border border-slate-200 p-2.5 transition-colors hover:border-primary/50 has-[:checked]:border-primary has-[:checked]:bg-primary/5">
                            <input type="checkbox" name="related_ids[]" value="{{ $product->id }}"
                                class="related-check h-4 w-4 shrink-0 rounded border-slate-300 text-primary focus:ring-primary"
                                {{ $product->is_related ? 'checked' : '' }}>
                            <img src="{{ $product->hero_image_url }}" alt="{{ $product->title }}"
                                class="h-12 w-12 shrink-0 rounded-md object-cover" loading="lazy">
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-medium text-slate-800">{{ $product->title }}</p>
                                <p class="text-xs text-slate-500">
                                    ৳ {{ number_format((float) $price) }}
                                    @unless ($product->status)
                                        <span class="ml-1 rounded bg-slate-100 px-1.5 py-0.5 text-[10px] uppercase text-slate-500">Inactive</span>
                                    @endunless
                                </p>
                            </div>
                        </label>
                    @empty
                        <p class="col-span-full py-10 text-center text-sm text-slate-500">No products found.</p>
                    @endforelse
                </div>
            </div>

            {{-- Save bar --}}
            <div class="flex items-center justify-end gap-3 border-t border-slate-200 px-4 py-4">
                <button type="submit"
                    class="inline-flex h-10 items-center justify-center gap-2 rounded-lg bg-primary px-5 text-sm font-semibold text-white shadow-sm transition-all hover:opacity-90">
                    <i class="fas fa-save text-xs"></i> Save Selection
                </button>
            </div>
        </div>
    </form>

    @push('js')
        <script>
            (function () {
                const search = document.getElementById('relatedSearch');
                const rows = Array.from(document.querySelectorAll('.related-row'));
                const checks = Array.from(document.querySelectorAll('.related-check'));
                const countEl = document.getElementById('selectedCount');

                function updateCount() {
                    countEl.textContent = checks.filter(c => c.checked).length;
                }
                function visibleChecks() {
                    return checks.filter(c => c.closest('.related-row').style.display !== 'none');
                }

                search.addEventListener('input', function () {
                    const q = this.value.trim().toLowerCase();
                    rows.forEach(row => {
                        row.style.display = row.dataset.title.includes(q) ? '' : 'none';
                    });
                });
                document.getElementById('selectAllBtn').addEventListener('click', function () {
                    visibleChecks().forEach(c => c.checked = true);
                    updateCount();
                });
                document.getElementById('clearAllBtn').addEventListener('click', function () {
                    visibleChecks().forEach(c => c.checked = false);
                    updateCount();
                });
                checks.forEach(c => c.addEventListener('change', updateCount));
            })();
        </script>
    @endpush
@endsection
