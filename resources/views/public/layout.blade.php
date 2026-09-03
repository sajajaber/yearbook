
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'University Annual Yearbook') — {{ config('app.name', 'Yearbook') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Merriweather:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">

    <!-- Base app styles (defines --ink, --paper, --red, etc.) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* =============================================================
           Public yearbook design system
           Screen-only. The PDF export uses a completely separate,
           print-safe layout (resources/views/public/yearbook/pdf/*)
           because PDF renderers don't run CSS transitions/animations
           and have unreliable support for grid/flex — trying to reuse
           this stylesheet for the PDF would silently break layout.
           ============================================================= */

        :root {
            --transition-fast: .18s cubic-bezier(.4, 0, .2, 1);
            --transition-base: .35s cubic-bezier(.4, 0, .2, 1);
            --transition-slow: .7s cubic-bezier(.16, 1, .3, 1);
            --shadow-soft: 0 1px 3px rgba(0, 24, 61, .06), 0 1px 2px rgba(0, 24, 61, .04);
            --shadow-lift: 0 20px 40px -12px rgba(0, 24, 61, .22);
        }

        /* Progressively enhance cross-page navigation with a soft
           cross-fade in browsers that support it (Chrome/Edge 126+).
           Everywhere else this simply does nothing — no JS required,
           no fallback needed. */
        @view-transition {
            navigation: auto;
        }

        html {
            scroll-behavior: smooth;
        }

        @media (prefers-reduced-motion: reduce) {
            html { scroll-behavior: auto; }
            *, *::before, *::after {
                animation-duration: .01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: .01ms !important;
                scroll-behavior: auto !important;
            }
        }

        body {
            background: var(--paper);
        }

        ::selection {
            background: var(--red);
            color: var(--ink);
        }

        /* ---------- Header ---------- */

        .site-header {
            background: rgba(255, 255, 255, .88);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--line);
            position: sticky;
            top: 0;
            z-index: 30;
            transition: box-shadow var(--transition-base), background var(--transition-base);
        }

        .site-header.is-scrolled {
            box-shadow: var(--shadow-soft);
        }

        .site-header-inner {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 32px;
            min-height: 72px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
        }

        .site-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--ink);
            font-family: "Merriweather", Georgia, serif;
            font-weight: 700;
            font-size: 20px;
            letter-spacing: -.2px;
        }

        .site-brand-mark {
            width: 34px;
            height: 34px;
            border-radius: 9px;
            background: linear-gradient(135deg, var(--ink), #1a3f7f);
            color: #fff;
            display: grid;
            place-items: center;
            font-size: 13px;
            font-weight: 700;
        }

        .site-nav-links {
            display: flex;
            gap: 30px;
            flex-wrap: wrap;
        }

        .site-nav-links a {
            position: relative;
            color: var(--ink-soft);
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding-bottom: 4px;
            transition: color var(--transition-fast);
        }

        .site-nav-links a::after {
            content: "";
            position: absolute;
            left: 0;
            right: 100%;
            bottom: 0;
            height: 2px;
            background: var(--red);
            transition: right var(--transition-base);
        }

        .site-nav-links a:hover,
        .site-nav-links a.is-active {
            color: var(--ink);
        }

        .site-nav-links a:hover::after,
        .site-nav-links a.is-active::after {
            right: 0;
        }

        .site-footer {
            background: var(--ink);
            color: #d8e3ef;
            margin-top: 60px;
            padding: 36px 32px;
            text-align: center;
            font-size: 12px;
        }

        /* ---------- Hero ---------- */

        .hero {
            background: linear-gradient(135deg, var(--ink) 0%, #1a3f7f 100%);
            color: #fff;
            padding: 76px 20px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .hero::after {
            content: "";
            position: absolute;
            width: 480px;
            height: 480px;
            border-radius: 50%;
            border: 1px solid rgba(255, 176, 52, .25);
            right: -160px;
            top: -200px;
        }

        .hero h1 {
            font-family: "Merriweather", Georgia, serif;
            font-size: clamp(30px, 4.2vw, 46px);
            margin: 0 0 14px;
            color: #fff;
            opacity: 0;
            transform: translateY(14px);
            animation: heroIn .8s cubic-bezier(.16, 1, .3, 1) .1s forwards;
        }

        .hero p {
            color: #d8e3ef;
            font-size: 15px;
            max-width: 560px;
            margin: 0 auto;
            position: relative;
            opacity: 0;
            transform: translateY(14px);
            animation: heroIn .8s cubic-bezier(.16, 1, .3, 1) .25s forwards;
        }

        @keyframes heroIn {
            to { opacity: 1; transform: none; }
        }

        /* ---------- Layout helpers ---------- */

        .container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 32px;
        }

        .section {
            padding: 52px 20px;
        }

        .section-title {
            font-family: "Merriweather", Georgia, serif;
            font-size: 27px;
            color: var(--ink);
            margin: 0 0 8px;
        }

        .section-subtitle {
            color: var(--ink-soft);
            font-size: 14px;
            margin: 0 0 30px;
        }

        .grid {
            display: grid;
            gap: 22px;
        }

        .grid-1 { grid-template-columns: 1fr; }
        .grid-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .grid-3 { grid-template-columns: repeat(3, minmax(0, 1fr)); }
        .grid-4 { grid-template-columns: repeat(4, minmax(0, 1fr)); }

        @media (max-width: 900px) {
            .grid-3, .grid-4 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }

        @media (max-width: 600px) {
            .grid-2, .grid-3, .grid-4 { grid-template-columns: 1fr; }
        }

        /* ---------- Cards ---------- */

        .card {
            background: var(--white);
            border: 1px solid var(--line);
            border-radius: 14px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: box-shadow var(--transition-base), transform var(--transition-base), border-color var(--transition-base);
        }

        .card:hover {
            box-shadow: var(--shadow-lift);
            transform: translateY(-6px);
            border-color: transparent;
        }

        .card-image {
            height: 220px;
            background: var(--paper);
            overflow: hidden;
        }

        .card-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .6s cubic-bezier(.16, 1, .3, 1);
        }

        .card:hover .card-image img {
            transform: scale(1.07);
        }

        .card-body {
            padding: 18px;
            display: flex;
            flex-direction: column;
            gap: 8px;
            flex: 1;
        }

        .card-title {
            font-family: "Merriweather", Georgia, serif;
            font-size: 17px;
            color: var(--ink);
            margin: 0;
        }

        .card-text {
            color: var(--ink-soft);
            font-size: 13px;
            line-height: 1.6;
            margin: 0;
        }

        .card-meta {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            color: var(--ink-soft);
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .card-link {
            color: #0a66c2;
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            transition: gap var(--transition-fast);
        }

        .card-link:hover {
            gap: 8px;
        }

        /* ---------- Stats ---------- */

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 18px;
        }

        @media (max-width: 700px) {
            .stats-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }

        .stat-card {
            background: var(--white);
            border: 1px solid var(--line);
            border-radius: 14px;
            padding: 24px;
            text-align: center;
            transition: transform var(--transition-base), box-shadow var(--transition-base);
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-soft);
        }

        .stat-number {
            font-family: "Merriweather", Georgia, serif;
            font-size: 34px;
            color: var(--ink);
        }

        .stat-label {
            color: var(--ink-soft);
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 6px;
        }

        /* ---------- Buttons ---------- */

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 22px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .5px;
            text-decoration: none;
            border: 1px solid transparent;
            cursor: pointer;
            transition: transform var(--transition-fast), box-shadow var(--transition-fast), background var(--transition-fast), border-color var(--transition-fast);
        }

        .btn:active {
            transform: scale(.97);
        }

        .btn-primary {
            background: var(--red);
            color: var(--ink);
        }

        .btn-primary:hover {
            box-shadow: 0 10px 20px -6px rgba(255, 176, 52, .55);
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: var(--white);
            border-color: var(--line);
            color: var(--ink);
        }

        .btn-secondary:hover {
            border-color: var(--ink);
            transform: translateY(-2px);
        }

        .btn-outline {
            background: transparent;
            border-color: rgba(255, 255, 255, .5);
            color: #fff;
        }

        .btn-outline:hover {
            background: rgba(255, 255, 255, .12);
            transform: translateY(-2px);
        }

        /* ---------- Pagination ---------- */

        .pagination {
            display: flex;
            justify-content: center;
            gap: 6px;
            flex-wrap: wrap;
        }

        .pagination a,
        .pagination span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 34px;
            height: 34px;
            padding: 0 8px;
            border: 1px solid var(--line);
            border-radius: 6px;
            color: var(--ink);
            font-size: 12px;
            text-decoration: none;
            transition: all var(--transition-fast);
        }

        .pagination span.active {
            background: var(--ink);
            color: #fff;
            border-color: var(--ink);
        }

        .pagination a:hover {
            border-color: var(--ink);
            transform: translateY(-1px);
        }

        /* ---------- Scroll-reveal ---------- */

        .reveal {
            opacity: 0;
            transform: translateY(28px);
            transition: opacity var(--transition-slow), transform var(--transition-slow);
        }

        .reveal.is-visible {
            opacity: 1;
            transform: none;
        }

        /* ---------- Back to top ---------- */

        .back-to-top {
            position: fixed;
            right: 24px;
            bottom: 24px;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: var(--ink);
            color: #fff;
            display: grid;
            place-items: center;
            text-decoration: none;
            box-shadow: var(--shadow-lift);
            opacity: 0;
            pointer-events: none;
            transform: translateY(12px);
            transition: opacity var(--transition-base), transform var(--transition-base);
            z-index: 25;
        }

        .back-to-top.is-visible {
            opacity: 1;
            pointer-events: auto;
            transform: none;
        }

        /* ---------- Print (browser "Print to PDF" fallback) ----------
           A real yearbook export uses the dedicated PDF routes/views;
           this just makes any public page print sanely if someone
           hits Ctrl/Cmd+P directly instead. */

        @media print {
            .site-header, .site-footer, .back-to-top,
            .btn, .pagination, .print-hide {
                display: none !important;
            }

            body {
                background: #fff;
            }

            .hero {
                background: #fff !important;
                color: #000 !important;
                padding: 24px 0;
            }

            .hero h1, .hero p {
                color: #000 !important;
                opacity: 1 !important;
                transform: none !important;
                animation: none !important;
            }

            .card {
                break-inside: avoid;
                box-shadow: none !important;
                border: 1px solid #ccc;
            }

            a[href]::after {
                content: none !important;
            }
        }
    </style>

    @yield('extra-css')
