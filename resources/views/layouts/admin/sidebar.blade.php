        @php
            // Returns 'active' when the current request path matches any of the given pattern(s).
            $navActive = fn ($patterns) => request()->is($patterns) ? 'active' : '';
        @endphp

        <aside class="dashboard-sidebar">
            <div class="profile">
                <div>
                    <h4 class="text-lg">ANAS LUXY WORLD</h4>
                    <p>Admin Panel</p>
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
                border-radius: 8px;
                font-size: 0.85rem;
                font-weight: 500;
                cursor: pointer;
                border: 1px solid rgba(0, 0, 0, 0.08);
                background: #fff;
                color: #444;
                text-decoration: none;
                transition: 0.2s;
            }

            .account-actions .logout-form {
                flex: 1;
                margin: 0;
            }

            .account-actions .logout-btn {
                width: 100%;
                color: #c0392b;
            }

            .account-actions .account-link:hover,
            .account-actions .account-link.active {
                background: #f2d231;
                color: #000;
            }

            .account-actions .logout-btn:hover {
                background: #c0392b;
                color: #fff;
                border-color: #c0392b;
            }

            /* Collapsible menu sections */
            .menu-section label {
                display: flex;
                align-items: center;
                justify-content: space-between;
                cursor: pointer;
                user-select: none;
                padding-right: 12px;
            }

            .menu-section label .chev {
                font-size: 1rem;
                color: #bbb;
                transition: transform 0.2s;
            }

            .menu-section.collapsed > ul {
                display: none;
            }

            .menu-section.collapsed label .chev {
                transform: rotate(-90deg);
            }
        </style>

        <script>
            (function () {
                const KEY = 'adminNavCollapsed';
                let state = {};
                try { state = JSON.parse(localStorage.getItem(KEY)) || {}; } catch (e) { state = {}; }

                // If the active page isn't represented by any nav item (e.g. the Profile
                // account link), keep every group expanded rather than collapsing all.
                const anyActive = !!document.querySelector('.nav-container li.active');

                document.querySelectorAll('.dashboard-sidebar .menu-section').forEach(function (section) {
                    const label = section.querySelector('label');
                    if (!label) return;
                    const name = label.dataset.section;
                    const hasActive = !!section.querySelector('li.active');

                    // On first visit, collapse every group except the one holding the active
                    // page; if nothing is active, leave all expanded. Saved choices win after.
                    let collapsed = (name in state) ? state[name] : (anyActive && !hasActive);
                    section.classList.toggle('collapsed', collapsed);

                    label.addEventListener('click', function () {
                        const now = section.classList.toggle('collapsed');
                        state[name] = now;
                        try { localStorage.setItem(KEY, JSON.stringify(state)); } catch (e) {}
                    });
                });
            })();
        </script>
