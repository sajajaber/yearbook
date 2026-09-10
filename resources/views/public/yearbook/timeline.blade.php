@extends('public.layout')

@section('title', 'Timeline')

@section('extra-css')

<style>
    /* =========================================================
       TIMELINE — EDITORIAL UNIVERSITY ARCHIVE
    ========================================================= */

    .timeline-page {
        background: #f4f1eb;
        margin: 0 -32px;
        color: var(--ink);
    }

    /* =========================================================
       HERO
    ========================================================= */

    .timeline-hero {
        min-height: 560px;
        background: var(--ink);
        color: #fff;
        padding: 90px 7vw 85px;
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: flex-end;
    }

    .timeline-hero-grid {
        position: absolute;
        inset: 0;
        opacity: .07;
        background-image:
            linear-gradient(rgba(255, 255, 255, .6) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255, 255, 255, .6) 1px, transparent 1px);
        background-size: 75px 75px;
        mask-image: linear-gradient(to right, transparent, black 55%);
        pointer-events: none;
    }

    .timeline-hero-circle {
        position: absolute;
        width: 580px;
        height: 580px;
        border: 1px solid rgba(255, 255, 255, .10);
        border-radius: 50%;
        right: -180px;
        top: -150px;
    }

    .timeline-hero-circle::after {
        content: "";
        position: absolute;
        inset: 65px;
        border: 1px solid rgba(255, 255, 255, .06);
        border-radius: 50%;
    }

    .timeline-hero-content {
        position: relative;
        z-index: 2;
        max-width: 1050px;
    }

    .timeline-kicker {
        display: flex;
        align-items: center;
        gap: 14px;
        color: var(--red);
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 3px;
        margin-bottom: 28px;
    }

    .timeline-kicker::before {
        content: "";
        width: 42px;
        height: 2px;
        background: var(--red);
    }

    .timeline-hero h1 {
        color: #fff;
        font-size: clamp(65px, 10vw, 140px);
        line-height: .82;
        letter-spacing: -6px;
        margin: 0;
        max-width: 900px;
        font-weight: 800;
    }

    .timeline-hero h1 span {
        display: block;
        font-family: "Merriweather", serif;
        color: #aebdcd;
        font-size: .46em;
        line-height: 1.35;
        font-weight: 400;
        letter-spacing: -2px;
        margin-top: 16px;
    }

    .timeline-hero-bottom {
        margin-top: 48px;
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        gap: 40px;
        max-width: 850px;
    }

    .timeline-hero-description {
        color: #b7c4d3;
        max-width: 500px;
        font-size: 14px;
        line-height: 1.8;
        margin: 0;
    }

    .timeline-hero-scroll {
        color: #8798ab;
        font-size: 9px;
        text-transform: uppercase;
        letter-spacing: 2px;
        white-space: nowrap;
    }

    /* =========================================================
       FILTER AREA
    ========================================================= */

    .timeline-controls {
        background: #fff;
        border-bottom: 1px solid #ddd9d1;
        padding: 25px 7vw;
        position: sticky;
        top: 0;
        z-index: 20;
    }

    .timeline-controls-inner {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 30px;
    }

    .timeline-controls-label {
        flex-shrink: 0;
    }

    .timeline-controls-label span {
        display: block;
        color: var(--red);
        font-size: 9px;
        font-weight: 800;
        letter-spacing: 2px;
        text-transform: uppercase;
        margin-bottom: 4px;
    }

    .timeline-controls-label strong {
        font-size: 14px;
        color: var(--ink);
    }

    .timeline-filters {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 8px;
        flex: 1;
    }

    .timeline-filter {
        position: relative;
    }

    .timeline-filter select {
        appearance: none;
        min-width: 145px;
        padding: 11px 38px 11px 14px;
        background: #f7f5f1;
        border: 1px solid #ddd9d1;
        border-radius: 0;
        color: var(--ink);
        font-size: 10px;
        font-weight: 700;
        cursor: pointer;
        outline: none;
        transition: border-color .2s ease, background .2s ease;
    }

    .timeline-filter select:hover,
    .timeline-filter select:focus {
        background: #fff;
        border-color: var(--ink);
    }

    .timeline-filter::after {
        content: "⌄";
        position: absolute;
        right: 13px;
        top: 50%;
        transform: translateY(-55%);
        pointer-events: none;
        color: var(--ink-soft);
        font-size: 13px;
    }

    .timeline-clear {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 11px 15px;
        background: var(--ink);
        color: #fff;
        text-decoration: none;
        font-size: 9px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: background .2s ease;
    }

    .timeline-clear:hover {
        background: var(--red);
        color: #fff;
    }

    /* =========================================================
       MAIN TIMELINE
    ========================================================= */

    .timeline-main {
        padding: 90px 7vw 120px;
    }

    .timeline-feed {
        max-width: 1100px;
        margin: 0 auto;
        position: relative;
    }

    .timeline-feed::before {
        content: "";
        position: absolute;
        top: 0;
        bottom: 0;
        left: 50%;
        width: 1px;
        background: #d5d1c9;
        transform: translateX(-50%);
    }

    /* =========================================================
       MONTH DIVIDER
    ========================================================= */

    .timeline-month-divider {
        position: relative;
        z-index: 3;
        display: flex;
        justify-content: center;
        margin: 20px 0 65px;
    }

    .timeline-month-badge {
        background: #f4f1eb;
        border: 1px solid #d4d0c8;
        color: var(--ink);
        padding: 10px 20px;
        font-size: 9px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 2px;
        position: relative;
    }

    .timeline-month-badge::before {
        content: "";
        position: absolute;
        left: 50%;
        bottom: -66px;
        width: 9px;
        height: 9px;
        background: var(--red);
        border: 3px solid #f4f1eb;
        border-radius: 50%;
        transform: translateX(-50%);
        box-shadow: 0 0 0 1px var(--red);
    }

    /* =========================================================
       EVENT ITEM
    ========================================================= */

    .timeline-item {
        position: relative;
        width: 50%;
        padding-bottom: 90px;
        opacity: 0;
        transform: translateY(30px);
        animation: timelineReveal .7s cubic-bezier(.2, .8, .2, 1) forwards;
    }

    .timeline-item:nth-child(2) {
        animation-delay: .08s;
    }

    .timeline-item:nth-child(3) {
        animation-delay: .16s;
    }

    .timeline-item:nth-child(4) {
        animation-delay: .24s;
    }

    .timeline-item:nth-child(5) {
        animation-delay: .32s;
    }

    .timeline-item:nth-child(6) {
        animation-delay: .40s;
    }

    .timeline-item:nth-child(7) {
        animation-delay: .48s;
    }

    .timeline-item:nth-child(8) {
        animation-delay: .56s;
    }

    /* Alternating layout */
    .timeline-item:nth-of-type(odd) {
        margin-left: 0;
        padding-right: 70px;
    }

    .timeline-item:nth-of-type(even) {
        margin-left: 50%;
        padding-left: 70px;
    }

    /* =========================================================
       TIMELINE DOT
    ========================================================= */

    .timeline-item::before {
        content: "";
        position: absolute;
        top: 22px;
        width: 13px;
        height: 13px;
        background: var(--red);
        border: 4px solid #f4f1eb;
        border-radius: 50%;
        z-index: 5;
        box-shadow: 0 0 0 1px var(--red);
    }

    .timeline-item:nth-of-type(odd)::before {
        right: -7px;
    }

    .timeline-item:nth-of-type(even)::before {
        left: -7px;
    }

    .timeline-item:hover::before {
        transform: scale(1.35);
        transition: transform .25s ease;
    }

    /* =========================================================
       DATE LABEL
    ========================================================= */

    .timeline-event-date {
        margin-bottom: 12px;
    }

    .timeline-event-date strong {
        font-family: "Merriweather", serif;
        color: var(--ink);
        font-size: 14px;
        font-weight: 700;
    }

    .timeline-event-date span {
        color: var(--red);
        font-size: 9px;
        font-weight: 800;
        letter-spacing: 1px;
        text-transform: uppercase;
        margin-left: 8px;
    }

    /* =========================================================
       EVENT CARD
    ========================================================= */

    .event-card {
        display: block;
        text-decoration: none;
        color: inherit;
        background: #fff;
        border: 1px solid #dedbd4;
        overflow: hidden;
        transition:
            transform .4s cubic-bezier(.2, .8, .2, 1),
            box-shadow .4s ease;
    }

    .event-card:hover {
        transform: translateY(-7px);
        box-shadow: 0 22px 50px rgba(19, 42, 58, .12);
    }

    /* =========================================================
       IMAGE
    ========================================================= */

    .event-card-media {
        height: 230px;
        overflow: hidden;
        position: relative;
        background: var(--ink);
    }

    .event-card-media::after {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(to top,
                rgba(19, 42, 58, .35),
                transparent 50%);
        pointer-events: none;
    }

    .event-card-media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform .7s cubic-bezier(.2, .8, .2, 1);
    }

    .event-card:hover .event-card-media img {
        transform: scale(1.06);
    }

    /* =========================================================
       EVENT BODY
    ========================================================= */

    .event-card-body {
        padding: 25px 27px 28px;
    }

    .event-category {
        display: inline-block;
        padding: 6px 9px;
        background: #f1eee8;
        color: var(--ink-soft);
        font-size: 8px;
        font-weight: 800;
        letter-spacing: 1.3px;
        text-transform: uppercase;
        margin-bottom: 13px;
    }

    .event-title {
        color: var(--ink);
        font-family: "Merriweather", serif;
        font-size: 22px;
        line-height: 1.25;
        margin: 0 0 12px;
        transition: color .2s ease;
    }

    .event-card:hover .event-title {
        color: var(--red);
    }

    .event-desc {
        color: var(--ink-soft);
        font-size: 12px;
        line-height: 1.75;
        margin: 0 0 22px;
    }

    /* =========================================================
       CAMPUS TAGS
    ========================================================= */

    .event-campuses {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        padding-top: 17px;
        border-top: 1px solid #e7e4de;
    }

    .campus-badge {
        color: var(--ink);
        font-size: 8px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .7px;
        padding: 5px 8px;
        border: 1px solid #ddd9d1;
    }

    /* =========================================================
       NO IMAGE VERSION
    ========================================================= */

    .event-card.no-image {
        min-height: 240px;
        display: flex;
        align-items: center;
    }

    .event-card.no-image .event-card-body {
        width: 100%;
    }

    /* =========================================================
       EMPTY STATE
    ========================================================= */

    .timeline-empty {
        max-width: 650px;
        margin: 0 auto;
        padding: 90px 30px;
        text-align: center;
        border: 1px dashed #cbc7bf;
        background: rgba(255, 255, 255, .5);
    }

    .timeline-empty-icon {
        width: 54px;
        height: 54px;
        margin: 0 auto 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #d3cec5;
        border-radius: 50%;
        color: var(--ink-soft);
    }

    .timeline-empty h3 {
        margin: 0 0 8px;
        font-family: "Merriweather", serif;
        font-size: 26px;
    }

    .timeline-empty p {
        color: var(--ink-soft);
        font-size: 12px;
        margin: 0 0 24px;
    }

    /* =========================================================
       FOOTER STATEMENT
    ========================================================= */

    .timeline-end {
        background: var(--ink);
        color: #fff;
        padding: 75px 7vw;
        text-align: center;
    }

    .timeline-end-kicker {
        color: var(--red);
        font-size: 9px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 3px;
        margin-bottom: 18px;
    }

    .timeline-end h2 {
        color: #fff;
        font-family: "Merriweather", serif;
        font-weight: 400;
        font-size: clamp(27px, 4vw, 48px);
        margin: 0;
    }

    /* =========================================================
       ANIMATION
    ========================================================= */

    @keyframes timelineReveal {
        from {
            opacity: 0;
            transform: translateY(30px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .timeline-item {
            opacity: 1;
            transform: none;
            animation: none;
        }

        .event-card,
        .event-card-media img {
            transition: none;
        }
    }

    /* =========================================================
       TABLET
    ========================================================= */

    @media (max-width: 900px) {

        .timeline-hero {
            min-height: 500px;
            padding: 75px 32px;
        }

        .timeline-hero h1 {
            letter-spacing: -4px;
        }

        .timeline-controls {
            padding: 20px 32px;
        }

        .timeline-controls-inner {
            align-items: flex-start;
            flex-direction: column;
        }

        .timeline-filters {
            width: 100%;
            justify-content: flex-start;
            flex-wrap: wrap;
        }

        .timeline-main {
            padding: 70px 32px 90px;
        }

        .timeline-item:nth-of-type(odd) {
            padding-right: 40px;
        }

        .timeline-item:nth-of-type(even) {
            padding-left: 40px;
        }
    }

    /* =========================================================
       MOBILE
    ========================================================= */

    @media (max-width: 650px) {

        .timeline-page {
            margin: 0 -20px;
        }

        .timeline-hero {
            min-height: 470px;
            padding: 60px 24px;
        }

        .timeline-hero h1 {
            font-size: clamp(58px, 17vw, 88px);
            letter-spacing: -4px;
        }

        .timeline-hero h1 span {
            font-size: .5em;
        }

        .timeline-hero-bottom {
            display: block;
        }

        .timeline-hero-scroll {
            display: block;
            margin-top: 25px;
        }

        .timeline-controls {
            position: static;
            padding: 20px 24px;
        }

        .timeline-controls-inner {
            gap: 18px;
        }

        .timeline-filters {
            display: grid;
            grid-template-columns: 1fr;
            width: 100%;
        }

        .timeline-filter select,
        .timeline-clear {
            width: 100%;
        }

        .timeline-main {
            padding: 55px 24px 75px;
        }

        .timeline-feed::before {
            left: 10px;
            transform: none;
        }

        .timeline-month-divider {
            justify-content: flex-start;
            margin-left: 0;
            padding-left: 35px;
            margin-bottom: 45px;
        }

        .timeline-month-badge::before {
            left: -30px;
            bottom: 50%;
            transform: translateY(50%);
        }

        .timeline-item,
        .timeline-item:nth-of-type(odd),
        .timeline-item:nth-of-type(even) {
            width: 100%;
            margin-left: 0;
            padding-left: 35px;
            padding-right: 0;
            padding-bottom: 50px;
        }

        .timeline-item:nth-of-type(odd)::before,
        .timeline-item:nth-of-type(even)::before {
            left: 4px;
            right: auto;
            top: 20px;
        }

        .timeline-event-date strong {
            font-size: 13px;
        }

        .event-card-media {
            height: 190px;
        }

        .event-card-body {
            padding: 22px;
        }

        .event-title {
            font-size: 19px;
        }

        .timeline-end {
            padding: 60px 24px;
        }
    }
</style>

@endsection

@section('content')

<div class="timeline-page">


    {{-- =====================================================
     HERO
====================================================== --}}

    <section class="timeline-hero">

        <div class="timeline-hero-grid"></div>
        <div class="timeline-hero-circle"></div>

        <div class="timeline-hero-content">

            <div class="timeline-kicker">
                LIU · University Archive
            </div>

            <h1>
                Life,
                <span>in moments.</span>
            </h1>

            <div class="timeline-hero-bottom">

                <p class="timeline-hero-description">
                    Explore the moments that shaped university life —
                    from ceremonies and celebrations to the everyday
                    events that became part of the story.
                </p>

                <span class="timeline-hero-scroll">
                    Scroll to explore ↓
                </span>

            </div>

        </div>

    </section>


    {{-- =====================================================
     FILTERS
====================================================== --}}

    <section class="timeline-controls">

        <div class="timeline-controls-inner">

            <div class="timeline-controls-label">
                <span>Archive</span>
                <strong>Browse events</strong>
            </div>

            <form
                id="timeline-filter-form"
                method="GET"
                action="{{ route('public.timeline') }}"
                class="timeline-filters">

                <div class="timeline-filter">

                    <select
                        name="year"
                        onchange="this.form.submit()"
                        aria-label="Filter by year">
                        <option value="">
                            All Years
                        </option>

                        @foreach($years as $year)

                        <option
                            value="{{ $year }}"
                            @if($year==request('year')) selected @endif>
                            {{ $year }}
                        </option>

                        @endforeach

                    </select>

                </div>


                <div class="timeline-filter">

                    <select
                        name="category"
                        onchange="this.form.submit()"
                        aria-label="Filter by category">
                        <option value="">
                            All Categories
                        </option>

                        @foreach($categories as $cat)

                        <option
                            value="{{ $cat->id }}"
                            @if($cat->id == request('category')) selected @endif
                            >
                            {{ $cat->name }}
                        </option>

                        @endforeach

                    </select>

                </div>


                <div class="timeline-filter">

                    <select
                        name="month"
                        onchange="this.form.submit()"
                        aria-label="Filter by month">
                        <option value="">
                            All Months
                        </option>

                        @for($m = 1; $m <= 12; $m++)

                            <option
                            value="{{ $m }}"
                            @if($m==request('month')) selected @endif>
                            {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                            </option>

                            @endfor

                    </select>

                </div>


                @if(request('year') || request('category') || request('month'))

                <a
                    href="{{ route('public.timeline') }}"
                    class="timeline-clear">
                    Clear filters
                </a>

                @endif

            </form>

        </div>

    </section>


    {{-- =====================================================
     TIMELINE
====================================================== --}}

    <main class="timeline-main">

        @if($events->count() > 0)

        <div class="timeline-feed">

            @php
            $currentMonthYear = '';
            @endphp


            @foreach($events as $event)

            @php
            $eventMonthYear = \Carbon\Carbon::parse(
            $event->event_date
            )->format('F Y');

            $image = $event->media->first();
            @endphp


            {{-- MONTH --}}
            @if($currentMonthYear !== $eventMonthYear)

            <div class="timeline-month-divider">

                <span class="timeline-month-badge">
                    {{ $eventMonthYear }}
                </span>

            </div>

            @php
            $currentMonthYear = $eventMonthYear;
            @endphp

            @endif


            {{-- EVENT --}}
            <article class="timeline-item">

                <div class="timeline-event-date">

                    <strong>
                        {{ \Carbon\Carbon::parse($event->event_date)->format('F d, Y') }}
                    </strong>

                    <span>
                        {{ \Carbon\Carbon::parse($event->event_date)->format('l') }}
                    </span>

                </div>


                <a
                    href="{{ route('public.event.detail', $event->id) }}"
                    class="event-card {{ !$image ? 'no-image' : '' }}">

                    {{-- IMAGE --}}
                    @if($image)

                    <div class="event-card-media">

                        <img
                            src="{{ Storage::disk('public')->url($image->path) }}"
                            alt="{{ $event->title }}"
                            loading="lazy">

                    </div>

                    @endif


                    {{-- CONTENT --}}
                    <div class="event-card-body">

                        @if($event->category)

                        <span class="event-category">
                            {{ $event->category->name }}
                        </span>

                        @endif


                        <h2 class="event-title">
                            {{ $event->title }}
                        </h2>


                        @if($event->description)

                        <p class="event-desc">
                            {{ Str::limit($event->description, 160) }}
                        </p>

                        @endif


                        @if($event->campuses->count())

                        <div class="event-campuses">

                            @foreach($event->campuses as $campus)

                            <span class="campus-badge">
                                {{ $campus->name }}
                            </span>

                            @endforeach

                        </div>

                        @endif

                    </div>

                </a>

            </article>

            @endforeach

        </div>

        @else

        <div class="timeline-empty">

            <div class="timeline-empty-icon">

                <svg
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round">
                    <circle cx="12" cy="12" r="9" />
                    <path d="M12 7v5l3 2" />
                </svg>

            </div>

            <h3>
                No moments found
            </h3>

            <p>
                Nothing matches your current filters.
                Try widening your search to discover more events.
            </p>

            <a
                href="{{ route('public.timeline') }}"
                class="timeline-clear">
                Reset filters
            </a>

        </div>

        @endif

    </main>


    {{-- =====================================================
     END
====================================================== --}}

    @if($events->count() > 0)

    <section class="timeline-end">

        <div class="timeline-end-kicker">
            LIU · Digital Yearbook
        </div>

        <h2>
            The moments become the memories.
        </h2>

    </section>

    @endif
    

</div>

@endsection