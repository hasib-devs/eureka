@php
    $mmLinks = \App\Support\MobileMenu::links();
    $mmSocials = collect(\App\Support\MobileMenu::socials())->filter(fn ($s) => $s['visible'] && $s['url']);
    $mmCats = \App\Support\MobileMenu::categories();
    $mmVideo = setting('MENU_BG_VIDEO');

    $mmRoute = fn ($key) => match ($key) {
        'home' => route('home'),
        'shop' => route('product'),
        'blog' => route('blogs'),
        'track' => route('track'),
        'contact' => route('contact'),
        default => '#',
    };

    $mmSocialSvg = [
        'call' => '<path d="M6.6 10.8a15.6 15.6 0 0 0 6.6 6.6l2.2-2.2a1 1 0 0 1 1-.25c1.1.36 2.3.56 3.5.56a1 1 0 0 1 1 1V20a1 1 0 0 1-1 1C10.6 21 3 13.4 3 4a1 1 0 0 1 1-1h3.5a1 1 0 0 1 1 1c0 1.2.2 2.4.56 3.5a1 1 0 0 1-.25 1L6.6 10.8Z"/>',
        'whatsapp' => '<path d="M20 12a8 8 0 1 1-3.6-6.7"/><path d="M20 4l-4.2 3.4"/><path d="M9 10.5c.3 1.6 2 3.3 3.6 3.6l1-1.2c.2-.3.6-.4 1-.3l1.6.6c.3.1.5.4.5.8v1.3c0 .5-.4.9-.9.9C12.6 16 8 11.4 7.8 8.2c0-.5.3-.9.9-.9h1.3c.4 0 .7.2.8.5l.6 1.6c.1.3 0 .7-.3 1L9 10.5Z"/>',
        'facebook' => '<circle cx="12" cy="12" r="9"/><path d="M13.8 21v-6.6h2.2l.3-2.6h-2.5V10c0-.75.2-1.26 1.28-1.26H16V6.4c-.24-.03-1.06-.1-2-.1-2 0-3.4 1.22-3.4 3.46v1.94H8.4v2.6h2.2V21"/>',
        'instagram' => '<rect x="3.5" y="3.5" width="17" height="17" rx="5"/><circle cx="12" cy="12" r="3.8"/><circle cx="17" cy="7" r="1" fill="currentColor" stroke="none"/>',
        'tiktok' => '<path d="M15 3v9.5a3.5 3.5 0 1 1-3.5-3.5c.35 0 .68.05 1 .14"/><path d="M15 3c.3 2.2 2 3.9 4.2 4.2"/>',
    ];
    $mmSocialLabel = ['call' => 'Call', 'whatsapp' => 'WhatsApp', 'facebook' => 'Facebook', 'instagram' => 'Instagram', 'tiktok' => 'TikTok'];
@endphp

