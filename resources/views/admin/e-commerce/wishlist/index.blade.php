@extends('layouts.admin.app')

@section('title', 'Wishlists')

@section('content')

    <section class="mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Wishlists</h1>
                <p class="mt-1 text-sm text-slate-500">Turn the storefront wishlist on or off and see what customers are saving</p>
            </div>
            <ol class="flex items-center gap-1 text-sm text-slate-400">
                <li><a href="{{ routeHelper('dashboard') }}" class="transition-colors hover:text-primary">Home</a></li>
                <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                <li class="font-medium text-slate-600">Wishlists</li>
            </ol>
        </div>
    </section>

    {{-- Feature toggle --}}
    <section class="mb-6">
        <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
            <form action="{{ route('admin.wishlist.update') }}" method="POST">
                @csrf
                <div class="flex flex-wrap items-center justify-between gap-4 px-5 py-4">
                    <div class="flex items-center gap-3">
                        <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                            <i class="fas fa-heart"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-semibold text-slate-900">Wishlist feature</h2>
                            <p class="text-xs text-slate-500">When off, the wishlist button, header icon and wishlist page are hidden storefront-wide</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <label class="inline-flex cursor-pointer items-center gap-2">
                            <input type="checkbox" name="wishlist_status" value="1" @checked($enabled)
                                class="h-5 w-5 cursor-pointer rounded border-slate-300 text-primary focus:ring-primary/30">
                            <span class="text-sm font-medium text-slate-700">{{ $enabled ? 'Enabled' : 'Disabled' }}</span>
                        </label>
                        <x-ui.button type="submit" variant="primary">
                            <i class="fas fa-check text-xs"></i>
                            Save
                        </x-ui.button>
                    </div>
                </div>
            </form>
        </div>
    </section>

    {{-- Summary --}}
    <section class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Total saves</p>
            <p class="mt-1 text-2xl font-bold text-slate-900">{{ number_format($totalSaves) }}</p>
        </div>
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Customers saving</p>
            <p class="mt-1 text-2xl font-bold text-slate-900">{{ number_format($customers) }}</p>
        </div>
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Products wishlisted</p>
            <p class="mt-1 text-2xl font-bold text-slate-900">{{ number_format($productsWishlisted) }}</p>
        </div>
    </section>

    {{-- Most wishlisted products --}}
    <section class="mb-6">
        <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 px-5 py-4">
                <h2 class="text-base font-semibold text-slate-900">Most wishlisted products</h2>
                <p class="text-xs text-slate-500">What customers are saving the most</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-100 text-left text-xs uppercase tracking-wide text-slate-400">
                            <th class="px-5 py-3 font-medium">#</th>
                            <th class="px-5 py-3 font-medium">Product</th>
                            <th class="px-5 py-3 text-right font-medium">Saves</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($topProducts as $i => $item)
                            <tr class="hover:bg-slate-50">
                                <td class="px-5 py-3 text-slate-400">{{ $i + 1 }}</td>
                                <td class="px-5 py-3">
                                    @if ($item->product)
                                        <a href="{{ route('admin.product.show', $item->product->id) }}"
                                            class="font-medium text-slate-700 transition-colors hover:text-primary">
                                            {{ $item->product->title }}
                                        </a>
                                    @else
                                        <span class="italic text-slate-400">Deleted product #{{ $item->product_id }}</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3 text-right font-semibold text-slate-900">{{ number_format($item->saves) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-5 py-10 text-center text-slate-400">No wishlist activity yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>

@endsection
