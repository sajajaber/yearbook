@php
$navItems = [
[
'label' => 'Timeline',
'route' => 'public.timeline',
'routeCheck' => 'public.timeline'
],
[
'label' => 'Events',
'route' => 'public.events',
'routeCheck' => 'public.events'
],
[
'label' => 'Graduates',
'route' => 'public.graduates',
'routeCheck' => 'public.graduates'
],
[
'label' => 'Archive',
'route' => 'public.archive',
'routeCheck' => 'public.archive'
],
[
'label' => 'Search',
'route' => 'search.index',
'routeCheck' => 'search.*'
],
];

// Check if this is the homepage
$isHomepage = request()->routeIs('public.home');
@endphp

<nav class="site-nav @if($isHomepage) site-nav--hide-on-load @endif" data-scroll-reveal="@if($isHomepage) true @else false @endif">
    <div class="nav-inner">
        <a href="{{ route('public.home') }}" class="brand-lockup">
            <span class="brand-mark">LIU</span>
            <span class="brand-copy">
                Digital<br>
                <small>Yearbook</small>
            </span>
        </a>

        <div class="nav-links hidden sm:flex">
            @foreach($navItems as $item)
            <a
                href="{{ route($item['route']) }}"
                class="nav-item @if(request()->routeIs($item['routeCheck'])) is-active @endif">
                {{ $item['label'] }}
            </a>
            @endforeach
        </div>
    </div>
</nav>

@if($isHomepage)
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const nav = document.querySelector('[data-scroll-reveal="true"]');
        if (!nav) return;

        let ticking = false;
        let lastScrollY = 0;
        const scrollThreshold = 50; // pixels before showing nav

        function updateNavVisibility() {
            lastScrollY = window.scrollY;

            if (lastScrollY > scrollThreshold) {
                nav.classList.add('is-visible');
            } else {
                nav.classList.remove('is-visible');
            }

            ticking = false;
        }

        window.addEventListener('scroll', function() {
            if (!ticking) {
                window.requestAnimationFrame(updateNavVisibility);
                ticking = true;
            }
        });

        // Initial check in case page is already scrolled
        updateNavVisibility();
    });
</script>
@endif