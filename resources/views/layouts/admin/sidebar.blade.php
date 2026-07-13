        @php
            // Returns 'active' when the current request path matches any of the given pattern(s).
            $navActive = fn ($patterns) => request()->is($patterns) ? 'active' : '';
        @endphp

        <aside class="dashboard-sidebar">
            @php
                $__user = auth()->user();
                $__hasAvatar = $__user && $__user->avatar && file_exists(public_path('uploads/admin/'.$__user->avatar));
            @endphp

            {{-- Single profile section: identity + account/theme/collapse actions --}}
            <div class="profile-block">
                <div class="profile">
                    <form action="{{ route('admin.profile.avatar') }}" method="POST" enctype="multipart/form-data" id="avatarForm">
                        @csrf
                        <label class="profile-avatar" title="Change profile picture">
                            @if ($__hasAvatar)
                                <img src="{{ asset('uploads/admin/'.$__user->avatar) }}" alt="Profile">
                            @else
                                <span class="profile-avatar-fallback">{{ strtoupper(mb_substr($__user->name ?? 'A', 0, 1)) }}</span>
                            @endif
                            <span class="profile-avatar-edit"><i class="bx bx-camera"></i></span>
                            <input type="file" name="avatar" accept="image/*" hidden onchange="this.form.submit()">
                        </label>
                    </form>
                    <div class="profile-meta">
                        <h4>{{ $__user->name ?? 'Admin' }}</h4>
                        <p>Administrator</p>
                    </div>
                </div>

                <div class="account-actions">
                    <a href="{{ url('admin/profile') }}" class="account-link {{ $navActive('admin/profile*') }}" title="My profile">
                        <i class="bx bx-user-circle"></i><span>Profile</span>
                    </a>
                    <button type="button" class="account-link" aria-label="Toggle dark mode" title="Toggle dark mode" data-theme-toggle>
                        <i class="bx bx-moon"></i>
                    </button>
                    <button type="button" class="account-link" aria-label="Collapse sidebar" title="Collapse sidebar" data-sidebar-toggle>
                        <i class="bx bx-menu"></i>
                    </button>
                    @if (Route::has('logout'))
                        <form method="POST" action="{{ route('logout') }}" class="logout-form">
                            @csrf
                            <button type="submit" class="logout-btn" title="Logout">
                                <i class="bx bx-log-out"></i>
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <nav class="nav-container">

                <!-- HOME -->
                <div class="menu-section">
                    <label data-section="home"><span>HOME</span><i class="bx bx-chevron-down chev"></i></label>
                    <ul>
                        <li class="{{ $navActive('admin/dashboard') }}"
                            onclick="window.location='{{ url('admin/dashboard') }}'">
                            <i class="bx bxs-dashboard"></i> Dashboard
                        </li>
                    </ul>
                </div>

                <!-- ORDERS -->
                <div class="menu-section">
                    <label data-section="orders"><span>ORDERS</span><i class="bx bx-chevron-down chev"></i></label>
                    <ul>
                        <li class="{{ $navActive('admin/order') || $navActive('admin/order/*') ? 'active' : '' }}"
                            onclick="window.location='{{ url('admin/order') }}'">
                            <i class="bx bx-cart-alt"></i> Orders
                        </li>
                        <li class="{{ $navActive('admin/incomplete-leads*') }}"
                            onclick="window.location='{{ url('admin/incomplete-leads') }}'">
                            <i class="bx bx-error-alt"></i> Incomplete Orders
                        </li>
                        <li class="{{ $navActive('admin/invoices') || $navActive('admin/invoices/*') ? 'active' : '' }}"
                            onclick="window.location='{{ url('admin/invoices') }}'">
                            <i class="bx bx-receipt"></i> Invoices
                        </li>
                    </ul>
                </div>

                <!-- CATALOG -->
                <div class="menu-section">
                    <label data-section="catalog"><span>CATALOG</span><i class="bx bx-chevron-down chev"></i></label>
                    <ul>
                        <li class="{{ $navActive('admin/product*') }}"
                            onclick="window.location='{{ url('admin/product') }}'">
                            <i class="bx bx-package"></i> Products
                        </li>
                        <li class="{{ $navActive('admin/variable-products*') }}"
                            onclick="window.location='{{ url('admin/variable-products') }}'">
                            <i class="bx bx-layer"></i> Variable Products
                        </li>
                        <li class="{{ $navActive('admin/related-products*') }}"
                            onclick="window.location='{{ url('admin/related-products') }}'">
                            <i class="bx bx-link"></i> Related Products
                        </li>
                        <li class="{{ $navActive('admin/classic*') }}"
                            onclick="window.location='{{ url('admin/classic/list') }}'">
                            <i class="bx bx-star"></i> Classic Products
                        </li>
                        <li class="{{ $navActive('admin/category*') }}"
                            onclick="window.location='{{ url('admin/category') }}'">
                            <i class="bx bx-category"></i> Categories
                        </li>
                        <li class="{{ $navActive('admin/sub-category*') }}"
                            onclick="window.location='{{ url('admin/sub-category') }}'">
                            <i class="bx bx-subdirectory-right"></i> Sub Categories
                        </li>
                        <li class="{{ $navActive('admin/mini-categories*') }}"
                            onclick="window.location='{{ url('admin/mini-categories/list') }}'">
                            <i class="bx bx-grid-small"></i> Mini Categories
                        </li>
                        <li class="{{ $navActive('admin/extra-categories*') }}"
                            onclick="window.location='{{ url('admin/extra-categories/list') }}'">
                            <i class="bx bx-grid-alt"></i> Extra Categories
                        </li>
                        <li class="{{ $navActive('admin/brand*') }}"
                            onclick="window.location='{{ url('admin/brand') }}'">
                            <i class="bx bx-bookmark"></i> Brands
                        </li>
                        <li class="{{ $navActive('admin/collection*') }}"
                            onclick="window.location='{{ url('admin/collection') }}'">
                            <i class="bx bx-collection"></i> Collections
                        </li>
                        <li class="{{ $navActive('admin/attribute*') }}"
                            onclick="window.location='{{ url('admin/attribute/list') }}'">
                            <i class="bx bx-list-ul"></i> Attributes
                        </li>
                        <li class="{{ $navActive('admin/color*') }}"
                            onclick="window.location='{{ url('admin/color') }}'">
                            <i class="bx bx-palette"></i> Colors
                        </li>
                        <li class="{{ $navActive('admin/size*') }}"
                            onclick="window.location='{{ url('admin/size') }}'">
                            <i class="bx bx-ruler"></i> Sizes
                        </li>
                        <li class="{{ $navActive('admin/tag*') }}"
                            onclick="window.location='{{ url('admin/tag') }}'">
                            <i class="bx bx-purchase-tag-alt"></i> Tags
                        </li>
                    </ul>
                </div>

                <!-- CUSTOMERS -->
                <div class="menu-section">
                    <label data-section="customers"><span>CUSTOMERS</span><i class="bx bx-chevron-down chev"></i></label>
                    <ul>
                        <li class="{{ $navActive('admin/customer*') }}"
                            onclick="window.location='{{ url('admin/customer') }}'">
                            <i class="bx bx-user"></i> Customers
                        </li>
                        <li class="{{ $navActive('admin/subscribe*') }}"
                            onclick="window.location='{{ url('admin/subscribe') }}'">
                            <i class="bx bx-envelope"></i> Subscribers
                        </li>
                    </ul>
                </div>

                <!-- SUPPORT / INBOX -->
                <div class="menu-section">
                    <label data-section="support"><span>SUPPORT</span><i class="bx bx-chevron-down chev"></i></label>
                    <ul>
                        <li class="{{ $navActive('admin/connection*') }}"
                            onclick="window.location='{{ url('admin/connection/live-chat') }}'">
                            <i class="bx bx-message-dots"></i> Live Chat
                        </li>
                        <li class="{{ $navActive('admin/ticket*') }}"
                            onclick="window.location='{{ url('admin/ticket') }}'">
                            <i class="bx bx-support"></i> Support Tickets
                        </li>
                        <li class="{{ $navActive('admin/mail*') }}"
                            onclick="window.location='{{ url('admin/mail') }}'">
                            <i class="bx bx-mail-send"></i> Mail
                        </li>
                    </ul>
                </div>

                <!-- MARKETING -->
                <div class="menu-section">
                    <label data-section="marketing"><span>MARKETING</span><i class="bx bx-chevron-down chev"></i></label>
                    <ul>
                        <li class="{{ $navActive('admin/campaing*') }}"
                            onclick="window.location='{{ url('admin/campaing/list') }}'">
                            <i class="bx bx-bullseye"></i> Campaigns
                        </li>
                        <li class="{{ $navActive('admin/coupon*') }}"
                            onclick="window.location='{{ url('admin/coupon') }}'">
                            <i class="bx bx-cut"></i> Coupons
                        </li>
                    </ul>
                </div>

                <!-- CONTENT -->
                <div class="menu-section">
                    <label data-section="content"><span>CONTENT</span><i class="bx bx-chevron-down chev"></i></label>
                    <ul>
                        <li class="{{ $navActive('admin/banner*') }}"
                            onclick="window.location='{{ url('admin/banner') }}'">
                            <i class="bx bx-image"></i> Banners
                        </li>
                        <li class="{{ $navActive('admin/slider') || $navActive('admin/slider/*') ? 'active' : '' }}"
                            onclick="window.location='{{ url('admin/slider') }}'">
                            <i class="bx bx-slideshow"></i> Sliders
                        </li>
                        <li class="{{ $navActive('admin/sliderone*') }}"
                            onclick="window.location='{{ url('admin/sliderone') }}'">
                            <i class="bx bx-layer"></i> Sliders One
                        </li>
                        <li class="{{ $navActive('admin/video*') }}"
                            onclick="window.location='{{ url('admin/video') }}'">
                            <i class="bx bx-video"></i> Homepage Video
                        </li>
                        <li class="{{ $navActive('admin/mobile-menu*') }}"
                            onclick="window.location='{{ url('admin/mobile-menu') }}'">
                            <i class="bx bx-mobile-alt"></i> Mobile Menu
                        </li>
                        <li class="{{ $navActive('admin/page*') }}"
                            onclick="window.location='{{ url('admin/pages') }}'">
                            <i class="bx bx-file"></i> Pages
                        </li>
                        <li class="{{ $navActive('admin/notice*') }}"
                            onclick="window.location='{{ url('admin/notice') }}'">
                            <i class="bx bx-info-circle"></i> Notice
                        </li>
                        <li class="{{ $navActive('admin/blog*') }}"
                            onclick="window.location='{{ url('admin/blogs') }}'">
                            <i class="bx bx-news"></i> Blogs
                        </li>
                    </ul>
                </div>

                <!-- TEAM -->
                <div class="menu-section">
                    <label data-section="team"><span>TEAM</span><i class="bx bx-chevron-down chev"></i></label>
                    <ul>
                        <li class="{{ $navActive('admin/staf*') }}"
                            onclick="window.location='{{ url('admin/staf/list') }}'">
                            <i class="bx bx-group"></i> Staff
                        </li>
                        <li class="{{ $navActive('admin/author*') }}"
                            onclick="window.location='{{ url('admin/author') }}'">
                            <i class="bx bx-edit"></i> Authors
                        </li>
                        <li class="{{ $navActive('admin/vendor') || $navActive('admin/vendor/product*') || $navActive('admin/vendor/change-pass*') ? 'active' : '' }}"
                            onclick="window.location='{{ url('admin/vendor') }}'">
                            <i class="bx bx-store-alt"></i> Vendors
                        </li>
                        <li class="{{ $navActive('admin/vendor/withdraw*') }}"
                            onclick="window.location='{{ url('admin/vendor/withdraw/list') }}'">
                            <i class="bx bx-wallet"></i> Vendor Withdrawals
                        </li>
                    </ul>
                </div>

                <!-- SETTINGS -->
                <div class="menu-section">
                    <label data-section="settings"><span>SETTINGS</span><i class="bx bx-chevron-down chev"></i></label>
                    <ul>
                        <li class="{{ $navActive('admin/setting') }}"
                            onclick="window.location='{{ url('admin/setting') }}'">
                            <i class="bx bx-cog"></i> General
                        </li>
                        <li class="{{ $navActive('admin/setting/site_info') }}"
                            onclick="window.location='{{ url('admin/setting/site_info') }}'">
                            <i class="bx bx-store"></i> Store Info
                        </li>
                        <li class="{{ $navActive('admin/setting/shop_settings') }}"
                            onclick="window.location='{{ url('admin/setting/shop_settings') }}'">
                            <i class="bx bx-slider"></i> Shop Settings
                        </li>
                        <li class="{{ $navActive('admin/setting/layout') }}"
                            onclick="window.location='{{ url('admin/setting/layout') }}'">
                            <i class="bx bx-layout"></i> Layout
                        </li>
                        <li class="{{ $navActive('admin/setting/color') }}"
                            onclick="window.location='{{ url('admin/setting/color') }}'">
                            <i class="bx bx-paint"></i> Theme Color
                        </li>
                        <li class="{{ $navActive('admin/setting/header') }}"
                            onclick="window.location='{{ url('admin/setting/header') }}'">
                            <i class="bx bx-heading"></i> Header
                        </li>
                        <li class="{{ $navActive('admin/setting/seo') }}"
                            onclick="window.location='{{ url('admin/setting/seo') }}'">
                            <i class="bx bx-search-alt"></i> SEO
                        </li>
                        <li class="{{ $navActive('admin/setting/getway') }}"
                            onclick="window.location='{{ url('admin/setting/getway') }}'">
                            <i class="bx bx-credit-card"></i> Payment Gateway
                        </li>
                        <li class="{{ $navActive('admin/setting/courier') }}"
                            onclick="window.location='{{ url('admin/setting/courier') }}'">
                            <i class="bx bx-package"></i> Courier
                        </li>
                        <li class="{{ $navActive('admin/setting/mailsmsapireglog') }}"
                            onclick="window.location='{{ url('admin/setting/mailsmsapireglog') }}'">
                            <i class="bx bx-message-square-detail"></i> Mail / SMS &amp; API
                        </li>
                        <li class="{{ $navActive('admin/setting/home') }}"
                            onclick="window.location='{{ url('admin/setting/home') }}'">
                            <i class="bx bx-home"></i> Home Page
                        </li>
                        <li class="{{ $navActive('admin/mission-control*') }}"
                            onclick="window.location='{{ url('admin/mission-control') }}'">
                            <i class="bx bx-bot"></i> Wedevs AI
                            @if (auth()->user()->isTaskExecutor())
                                @php $mcAwaiting = \App\Models\Task::where('status', \App\Models\Task::STATUS_AWAITING)->count(); @endphp
                                @if ($mcAwaiting)
                                    <span class="mc-nav-badge">{{ $mcAwaiting }}</span>
                                @endif
                            @endif
                        </li>
                    </ul>
                </div>

                <!-- SYSTEM -->
                <div class="menu-section">
                    <label data-section="system"><span>SYSTEM</span><i class="bx bx-chevron-down chev"></i></label>
                    <ul>
                        <li class="{{ $navActive('admin/ip-block*') }}"
                            onclick="window.location='{{ url('admin/ip-block') }}'">
                            <i class="bx bx-shield-x"></i> IP Block
                        </li>
                        <li class="{{ $navActive('admin/update*') }}"
                            onclick="window.location='{{ url('admin/update') }}'">
                            <i class="bx bx-cloud-download"></i> System Update
                        </li>
                        <li class="{{ $navActive('admin/php_info*') }}"
                            onclick="window.location='{{ url('admin/php_info') }}'">
                            <i class="bx bx-info-square"></i> PHP Info
                        </li>
                    </ul>
                </div>

            </nav>
        </aside>

        <style>
            /* ==== Sidebar theme ============================================
               Light mode: the original white sidebar look (the base styles in
               dashboard-assets/style.css supply the white panel and the gold
               hover/active states). Dark mode: the corporate graphite theme,
               scoped under html.admin-dark. Structure/behavior hooks (.rail,
               .collapsed, data attrs) are theme-neutral. */
            /* Site brand display font (self-hosted) — used for the profile name.
               Declared here too because the sidebar renders on every admin page,
               not just Mission Control where the @font-face also lives. */
            @font-face {
                font-family: 'Unbounded';
                font-style: normal;
                font-weight: 700;
                font-display: swap;
                src: url('{{ asset('fonts/unbounded-700.woff2') }}') format('woff2');
            }
            .dashboard-sidebar {
                font-family: var(--font-sans, "Instrument Sans", system-ui, sans-serif);
            }

            /* Mission Control: unread-tasks badge for the executor */
            .mc-nav-badge {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                min-width: 18px;
                height: 18px;
                padding: 0 5px;
                margin-left: auto;
                border-radius: 9px;
                background: #ef4444;
                color: #fff;
                font-size: 11px;
                font-weight: 700;
                line-height: 1;
                animation: mcNavPulse 1.6s ease-in-out infinite;
            }
            @keyframes mcNavPulse {
                0%, 100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.55); }
                50% { box-shadow: 0 0 0 6px rgba(239, 68, 68, 0); }
            }

            /* ---- Single profile section (structure + light colors) ---- */
            .profile-block {
                margin: 0 16px 18px 16px;
                border: 1px solid rgba(0, 0, 0, 0.08);
                border-radius: 14px;
                background: #fff;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
                overflow: hidden;
            }
            .profile {
                display: flex;
                align-items: center;
                gap: 12px;
                margin: 0;
                padding: 14px;
                background: transparent;
                border-radius: 0;
                box-shadow: none;
            }
            .profile #avatarForm { margin: 0; }
            .profile-avatar {
                position: relative;
                display: block;
                width: 44px;
                height: 44px;
                border-radius: 12px;
                overflow: hidden;
                cursor: pointer;
                flex-shrink: 0;
                border: 1px solid rgba(0, 0, 0, 0.12);
            }
            .profile-avatar img { width: 100%; height: 100%; object-fit: cover; display: block; }
            .profile-avatar-fallback {
                width: 100%;
                height: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
                background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
                color: #334155;
                font-weight: 700;
                font-size: 1.15rem;
            }
            .profile-avatar-edit {
                position: absolute;
                left: 0; right: 0; bottom: 0;
                height: 42%;
                background: rgba(0, 0, 0, 0.55);
                color: #fff;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 0.85rem;
                opacity: 0;
                transition: opacity 0.18s ease;
            }
            .profile-avatar:hover .profile-avatar-edit { opacity: 1; }
            .profile-meta { min-width: 0; }
            .profile-meta h4 {
                font-family: 'Unbounded', var(--font-sans, "Instrument Sans", system-ui, sans-serif);
                font-size: 0.82rem;
                font-weight: 700;
                color: #1e293b;
                line-height: 1.25;
                margin: 0;
                letter-spacing: -0.2px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }
            .profile-meta p {
                font-size: 0.64rem;
                font-weight: 600;
                color: #94a3b8;
                letter-spacing: 1.3px;
                text-transform: uppercase;
                margin: 3px 0 0 0;
            }

            /* Action strip inside the profile section */
            .account-actions {
                display: flex;
                gap: 6px;
                margin: 0;
                padding: 8px;
                border-top: 1px solid rgba(0, 0, 0, 0.06);
                background: #fafbfc;
            }
            .account-actions .account-link,
            .account-actions .logout-btn {
                flex: 1;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 6px;
                padding: 8px 6px;
                border-radius: 9px;
                font-size: 0.8rem;
                font-weight: 500;
                cursor: pointer;
                border: 0;
                background: transparent;
                color: #64748b;
                text-decoration: none;
                transition: background 0.16s, color 0.16s;
            }
            .account-actions .account-link i,
            .account-actions .logout-btn i { font-size: 1.05rem; }
            .account-actions .logout-form { flex: 1; margin: 0; display: flex; }
            .account-actions .logout-btn { width: 100%; color: #dc2626; }
            .account-actions .account-link:hover,
            .account-actions .account-link.active {
                background: #f1f5f9;
                color: #0f172a;
            }
            .account-actions .logout-btn:hover { background: #fee2e2; color: #b91c1c; }

            /* ---- Section labels + items (structure; light colors come from
                   the base stylesheet, incl. the original gold hover/active) ---- */
            .menu-section { margin-bottom: 14px; }
            .menu-section label {
                display: flex;
                align-items: center;
                justify-content: space-between;
                cursor: pointer;
                user-select: none;
                padding: 6px 12px 6px 0;
                border-radius: 6px;
                font-size: 0.64rem;
                font-weight: 700;
                letter-spacing: 1.6px;
                transition: color 0.15s;
            }
            .menu-section label .chev {
                font-size: 1rem;
                color: #bbb;
                transition: transform 0.2s;
            }
            .menu-section.collapsed > ul { display: none; }
            .menu-section.collapsed label .chev { transform: rotate(-90deg); }

            .dashboard-sidebar li {
                position: relative;
                font-size: 0.875rem;
                font-weight: 500;
                letter-spacing: 0.1px;
                padding: 9px 12px;
                transition: background 0.16s, color 0.16s, transform 0.16s;
            }
            .dashboard-sidebar li i {
                font-size: 1.08rem;
                width: 20px;
                text-align: center;
                flex-shrink: 0;
            }
            .dashboard-sidebar li:hover { transform: translateX(2px); }

            /* ---- Thin scrollbar ---- */
            .nav-container { scrollbar-width: thin; }
            .nav-container::-webkit-scrollbar { width: 5px; }
            .nav-container::-webkit-scrollbar-thumb { background: rgba(100, 116, 139, .3); border-radius: 999px; }
            .nav-container::-webkit-scrollbar-track { background: transparent; }

            /* ---- Icons-only (rail) mode ---- */
            .dashboard-sidebar.rail { width: 76px; min-width: 76px; }
            .dashboard-sidebar.rail .profile-block { margin: 0 10px 14px 10px; }
            .dashboard-sidebar.rail .profile { padding: 10px; justify-content: center; }
            .dashboard-sidebar.rail .profile-meta { display: none; }
            .dashboard-sidebar.rail .menu-section label { display: none; }
            .dashboard-sidebar.rail .nav-container { padding: 0 10px; }
            .dashboard-sidebar.rail .menu-section > ul { display: block; }
            .dashboard-sidebar.rail li {
                justify-content: center;
                gap: 0;
                font-size: 0;
                padding: 10px 0;
            }
            .dashboard-sidebar.rail li i { font-size: 1.35rem; }
            .dashboard-sidebar.rail .account-actions { flex-direction: column; }
            .dashboard-sidebar.rail .account-actions .account-link span { display: none; }

            /* ==== Dark mode (html.admin-dark): corporate graphite ==== */
            html.admin-dark .dashboard-sidebar {
                --admin-gold: #d4af37;
                background: #0c1322;
                border-right: 1px solid rgba(148, 163, 184, 0.12);
                backdrop-filter: none;
            }
            /* Carved 1px light-blue outline on the profile card (dark mode) */
            html.admin-dark .profile-block {
                border-color: rgba(147, 197, 253, 0.38);
                background: rgba(255, 255, 255, 0.03);
                box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.06), 0 0 0 1px rgba(147, 197, 253, 0.06);
            }
            html.admin-dark .profile-avatar { border-color: rgba(148, 163, 184, 0.35); }
            html.admin-dark .profile-avatar-fallback {
                background: linear-gradient(135deg, #334155, #1e293b);
                color: #e2e8f0;
            }
            html.admin-dark .profile-meta h4 { color: #f1f5f9; }
            html.admin-dark .profile-meta p { color: #aab7cc; }
            html.admin-dark .account-actions {
                border-top-color: rgba(148, 163, 184, 0.12);
                background: rgba(255, 255, 255, 0.02);
            }
            html.admin-dark .account-actions .account-link { color: #9aa9bf; }
            /* Profile / dark-mode / collapse icons in deep gold (logout stays red) */
            html.admin-dark .account-actions .account-link i { color: var(--admin-gold); }
            html.admin-dark .account-actions .account-link:hover,
            html.admin-dark .account-actions .account-link.active {
                background: rgba(255, 255, 255, 0.08);
                color: #fff;
            }
            html.admin-dark .account-actions .logout-btn { color: #f87171; }
            html.admin-dark .account-actions .logout-btn:hover { background: rgba(220, 38, 38, 0.18); color: #fca5a5; }
            html.admin-dark .menu-section label { color: #ffffff; }
            html.admin-dark .menu-section label:hover { color: #ffffff; }
            html.admin-dark .menu-section label .chev { color: #475569; }
            html.admin-dark .dashboard-sidebar li { color: #94a3b8; }
            /* Menu item icons in the same deep gold */
            html.admin-dark .dashboard-sidebar li i { color: var(--admin-gold); }
            html.admin-dark .dashboard-sidebar li:hover {
                background: rgba(255, 255, 255, 0.06);
                color: #f1f5f9;
                font-weight: 500;
            }
            html.admin-dark .dashboard-sidebar li.active {
                background: rgba(255, 255, 255, 0.07);
                color: #fff;
                font-weight: 600;
            }
            html.admin-dark .dashboard-sidebar li.active::before {
                content: '';
                position: absolute;
                left: 0;
                top: 20%;
                bottom: 20%;
                width: 3px;
                border-radius: 999px;
                background: #f85606;
            }
            html.admin-dark .nav-container { scrollbar-color: rgba(148,163,184,.25) transparent; }
            html.admin-dark .nav-container::-webkit-scrollbar-thumb { background: rgba(148,163,184,.25); }
        </style>

        <script>
            (function () {
                const sidebar = document.querySelector('.dashboard-sidebar');
                if (!sidebar) return;

                /* ---------- Collapse the sidebar to an icon-only rail ---------- */
                const RAIL_KEY = 'adminSidebarRail';
                const toggle = sidebar.querySelector('[data-sidebar-toggle]');

                function applyRail(on) {
                    sidebar.classList.toggle('rail', on);
                    if (toggle) {
                        toggle.setAttribute('aria-label', on ? 'Expand sidebar' : 'Collapse sidebar');
                    }
                }

                let rail = false;
                try { rail = localStorage.getItem(RAIL_KEY) === '1'; } catch (e) {}
                applyRail(rail);

                if (toggle) {
                    toggle.addEventListener('click', function () {
                        rail = !sidebar.classList.contains('rail');
                        applyRail(rail);
                        try { localStorage.setItem(RAIL_KEY, rail ? '1' : '0'); } catch (e) {}
                    });
                }

                /* ---------- Dark mode toggle ---------- */
                const THEME_KEY = 'adminTheme';
                const themeToggle = sidebar.querySelector('[data-theme-toggle]');

                function applyTheme(dark) {
                    document.documentElement.classList.toggle('admin-dark', dark);
                    if (themeToggle) {
                        const icon = themeToggle.querySelector('i');
                        if (icon) icon.className = dark ? 'bx bx-sun' : 'bx bx-moon';
                    }
                }

                applyTheme(document.documentElement.classList.contains('admin-dark'));

                if (themeToggle) {
                    themeToggle.addEventListener('click', function () {
                        const dark = !document.documentElement.classList.contains('admin-dark');
                        applyTheme(dark);
                        try { localStorage.setItem(THEME_KEY, dark ? 'dark' : 'light'); } catch (e) {}
                    });
                }

                /* ---------- Single-open accordion for the menu groups ---------- */
                const OPEN_KEY = 'adminNavOpenSection';
                const sections = Array.from(sidebar.querySelectorAll('.menu-section'));
                const nameOf = (s) => s.querySelector('label')?.dataset.section;

                function openOnly(name) {
                    sections.forEach(function (section) {
                        section.classList.toggle('collapsed', nameOf(section) !== name);
                    });
                }

                // Pick the group to open on load: the saved one, else the group that
                // holds the active page, else the first group. Only one is ever open.
                let saved = null;
                try { saved = localStorage.getItem(OPEN_KEY); } catch (e) {}
                const activeSection = sections.find(s => s.querySelector('li.active'));
                const savedSection = saved && sections.find(s => nameOf(s) === saved);

                const openName = savedSection ? saved
                    : activeSection ? nameOf(activeSection)
                    : (sections[0] ? nameOf(sections[0]) : null);
                if (openName) openOnly(openName);

                sections.forEach(function (section) {
                    const label = section.querySelector('label');
                    if (!label) return;
                    label.addEventListener('click', function () {
                        const name = label.dataset.section;
                        if (section.classList.contains('collapsed')) {
                            openOnly(name);                      // open this, close the others
                            try { localStorage.setItem(OPEN_KEY, name); } catch (e) {}
                        } else {
                            section.classList.add('collapsed');  // close the open one
                            try { localStorage.removeItem(OPEN_KEY); } catch (e) {}
                        }
                    });
                });
            })();
        </script>
