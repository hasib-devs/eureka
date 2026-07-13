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
</div>
