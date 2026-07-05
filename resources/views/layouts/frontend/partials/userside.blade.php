@php
    $u = auth()->user();
    $initials = collect(explode(' ', trim($u?->name ?? 'U')))
        ->filter()->take(2)->map(fn ($p) => mb_strtoupper(mb_substr($p, 0, 1)))->implode('');
    $nav = [
        ['route' => 'dashboard',     'label' => 'Dashboard',           'icon' => 'fa-th-large'],
        ['route' => 'account',       'label' => 'My Account',          'icon' => 'fa-user'],
        ['route' => 'order',         'label' => 'Orders',              'icon' => 'fa-box'],
        ['route' => 'returns',       'label' => 'Returns',             'icon' => 'fa-undo'],
        ['route' => 'download',      'label' => 'Download',            'icon' => 'fa-download'],
        ['route' => 'wishlist',      'label' => 'Wishlist',            'icon' => 'fa-heart'],
        ['route' => 'ticket',        'label' => 'Support Ticket',      'icon' => 'fa-headset'],
        ['route' => 'ads.index',     'label' => 'Sell Old Product',    'icon' => 'fa-tag'],
        ['route' => 'ads.list',      'label' => 'My Old Product',      'icon' => 'fa-boxes'],
        ['route' => 'user_blog',     'label' => 'My Blogs',            'icon' => 'fa-newspaper'],
        ['route' => 'myrefer',       'label' => 'My Refer',            'icon' => 'fa-share-alt'],
        ['route' => 'redem.index',   'label' => 'Point to Wallet',     'icon' => 'fa-wallet'],
        ['route' => 'email.verify',  'label' => 'Verify or Change Email','icon' => 'fa-envelope'],
        ['route' => 'pass-change',   'label' => 'Password Change',     'icon' => 'fa-lock'],
    ];
@endphp

