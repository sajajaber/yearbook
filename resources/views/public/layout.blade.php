<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'University Digital Yearbook')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Merriweather:wght@400;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('extra-css')

    <style>
        .site-nav { height: 60px; box-shadow: none !important; }
        .site-nav .nav-inner { height: 60px; min-height: 60px; }
        .site-nav .brand-mark { font-size: 22px; }
        .site-nav .brand-copy { font-size: 11px; padding-left: 10px; }
        .site-nav .nav-links { gap: 25px; }
        .site-nav .nav-item { font-size: 10px; }

        .site-nav.home-nav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            transform: translateY(-100%);
            transition: transform .35s ease;
            box-shadow: none !important;
        }

        .site-nav.home-nav.is-visible { transform: translateY(0); box-shadow: none !important; }

        html, body { max-width: 100%; overflow-x: hidden; }
        img, video, svg, canvas { max-width: 100%; height: auto; }

        /* Public pagination fallback. */
        .pagination,
        .graduates-pagination,
        .events-pagination,
        .media-pagination {
            display:flex;
            align-items:center;
            justify-content:center;
            flex-wrap:wrap;
            gap:6px;
            margin-top:32px;
        }
        .pagination a,
        .pagination span,
        .graduates-pagination a,
        .graduates-pagination span,
        .events-pagination a,
        .events-pagination span,
        .media-pagination a,
        .media-pagination span {
            display:inline-flex;
            align-items:center;
            justify-content:center;
            min-width:36px;
            height:36px;
            padding:0 10px;
            border:1px solid var(--line);
            border-radius:0;
            background:#fff;
            color:var(--ink);
            font:600 10px/1 "Inter",sans-serif;
            letter-spacing:.4px;
            text-decoration:none;
            transition:background .18s ease,color .18s ease,border-color .18s ease;
        }
        .pagination a:hover,
        .graduates-pagination a:hover,
        .events-pagination a:hover,
        .media-pagination a:hover {
            border-color:var(--ink);
            background:var(--ink);
            color:#fff;
        }
        .pagination .active span,
        .graduates-pagination .active,
        .events-pagination .active,
        .media-pagination .active {
            border-color:var(--ink);
            background:var(--ink);
            color:#fff;
        }
        .pagination .disabled span,
        .graduates-pagination .disabled,
        .events-pagination .disabled,
        .media-pagination .disabled {
            color:#a7b3c0;
            background:#f8fafc;
            cursor:not-allowed;
        }

        @media (max-width: 1024px) {
            .nav-inner, .dashboard-heading, .dashboard-wrap { padding-left: 24px; padding-right: 24px; }
            .content-grid { grid-template-columns: 1fr; }
            .stat-grid { grid-template-columns: repeat(2, 1fr); }
            .welcome-banner, .graduation-intro { padding: 34px; }
        }

        @media (max-width: 640px) {
            .site-nav, .site-nav .nav-inner { height: auto; min-height: 56px; }
            .nav-inner { width: 100%; padding: 10px 16px; gap: 12px; }
            .site-nav .brand-mark { font-size: 19px; }
            .site-nav .brand-copy { font-size: 9px; padding-left: 8px; letter-spacing: 1px; }
            .site-nav .nav-links { display: flex !important; flex: 1 1 auto; min-width: 0; margin-left: auto; gap: 14px; overflow-x: auto; scrollbar-width: none; }
            .site-nav .nav-links::-webkit-scrollbar { display: none; }
            .site-nav .nav-item { flex: 0 0 auto; height: 36px; font-size: 8px; letter-spacing: .8px; white-space: nowrap; }
            .dashboard-heading { padding: 30px 16px 22px; display: block; }
            .dashboard-wrap { padding: 0 16px 40px; }
            .welcome-banner, .graduation-intro { min-height: 0; padding: 28px 22px; display: block; }
            .stat-grid, .school-grid { grid-template-columns: 1fr; }
            .content-grid { grid-template-columns: 1fr; gap: 16px; }
            .panel { padding: 20px; min-width: 0; }
            .panel-header { flex-direction: column; gap: 8px; }
            .workflow-row { grid-template-columns: 1fr auto; }
            .workflow-track { grid-column: 1 / -1; width: 100%; }
            .graduation-toolbar { flex-direction: column; align-items: stretch; }
            .search-field input { width: 100%; }
            .featured-edition { grid-template-columns: 1fr; gap: 18px; }
            .featured-date { border-right: 0; border-bottom: 1px solid rgba(192,82,42,.3); padding-right: 0; padding-bottom: 14px; }
            .button { max-width: 100%; justify-content: center; }
            .public-shell table { display: block; width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; }
            .public-shell h1, .public-shell h2, .public-shell h3, .public-shell p, .public-shell a, .public-shell span, .public-shell strong, .public-shell td, .public-shell th { overflow-wrap: anywhere; }
            .public-shell input, .public-shell select, .public-shell textarea { max-width: 100%; }
            .pagination, .graduates-pagination, .events-pagination, .media-pagination { gap:5px; }
            .pagination a, .pagination span, .graduates-pagination a, .graduates-pagination span, .events-pagination a, .events-pagination span, .media-pagination a, .media-pagination span { min-width:34px; height:34px; padding:0 8px; }
        }

        @media (max-width: 380px) {
            .nav-inner { padding-left: 12px; padding-right: 12px; }
            .site-nav .nav-links { gap: 10px; }
            .site-nav .nav-item { font-size: 7px; }
            .dashboard-wrap { padding-left: 12px; padding-right: 12px; }
        }

        @media (prefers-reduced-motion: reduce) { .site-nav.home-nav { transition: none; } }
    </style>
</head>

<body>
    <div class="site-shell public-shell">
        <nav class="site-nav {{ request()->routeIs('public.home') ? 'home-nav' : '' }}">
            <div class="nav-inner">
                <a href="{{ route('public.home') }}" class="brand-lockup">
                    <span class="brand-mark">LIU</span>
                    <span class="brand-copy">Digital<br><small>Yearbook</small></span>
                </a>

                <div class="nav-links hidden sm:flex">
                    <a href="{{ route('public.timeline') }}" class="nav-item {{ request()->routeIs('public.timeline') ? 'is-active' : '' }}">Timeline</a>
                    <a href="{{ route('public.events') }}" class="nav-item {{ request()->routeIs('public.events') ? 'is-active' : '' }}">Events</a>
                    <a href="{{ route('public.graduates') }}" class="nav-item {{ request()->routeIs('public.graduates') ? 'is-active' : '' }}">Graduates</a>
                    <a href="{{ route('public.archive') }}" class="nav-item {{ request()->routeIs('public.archive') ? 'is-active' : '' }}">Archive</a>
                    <a href="{{ route('search.index') }}" class="nav-item {{ request()->routeIs('search.*') ? 'is-active' : '' }}">Search</a>
                </div>
            </div>
        </nav>

        @yield('content')
    </div>

    @stack('scripts')

    @if(request()->routeIs('public.home'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const nav = document.querySelector('.home-nav');
                if (!nav) return;
                const updateNavbar = () => nav.classList.toggle('is-visible', window.scrollY > 40);
                updateNavbar();
                window.addEventListener('scroll', updateNavbar, { passive: true });
            });
        </script>
    @endif
</body>
</html>
