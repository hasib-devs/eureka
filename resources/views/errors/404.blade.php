@php
    [$app, $route] = match (auth()->user()?->is_admin) {
        true => ['layouts.admin.app', route('admin.dashboard')],
        false => ['layouts.frontend.app', route('home')],
        default => ['layouts.frontend.app', route('home')],
    };
@endphp

@extends($app)

@section('title', 'Page Not Found')

@section('content')
    <section class="e404-wrap">
        <div class="e404-card">
            <div class="e404-code">404</div>
            <h1 class="e404-title">This page has wandered off</h1>
            <p class="e404-text">
                The page you&rsquo;re looking for doesn&rsquo;t exist or may have moved.
                Let&rsquo;s get you back to {{ setting('site_title') ?: 'Anas Luxy World' }}.
            </p>
            <div class="e404-actions">
                <a href="{{ $route }}" class="e404-btn e404-btn-primary">Back to Home</a>
                <a href="{{ route('product') }}" class="e404-btn e404-btn-ghost">Browse Shop</a>
            </div>
        </div>
    </section>

    <style>
        .e404-wrap {
            min-height: 60vh; display: grid; place-items: center;
            padding: 60px 20px; font-family: var(--font-store, "Muli", sans-serif);
        }
        .e404-card { text-align: center; max-width: 560px; }
        .e404-code {
            font-size: clamp(90px, 22vw, 180px); font-weight: 800; line-height: 1;
            color: var(--primary_color, #f85606); letter-spacing: -4px;
        }
        .e404-title { margin: 8px 0 10px; font-size: clamp(22px, 5vw, 30px); font-weight: 700; color: #1a1a1a; }
        .e404-text { margin: 0 auto 26px; max-width: 440px; color: #5b5b5b; font-size: 15px; line-height: 1.6; }
        .e404-actions { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }
        .e404-btn {
            display: inline-flex; align-items: center; justify-content: center;
            padding: 12px 26px; border-radius: 10px; font-size: 14px; font-weight: 600;
            text-decoration: none; transition: transform .12s ease, box-shadow .12s ease, background .2s ease;
        }
        .e404-btn-primary { background: var(--primary_color, #f85606); color: #fff; }
        .e404-btn-primary:hover { transform: translateY(-1px); box-shadow: 0 8px 18px rgba(0, 0, 0, .15); color: #fff; }
        .e404-btn-ghost { background: transparent; color: #1a1a1a; border: 1.5px solid #1a1a1a; }
        .e404-btn-ghost:hover { background: #1a1a1a; color: #fff; }
    </style>
@endsection
