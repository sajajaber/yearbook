@extends('public.layout')

@section('title', 'LIU Digital Yearbook')

@section('extra-css')
    @vite('resources/css/pages/home.css')
@endsection

@section('content')

<div class="yearbook-wrapper">

    {{-- =====================================================
    01 — COVER
    ===================================================== --}}

    <section class="yb-cover" id="top">

        <div class="yb-cover-image">

            @if($heroImages->isNotEmpty())

            <img
                src="{{ asset('storage/' . $heroImages->first()->path) }}"
                alt="Yearbook collection">

            @else

            <div class="yb-cover-empty"></div>

            @endif

        </div>

        <div class="yb-cover-grid"></div>

        <div class="yb-cover-inner">

            <div class="yb-cover-top">

                <a href="{{ url('/') }}" class="yb-brand">

                    <div class="yb-brand-mark">
                        LIU
                    </div>

                    <div class="yb-brand-text">
                        Digital<br>
                        Yearbook
                    </div>

                </a>

                <div class="yb-edition">

                    Current edition

                    <strong>
                        {{ $currentYear->title ?? 'Archive Edition' }}
                    </strong>

                </div>

            </div>

            <div class="yb-cover-main">

                <div class="yb-cover-kicker">
                    {{ $currentYear->title ?? 'Archive Edition' }}
                </div>

                @if($currentYear)

                <h1 class="yb-cover-title" data-split-load>
                    A year<br>
                    <span>worth</span><br>
                    remembering
                </h1>

                @else

                <h1 class="yb-cover-title" data-split-load>
                    The<br>
                    <span>yearbook</span><br>
                    archive.
                </h1>

                @endif

                <p class="yb-cover-description">
                    A living collection of the people, places,
                    celebrations, and moments that shaped our
                    university year.
                </p>

                <div class="yb-cover-actions">

                    <a
                        href="{{ route('public.events') }}"
                        class="yb-cover-link">

                        Moments

                        <span class="yb-cover-link-arrow">
                            ↘
                        </span>

                    </a>

                    <a
                        href="{{ route('public.graduates') }}"
                        style="
                        color: rgba(255,255,255,.75);
                        text-decoration:none;
                        font-size:.68rem;
                        text-transform:uppercase;
                        letter-spacing:1.5px;
                        font-weight:800;
                    ">

                        Meet the class →

                    </a>

                </div>

            </div>

            <div class="yb-cover-bottom">

                <div class="yb-cover-caption">
                    People · Places · Moments · 2026
                </div>

                <div class="yb-cover-scroll">

                    Scroll to explore

                    <span class="yb-scroll-line"></span>

                </div>

            </div>

        </div>

    </section>


    {{-- =====================================================
    02 — INTRODUCTION
    ===================================================== --}}

    <section class="yb-introduction">

        <div class="yb-container">

            <div class="yb-intro-grid">

                <div class="yb-intro-side">

                    <div class="yb-overline">
                        The year in perspective
                    </div>

                    <div class="yb-intro-number">
                        {{ $currentYear ? substr($currentYear->title, -2) : '00' }}
                    </div>

                    <p>
                        Every academic year leaves behind more
                        than dates and ceremonies. It leaves stories.
                    </p>

                </div>

                <div>

                    <h2
                        class="yb-intro-heading"
                        data-split-scroll>

                        This is more than an archive.

                        <em>
                            This is what the year looked like.
                        </em>

                    </h2>

                    <p class="yb-intro-copy">
                        Explore the people who graduated, the events
                        that brought the community together, and the
                        moments that became part of our shared history.
                    </p>

                </div>

            </div>

        </div>

    </section>


    {{-- =====================================================
    03 — NUMBERS
    ===================================================== --}}

    <section class="yb-numbers">

        <div class="yb-container">

            <div class="yb-number-grid">

                <div class="yb-number-item">

                    <div class="yb-number-value">
                        {{ $stats['undergraduates'] ?? 0 }}
                    </div>

                    <div class="yb-number-label">
                        Undergraduate graduates
                    </div>

                </div>

                <div class="yb-number-item">

                    <div class="yb-number-value">
                        {{ $stats['graduates'] ?? 0 }}
                    </div>

                    <div class="yb-number-label">
                        Postgraduate graduates
                    </div>

                </div>

                <div class="yb-number-item">

                    <div class="yb-number-value">
                        {{ $stats['events'] ?? 0 }}
                    </div>

                    <div class="yb-number-label">
                        Campus events
                    </div>

                </div>

            </div>

        </div>

    </section>


    {{-- =====================================================
    04 — THE YEAR / EVENTS TIMELINE
    ===================================================== --}}

    @if($featuredEvents->isNotEmpty())

    <section
        class="yb-timeline-section"
        id="timeline">

        <div class="yb-container">

            <div class="yb-section-header">

                <div>

                    <div class="yb-overline">
                        Chapter one
                    </div>

                    <h2 data-split-scroll>
                        The year<br>
                        <em>unfolds.</em>
                    </h2>

                </div>

                <p class="yb-section-header-note">
                    A selection of moments, celebrations and
                    events that defined the academic year.
                </p>

            </div>


            <div class="yb-timeline">

                @foreach($featuredEvents as $event)

                <article class="yb-event">

                    <div class="yb-event-date">

                        <span>
                            Event
                        </span>

                        {{ \Carbon\Carbon::parse($event->event_date)->format('M d') }}

                    </div>


                    <div class="yb-event-content">

                        <div>

                            <h3 class="yb-event-title">
                                {{ $event->title }}
                            </h3>

                            @if($event->description)

                            <p class="yb-event-description">
                                {{ Str::limit(strip_tags(\App\Support\RichText::sanitize($event->description)), 180) }}
                            </p>

                            @endif


                            <a
                                href="{{ route('public.event.detail', $event->id) }}"
                                class="yb-event-link">

                                View this moment

                                <span>
                                    →
                                </span>

                            </a>

                        </div>


                        <div>

                            {{-- IMPORTANT:
                                     Only select an actual image.
                                     The old code used media->first(),
                                     which could return a video/document.
                                --}}
                            @php
                            $image = $event->media->firstWhere('type', 'image');
                            @endphp


                            @if($image)

                            @php
                            $eventImageUrl = method_exists($image, 'thumbnailUrl')
                            ? $image->thumbnailUrl()
                            : asset('storage/' . $image->path);
                            @endphp

                            <img
                                src="{{ $eventImageUrl }}"
                                alt="{{ $image->alt_text ?: $event->title }}"
                                class="yb-event-image yb-reveal-img"
                                loading="lazy"
                                onerror="this.onerror=null; this.src='{{ asset('storage/' . $image->path) }}';">

                            @else

                            <div class="yb-event-placeholder">
                                No image available
                            </div>

                            @endif

                        </div>

                    </div>

                </article>

                @endforeach

            </div>

        </div>

    </section>

    @endif


    {{-- =====================================================
    05 — GRADUATIONS / PEOPLE
    ===================================================== --}}

    @if($graduations->isNotEmpty())

    <section
        class="yb-people"
        id="people">

        <div class="yb-container">

            <div class="yb-people-heading">

                <div>

                    <div class="yb-overline">
                        Chapter two
                    </div>

                    <h2 data-split-scroll>
                        <span>Graduations</span>
                    </h2>

                </div>

                <p>
                    Behind every ceremony is a collection of
                    people, ambitions, friendships and stories.
                    Explore the graduation moments that marked
                    the end of one chapter and the beginning
                    of another.
                </p>

            </div>


            <div class="yb-graduation-grid">

                @foreach($graduations as $graduation)

                <a
                    href="{{ route('public.graduation.detail', $graduation->id) }}"
                    class="yb-graduation-card">

                    @php
                    $image = $graduation->media->firstWhere('type', 'image');
                    @endphp


                    @if($image)

                    @php
                    $graduationImageUrl = method_exists($image, 'thumbnailUrl')
                    ? $image->thumbnailUrl()
                    : asset('storage/' . $image->path);
                    @endphp

                    <img
                        src="{{ $graduationImageUrl }}"
                        alt="{{ $image->alt_text ?: ($graduation->name ?? 'Graduation Ceremony') }}"
                        class="yb-graduation-image"
                        loading="lazy"
                        onerror="this.onerror=null; this.src='{{ asset('storage/' . $image->path) }}';">

                    @else

                    <div class="yb-graduation-placeholder"></div>

                    @endif


                    <div class="yb-graduation-arrow">
                        ↗
                    </div>


                    <div class="yb-graduation-info">

                        <span class="yb-graduation-date">
                            {{ \Carbon\Carbon::parse($graduation->ceremony_date)->format('F Y') }}
                        </span>

                        <h3 class="yb-graduation-title">
                            Graduation Ceremony
                        </h3>

                    </div>

                </a>

                @endforeach

            </div>

        </div>

    </section>

    @endif


    {{-- =====================================================
    06 — CAMPUSES
    ===================================================== --}}

    <section class="yb-campus">

        <div class="yb-container">

            <div class="yb-campus-header">

                <div class="yb-campus-index">
                    10
                </div>

                <div>

                    <div class="yb-overline">
                        Chapter three
                    </div>

                    <h2 data-split-scroll>
                        One university.<br>
                        <em>Many places.</em>
                    </h2>

                    <p>
                        From Lebanon to the wider world, the year
                        was experienced across campuses, communities
                        and classrooms. Explore the places that make
                        up the LIU story.
                    </p>

                </div>

            </div>


            <div class="yb-campus-strip">

                <div class="yb-campus-track">

                    <span class="yb-campus-name">Beirut</span>
                    <span class="yb-campus-name">Bekaa</span>
                    <span class="yb-campus-name">Saida</span>
                    <span class="yb-campus-name">Nabatieh</span>
                    <span class="yb-campus-name">Tripoli</span>
                    <span class="yb-campus-name">Mount Lebanon</span>
                    <span class="yb-campus-name">Tyre</span>
                    <span class="yb-campus-name">Rayak</span>
                    <span class="yb-campus-name">Akkar</span>
                    <span class="yb-campus-name">Yemen</span>
                    <span class="yb-campus-name">Senegal</span>
                    <span class="yb-campus-name">Mauritania</span>

                    <span class="yb-campus-name">Beirut</span>
                    <span class="yb-campus-name">Bekaa</span>
                    <span class="yb-campus-name">Saida</span>
                    <span class="yb-campus-name">Nabatieh</span>
                    <span class="yb-campus-name">Tripoli</span>
                    <span class="yb-campus-name">Mount Lebanon</span>
                    <span class="yb-campus-name">Tyre</span>
                    <span class="yb-campus-name">Rayak</span>
                    <span class="yb-campus-name">Akkar</span>
                    <span class="yb-campus-name">Yemen</span>
                    <span class="yb-campus-name">Senegal</span>
                    <span class="yb-campus-name">Mauritania</span>

                </div>

            </div>

        </div>

    </section>


    {{-- =====================================================
    07 — ARCHIVE
    ===================================================== --}}

    <section class="yb-archive">

        <div class="yb-container">

            <div class="yb-archive-inner">

                <div>

                    <div class="yb-overline">
                        The collection
                    </div>

                    <h2
                        class="yb-archive-heading"
                        data-split-scroll>

                        The year<br>

                        <em>doesn't end here.</em>

                    </h2>

                    <p class="yb-archive-copy">
                        Yesterday becomes history. Explore the archive
                        and discover the people, moments and milestones
                        that came before this edition.
                    </p>

                    <a
                        href="{{ route('public.archive') }}"
                        class="yb-archive-link">

                        Explore the archive

                        <span>
                            ↗
                        </span>

                    </a>

                </div>


                <div class="yb-archive-years">

                    @if($currentYear)

                    <a
                        href="{{ route('public.timeline') }}"
                        class="yb-archive-year">

                        <span>
                            {{ $currentYear->title }}
                        </span>

                        <span>
                            Current edition →
                        </span>

                    </a>

                    @endif


                    <a
                        href="{{ route('public.archive') }}"
                        class="yb-archive-year">

                        <span>
                            Previous editions
                        </span>

                        <span>
                            Explore →
                        </span>

                    </a>


                    <a
                        href="{{ route('public.graduates') }}"
                        class="yb-archive-year">

                        <span>
                            Graduate directory
                        </span>

                        <span>
                            People →
                        </span>

                    </a>


                    <a
                        href="{{ route('public.events') }}"
                        class="yb-archive-year">

                        <span>
                            Event collection
                        </span>

                        <span>
                            Moments →
                        </span>

                    </a>

                </div>

            </div>

        </div>

    </section>


    {{-- =====================================================
    08 — FOOTER
    ===================================================== --}}

    <footer class="public-footer">

        <div class="public-footer-inner">

            <div class="public-footer-top">

                <div class="public-footer-brand">

                    <p class="footer-eyebrow">
                        Lebanese International University
                    </p>

                    <h2>
                        Lebanese International University
                    </h2>

                    <p>
                        Excellence in Education
                    </p>

                    <div
                        class="footer-gold-line"
                        aria-hidden="true">
                    </div>

                </div>


                <div class="public-footer-contact">

                    <div class="footer-contact-item">

                        <span
                            class="footer-contact-icon"
                            aria-hidden="true">

                            <svg viewBox="0 0 24 24">

                                <rect
                                    x="3"
                                    y="5"
                                    width="18"
                                    height="14"
                                    rx="1.5">
                                </rect>

                                <path d="m3 7 9 6 9-6"></path>

                            </svg>

                        </span>

                        <div class="footer-contact-copy">

                            <strong>
                                Email
                            </strong>

                            <a href="mailto:info@liu.edu.lb">
                                info@liu.edu.lb
                            </a>

                        </div>

                    </div>


                    <div class="footer-contact-item">

                        <span
                            class="footer-contact-icon"
                            aria-hidden="true">

                            <svg viewBox="0 0 24 24">

                                <path d="
                                M7.2 3.5
                                5 4.4
                                c-.8.3-1.3 1.1-1.1 2
                                1.2 6.8 6.9 12.5 13.7 13.7
                                .9.2 1.7-.3 2-1.1
                                l.9-2.2
                                c.3-.7 0-1.5-.7-1.9
                                l-2.8-1.4
                                c-.6-.3-1.4-.2-1.8.4
                                l-1.1 1.3
                                c-2.3-1.1-4.1-2.9-5.2-5.2
                                l1.3-1.1
                                c.5-.4.7-1.2.4-1.8
                                L9.1 4.2
                                c-.4-.7-1.2-1-1.9-.7Z">
                                </path>

                            </svg>

                        </span>

                        <div class="footer-contact-copy">

                            <strong>
                                Phone
                            </strong>

                            <a href="tel:+9611705080">
                                +961-1-705080
                            </a>

                        </div>

                    </div>


                    <div class="footer-contact-item">

                        <span
                            class="footer-contact-icon"
                            aria-hidden="true">

                            <svg viewBox="0 0 24 24">

                                <path d="M6 4h12v16H6z"></path>
                                <path d="M9 8h6M9 12h6M9 16h4"></path>

                            </svg>

                        </span>

                        <div class="footer-contact-copy">

                            <strong>
                                Fax
                            </strong>

                            <span>
                                +961-1-306044
                            </span>

                        </div>

                    </div>


                    <div class="footer-contact-item">

                        <span
                            class="footer-contact-icon"
                            aria-hidden="true">

                            <svg viewBox="0 0 24 24">

                                <path d="
                                M12 21
                                s7-6.1 7-12
                                a7 7 0 1 0-14 0
                                c0 5.9 7 12 7 12Z">
                                </path>

                                <circle
                                    cx="12"
                                    cy="9"
                                    r="2.2">
                                </circle>

                            </svg>

                        </span>

                        <div class="footer-contact-copy">

                            <strong>
                                Address
                            </strong>

                            <span>
                                Mousaitbeh, P.O. Box 14-6404,
                                Beirut, Lebanon
                            </span>

                        </div>

                    </div>

                </div>

            </div>


            <div class="public-footer-bottom">

                <span>
                    © {{ now()->year }}
                    Lebanese International University —
                    Digital Yearbook
                </span>

                <a href="{{ route('search.index') }}">
                    Search the archive
                </a>

            </div>

        </div>

    </footer>

