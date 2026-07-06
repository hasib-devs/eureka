@extends('layouts.admin.app')

@section('title', 'Ticket List')

@section('content')

    <section class="mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Ticket Conversation</h1>
                <p class="mt-1 text-sm text-slate-500">Review the ticket and send a reply to the customer</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.ticket') }}"
                    class="inline-flex h-10 items-center justify-center gap-2 rounded-lg border border-slate-300 bg-white px-4 text-sm font-medium text-slate-600 shadow-sm transition-all hover:-translate-y-px hover:border-slate-400 hover:text-slate-800 hover:shadow">
                    <i class="fas fa-long-arrow-alt-left text-xs"></i> Back to List
                </a>
                <ol class="flex items-center gap-1 text-sm text-slate-400">
                    <li><a href="{{ routeHelper('dashboard') }}" class="transition-colors hover:text-primary">Home</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li class="font-medium text-slate-600">Ticket</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="mb-6">
        <div class="mx-auto max-w-3xl overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

            {{-- Thread header --}}
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 px-5 py-4">
                <div class="flex items-center gap-3">
                    <span class="grid h-10 w-10 place-items-center rounded-full bg-primary/10 text-sm font-semibold uppercase text-primary">
                        {{ mb_substr($tickets->user->username ?? '?', 0, 1) }}
                    </span>
                    <div>
                        <h2 class="text-base font-semibold text-slate-900">{{ $tickets->user->username ?? 'Unknown user' }}</h2>
                        <p class="text-xs text-slate-500">{{ $tickets->sub }}</p>
                    </div>
                </div>
                @if ($tickets->status == 0)
                    <x-ui.badge variant="warning" dot class="whitespace-nowrap">Awaiting Reply</x-ui.badge>
                @else
                    <x-ui.badge variant="success" dot class="whitespace-nowrap">Replied</x-ui.badge>
                @endif
            </div>

            {{-- Conversation --}}
            <div class="space-y-5 bg-slate-50/50 px-5 py-6">

                {{-- Customer message --}}
                <div class="flex items-end gap-2.5">
                    <span class="grid h-8 w-8 shrink-0 place-items-center rounded-full bg-slate-200 text-xs font-semibold uppercase text-slate-600">
                        {{ mb_substr($tickets->user->username ?? '?', 0, 1) }}
                    </span>
                    <div class="max-w-[75%]">
                        <div class="rounded-2xl rounded-bl-md bg-white px-4 py-3 text-sm leading-relaxed text-slate-700 shadow-sm ring-1 ring-slate-200">
                            {{ $tickets->body }}
                        </div>
                        <p class="mt-1 text-[11px] text-slate-400">
                            {{ $tickets->user->username ?? '' }}
                            @if ($tickets->created_at)
                                · {{ $tickets->created_at->format('d M Y, h:i A') }}
                            @endif
                        </p>
                    </div>
                </div>

                {{-- Admin reply --}}
                @if (!empty($tickets->reply))
                    <div class="flex justify-end">
                        <div class="max-w-[75%]">
                            <div class="rounded-2xl rounded-br-md bg-primary px-4 py-3 text-sm leading-relaxed text-white shadow-sm">
                                {{ $tickets->reply }}
                            </div>
                            <p class="mt-1 text-right text-[11px] text-slate-400">
                                Admin
                                @if ($tickets->updated_at)
                                    · {{ $tickets->updated_at->format('d M Y, h:i A') }}
                                @endif
                            </p>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Reply composer --}}
            <form action="{{ route('admin.ticket.update') }}" method="POST" class="border-t border-slate-200 px-5 py-4">
                @csrf
                <label for="replay" class="mb-1 block text-sm font-medium text-slate-700">Admin reply:</label>
                <textarea id="replay" cols="5" name="replay" placeholder="Write  Replay"
                    rows="4"
                    class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-700 shadow-sm transition-shadow focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20"></textarea>
                <input type="hidden" value="{{ $tickets->id }}" name="gurdmen">
                <input type="hidden" name="name" value="{{ $tickets->user->username ?? '' }}">

                <div class="mt-4 flex items-center justify-end gap-2 border-t border-slate-200 pt-4">
                    <button type="submit"
                        class="inline-flex h-10 items-center justify-center gap-2 rounded-lg bg-primary px-5 text-sm font-medium text-white shadow-sm transition-all hover:bg-primary-600 hover:shadow focus:outline-none focus-visible:ring-2 focus-visible:ring-primary/50 active:scale-[.98]">
                        <i class="fas fa-paper-plane text-xs"></i> Send Reply
                    </button>
                </div>
            </form>

        </div>
    </section>

@endsection
