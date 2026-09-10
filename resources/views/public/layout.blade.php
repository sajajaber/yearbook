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
        /* Compact public navbar */
        .site-nav {
            height: 60px;
            box-shadow: none !important;
        }

        .site-nav .nav-inner {
            height: 60px;
            min-height: 60px;
        }

        .site-nav .brand-mark {
            font-size: 22px;
        }

        .site-nav .brand-copy {
            font-size: 11px;
            padding-left: 10px;
        }

        .site-nav .nav-links {
            gap: 25px;
        }

        .site-nav .nav-item {
            font-size: 10px;
        }

        /* Homepage navbar starts completely hidden and appears on scroll */
        .site-nav.home-nav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            transform: translateY(-100%);
            transition: transform 0.35s ease;
            box-shadow: none !important;
        }

        .site-nav.home-nav.is-visible {
            transform: translateY(0);
            box-shadow: none !important;
        }

        @media (prefers-reduced-motion: reduce) {
            .site-nav.home-nav {
                transition: none;
            }
        }
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

                const updateNavbar = () => {
                    nav.classList.toggle('is-visible', window.scrollY > 40);
                };

                updateNavbar();
                window.addEventListener('scroll', updateNavbar, { passive: true });
            });
        </script>
    @endif
</body>

</html>