@extends('layouts.admin.app')

@section('title', 'Settings')

@section('content')

    <section class="mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Site Information</h1>
                <p class="mt-1 text-sm text-slate-500">Company contact details, footer content and social profiles</p>
            </div>
            <div class="flex items-center gap-3">
                <x-ui.button variant="outline" :href="routeHelper('shop')">
                    Update Main Information
                    <i class="fas fa-caret-right"></i>
                </x-ui.button>
                <ol class="flex items-center gap-1 text-sm text-slate-400">
                    <li><a href="{{ routeHelper('dashboard') }}" class="transition-colors hover:text-primary">Home</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li><a href="{{ route('admin.setting') }}" class="transition-colors hover:text-primary">Settings</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li class="font-medium text-slate-600">Site Info</li>
                </ol>
            </div>
        </div>
    </section>

    @php
        $control = 'block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 shadow-sm transition-shadow focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20';
    @endphp

    <section class="mb-6">
        <form id="email_config" action="{{ routeHelper('setting') }}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="type" value="8">

            <div class="mx-auto w-full max-w-3xl space-y-4">

                {{-- Company details --}}
                <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex items-center gap-3 border-b border-slate-200 px-5 py-4">
                        <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                            <i class="fas fa-building"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-semibold text-slate-900">Company Details</h2>
                            <p class="text-xs text-slate-500">Shown in the storefront footer and contact sections</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 p-5 md:grid-cols-2">
                        <div>
                            <label for="SITE_INFO_ADDRESS" class="mb-1 block text-sm font-medium text-slate-700">Company Full Address
                                <span class="text-danger">*</span></label>
                            <input class="{{ $control }}" type="text" name="SITE_INFO_ADDRESS"
                                id="SITE_INFO_ADDRESS" placeholder="Company Full Address"
                                value="{{ $SITE_INFO_ADDRESS->value }}" required>
                        </div>

                        <div>
                            <label for="SITE_INFO_PHONE" class="mb-1 block text-sm font-medium text-slate-700">Company Primary Phone
                                <span class="text-danger">*</span></label>
                            <input class="{{ $control }}" type="text" name="SITE_INFO_PHONE"
                                id="SITE_INFO_PHONE" placeholder="Company Primary Phone"
                                value="{{ $SITE_INFO_PHONE->value }}" required>
                        </div>

                        <div>
                            <label for="SITE_INFO_SUPPORT_MAIL" class="mb-1 block text-sm font-medium text-slate-700">Company Support Mail
                                <span class="text-danger">*</span></label>
                            <input class="{{ $control }}" type="text" name="SITE_INFO_SUPPORT_MAIL"
                                id="SITE_INFO_SUPPORT_MAIL" placeholder="Company Support Email"
                                value="{{ $SITE_INFO_SUPPORT_MAIL->value }}" required>
                        </div>

                        <div>
                            <label for="copy_right_text" class="mb-1 block text-sm font-medium text-slate-700">Copyright Text
                                <span class="text-danger">*</span></label>
                            <input class="{{ $control }}" type="text" name="copy_right_text"
                                id="copy_right_text" placeholder="Company Support Email"
                                value="{{ setting('copy_right_text') ?? '© Lems Copyright' }}" required>
                        </div>

                        <div class="md:col-span-2">
                            <label for="footer_description" class="mb-1 block text-sm font-medium text-slate-700">Footer Description
                                <span class="text-danger">*</span></label>
                            <textarea name="footer_description" id="footer_description" rows="4"
                                class="{{ $control }}"
                                required>{{ setting('footer_description') ?? 'Footer Description, Example: This is Lems by Finvasoft' }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Contact & social --}}
                <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex items-center gap-3 border-b border-slate-200 px-5 py-4">
                        <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                            <i class="fas fa-share-alt"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-semibold text-slate-900">Contact &amp; Social Profiles</h2>
                            <p class="text-xs text-slate-500">Public email and social media links</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 p-5 md:grid-cols-2">
                        <div>
                            <label for="email" class="mb-1 block text-sm font-medium text-slate-700">E-mail
                                <span class="text-danger">*</span></label>
                            <input class="{{ $control }}" type="email" name="email" id="email"
                                placeholder="info@finvasoft.com"
                                value="{{ setting('email') ?? 'hello@asifulmamun.info.bd' }}" required>
                        </div>

                        <div>
                            <label for="whatsapp" class="mb-1 block text-sm font-medium text-slate-700">WhatsApp</label>
                            <input class="{{ $control }}" type="number" name="whatsapp" id="whatsapp"
                                placeholder="8801721600688"
                                value="{{ setting('whatsapp') ?? '01721*****88' }}">
                            <p class="mt-1 text-xs text-slate-500">With country code (without + sign)</p>
                        </div>

                        <div>
                            <label for="facebook" class="mb-1 block text-sm font-medium text-slate-700">Facebook</label>
                            <input class="{{ $control }}" type="text" name="facebook"
                                id="facebook" value="{{ setting('facebook') ?? '' }}">
                        </div>

                        <div>
                            <label for="messanger" class="mb-1 block text-sm font-medium text-slate-700">Messenger</label>
                            <input class="{{ $control }}" type="text" name="messanger"
                                id="messanger" value="{{ setting('messanger') ?? '' }}">
                        </div>

                        <div>
                            <label for="linkedin" class="mb-1 block text-sm font-medium text-slate-700">LinkedIn</label>
                            <input class="{{ $control }}" type="text" name="linkedin"
                                id="linkedin" value="{{ setting('linkedin') ?? '' }}">
                        </div>

                        <div>
                            <label for="twitter" class="mb-1 block text-sm font-medium text-slate-700">Twitter</label>
                            <input class="{{ $control }}" type="text" name="twitter" id="twitter"
                                value="{{ setting('twitter') ?? '' }}">
                        </div>

                        <div>
                            <label for="youtube" class="mb-1 block text-sm font-medium text-slate-700">YouTube</label>
                            <input class="{{ $control }}" type="text" name="youtube" id="youtube"
                                value="{{ setting('youtube') ?? '' }}">
                        </div>

                        <div>
                            <label for="instagram" class="mb-1 block text-sm font-medium text-slate-700">Instagram</label>
                            <input class="{{ $control }}" type="text" name="instagram"
                                id="instagram" value="{{ setting('instagram') ?? '' }}">
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2 border-t border-slate-200 px-5 py-4">
                        <x-ui.button type="submit" variant="primary">
                            <i class="fas fa-check text-xs"></i>
                            Save Changes
                        </x-ui.button>
                    </div>
                </div>

            </div>
        </form>
    </section>

@endsection
