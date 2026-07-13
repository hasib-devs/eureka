@extends('layouts.admin.app')

@section('title', 'Settings')

@section('content')

    <section class="mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Credential Settings</h1>
                <p class="mt-1 text-sm text-slate-500">Login &amp; registration options, mail server and SMS gateway</p>
            </div>
            <div class="flex items-center gap-3">
                <ol class="flex items-center gap-1 text-sm text-slate-400">
                    <li><a href="{{ routeHelper('dashboard') }}" class="transition-colors hover:text-primary">Home</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li><a href="{{ route('admin.setting') }}" class="transition-colors hover:text-primary">Settings</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li class="font-medium text-slate-600">Credentials</li>
                </ol>
            </div>
        </div>
    </section>

    @php
        $control = 'block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 shadow-sm transition-shadow focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20';
        $selectControl = $control . ' cursor-pointer';
    @endphp

    <section class="mb-6">
        <div class="mx-auto w-full max-w-3xl space-y-4">

            {{-- Login / registration options --}}
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center gap-3 border-b border-slate-200 px-5 py-4">
                    <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                        <i class="fas fa-user-lock"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-semibold text-slate-900">Login &amp; Registration</h2>
                        <p class="text-xs text-slate-500">How customers verify their account and recover access</p>
                    </div>
                </div>

                <form id="email_config" action="{{ routeHelper('setting') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="type" value="7">

                    <div class="grid grid-cols-1 gap-4 p-5 md:grid-cols-2">
                        <div>
                            <label for="regVerify" class="mb-1 block text-sm font-medium text-slate-700">Registration Verify With</label>
                            <select name="regVerify" id="regVerify" class="{{ $selectControl }}">
                                @if (setting('regVerify') == 'email')
                                    <option value="email">Only Email</option>
                                    <option value="sms">Only SMS</option>
                                @else
                                    <option value="sms">Only SMS</option>
                                    <option value="email">Only Email</option>
                                @endif
                            </select>
                            <p class="mt-1 text-xs text-slate-500">Currently selected: <span class="font-semibold text-primary">{{ Str::upper(setting('regVerify')) }}</span></p>
                        </div>
                        <div>
                            <label for="recovrAC" class="mb-1 block text-sm font-medium text-slate-700">Account Recover With</label>
                            <select name="recovrAC" id="recovrAC" class="{{ $selectControl }}">
                                @if (setting('recovrAC') == 'email')
                                    <option value="email">Only Email</option>
                                    <option value="sms">Only SMS</option>
                                    <option value="emailsms">Email & SMS both</option>
                                @elseif (setting('recovrAC') == 'sms')
                                    <option value="sms">Only SMS</option>
                                    <option value="email">Only Email</option>
                                    <option value="emailsms">Email & SMS both</option>
                                @else
                                    <option value="emailsms">Email & SMS both</option>
                                    <option value="email">Only Email</option>
                                    <option value="sms">Only SMS</option>
                                @endif
                            </select>
                            <p class="mt-1 text-xs text-slate-500">Currently selected: <span class="font-semibold text-primary">{{ Str::upper(setting('recovrAC')) }}</span></p>
                        </div>
                        <div>
                            <label for="google_login_status" class="mb-1 block text-sm font-medium text-slate-700">Google Login</label>
                            <select name="google_login_status" id="google_login_status" class="{{ $selectControl }}">
                                @if (setting('google_login_status') == '1')
                                    <option value="1">On</option>
                                    <option value="0">Off</option>
                                @else
                                    <option value="0">Off</option>
                                    <option value="1">On</option>
                                @endif
                            </select>
                            <p class="mt-1 text-xs text-slate-500">Show &ldquo;Continue with Google&rdquo; on login/register. Requires GOOGLE_CLIENT_ID and GOOGLE_CLIENT_SECRET in <code>.env</code>.</p>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2 border-t border-slate-200 px-5 py-4">
                        <x-ui.button type="submit" variant="primary">
                            <i class="fas fa-check text-xs"></i>
                            Save Changes
                        </x-ui.button>
                    </div>
                </form>
            </div>

            {{-- Mail configuration --}}
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center gap-3 border-b border-slate-200 px-5 py-4">
                    <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-semibold text-slate-900">Mail Configuration</h2>
                        <p class="text-xs text-slate-500">SMTP server used for transactional emails</p>
                    </div>
                </div>

                <form id="email_config" action="{{ routeHelper('setting') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="type" value="5">
                    <input type="hidden" name="MAIL_DRIVER" value="smtp">

                    <div class="space-y-4 p-5">
                        <div class="flex flex-wrap items-center justify-between gap-3 rounded-lg border border-slate-200 bg-slate-50/60 px-4 py-3">
                            <div>
                                <label for="mail_config" class="block text-sm font-medium text-slate-700">Mail Configuration</label>
                                <p class="mt-0.5 text-xs text-slate-500">Enable or disable outgoing mail</p>
                            </div>
                            <select name="mail_config" id="mail_config"
                                class="h-10 w-28 cursor-pointer rounded-lg border border-slate-300 bg-white px-3 text-sm text-slate-700 shadow-sm transition-shadow focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                                @if (setting('mail_config') == 1)
                                    <option value="1">ON</option>
                                    <option value="0">OFF</option>
                                @else
                                    <option value="0">OFF</option>
                                    <option value="1">ON</option>
                                @endif
                            </select>
                        </div>

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label for="MAIL_HOST" class="mb-1 block text-sm font-medium text-slate-700">Email Host</label>
                                <input type="text" name="MAIL_HOST" id="MAIL_HOST"
                                    value="{{ setting('MAIL_HOST') ?? '' }}" class="{{ $control }}">
                            </div>
                            <div>
                                <label for="MAIL_PORT" class="mb-1 block text-sm font-medium text-slate-700">Port</label>
                                <input type="text" name="MAIL_PORT" id="MAIL_PORT"
                                    value="{{ setting('MAIL_PORT') ?? '' }}" class="{{ $control }}">
                            </div>
                            <div>
                                <label for="MAIL_USERNAME" class="mb-1 block text-sm font-medium text-slate-700">Username</label>
                                <input type="text" name="MAIL_USERNAME" id="MAIL_USERNAME"
                                    value="{{ setting('MAIL_USERNAME') ?? '' }}" class="{{ $control }}">
                            </div>
                            <div>
                                <label for="MAIL_PASSWORD" class="mb-1 block text-sm font-medium text-slate-700">Password</label>
                                <input type="text" name="MAIL_PASSWORD" id="MAIL_PASSWORD"
                                    value="{{ setting('MAIL_PASSWORD') ?? '' }}" class="{{ $control }}">
                            </div>
                            <div>
                                <label for="MAIL_ENCRYPTION" class="mb-1 block text-sm font-medium text-slate-700">Encryption Type</label>
                                <select name="MAIL_ENCRYPTION" id="MAIL_ENCRYPTION" class="{{ $selectControl }}">
                                    @if (setting('MAIL_ENCRYPTION') == 'tls')
                                        <option value="tls">TLS</option>
                                        <option value="ssl">SSL</option>
                                    @else
                                        <option value="ssl">SSL</option>
                                        <option value="tls">TLS</option>
                                    @endif
                                </select>
                                <p class="mt-1 text-xs text-slate-500">Currently selected: <span class="font-semibold text-primary">{{ Str::upper(setting('MAIL_ENCRYPTION')) }}</span></p>
                            </div>
                            <div>
                                <label for="MAIL_FROM_ADDRESS" class="mb-1 block text-sm font-medium text-slate-700">Mail From</label>
                                <input type="email" name="MAIL_FROM_ADDRESS" id="MAIL_FROM_ADDRESS"
                                    value="{{ setting('MAIL_FROM_ADDRESS') ?? '' }}" class="{{ $control }}">
                            </div>
                            <div>
                                <label for="MAIL_FROM_NAME" class="mb-1 block text-sm font-medium text-slate-700">From Name</label>
                                <input type="text" name="MAIL_FROM_NAME" id="MAIL_FROM_NAME"
                                    value="{{ setting('MAIL_FROM_NAME') }}" class="{{ $control }}">
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2 border-t border-slate-200 px-5 py-4">
                        <x-ui.button type="submit" variant="primary">
                            <i class="fas fa-check text-xs"></i>
                            Save Changes
                        </x-ui.button>
                    </div>
                </form>
            </div>

            {{-- SMS configuration --}}
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 px-5 py-4">
                    <div class="flex items-center gap-3">
                        <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                            <i class="fas fa-sms"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-semibold text-slate-900">SMS Configuration</h2>
                            <p class="text-xs text-slate-500">Gateway used for OTP and notification SMS</p>
                        </div>
                    </div>
                    <a href="https://bulksmsbd.com/" target="_blank" rel="noopener noreferrer"
                        class="text-xs font-medium text-primary hover:underline">
                        Purchase SMS from bulksmsbd.com <i class="fas fa-external-link-alt text-[9px]"></i>
                    </a>
                </div>

                <form id="sms_config" action="{{ routeHelper('setting') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="type" value="6">

                    <div class="space-y-4 p-5">
                        <div class="flex flex-wrap items-center justify-between gap-3 rounded-lg border border-slate-200 bg-slate-50/60 px-4 py-3">
                            <div>
                                <label for="sms_config_status" class="block text-sm font-medium text-slate-700">SMS Configuration</label>
                                <p class="mt-0.5 text-xs text-slate-500">Enable or disable outgoing SMS</p>
                            </div>
                            <select name="sms_config_status" id="sms_config_status"
                                class="h-10 w-28 cursor-pointer rounded-lg border border-slate-300 bg-white px-3 text-sm text-slate-700 shadow-sm transition-shadow focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                                @if (setting('sms_config_status') == 1)
                                    <option value="1">ON</option>
                                    <option value="0">OFF</option>
                                @else
                                    <option value="0">OFF</option>
                                    <option value="1">ON</option>
                                @endif
                            </select>
                        </div>

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div class="md:col-span-2">
                                <label for="SMS_API_URL" class="mb-1 block text-sm font-medium text-slate-700">SMS API URL</label>
                                <input type="text" name="SMS_API_URL" id="SMS_API_URL"
                                    value="{{ setting('SMS_API_URL') ?? '' }}" class="{{ $control }}">
                            </div>
                            <div>
                                <label for="SMS_API_KEY" class="mb-1 block text-sm font-medium text-slate-700">SMS API Key</label>
                                <input type="text" name="SMS_API_KEY" id="SMS_API_KEY"
                                    value="{{ setting('SMS_API_KEY') ?? '' }}" class="{{ $control }}">
                            </div>
                            <div>
                                <label for="SMS_API_SENDER_ID" class="mb-1 block text-sm font-medium text-slate-700">Sender ID</label>
                                <input type="text" name="SMS_API_SENDER_ID" id="SMS_API_SENDER_ID"
                                    value="{{ setting('SMS_API_SENDER_ID') }}" class="{{ $control }}">
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2 border-t border-slate-200 px-5 py-4">
                        <x-ui.button type="submit" variant="primary">
                            <i class="fas fa-check text-xs"></i>
                            Save Changes
                        </x-ui.button>
                    </div>
                </form>
            </div>

        </div>
    </section>

@endsection
