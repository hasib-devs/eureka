@extends('layouts.admin.app')

@section('title', 'IP Block List')

@section('content')

    <section class="mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">IP Block Management</h1>
                <p class="mt-1 text-sm text-slate-500">Block abusive IP addresses and manage the current blocklist</p>
            </div>
            <div class="flex items-center gap-3">
                <ol class="flex items-center gap-1 text-sm text-slate-400">
                    <li><a href="{{ route('admin.dashboard') }}" class="transition-colors hover:text-primary">Home</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li class="font-medium text-slate-600">IP Block</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="mb-6 grid grid-cols-1 items-start gap-4 lg:grid-cols-3">

        {{-- Block form --}}
        <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center gap-3 border-b border-slate-200 px-5 py-4">
                <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                    <i class="fas fa-ban"></i>
                </div>
                <div>
                    <h2 class="text-base font-semibold text-slate-900">Block New IP</h2>
                    <p class="text-xs text-slate-500">Deny an IP address access to the site</p>
                </div>
            </div>
            <form action="{{ route('admin.ip_block.store') }}" method="POST" class="p-5">
                @csrf
                <div class="space-y-4">
                    <x-ui.input name="ip_address" label="IP Address" type="text"
                        placeholder="Enter IP Address" required />

                    <x-ui.textarea name="reason" label="Reason (Optional)"
                        placeholder="Reason for blocking" />
                </div>
                <div class="flex items-center justify-end gap-2 border-t border-slate-200 pt-4">
                    <x-ui.button type="submit" variant="primary">
                        <i class="fas fa-ban text-xs"></i>
                        Block IP
                    </x-ui.button>
                </div>
            </form>
        </div>

        {{-- Blocked IP list --}}
        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm lg:col-span-2">
            <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-200 px-5 py-4">
                <h2 class="text-base font-semibold text-slate-900">Blocked IP List</h2>
                <span class="inline-flex items-center rounded-full bg-primary/10 px-2.5 py-1 text-xs font-semibold text-primary ring-1 ring-inset ring-primary/20">
                    {{ $ipBlocks->total() }} {{ Str::plural('IP', $ipBlocks->total()) }}
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[640px] text-left text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50/80 text-[11px] font-semibold uppercase tracking-wider text-slate-400">
                            <th class="w-14 px-4 py-3.5">SL</th>
                            <th class="px-4 py-3.5">IP Address</th>
                            <th class="px-4 py-3.5">Reason</th>
                            <th class="px-4 py-3.5">Date</th>
                            <th class="px-4 py-3.5 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($ipBlocks as $key => $block)
                            <tr class="group transition-colors hover:bg-slate-50/80">
                                <td class="px-4 py-4 text-slate-500">{{ $ipBlocks->firstItem() + $key }}</td>
                                <td class="px-4 py-4 font-semibold tabular-nums text-slate-800">{{ $block->ip_address }}</td>
                                <td class="px-4 py-4 text-slate-500">{{ $block->reason }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-slate-500">{{ $block->created_at->format('d M Y, h:i A') }}</td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <form action="{{ route('admin.ip_block.destroy', $block->id) }}"
                                            method="POST" onsubmit="return confirm('Are you sure?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Unblock IP"
                                                class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 shadow-sm transition-all hover:-translate-y-px hover:border-danger hover:bg-danger/10 hover:text-danger hover:shadow">
                                                <i class="fas fa-undo"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-20 text-center">
                                    <div class="mx-auto mb-4 grid h-14 w-14 place-items-center rounded-2xl bg-slate-50 ring-1 ring-slate-200">
                                        <i class="fas fa-ban text-xl text-slate-300"></i>
                                    </div>
                                    <p class="font-semibold text-slate-700">No blocked IPs found</p>
                                    <p class="mt-1 text-sm text-slate-500">Use the form to block an IP address.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($ipBlocks->hasPages())
                <div class="border-t border-slate-200 px-5 py-4">
                    {{ $ipBlocks->appends(request()->query())->links('admin.partials.pagination') }}
                </div>
            @endif
        </div>

    </section>

@endsection