<div class="alw-menu-overlay" id="alwMenuOverlay" aria-hidden="true">
    @if ($mmVideo)
        <div class="alw-video-stage" aria-hidden="true">
            <video id="alwLampVideo" data-src="{{ asset('uploads/menu/'.$mmVideo) }}" muted loop playsinline preload="none"></video>
            <div class="alw-glass alw-glass-tint"></div>
            <div class="alw-glass alw-glass-blur"></div>
            <div class="alw-glass alw-glass-scrim-top"></div>
            <div class="alw-glass alw-glass-scrim-bottom"></div>
            <div class="alw-glass alw-glass-vignette"></div>
        </div>
    @endif
    <div class="alw-ambient-glow" aria-hidden="true"></div>

    <div class="alw-menu-inner">
        <nav aria-label="Mobile primary">
            <ul class="alw-menu-links">
                @foreach ($mmLinks as $link)
                    @continue(! $link['visible'])
                    @if ($link['key'] === 'categories')
                        @if ($mmCats->isNotEmpty())
                            <li class="alw-has-cats" id="alwCatsItem">
                                <button type="button" class="alw-cat-toggle" id="alwCatsToggle" aria-expanded="false" aria-controls="alwCatsPanel">
                                    {{ $link['label'] }}
                                    <svg class="alw-chevron" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
                                </button>
                                <div class="alw-cat-panel" id="alwCatsPanel">
                                    <div class="alw-cat-panel-inner">
                                        <ul class="alw-cat-list">
                                            @foreach ($mmCats as $cat)
                                                <li><a href="{{ route('category.product', $cat->slug) }}">{{ $cat->name }}</a></li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </li>
                        @endif
                    @else
                        <li><a href="{{ $mmRoute($link['key']) }}">{{ $link['label'] }}</a></li>
                    @endif
                @endforeach
            </ul>
        </nav>

        @if ($mmSocials->isNotEmpty())
            <div class="alw-menu-lower">
                <p class="alw-menu-section-label">Get in Touch</p>
                <div class="alw-icon-row">
                    @foreach ($mmSocials as $social)
                        <a class="alw-icon-chip" href="{{ $social['url'] }}" @if (! str_starts_with($social['url'], 'tel:')) target="_blank" rel="noopener" @endif>
                            <svg viewBox="0 0 24 24">{!! $mmSocialSvg[$social['type']] !!}</svg>
                            {{ $mmSocialLabel[$social['type']] }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600&family=Inter:wght@400;500;600&display=swap');

    .alw-menu-overlay{
        position:fixed; inset:0; z-index:9990; background:#17130F;
        opacity:0; pointer-events:none; transform:scale(1.015); overflow:hidden;
        transition:opacity .7s cubic-bezier(.22,.61,.36,1), transform .7s cubic-bezier(.22,.61,.36,1);
        font-family:'Inter',sans-serif;
    }
    body.alw-menu-open .alw-menu-overlay{ opacity:1; pointer-events:auto; transform:scale(1); }
    body.alw-menu-open{ overflow:hidden; }

    .alw-menu-overlay::before{
        content:''; position:absolute; inset:0; z-index:0; pointer-events:none;
        background:
          radial-gradient(ellipse at 50% 0%, rgba(255,255,255,.035) 0%, transparent 55%),
          radial-gradient(ellipse at 50% 100%, rgba(0,0,0,.35) 0%, transparent 60%);
    }

    .alw-ambient-glow{
        position:absolute; left:50%; top:38%; width:64vw; height:64vw; max-width:480px; max-height:480px;
        transform:translate(-50%,-50%); border-radius:50%; pointer-events:none; z-index:2; opacity:0;
        background:radial-gradient(circle, rgba(232,166,87,.11) 0%, rgba(232,166,87,.045) 45%, transparent 72%);
        transition:opacity 1.1s cubic-bezier(.22,.61,.36,1);
    }
    body.alw-phase-glow .alw-ambient-glow{ opacity:1; }

    .alw-video-stage{
        position:absolute; inset:0; z-index:0; overflow:hidden; opacity:0; transform:scale(1.045);
        transition:opacity 1.1s cubic-bezier(.22,.61,.36,1), transform 1.6s cubic-bezier(.22,.61,.36,1);
    }
    body.alw-phase-video .alw-video-stage{ opacity:1; transform:scale(1); }
    .alw-video-stage video{ position:absolute; inset:0; width:100%; height:100%; object-fit:cover; display:block; }

    .alw-glass{ position:absolute; inset:0; z-index:1; pointer-events:none; }
    .alw-glass-tint{ background:linear-gradient(180deg, rgba(8,6,4,.34) 0%, rgba(8,6,4,.16) 35%, rgba(8,6,4,.24) 65%, rgba(6,5,3,.5) 100%); }
    .alw-glass-blur{ background:rgba(10,10,10,.45); -webkit-backdrop-filter:blur(3px) saturate(110%); backdrop-filter:blur(3px) saturate(110%); }
    .alw-glass-scrim-top{ inset:0 0 auto 0; height:32%; background:linear-gradient(to bottom, rgba(10,8,6,.55), transparent); }
    .alw-glass-scrim-bottom{ inset:auto 0 0 0; height:52%; background:linear-gradient(to top, rgba(8,6,4,.78), transparent); }
    .alw-glass-vignette{ background:radial-gradient(circle at center, rgba(255,220,170,.03) 0%, rgba(0,0,0,.18) 45%, rgba(0,0,0,.55) 100%); }

    .alw-menu-inner{
        position:relative; z-index:3; height:100%; padding:88px 24px 28px;
        display:flex; flex-direction:column; justify-content:space-between;
        max-width:960px; margin:0 auto; overflow-y:auto;
    }
    .alw-menu-links{ list-style:none; margin:0; padding:0; }
    .alw-menu-links > li{ opacity:0; transform:translateY(16px); transition:opacity .6s cubic-bezier(.22,.61,.36,1), transform .6s cubic-bezier(.22,.61,.36,1); }
    body.alw-phase-nav .alw-menu-links > li{ opacity:1; transform:translateY(0); }
    body.alw-phase-nav .alw-menu-links > li:nth-child(1){ transition-delay:0ms; }
    body.alw-phase-nav .alw-menu-links > li:nth-child(2){ transition-delay:70ms; }
    body.alw-phase-nav .alw-menu-links > li:nth-child(3){ transition-delay:140ms; }
    body.alw-phase-nav .alw-menu-links > li:nth-child(4){ transition-delay:210ms; }
    body.alw-phase-nav .alw-menu-links > li:nth-child(5){ transition-delay:280ms; }
    body.alw-phase-nav .alw-menu-links > li:nth-child(6){ transition-delay:350ms; }

    .alw-menu-links a, .alw-cat-toggle{
        display:flex; align-items:center; justify-content:space-between; width:100%; padding:12px 0;
        font-family:'Fraunces',serif; font-size:clamp(19px,5vw,29px); font-weight:500; color:#F5EEE3;
        text-decoration:none; border:none; border-bottom:1px solid #332B21; background:none; text-align:left; cursor:pointer;
        transition:color .35s ease;
    }
    .alw-menu-links > li:first-child > a, .alw-menu-links > li:first-child > .alw-cat-toggle{ border-top:1px solid #332B21; }
    .alw-menu-links a:hover, .alw-menu-links a:focus-visible, .alw-cat-toggle:hover, .alw-cat-toggle:focus-visible{ color:#E8A657; }

    .alw-chevron{ width:19px; height:19px; flex-shrink:0; stroke:#9A8F80; fill:none; stroke-width:1.6; stroke-linecap:round; stroke-linejoin:round; transition:transform .35s ease, stroke .3s ease; }
    .alw-cat-toggle:hover .alw-chevron{ stroke:#E8A657; }
    li.alw-is-expanded .alw-chevron{ transform:rotate(180deg); stroke:#E8A657; }

    .alw-cat-panel{ display:grid; grid-template-rows:0fr; transition:grid-template-rows .38s ease; }
    li.alw-is-expanded .alw-cat-panel{ grid-template-rows:1fr; }
    .alw-cat-panel-inner{ overflow:hidden; }
    .alw-cat-list{ list-style:none; margin:0; padding:4px 0 12px; }
    .alw-cat-list a{ display:block; padding:9px 0 9px 20px; font-family:'Inter',sans-serif; font-size:15px; font-weight:500; color:#9A8F80; text-decoration:none; border:none; position:relative; }
    .alw-cat-list a::before{ content:''; position:absolute; left:0; top:50%; width:6px; height:6px; border-radius:50%; background:#E8A657; transform:translateY(-50%); opacity:.85; }
    .alw-cat-list a:hover, .alw-cat-list a:focus-visible{ color:#E8A657; }

    .alw-menu-lower{ margin-top:32px; padding-top:22px; border-top:1px solid #332B21; opacity:0; transform:translateY(14px); transition:opacity .6s cubic-bezier(.22,.61,.36,1), transform .6s cubic-bezier(.22,.61,.36,1); }
    body.alw-phase-social .alw-menu-lower{ opacity:1; transform:translateY(0); }
    .alw-menu-section-label{ font-size:11px; letter-spacing:.14em; text-transform:uppercase; color:#9A8F80; margin:0 0 13px; font-weight:600; }
    .alw-icon-row{ display:flex; flex-wrap:wrap; gap:10px; }
    .alw-icon-chip{ display:inline-flex; align-items:center; gap:8px; padding:9px 16px 9px 12px; border:1px solid #332B21; border-radius:100px; color:#F5EEE3; text-decoration:none; font-size:13px; transition:border-color .3s ease, color .3s ease, background .3s ease; }
    .alw-icon-chip svg{ width:17px; height:17px; stroke:#F5EEE3; fill:none; stroke-width:1.6; stroke-linecap:round; stroke-linejoin:round; transition:stroke .3s ease; }
    .alw-icon-chip:hover, .alw-icon-chip:focus-visible{ border-color:#E8A657; color:#E8A657; background:rgba(232,166,87,.14); }
    .alw-icon-chip:hover svg, .alw-icon-chip:focus-visible svg{ stroke:#E8A657; }

    /* Keep the fixed header bar (with the hamburger -> X) ABOVE the overlay so it stays
       visible and tappable to close; go translucent while open (mobile only).
       The overlay is z-index:9990, so the header must sit above that. */
    @media (max-width:991px){
        body.alw-menu-open .site-header,
        body.alw-menu-open .app-header,
        body.alw-menu-open .main-header{ z-index:9996 !important; }
        body.alw-menu-open .main-header,
        body.alw-menu-open .main-header .header-row{
            background:rgba(15,12,9,.30) !important;
            -webkit-backdrop-filter:blur(12px); backdrop-filter:blur(12px);
            box-shadow:none !important; border-bottom-color:rgba(255,255,255,.08) !important;
        }
        body.alw-menu-open .hamburger .bars span{ background:#F5EEE3 !important; }
    }

    /* Desktop: never show the mobile menu */
    @media (min-width:992px){ .alw-menu-overlay{ display:none !important; } }

    @media (prefers-reduced-motion: reduce){
        .alw-menu-overlay, .alw-menu-overlay *{ transition:none !important; }
    }
</style>

<script>
    (function () {
        var body = document.body;
        var overlay = document.getElementById('alwMenuOverlay');
        var toggle = document.querySelector('[data-open-mobile-sidebar]');
        if (!overlay || !toggle) return;

        var video = document.getElementById('alwLampVideo');
        var PHASES = ['alw-phase-video', 'alw-phase-glow', 'alw-phase-nav', 'alw-phase-social'];
        var OPEN_DELAYS = [120, 550, 1400, 2350];
        var timers = [];
        function clearTimers() { timers.forEach(clearTimeout); timers = []; }

        function open() {
            body.classList.add('alw-menu-open');
            toggle.classList.add('is-active');
            toggle.setAttribute('aria-expanded', 'true');
            overlay.setAttribute('aria-hidden', 'false');
            clearTimers();
            if (video) {
                if (!video.src && video.dataset.src) video.src = video.dataset.src;
                try { var p = video.play(); if (p && p.catch) p.catch(function () {}); } catch (e) {}
            }
            PHASES.forEach(function (phase, i) { timers.push(setTimeout(function () { body.classList.add(phase); }, OPEN_DELAYS[i])); });
        }

        function close() {
            clearTimers();
            var reversed = PHASES.slice().reverse();
            reversed.forEach(function (phase, i) { timers.push(setTimeout(function () { body.classList.remove(phase); }, i * 120)); });
            timers.push(setTimeout(function () {
                body.classList.remove('alw-menu-open');
                toggle.classList.remove('is-active');
                toggle.setAttribute('aria-expanded', 'false');
                overlay.setAttribute('aria-hidden', 'true');
                if (video) { try { video.pause(); } catch (e) {} }
            }, reversed.length * 120 + 180));
        }

        toggle.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            if (body.classList.contains('alw-menu-open')) close(); else open();
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && body.classList.contains('alw-menu-open')) close();
        });

        var catsToggle = document.getElementById('alwCatsToggle');
        if (catsToggle) {
            var catsItem = document.getElementById('alwCatsItem');
            catsToggle.addEventListener('click', function () {
                var expanded = catsItem.classList.toggle('alw-is-expanded');
                catsToggle.setAttribute('aria-expanded', String(expanded));
            });
        }
    })();
</script>
