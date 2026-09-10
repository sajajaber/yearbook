@extends('public.layout')

@section('title', 'Digital Yearbook Archive')

@section('extra-css')

<style>
    /* =========================================================
       ARCHIVE — EDITORIAL TIMELINE
    ========================================================= */

    .archive-page {
        background: #f4f1eb;
        margin: 0 -32px;
        color: var(--ink);
    }

    /* =========================================================
       HERO
    ========================================================= */

    .archive-hero {
        min-height: 620px;
        padding: 90px 7vw 100px;
        background: var(--ink);
        color: var(--white);
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: flex-end;
    }

    .archive-hero::before {
        content: "";
        position: absolute;
        width: 520px;
        height: 520px;
        border: 1px solid rgba(255, 255, 255, .12);
        border-radius: 50%;
        right: -160px;
        top: -180px;
    }

    .archive-hero::after {
        content: "";
        position: absolute;
        width: 720px;
        height: 720px;
        border: 1px solid rgba(255, 255, 255, .06);
        border-radius: 50%;
        right: -260px;
        top: -280px;
    }

    .archive-hero-grid {
        position: absolute;
        inset: 0;
        opacity: .08;
        background-image:
            linear-gradient(rgba(255, 255, 255, .5) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255, 255, 255, .5) 1px, transparent 1px);
        background-size: 80px 80px;
        mask-image: linear-gradient(to right, transparent, black 60%);
        pointer-events: none;
    }

    .archive-hero-content {
        position: relative;
        z-index: 2;
        max-width: 1050px;
    }

    .archive-kicker {
        display: flex;
        align-items: center;
        gap: 14px;
        color: var(--red);
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 3px;
        text-transform: uppercase;
        margin-bottom: 30px;
    }

    .archive-kicker::before {
        content: "";
        width: 42px;
        height: 2px;
        background: var(--red);
    }

    .archive-hero h1 {
        margin: 0;
        color: #fff;
        max-width: 950px;
        font-size: clamp(58px, 9vw, 132px);
        line-height: .88;
        letter-spacing: -5px;
        font-weight: 800;
    }

    .archive-hero h1 em {
        display: block;
        color: #aebdcd;
        font-family: "Merriweather", serif;
        font-weight: 400;
        font-size: .56em;
        line-height: 1.2;
        letter-spacing: -2px;
        margin-top: 16px;
    }

    .archive-hero-bottom {
        margin-top: 55px;
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        gap: 40px;
        max-width: 850px;
    }

    .archive-hero-description {
        color: #b9c6d6;
        font-size: 15px;
        line-height: 1.8;
        max-width: 510px;
        margin: 0;
    }

    .archive-hero-count {
        flex-shrink: 0;
        text-align: right;
    }

    .archive-hero-count strong {
        display: block;
        color: #fff;
        font-size: 44px;
        line-height: 1;
        font-weight: 700;
    }

    .archive-hero-count span {
        display: block;
        margin-top: 7px;
        color: #8293a7;
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 2px;
    }

    /* =========================================================
       ARCHIVE INTRO
    ========================================================= */

    .archive-intro {
        padding: 80px 7vw 55px;
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        gap: 50px;
    }

    .archive-intro-label {
        color: var(--red);
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin: 0 0 14px;
    }

    .archive-intro h2 {
        margin: 0;
        font-size: clamp(34px, 5vw, 62px);
        line-height: .95;
        letter-spacing: -2px;
        max-width: 650px;
    }

    .archive-intro-copy {
        max-width: 340px;
        color: var(--ink-soft);
        font-size: 13px;
        line-height: 1.7;
        margin: 0;
    }

    /* =========================================================
       TIMELINE
    ========================================================= */

    .archive-timeline {
        padding: 20px 7vw 120px;
        position: relative;
    }

    .archive-timeline::before {
        content: "";
        position: absolute;
        left: calc(7vw + 105px);
        top: 30px;
        bottom: 120px;
        width: 1px;
        background: #d8d4cc;
    }

    /* =========================================================
       YEAR ROW
    ========================================================= */

    .archive-year {
        position: relative;
        display: grid;
        grid-template-columns: 210px 1fr;
        gap: 55px;
        padding: 45px 0;
    }

    .archive-year+.archive-year {
        border-top: 1px solid #dedbd4;
    }

    .archive-year-marker {
        position: relative;
        z-index: 2;
        background: #f4f1eb;
        align-self: start;
        padding: 0 20px 0 0;
    }

    .archive-year-marker::after {
        content: "";
        position: absolute;
        right: -10px;
        top: 12px;
        width: 19px;
        height: 19px;
        background: #f4f1eb;
        border: 2px solid var(--red);
        border-radius: 50%;
        box-shadow: 0 0 0 7px #f4f1eb;
    }

    .archive-year-number {
        display: block;
        font-size: clamp(34px, 4vw, 54px);
        line-height: 1;
        font-weight: 800;
        letter-spacing: -2px;
        color: var(--ink);
    }

    .archive-year-status {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        margin-top: 13px;
        color: var(--ink-soft);
        font-size: 9px;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        font-weight: 700;
    }

    .archive-year-status::before {
        content: "";
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #aaa;
    }

    .archive-year.is-current .archive-year-status {
        color: var(--red);
    }

    .archive-year.is-current .archive-year-status::before {
        background: var(--red);
    }

    /* =========================================================
       YEAR CONTENT
    ========================================================= */

    .archive-year-content {
        min-width: 0;
    }

    .archive-year-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 30px;
        margin-bottom: 25px;
    }

    .archive-year-title {
        margin: 0;
        font-family: "Merriweather", serif;
        font-size: clamp(27px, 3vw, 42px);
        line-height: 1.1;
        color: var(--ink);
        font-weight: 700;
    }

    .archive-current-badge {
        flex-shrink: 0;
        padding: 8px 11px;
        background: var(--ink);
        color: #fff;
        font-size: 8px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1.4px;
    }

    /* =========================================================
       EDITION PANEL
    ========================================================= */

    .archive-edition {
        background: #fff;
        border: 1px solid #dedbd4;
        position: relative;
        overflow: hidden;
        transition:
            transform .35s cubic-bezier(.2, .8, .2, 1),
            box-shadow .35s ease;
    }

    .archive-edition:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 45px rgba(19, 42, 58, .10);
    }

    .archive-edition::before {
        content: "";
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 5px;
        background: var(--red);
    }

    .archive-year.is-current .archive-edition::before {
        background: var(--ink);
    }

    .archive-edition-inner {
        padding: 30px 32px 30px 38px;
    }

    .archive-edition-description {
        color: var(--ink-soft);
        font-size: 13px;
        line-height: 1.7;
        max-width: 600px;
        margin: 0 0 28px;
    }

    /* =========================================================
       STATS
    ========================================================= */

    .archive-stats {
        display: flex;
        border-top: 1px solid #e6e3dd;
        margin-bottom: 25px;
    }

    .archive-stat {
        flex: 1;
        padding: 19px 20px 4px 0;
        border-right: 1px solid #e6e3dd;
        margin-right: 20px;
    }

    .archive-stat:last-child {
        border-right: 0;
        margin-right: 0;
    }

    .archive-stat strong {
        display: block;
        font-size: 24px;
        line-height: 1;
        color: var(--ink);
        margin-bottom: 7px;
    }

    .archive-stat span {
        font-size: 9px;
        color: var(--ink-soft);
        text-transform: uppercase;
        letter-spacing: 1.2px;
        font-weight: 700;
    }

    /* =========================================================
       ACTIONS
    ========================================================= */

    .archive-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 9px;
    }

    .archive-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        padding: 12px 16px;
        text-decoration: none;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-size: 9px;
        font-weight: 800;
        transition: all .2s ease;
    }

    .archive-action-primary {
        background: var(--ink);
        color: #fff;
    }

    .archive-action-primary:hover {
        background: var(--red);
        color: #fff;
    }

    .archive-action-secondary {
        border: 1px solid #d7d3cc;
        color: var(--ink);
        background: transparent;
    }

    .archive-action-secondary:hover {
        border-color: var(--ink);
        background: #f8f6f2;
    }

    .archive-action svg {
        width: 13px;
        height: 13px;
        stroke: currentColor;
    }

    /* =========================================================
       EMPTY STATE
    ========================================================= */

    .archive-empty {
        padding: 80px 30px;
        text-align: center;
        border: 1px dashed #cbc7bf;
        color: var(--ink-soft);
    }

    .archive-empty strong {
        display: block;
        color: var(--ink);
        font-family: "Merriweather", serif;
        font-size: 25px;
        margin-bottom: 8px;
    }

    .archive-empty span {
        font-size: 12px;
    }

    /* =========================================================
       FOOTER NOTE
    ========================================================= */

    .archive-end {
        background: var(--ink);
        color: #fff;
        padding: 70px 7vw;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 40px;
    }

    .archive-end h3 {
        color: #fff;
        margin: 0 0 8px;
        font-family: "Merriweather", serif;
        font-size: 27px;
    }

    .archive-end p {
        color: #8fa0b3;
        font-size: 12px;
        margin: 0;
    }

    .archive-end-mark {
        color: var(--red);
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 3px;
        text-transform: uppercase;
        white-space: nowrap;
    }

    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 900px) {
        .archive-hero {
            min-height: 560px;
            padding: 70px 32px 80px;
        }

        .archive-hero h1 {
            letter-spacing: -3px;
        }

        .archive-hero-bottom {
            flex-direction: column;
            align-items: flex-start;
        }

        .archive-hero-count {
            text-align: left;
        }

        .archive-intro,
        .archive-timeline,
        .archive-end {
            padding-left: 32px;
            padding-right: 32px;
        }

        .archive-timeline::before {
            left: 80px;
        }

        .archive-year {
            grid-template-columns: 130px 1fr;
            gap: 35px;
        }
    }

    @media (max-width: 650px) {
        .archive-page {
            margin: 0 -20px;
        }

        .archive-hero {
            min-height: 500px;
            padding: 60px 24px;
        }

        .archive-hero h1 {
            font-size: clamp(52px, 17vw, 82px);
            letter-spacing: -3px;
        }

        .archive-hero h1 em {
            font-size: .5em;
        }

        .archive-intro {
            padding: 60px 24px 35px;
            display: block;
        }

        .archive-intro-copy {
            margin-top: 25px;
        }

        .archive-timeline {
            padding: 10px 24px 80px;
        }

        .archive-timeline::before {
            display: none;
        }

        .archive-year {
            display: block;
            padding: 35px 0;
        }

        .archive-year-marker {
            padding: 0;
            margin-bottom: 22px;
        }

        .archive-year-marker::after {
            display: none;
        }

        .archive-year-top {
            display: block;
        }

        .archive-current-badge {
            display: inline-block;
            margin-top: 13px;
        }

        .archive-edition-inner {
            padding: 25px 22px 25px 27px;
        }

        .archive-stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0;
        }

        .archive-stat {
            padding: 17px 10px 12px 0;
            border-right: 0;
            margin-right: 0;
        }

        .archive-actions {
            flex-direction: column;
        }

        .archive-action {
            width: 100%;
        }

        .archive-end {
            padding: 55px 24px;
            display: block;
        }

        .archive-end-mark {
            display: block;
            margin-top: 25px;
        }
    }

    /* =========================================================
       LOAD ANIMATIONS
    ========================================================= */

    .archive-reveal {
        opacity: 0;
        transform: translateY(25px);
        animation: archiveReveal .7s cubic-bezier(.2, .8, .2, 1) forwards;
    }

    .archive-reveal:nth-child(1) {
        animation-delay: .08s;
    }

    .archive-reveal:nth-child(2) {
        animation-delay: .16s;
    }

    .archive-reveal:nth-child(3) {
        animation-delay: .24s;
    }

    .archive-reveal:nth-child(4) {
        animation-delay: .32s;
    }

    .archive-reveal:nth-child(5) {
        animation-delay: .40s;
    }

    @keyframes archiveReveal {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .archive-reveal {
            opacity: 1;
            transform: none;
            animation: none;
        }

        .archive-edition {
            transition: none;
        }
    }
