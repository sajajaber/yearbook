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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Merriweather:wght@400;700&display=swap" rel="stylesheet">

    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>

        .site-header {
            background: var(--white);
            border-bottom: 1px solid var(--line);
            position: sticky;
            top: 0;
            z-index: 20;
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
            gap: 12px;
            color: var(--ink);
            font-family: "Merriweather", Georgia, serif;
            font-weight: 700;
            font-size: 20px;
        }

        .site-nav-links {
            display: flex;
            gap: 26px;
            flex-wrap: wrap;
        }

        .site-nav-links a {
            color: var(--ink-soft);
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .site-nav-links a:hover,
        .site-nav-links a.is-active {
            color: var(--ink);
        }

        .site-footer {
            background: var(--ink);
            color: #d8e3ef;
            margin-top: 60px;
            padding: 36px 32px;
            text-align: center;
            font-size: 12px;
        }

        .hero {
            background: linear-gradient(135deg, var(--ink) 0%, #1a3f7f 100%);
            color: #fff;
            padding: 64px 20px;
            text-align: center;
        }

        .hero h1 {
            font-family: "Merriweather", Georgia, serif;
            font-size: clamp(28px, 4vw, 44px);
            margin: 0 0 12px;
            color: #fff;
        }

        .hero p {
            color: #d8e3ef;
            font-size: 15px;
            max-width: 560px;
            margin: 0 auto;
        }

        .container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 32px;
        }

        .section {
            padding: 48px 20px;
        }

        .section-title {
            font-family: "Merriweather", Georgia, serif;
            font-size: 26px;
            color: var(--ink);
            margin: 0 0 8px;
        }

        .section-subtitle {
            color: var(--ink-soft);
            font-size: 14px;
            margin: 0 0 28px;
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

        .card {
            background: var(--white);
            border: 1px solid var(--line);
            border-radius: 12px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: box-shadow .2s ease, transform .2s ease;
        }

        .card:hover {
            box-shadow: 0 12px 24px -8px rgba(0, 42, 92, .18);
            transform: translateY(-2px);
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
        }

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
            border-radius: 12px;
            padding: 22px;
            text-align: center;
        }

        .stat-number {
            font-family: "Merriweather", Georgia, serif;
            font-size: 32px;
            color: var(--ink);
        }

        .stat-label {
            color: var(--ink-soft);
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 6px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .5px;
            text-decoration: none;
            border: 1px solid transparent;
            cursor: pointer;
        }

        .btn-primary {
            background: var(--red);
            color: var(--ink);
        }

        .btn-primary:hover {
            background: var(--red-dark, var(--red));
        }

        .btn-secondary {
            background: var(--white);
            border-color: var(--line);
            color: var(--ink);
        }

        .btn-secondary:hover {
            border-color: var(--ink);
        }

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
        }

        .pagination span.active {
            background: var(--ink);
            color: #fff;
            border-color: var(--ink);
        }

        .pagination a:hover {
            border-color: var(--ink);
        }
    </style>

    @yield('extra-css')
</head>
<body class="font-sans text-gray-900 antialiased">

    <header class="site-header">
        <div class="site-header-inner">
            <a href="{{ route('public.index') }}" class="site-brand">Yearbook</a>
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

</body>
</html>
