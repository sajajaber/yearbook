@extends('public.layout')

@section('title', 'Events')

@section('extra-css')

<style>
    /* =========================================================
       EVENTS ARCHIVE
       Editorial / Digital Yearbook Layout
       ========================================================= */

    .events-page {
        --events-accent: var(--red);
        --events-dark: var(--ink);
        --events-muted: var(--ink-soft);
        --events-line: var(--line);
        --events-paper: var(--paper);
        --events-white: var(--white);
    }

    /* =========================================================
       HERO
       ========================================================= */

    .events-hero {
        position: relative;
        overflow: hidden;
        padding: 78px 0 70px;
    }

    .events-hero::before {
        content: '';
        position: absolute;

        width: 420px;
        height: 420px;

        top: -260px;
        right: -130px;

        border-radius: 50%;

        background: rgba(255, 176, 52, 0.09);
        pointer-events: none;
    }

    .events-hero::after {
        content: '';

        position: absolute;

        width: 180px;
        height: 180px;

        left: -80px;
        bottom: -100px;

        border: 1px solid rgba(0, 0, 0, 0.07);
        border-radius: 50%;

        pointer-events: none;
    }

    .events-hero-inner {
        position: relative;
        z-index: 1;

        display: grid;
        grid-template-columns: minmax(0, 1.2fr) minmax(260px, 0.8fr);
        gap: 60px;
        align-items: end;
    }

    .events-kicker {
        display: inline-flex;
        align-items: center;
        gap: 10px;

        margin-bottom: 18px;

        color: var(--events-accent);

        font-size: 0.72rem;
        font-weight: 800;
        letter-spacing: 2.5px;
        text-transform: uppercase;
    }

    .events-kicker::before {
        content: '';

        width: 30px;
        height: 2px;

        background: currentColor;
    }

    .events-hero h1 {
        max-width: 800px;

        margin: 0;

        color: var(--events-dark);

        font-size: clamp(3rem, 7vw, 6.2rem);
        line-height: 0.9;
        letter-spacing: -3px;
        font-weight: 800;
    }

    .events-hero-description {
        max-width: 430px;

        margin: 0 0 6px auto;

        color: var(--events-muted);

        font-size: 1rem;
        line-height: 1.8;
    }

    /* =========================================================
       DISCOVERY / FILTERS
       ========================================================= */

    .events-discovery {
        position: relative;
        z-index: 5;

        margin-top: -8px;
        margin-bottom: 60px;
    }

    .events-filter-shell {
        padding: 7px;

        border: 1px solid var(--events-line);
        border-radius: 17px;

        background: var(--events-white);

        box-shadow:
            0 12px 35px rgba(0, 0, 0, 0.055),
            0 2px 6px rgba(0, 0, 0, 0.025);
    }

    .events-filter-form {
        display: grid;
        grid-template-columns: minmax(220px, 1.5fr) repeat(3, minmax(150px, 1fr)) auto;
        gap: 7px;
    }

    .events-filter-field {
        position: relative;
        min-width: 0;
    }

    .events-filter-field input,
    .events-filter-field select {
        width: 100%;
        height: 58px;

        padding: 0 15px;

        border: 1px solid transparent;
        border-radius: 11px;

        background: var(--events-paper);
        color: var(--events-dark);

        font-size: 0.87rem;
        font-weight: 600;

        transition:
            border-color 0.2s ease,
            background 0.2s ease,
            box-shadow 0.2s ease;
    }

    .events-filter-field input {
        padding-left: 43px;
    }

    .events-filter-field select {
        appearance: none;

        padding-right: 38px;

        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='11' height='7' viewBox='0 0 11 7'%3E%3Cpath d='M1 1l4.5 4.5L10 1' stroke='%23888888' stroke-width='1.6' fill='none' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 15px center;
    }

    .events-filter-field input::placeholder {
        color: var(--events-muted);
    }

    .events-filter-field input:hover,
    .events-filter-field select:hover {
        background: var(--events-white);
        border-color: var(--events-line);
    }

    .events-filter-field input:focus,
    .events-filter-field select:focus {
        outline: none;

        background: var(--events-white);

        border-color: var(--events-accent);

        box-shadow:
            0 0 0 3px rgba(255, 176, 52, 0.1);
    }

    .events-search-icon {
        position: absolute;

        top: 50%;
        left: 15px;

        width: 17px;
        height: 17px;

        transform: translateY(-50%);

        color: var(--events-muted);

        pointer-events: none;
    }

    .events-filter-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;

        min-width: 115px;
        height: 58px;

        padding: 0 18px;

        border: 0;
        border-radius: 11px;

        background: var(--events-dark);
        color: var(--events-white);

        font-size: 0.84rem;
        font-weight: 700;

        cursor: pointer;

        transition:
            transform 0.2s ease,
            opacity 0.2s ease,
            box-shadow 0.2s ease;
    }

    .events-filter-button:hover {
        opacity: 0.92;
        transform: translateY(-2px);

        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.13);
    }

    /* =========================================================
       ACTIVE FILTERS
       ========================================================= */

    .events-active-filters {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;

        margin-top: 12px;
        padding: 0 5px 3px;
    }

    .events-active-label {
        color: var(--events-muted);

        font-size: 0.7rem;
        font-weight: 800;
        letter-spacing: 1px;
        text-transform: uppercase;
    }

    .events-filter-tag {
        display: inline-flex;
        align-items: center;
        gap: 7px;

        padding: 6px 10px;

        border: 1px solid var(--events-line);
        border-radius: 999px;

        background: var(--events-paper);
        color: var(--events-dark);

        font-size: 0.72rem;
        font-weight: 650;
    }

    .events-filter-tag a {
        display: inline-flex;
        align-items: center;
        justify-content: center;

        width: 17px;
        height: 17px;

        border-radius: 50%;

        color: var(--events-muted);

        text-decoration: none;

        transition:
            background 0.2s ease,
            color 0.2s ease;
    }

    .events-filter-tag a:hover {
        background: var(--events-dark);
        color: var(--events-white);
    }

    .events-clear {
        margin-left: 3px;

        color: var(--events-accent);

        font-size: 0.72rem;
        font-weight: 700;

        text-decoration: none;
    }

    /* =========================================================
       SECTION INTRO
       ========================================================= */

    .events-section-intro {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;

        gap: 25px;

        margin-bottom: 35px;
    }

    .events-section-label {
        margin-bottom: 7px;

        color: var(--events-accent);

        font-size: 0.7rem;
        font-weight: 800;
        letter-spacing: 2px;
        text-transform: uppercase;
    }

    .events-section-intro h2 {
        margin: 0;

        color: var(--events-dark);

        font-size: clamp(1.8rem, 3vw, 2.5rem);
        line-height: 1.1;
        letter-spacing: -0.8px;
    }

    .events-results {
        flex-shrink: 0;

        color: var(--events-muted);

        font-size: 0.78rem;
        font-weight: 650;
    }

    /* =========================================================
       EDITORIAL EVENT GRID
       ========================================================= */

    .events-editorial-grid {
        display: grid;

        grid-template-columns: repeat(12, minmax(0, 1fr));

        gap: 22px;
    }

    .event-editorial-link {
        display: block;

        color: inherit;
        text-decoration: none;
    }

    /* First event */
    .event-editorial-link:nth-child(1) {
        grid-column: span 7;
        grid-row: span 2;
    }

    /* Second event */
    .event-editorial-link:nth-child(2) {
        grid-column: span 5;
    }

    /* Third event */
    .event-editorial-link:nth-child(3) {
        grid-column: span 5;
    }

    /* Remaining events */
    .event-editorial-link:nth-child(n + 4) {
        grid-column: span 4;
    }

    /* =========================================================
       FEATURE EVENT
       ========================================================= */

    .event-feature {
        position: relative;

        height: 610px;

        overflow: hidden;

        border-radius: 20px;

        background: var(--events-dark);

        box-shadow:
            0 10px 30px rgba(0, 0, 0, 0.08);
    }

    .event-feature-image {
        position: absolute;
        inset: 0;

        width: 100%;
        height: 100%;

        object-fit: cover;

        transition:
            transform 0.8s cubic-bezier(0.22, 1, 0.36, 1);
    }

    .event-feature::after {
        content: '';

        position: absolute;
        inset: 0;

        background:
            linear-gradient(
                180deg,
                rgba(0, 0, 0, 0.03) 25%,
                rgba(0, 0, 0, 0.15) 45%,
                rgba(0, 0, 0, 0.82) 100%
            );
    }

    .event-editorial-link:hover .event-feature-image {
        transform: scale(1.045);
    }

    .event-feature-content {
        position: absolute;
        z-index: 2;

        right: 0;
        bottom: 0;
        left: 0;

        padding: 34px;
    }

    .event-feature-date {
        display: inline-flex;
        align-items: center;
        gap: 8px;

        margin-bottom: 13px;

        color: rgba(255, 255, 255, 0.78);

        font-size: 0.7rem;
        font-weight: 800;
        letter-spacing: 1.5px;
        text-transform: uppercase;
    }

    .event-feature-date::before {
        content: '';

        width: 22px;
        height: 2px;

        background: var(--events-accent);
    }

    .event-feature-title {
        max-width: 680px;

        margin: 0 0 13px;

        color: #fff;

        font-size: clamp(2rem, 4vw, 3.4rem);
        line-height: 1.02;
        letter-spacing: -1.4px;
        font-weight: 800;
    }

    .event-feature-description {
        max-width: 600px;

        margin: 0;

        color: rgba(255, 255, 255, 0.78);

        font-size: 0.9rem;
        line-height: 1.65;
    }

    /* =========================================================
       STANDARD EDITORIAL CARDS
       ========================================================= */

    .event-editorial-card {
        height: 100%;

        overflow: hidden;

        border: 1px solid var(--events-line);
        border-radius: 18px;

        background: var(--events-white);

        transition:
            transform 0.3s ease,
            box-shadow 0.3s ease,
            border-color 0.3s ease;
    }

    .event-editorial-link:hover .event-editorial-card {
        transform: translateY(-5px);

        border-color: rgba(255, 176, 52, 0.35);

        box-shadow:
            0 17px 35px rgba(0, 0, 0, 0.08);
    }

    .event-editorial-image {
        position: relative;

        height: 250px;

        overflow: hidden;

        background: var(--events-paper);
    }

    .event-editorial-image img {
        display: block;

        width: 100%;
        height: 100%;

        object-fit: cover;

        transition:
            transform 0.7s cubic-bezier(0.22, 1, 0.36, 1);
    }

    .event-editorial-link:hover .event-editorial-image img {
        transform: scale(1.06);
    }

    .event-editorial-placeholder {
        position: relative;

        display: flex;
        align-items: center;
        justify-content: center;

        width: 100%;
        height: 100%;

        overflow: hidden;

        background:
            linear-gradient(
                135deg,
                var(--events-dark),
                #1a3f7f
            );
    }

    .event-editorial-placeholder::before {
        content: '';

        position: absolute;

        width: 180px;
        height: 180px;

        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 50%;
    }

    .event-editorial-placeholder::after {
        content: '';

        position: absolute;

        width: 100px;
        height: 100px;

        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }

    .event-placeholder-text {
        position: relative;
        z-index: 1;

        color: rgba(255, 255, 255, 0.7);

        font-size: 0.65rem;
        font-weight: 800;
        letter-spacing: 2px;
        text-transform: uppercase;
    }

    .event-editorial-body {
        display: flex;
        flex-direction: column;

        min-height: 205px;

        padding: 23px 24px;
    }

    .event-editorial-meta {
        display: flex;
        align-items: center;
        justify-content: space-between;

        gap: 12px;

        margin-bottom: 12px;
    }

    .event-category {
        display: inline-flex;
        align-items: center;

        max-width: 60%;

        padding: 5px 9px;

        border: 1px solid rgba(255, 176, 52, 0.28);
        border-radius: 999px;

        background: rgba(255, 176, 52, 0.09);
        color: var(--events-accent);

        font-size: 0.66rem;
        font-weight: 800;

        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .event-date {
        color: var(--events-muted);

        font-size: 0.68rem;
        font-weight: 700;

        white-space: nowrap;
    }

    .event-editorial-title {
        margin: 0 0 10px;

        color: var(--events-dark);

        font-size: 1.22rem;
        line-height: 1.25;
        letter-spacing: -0.25px;
        font-weight: 750;
    }

    .event-editorial-description {
        margin: 0;

        color: var(--events-muted);

        font-size: 0.82rem;
        line-height: 1.7;
    }

    .event-card-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;

        gap: 15px;

        margin-top: auto;
        padding-top: 18px;
    }

    .event-campus-list {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;

        min-width: 0;
    }

    .event-campus {
        display: inline-flex;

        padding: 4px 7px;

        border-radius: 5px;

        background: var(--events-paper);
        color: var(--events-muted);

        font-size: 0.63rem;
        font-weight: 700;
    }

    .event-view {
        display: inline-flex;
        align-items: center;
        gap: 5px;

        flex-shrink: 0;

        color: var(--events-dark);

        font-size: 0.7rem;
        font-weight: 800;

        transition:
            gap 0.2s ease,
            color 0.2s ease;
    }

    .event-editorial-link:hover .event-view {
        gap: 8px;
        color: var(--events-accent);
    }

    .event-view svg {
        width: 13px;
        height: 13px;
    }

    /* =========================================================
       EMPTY STATE
       ========================================================= */

    .events-empty {
        max-width: 650px;

        margin: 15px auto 80px;
        padding: 70px 30px;

        text-align: center;

        border: 1px dashed var(--events-line);
        border-radius: 20px;

        background: var(--events-white);
    }

    .events-empty-icon {
        display: flex;
        align-items: center;
        justify-content: center;

        width: 66px;
        height: 66px;

        margin: 0 auto 20px;

        border-radius: 50%;

        background: rgba(255, 176, 52, 0.1);
        color: var(--events-accent);
    }

    .events-empty-icon svg {
        width: 28px;
        height: 28px;
    }

    .events-empty h3 {
        margin: 0 0 8px;

        color: var(--events-dark);

        font-size: 1.35rem;
    }

    .events-empty p {
        max-width: 450px;

        margin: 0 auto 24px;

        color: var(--events-muted);

        font-size: 0.9rem;
        line-height: 1.7;
    }

    /* =========================================================
       PAGINATION
       ========================================================= */

    .events-pagination {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 7px;

        margin: 55px 0 80px;
    }

    .events-pagination a,
    .events-pagination span {
        display: inline-flex;
        align-items: center;
        justify-content: center;

        min-width: 40px;
        height: 40px;

        padding: 0 10px;

        border: 1px solid var(--events-line);
        border-radius: 9px;

        background: var(--events-white);
        color: var(--events-dark);

        font-size: 0.78rem;
        font-weight: 700;

        text-decoration: none;

        transition:
            background 0.2s ease,
            border-color 0.2s ease,
            transform 0.2s ease;
    }

    .events-pagination a:hover {
        border-color: var(--events-accent);

        transform: translateY(-2px);
    }

    .events-pagination .active {
        border-color: var(--events-dark);
        background: var(--events-dark);
        color: var(--events-white);
    }

    .events-pagination .disabled {
        opacity: 0.4;
    }

    /* =========================================================
       RESPONSIVE
       ========================================================= */

    @media (max-width: 1100px) {
        .events-filter-form {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .events-filter-button {
            width: 100%;
        }

        .event-editorial-link:nth-child(1) {
            grid-column: span 12;
            grid-row: auto;
        }

        .event-editorial-link:nth-child(2),
        .event-editorial-link:nth-child(3) {
            grid-column: span 6;
        }

        .event-editorial-link:nth-child(n + 4) {
            grid-column: span 4;
        }

        .event-feature {
            height: 520px;
        }
    }

    @media (max-width: 800px) {
        .events-hero {
            padding: 55px 0 50px;
        }

        .events-hero-inner {
            grid-template-columns: 1fr;
            gap: 22px;
        }

        .events-hero h1 {
            font-size: clamp(3rem, 14vw, 5rem);
        }

        .events-hero-description {
            margin: 0;
            max-width: 560px;
        }

        .events-discovery {
            margin-bottom: 45px;
        }

        .events-filter-form {
            grid-template-columns: 1fr;
        }

        .events-filter-button {
            width: 100%;
        }

        .events-section-intro {
            align-items: flex-start;
            flex-direction: column;
            margin-bottom: 25px;
        }

        .events-editorial-grid {
            grid-template-columns: 1fr;
            gap: 16px;
        }

        .event-editorial-link:nth-child(1),
        .event-editorial-link:nth-child(2),
        .event-editorial-link:nth-child(3),
        .event-editorial-link:nth-child(n + 4) {
            grid-column: 1;
        }

        .event-feature {
            height: 480px;
        }

        .event-editorial-image {
            height: 230px;
        }
    }

    @media (max-width: 520px) {
        .events-hero h1 {
            letter-spacing: -2px;
        }

        .events-filter-shell {
            border-radius: 14px;
        }

        .events-filter-field input,
        .events-filter-field select,
        .events-filter-button {
            height: 54px;
        }

        .event-feature {
            height: 430px;
            border-radius: 16px;
        }

        .event-feature-content {
            padding: 23px;
        }

        .event-feature-title {
            font-size: 2rem;
        }

        .event-feature-description {
            font-size: 0.82rem;
        }

        .event-editorial-card {
            border-radius: 15px;
        }

        .event-editorial-image {
            height: 210px;
        }

        .event-editorial-body {
            min-height: 190px;
            padding: 20px;
        }

        .events-pagination {
            margin-top: 40px;
        }

        .events-pagination a,
        .events-pagination span {
            min-width: 36px;
            height: 36px;
        }
    }

    /* =========================================================
       REDUCED MOTION
       ========================================================= */

    @media (prefers-reduced-motion: reduce) {
        .event-feature-image,
        .event-editorial-image img,
        .event-editorial-card,
        .events-filter-button,
        .events-pagination a,
        .event-view {
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
                    Moments<br>
                    Worth Remembering
                </h1>
            </div>

            <p class="events-hero-description">
                Discover the celebrations, gatherings, achievements,
                and experiences that brought our university community
                together throughout the year.
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
                id="filterForm"
                class="events-filter-form"
            >

                {{-- Search --}}
                <div class="events-filter-field">

                    <svg
                        class="events-search-icon"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        aria-hidden="true"
                    >
                        <circle cx="11" cy="11" r="7"/>
                        <path d="m20 20-4-4"/>
                    </svg>

                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Search memories..."
                        aria-label="Search events"
                    >

                </div>


                {{-- Category --}}
                <div class="events-filter-field">

                    <select name="category" aria-label="Filter by category">

                        <option value="">
                            All Categories
                        </option>

                        @foreach($categories as $cat)
                            <option
                                value="{{ $cat->id }}"
                                @if($cat->id == $category) selected @endif
                            >
                                {{ $cat->name }}
                            </option>
                        @endforeach

                    </select>

                </div>


                {{-- Campus --}}
                <div class="events-filter-field">

                    <select name="campus" aria-label="Filter by campus">

                        <option value="">
                            All Campuses
                        </option>

                        @foreach($campuses as $c)
                            <option
                                value="{{ $c->id }}"
                                @if($c->id == $campus) selected @endif
                            >
                                {{ $c->name }}
                            </option>
                        @endforeach

                    </select>

                </div>


                {{-- Sort --}}
                <div class="events-filter-field">

                    <select name="sort" aria-label="Sort events">

                        <option
                            value="latest"
                            @if($sort == 'latest') selected @endif
                        >
                            Latest First
                        </option>

                        <option
                            value="oldest"
                            @if($sort == 'oldest') selected @endif
                        >
                            Oldest First
                        </option>

                        <option
                            value="alphabetical"
                            @if($sort == 'alphabetical') selected @endif
                        >
                            Alphabetical
                        </option>

                    </select>

                </div>


                <button
                    type="submit"
                    class="events-filter-button"
                >
                    Explore
                </button>

            </form>


            {{-- Active filters --}}
            @if($search || $category || $campus)

                <div class="events-active-filters">

                    <span class="events-active-label">
                        Filters
                    </span>


                    @if($search)
                        <div class="events-filter-tag">

                            Search: {{ $search }}

                            <a
                                href="{{ route('public.events', array_merge(request()->query(), ['search' => null])) }}"
                                aria-label="Remove search filter"
                            >
                                ×
                            </a>

                        </div>
                    @endif


                    @if($category)

                        <div class="events-filter-tag">

                            Category:
                            {{ $categories->find($category)?->name }}

                            <a
                                href="{{ route('public.events', array_merge(request()->query(), ['category' => null])) }}"
                                aria-label="Remove category filter"
                            >
                                ×
                            </a>

                        </div>

                    @endif


                    @if($campus)

                        <div class="events-filter-tag">

                            Campus:
                            {{ $campuses->find($campus)?->name }}

                            <a
                                href="{{ route('public.events', array_merge(request()->query(), ['campus' => null])) }}"
                                aria-label="Remove campus filter"
                            >
                                ×
                            </a>

                        </div>

                    @endif


                    <a
                        href="{{ route('public.events') }}"
                        class="events-clear"
                    >
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

            {{-- Section intro --}}
            <div class="events-section-intro">

                <div>

                    <div class="events-section-label">
                        The Archive
                    </div>

                    <h2>
                        Recent moments
                    </h2>

                </div>

                <div class="events-results">
                    Showing
                    {{ $events->firstItem() }}
                    –
                    {{ $events->lastItem() }}
                    of
                    {{ $events->total() }}
                    events
                </div>

            </div>


            {{-- =================================================
                 EDITORIAL GRID
                 ================================================= --}}
            <div class="events-editorial-grid">

                @foreach($events as $event)

                    @php
                        $image = $event->media->first();
                    @endphp


                    <a
                        href="{{ route('public.event.detail', $event->id) }}"
                        class="event-editorial-link"
                        aria-label="View {{ $event->title }}"
                    >

                        {{-- =====================================
                             FEATURE EVENT
                             ===================================== --}}
                        @if($loop->first)

                            <article class="event-feature">

                                @if($image)

                                    <img
                                        src="{{ Storage::disk('public')->url($image->path) }}"
                                        alt="{{ $event->title }}"
                                        class="event-feature-image"
                                        loading="eager"
                                    >

                                @else

                                    <div
                                        class="event-editorial-placeholder event-feature-image"
                                        aria-hidden="true"
                                    >
                                        <span class="event-placeholder-text">
                                            University Memories
                                        </span>
                                    </div>

                                @endif


                                <div class="event-feature-content">

                                    <div class="event-feature-date">
                                        {{ \Carbon\Carbon::parse($event->event_date)->format('F d, Y') }}
                                    </div>

                                    <h3 class="event-feature-title">
                                        {{ $event->title }}
                                    </h3>

                                    @if($event->description)
                                        <p class="event-feature-description">
                                            {{ Str::limit($event->description, 220) }}
                                        </p>
                                    @endif

                                </div>

                            </article>


                        {{-- =====================================
                             STANDARD EVENT
                             ===================================== --}}
                        @else

                            <article class="event-editorial-card">

                                {{-- Image --}}
                                <div class="event-editorial-image">

                                    @if($image)

                                        <img
                                            src="{{ Storage::disk('public')->url($image->path) }}"
                                            alt="{{ $event->title }}"
                                            loading="lazy"
                                        >

                                    @else

                                        <div
                                            class="event-editorial-placeholder"
                                            aria-hidden="true"
                                        >
                                            <span class="event-placeholder-text">
                                                Yearbook
                                            </span>
                                        </div>

                                    @endif

                                </div>


                                {{-- Body --}}
                                <div class="event-editorial-body">

                                    <div class="event-editorial-meta">

                                        @if($event->category)

                                            <span class="event-category">
                                                {{ $event->category->name }}
                                            </span>

                                        @endif

                                        <span class="event-date">
                                            {{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}
                                        </span>

                                    </div>


                                    <h3 class="event-editorial-title">
                                        {{ $event->title }}
                                    </h3>


                                    @if($event->description)

                                        <p class="event-editorial-description">
                                            {{ Str::limit($event->description, 135) }}
                                        </p>

                                    @endif


                                    <div class="event-card-footer">

                                        <div class="event-campus-list">

                                            @foreach($event->campuses as $c)

                                                <span class="event-campus">
                                                    {{ $c->name }}
                                                </span>

                                            @endforeach

                                        </div>


                                        <span class="event-view">

                                            View

                                            <svg
                                                viewBox="0 0 24 24"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="2"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                aria-hidden="true"
                                            >
                                                <path d="M5 12h14"/>
                                                <path d="m13 6 6 6-6 6"/>
                                            </svg>

                                        </span>

                                    </div>

                                </div>

                            </article>

                        @endif

                    </a>

                @endforeach

            </div>


            {{-- =================================================
                 PAGINATION
                 ================================================= --}}
            @if($events->hasPages())

                <nav
                    class="events-pagination"
                    aria-label="Events pagination"
                >

                    {{-- Previous --}}
                    @if($events->onFirstPage())

                        <span class="disabled">
                            ←
                        </span>

                    @else

                        <a
                            href="{{ $events->previousPageUrl() }}"
                            aria-label="Previous page"
                        >
                            ←
                        </a>

                    @endif


                    {{-- Pages --}}
                    @foreach($events->getUrlRange(1, $events->lastPage()) as $page => $url)

                        @if($page == $events->currentPage())

                            <span
                                class="active"
                                aria-current="page"
                            >
                                {{ $page }}
                            </span>

                        @else

                            <a href="{{ $url }}">
                                {{ $page }}
                            </a>

                        @endif

                    @endforeach


                    {{-- Next --}}
                    @if($events->hasMorePages())

                        <a
                            href="{{ $events->nextPageUrl() }}"
                            aria-label="Next page"
                        >
                            →
                        </a>

                    @else

                        <span class="disabled">
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

                <div class="events-empty-icon">

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.7"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        aria-hidden="true"
                    >
                        <rect
                            x="3"
                            y="4"
                            width="18"
                            height="17"
                            rx="2"
                        />

                        <path d="M16 2v4"/>
                        <path d="M8 2v4"/>
                        <path d="M3 10h18"/>
                        <path d="M8 14h.01"/>
                        <path d="M12 14h.01"/>
                        <path d="M16 14h.01"/>
                        <path d="M8 18h.01"/>
                        <path d="M12 18h.01"/>
                    </svg>

                </div>


                <h3>
                    No moments found
                </h3>

                <p>
                    We couldn't find any events matching your current
                    search and filters. Try broadening your search
                    to discover more of the yearbook archive.
                </p>


                <a
                    href="{{ route('public.events') }}"
                    class="btn btn-primary"
                >
                    Browse all events
                </a>

            </div>

        @endif

    </div>

</section>

</div>

@endsection
