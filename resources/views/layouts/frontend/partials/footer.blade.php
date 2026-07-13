{{-- Global luxe footer — every element is admin-controlled via Settings / Pages --}}
@php
    $socials = [
        'facebook' => ['url' => setting('facebook'), 'label' => 'Facebook', 'icon' => '<path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/>'],
        'instagram' => ['url' => setting('instagram'), 'label' => 'Instagram', 'icon' => '<rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1"/>'],
        'whatsapp' => ['url' => setting('whatsapp') ? 'https://wa.me/'.setting('whatsapp') : null, 'label' => 'WhatsApp', 'icon' => '<path d="M21 11.5a8.5 8.5 0 01-12.4 7.55L3 21l2.05-5.4A8.5 8.5 0 1121 11.5z"/>'],
        'pinterest' => ['url' => setting('pinterest'), 'label' => 'Pinterest', 'icon' => '<circle cx="12" cy="12" r="9"/><path d="M9 17c1-3 1.5-5 1.5-5m0 0C10 10 11 8 13 8c2.2 0 3 1.6 3 3.2 0 2.3-1.2 4.3-3 4.3-.9 0-1.5-.5-1.7-1.1"/>'],
        'tiktok' => ['url' => setting('tiktok'), 'label' => 'TikTok', 'icon' => '<path d="M15 3v10.5a3.5 3.5 0 11-3-3.46V7a6.5 6.5 0 106.5 6.5V9.8A6.9 6.9 0 0021 11V8a5 5 0 01-3-2.2A5 5 0 0117 3h-2z"/>'],
        'youtube' => ['url' => setting('youtube'), 'label' => 'YouTube', 'icon' => '<rect x="2" y="5" width="20" height="14" rx="4"/><path d="M10 9l5 3-5 3z"/>'],
        'twitter' => ['url' => setting('twitter'), 'label' => 'Twitter', 'icon' => '<path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"/>'],
        'linkedin' => ['url' => setting('linkedin'), 'label' => 'LinkedIn', 'icon' => '<path d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-4 0v7h-4V9h4v1.5A6 6 0 0116 8z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/>'],
    ];
    $activeSocials = array_filter($socials, fn ($s) => !empty($s['url']));
    $legalPages = \App\Models\Page::where('position', 2)->where('status', 1)->get();
@endphp

