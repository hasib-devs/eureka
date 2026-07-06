{{-- Premium Light Footer — shared across storefront pages (full-width) --}}

<style>
    footer.lux-prem-footer {
        background: #faf9f7 !important;
        background-color: #faf9f7 !important;
        padding: 72px 0 0 !important;
        color: #111 !important;
        border-top: 1px solid #ececec;
        /* break out of site-inner padding */
        width: 100vw !important;
        max-width: 100vw !important;
        margin-left: calc(50% - 50vw) !important;
        margin-right: calc(50% - 50vw) !important;
        display: block !important;
    }
    footer.lux-prem-footer * { box-sizing: border-box; }
    .lux-prem-footer-inner {
        max-width: 1240px;
        margin: 0 auto;
        padding: 0 48px;
    }
    .lux-prem-footer-top {
        display: grid;
        grid-template-columns: 1.6fr 1fr 1fr 1fr;
        gap: 56px;
        padding-bottom: 60px;
        border-bottom: 1px solid rgba(0,0,0,.07);
    }
    /* Site logo in footer */
    .lux-prem-footer-logo {
        display: block;
        margin-bottom: 16px;
        height: 48px;
        width: auto;
        object-fit: contain;
        object-position: left center;
    }
    .lux-prem-footer-brand .brand-name {
        font-size: 17px !important;
        font-weight: 700 !important;
        color: #111 !important;
        margin: 0 0 12px !important;
        font-family: 'Cinzel Decorative', Georgia, serif !important;
        letter-spacing: 1px;
        display: block;
    }
    .lux-prem-footer-brand > p {
        font-size: 13px !important;
        color: #6b6b6b !important;
        line-height: 1.78 !important;
        margin: 0 0 24px !important;
        max-width: 230px;
    }
    .lux-prem-social { display: flex; gap: 8px; flex-wrap: wrap; }
    .lux-prem-social a {
        display: flex !important;
        align-items: center;
        justify-content: center;
        width: 34px;
        height: 34px;
        border-radius: 8px;
        background: #fff !important;
        border: 1px solid #e4e0d8 !important;
        color: #777 !important;
        font-size: 12px;
        text-decoration: none !important;
        transition: all .25s ease;
    }
    .lux-prem-social a:hover {
        background: rgba(201,169,110,.14) !important;
        border-color: rgba(176,141,76,.5) !important;
        color: #a8834b !important;
        transform: translateY(-2px);
    }
    /* Column headings */
    .lux-prem-footer-col h5 {
        font-size: 9.5px !important;
        font-weight: 700 !important;
        letter-spacing: 2.5px;
        text-transform: uppercase;
        color: #a8834b !important;
        margin: 0 0 18px !important;
        padding-bottom: 10px;
        border-bottom: 1px solid rgba(176,141,76,.25);
    }
    .lux-prem-footer-col ul { list-style: none !important; padding: 0 !important; margin: 0 !important; }
    .lux-prem-footer-col li { margin-bottom: 10px !important; list-style: none !important; }
    .lux-prem-footer-col a {
        font-size: 13px !important;
        color: #6b6b6b !important;
        text-decoration: none !important;
        transition: all .2s ease;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .lux-prem-footer-col a::before {
        content: '';
        display: inline-block;
        width: 0;
        height: 1px;
        background: #a8834b;
        transition: width .2s ease;
        vertical-align: middle;
    }
    .lux-prem-footer-col a:hover { color: #111 !important; padding-left: 4px; }
    .lux-prem-footer-col a:hover::before { width: 8px; }
    /* Bottom bar */
    .lux-prem-footer-bottom {
        max-width: 1240px;
        margin: 0 auto;
        padding: 20px 48px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
        border-top: 1px solid rgba(0,0,0,.06);
    }
    .lux-prem-footer-bottom > span {
        font-size: 12px !important;
        color: #9a9a9a !important;
    }
    .lux-prem-footer-bottom-links { display: flex; gap: 20px; }
    .lux-prem-footer-bottom-links a {
        font-size: 12px !important;
        color: #9a9a9a !important;
        text-decoration: none !important;
        transition: color .2s ease;
    }
    .lux-prem-footer-bottom-links a:hover { color: #111 !important; }

    @media (max-width: 1100px) {
        .lux-prem-footer-top { grid-template-columns: 1.4fr 1fr 1fr 1fr; gap: 36px; }
        .lux-prem-footer-inner { padding: 0 32px; }
        .lux-prem-footer-bottom { padding: 20px 32px 24px; }
    }

    @media (max-width: 900px) {
        .lux-prem-footer-top { grid-template-columns: 1fr 1fr 1fr; gap: 28px; }
        .lux-prem-footer-brand { grid-column: 1 / -1; }
        .lux-prem-footer-brand > p { max-width: 100%; }
    }

    @media (max-width: 768px) {
        footer.lux-prem-footer { padding: 52px 0 0 !important; }
        .lux-prem-footer-inner { padding: 0 20px; }
        .lux-prem-footer-top { grid-template-columns: 1fr 1fr; gap: 24px; padding-bottom: 40px; }
        .lux-prem-footer-brand { grid-column: 1 / -1; }
        .lux-prem-footer-brand > p { max-width: 100%; }
        .lux-prem-footer-bottom { padding: 16px 20px 80px; flex-direction: column; align-items: flex-start; gap: 10px; }
        .lux-prem-footer-bottom-links { gap: 16px; }
    }
</style>

<footer class="lux-prem-footer">
    <div class="lux-prem-footer-inner">
        <div class="lux-prem-footer-top">
            <div class="lux-prem-footer-brand">
                @if(setting('logo'))
                    <img src="{{ asset('uploads/setting/' . setting('logo')) }}" alt="{{ setting('site_name', 'AnasLuxyWorld') }}" class="lux-prem-footer-logo">
                @else
                    <div class="brand-name">AnasLuxyWorld</div>
                @endif
                <p>Premium cozy lighting crafted to elevate your space with elegance and warmth.</p>
                <div class="lux-prem-social">
                    @if(!empty(setting('facebook')))
                        <a href="{{ setting('facebook') }}" target="_blank" rel="noopener" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                    @endif
                    @if(!empty(setting('instagram')))
                        <a href="{{ setting('instagram') }}" target="_blank" rel="noopener" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    @endif
                    @if(!empty(setting('twitter')))
                        <a href="{{ setting('twitter') }}" target="_blank" rel="noopener" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                    @endif
                    @if(!empty(setting('youtube')))
                        <a href="{{ setting('youtube') }}" target="_blank" rel="noopener" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                    @endif
                    @if(!empty(setting('whatsapp')))
                        <a href="https://wa.me/{{ setting('whatsapp') }}" target="_blank" rel="noopener" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                    @endif
                    @if(!empty(setting('linkedin')))
                        <a href="{{ setting('linkedin') }}" target="_blank" rel="noopener" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                    @endif
                </div>
            </div>

            <div class="lux-prem-footer-col">
                <h5>Product</h5>
                <ul>
                    <li><a href="{{ route('product') }}">Shop All</a></li>
                    <li><a href="{{ route('categories_all') }}">Categories</a></li>
                    <li><a href="{{ route('product') }}">New Arrivals</a></li>
                </ul>
            </div>

            <div class="lux-prem-footer-col">
                <h5>Resources</h5>
                <ul>
                    <li><a href="{{ route('blogs') }}">Blog</a></li>
                    <li><a href="{{ route('contact') }}">Support</a></li>
                    <li><a href="{{ route('track') }}">Track Order</a></li>
                </ul>
            </div>

            <div class="lux-prem-footer-col">
                <h5>Company</h5>
                <ul>
                    <li><a href="{{ route('page', ['slug' => 'about']) }}">About</a></li>
                    <li><a href="{{ route('contact') }}">Contact</a></li>
                    <li><a href="{{ route('page', ['slug' => 'refund-policy']) }}">Refund Policy</a></li>
                    <li><a href="{{ route('page', ['slug' => 'privacy-policy']) }}">Privacy Policy</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="lux-prem-footer-bottom">
        <span>© {{ date('Y') }} AnasLuxyWorld. All rights reserved.</span>
        <div class="lux-prem-footer-bottom-links">
            <a href="{{ route('page', ['slug' => 'privacy-policy']) }}">Privacy</a>
            <a href="{{ route('page', ['slug' => 'terms-and-conditions']) }}">Terms</a>
        </div>
    </div>
</footer>
