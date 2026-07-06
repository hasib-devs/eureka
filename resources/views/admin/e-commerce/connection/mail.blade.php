@extends('layouts.admin.app')

@section('title', 'mails List')

@section('content')

    <section class="mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Contact Mails</h1>
                <p class="mt-1 text-sm text-slate-500">Messages submitted through the contact form</p>
            </div>
            <div class="flex items-center gap-3">
                <ol class="flex items-center gap-1 text-sm text-slate-400">
                    <li><a href="{{ routeHelper('dashboard') }}" class="transition-colors hover:text-primary">Home</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li class="font-medium text-slate-600">Mails</li>
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
                <h2 class="text-base font-semibold text-slate-900">All Mails</h2>
                <span class="inline-flex items-center rounded-full bg-primary/10 px-2.5 py-1 text-xs font-semibold text-primary ring-1 ring-inset ring-primary/20">
                    {{ $mails->count() }} {{ Str::plural('mail', $mails->count()) }}
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[640px] text-left text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50/80 text-[11px] font-semibold uppercase tracking-wider text-slate-400">
                            <th class="w-14 px-4 py-3.5">SL</th>
                            <th class="px-4 py-3.5">Name</th>
                            <th class="px-4 py-3.5">Subject</th>
                            <th class="px-4 py-3.5 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($mails as $data)
                            <tr class="group transition-colors hover:bg-slate-50/80">
                                <td class="px-4 py-4 text-slate-500">{{ $loop->iteration }}</td>
                                <td class="px-4 py-4 font-semibold text-slate-800">{{ $data->name }}</td>
                                <td class="px-4 py-4 text-slate-600">{{ $data->title }}</td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('admin.mail.show', ['id' => $data->id]) }}"
                                            title="View mail"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 shadow-sm transition-all hover:-translate-y-px hover:border-primary hover:bg-primary-50 hover:text-primary hover:shadow">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.mail.delete', ['id' => $data->id]) }}"
                                            title="Delete mail"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 shadow-sm transition-all hover:-translate-y-px hover:border-danger hover:bg-danger/10 hover:text-danger hover:shadow">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-20 text-center">
                                    <div class="mx-auto mb-4 grid h-14 w-14 place-items-center rounded-2xl bg-slate-50 ring-1 ring-slate-200">
                                        <i class="fas fa-envelope-open text-xl text-slate-300"></i>
                                    </div>
                                    <p class="font-semibold text-slate-700">No mails found</p>
                                    <p class="mt-1 text-sm text-slate-500">Contact form submissions will appear here.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </section>

@endsection