<style>
.lux-footer { font-family: 'Inter', sans-serif; background: #111111; color: #b8b8b8; }
.lux-footer a { text-decoration: none; }
.lux-footer .footer-accent-line { height: 3px; background: #FFCC00; }
.lux-footer .footer-top { background: #faf8f4; color: #1c1c1c; }
.lux-footer .footer-top-inner { max-width: 1200px; margin: 0 auto; padding: 64px 20px 48px; }
.lux-footer .footer-grid { display: grid; grid-template-columns: 1.3fr 0.9fr 0.9fr 1.1fr; gap: 56px; }
.lux-footer .footer-brand .footer-logo-row { display: flex; align-items: center; gap: 12px; margin-bottom: 16px; }
.lux-footer .footer-brand .footer-logo-row img { height: 88px; width: auto; display: block; }
.lux-footer .footer-brand p { font-size: 13.5px; line-height: 1.85; color: #5c5c5c; margin: 0 0 26px; max-width: 300px; }
.lux-footer .footer-social { display: flex; gap: 10px; flex-wrap: wrap; }
.lux-footer .footer-social a {
    width: 34px; height: 34px; border: 1px solid #ddd8ca; border-radius: 50%; display: flex;
    align-items: center; justify-content: center; background: #ffffff; transition: all 0.3s ease;
}
.lux-footer .footer-social a svg { width: 14px; height: 14px; stroke: #1c1c1c; fill: none; transition: all 0.3s ease; }
.lux-footer .footer-social a:hover { background: #0a0a0a; border-color: #0a0a0a; }
.lux-footer .footer-social a:hover svg { stroke: #FFCC00; }
.lux-footer .footer-col h4 {
    font-size: 11px; letter-spacing: 2px; text-transform: uppercase; color: #1c1c1c;
    margin: 0 0 24px; font-weight: 700; position: relative; padding-bottom: 12px;
}
.lux-footer .footer-col h4::after { content: ''; position: absolute; left: 0; bottom: 0; width: 28px; height: 2px; background: #FFCC00; }
.lux-footer .footer-col ul { list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 13px; }
.lux-footer .footer-col ul li a { color: #5c5c5c; font-size: 13.5px; transition: color 0.3s ease; }
.lux-footer .footer-col ul li a:hover { color: #C9A227; }
.lux-footer .footer-contact-list { list-style: none; padding: 0; margin: 0 0 26px; display: flex; flex-direction: column; gap: 15px; }
.lux-footer .footer-contact-list li { display: flex; gap: 11px; font-size: 13px; color: #5c5c5c; line-height: 1.55; }
.lux-footer .footer-contact-list svg { width: 15px; height: 15px; stroke: #C9A227; fill: none; flex-shrink: 0; margin-top: 2px; }
.lux-footer .footer-contact-list a { color: #3a3a3a; }
.lux-footer .footer-contact-list a:hover { color: #C9A227; }
.lux-footer .footer-newsletter { display: flex; border: 1px solid #ddd8ca; background: #ffffff; }
.lux-footer .footer-newsletter input {
    flex: 1; background: none; border: none; padding: 12px 14px; font-size: 12.5px; color: #1c1c1c; font-family: inherit; min-width: 0;
}
.lux-footer .footer-newsletter input::placeholder { color: #999; }
.lux-footer .footer-newsletter input:focus { outline: none; }
.lux-footer .footer-newsletter button {
    background: #0a0a0a; border: none; padding: 0 18px; font-size: 11px; letter-spacing: 1.5px;
    text-transform: uppercase; font-weight: 600; cursor: pointer; transition: all 0.3s ease; color: #FFCC00; flex-shrink: 0;
}
.lux-footer .footer-newsletter button:hover { background: #C9A227; color: #000; }
.lux-footer .newsletter-msg { font-size: 11.5px; margin-top: 10px; display: none; }
.lux-footer .newsletter-msg.ok { color: #2e6b3e; }
.lux-footer .newsletter-msg.err { color: #b23b3b; }
.lux-footer .footer-bottom { background: #0a0a0a; border-top: 1px solid #262626; }
.lux-footer .footer-bottom-inner {
    max-width: 1200px; margin: 0 auto; padding: 22px 20px;
    display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 14px;
}
.lux-footer .footer-bottom .copyright { font-size: 11.5px; color: #6f6f6f; }
.lux-footer .footer-bottom .legal-links { display: flex; gap: 22px; }
.lux-footer .footer-bottom .legal-links a { font-size: 11.5px; color: #6f6f6f; }
.lux-footer .footer-bottom .legal-links a:hover { color: #FFCC00; }
.lux-footer .payment-badges { display: flex; gap: 8px; flex-wrap: wrap; }
.lux-footer .payment-badges span { font-size: 10px; letter-spacing: 0.5px; border: 1px solid #2e2e2e; color: #888; padding: 5px 10px; }
.lux-whatsapp-fixed {
    position: fixed; right: 24px; bottom: 24px; width: 48px; height: 48px; border-radius: 50%;
    background: #25D366; display: flex; align-items: center; justify-content: center; z-index: 500;
    box-shadow: 0 4px 14px rgba(0,0,0,0.25);
}
.lux-whatsapp-fixed svg { width: 24px; height: 24px; stroke: #fff; fill: none; }
/* Scroll-to-top: right side, above the WhatsApp button, on every page. */
.lux-scroll-top {
    position: fixed; right: 24px; bottom: 24px; width: 46px; height: 46px; border-radius: 50%;
    background: #0a0a0a; color: #C9A227; border: none; cursor: pointer;
    display: flex; align-items: center; justify-content: center; font-size: 18px; line-height: 1; z-index: 500;
    box-shadow: 0 4px 14px rgba(0,0,0,0.3); opacity: 0; pointer-events: none; transition: all 0.3s ease;
}
.lux-scroll-top.stacked { bottom: 84px; } /* leave room for the WhatsApp button below */
.lux-scroll-top.visible { opacity: 1; pointer-events: auto; }
.lux-scroll-top:hover { background: #C9A227; color: #0a0a0a; }
@media (max-width: 900px) {
    .lux-footer .footer-grid { grid-template-columns: 1fr 1fr; gap: 40px 30px; }
    .lux-footer .footer-brand { grid-column: 1 / 3; }
    .lux-footer .footer-brand .footer-logo-row img { height: 64px; }
    .lux-whatsapp-fixed { right: 16px; bottom: 16px; }
    .lux-scroll-top { right: 16px; bottom: 16px; width: 42px; height: 42px; }
    .lux-scroll-top.stacked { bottom: 72px; }
}
</style>

<div class="lux-footer">
    <div class="footer-accent-line"></div>

    <div class="footer-top">
        <div class="footer-top-inner">
            <div class="footer-grid">
                {{-- Brand --}}
                <div class="footer-col footer-brand">
                    <div class="footer-logo-row">
                        <a href="{{ route('home') }}">
                            <img src="{{ asset('uploads/setting/' . setting('logo')) }}" alt="{{ setting('site_title') ?: 'Anas Luxy World' }}">
                        </a>
                    </div>
                    @if (setting('footer_description'))
                        <p>{{ setting('footer_description') }}</p>
                    @endif
                    @if (count($activeSocials))
                        <div class="footer-social">
                            @foreach ($activeSocials as $social)
                                <a href="{{ $social['url'] }}" target="_blank" rel="noopener" aria-label="{{ $social['label'] }}">
                                    <svg viewBox="0 0 24 24" stroke-width="1.6">{!! $social['icon'] !!}</svg>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Quick links --}}
                <div class="footer-col">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="{{ route('home') }}">Home</a></li>
                        <li><a href="{{ route('product') }}">Shop All</a></li>
                        @foreach ($categories_f->take(3) as $cat)
                            <li><a href="{{ route('category.product', $cat->slug) }}">{{ $cat->name }}</a></li>
                        @endforeach
                        <li><a href="{{ route('wishlist') }}">Wishlist</a></li>
                        <li><a href="{{ route('account') }}">My Account</a></li>
                    </ul>
                </div>

                {{-- Customer care (admin-managed pages) --}}
                <div class="footer-col">
                    <h4>Customer Care</h4>
                    <ul>
                        @foreach ($footerPages as $page)
                            <li><a href="{{ route('page', ['slug' => $page->name]) }}">{{ $page->name }}</a></li>
                        @endforeach
                        @if (setting('whatsapp'))
                            <li><a href="https://wa.me/{{ setting('whatsapp') }}" target="_blank" rel="noopener">Contact Us</a></li>
                        @endif
                    </ul>
                </div>

                {{-- Contact + newsletter --}}
                <div class="footer-col">
                    <h4>Get In Touch</h4>
                    <ul class="footer-contact-list">
                        @if (setting('SITE_INFO_PHONE'))
                            <li>
                                <svg viewBox="0 0 24 24"><path d="M22 16.9v3a2 2 0 01-2.2 2 19.8 19.8 0 01-8.6-3.1 19.5 19.5 0 01-6-6A19.8 19.8 0 012.1 4.2 2 2 0 014.1 2h3a2 2 0 012 1.7c.1.9.3 1.8.6 2.7a2 2 0 01-.5 2.1L8 9.7a16 16 0 006 6l1.2-1.2a2 2 0 012.1-.5c.9.3 1.8.5 2.7.6a2 2 0 011.7 2z"/></svg>
                                <a href="tel:{{ setting('SITE_INFO_PHONE') }}">{{ setting('SITE_INFO_PHONE') }}</a>
                            </li>
                        @endif
                        @if (setting('SITE_INFO_SUPPORT_MAIL'))
                            <li>
                                <svg viewBox="0 0 24 24"><path d="M4 4h16v16H4z"/><path d="M22 6l-10 7L2 6"/></svg>
                                <a href="mailto:{{ setting('SITE_INFO_SUPPORT_MAIL') }}">{{ setting('SITE_INFO_SUPPORT_MAIL') }}</a>
                            </li>
                        @endif
                        @if (setting('SITE_INFO_ADDRESS'))
                            <li>
                                <svg viewBox="0 0 24 24"><path d="M12 22s7-6.2 7-12a7 7 0 10-14 0c0 5.8 7 12 7 12z"/><circle cx="12" cy="10" r="2.5"/></svg>
                                <span>{{ setting('SITE_INFO_ADDRESS') }}</span>
                            </li>
                        @endif
                    </ul>
                    <h4 style="margin-bottom:14px;">Join The List</h4>
                    <div class="footer-newsletter">
                        <input type="email" id="luxNewsletterInput" placeholder="Your email address">
                        <button type="button" onclick="luxSubscribeNewsletter()">Join</button>
                    </div>
                    <div class="newsletter-msg" id="luxNewsletterMsg"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="footer-bottom-inner">
            <span class="copyright">{{ setting('copy_right_text') ?: '© '.date('Y').' '.(setting('site_title') ?: 'Anas Luxy World').'. All Rights Reserved.' }}</span>
            <div class="payment-badges">
                <span>Cash on Delivery</span>
                @if (setting('bkash'))<span>bKash</span>@endif
                @if (setting('nagad'))<span>Nagad</span>@endif
            </div>
            @if ($legalPages->isNotEmpty())
                <div class="legal-links">
                    @foreach ($legalPages as $page)
                        <a href="{{ route('page', ['slug' => $page->name]) }}">{{ $page->name }}</a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

@if (setting('whatsapp'))
    <a href="https://wa.me/{{ setting('whatsapp') }}" class="lux-whatsapp-fixed" target="_blank" rel="noopener" aria-label="Chat on WhatsApp">
        <svg viewBox="0 0 24 24" stroke-width="1.8"><path d="M21 11.5a8.5 8.5 0 01-12.4 7.55L3 21l2.05-5.4A8.5 8.5 0 1121 11.5z"/></svg>
    </a>
@endif

{{-- Global scroll-to-top: shown after scrolling down, on every page.
     Stacked above the WhatsApp button when that button is present. --}}
<button type="button" class="lux-scroll-top{{ setting('whatsapp') ? ' stacked' : '' }}" id="luxScrollTopBtn"
        onclick="window.scrollTo({ top: 0, behavior: 'smooth' })" aria-label="Back to top">↑</button>

@push('js')
<script>
(function () {
    var btn = document.getElementById('luxScrollTopBtn');
    if (!btn) return;
    function toggle() { btn.classList.toggle('visible', window.scrollY > 400); }
    window.addEventListener('scroll', toggle, { passive: true });
    toggle();
})();
</script>
@endpush

<script>
function luxSubscribeNewsletter() {
    const input = document.getElementById('luxNewsletterInput');
    const msg = document.getElementById('luxNewsletterMsg');
    const email = input.value.trim();
    if (!email || !email.includes('@')) { input.focus(); return; }

    const body = new FormData();
    body.append('_token', '{{ csrf_token() }}');
    body.append('subscription', email);

    fetch('{{ route('subscription') }}', { method: 'POST', body, headers: { 'Accept': 'application/json' } })
        .then(async r => {
            if (!r.ok) throw new Error('Please enter a valid email address.');
            return r.json();
        })
        .then(() => {
            msg.textContent = "Thanks — you're on the list!";
            msg.className = 'newsletter-msg ok';
            msg.style.display = 'block';
            input.value = '';
        })
        .catch(err => {
            msg.textContent = err.message;
            msg.className = 'newsletter-msg err';
            msg.style.display = 'block';
        });
}
</script>
