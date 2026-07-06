@extends('layouts.admin.app')

@section('title', 'mails List')

@section('content')

    <section class="mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Mail Details</h1>
                <p class="mt-1 text-sm text-slate-500">Contact form message from {{ $mail->name }}</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.mail') }}"
                    class="inline-flex h-10 items-center justify-center gap-2 rounded-lg border border-slate-300 bg-white px-4 text-sm font-medium text-slate-600 shadow-sm transition-all hover:-translate-y-px hover:border-slate-400 hover:text-slate-800 hover:shadow">
                    <i class="fas fa-long-arrow-alt-left text-xs"></i> Back to List
                </a>
                <ol class="flex items-center gap-1 text-sm text-slate-400">
                    <li><a href="{{ routeHelper('dashboard') }}" class="transition-colors hover:text-primary">Home</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li class="font-medium text-slate-600">Mail Details</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="mb-6">
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">

            {{-- Message --}}
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm lg:col-span-2">
                <div class="flex items-center gap-3 border-b border-slate-200 px-5 py-4">
                    <span class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                        <i class="fas fa-envelope-open"></i>
                    </span>
                    <div>
                        <h3 class="text-base font-semibold text-slate-900">{{ $mail->title }}</h3>
                        <p class="text-xs text-slate-500">Subject</p>
                    </div>
                </div>
                <div class="px-5 py-4">
                    <p class="text-sm leading-relaxed text-slate-700">{{ $mail->body }}</p>
                </div>

                @if (!empty($mail->documents))
                    <div class="border-t border-slate-200 px-5 py-4">
                        <p class="mb-2 text-xs font-semibold uppercase tracking-wider text-slate-400">Photo</p>
                        <img src="{{ asset('/') }}uploads/contact/{{ $mail->documents }}"
                            alt="Mail attachment"
                            class="max-h-96 max-w-full rounded-xl object-contain ring-1 ring-slate-200">
                    </div>
                @endif
            </div>

            {{-- Sender --}}
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center gap-3 border-b border-slate-200 px-5 py-4">
                    <span class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                        <i class="fas fa-user"></i>
                    </span>
                    <h3 class="text-base font-semibold text-slate-900">Sender</h3>
                </div>
                <dl class="divide-y divide-slate-100 px-5 py-2">
                    <div class="flex justify-between gap-4 py-2 text-sm">
                        <dt class="text-slate-500">Name</dt>
                        <dd class="text-right font-medium text-slate-800">{{ $mail->name }}</dd>
                    </div>
                    <div class="flex justify-between gap-4 py-2 text-sm">
                        <dt class="text-slate-500">Email</dt>
                        <dd class="text-right font-medium text-slate-800">{{ $mail->email }}</dd>
                    </div>
                    <div class="flex justify-between gap-4 py-2 text-sm">
                        <dt class="text-slate-500">Phone</dt>
                        <dd class="text-right font-medium text-slate-800">{{ $mail->phone }}</dd>
                    </div>
                    @if (!empty($mail->meet))
                        <div class="flex justify-between gap-4 py-2 text-sm">
                            <dt class="text-slate-500">Meet Time</dt>
                            <dd class="text-right font-medium text-slate-800">{{ $mail->meet }}</dd>
                        </div>
                    @endif
                </dl>
            </div>

        </div>
    </section>

@endsection
