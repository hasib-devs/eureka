<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="{{ asset('dashboard-assets/style.css') }}">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        .premium-revenue-card {
            background: linear-gradient(135deg, #0f172a, #1e293b);
            border-radius: 28px;
            padding: 30px;
            margin-top: 40px;
            margin-bottom: 30px;
            color: #fff;
            box-shadow: 0 20px 45px rgba(15, 23, 42, .28);
            overflow: hidden;
            position: relative;
        }

        .premium-revenue-card:before {
            content: "";
            position: absolute;
            width: 260px;
            height: 260px;
            right: -80px;
            top: -90px;
            background: rgba(242, 210, 49, .18);
            border-radius: 50%;
            filter: blur(4px);
        }

        .premium-revenue-header {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            align-items: flex-start;
            position: relative;
            z-index: 1;
        }

        .premium-kicker {
            display: inline-block;
            background: rgba(242, 210, 49, .16);
            color: #f2d231;
            padding: 6px 14px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 12px;
        }

        .premium-revenue-header h2 {
            margin: 0;
            font-size: 26px;
            color: #fff;
        }

        .premium-revenue-header p {
            margin: 8px 0 0;
            color: rgba(255, 255, 255, .62);
        }

        .premium-total {
            font-size: 28px;
            font-weight: 800;
            color: #f2d231;
            white-space: nowrap;
        }

        .premium-bars {
            height: 220px;
            display: flex;
            align-items: flex-end;
            gap: 24px;
            margin-top: 34px;
            position: relative;
            z-index: 1;
        }

        .premium-bar-wrap {
            flex: 1;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            align-items: center;
            gap: 10px;
        }

        .premium-bar-wrap span {
            width: 100%;
            max-width: 70px;
            display: block;
            border-radius: 22px 22px 8px 8px;
            background: linear-gradient(180deg, #f2d231, #fb923c);
            box-shadow: 0 12px 30px rgba(242, 210, 49, .28);
            animation: premiumBarGrow 1.4s ease both;
        }

        .premium-bar-wrap small {
            color: rgba(255, 255, 255, .7);
            font-weight: 700;
        }

        @keyframes premiumBarGrow {
            from {
                height: 0;
                opacity: .2;
            }

            to {
                opacity: 1;
            }
        }

        @media(max-width:767px) {
            .premium-revenue-header {
                flex-direction: column;
            }

            .premium-bars {
                gap: 12px;
                height: 180px;
            }
        }
    </style>

</head>

<body>
    <div class="container">
        <aside class="sidebar">
            <div class="profile">
                <div class="rounded-full overflow-hidden w-12 h-12">
                    <img src="https://placehold.co/50" alt="User">
                </div>
                <div>
                    <h3>ANAS LUXY WORLD</h3>
                    <p>Admin</p>
                </div>
            </div>

            <nav class="nav-container">

                <!-- HOME -->
                <div class="menu-section">
                    <label>HOME</label>
                    <ul>
                        <li class="active" onclick="window.location='{{ url('admin/dashboard') }}'">
                            <i class="bx bxs-dashboard"></i> Dashboard
                        </li>
                    </ul>
                </div>

                <!-- ORDERS -->
                <div class="menu-section">
                    <label>ORDERS</label>
                    <ul>
                        <li onclick="window.location='{{ url('admin/order') }}'">
                            <i class="bx bx-cart-alt"></i> Orders
                        </li>
                        <li onclick="window.location='{{ url('admin/incomplete-leads') }}'">
                            <i class="bx bx-error-alt"></i> Incomplete Order
                        </li>
                        <li style="opacity:.6;cursor:not-allowed;">
                            <i class="bx bx-shield-alt-2"></i> Order Guard
                        </li>
                        <li style="opacity:.6;cursor:not-allowed;">
                            <i class="bx bx-phone-call"></i> Auto Call
                        </li>
                    </ul>
                </div>

                <!-- PRODUCTS -->
                <div class="menu-section">
                    <label>PRODUCTS</label>
                    <ul>
                        <li onclick="window.location='{{ url('admin/product') }}'">
                            <i class="bx bx-package"></i> Products
                        </li>
                        <li onclick="window.location='{{ url('admin/classic/list') }}'">
                            <i class="bx bx-star"></i> Classic Product
                        </li>
                        <li onclick="window.location='{{ url('admin/category') }}'">
                            <i class="bx bx-category"></i> Categories
                        </li>
                        <li onclick="window.location='{{ url('admin/collection') }}'">
                            <i class="bx bx-collection"></i> Collection
                        </li>
                        <li onclick="window.location='{{ url('admin/brand') }}'">
                            <i class="bx bx-bookmark"></i> Brands
                        </li>
                        <li onclick="window.location='{{ url('admin/attribute/list') }}'">
                            <i class="bx bx-list-ul"></i> Attributes
                        </li>
                        <li style="opacity:.6;cursor:not-allowed;">
                            <i class="bx bx-purchase-tag-alt"></i> Tags
                        </li>
                    </ul>
                </div>

                <!-- CUSTOMERS -->
                <div class="menu-section">
                    <label>CUSTOMERS</label>
                    <ul>
                        <li onclick="window.location='{{ url('admin/customer') }}'">
                            <i class="bx bx-user"></i> Customers
                        </li>
                        <li onclick="window.location='{{ url('admin/connection/live-chat') }}'">
                            <i class="bx bx-message-dots"></i> Live Chat
                        </li>
                    </ul>
                </div>

                <!-- MARKETING -->
                <div class="menu-section">
                    <label>MARKETING</label>
                    <ul>
                        <li onclick="window.location='{{ url('admin/campaing/list') }}'">
                            <i class="bx bx-bullseye"></i> Campaign
                        </li>
                        <li onclick="window.location='{{ url('admin/coupon') }}'">
                            <i class="bx bx-cut"></i> Coupon
                        </li>
                    </ul>
                </div>

                <!-- CONTENT -->
                <div class="menu-section">
                    <label>CONTENT</label>
                    <ul>
                        <li onclick="window.location='{{ url('admin/banner') }}'">
                            <i class="bx bx-image"></i> Banners
                        </li>
                        <li style="opacity:.6;cursor:not-allowed;">
                            <i class="bx bx-slideshow"></i> Sliders
                        </li>
                        <li style="opacity:.6;cursor:not-allowed;">
                            <i class="bx bx-layer"></i> Sliders One
                        </li>
                        <li onclick="window.location='{{ url('admin/notice') }}'">
                            <i class="bx bx-info-circle"></i> Notice
                        </li>
                        <li onclick="window.location='{{ url('admin/blogs') }}'">
                            <i class="bx bx-news"></i> Blogs
                        </li>
                    </ul>
                </div>

                <!-- ANALYTICS -->
                <div class="menu-section">
                    <label>ANALYTICS & TOOLS</label>
                    <ul>
                        <li onclick="window.location='{{ url('admin/incomplete-leads/stats') }}'">
                            <i class="bx bx-line-chart"></i> Tracking
                        </li>
                        <li style="opacity:.6;cursor:not-allowed;">
                            <i class="bx bx-shield-quarter"></i> Fraud Checker
                        </li>
                    </ul>
                </div>

                <!-- TEAM -->
                <div class="menu-section">
                    <label>TEAM</label>
                    <ul>
                        <li style="opacity:.6;cursor:not-allowed;">
                            <i class="bx bx-group"></i> Staff
                        </li>
                        <li onclick="window.location='{{ url('admin/author') }}'">
                            <i class="bx bx-edit"></i> Author
                        </li>
                        <li style="opacity:.6;cursor:not-allowed;">
                            <i class="bx bx-store-alt"></i> Vendor
                        </li>
                    </ul>
                </div>

                <!-- SETTINGS -->
                <div class="menu-section">
                    <label>SETTINGS</label>
                    <ul>
                        <li onclick="window.location='{{ url('admin/connection/live-chat') }}'">
                            <i class="bx bx-link-alt"></i> Connection
                        </li>
                        <li onclick="window.location='{{ url('admin/php_info') }}'">
                            <i class="bx bx-cog"></i> Settings
                        </li>
                    </ul>
                </div>

            </nav>
        </aside>

        <main class="main-content">
            <header>
                <div class="top-left">
                    <span onclick="window.open('{{ url('/') }}', '_blank')"><i class="bx bx-globe"></i> Visit
                        Site</span>
                    <span><i class="bx bx-cart"></i> Pos</span>
                </div>
                <div class="top-right">
                    <span onclick="window.location='{{ url('admin/dashboard') }}'"><i class="bx bx-user-circle"></i>
                        User</span>
                    <span id="theme-toggle" style="cursor:pointer;"><i class="bx bx-moon"></i></span>
                </div>
            </header>

            <div class="analytics-live-card">
                <div class="analytics-content">
                    <div class="analytics-text">
                        <div class="live-status">
                            <span class="pulse-dot"></span>
                            <p>Real-time Overview</p>
                        </div>
                        <h2><span id="live-active-visitors">0</span> <small>Active Visitors</small></h2>
                        <p class="analytics-subtext">Google Analytics Live Tracking</p>
                    </div>
                    <div class="analytics-chart-placeholder">
                        <div class="bar-chart">
                            <div class="bar" style="height:40%"></div>
                            <div class="bar" style="height:70%"></div>
                            <div class="bar" style="height:50%"></div>
                            <div class="bar" style="height:90%"></div>
                            <div class="bar" style="height:60%"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-grid">
                <div class="card cyan">
                    <div class="card-info">
                        <h1>{{ number_format($products) }}</h1>
                        <p>Total Products</p>
                    </div><i class="bx bx-package icon-bg"></i>
                    <div class="more-info">More info <i class="bx bx-right-arrow-circle"></i></div>
                </div>
                <div class="card orange">
                    <div class="card-info">
                        <h1>{{ number_format($quantity) }}</h1>
                        <p>Product Qty</p>
                    </div><i class="bx bx-stats icon-bg"></i>
                    <div class="more-info">More info <i class="bx bx-right-arrow-circle"></i></div>
                </div>
                <div class="card green">
                    <div class="card-info">
                        <h1>{{ number_format($orders) }}</h1>
                        <p>Total Orders</p>
                    </div><i class="bx bx-shopping-bag icon-bg"></i>
                    <div class="more-info">More info <i class="bx bx-right-arrow-circle"></i></div>
                </div>
                <div class="card red">
                    <div class="card-info">
                        <h1>{{ number_format($cancel_orders) }}</h1>
                        <p>Cancel Orders</p>
                    </div><i class="bx bx-x-circle icon-bg"></i>
                    <div class="more-info">More info <i class="bx bx-right-arrow-circle"></i></div>
                </div>
                <div class="card blue">
                    <div class="card-info">
                        <h1>{{ number_format($pending_orders) }}</h1>
                        <p>Pending Orders</p>
                    </div><i class="bx bx-time icon-bg"></i>
                    <div class="more-info">More info <i class="bx bx-right-arrow-circle"></i></div>
                </div>
                <div class="card green">
                    <div class="card-info">
                        <h1>{{ number_format($delivered_orders) }}</h1>
                        <p>Delivered Orders</p>
                    </div><i class="bx bx-check-circle icon-bg"></i>
                    <div class="more-info">More info <i class="bx bx-right-arrow-circle"></i></div>
                </div>
                <div class="card orange">
                    <div class="card-info">
                        <h1>{{ number_format($return_orders) }}</h1>
                        <p>Return Orders</p>
                    </div><i class="bx bx-undo icon-bg"></i>
                    <div class="more-info">More info <i class="bx bx-right-arrow-circle"></i></div>
                </div>
                <div class="card purple">
                    <div class="card-info">
                        <h1>৳{{ number_format($revenue_today) }}</h1>
                        <p>Revenue Today</p>
                    </div><i class="bx bx-dollar icon-bg"></i>
                    <div class="more-info">More info <i class="bx bx-right-arrow-circle"></i></div>
                </div>
                <div class="card dark-blue">
                    <div class="card-info">
                        <h1>৳{{ number_format($revenue_monthly) }}</h1>
                        <p>Revenue Monthly</p>
                    </div><i class="bx bx-wallet icon-bg"></i>
                    <div class="more-info">More info <i class="bx bx-right-arrow-circle"></i></div>
                </div>
                <div class="card cyan">
                    <div class="card-info">
                        <h1>{{ number_format($customers) }}</h1>
                        <p>Total Customers</p>
                    </div><i class="bx bx-group icon-bg"></i>
                    <div class="more-info">More info <i class="bx bx-right-arrow-circle"></i></div>
                </div>
                <div class="card green">
                    <div class="card-info">
                        <h1>{{ number_format($new_customers) }}</h1>
                        <p>New Customers</p>
                    </div><i class="bx bx-user-plus icon-bg"></i>
                    <div class="more-info">More info <i class="bx bx-right-arrow-circle"></i></div>
                </div>
                <div class="card orange">
                    <div class="card-info">
                        <h1>{{ number_format($staff_count) }}</h1>
                        <p>Staff Count</p>
                    </div><i class="bx bx-user-voice icon-bg"></i>
                    <div class="more-info">More info <i class="bx bx-right-arrow-circle"></i></div>
                </div>
                <div class="card red">
                    <div class="card-info">
                        <h1>{{ number_format($fraud_orders) }}</h1>
                        <p>Fraud Orders</p>
                    </div><i class="bx bx-shield-x icon-bg"></i>
                    <div class="more-info">More info <i class="bx bx-right-arrow-circle"></i></div>
                </div>
                <div class="card blue">
                    <div class="card-info">
                        <h1>{{ $conversion_rate }}%</h1>
                        <p>Conversion Rate</p>
                    </div><i class="bx bx-analyse icon-bg"></i>
                    <div class="more-info">More info <i class="bx bx-right-arrow-circle"></i></div>
                </div>
                <div class="card purple">
                    <div class="card-info">
                        <h1>{{ number_format($daily_visits) }}</h1>
                        <p>Daily Visits</p>
                    </div><i class="bx bx-show icon-bg"></i>
                    <div class="more-info">More info <i class="bx bx-right-arrow-circle"></i></div>
                </div>
                <div class="card dark-blue">
                    <div class="card-info">
                        <h1>{{ $bounce_rate }}%</h1>
                        <p>Bounce Rate</p>
                    </div><i class="bx bx-run icon-bg"></i>
                    <div class="more-info">More info <i class="bx bx-right-arrow-circle"></i></div>
                </div>
            </div>

            <div class="overview-section">
                <h2 class="section-title">Orders Overview</h2>

                <div class="circle-grid">
                    <div class="circle-card blue">
                        <div class="circle-content">
                            <i class="bx bx-list-check"></i>
                            <p>All Orders</p>
                            <h3>{{ number_format($orders) }} Qty</h3>
                            <h2>৳{{ number_format($revenue_monthly) }}</h2>
                        </div>
                    </div>

                    <div class="circle-card green">
                        <div class="circle-content">
                            <i class="bx bx-check-double"></i>
                            <p>Completed</p>
                            <h3>{{ number_format($delivered_orders) }} Qty</h3>
                            <h2>৳{{ number_format($revenue_monthly) }}</h2>
                        </div>
                    </div>

                    <div class="circle-card red">
                        <div class="circle-content">
                            <i class="bx bx-message-square-x"></i>
                            <p>Cancelled</p>
                            <h3>{{ number_format($cancel_orders) }} Qty</h3>
                            <h2>৳0</h2>
                        </div>
                    </div>

                    <div class="circle-card orange">
                        <div class="circle-content">
                            <i class="bx bx-pause-circle"></i>
                            <p>Pending</p>
                            <h3>{{ number_format($pending_orders) }} Qty</h3>
                            <h2>৳0</h2>
                        </div>
                    </div>

                    <div class="circle-card cyan">
                        <div class="circle-content">
                            <i class="bx bx-loader-alt bx-spin"></i>
                            <p>Processing</p>
                            <h3>{{ number_format($processing_orders) }} Qty</h3>
                            <h2>৳0</h2>
                        </div>
                    </div>
                </div>
            </div>


            <div class="premium-revenue-card">
                <div class="premium-revenue-header">
                    <div>
                        <span class="premium-kicker">Revenue Analytics</span>
                        <h2>Monthly Revenue Overview</h2>
                        <p>Smart animated sales performance</p>
                    </div>
                    <div class="premium-total">৳{{ number_format($revenue_monthly) }}</div>
                </div>

                <div class="premium-bars">
                    <div class="premium-bar-wrap"><span style="height:35%"></span><small>W1</small></div>
                    <div class="premium-bar-wrap"><span style="height:55%"></span><small>W2</small></div>
                    <div class="premium-bar-wrap"><span style="height:72%"></span><small>W3</small></div>
                    <div class="premium-bar-wrap"><span style="height:90%"></span><small>W4</small></div>
                </div>
            </div>


        </main>
    </div>

    <script src="{{ asset('dashboard-assets/script.js') }}"></script>
    <script>
        (function() {
            const url = "{{ route('visitor.count') }}";

            function updateCount() {
                fetch(url).then(res => res.json()).then(data => {
                    const count = data.active_count ?? data.count ?? data.active_users ?? 0;
                    document.getElementById('live-active-visitors').innerText = count;
                }).catch(() => {});
            }
            updateCount();
            setInterval(updateCount, 5000);
        })();
    </script>



</body>

</html>
BLADE

php artisan view:clear