</head>
<body class="font-sans text-gray-900 antialiased">

    <header class="site-header" id="site-header">
        <div class="site-header-inner">
            <a href="{{ route('public.index') }}" class="site-brand">
                <span class="site-brand-mark" aria-hidden="true">YB</span>
                Yearbook
            </a>
            <nav class="site-nav-links">
                <a href="{{ route('public.index') }}" class="{{ request()->routeIs('public.index') ? 'is-active' : '' }}">Home</a>
                <a href="{{ route('public.timeline') }}" class="{{ request()->routeIs('public.timeline') ? 'is-active' : '' }}">Timeline</a>
                <a href="{{ route('public.events') }}" class="{{ request()->routeIs('public.events') || request()->routeIs('public.event.*') ? 'is-active' : '' }}">Events</a>
                <a href="{{ route('public.graduations') }}" class="{{ request()->routeIs('public.graduations') || request()->routeIs('public.graduation.*') ? 'is-active' : '' }}">Graduations</a>
                <a href="{{ route('public.graduates') }}" class="{{ request()->routeIs('public.graduates') || request()->routeIs('public.graduate.*') ? 'is-active' : '' }}">Graduates</a>
                <a href="{{ route('public.search') }}" class="{{ request()->routeIs('public.search') ? 'is-active' : '' }}">Search</a>
            </nav>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="site-footer">
        &copy; {{ now()->year }} {{ config('app.name', 'University') }} &mdash; Digital Yearbook
    </footer>

    <a href="#site-header" class="back-to-top" id="back-to-top" aria-label="Back to top" title="Back to top">&uarr;</a>

    <script>
        (function () {
            // Header shadow once the page scrolls.
            var header = document.getElementById('site-header');
            var backToTop = document.getElementById('back-to-top');

            var onScroll = function () {
                var scrolled = window.scrollY > 12;
                header.classList.toggle('is-scrolled', scrolled);
                backToTop.classList.toggle('is-visible', window.scrollY > 480);
            };
            document.addEventListener('scroll', onScroll, { passive: true });
            onScroll();

            var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

            // Auto-tag common content blocks as reveal-on-scroll targets,
            // so individual pages don't need to add classes by hand.
            var targets = document.querySelectorAll(
                '.card, .stat-card, .timeline-entry, .section-title, .section-subtitle, ' +
                '.featured-edition, .section-card, .sidebar-widget'
            );

            if (reduceMotion || !('IntersectionObserver' in window)) {
                targets.forEach(function (el) { el.classList.add('reveal', 'is-visible'); });
                return;
            }

            var grid = null;
            var indexInGrid = 0;

            targets.forEach(function (el) {
                el.classList.add('reveal');

                var parentGrid = el.closest('.grid, .stats-grid');
                if (parentGrid !== grid) {
                    grid = parentGrid;
                    indexInGrid = 0;
                }
                var delay = Math.min(indexInGrid * 70, 420);
                el.style.transitionDelay = delay + 'ms';
                indexInGrid++;
            });

            var observer = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: .12, rootMargin: '0px 0px -60px 0px' });

            targets.forEach(function (el) { observer.observe(el); });
        })();
    </script>

    @yield('extra-js')
</body>
</html>
-->