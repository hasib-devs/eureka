@extends('layouts.admin.app')

@section('title', 'Vendor List')

@section('content')

    <section class="mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Vendors</h1>
                <p class="mt-1 text-sm text-slate-500">Manage vendor accounts, shops and balances</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ routeHelper('vendor/create') }}"
                    class="inline-flex h-10 items-center justify-center gap-2 rounded-lg bg-primary px-4 text-sm font-medium text-white shadow-sm transition-all hover:bg-primary-600 hover:shadow focus:outline-none focus-visible:ring-2 focus-visible:ring-primary/50 active:scale-[.98]">
                    <i class="fas fa-plus text-xs"></i>
                    Add Vendor
                </a>
                <ol class="flex items-center gap-1 text-sm text-slate-400">
                    <li><a href="{{ routeHelper('dashboard') }}" class="transition-colors hover:text-primary">Home</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li class="font-medium text-slate-600">Vendors</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="mb-6">

        <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-200 px-5 py-4">
                <h2 class="text-base font-semibold text-slate-900">All Vendors</h2>
                <span class="inline-flex items-center rounded-full bg-primary/10 px-2.5 py-1 text-xs font-semibold text-primary ring-1 ring-inset ring-primary/20">
                    {{ $vendors->total() }} {{ Str::plural('vendor', $vendors->total()) }}
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[1000px] text-left text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50/80 text-[11px] font-semibold uppercase tracking-wider text-slate-400">
                            <th class="w-14 px-4 py-3.5">SL</th>
                            <th class="px-4 py-3.5">Name</th>
                            <th class="px-4 py-3.5">Email</th>
                            <th class="px-4 py-3.5">Phone</th>
                            <th class="px-4 py-3.5">Status</th>
                            <th class="px-4 py-3.5 text-right">Amount</th>
                            <th class="px-4 py-3.5 text-right">Pending</th>
                            <th class="px-4 py-3.5 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($vendors as $key => $data)
                            <tr class="group transition-colors hover:bg-slate-50/80">
                                <td class="px-4 py-4 text-slate-500">{{ $vendors->firstItem() + $key }}</td>
                                <td class="px-4 py-4">
                                    <p class="font-semibold text-slate-800">{{ $data->name }}</p>
                                    <p class="mt-0.5 text-slate-500">{{ '@' . $data->username }}</p>
                                </td>
                                <td class="px-4 py-4 text-slate-600">{{ $data->email }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-slate-600">{{ $data->phone }}</td>
                                <td class="px-4 py-4">
                                    @if ($data->is_approved == 1)
                                        <x-ui.badge variant="success" dot>Active</x-ui.badge>
                                    @else
                                        <x-ui.badge variant="danger" dot>Disabled</x-ui.badge>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-right font-semibold tabular-nums text-slate-800">{{ $data->vendorAccount->amount ?? 0 }}</td>
                                <td class="px-4 py-4 text-right tabular-nums text-slate-500">{{ $data->vendorAccount->pending_amount ?? 0 }}</td>
                                <td class="relative px-4 py-4">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ routeHelper('user/status/' . $data->id) }}"
                                            title="{{ $data->is_approved == 1 ? 'Disable' : 'Activate' }}"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 shadow-sm transition-all hover:-translate-y-px hover:border-primary hover:bg-primary-50 hover:text-primary hover:shadow">
                                            <i class="fas {{ $data->is_approved == 1 ? 'fa-lock-open' : 'fa-lock' }}"></i>
                                        </a>
                                        <a href="{{ route('admin.vendor.product', ['vid' => $data->id]) }}"
                                            title="Vendor products"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 shadow-sm transition-all hover:-translate-y-px hover:border-primary hover:bg-primary-50 hover:text-primary hover:shadow">
                                            <i class="fas fa-store"></i>
                                        </a>
                                        <a href="{{ routeHelper('vendor/' . $data->id) }}"
                                            title="View vendor"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 shadow-sm transition-all hover:-translate-y-px hover:border-primary hover:bg-primary-50 hover:text-primary hover:shadow">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ routeHelper('vendor/' . $data->id . '/edit') }}"
                                            title="Edit vendor"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 shadow-sm transition-all hover:-translate-y-px hover:border-primary hover:bg-primary-50 hover:text-primary hover:shadow">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="javascript:void(0)" data-id="{{ $data->id }}" id="deleteData"
                                            title="Delete vendor"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 shadow-sm transition-all hover:-translate-y-px hover:border-danger hover:bg-danger/10 hover:text-danger hover:shadow">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                        <form id="delete-data-form-{{ $data->id }}"
                                            action="{{ routeHelper('vendor/' . $data->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        <button type="button" onclick="action_vn({{ $data->id }})"
                                            title="More actions"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 shadow-sm transition-all hover:-translate-y-px hover:border-primary hover:bg-primary-50 hover:text-primary hover:shadow">
                                            <i class="fas fa-ellipsis-h"></i>
                                        </button>
                                    </div>
                                    <div id="action_apply_vn_{{ $data->id }}"
                                        class="absolute right-4 top-full z-20 -mt-2 hidden w-44 rounded-lg border border-slate-200 bg-white p-1 shadow-lg">
                                        <a href="{{ route('admin.vendor.change_pass_index', ['id' => $data->id]) }}"
                                            class="block rounded-lg px-3 py-2 text-sm text-slate-700 transition-colors hover:bg-primary-50 hover:text-primary">
                                            <i class="fas fa-key mr-1.5 text-xs text-slate-400"></i>
                                            Change Password
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-5 py-20 text-center">
                                    <div class="mx-auto mb-4 grid h-14 w-14 place-items-center rounded-2xl bg-slate-50 ring-1 ring-slate-200">
                                        <i class="fas fa-store text-xl text-slate-300"></i>
                                    </div>
                                    <p class="font-semibold text-slate-700">No vendors found</p>
                                    <p class="mt-1 text-sm text-slate-500">
                                        Get started by
                                        <a href="{{ routeHelper('vendor/create') }}" class="text-primary hover:underline">adding your first vendor</a>.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($vendors->hasPages())
                <div class="border-t border-slate-200 px-5 py-4">
                    {{ $vendors->appends(request()->query())->links('admin.partials.pagination') }}
                </div>
            @endif
        </div>

    </section>

@endsection

@push('js')
    <script>
        function action_vn(data_id) {
            $(`#action_apply_vn_${data_id}`).toggle();
        }
    </script>
@endpush