</div>

@endsection

@push('scripts')

<script>
    document.addEventListener('DOMContentLoaded', function() {

        const wrapper =
            document.querySelector('.yearbook-wrapper');

        if (!wrapper) {
            return;
        }

        const prefersReducedMotion =
            window.matchMedia(
                '(prefers-reduced-motion: reduce)'
            ).matches;


        /* =========================================================
           LETTER SPLITTING
           ========================================================= */

        function splitNode(node, counter) {

            if (node.nodeType === Node.TEXT_NODE) {

                const frag =
                    document.createDocumentFragment();

                const parts =
                    node.textContent.split(/(\s+)/);

                parts.forEach(function(part) {

                    if (part === '') {
                        return;
                    }

                    if (/^\s+$/.test(part)) {

                        frag.appendChild(
                            document.createTextNode(part)
                        );

                        return;
                    }

                    const wordSpan =
                        document.createElement('span');

                    wordSpan.className =
                        'yb-split-word';

                    Array.from(part).forEach(function(ch) {

                        const charSpan =
                            document.createElement('span');

                        charSpan.className =
                            'yb-split-char';

                        charSpan.textContent =
                            ch;

                        charSpan.style.setProperty(
                            '--yb-char-delay',
                            Math.min(
                                counter.i * 16,
                                520
                            ) + 'ms'
                        );

                        counter.i += 1;

                        wordSpan.appendChild(
                            charSpan
                        );

                    });

                    frag.appendChild(
                        wordSpan
                    );

                });

                node.parentNode.replaceChild(
                    frag,
                    node
                );

                return;
            }

            if (
                node.nodeType === Node.ELEMENT_NODE &&
                node.tagName !== 'BR'
            ) {

                Array.from(
                    node.childNodes
                ).forEach(function(child) {

                    splitNode(
                        child,
                        counter
                    );

                });

            }

        }


        function splitHeading(el) {

            if (
                !el ||
                el.dataset.ybSplit === 'done'
            ) {
                return;
            }

            const counter = {
                i: 0
            };

            Array.from(
                el.childNodes
            ).forEach(function(child) {

                splitNode(
                    child,
                    counter
                );

            });

            el.classList.add(
                'yb-split-ready'
            );

            el.dataset.ybSplit =
                'done';

        }


        /* =========================================================
           HERO SETUP
           ========================================================= */

        const loadHeading =
            wrapper.querySelector(
                '[data-split-load]'
            );

        if (loadHeading) {

            splitHeading(
                loadHeading
            );

        }


        /* =========================================================
           REDUCED MOTION FALLBACK
           ========================================================= */

        if (prefersReducedMotion) {

            document
                .querySelectorAll(
                    '[data-split-scroll]'
                )
                .forEach(function(heading) {

                    splitHeading(
                        heading
                    );

                    heading.classList.add(
                        'is-visible'
                    );

                });

            return;
        }


        /* =========================================================
           PAGE LOAD ANIMATION
           ========================================================= */

        requestAnimationFrame(function() {

            wrapper.classList.add(
                'page-animations'
            );

            if (loadHeading) {

                requestAnimationFrame(
                    function() {

                        loadHeading.classList.add(
                            'is-visible'
                        );

                    }
                );

            }

        });


        /* =========================================================
           NAVBAR
           ========================================================= */

        const nav =
            document.querySelector(
                '.yb-nav'
            );

        function updateNavbar() {

            if (!nav) {
                return;
            }

            nav.classList.toggle(
                'is-scrolled',
                window.scrollY > 24
            );

        }

        updateNavbar();

        window.addEventListener(
            'scroll',
            updateNavbar, {
                passive: true
            }
        );


        /* =========================================================
           SCROLL HEADINGS
           ========================================================= */

        const scrollHeadings =
            Array.from(
                wrapper.querySelectorAll(
                    '[data-split-scroll]'
                )
            );

        scrollHeadings.forEach(
            splitHeading
        );


        /* =========================================================
           REVEAL ELEMENTS
           ========================================================= */

        const revealItems =
            Array.from(
                wrapper.querySelectorAll(
                    [
                        '.yb-intro-side',
                        '.yb-intro-copy',
                        '.yb-number-item',
                        '.yb-section-header-note',
                        '.yb-event',
                        '.yb-people-heading > p',
                        '.yb-graduation-card',
                        '.yb-campus-header > p',
                        '.yb-archive-inner',
                        '.public-footer-top',
                        '.yb-event-image',
                        '.yb-reveal-img'
                    ].join(', ')
                )
            );


        revealItems.forEach(
            function(item, index) {

                item.classList.add(
                    'yb-reveal'
                );

                item.style.setProperty(
                    '--yb-delay',
                    Math.min(
                        (index % 8) * 65,
                        420
                    ) + 'ms'
                );

            }
        );


        wrapper.classList.add(
            'scroll-animations-ready'
        );


        /* =========================================================
           INTERSECTION OBSERVER
           ========================================================= */

        const allObserved =
            revealItems.concat(
                scrollHeadings
            );


        if (
            !('IntersectionObserver' in window)
        ) {

            allObserved.forEach(
                function(item) {

                    item.classList.add(
                        'is-visible'
                    );

                }
            );

            return;
        }


        const observer =
            new IntersectionObserver(
                function(entries) {

                    entries.forEach(
                        function(entry) {

                            if (
                                entry.isIntersecting
                            ) {

                                entry.target.classList.add(
                                    'is-visible'
                                );

                                observer.unobserve(
                                    entry.target
                                );

                            }

                        }
                    );

                }, {
                    threshold: 0.12,
                    rootMargin: '0px 0px -8% 0px'
                }
            );


        allObserved.forEach(
            function(item) {

                observer.observe(
                    item
                );

            }
        );


        /* =========================================================
           SCROLL HINT
           ========================================================= */

        const scrollHint =
            wrapper.querySelector(
                '.yb-cover-scroll'
            );

        if (scrollHint) {

            scrollHint.classList.add(
                'yb-scroll-pulse'
            );

        }


        /* =========================================================
           HERO PARALLAX
           ========================================================= */

        const heroImage =
            wrapper.querySelector(
                '.yb-cover-image img'
            );

        if (heroImage) {

            let ticking = false;

            function updateHeroParallax() {

                if (
                    window.scrollY <=
                    window.innerHeight
                ) {

                    const offset =
                        Math.min(
                            window.scrollY * 0.12,
                            70
                        );

                    heroImage.style.transform =
                        'scale(1.03) translate3d(0, ' +
                        offset +
                        'px, 0)';

                }

                ticking = false;

            }

            window.addEventListener(
                'scroll',
                function() {

                    if (!ticking) {

                        window.requestAnimationFrame(
                            updateHeroParallax
                        );

                        ticking = true;

                    }

                }, {
                    passive: true
                }
            );

        }

    });
</script>

@endpush