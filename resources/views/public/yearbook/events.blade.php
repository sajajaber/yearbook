@extends('public.layout')

@section('title', 'Events')

@section('extra-css')

<style>
    /* =========================================================
        EVENTS PAGE
        ========================================================= */

    .events-page {
        --events-accent: var(--red);
        --events-dark: var(--ink);
        --events-muted: var(--ink-soft);
        --events-line: var(--line);
        --events-paper: var(--paper);
        --events-white: var(--white);

        --events-ease: cubic-bezier(.16, 1, .3, 1);
        --events-ease-soft: cubic-bezier(.22, 1, .36, 1);
    }


    /* =========================================================
        HERO
        ========================================================= */

    .events-hero {
        position: relative;
        overflow: hidden;

        padding: 82px 0 72px;

        border-bottom: 1px solid var(--events-line);
    }

    .events-hero::before {
        content: '';

        position: absolute;

        width: 460px;
        height: 460px;

        top: -300px;
        right: -150px;

        border: 1px solid rgba(0, 42, 92, .08);

        border-radius: 50%;

        pointer-events: none;

        transition:
            transform 1.4s var(--events-ease),
            opacity .8s ease;
    }

    .events-hero:hover::before {
        transform: translate(-25px, 30px) scale(1.04);
        opacity: .75;
    }

    .events-hero-inner {
        position: relative;
        z-index: 1;

        display: grid;

        grid-template-columns:
            minmax(0, 1.2fr) minmax(260px, .8fr);

        gap: 60px;

        align-items: end;
    }


    /* =========================================================
        HERO KICKER
        ========================================================= */

    .events-kicker,
    .events-section-label {
        color: var(--events-accent);

        font-size: .7rem;

        font-weight: 800;

        letter-spacing: 2.5px;

        text-transform: uppercase;
    }

    .events-kicker {
        display: inline-flex;

        align-items: center;

        gap: 10px;

        margin-bottom: 18px;

        opacity: 0;

        transform: translateY(18px);

        animation:
            eventsRevealUp .8s var(--events-ease) .05s forwards;
    }

    .events-kicker::before {
        content: '';

        width: 30px;
        height: 2px;

        background: currentColor;

        transform-origin: left;

        transform: scaleX(0);

        animation:
            eventsLineReveal .7s var(--events-ease) .45s forwards;
    }


    /* =========================================================
        HERO TITLE
        ========================================================= */

    .events-hero h1 {
        max-width: 850px;

        margin: 0;

        color: var(--events-dark);

        font-size: clamp(3.2rem, 8vw, 7rem);

        line-height: .9;

        letter-spacing: -4px;

        font-weight: 800;

        opacity: 0;

        transform:
            translateY(35px);

        animation:
            eventsRevealUp 1s var(--events-ease) .18s forwards;
    }


    /* =========================================================
        HERO DESCRIPTION
        ========================================================= */

    .events-hero-description {
        max-width: 440px;

        margin: 0 0 5px auto;

        color: var(--events-muted);

        font-size: .98rem;

        line-height: 1.8;

        opacity: 0;

        transform:
            translateY(25px);

        animation:
            eventsRevealUp .9s var(--events-ease) .38s forwards;
    }


    /* =========================================================
        DISCOVERY / FILTERS
        ========================================================= */

    .events-discovery {
        position: relative;
        z-index: 2;

        margin: 30px 0 70px;

        opacity: 0;

        transform:
            translateY(28px);

        animation:
            eventsRevealUp .85s var(--events-ease) .5s forwards;
    }

    .events-filter-shell {
        padding: 7px;

        border: 1px solid var(--events-line);

        border-radius: 14px;

        background: var(--events-white);

        box-shadow:
            0 8px 30px rgba(0, 42, 92, .035);

        transition:
            box-shadow .45s ease,
            border-color .35s ease,
            transform .45s var(--events-ease);
    }

    .events-filter-shell:hover {
        border-color:
            rgba(0, 42, 92, .2);

        box-shadow:
            0 18px 45px rgba(0, 42, 92, .08);

        transform:
            translateY(-2px);
    }

    .events-filter-form {
        display: grid;

        grid-template-columns:
            minmax(220px, 1.5fr) repeat(4, minmax(145px, 1fr)) auto;

        gap: 7px;
    }

    .events-filter-field {
        min-width: 0;
    }


    /* =========================================================
        FILTER INPUTS
        ========================================================= */

    .events-filter-field input,
    .events-filter-field select {
        width: 100%;

        height: 52px;

        padding: 0 14px;

        border: 1px solid transparent;

        border-radius: 9px;

        background: var(--events-paper);

        color: var(--events-dark);

        font-size: .82rem;

        font-weight: 600;

        outline: none;

        transition:
            border-color .3s ease,
            background .3s ease,
            box-shadow .3s ease,
            transform .3s var(--events-ease);
    }

    .events-filter-field input::placeholder {
        color:
            rgba(100, 116, 139, .72);

        transition:
            color .25s ease;
    }

    .events-filter-field input:hover,
    .events-filter-field select:hover {
        background:
            #eef4f9;
    }

    .events-filter-field input:focus,
    .events-filter-field select:focus {
        border-color:
            var(--events-accent);

        background:
            var(--events-white);

        box-shadow:
            0 0 0 3px rgba(255, 176, 52, .12);

        transform:
            translateY(-1px);
    }

    .events-filter-field input:focus::placeholder {
        color:
            rgba(100, 116, 139, .4);
    }

    .events-filter-field select {
        cursor: pointer;
    }


    /* =========================================================
        FILTER BUTTON
        ========================================================= */

    .events-filter-button {
        position: relative;

        height: 52px;

        min-width: 105px;

        padding: 0 18px;

        overflow: hidden;

        border: 0;

        border-radius: 9px;

        background: var(--events-dark);

        color: #fff;

        font-size: .76rem;

        font-weight: 800;

        letter-spacing: .7px;

        text-transform: uppercase;

        cursor: pointer;

        transition:
            transform .4s var(--events-ease),
            background .3s ease,
            box-shadow .4s ease;
    }

    .events-filter-button::before {
        content: '';

        position: absolute;

        inset: 0;

        background:
            linear-gradient(110deg,
                transparent 20%,
                rgba(255, 255, 255, .16) 50%,
                transparent 80%);

        transform:
            translateX(-120%);

        transition:
            transform .7s var(--events-ease);
    }

    .events-filter-button:hover {
        background:
            var(--events-accent);

        transform:
            translateY(-2px);

        box-shadow:
            0 10px 25px rgba(0, 42, 92, .16);
    }

    .events-filter-button:hover::before {
        transform:
            translateX(120%);
    }

    .events-filter-button:active {
        transform:
            translateY(0) scale(.98);
    }


    /* =========================================================
        ACTIVE FILTERS
        ========================================================= */

    .events-active-filters {
        display: flex;

        align-items: center;

        flex-wrap: wrap;

        gap: 8px;

        margin-top: 10px;

        padding: 0 4px 2px;

        animation:
            eventsFadeIn .45s ease forwards;
    }

    .events-active-label {
        color: var(--events-muted);

        font-size: .65rem;

        font-weight: 800;

        letter-spacing: 1.2px;

        text-transform: uppercase;
    }

    .events-filter-tag {
        display: inline-flex;

        align-items: center;

        gap: 7px;

        padding: 5px 9px;

        border: 1px solid var(--events-line);

        background: var(--events-paper);

        color: var(--events-dark);

        font-size: .68rem;

        font-weight: 700;

        transition:
            transform .3s var(--events-ease),
            border-color .3s ease,
            background .3s ease;
    }

    .events-filter-tag:hover {
        transform:
            translateY(-2px);

        border-color:
            rgba(0, 42, 92, .22);

        background:
            var(--events-white);
    }

    .events-filter-tag a {
        color: var(--events-muted);

        text-decoration: none;

        transition:
            color .25s ease;
    }

    .events-filter-tag a:hover {
        color:
            var(--events-dark);
    }

    .events-clear {
        color: var(--events-accent);

        font-size: .68rem;

        font-weight: 800;

        text-decoration: none;

        transition:
            transform .35s var(--events-ease),
            color .25s ease;
    }

    .events-clear:hover {
        color:
            var(--events-dark);

        transform:
            translateX(3px);
    }


    /* =========================================================
        SECTION INTRO
        ========================================================= */

    .events-section-intro {
        display: flex;

        align-items: end;

        justify-content: space-between;

        gap: 25px;

        margin-bottom: 28px;

        opacity: 0;

        transform:
            translateY(20px);

        animation:
            eventsRevealUp .75s var(--events-ease) .65s forwards;
    }

    .events-section-label {
        margin-bottom: 7px;
    }

    .events-section-intro h2 {
        margin: 0;

        color: var(--events-dark);

        font-size: clamp(1.8rem, 3vw, 2.5rem);

        line-height: 1.1;

        letter-spacing: -.8px;
    }

    .events-results {
        color: var(--events-muted);

        font-size: .74rem;

        font-weight: 700;
    }


    /* =========================================================
        EVENTS LIST
        ========================================================= */

    .events-list {
        border-top:
            2px solid var(--events-dark);
    }

    .event-list-link {
        display: block;

        color: inherit;

        text-decoration: none;
    }

    .event-list-item {
        display: grid;

        grid-template-columns:
            150px minmax(0, 1fr) 34px;

        gap: 28px;

        align-items: center;

        min-height: 128px;

        padding: 24px 10px;

        border-bottom:
            1px solid var(--events-line);

        /*
         * Fixed version of your original transition.
         */
        transition:
            padding .5s var(--events-ease),
            background .45s ease,
            border-color .35s ease,
            transform .5s var(--events-ease);
    }

    .event-list-link:hover .event-list-item {
        padding-left: 18px;

        padding-right: 2px;

        background:
            var(--events-paper);

        transform:
            translateX(4px);
    }


    /* =========================================================
        EVENT DATE
        ========================================================= */

    .event-list-date {
        color: var(--events-accent);

        font-size: .72rem;

        font-weight: 800;

        letter-spacing: 1.2px;

        line-height: 1.5;

        text-transform: uppercase;

        transition:
            transform .45s var(--events-ease),
            color .3s ease;
    }

    .event-list-link:hover .event-list-date {
        transform:
            translateX(4px);

        color:
            var(--events-dark);
    }


    /* =========================================================
        EVENT MAIN CONTENT
        ========================================================= */

    .event-list-main {
        min-width: 0;
    }

    .event-list-meta {
        display: flex;

        align-items: center;

        flex-wrap: wrap;

        gap: 8px;

        margin-bottom: 7px;
    }


    /* =========================================================
        CATEGORY BADGE
        ========================================================= */

    .event-category {
        padding: 4px 8px;

        border: 1px solid rgba(255, 176, 52, .3);

        background:
            rgba(255, 176, 52, .08);

        color:
            var(--events-accent);

        font-size: .62rem;

        font-weight: 800;

        letter-spacing: .5px;

        text-transform: uppercase;

        transition:
            transform .35s var(--events-ease),
            background .3s ease,
            border-color .3s ease;
    }

    .event-list-link:hover .event-category {
        transform:
            translateY(-2px);

        background:
            rgba(255, 176, 52, .14);

        border-color:
            rgba(255, 176, 52, .55);
    }


    /* =========================================================
        LOCATION
        ========================================================= */

    .event-list-location {
        color: var(--events-muted);

        font-size: .67rem;

        font-weight: 650;

        transition:
            color .25s ease;
    }

    .event-list-link:hover .event-list-location {
        color:
            var(--events-dark);
    }


    /* =========================================================
        EVENT TITLE
        ========================================================= */

    .event-list-title {
        margin: 0;

        color: var(--events-dark);

        font-family: 'Merriweather', serif;

        font-size:
            clamp(1.05rem, 2vw, 1.45rem);

        line-height: 1.3;

        font-weight: 700;

        transition:
            transform .5s var(--events-ease),
            color .3s ease;
    }

    .event-list-link:hover .event-list-title {
        color:
            var(--events-accent);

        transform:
            translateX(5px);
    }


    /* =========================================================
        EVENT DESCRIPTION
        ========================================================= */

    .event-list-description {
        max-width: 720px;

        margin: 7px 0 0;

        color: var(--events-muted);

        font-size: .8rem;

        line-height: 1.65;

        transition:
            color .3s ease;
    }

    .event-list-link:hover .event-list-description {
        color:
            #536275;
    }


    /* =========================================================
        EVENT ARROW
        ========================================================= */

    .event-list-arrow {
        display: flex;

        align-items: center;

        justify-content: center;

        width: 34px;
        height: 34px;

        border: 1px solid var(--events-line);

        color: var(--events-dark);

        font-size: 1rem;

        transition:
            transform .5s var(--events-ease),
            border-color .3s ease,
            background .35s ease,
            color .3s ease;
    }

    .event-list-link:hover .event-list-arrow {
        border-color:
            var(--events-dark);

        background:
            var(--events-dark);

        color: #fff;

        transform:
            translateX(4px) rotate(-45deg);
    }


    /* =========================================================
        PAGINATION
        ========================================================= */

    .events-pagination {
        display: flex;

        align-items: center;

        justify-content: center;

        flex-wrap: wrap;

        gap: 6px;

        margin: 48px 0 80px;

        opacity: 0;

        animation:
            eventsFadeIn .7s ease .3s forwards;
    }

    .events-pagination a,
    .events-pagination span {
        display: inline-flex;

        align-items: center;

        justify-content: center;

        min-width: 38px;

        height: 38px;

        padding: 0 10px;

        border: 1px solid var(--events-line);

        background:
            var(--events-white);

        color:
            var(--events-dark);

        font-size: .72rem;

        font-weight: 800;

        text-decoration: none;

        transition:
            transform .4s var(--events-ease),
            border-color .3s ease,
            background .3s ease,
            color .3s ease,
            box-shadow .3s ease;
    }

    .events-pagination a:hover,
    .events-pagination .active {
        border-color:
            var(--events-dark);

        background:
            var(--events-dark);

        color: #fff;

        transform:
            translateY(-3px);

        box-shadow:
            0 7px 18px rgba(0, 42, 92, .12);
    }

    .events-pagination a:active {
        transform:
            translateY(0) scale(.96);
    }

    .events-pagination .disabled {
        color: #a7b3c0;

        background: #f8fafc;

        cursor: not-allowed;
    }


    /* =========================================================
        EMPTY STATE
        ========================================================= */

    .events-empty {
        padding: 70px 30px;

        border-top:
            2px solid var(--events-dark);

        border-bottom:
            1px solid var(--events-line);

        text-align: center;

        opacity: 0;

        transform:
            translateY(25px);

        animation:
            eventsRevealUp .8s var(--events-ease) .25s forwards;
    }

    .events-empty h3 {
        margin: 0 0 8px;

        color:
            var(--events-dark);

        font-family:
            'Merriweather', serif;

        transition:
            transform .4s var(--events-ease);
    }

    .events-empty:hover h3 {
        transform:
            translateY(-3px);
    }

    .events-empty p {
        max-width: 500px;

        margin: 0 auto 22px;

        color:
            var(--events-muted);

        font-size: .85rem;

        line-height: 1.7;
    }

    .events-empty a {
        display: inline-flex;

        padding: 11px 17px;

        background:
            var(--events-dark);

        color: #fff;

        font-size: .7rem;

        font-weight: 800;

        letter-spacing: .8px;

        text-decoration: none;

        text-transform: uppercase;

        transition:
            transform .4s var(--events-ease),
            background .3s ease,
            box-shadow .35s ease;
    }

    .events-empty a:hover {
        background:
            var(--events-accent);

        transform:
            translateY(-3px);

        box-shadow:
            0 10px 25px rgba(0, 42, 92, .15);
    }


    /* =========================================================
        KEYFRAMES
        ========================================================= */

    @keyframes eventsRevealUp {

        from {
            opacity: 0;

            transform:
                translateY(30px);
        }

        to {
            opacity: 1;

            transform:
                translateY(0);
        }

    }

    @keyframes eventsFadeIn {

        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }

    }

    @keyframes eventsLineReveal {

        from {
            transform:
                scaleX(0);
        }

        to {
            transform:
                scaleX(1);
        }

    }


    /* =========================================================
        RESPONSIVE
        ========================================================= */

    @media(max-width:1100px) {

        .events-filter-form {
            grid-template-columns:
                repeat(3, minmax(0, 1fr));
        }

        .events-filter-button {
            width: 100%;
        }

    }


    @media(max-width:800px) {

        .events-hero {
            padding:
                58px 0 50px;
        }

        .events-hero-inner {
            grid-template-columns: 1fr;

            gap: 25px;
        }

        .events-hero-description {
            margin: 0;
        }

        .events-filter-form {
            grid-template-columns:
                1fr 1fr;
        }

        .events-section-intro {
            align-items:
                flex-start;

            flex-direction:
                column;
        }

        .event-list-item {
            grid-template-columns:
                105px minmax(0, 1fr) 30px;

            gap: 18px;
        }

    }


    @media(max-width:560px) {

        .events-hero h1 {
            letter-spacing:
                -2.5px;
        }

        .events-discovery {
            margin-bottom:
                48px;
        }

        .events-filter-form {
            grid-template-columns:
                1fr;
        }

        .event-list-item {
            grid-template-columns:
                1fr 30px;

            gap:
                10px 15px;

            padding:
                22px 5px;
        }

        .event-list-date {
            grid-column:
                1 / -1;
        }

        .event-list-description {
            font-size:
                .76rem;
        }

        .events-pagination {
            margin-top:
                38px;
        }

        .events-pagination a,
        .events-pagination span {
            min-width:
                34px;

            height:
                34px;
        }

    }


    /* =========================================================
        ACCESSIBILITY
        ========================================================= */

    @media(prefers-reduced-motion: reduce) {

        *,
        *::before,
        *::after {
            animation-duration:
                .01ms !important;

            animation-iteration-count:
                1 !important;

            transition-duration:
                .01ms !important;

            scroll-behavior:
                auto !important;
        }

        .events-kicker,
        .events-hero h1,
        .events-hero-description,
        .events-discovery,
        .events-section-intro,
        .events-pagination,
        .events-empty {
            opacity: 1;

            transform:
                none;

            animation: none;
        }

        .events-kicker::before {
            transform:
                scaleX(1);

            animation: none;
        }

    }

    /* =========================================================
   CASCADE SCROLL ANIMATION
   ========================================================= */

    .event-list-link {
        opacity: 0;
        transform: translateY(55px);
        transition:
            opacity .8s var(--events-ease),
            transform .8s var(--events-ease);
    }

    /* When the event enters the viewport */
    .event-list-link.is-visible {
        opacity: 1;
        transform: translateY(0);
    }

    /* Slightly stagger the events */
    .event-list-link:nth-child(1) {
        transition-delay: .05s;
    }

    .event-list-link:nth-child(2) {
        transition-delay: .12s;
    }

    .event-list-link:nth-child(3) {
        transition-delay: .19s;
    }

    .event-list-link:nth-child(4) {
        transition-delay: .26s;
    }

    .event-list-link:nth-child(5) {
        transition-delay: .33s;
    }

    .event-list-link:nth-child(6) {
        transition-delay: .40s;
    }

    .event-list-link:nth-child(7) {
        transition-delay: .47s;
    }

    .event-list-link:nth-child(8) {
        transition-delay: .54s;
    }

    .event-list-link:nth-child(9) {
        transition-delay: .61s;
    }

    .event-list-link:nth-child(10) {
        transition-delay: .68s;
    }

    /* Keep hover animation working after reveal */
    .event-list-link.is-visible:hover .event-list-item {
        padding-left: 18px;
        padding-right: 2px;
        background: var(--events-paper);
        transform: translateX(4px);
    }


    /* =========================================================
   REDUCED MOTION
   ========================================================= */

    @media (prefers-reduced-motion: reduce) {

        .event-list-link {
            opacity: 1;
            transform: none;
            transition: none;
        }

    }
