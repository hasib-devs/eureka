{{--
    Consent banner.

    Deliberately NOT shown to everyone. It renders only when the visitor's
    regional default denies storage (EU/EEA/UK, or an unresolvable country) and
    they have not already answered. Bangladeshi customers — this store's market,
    where GDPR does not apply — never see a cookie popup, so no conversion data
    is lost to banner friction for no compliance benefit.

    Rendering is decided server-side by ConsentResolver::shouldShowBanner().
--}}
@php($__consent = app(\App\Services\Tracking\ConsentResolver::class))

@if ($__consent->shouldShowBanner(request()))
    <div
        x-data="{ open: true }"
        x-show="open"
        x-cloak
        x-transition.opacity
        class="fixed inset-x-0 bottom-0 z-[9999] p-3 sm:p-4"
        role="dialog"
        aria-live="polite"
        aria-label="{{ __('Cookie consent') }}"
    >
        <div class="mx-auto flex max-w-4xl flex-col gap-3 rounded-xl border border-slate-200 bg-white p-4 shadow-lg sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm leading-relaxed text-slate-700">
                {{ __('We use cookies to measure traffic and show relevant ads. You can accept or reject these at any time.') }}
                @if (($__policy = setting('PRIVACY_POLICY_URL')))
                    <a href="{{ $__policy }}" class="font-medium text-primary underline">{{ __('Learn more') }}</a>
                @endif
            </p>

            <div class="flex shrink-0 gap-2">
                <button
                    type="button"
                    @click="window.setTrackingConsent(false); open = false"
                    class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50"
                >
                    {{ __('Reject') }}
                </button>
                <button
                    type="button"
                    @click="window.setTrackingConsent(true); open = false"
                    class="rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white transition hover:opacity-90"
                >
                    {{ __('Accept') }}
                </button>
            </div>
        </div>
    </div>
@endif