<style>
    /* Consistent top spacing for all account pages that use .customar-dashboard */
    .customar-dashboard { padding: 32px 0 56px; }

    .us-card {
        background: #fff;
        border: 1px solid #ececec;
        border-radius: 16px;
        box-shadow: 0 1px 2px rgba(16, 24, 40, .04), 0 1px 3px rgba(16, 24, 40, .04);
        overflow: hidden;
        font-family: var(--font-store, "Muli", sans-serif);
    }
    .us-head {
        display: flex; align-items: center; gap: 12px;
        padding: 18px 18px 16px;
        background: linear-gradient(135deg, #fbf7ec 0%, #fff 70%);
        border-bottom: 1px solid #f0ece0;
    }
    .us-avatar {
        flex: 0 0 auto; width: 46px; height: 46px; border-radius: 50%;
        display: grid; place-items: center;
        background: linear-gradient(135deg, #c9a24b, #9c7c2e);
        color: #fff; font-weight: 700; font-size: 16px; letter-spacing: .5px;
        box-shadow: 0 2px 6px rgba(156, 124, 46, .35);
    }
    .us-head .us-name { font-size: 15px; font-weight: 700; color: #1a1a1a; line-height: 1.2; margin: 0; }
    .us-head .us-role { font-size: 11.5px; color: #9a8a5f; text-transform: uppercase; letter-spacing: .6px; margin: 2px 0 0; }
    .us-nav { padding: 10px; display: flex; flex-direction: column; gap: 2px; }
    .us-nav a {
        display: flex; align-items: center; gap: 12px;
        padding: 10px 12px; border-radius: 10px;
        font-size: 14px; color: #4b5563; text-decoration: none;
        transition: background .15s ease, color .15s ease;
    }
    .us-nav a i { width: 18px; text-align: center; font-size: 15px; color: #9ca3af; transition: color .15s ease; }
    .us-nav a:hover { background: #faf6ea; color: #8a6d21; }
    .us-nav a:hover i { color: #b8964a; }
    .us-nav a.is-active { background: #f7efd8; color: #7a5f18; font-weight: 700; }
    .us-nav a.is-active i { color: #b8964a; }
    .us-logout { display: flex; align-items: center; gap: 12px; padding: 10px 12px; border-radius: 10px;
        font-size: 14px; color: #b91c1c; text-decoration: none; transition: background .15s ease; }
    .us-logout i { width: 18px; text-align: center; font-size: 15px; }
    .us-logout:hover { background: #fef2f2; color: #991b1b; }
    .us-divider { height: 1px; background: #f0f0f0; margin: 8px 6px; }
    .us-cta { padding: 12px 14px 16px; }
    .us-cta a {
        display: flex; align-items: center; justify-content: center; gap: 8px;
        width: 100%; padding: 11px 14px; border-radius: 11px;
        background: linear-gradient(135deg, #c9a24b, #a6811f);
        color: #fff; font-weight: 700; font-size: 13.5px; text-decoration: none;
        box-shadow: 0 4px 10px rgba(166, 129, 31, .28); transition: transform .12s ease, box-shadow .12s ease;
    }
    .us-cta a:hover { transform: translateY(-1px); box-shadow: 0 6px 14px rgba(166, 129, 31, .34); color: #fff; }

    /* Premium "Build your website" promo */
    .us-promo {
        margin: 14px; padding: 16px 16px 15px; border-radius: 16px;
        background: linear-gradient(155deg, #1c1a15 0%, #2a2620 55%, #14120e 100%);
        position: relative; overflow: hidden;
        box-shadow: 0 8px 20px rgba(20, 18, 14, .28);
    }
    .us-promo::before {
        content: ""; position: absolute; top: -40px; right: -30px; width: 120px; height: 120px;
        background: radial-gradient(circle, rgba(201,162,75,.45), transparent 70%);
    }
    .us-promo-eyebrow { position: relative; font-size: 10.5px; letter-spacing: 1.2px; text-transform: uppercase;
        color: #d4b45f; font-weight: 700; margin: 0 0 4px; }
    .us-promo-title { position: relative; font-size: 16.5px; font-weight: 800; color: #fff; line-height: 1.25; margin: 0; }
    .us-promo-sub { position: relative; font-size: 12px; color: #b9b2a3; margin: 5px 0 13px; line-height: 1.4; }
    .us-promo-actions { position: relative; display: flex; gap: 9px; }
    .us-promo-btn {
        flex: 1 1 auto; display: inline-flex; align-items: center; justify-content: center; gap: 7px;
        padding: 10px 12px; border-radius: 11px; text-decoration: none;
        background: linear-gradient(135deg, #e2c162, #b98f22); color: #1a1204; font-weight: 800; font-size: 13px;
        box-shadow: 0 4px 12px rgba(201,162,75,.35); transition: transform .12s ease, box-shadow .12s ease;
    }
    .us-promo-btn:hover { transform: translateY(-1px); box-shadow: 0 7px 16px rgba(201,162,75,.45); color: #1a1204; }
    .us-promo-wa {
        flex: 0 0 auto; width: 40px; height: 40px; display: grid; place-items: center; border-radius: 11px;
        background: #25d366; color: #fff; font-size: 18px; text-decoration: none;
        box-shadow: 0 4px 12px rgba(37,211,102,.4); transition: transform .12s ease, box-shadow .12s ease;
    }
    .us-promo-wa:hover { transform: translateY(-1px); box-shadow: 0 7px 16px rgba(37,211,102,.5); color: #fff; }
</style>

<div class="us-card">
    <div class="us-head">
        <div class="us-avatar">{{ $initials ?: 'U' }}</div>
        <div>
            <p class="us-name">{{ $u?->name }}</p>
            <p class="us-role">{{ $u?->role_id == 2 ? 'Vendor' : 'Customer' }}</p>
        </div>
    </div>

    <nav class="us-nav">
        @foreach ($nav as $item)
            <a href="{{ route($item['route']) }}" class="{{ request()->routeIs($item['route']) ? 'is-active' : '' }}">
                <i class="fas {{ $item['icon'] }}"></i><span>{{ $item['label'] }}</span>
            </a>
        @endforeach

        <div class="us-divider"></div>

        <a href="{{ route('logout') }}" class="us-logout"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fas fa-sign-out-alt"></i><span>Log Out</span>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
    </nav>

    @auth
        @if (auth()->user()->role_id == 2)
            <div class="us-cta">
                <a href="{{ routeHelper('dashboard') }}"><i class="fas fa-th-large"></i> Vendor Dashboard</a>
            </div>
        @endif
    @endauth

    <div class="us-promo">
        <p class="us-promo-eyebrow">Reliable UK Solutions</p>
        <p class="us-promo-title">Build your website now</p>
        <p class="us-promo-sub">Premium, custom-built websites for your business. Let’s bring your idea online.</p>
        <div class="us-promo-actions">
            <a class="us-promo-btn" href="https://reliableuksolutions.com" target="_blank" rel="noopener">
                Get Started <i class="fas fa-arrow-right"></i>
            </a>
            <a class="us-promo-wa" href="https://wa.me/8801926914445" target="_blank" rel="noopener" aria-label="Chat on WhatsApp">
                <i class="fab fa-whatsapp"></i>
            </a>
        </div>
    </div>
</div>
