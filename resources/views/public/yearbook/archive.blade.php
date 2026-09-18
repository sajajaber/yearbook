@extends('public.layout')

@section('title', 'Digital Yearbook Archive')

@section('extra-css')
    @vite('resources/css/pages/archive.css')
@endsection


@section('content')

<div class="archive-page">


    {{-- =====================================================
         HERO
         Page-load transitions — NOT scroll triggered
    ====================================================== --}}

    <section class="archive-hero">

        <div class="archive-hero-grid"></div>

        <div class="archive-hero-content">

            <div class="archive-kicker">
                LIU · Digital Yearbook
            </div>

            <h1>
                The years
                <em>we remember.</em>
            </h1>

            <div class="archive-hero-bottom">

                <p class="archive-hero-description">
                    A living archive of university life — the people,
                    places, celebrations, and moments that shaped every
                    graduating class.
                </p>

                <div class="archive-hero-count">

                    <strong>
                        {{ $academicYears->count() }}
                    </strong>

                    <span>
                        Yearbook editions
                    </span>

                </div>

            </div>

        </div>

    </section>


    {{-- =====================================================
         INTRO
         ===================================================== --}}

    <section class="archive-intro">

        <div>

            <p class="archive-intro-label">
                The archive
            </p>

            <h2>
                One university.<br>
                Many stories.
            </h2>

        </div>

        <p class="archive-intro-copy">
            Explore each edition chronologically. Open a yearbook
            to discover its graduates, events, ceremonies, and
            memories preserved from that academic year.
        </p>

    </section>


    {{-- =====================================================
         TIMELINE
         On-scroll cascade remains unchanged
    ====================================================== --}}

    <section class="archive-timeline">

        @forelse ($academicYears as $year)

        <article
            class="archive-year {{ $year->status === 'active' ? 'is-current' : '' }}">

            {{-- =================================================
                 YEAR
            ================================================== --}}

            <div class="archive-year-marker">

                <span class="archive-year-number">
                    {{ $year->title }}
                </span>

                <span class="archive-year-status">
                    {{ $year->status === 'active'
                        ? 'Current edition'
                        : 'Archived edition'
                    }}
                </span>

            </div>


            {{-- =================================================
                 CONTENT
            ================================================== --}}

            <div class="archive-year-content">

                <div class="archive-year-top">

                    <h3 class="archive-year-title">
                        {{ $year->title }} Yearbook
                    </h3>

                    @if ($year->status === 'active')

                    <span class="archive-current-badge">
                        Current
                    </span>

                    @endif

                </div>


                {{-- =================================================
                     EDITION
                ================================================== --}}

                <div class="archive-edition">

                    <div class="archive-edition-inner">

                        <p class="archive-edition-description">
                            A collection of the people, events,
                            milestones, and memories from the
                            {{ $year->title }} academic year.
                        </p>


                        {{-- =================================================
                             STATS
                        ================================================== --}}

                        <div class="archive-stats">

                            <div class="archive-stat">

                                <strong>
                                    {{ $year->graduate_count }}
                                </strong>

                                <span>
                                    Graduates
                                </span>

                            </div>


                            <div class="archive-stat">

                                <strong>
                                    {{ $year->event_count }}
                                </strong>

                                <span>
                                    Events
                                </span>

                            </div>

                        </div>


                        {{-- =================================================
                             ACTIONS
                        ================================================== --}}

                        <div class="archive-actions">

                            <a
                                href="{{ route('public.book', $year->id) }}"
                                class="archive-action archive-action-primary">

                                <span>
                                    Explore yearbook
                                </span>

                                <svg
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    aria-hidden="true">
                                    <path d="M5 12h14" />
                                    <path d="m13 6 6 6-6 6" />
                                </svg>

                            </a>


                            <a
                                href="{{ route('public.book.pdf', $year->id) }}"
                                class="archive-action archive-action-secondary"
                                onclick="event.stopPropagation()">

                                <svg
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    aria-hidden="true">
                                    <path d="M12 3v12" />
                                    <path d="m7 10 5 5 5-5" />
                                    <path d="M5 21h14" />
                                </svg>

                                <span>
                                    Download PDF
                                </span>

                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </article>

        @empty

        {{-- =================================================
             EMPTY STATE
        ================================================== --}}

        <div class="archive-empty">

            <strong>
                The archive is waiting.
            </strong>

            <span>
                No yearbook editions have been published yet.
            </span>

        </div>

        @endforelse

    </section>


    {{-- =====================================================
         END SECTION
         On-scroll transition
    ====================================================== --}}

    @if ($academicYears->count())

    <section class="archive-end">

        <div>

            <h3>
                Every edition tells a story.
            </h3>

            <p>
                Preserving university memories, one year at a time.
            </p>

        </div>

        <div class="archive-end-mark">
            LIU · Yearbook Archive
        </div>

    </section>

    @endif

</div>


{{-- =========================================================
     SCROLL CASCADE
     Body elements ONLY
========================================================= --}}

<script>
    document.addEventListener('DOMContentLoaded', function() {

        const archiveItems = document.querySelectorAll(
            '.archive-year, .archive-end'
        );

        if (!archiveItems.length) {
            return;
        }


        /*
         * Reveal each archive section when it enters
         * the viewport.
         *
         * The hero is intentionally NOT included here.
         *
         * Hero animations happen immediately on page load.
         */

        if (!('IntersectionObserver' in window)) {

            archiveItems.forEach(function(item) {
                item.classList.add('is-visible');
            });

            return;
        }


        const archiveObserver = new IntersectionObserver(

            function(entries, observer) {

                entries.forEach(function(entry) {

                    if (!entry.isIntersecting) {
                        return;
                    }

                    entry.target.classList.add('is-visible');

                    observer.unobserve(entry.target);

                });

            },

            {
                threshold: 0.18,
                rootMargin: '0px 0px -8% 0px'
            }

        );


        archiveItems.forEach(function(item) {
            archiveObserver.observe(item);
        });

    });
</script>

@endsection