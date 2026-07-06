@extends('layouts.admin.app')

@section('title', 'Ticket List')

@section('content')

    <section class="mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Support Tickets</h1>
                <p class="mt-1 text-sm text-slate-500">Review and reply to customer support tickets</p>
            </div>
            <div class="flex items-center gap-3">
                <ol class="flex items-center gap-1 text-sm text-slate-400">
                    <li><a href="{{ routeHelper('dashboard') }}" class="transition-colors hover:text-primary">Home</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li class="font-medium text-slate-600">Tickets</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="mb-6 space-y-4">

        @if (!empty(Session::get('massage2')))
            <x-ui.alert variant="success" class="text-center">
                {{ Session::get('massage2') }}
            </x-ui.alert>
        @endif

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-200 px-5 py-4">
                <h2 class="text-base font-semibold text-slate-900">All Tickets</h2>
                <span class="inline-flex items-center rounded-full bg-primary/10 px-2.5 py-1 text-xs font-semibold text-primary ring-1 ring-inset ring-primary/20">
                    {{ $tickets->count() }} {{ Str::plural('ticket', $tickets->count()) }}
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[720px] text-left text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50/80 text-[11px] font-semibold uppercase tracking-wider text-slate-400">
                            <th class="w-14 px-4 py-3.5">SL</th>
                            <th class="px-4 py-3.5">Username</th>
                            <th class="px-4 py-3.5">Subject</th>
                            <th class="px-4 py-3.5">Status</th>
                            <th class="px-4 py-3.5 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($tickets as $data)
                            <tr class="group transition-colors hover:bg-slate-50/80">
                                <td class="px-4 py-4 text-slate-500">{{ $loop->iteration }}</td>
                                <td class="px-4 py-4 font-semibold text-slate-800">{{ $data->user->username ?? '' }}</td>
                                <td class="px-4 py-4 text-slate-600">{{ $data->sub }}</td>
                                <td class="px-4 py-4">
                                    @if ($data->status == 0)
                                        <x-ui.badge variant="warning" dot class="whitespace-nowrap">Awaiting Reply</x-ui.badge>
                                    @else
                                        <x-ui.badge variant="success" dot class="whitespace-nowrap">Replied</x-ui.badge>
                                    @endif
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('admin.ticket.show', ['show' => $data->id]) }}"
                                            title="View & reply"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 shadow-sm transition-all hover:-translate-y-px hover:border-primary hover:bg-primary-50 hover:text-primary hover:shadow">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.ticket.delete', ['ticket' => $data->id]) }}"
                                            title="Delete ticket"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 shadow-sm transition-all hover:-translate-y-px hover:border-danger hover:bg-danger/10 hover:text-danger hover:shadow">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-20 text-center">
                                    <div class="mx-auto mb-4 grid h-14 w-14 place-items-center rounded-2xl bg-slate-50 ring-1 ring-slate-200">
                                        <i class="fas fa-ticket-alt text-xl text-slate-300"></i>
                                    </div>
                                    <p class="font-semibold text-slate-700">No tickets found</p>
                                    <p class="mt-1 text-sm text-slate-500">Customer support tickets will appear here.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </section>

@endsection
