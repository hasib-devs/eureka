@extends('layouts.admin.app')

@section('title', 'Tracking & Integrations')

@section('content')

    <section class="mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Tracking &amp; Integrations</h1>
                <p class="mt-1 text-sm text-slate-500">Meta Pixel &amp; Conversions API, Google Tag Manager, GA4, Search Console, Consent Mode</p>
            </div>
            <ol class="flex items-center gap-1 text-sm text-slate-400">
                <li><a href="{{ routeHelper('dashboard') }}" class="transition-colors hover:text-primary">Home</a></li>
                <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                <li class="font-medium text-slate-600">Settings</li>
                <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                <li class="font-medium text-slate-600">Tracking</li>
            </ol>
        </div>
    </section>

    @php
        $control = 'block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 shadow-sm transition-shadow focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20';
        $hint = 'mt-1 text-xs text-slate-500';
        $label = 'mb-1 block text-sm font-medium text-slate-700';
    @endphp

    <section class="mb-6">
        <div class="mx-auto w-full max-w-4xl space-y-4">

            @if (session('success'))
                <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <ul class="list-inside list-disc space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Conflict: the legacy raw snippet still holds a Pixel ID. Enabling
                 Meta below without clearing it double-counts every PageView. --}}
            @if ($legacyPixelId)
                <div class="rounded-lg border border-amber-300 bg-amber-50 px-4 py-3">
                    <p class="text-sm font-semibold text-amber-900">
                        <i class="fas fa-triangle-exclamation"></i> Conflict: a Pixel is still installed the old way
                    </p>
                    <p class="mt-1 text-sm text-amber-800">
                        The <strong>Custom Header Code &rarr; Facebook Pixel Code</strong> field still contains Pixel ID
                        <code class="rounded bg-amber-100 px-1 font-mono">{{ $legacyPixelId }}</code>.
                        If you enable Meta below with the same ID, <strong>PageView will fire twice and be counted twice</strong> —
                        the old snippet has no event ID, so it cannot be de-duplicated.
                    </p>
                    <form action="{{ route('admin.setting.tracking.import-legacy') }}" method="POST" class="mt-3">
                        @csrf
                        <button type="submit"
                            class="rounded-lg bg-amber-600 px-4 py-2 text-xs font-semibold text-white transition hover:bg-amber-700">
                            Import ID {{ $legacyPixelId }} &amp; clear the old snippet
                        </button>
                    </form>
                </div>
            @endif

            {{-- A stale site_url after a domain migration silently mis-attributes
                 every event, so surface it rather than let it pass unnoticed. --}}
            @if ($hostMismatch)
                <div class="rounded-lg border border-red-300 bg-red-50 px-4 py-3">
                    <p class="text-sm font-semibold text-red-900">
                        <i class="fas fa-globe"></i> Site URL does not match this domain
                    </p>
                    <p class="mt-1 text-sm text-red-800">
                        You are on <code class="rounded bg-red-100 px-1 font-mono">{{ $requestHost }}</code>
                        but Site URL is set to <code class="rounded bg-red-100 px-1 font-mono">{{ $tracking->site_url }}</code>.
                        Events are being attributed to the old domain. Update Site URL below, then complete the
                        Meta/Google-side steps in <code>TRACKING.md</code>.
                    </p>
                </div>
            @endif

            @unless ($usingDedicatedKey)
                <div class="rounded-lg border border-slate-200 bg-slate-50 px-4 py-3 text-xs text-slate-600">
                    <i class="fas fa-key"></i>
                    Secrets are encrypted with <code>APP_KEY</code>. For key isolation, add
                    <code>SETTINGS_ENCRYPTION_KEY</code> to <code>.env</code> (generate with
                    <code>php artisan tracking:key</code>) and re-paste the secrets below. Optional — everything works as is.
                </div>
            @endunless

            <form action="{{ route('admin.setting.tracking.save') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                {{-- ─── Meta ──────────────────────────────────────────────── --}}
                <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 px-5 py-4">
                        <div class="flex items-center gap-3">
                            <div class="grid h-9 w-9 place-items-center rounded-lg bg-[#0866FF]/10 text-[#0866FF]">
                                <i class="fab fa-facebook-f"></i>
                            </div>
                            <div>
                                <h2 class="text-base font-semibold text-slate-900">Meta Pixel &amp; Conversions API</h2>
                                <p class="text-xs text-slate-500">Browser pixel + server-side events, de-duplicated</p>
                            </div>
                        </div>
                        <label class="inline-flex cursor-pointer items-center gap-2">
                            <input type="checkbox" name="meta_enabled" value="1" @checked($tracking->meta_enabled)
                                class="h-4 w-4 rounded border-slate-300 text-primary focus:ring-primary/30">
                            <span class="text-sm font-medium text-slate-700">Enabled</span>
                        </label>
                    </div>

                    <div class="space-y-4 p-5">
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label for="meta_pixel_id" class="{{ $label }}">Pixel ID</label>
                                <input type="text" name="meta_pixel_id" id="meta_pixel_id" class="{{ $control }}"
                                    value="{{ old('meta_pixel_id', $tracking->meta_pixel_id) }}" placeholder="1234567890123456">
                                <p class="{{ $hint }}">Events Manager &rarr; Data Sources &rarr; your pixel.</p>
                            </div>
                            <div>
                                <label for="meta_api_version" class="{{ $label }}">Graph API version</label>
                                <input type="text" name="meta_api_version" id="meta_api_version" class="{{ $control }}"
                                    value="{{ old('meta_api_version', $tracking->meta_api_version ?: 'v25.0') }}" placeholder="v25.0">
                                <p class="{{ $hint }}">Meta expires old versions roughly yearly — bump this when they do.</p>
                            </div>
                        </div>

                        <div>
                            <label for="meta_access_token" class="{{ $label }}">
                                Conversions API access token
                                @if ($hasMetaToken)
                                    <span class="ml-1 rounded-full bg-emerald-50 px-2 py-0.5 text-[10px] font-medium text-emerald-700">saved</span>
                                @endif
                            </label>
                            {{-- Write-only: a stored token is never rendered back. --}}
                            <input type="password" name="meta_access_token" id="meta_access_token" class="{{ $control }}"
                                autocomplete="new-password"
                                placeholder="{{ $hasMetaToken ? 'Saved — leave blank to keep unchanged' : 'Paste the token from Events Manager' }}">
                            <p class="{{ $hint }}">Stored encrypted. Never shown again and never sent to the browser. Leave blank to keep the saved value.</p>
                            @if ($hasMetaToken)
                                {{-- Blank means "unchanged", so revoking a leaked token needs its own control. --}}
                                <label class="mt-2 inline-flex cursor-pointer items-center gap-2">
                                    <input type="checkbox" name="clear_meta_access_token" value="1"
                                        class="h-3.5 w-3.5 rounded border-slate-300 text-red-600 focus:ring-red-300">
                                    <span class="text-xs text-red-600">Clear the saved token (revoke it in Events Manager too)</span>
                                </label>
                            @endif
                        </div>

                        <div>
                            <label for="meta_test_event_code" class="{{ $label }}">Test event code <span class="font-normal text-slate-400">(optional)</span></label>
                            <input type="text" name="meta_test_event_code" id="meta_test_event_code" class="{{ $control }}"
                                value="{{ old('meta_test_event_code', $tracking->meta_test_event_code) }}" placeholder="TEST12345">
                            <p class="{{ $hint }}">While set, events appear in Events Manager &rarr; Test Events. <strong>Clear it before going live.</strong></p>
                        </div>

                        <div class="flex items-center gap-3 border-t border-slate-100 pt-4">
                            <button type="button" data-test="meta"
                                class="rounded-lg border border-slate-300 px-4 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-50">
                                <i class="fas fa-plug"></i> Test connection
                            </button>
                            <span data-result="meta" class="text-xs"></span>
                        </div>
                    </div>
                </div>

                {{-- ─── GTM ───────────────────────────────────────────────── --}}
                <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 px-5 py-4">
                        <div class="flex items-center gap-3">
                            <div class="grid h-9 w-9 place-items-center rounded-lg bg-blue-50 text-blue-600">
                                <i class="fas fa-tags"></i>
                            </div>
                            <div>
                                <h2 class="text-base font-semibold text-slate-900">Google Tag Manager</h2>
                                <p class="text-xs text-slate-500">Container loaded on every storefront page</p>
                            </div>
                        </div>
                        <label class="inline-flex cursor-pointer items-center gap-2">
                            <input type="checkbox" name="gtm_enabled" value="1" @checked($tracking->gtm_enabled)
                                class="h-4 w-4 rounded border-slate-300 text-primary focus:ring-primary/30">
                            <span class="text-sm font-medium text-slate-700">Enabled</span>
                        </label>
                    </div>

                    <div class="space-y-4 p-5">
                        <div>
                            <label for="gtm_container_id" class="{{ $label }}">Container ID</label>
                            <input type="text" name="gtm_container_id" id="gtm_container_id" class="{{ $control }}"
                                value="{{ old('gtm_container_id', $tracking->gtm_container_id) }}" placeholder="GTM-XXXXXXX">
                        </div>
                        <div class="flex items-center gap-3 border-t border-slate-100 pt-4">
                            <button type="button" data-test="gtm"
                                class="rounded-lg border border-slate-300 px-4 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-50">
                                <i class="fas fa-plug"></i> Test connection
                            </button>
                            <span data-result="gtm" class="text-xs"></span>
                        </div>
                    </div>
                </div>

                {{-- ─── GA4 ───────────────────────────────────────────────── --}}
                <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 px-5 py-4">
                        <div class="flex items-center gap-3">
                            <div class="grid h-9 w-9 place-items-center rounded-lg bg-amber-50 text-amber-600">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div>
                                <h2 class="text-base font-semibold text-slate-900">Google Analytics 4</h2>
                                <p class="text-xs text-slate-500">Browser tags + server-side Measurement Protocol</p>
                            </div>
                        </div>
                        <label class="inline-flex cursor-pointer items-center gap-2">
                            <input type="checkbox" name="ga4_enabled" value="1" @checked($tracking->ga4_enabled)
                                class="h-4 w-4 rounded border-slate-300 text-primary focus:ring-primary/30">
                            <span class="text-sm font-medium text-slate-700">Enabled</span>
                        </label>
                    </div>

                    <div class="space-y-4 p-5">
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label for="ga4_measurement_id" class="{{ $label }}">Measurement ID</label>
                                <input type="text" name="ga4_measurement_id" id="ga4_measurement_id" class="{{ $control }}"
                                    value="{{ old('ga4_measurement_id', $tracking->ga4_measurement_id) }}" placeholder="G-XXXXXXXXXX">
                                <p class="{{ $hint }}">GA4 Admin &rarr; Data Streams &rarr; your web stream.</p>
                            </div>
                            <div>
                                <label for="ga4_api_secret" class="{{ $label }}">
                                    Measurement Protocol API secret
                                    @if ($hasGa4Secret)
                                        <span class="ml-1 rounded-full bg-emerald-50 px-2 py-0.5 text-[10px] font-medium text-emerald-700">saved</span>
                                    @endif
                                </label>
                                <input type="password" name="ga4_api_secret" id="ga4_api_secret" class="{{ $control }}"
                                    autocomplete="new-password"
                                    placeholder="{{ $hasGa4Secret ? 'Saved — leave blank to keep unchanged' : 'Data Streams → Measurement Protocol API secrets' }}">
                                <p class="{{ $hint }}">Stored encrypted. Required only for server-side events.</p>
                                @if ($hasGa4Secret)
                                    <label class="mt-2 inline-flex cursor-pointer items-center gap-2">
                                        <input type="checkbox" name="clear_ga4_api_secret" value="1"
                                            class="h-3.5 w-3.5 rounded border-slate-300 text-red-600 focus:ring-red-300">
                                        <span class="text-xs text-red-600">Clear the saved secret (delete it in GA4 too)</span>
                                    </label>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center gap-3 border-t border-slate-100 pt-4">
                            <button type="button" data-test="ga4"
                                class="rounded-lg border border-slate-300 px-4 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-50">
                                <i class="fas fa-plug"></i> Test connection
                            </button>
                            <span data-result="ga4" class="text-xs"></span>
                        </div>
                    </div>
                </div>

                {{-- ─── Search Console ────────────────────────────────────── --}}
                <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 px-5 py-4">
                        <div class="flex items-center gap-3">
                            <div class="grid h-9 w-9 place-items-center rounded-lg bg-emerald-50 text-emerald-600">
                                <i class="fas fa-magnifying-glass"></i>
                            </div>
                            <div>
                                <h2 class="text-base font-semibold text-slate-900">Google Search Console</h2>
                                <p class="text-xs text-slate-500">Site verification meta tag</p>
                            </div>
                        </div>
                        <label class="inline-flex cursor-pointer items-center gap-2">
                            <input type="checkbox" name="gsc_enabled" value="1" @checked($tracking->gsc_enabled)
                                class="h-4 w-4 rounded border-slate-300 text-primary focus:ring-primary/30">
                            <span class="text-sm font-medium text-slate-700">Enabled</span>
                        </label>
                    </div>

                    <div class="space-y-4 p-5">
                        <div>
                            <label for="gsc_verification_code" class="{{ $label }}">Verification code</label>
                            <input type="text" name="gsc_verification_code" id="gsc_verification_code" class="{{ $control }}"
                                value="{{ old('gsc_verification_code', $tracking->gsc_verification_code) }}"
                                placeholder="paste only the content value, not the whole tag">
                            <p class="{{ $hint }}">
                                From <code>&lt;meta name="google-site-verification" content="THIS_PART"&gt;</code>.
                            </p>
                        </div>
                        <div class="flex items-center gap-3 border-t border-slate-100 pt-4">
                            <button type="button" data-test="gsc"
                                class="rounded-lg border border-slate-300 px-4 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-50">
                                <i class="fas fa-plug"></i> Test connection
                            </button>
                            <span data-result="gsc" class="text-xs"></span>
                        </div>
                    </div>
                </div>

                {{-- ─── Consent Mode ──────────────────────────────────────── --}}
                <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 px-5 py-4">
                        <div class="flex items-center gap-3">
                            <div class="grid h-9 w-9 place-items-center rounded-lg bg-violet-50 text-violet-600">
                                <i class="fas fa-shield-halved"></i>
                            </div>
                            <div>
                                <h2 class="text-base font-semibold text-slate-900">Consent Mode v2</h2>
                                <p class="text-xs text-slate-500">Default consent state, per region</p>
                            </div>
                        </div>
                        <label class="inline-flex cursor-pointer items-center gap-2">
                            <input type="checkbox" name="consent_mode_enabled" value="1" @checked($tracking->consent_mode_enabled)
                                class="h-4 w-4 rounded border-slate-300 text-primary focus:ring-primary/30">
                            <span class="text-sm font-medium text-slate-700">Enabled</span>
                        </label>
                    </div>

                    <div class="space-y-4 p-5">
                        <div class="rounded-lg bg-slate-50 px-4 py-3 text-xs text-slate-600">
                            Visitors from the EU/EEA/UK get the <strong>EU defaults</strong>; everyone else (including Bangladesh)
                            gets the <strong>default</strong> column. Region comes from your CDN's country header —
                            when it can't be determined, EU defaults apply as a fail-safe.
                        </div>

                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            @foreach ([
                                'consent_default_row' => ['Default (Bangladesh + rest of world)', \App\Models\TrackingSetting::defaultRowConsent()],
                                'consent_default_eu' => ['EU / EEA / UK', \App\Models\TrackingSetting::defaultEuConsent()],
                            ] as $group => [$groupLabel, $groupDefaults])
                                @php($current = $tracking->{$group} ?: $groupDefaults)
                                <div>
                                    <p class="mb-2 text-sm font-semibold text-slate-800">{{ $groupLabel }}</p>
                                    <div class="space-y-2">
                                        @foreach (array_keys($groupDefaults) as $signal)
                                            <div class="flex items-center justify-between gap-3 rounded-lg border border-slate-200 px-3 py-2">
                                                <label for="{{ $group }}_{{ $signal }}" class="text-xs font-medium text-slate-600">{{ $signal }}</label>
                                                <select name="{{ $group }}[{{ $signal }}]" id="{{ $group }}_{{ $signal }}"
                                                    class="h-8 w-28 cursor-pointer rounded-lg border border-slate-300 bg-white px-2 text-xs text-slate-700">
                                                    <option value="granted" @selected(($current[$signal] ?? '') === 'granted')>granted</option>
                                                    <option value="denied" @selected(($current[$signal] ?? '') === 'denied')>denied</option>
                                                </select>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <label class="flex cursor-pointer items-center gap-2 border-t border-slate-100 pt-4">
                            <input type="checkbox" name="consent_banner_enabled" value="1" @checked($tracking->consent_banner_enabled)
                                class="h-4 w-4 rounded border-slate-300 text-primary focus:ring-primary/30">
                            <span class="text-sm text-slate-700">
                                Show the consent banner
                                <span class="text-xs text-slate-500">— only to visitors whose defaults deny something, so BD customers never see it</span>
                            </span>
                        </label>
                    </div>
                </div>

                {{-- ─── Site URL ──────────────────────────────────────────── --}}
                <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-200 px-5 py-4">
                        <div class="flex items-center gap-3">
                            <div class="grid h-9 w-9 place-items-center rounded-lg bg-slate-100 text-slate-600">
                                <i class="fas fa-globe"></i>
                            </div>
                            <div>
                                <h2 class="text-base font-semibold text-slate-900">Canonical domain</h2>
                                <p class="text-xs text-slate-500">One field to change when the site moves domain</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-5">
                        <label for="site_url" class="{{ $label }}">Site URL</label>
                        <input type="url" name="site_url" id="site_url" class="{{ $control }}"
                            value="{{ old('site_url', $tracking->site_url) }}" placeholder="https://example.com">
                        <p class="{{ $hint }}">
                            Drives event_source_url, GA4 document_location, canonical tags, og:url, sitemap.xml and robots.txt.
                            Changing it here is the only code-side step of a domain migration — see <code>TRACKING.md</code>
                            for the Meta/Google-side steps it cannot do for you.
                        </p>
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <button type="submit"
                        class="rounded-lg bg-primary px-6 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:opacity-90">
                        Save settings
                    </button>
                </div>
            </form>

            {{-- ─── Audit log ─────────────────────────────────────────────── --}}
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 px-5 py-4">
                    <h2 class="text-base font-semibold text-slate-900">Change log</h2>
                    <p class="text-xs text-slate-500">Who changed what, and when. Secret values are never recorded — only that they changed.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                            <tr>
                                <th class="px-5 py-3 font-medium">When</th>
                                <th class="px-5 py-3 font-medium">Admin</th>
                                <th class="px-5 py-3 font-medium">Field</th>
                                <th class="px-5 py-3 font-medium">From</th>
                                <th class="px-5 py-3 font-medium">To</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($audits as $audit)
                                <tr class="text-slate-600">
                                    <td class="whitespace-nowrap px-5 py-3 text-xs">{{ $audit->created_at?->format('d M Y, H:i') }}</td>
                                    <td class="px-5 py-3 text-xs">{{ $audit->user?->name ?? $audit->user_name ?? 'System' }}</td>
                                    <td class="px-5 py-3 font-mono text-xs">{{ $audit->field }}</td>
                                    <td class="px-5 py-3 font-mono text-xs text-slate-400">{{ Str::limit($audit->old_value ?? '—', 40) }}</td>
                                    <td class="px-5 py-3 font-mono text-xs">{{ Str::limit($audit->new_value ?? '—', 40) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-5 py-8 text-center text-sm text-slate-400">No changes recorded yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </section>

    <script>
        // Test Connection, per integration. Saves nothing — it validates what is
        // currently stored, so save first, then test.
        document.querySelectorAll('[data-test]').forEach(function (button) {
            button.addEventListener('click', function () {
                const key = button.getAttribute('data-test');
                const output = document.querySelector('[data-result="' + key + '"]');

                output.textContent = 'Testing…';
                output.className = 'text-xs text-slate-500';
                button.disabled = true;

                fetch('{{ url('admin/setting/tracking/test') }}/' + key, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                })
                    .then(function (r) { return r.json(); })
                    .then(function (data) {
                        output.textContent = data.message;
                        output.className = data.ok
                            ? 'text-xs font-medium text-emerald-600'
                            : 'text-xs font-medium text-red-600';
                    })
                    .catch(function () {
                        output.textContent = 'Test failed to run.';
                        output.className = 'text-xs font-medium text-red-600';
                    })
                    .finally(function () { button.disabled = false; });
            });
        });
    </script>

@endsection