</style>

@endsection


@section('content')

<div class="events-page">


    {{-- =====================================================
        HERO
        ===================================================== --}}

    <section class="events-hero">

        <div class="container">

            <div class="events-hero-inner">

                <div>

                    <div class="events-kicker">
                        Campus Life
                    </div>

                    <h1>
                        Events<br>
                        Worth Remembering
                    </h1>

                </div>


                <p class="events-hero-description">

                    Explore the celebrations, gatherings,
                    achievements, and experiences that shaped
                    the university year. Select an event to open
                    its full story and media gallery.

                </p>

            </div>

        </div>

    </section>


    {{-- =====================================================
        FILTER / DISCOVERY
        ===================================================== --}}

    <section class="events-discovery">

        <div class="container">

            <div class="events-filter-shell">

                <form
                    method="GET"
                    action="{{ route('public.events') }}"
                    class="events-filter-form">

                    <div class="events-filter-field">

                        <input
                            type="text"
                            name="search"
                            value="{{ $search }}"
                            placeholder="Search events..."
                            aria-label="Search events">

                    </div>


                    <div class="events-filter-field">

                        <select
                            name="year"
                            aria-label="Filter by academic year">

                            @foreach($years as $academicYear)

                            <option
                                value="{{ $academicYear->id }}"
                                @selected(
                                (string) $academicYear->id ===
                                (string) $year
                                )
                                >
                                {{ $academicYear->title }}
                            </option>

                            @endforeach

                            <option
                                value=""
                                @selected(!$year)>
                                All Years
                            </option>

                        </select>

                    </div>


                    <div class="events-filter-field">

                        <select
                            name="school"
                            aria-label="Filter by school">

                            <option value="">
                                All Schools
                            </option>

                            @foreach($schools as $s)

                            <option
                                value="{{ $s->id }}"
                                @selected(
                                (string) $s->id ===
                                (string) $school
                                )
                                >
                                {{ $s->name }}
                            </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="events-filter-field">

                        <select
                            name="category"
                            aria-label="Filter by category">

                            <option value="">
                                All Categories
                            </option>

                            @foreach($categories as $cat)

                            <option
                                value="{{ $cat->id }}"
                                @selected(
                                (string) $cat->id ===
                                (string) $category
                                )
                                >
                                {{ $cat->name }}
                            </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="events-filter-field">

                        <select
                            name="campus"
                            aria-label="Filter by campus">

                            <option value="">
                                All Campuses
                            </option>

                            @foreach($campuses as $c)

                            <option
                                value="{{ $c->id }}"
                                @selected(
                                (string) $c->id ===
                                (string) $campus
                                )
                                >
                                {{ $c->name }}
                            </option>

                            @endforeach

                        </select>

                    </div>


                    <button
                        type="submit"
                        class="events-filter-button">
                        Explore
                    </button>

                </form>


                @if(
                $search ||
                $school ||
                $category ||
                $campus ||
                $year
                )

                <div class="events-active-filters">

                    <span class="events-active-label">
                        Filters
                    </span>


                    @if($search)

                    <div class="events-filter-tag">
                        Search: {{ $search }}
                    </div>

                    @endif


                    @if($year)

                    <div class="events-filter-tag">
                        Year:
                        {{ $years->firstWhere('id', $year)?->title }}
                    </div>

                    @endif


                    @if($school)

                    <div class="events-filter-tag">
                        School:
                        {{ $schools->firstWhere('id', $school)?->name }}
                    </div>

                    @endif


                    @if($category)

                    <div class="events-filter-tag">
                        Category:
                        {{ $categories->firstWhere('id', $category)?->name }}
                    </div>

                    @endif


                    @if($campus)

                    <div class="events-filter-tag">
                        Campus:
                        {{ $campuses->firstWhere('id', $campus)?->name }}
                    </div>

                    @endif


                    <a
                        href="{{ route('public.events') }}"
                        class="events-clear">
                        Clear all
                    </a>

                </div>

                @endif

            </div>

        </div>

    </section>


    {{-- =====================================================
        EVENTS
        ===================================================== --}}

    <section class="section">

        <div class="container">

            @if($events->count() > 0)

            <div class="events-section-intro">

                <div>

                    <div class="events-section-label">
                        The Archive
                    </div>

                    <h2>
                        University events
                    </h2>

                </div>


                <div class="events-results">

                    Showing
                    {{ $events->firstItem() }}–{{ $events->lastItem() }}
                    of
                    {{ $events->total() }}
                    events

                </div>

            </div>


            <div class="events-list">

                @foreach($events as $event)

                <a
                    href="{{ route('public.event.detail', $event->id) }}"
                    class="event-list-link"
                    aria-label="View {{ $event->title }}">

                    <article class="event-list-item">


                        {{-- DATE --}}

                        <div class="event-list-date">

                            {{
                                        \Carbon\Carbon::parse(
                                            $event->event_date
                                        )->format('M d, Y')
                                    }}

                        </div>


                        {{-- MAIN CONTENT --}}

                        <div class="event-list-main">

                            <div class="event-list-meta">

                                @if($event->category)

                                <span class="event-category">

                                    {{ $event->category->name }}

                                </span>

                                @endif


                                @if($event->location)

                                <span class="event-list-location">

                                    {{ $event->location }}

                                </span>

                                @endif

                            </div>


                            <h3 class="event-list-title">

                                {{ $event->title }}

                            </h3>


                            @if($event->description)

                            <p class="event-list-description">

                                {{ Str::limit(
                                                $event->description,
                                                180
                                            ) }}

                            </p>

                            @endif

                        </div>


                        {{-- ARROW --}}

                        <span
                            class="event-list-arrow"
                            aria-hidden="true">
                            →
                        </span>

                    </article>

                </a>

                @endforeach

            </div>


            {{-- =================================================
                    PAGINATION
                    ================================================= --}}

            @if($events->hasPages())

            <nav
                class="events-pagination"
                aria-label="Events pagination">

                @if($events->onFirstPage())

                <span
                    class="disabled"
                    aria-disabled="true">
                    ←
                </span>

                @else

                <a
                    href="{{ $events->previousPageUrl() }}"
                    aria-label="Previous page">
                    ←
                </a>

                @endif


                @foreach(
                $events->getUrlRange(
                1,
                $events->lastPage()
                )
                as $page => $url
                )

                @if($page == $events->currentPage())

                <span
                    class="active"
                    aria-current="page">
                    {{ $page }}
                </span>

                @else

                <a href="{{ $url }}">
                    {{ $page }}
                </a>

                @endif

                @endforeach


                @if($events->hasMorePages())

                <a
                    href="{{ $events->nextPageUrl() }}"
                    aria-label="Next page">
                    →
                </a>

                @else

                <span
                    class="disabled"
                    aria-disabled="true">
                    →
                </span>

                @endif

            </nav>

            @endif


            @else


            {{-- =================================================
                    EMPTY STATE
                    ================================================= --}}

            <div class="events-empty">

                <h3>
                    No events found
                </h3>


                <p>

                    We couldn't find any published events
                    matching your current search and filters.

                </p>


                <a
                    href="{{ route('public.events') }}">
                    Browse all events
                </a>

            </div>

            @endif

        </div>

    </section>

</div>

@endsection

<script>
document.addEventListener('DOMContentLoaded', function () {

    const events = document.querySelectorAll('.event-list-link');

    if (!events.length) {
        return;
    }

    const observer = new IntersectionObserver(
        (entries, observer) => {

            entries.forEach(entry => {

                if (!entry.isIntersecting) {
                    return;
                }

                entry.target.classList.add('is-visible');

                observer.unobserve(entry.target);
            });

        },
        {
            threshold: 0.15,
            rootMargin: '0px 0px -60px 0px'
        }
    );

    events.forEach(event => {
        observer.observe(event);
    });

});
</script>