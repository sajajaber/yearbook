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
</head>


<body>
    <div class="site-shell public-shell">
        <nav class="site-nav">
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
</body>

</html>