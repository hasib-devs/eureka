@extends('layouts.admin.app')

@section('title', 'Incomplete Leads List')

@push('css')
    <style>
        /* animate-pulse uses cubic-bezier; original used linear — keep keyframe for exact parity */
        @keyframes badge-pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        .badge-new { animation: badge-pulse 2s linear infinite; }
    </style>
@endpush

@section('content')

    <section class="mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Incomplete Leads</h1>
                <p class="mt-1 text-sm text-slate-500">Track abandoned carts and convert them into orders</p>
            </div>
            <div class="flex items-center gap-3">
                <ol class="flex items-center gap-1 text-sm text-slate-400">
                    <li><a href="{{ routeHelper('dashboard') }}" class="transition-colors hover:text-primary">Home</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li class="font-medium text-slate-600">Incomplete Leads</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="mb-6 space-y-4">

        {{-- Stats --}}
        <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
            <x-ui.stat-tile
                variant="info"
                :value="$stats['total'] ?? 0"
                label="Total Leads"
                icon="fas fa-users"
            />
            <x-ui.stat-tile
                variant="warning"
                :value="$stats['active'] ?? 0"
                label="Active (Not Converted)"
                icon="fas fa-user-clock"
            />
            <x-ui.stat-tile
                variant="success"
                :value="$stats['converted'] ?? 0"
                label="Converted to Orders"
                icon="fas fa-check-circle"
            />
            <x-ui.stat-tile
                variant="primary"
                :value="number_format($stats['conversion_rate'] ?? 0, 1) . '%'"
                label="Conversion Rate"
                icon="fas fa-percentage"
            />
        </div>

        {{-- Filters --}}
        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm md:p-5">
            @php
                $control = 'h-10 w-full rounded-lg border border-slate-300 bg-white px-3 text-sm text-slate-700 shadow-sm transition-shadow focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20';
            @endphp

            <form action="{{ route('admin.incomplete.leads.index') }}" method="GET" class="grid grid-cols-1 items-end gap-3 md:grid-cols-12">
                <div class="md:col-span-4">
                    <label for="keyword" class="mb-1 block text-sm font-medium text-slate-700">Search</label>
                    <div class="relative">
                        <i class="fas fa-search pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-sm text-slate-400"></i>
                        <input
                            id="keyword"
                            name="keyword"
                            type="text"
                            value="{{ request('keyword') }}"
                            placeholder="Name or Phone"
                            class="{{ $control }} pl-9"
                        />
                    </div>
                </div>

                <div class="md:col-span-2">
                    <label for="converted" class="mb-1 block text-sm font-medium text-slate-700">Status</label>
                    <select id="converted" name="converted" class="{{ $control }}">
                        <option value="">All</option>
                        <option value="0" {{ request('converted') === '0' ? 'selected' : '' }}>Active (Not Converted)</option>
                        <option value="1" {{ request('converted') === '1' ? 'selected' : '' }}>Converted</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label for="from_date" class="mb-1 block text-sm font-medium text-slate-700">From Date</label>
                    <input id="from_date" name="from_date" type="date" value="{{ request('from_date') }}" class="{{ $control }}" />
                </div>

                <div class="md:col-span-2">
                    <label for="to_date" class="mb-1 block text-sm font-medium text-slate-700">To Date</label>
                    <input id="to_date" name="to_date" type="date" value="{{ request('to_date') }}" class="{{ $control }}" />
                </div>

                <div class="flex gap-2 md:col-span-2">
                    <button type="submit" class="inline-flex h-10 flex-1 items-center justify-center gap-2 rounded-lg bg-primary px-4 text-sm font-medium text-white shadow-sm transition-all hover:bg-primary-600 hover:shadow focus:outline-none focus-visible:ring-2 focus-visible:ring-primary/50 active:scale-[.98]">
                        <i class="fas fa-filter text-xs"></i> Filter
                    </button>
                    <a href="{{ route('admin.incomplete.leads.index') }}" title="Reset filters" class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-slate-300 bg-white text-slate-500 shadow-sm transition-all hover:-translate-y-px hover:border-slate-400 hover:text-slate-700 hover:shadow">
                        <i class="fas fa-undo"></i>
                    </a>
                </div>
            </form>
        </div>

        {{-- Lead list --}}
        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-200 px-4 py-4">
                <div class="flex flex-wrap items-center gap-2">
                    <h2 class="text-base font-semibold text-slate-900">Incomplete Lead Tracking</h2>
                    <span class="inline-flex items-center rounded-full bg-primary/10 px-2.5 py-1 text-xs font-semibold text-primary ring-1 ring-inset ring-primary/20">
                        {{ $leads->total() }} {{ Str::plural('lead', $leads->total()) }}
                    </span>
                </div>
                <a href="{{ route('admin.incomplete.leads.export') }}"
                    class="inline-flex h-9 items-center justify-center gap-2 rounded-lg bg-success px-3 text-sm font-medium text-white shadow-sm transition-all hover:opacity-90 hover:shadow focus:outline-none focus-visible:ring-2 focus-visible:ring-success/50 active:scale-[.98]">
                    <i class="fas fa-download text-xs"></i> Export CSV
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[1100px] text-left text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50/80 text-[11px] font-semibold uppercase tracking-wider text-slate-400">
                            <th class="w-14 px-4 py-3.5">SL</th>
                            <th class="px-4 py-3.5">Name</th>
                            <th class="px-4 py-3.5">Phone</th>
                            <th class="px-4 py-3.5">Email</th>
                            <th class="px-4 py-3.5">Cart Items</th>
                            <th class="px-4 py-3.5 text-right">Subtotal</th>
                            <th class="px-4 py-3.5">Last Activity</th>
                            <th class="px-4 py-3.5">Status</th>
                            <th class="px-4 py-3.5 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($leads as $key => $lead)
                            <tr class="group transition-colors hover:bg-slate-50/80 {{ !$lead->converted && $lead->last_activity > now()->subHours(1) ? '!bg-amber-100' : '' }}">
                                <td class="px-4 py-4 text-slate-500">{{ $leads->firstItem() + $key }}</td>
                                <td class="px-4 py-4">
                                    <span class="font-semibold text-slate-800">{{ $lead->name ?? 'N/A' }}</span>
                                    @if (!$lead->converted && $lead->last_activity > now()->subMinutes(30))
                                        <x-ui.badge variant="danger" class="badge-new ml-1">Active Now</x-ui.badge>
                                    @endif
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-2">
                                        <a href="tel:{{ $lead->phone }}" class="text-slate-700 transition-colors hover:text-primary">{{ $lead->phone ?? 'N/A' }}</a>
                                        @if ($lead->phone)
                                            <a href="tel:{{ $lead->phone }}" title="Call Now"
                                                class="inline-flex h-7 w-7 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 shadow-sm transition-all hover:-translate-y-px hover:border-success hover:bg-success/10 hover:text-success hover:shadow">
                                                <i class="fas fa-phone text-xs"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-slate-600">{{ $lead->email ?? 'N/A' }}</td>
                                <td class="px-4 py-4">
                                    @if ($lead->cart_data && count($lead->cart_data) > 0)
                                        <div class="max-w-[300px]" x-data="{ open: false }">
                                            <button type="button" @click="open = true"
                                                class="inline-flex h-8 items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-2.5 text-xs font-medium text-slate-600 shadow-sm transition-all hover:-translate-y-px hover:border-primary hover:bg-primary-50 hover:text-primary hover:shadow">
                                                <i class="fas fa-shopping-cart"></i>
                                                {{ $lead->total_items }} Item(s)
                                            </button>

                                            {{-- Cart Modal --}}
                                            <div
                                                x-show="open"
                                                x-cloak
                                                @keydown.escape.window="open = false"
                                                class="fixed inset-0 z-50 flex items-center justify-center p-4"
                                                id="cartModal{{ $lead->id }}"
                                            >
                                                <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-[2px]" @click="open = false"></div>
                                                <div class="relative z-10 flex max-h-[90vh] w-full max-w-3xl flex-col overflow-hidden rounded-2xl bg-white shadow-2xl">
                                                    <div class="flex items-center justify-between border-b border-slate-200 px-4 py-4">
                                                        <h5 class="font-semibold text-slate-900">Cart Items - {{ $lead->name }}</h5>
                                                        <button type="button" @click="open = false" class="text-slate-400 transition-colors hover:text-slate-600">
                                                            <i class="fas fa-times text-lg"></i>
                                                        </button>
                                                    </div>
                                                    <div class="overflow-y-auto p-5">
                                                        <table class="w-full text-left text-sm text-slate-700 [&_td]:border [&_td]:border-slate-200 [&_td]:px-3 [&_td]:py-1.5 [&_th]:border [&_th]:border-slate-200 [&_th]:bg-slate-50 [&_th]:px-3 [&_th]:py-1.5 [&_th]:font-medium">
                                                            <thead>
                                                                <tr>
                                                                    <th>Product</th>
                                                                    <th>Qty</th>
                                                                    <th>Price</th>
                                                                    <th>Total</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($lead->cart_data as $item)
                                                                    <tr>
                                                                        <td>
                                                                            @if (!empty($item['image']))
                                                                                <img src="{{ asset($item['image']) }}" alt="" class="mr-2 inline-block h-10 w-10 rounded-lg object-cover ring-1 ring-slate-200">
                                                                            @endif
                                                                            <strong>{{ $item['product_name'] ?? 'N/A' }}</strong>
                                                                        </td>
                                                                        <td>{{ $item['qty'] ?? 1 }}</td>
                                                                        <td>{{ number_format($item['price'] ?? 0, 2) }} TK</td>
                                                                        <td>{{ number_format($item['total'] ?? 0, 2) }} TK</td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                            <tfoot>
                                                                <tr>
                                                                    <th colspan="3" class="text-right">Subtotal:</th>
                                                                    <th>{{ number_format($lead->subtotal, 2) }} TK</th>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-slate-500">No items</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-right font-semibold tabular-nums text-slate-800">{{ number_format($lead->subtotal, 2) }} TK</td>
                                <td class="px-4 py-4">
                                    <p class="whitespace-nowrap text-slate-600">{{ $lead->last_activity ? $lead->last_activity->format('d M Y') : 'N/A' }}</p>
                                    <p class="text-xs text-slate-500">{{ $lead->last_activity ? $lead->last_activity->format('h:i A') : '' }}</p>
                                    @if ($lead->last_activity)
                                        <x-ui.badge variant="neutral" class="mt-1 whitespace-nowrap">{{ $lead->last_activity->diffForHumans() }}</x-ui.badge>
                                    @endif
                                </td>
                                <td class="px-4 py-4">
                                    @if ($lead->converted)
                                        <x-ui.badge variant="success" dot class="whitespace-nowrap">Converted</x-ui.badge>
                                    @else
                                        <x-ui.badge variant="warning" dot class="whitespace-nowrap">Pending</x-ui.badge>
                                    @endif
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center justify-end gap-1.5" x-data="{ open: false }">
                                        <button type="button" @click="open = true" title="View Details"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 shadow-sm transition-all hover:-translate-y-px hover:border-primary hover:bg-primary-50 hover:text-primary hover:shadow">
                                            <i class="fas fa-eye"></i>
                                        </button>

                                        @if (!$lead->converted)
                                            <a href="tel:{{ $lead->phone }}" title="Call Customer"
                                                class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 shadow-sm transition-all hover:-translate-y-px hover:border-success hover:bg-success/10 hover:text-success hover:shadow">
                                                <i class="fas fa-phone"></i>
                                            </a>

                                            <form action="{{ route('admin.incomplete.leads.delete', $lead->id) }}"
                                                method="POST" class="inline"
                                                onsubmit="return confirm('Are you sure you want to delete this lead?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" title="Delete"
                                                    class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 shadow-sm transition-all hover:-translate-y-px hover:border-danger hover:bg-danger/10 hover:text-danger hover:shadow">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif

                                        {{-- Detail Modal --}}
                                        <div
                                            x-show="open"
                                            x-cloak
                                            @keydown.escape.window="open = false"
                                            class="fixed inset-0 z-50 flex items-center justify-center p-4"
                                            id="detailModal{{ $lead->id }}"
                                        >
                                            <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-[2px]" @click="open = false"></div>
                                            <div class="relative z-10 flex max-h-[90vh] w-full max-w-3xl flex-col overflow-hidden rounded-2xl bg-white shadow-2xl">
                                                <div class="flex items-center justify-between border-b border-slate-200 px-4 py-4">
                                                    <h5 class="font-semibold text-slate-900">Lead Details - {{ $lead->name }}</h5>
                                                    <button type="button" @click="open = false" class="text-slate-400 transition-colors hover:text-slate-600">
                                                        <i class="fas fa-times text-lg"></i>
                                                    </button>
                                                </div>
                                                <div class="overflow-y-auto p-5 text-left">
                                                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                                        <div>
                                                            <h6 class="mb-2 text-sm font-semibold text-slate-700">Customer Information</h6>
                                                            <table class="w-full text-left text-sm text-slate-700 [&_td]:px-2 [&_td]:py-1 [&_th]:px-2 [&_th]:py-1 [&_th]:font-medium">
                                                                <tr>
                                                                    <th width="120">Name:</th>
                                                                    <td>{{ $lead->name ?? 'N/A' }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Phone:</th>
                                                                    <td>{{ $lead->phone ?? 'N/A' }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Email:</th>
                                                                    <td>{{ $lead->email ?? 'N/A' }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Address:</th>
                                                                    <td>{{ $lead->address ?? 'N/A' }}</td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-2 text-sm font-semibold text-slate-700">Lead Information</h6>
                                                            <table class="w-full text-left text-sm text-slate-700 [&_td]:px-2 [&_td]:py-1 [&_th]:px-2 [&_th]:py-1 [&_th]:font-medium">
                                                                <tr>
                                                                    <th width="140">IP Address:</th>
                                                                    <td>{{ $lead->ip_address ?? 'N/A' }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Page URL:</th>
                                                                    <td><small>{{ $lead->page_url ?? 'N/A' }}</small></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Session ID:</th>
                                                                    <td><small>{{ $lead->session_id }}</small></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Created At:</th>
                                                                    <td>{{ $lead->created_at->format('d M Y h:i A') }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Last Activity:</th>
                                                                    <td>{{ $lead->last_activity ? $lead->last_activity->format('d M Y h:i A') : 'N/A' }}</td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </div>

                                                    @if ($lead->cart_data && count($lead->cart_data) > 0)
                                                        <hr class="my-3 border-slate-200">
                                                        <h6 class="mb-2 text-sm font-semibold text-slate-700">Cart Items</h6>
                                                        <table class="w-full text-left text-sm text-slate-700 [&_td]:border [&_td]:border-slate-200 [&_td]:px-3 [&_td]:py-1.5 [&_th]:border [&_th]:border-slate-200 [&_th]:bg-slate-50 [&_th]:px-3 [&_th]:py-1.5 [&_th]:font-medium">
                                                            <thead>
                                                                <tr>
                                                                    <th>Product</th>
                                                                    <th width="80">Qty</th>
                                                                    <th width="100">Price</th>
                                                                    <th width="100">Total</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($lead->cart_data as $item)
                                                                    <tr>
                                                                        <td>{{ $item['product_name'] ?? 'N/A' }}</td>
                                                                        <td>{{ $item['qty'] ?? 1 }}</td>
                                                                        <td>{{ number_format($item['price'] ?? 0, 2) }} TK</td>
                                                                        <td>{{ number_format($item['total'] ?? 0, 2) }} TK</td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                            <tfoot>
                                                                <tr>
                                                                    <th colspan="3" class="text-right">Subtotal:</th>
                                                                    <th>{{ number_format($lead->subtotal, 2) }}</th>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                    @endif
                                                </div>
                                                <div class="flex justify-end gap-2 border-t border-slate-200 px-4 py-4">
                                                    @if (!$lead->converted && $lead->phone)
                                                        <a href="tel:{{ $lead->phone }}"
                                                            class="inline-flex h-9 items-center justify-center gap-2 rounded-lg bg-success px-3 text-sm font-medium text-white shadow-sm transition-all hover:opacity-90 hover:shadow active:scale-[.98]">
                                                            <i class="fas fa-phone text-xs"></i> Call Now
                                                        </a>
                                                    @endif
                                                    <button type="button" @click="open = false"
                                                        class="inline-flex h-9 items-center justify-center rounded-lg border border-slate-300 bg-white px-3 text-sm font-medium text-slate-600 shadow-sm transition-all hover:border-slate-400 hover:text-slate-700 hover:shadow">
                                                        Close
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-5 py-20 text-center">
                                    <div class="mx-auto mb-4 grid h-14 w-14 place-items-center rounded-2xl bg-slate-50 ring-1 ring-slate-200">
                                        <i class="fas fa-inbox text-xl text-slate-300"></i>
                                    </div>
                                    <p class="font-semibold text-slate-700">No incomplete leads found</p>
                                    <p class="mt-1 text-sm text-slate-500">
                                        Try adjusting your search or
                                        <a href="{{ route('admin.incomplete.leads.index') }}" class="text-primary hover:underline">reset the filters</a>.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($leads->hasPages())
                <div class="border-t border-slate-200 px-4 py-4">
                    {{ $leads->appends(request()->query())->links('admin.partials.pagination') }}
                </div>
            @endif
        </div>
    </section>

@endsection

@push('js')
    <script>
        $(document).ready(function() {
            // Auto refresh every 2 minutes for active leads
            @if (request('converted') === '0' || !request('converted'))
                setInterval(function() {
                    location.reload();
                }, 120000); // 2 minutes
            @endif

            // Highlight new leads
            $('.badge-new').each(function() {
                $(this).closest('tr').addClass('!bg-amber-100');
            });
        });
    </script>
@endpush
