        @php
            // Returns 'active' when the current request path matches any of the given pattern(s).
            $navActive = fn ($patterns) => request()->is($patterns) ? 'active' : '';
        @endphp

        <aside class="dashboard-sidebar">
            <div class="sidebar-brand">
                <span class="sidebar-brand-mark"><i class="bx bxs-bolt"></i></span>
                <span class="sidebar-brand-text">
                    <strong>Eureka</strong>
                    <small>Admin Panel</small>
                </span>
                <button type="button" class="sidebar-toggle" aria-label="Collapse sidebar" data-sidebar-toggle>
                    <i class="bx bx-menu"></i>
                </button>
            </div>

            @php
                $__user = auth()->user();
                $__hasAvatar = $__user && $__user->avatar && file_exists(public_path('uploads/admin/'.$__user->avatar));
            @endphp
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

            <!-- ACCOUNT -->
            <div class="account-actions">
                <a href="{{ url('admin/profile') }}" class="account-link {{ $navActive('admin/profile*') }}">
                    <i class="bx bx-user-circle"></i> Profile
                </a>
                @if (Route::has('logout'))
                    <form method="POST" action="{{ route('logout') }}" class="logout-form">
                        @csrf
                        <button type="submit" class="logout-btn">
                            <i class="bx bx-log-out"></i> Logout
                        </button>
                    </form>
                @endif
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
                        <li class="{{ $navActive('admin/setting/docs') }}"
                            onclick="window.location='{{ url('admin/setting/docs') }}'">
                            <i class="bx bx-book"></i> Docs
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
            /* ==== Premium dark sidebar =====================================
               Overrides the light base styles in dashboard-assets/style.css
               (this block comes later in the document, so it wins at equal
               specificity). All behavior hooks (.rail, .collapsed, labels,
               data attributes) are unchanged. */
            .dashboard-sidebar {
                background: linear-gradient(180deg, #0f172a 0%, #111827 60%, #0b1120 100%);
                border-right: 1px solid rgba(148, 163, 184, 0.12);
                backdrop-filter: none;
            }

            /* ---- Brand header ---- */
            .sidebar-brand {
                display: flex;
                align-items: center;
                gap: 11px;
                margin: 0 20px 18px 20px;
                padding-bottom: 18px;
                border-bottom: 1px solid rgba(148, 163, 184, 0.12);
            }
            .sidebar-brand-mark {
                display: grid;
                place-items: center;
                width: 38px;
                height: 38px;
                border-radius: 11px;
                background: linear-gradient(135deg, #f2d231, #f85606);
                color: #111;
                font-size: 1.25rem;
                box-shadow: 0 8px 18px -6px rgba(248, 86, 6, 0.55);
                flex-shrink: 0;
            }
            .sidebar-brand-text {
                display: flex;
                flex-direction: column;
                line-height: 1.15;
                min-width: 0;
            }
            .sidebar-brand-text strong {
                color: #f8fafc;
                font-size: 1.02rem;
                font-weight: 800;
                letter-spacing: 0.2px;
            }
            .sidebar-brand-text small {
                color: #64748b;
                font-size: 0.66rem;
                font-weight: 600;
                letter-spacing: 1.4px;
                text-transform: uppercase;
                margin-top: 2px;
            }

            /* ---- Profile card ---- */
            .profile {
                background: rgba(255, 255, 255, 0.04);
                border: 1px solid rgba(148, 163, 184, 0.14);
                box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.04);
            }
            .profile-meta h4 { color: #f1f5f9 !important; }
            .profile-meta p  { color: #f2d231 !important; }
            .profile #avatarForm {
                margin: 0;
            }
            .profile-avatar {
                position: relative;
                display: block;
                width: 48px;
                height: 48px;
                border-radius: 12px;
                overflow: hidden;
                cursor: pointer;
                flex-shrink: 0;
                border: 2px solid #f2d231;
                box-shadow: 0 3px 8px rgba(0, 0, 0, 0.14);
            }
            .profile-avatar img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                display: block;
            }
            .profile-avatar-fallback {
                width: 100%;
                height: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
                background: linear-gradient(135deg, #f2d231, #e0b41a);
                color: #1a1a1a;
                font-weight: 800;
                font-size: 1.25rem;
            }
            .profile-avatar-edit {
                position: absolute;
                left: 0;
                right: 0;
                bottom: 0;
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
            .profile-avatar:hover .profile-avatar-edit {
                opacity: 1;
            }
            .profile-meta h4 {
                font-size: 0.9rem;
                font-weight: 700;
                line-height: 1.2;
                margin: 0;
            }
            .profile-meta p {
                font-size: 0.68rem;
                font-weight: 600;
                letter-spacing: 0.5px;
                text-transform: uppercase;
                margin-top: 3px;
            }

            .account-actions {
                display: flex;
                gap: 8px;
                margin: 0 20px 18px 20px;
            }

            .account-actions .account-link,
            .account-actions .logout-btn {
                flex: 1;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 6px;
                padding: 9px 10px;
                border-radius: 9px;
                font-size: 0.85rem;
                font-weight: 500;
                cursor: pointer;
                border: 1px solid rgba(148, 163, 184, 0.16);
                background: rgba(255, 255, 255, 0.04);
                color: #cbd5e1;
                text-decoration: none;
                transition: 0.2s;
            }

            .account-actions .logout-form {
                flex: 1;
                margin: 0;
            }

            .account-actions .logout-btn {
                width: 100%;
                color: #f87171;
            }

            .account-actions .account-link:hover,
            .account-actions .account-link.active {
                background: #f2d231;
                border-color: #f2d231;
                color: #111;
            }

            .account-actions .logout-btn:hover {
                background: #dc2626;
                color: #fff;
                border-color: #dc2626;
            }

            /* ---- Section labels + items (dark overrides) ---- */
            .menu-section label {
                display: flex;
                align-items: center;
                justify-content: space-between;
                cursor: pointer;
                user-select: none;
                padding: 6px 12px 6px 0;
                border-radius: 6px;
                color: #64748b;
                transition: color 0.15s;
            }
            .menu-section label:hover { color: #cbd5e1; }
            .menu-section label span { letter-spacing: 1.6px; }

            .menu-section label .chev {
                font-size: 1rem;
                color: #475569;
                transition: transform 0.2s;
            }

            .menu-section.collapsed > ul {
                display: none;
            }

            .menu-section.collapsed label .chev {
                transform: rotate(-90deg);
            }

            .dashboard-sidebar li {
                color: #94a3b8;
                position: relative;
                font-weight: 500;
                transition: background 0.16s, color 0.16s, transform 0.16s;
            }
            .dashboard-sidebar li i {
                font-size: 1.1rem;
                width: 20px;
                text-align: center;
                flex-shrink: 0;
            }
            .dashboard-sidebar li:hover {
                background: rgba(255, 255, 255, 0.06);
                color: #f1f5f9;
                font-weight: 500;
                transform: translateX(2px);
            }
            .dashboard-sidebar li.active {
                background: linear-gradient(90deg, rgba(242, 210, 49, 0.16), rgba(248, 86, 6, 0.10));
                color: #f2d231;
                font-weight: 600;
                box-shadow: inset 0 0 0 1px rgba(242, 210, 49, 0.22);
            }
            .dashboard-sidebar li.active::before {
                content: '';
                position: absolute;
                left: 0;
                top: 20%;
                bottom: 20%;
                width: 3px;
                border-radius: 999px;
                background: linear-gradient(180deg, #f2d231, #f85606);
            }

            /* ---- Thin dark scrollbar for the nav ---- */
            .nav-container { scrollbar-width: thin; scrollbar-color: rgba(148,163,184,.25) transparent; }
            .nav-container::-webkit-scrollbar { width: 5px; }
            .nav-container::-webkit-scrollbar-thumb { background: rgba(148,163,184,.25); border-radius: 999px; }
            .nav-container::-webkit-scrollbar-track { background: transparent; }

            /* Sidebar collapse toggle */
            .sidebar-toggle {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 34px;
                height: 34px;
                margin-left: auto;
                border: 1px solid rgba(148, 163, 184, 0.16);
                border-radius: 9px;
                background: rgba(255, 255, 255, 0.04);
                color: #94a3b8;
                cursor: pointer;
                font-size: 1.15rem;
                transition: 0.2s;
                flex-shrink: 0;
            }

            .sidebar-toggle:hover {
                background: #f2d231;
                border-color: #f2d231;
                color: #111;
            }

            /* ---- Icons-only (rail) mode ---- */
            .dashboard-sidebar.rail {
                width: 76px;
                min-width: 76px;
            }

            .dashboard-sidebar.rail .sidebar-brand {
                flex-direction: column;
                gap: 10px;
                margin: 0 10px 14px 10px;
                padding-bottom: 14px;
            }

            .dashboard-sidebar.rail .sidebar-toggle {
                margin: 0 auto;
            }

            /* Hide brand text, profile card and section headers when collapsed */
            .dashboard-sidebar.rail .sidebar-brand-text,
            .dashboard-sidebar.rail .profile,
            .dashboard-sidebar.rail .menu-section label {
                display: none;
            }

            .dashboard-sidebar.rail .nav-container {
                padding: 0 10px;
            }

            /* In rail mode every group is reachable, so never hide its items */
            .dashboard-sidebar.rail .menu-section > ul {
                display: block;
            }

            /* Center each item and drop its text label, keeping only the icon */
            .dashboard-sidebar.rail li {
                justify-content: center;
                gap: 0;
                font-size: 0;
                padding: 10px 0;
            }

            .dashboard-sidebar.rail li i {
                font-size: 1.35rem;
            }

            /* Account actions: stack into icon-only buttons */
            .dashboard-sidebar.rail .account-actions {
                flex-direction: column;
                margin: 0 10px 16px 10px;
            }

            .dashboard-sidebar.rail .account-actions .account-link,
            .dashboard-sidebar.rail .account-actions .logout-btn {
                font-size: 0;
                gap: 0;
                padding: 9px 0;
            }

            .dashboard-sidebar.rail .account-actions .account-link i,
            .dashboard-sidebar.rail .account-actions .logout-btn i {
                font-size: 1.2rem;
            }
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
