{{--
    Tracking head — Consent Mode v2, GTM, Meta Pixel, GA4, Search Console.

    Order is load-bearing and must not be rearranged:
      1. Consent Mode v2 defaults   (MUST be the first dataLayer push, before any tag)
      2. GTM container
      3. Meta Pixel init + advanced matching
      4. Queued events (Purchase etc.) with the server's event_id, for dedup

    Renders nothing at all unless an admin has enabled and configured an
    integration, so merging this changes no live behaviour.

    Everything here comes from TrackingSettingsService::publicConfig(), which
    structurally cannot contain meta_access_token or ga4_api_secret.
--}}
@php($__tracking = app(\App\Services\Tracking\TrackingViewModel::class)->forRequest(request()))

@if ($__tracking['any'])
    {{-- 1. dataLayer + gtag shim, then Consent Mode v2 defaults.
         The shim is defined unconditionally: the GA4 block below calls gtag()
         regardless of Consent Mode, so defining it only inside the consent
         branch would throw "gtag is not defined" and kill GA4 entirely for
         anyone who turns Consent Mode off. Consent defaults stay the FIRST
         push, before GTM/gtag/fbq load. --}}
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        @if ($__tracking['consentMode'])
        gtag('consent', 'default', @json($__tracking['consentDefaults']));
        gtag('set', 'ads_data_redaction', {{ $__tracking['consentDefaults']['ad_storage'] === 'denied' ? 'true' : 'false' }});
        @endif
    </script>

    {{-- 2. Google Tag Manager --}}
    @if ($__tracking['gtmId'])
        <script>
            (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
            new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer','{{ $__tracking['gtmId'] }}');
        </script>
    @endif

    {{-- 3. GA4 via gtag, only when GTM is not already loading it --}}
    @if ($__tracking['ga4Id'] && ! $__tracking['gtmId'])
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ $__tracking['ga4Id'] }}"></script>
        <script>
            gtag('js', new Date());
            gtag('config', '{{ $__tracking['ga4Id'] }}', {
                send_page_view: true,
                cookie_domain: {!! $__tracking['cookieDomain'] ? "'".$__tracking['cookieDomain']."'" : "'auto'" !!}
            });
        </script>
    @endif

    {{-- 4. Meta Pixel. Advanced matching values are hashed server-side and
         injected already-hashed, so raw PII never reaches the browser and the
         client and server hashes cannot drift apart. --}}
    @if ($__tracking['pixelId'])
        <script>
            !function(f,b,e,v,n,t,s)
            {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window,document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', '{{ $__tracking['pixelId'] }}'@if ($__tracking['advancedMatching']), @json($__tracking['advancedMatching']) @endif);
        </script>
        <noscript><img height="1" width="1" style="display:none"
            src="https://www.facebook.com/tr?id={{ $__tracking['pixelId'] }}&ev=PageView&noscript=1" alt=""/></noscript>
    @endif

    {{-- Config for resources/js/tracking.js --}}
    <script>
        window.__TRACKING__ = @json($__tracking['js']);
    </script>

    {{-- 5. Events the server already sent to Meta CAPI / GA4 MP for this same
         user action. Firing them here with the SAME event_id is what makes Meta
         collapse the two copies into one event instead of double-counting. --}}
    @if (! empty($__tracking['queued']))
        <script>
            window.__TRACKING_QUEUED__ = @json($__tracking['queued']);
        </script>
    @endif
@endif

{{-- Google Search Console verification. Independent of the tracking toggles:
     pasting a code in the admin panel is the entire workflow. --}}
@if ($__tracking['gscCode'])
    <meta name="google-site-verification" content="{{ $__tracking['gscCode'] }}" />
@endif