</style>

@endsection

@section('content')

<div class="archive-page">

    {{-- =====================================================
     HERO
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
                    <strong>{{ $academicYears->count() }}</strong>
                    <span>Yearbook editions</span>
                </div>

            </div>

        </div>

    </section>


    {{-- =====================================================
     INTRO
====================================================== --}}

    <section class="archive-intro">

        <div>
            <p class="archive-intro-label">The archive</p>

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
====================================================== --}}

    <section class="archive-timeline">

        @forelse ($academicYears as $year)

        <article
            class="archive-year archive-reveal {{ $year->status === 'active' ? 'is-current' : '' }}">

            {{-- YEAR --}}
            <div class="archive-year-marker">

                <span class="archive-year-number">
                    {{ $year->title }}
                </span>

                <span class="archive-year-status">
                    {{ $year->status === 'active' ? 'Current edition' : 'Archived edition' }}
                </span>

            </div>


            {{-- CONTENT --}}
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


                {{-- EDITION --}}
                <div class="archive-edition">

                    <div class="archive-edition-inner">

                        <p class="archive-edition-description">
                            A collection of the people, events,
                            milestones, and memories from the
                            {{ $year->title }} academic year.
                        </p>


                        {{-- STATS --}}
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


                        {{-- ACTIONS --}}
                        <div class="archive-actions">

                            <a
                                href="{{ route('public.book', $year->id) }}"
                                class="archive-action archive-action-primary">
                                <span>Explore yearbook</span>

                                <svg
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round">
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
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M12 3v12" />
                                    <path d="m7 10 5 5 5-5" />
                                    <path d="M5 21h14" />
                                </svg>

                                <span>Download PDF</span>
                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </article>

        @empty

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
SIMPLE REVEAL SCRIPT
========================================================= --}}

<script>
    document.addEventListener('DOMContentLoaded', () => {

        const items = document.querySelectorAll('.archive-reveal');

        if (!items.length) return;

        const observer = new IntersectionObserver(
            (entries) => {
                entries.forEach(entry => {

                    if (entry.isIntersecting) {
                        entry.target.classList.add('archive-visible');
                        observer.unobserve(entry.target);

                    }

                });
            }, {
                threshold: 0.12
            }
        );

        items.forEach(item => observer.observe(item));

    });
</script>

@endsection