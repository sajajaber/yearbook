<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'University Digital Yearbook')</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Merriweather:wght@400;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('extra-css')

    @vite('resources/css/public-layout.css')
</head>

<body class="{{ request()->routeIs('public.index') ? 'public-index-page' : '' }}">
    <div class="site-shell public-shell">
        <nav class="site-nav {{ request()->routeIs('public.home') ? 'home-nav' : '' }}">
            <div class="nav-inner">
                <a href="{{ route('public.home') }}" class="brand-lockup">
                    <span class="brand-mark">LIU</span>
                    <span class="brand-copy">Digital<br><small>Yearbook</small></span>
                </a>

                <div class="nav-links hidden sm:flex">
                    <a href="{{ route('public.events') }}" class="nav-item {{ request()->routeIs('public.events') ? 'is-active' : '' }}">Events</a>
                    <a href="{{ route('public.graduations') }}" class="nav-item {{ request()->routeIs('public.graduations') ? 'is-active' : '' }}">Graduations</a>
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
        document.addEventListener('DOMContentLoaded', function() {
            const nav = document.querySelector('.home-nav');
            if (!nav) return;
            const updateNavbar = () => nav.classList.toggle('is-visible', window.scrollY > 40);
            updateNavbar();
            window.addEventListener('scroll', updateNavbar, {
                passive: true
            });
        });
    </script>
    @endif

    @if(request()->routeIs('public.index'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const wrapper = document.querySelector('.yearbook-wrapper');
            if (!wrapper) return;

            /* Show the active academic year instead of the hard-coded 01. */
            const introNumber = document.querySelector('.yb-intro-number');
            const edition = document.querySelector('.yb-edition strong');
            if (introNumber && edition) {
                const match = edition.textContent.match(/\b(20\d{2})/);
                if (match) {
                    introNumber.textContent = match[1].slice(-2);
                }
            }

            /* Duplicate the campus names so the strip can loop continuously. */
            const campusStrip = document.querySelector('.yb-campus-strip');
            if (campusStrip && campusStrip.children.length && !campusStrip.dataset.marqueeReady) {
                campusStrip.dataset.marqueeReady = 'true';
                Array.from(campusStrip.children).forEach(function(item) {
                    const clone = item.cloneNode(true);
                    clone.setAttribute('aria-hidden', 'true');
                    campusStrip.appendChild(clone);
                });
            }

            /* Gentle section transitions as the visitor scrolls. */
            const sections = wrapper.querySelectorAll(
                '.yb-introduction, .yb-numbers, .yb-timeline-section, .yb-people, .yb-campus, .yb-archive, .yb-footer'
            );

            if ('IntersectionObserver' in window) {
                const observer = new IntersectionObserver(function(entries) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('yb-section-visible');
                            observer.unobserve(entry.target);
                        }
                    });
                }, {
                    threshold: 0.08,
                    rootMargin: '0px 0px -8% 0px'
                });

                sections.forEach(function(section) {
                    observer.observe(section);
                });
            } else {
                sections.forEach(function(section) {
                    section.classList.add('yb-section-visible');
                });
            }
        });
    </script>
    @endif

    @if(request()->routeIs('public.event.detail'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const videoExtensions = /\.(mp4|webm|ogg|mov|m4v)(?:\?.*)?$/i;

            function mimeTypeFor(src) {
                const clean = src.split('?')[0].toLowerCase();
                if (clean.endsWith('.webm')) return 'video/webm';
                if (clean.endsWith('.ogg')) return 'video/ogg';
                return 'video/mp4';
            }

            function createVideo(src, label, options = {}) {
                const video = document.createElement('video');
                video.controls = options.controls !== false;
                video.preload = 'metadata';
                video.playsInline = true;
                video.setAttribute('aria-label', label || 'Event video');

                if (options.autoplay) {
                    video.autoplay = true;
                    video.muted = true;
                    video.loop = true;
                }

                const source = document.createElement('source');
                source.src = src;
                source.type = mimeTypeFor(src);
                video.appendChild(source);

                return video;
            }

            /* The event view historically rendered every media item as <img>.
               Replace video URLs with real HTML5 video elements after the page loads. */
            document.querySelectorAll('.event-gallery-item img').forEach(function(image) {
                const src = image.currentSrc || image.src;
                if (!videoExtensions.test(src)) return;

                const item = image.closest('.event-gallery-item');
                if (!item) return;

                const video = createVideo(src, image.alt || 'Event video');
                image.replaceWith(video);
                item.classList.add('event-video-item');
                item.onclick = null;

                if (!item.querySelector('.event-video-badge')) {
                    const badge = document.createElement('span');
                    badge.className = 'event-video-badge';
                    badge.innerHTML = '<span aria-hidden="true">▶</span> Video';
                    item.appendChild(badge);
                }
            });

            /* If the first event media is a video, make the hero cinematic too. */
            const heroImage = document.querySelector('.event-hero-image img');
            if (heroImage) {
                const src = heroImage.currentSrc || heroImage.src;
                if (videoExtensions.test(src)) {
                    const hero = heroImage.closest('.event-hero-image');
                    const video = createVideo(src, heroImage.alt || 'Event video', {
                        controls: false,
                        autoplay: true
                    });
                    heroImage.replaceWith(video);
                    hero.classList.add('event-hero-video');
                }
            }
        });
    </script>
    @endif
</body>

</html>