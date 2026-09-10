@extends('public.layout')

@section('title', 'Graduates')

@section('extra-css')
<style>
    /* =========================================================
       GRADUATES DIRECTORY
       Equal Portrait Archive
       ========================================================= */

    .graduates-page {
        --graduates-accent: var(--red);
        --graduates-dark: var(--ink);
        --graduates-muted: var(--ink-soft);
        --graduates-line: var(--line);
        --graduates-paper: var(--paper);
        --graduates-white: var(--white);
    }

    /* =========================================================
       HERO
       ========================================================= */

    .graduates-hero {
        position: relative;
        overflow: hidden;
        padding: 80px 0 72px;
    }

    .graduates-hero::before,
    .graduates-hero::after {
        content: '';
        position: absolute;
        border: 1px solid rgba(0, 0, 0, 0.06);
        border-radius: 50%;
        pointer-events: none;
    }

    .graduates-hero::before {
        width: 520px;
        height: 520px;
        right: -210px;
        top: -300px;
    }

    .graduates-hero::after {
        width: 280px;
        height: 280px;
        right: -70px;
        top: -175px;
    }

    .graduates-hero-inner {
        position: relative;
        z-index: 1;
        display: grid;
        grid-template-columns: minmax(0, 1.2fr) minmax(280px, 0.8fr);
        gap: 60px;
        align-items: end;
    }

    .graduates-kicker {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 18px;
        color: var(--graduates-accent);
        font-size: 0.7rem;
        font-weight: 800;
        letter-spacing: 2.5px;
        text-transform: uppercase;
    }

    .graduates-kicker::before {
        content: '';
        width: 30px;
        height: 2px;
        background: currentColor;
    }

    .graduates-hero h1 {
        max-width: 850px;
        margin: 0;
        color: var(--graduates-dark);
        font-size: clamp(3.3rem, 7vw, 6.4rem);
        line-height: 0.88;
        letter-spacing: -3.5px;
        font-weight: 800;
    }

    .graduates-hero-copy {
        max-width: 420px;
        margin: 0 0 7px auto;
        color: var(--graduates-muted);
        font-size: 1rem;
        line-height: 1.8;
    }

    /* =========================================================
       FILTERS
       ========================================================= */

    .graduates-discovery {
        position: relative;
        z-index: 5;
        margin-bottom: 65px;
    }

    .graduates-filter-shell {
        padding: 7px;
        border: 1px solid var(--graduates-line);
        border-radius: 17px;
        background: var(--graduates-white);
        box-shadow:
            0 12px 35px rgba(0, 0, 0, 0.055),
            0 2px 6px rgba(0, 0, 0, 0.025);
    }

    .graduates-filter-form {
        display: grid;
        grid-template-columns:
            minmax(210px, 1.5fr) repeat(4, minmax(130px, 1fr)) auto;
        gap: 7px;
    }

    .graduates-filter-field {
        position: relative;
        min-width: 0;
    }

    .graduates-filter-field input,
    .graduates-filter-field select {
        width: 100%;
        height: 57px;
        padding: 0 14px;
        border: 1px solid transparent;
        border-radius: 11px;
        background: var(--graduates-paper);
        color: var(--graduates-dark);
        font-size: 0.83rem;
        font-weight: 600;
        transition:
            border-color 0.2s ease,
            background 0.2s ease,
            box-shadow 0.2s ease;
    }

    .graduates-filter-field input {
        padding-left: 43px;
    }

    .graduates-filter-field input::placeholder {
        color: var(--graduates-muted);
    }

    .graduates-filter-field select {
        appearance: none;
        padding-right: 37px;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='11' height='7' viewBox='0 0 11 7'%3E%3Cpath d='M1 1l4.5 4.5L10 1' stroke='%23888888' stroke-width='1.6' fill='none' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 14px center;
    }

    .graduates-filter-field input:hover,
    .graduates-filter-field select:hover {
        background: var(--graduates-white);
        border-color: var(--graduates-line);
    }

    .graduates-filter-field input:focus,
    .graduates-filter-field select:focus {
        outline: none;
        background: var(--graduates-white);
        border-color: var(--graduates-accent);
        box-shadow: 0 0 0 3px rgba(255, 176, 52, 0.1);
    }

    .graduates-search-icon {
        position: absolute;
        top: 50%;
        left: 15px;
        width: 17px;
        height: 17px;
        transform: translateY(-50%);
        color: var(--graduates-muted);
        pointer-events: none;
    }

    .graduates-filter-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 105px;
        height: 57px;
        padding: 0 18px;
        border: 0;
        border-radius: 11px;
        background: var(--graduates-dark);
        color: var(--graduates-white);
        font-size: 0.82rem;
        font-weight: 750;
        cursor: pointer;
        transition:
            transform 0.2s ease,
            opacity 0.2s ease,
            box-shadow 0.2s ease;
    }

    .graduates-filter-button:hover {
        opacity: 0.92;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.13);
    }

    /* =========================================================
       ACTIVE FILTERS
       ========================================================= */

    .graduates-active-filters {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 12px;
        padding: 0 5px 3px;
    }

    .graduates-active-label {
        color: var(--graduates-muted);
        font-size: 0.68rem;
        font-weight: 800;
        letter-spacing: 1px;
        text-transform: uppercase;
    }

    .graduates-filter-tag {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 6px 10px;
        border: 1px solid var(--graduates-line);
        border-radius: 999px;
        background: var(--graduates-paper);
        color: var(--graduates-dark);
        font-size: 0.7rem;
        font-weight: 650;
    }

    .graduates-filter-tag a {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 17px;
        height: 17px;
        border-radius: 50%;
        color: var(--graduates-muted);
        text-decoration: none;
        transition:
            background 0.2s ease,
            color 0.2s ease;
    }

    .graduates-filter-tag a:hover {
        background: var(--graduates-dark);
        color: var(--graduates-white);
    }

    .graduates-clear {
        margin-left: 3px;
        color: var(--graduates-accent);
        font-size: 0.7rem;
        font-weight: 750;
        text-decoration: none;
    }

    /* =========================================================
       ARCHIVE HEADER
       ========================================================= */

    .graduates-archive-header {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 25px;
        margin-bottom: 35px;
    }

    .graduates-archive-kicker {
        margin-bottom: 7px;
        color: var(--graduates-accent);
        font-size: 0.69rem;
        font-weight: 800;
        letter-spacing: 2px;
        text-transform: uppercase;
    }

    .graduates-archive-header h2 {
        margin: 0;
        color: var(--graduates-dark);
        font-size: clamp(1.8rem, 3vw, 2.6rem);
        line-height: 1;
        letter-spacing: -1px;
    }

    .graduates-count {
        color: var(--graduates-muted);
        font-size: 0.76rem;
        font-weight: 650;
    }

    /* =========================================================
       YEAR DIVIDERS
       ========================================================= */

    .graduates-year-group {
        margin-bottom: 58px;
    }

    .graduates-year-heading {
        display: flex;
        align-items: center;
        gap: 18px;
        margin: 0 0 22px;
    }

    .graduates-year-number {
        color: var(--graduates-dark);
        font-size: 1.05rem;
        font-weight: 800;
        letter-spacing: 0.5px;
    }

    .graduates-year-line {
        flex: 1;
        height: 1px;
        background: var(--graduates-line);
    }

    /* =========================================================
       EQUAL GRADUATE GRID
       ========================================================= */

    .graduates-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 24px;
    }

    .graduate-link {
        display: block;
        color: inherit;
        text-decoration: none;
    }

    .graduate-card {
        height: 100%;
        overflow: hidden;
        border: 1px solid var(--graduates-line);
        border-radius: 17px;
        background: var(--graduates-white);
        transition:
            transform 0.3s ease,
            box-shadow 0.3s ease,
            border-color 0.3s ease;
    }

    .graduate-link:hover .graduate-card {
        transform: translateY(-5px);
        border-color: rgba(255, 176, 52, 0.4);
        box-shadow: 0 17px 35px rgba(0, 0, 0, 0.08);
    }

    .graduate-portrait {
        position: relative;
        height: 330px;
        overflow: hidden;
        background: var(--graduates-paper);
    }

    .graduate-portrait img {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.65s cubic-bezier(0.22, 1, 0.36, 1);
    }

    .graduate-link:hover .graduate-portrait img {
        transform: scale(1.045);
    }

    .graduate-placeholder {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, var(--graduates-dark), #1a3f7f);
        color: #fff;
        font-size: 3.8rem;
        font-weight: 800;
    }

    .graduate-year-badge {
        position: absolute;
        top: 13px;
        right: 13px;
        padding: 6px 10px;
        border-radius: 999px;
        background: var(--graduates-white);
        color: var(--graduates-dark);
        font-size: 0.66rem;
        font-weight: 800;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
    }

    .graduate-card-body {
        padding: 20px 21px 21px;
    }

    .graduate-name {
        margin: 0 0 9px;
        color: var(--graduates-dark);
        font-size: 1.1rem;
        line-height: 1.2;
        letter-spacing: -0.2px;
        font-weight: 750;
    }

    .graduate-major {
        margin: 0 0 4px;
        color: var(--graduates-dark);
        font-size: 0.77rem;
        line-height: 1.4;
        font-weight: 650;
    }

    .graduate-school {
        margin: 0;
        color: var(--graduates-muted);
        font-size: 0.7rem;
        line-height: 1.5;
    }

    .graduate-card-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-top: 17px;
        padding-top: 13px;
        border-top: 1px solid var(--graduates-line);
    }

    .graduate-view {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        color: var(--graduates-dark);
        font-size: 0.68rem;
        font-weight: 800;
        transition:
            gap 0.2s ease,
            color 0.2s ease;
    }

    .graduate-link:hover .graduate-view {
        gap: 8px;
        color: var(--graduates-accent);
    }

    .graduate-view svg {
        width: 13px;
        height: 13px;
    }

    /* =========================================================
       EMPTY STATE
       ========================================================= */

    .graduates-empty {
        max-width: 650px;
        margin: 15px auto 80px;
        padding: 70px 30px;
        text-align: center;
        border: 1px dashed var(--graduates-line);
        border-radius: 20px;
        background: var(--graduates-white);
    }

    .graduates-empty-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 66px;
        height: 66px;
        margin: 0 auto 20px;
        border-radius: 50%;
        background: rgba(255, 176, 52, 0.1);
        color: var(--graduates-accent);
    }

    .graduates-empty-icon svg {
        width: 29px;
        height: 29px;
    }

    .graduates-empty h3 {
        margin: 0 0 8px;
        color: var(--graduates-dark);
        font-size: 1.35rem;
    }

    .graduates-empty p {
        max-width: 450px;
        margin: 0 auto 24px;
        color: var(--graduates-muted);
        font-size: 0.9rem;
        line-height: 1.7;
    }

    /* =========================================================
       PAGINATION
       ========================================================= */

    .graduates-pagination {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        margin: 55px 0 80px;
    }

    .graduates-pagination a,
    .graduates-pagination span {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 40px;
        height: 40px;
        padding: 0 10px;
        border: 1px solid var(--graduates-line);
        border-radius: 9px;
        background: var(--graduates-white);
        color: var(--graduates-dark);
        font-size: 0.76rem;
        font-weight: 700;
        text-decoration: none;
        transition:
            background 0.2s ease,
            border-color 0.2s ease,
            transform 0.2s ease;
    }

    .graduates-pagination a:hover {
        border-color: var(--graduates-accent);
        transform: translateY(-2px);
    }

    .graduates-pagination .active {
        border-color: var(--graduates-dark);
        background: var(--graduates-dark);
        color: var(--graduates-white);
    }

    .graduates-pagination .disabled {
        opacity: 0.4;
    }

    /* =========================================================
       RESPONSIVE
       ========================================================= */

    @media (max-width: 1100px) {
        .graduates-filter-form {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .graduates-filter-button {
            width: 100%;
        }

        .graduates-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }

    @media (max-width: 900px) {
        .graduates-hero {
            padding: 58px 0 52px;
        }

        .graduates-hero-inner {
            grid-template-columns: 1fr;
            gap: 23px;
        }

        .graduates-hero-copy {
            margin: 0;
        }

        .graduates-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 650px) {
        .graduates-hero h1 {
            font-size: clamp(3rem, 14vw, 5rem);
            letter-spacing: -2.5px;
        }

        .graduates-filter-form {
            grid-template-columns: 1fr;
        }

        .graduates-archive-header {
            align-items: flex-start;
            flex-direction: column;
            gap: 8px;
        }

        .graduates-grid {
            grid-template-columns: 1fr;
            gap: 16px;
        }

        .graduate-portrait {
            height: 330px;
        }

        .graduates-year-group {
            margin-bottom: 45px;
        }

        .graduates-pagination {
            margin-top: 40px;
        }
    }

    @media (max-width: 420px) {
        .graduate-portrait {
            height: 290px;
        }

        .graduates-pagination a,
        .graduates-pagination span {
            min-width: 36px;
            height: 36px;
        }
    }

    @media (prefers-reduced-motion: reduce) {

        .graduate-portrait img,
        .graduate-card,
        .graduates-filter-button,
        .graduates-pagination a,
        .graduate-view {
            transition: none;
        }
    }
</style>
@endsection


@section('content')

<div class="graduates-page">

    {{-- =====================================================
         HERO
         ===================================================== --}}
    <section class="graduates-hero">

        <div class="container">

            <div class="graduates-hero-inner">

                <div>
                    <div class="graduates-kicker">
                        The Class Archive
                    </div>

                    <h1>
                        Faces of<br>
                        Our Year
                    </h1>
                </div>

                <p class="graduates-hero-copy">
                    Meet the people who made this academic year
                    memorable. Explore graduates by class, school,
                    campus, and field of study.
                </p>

            </div>

        </div>

    </section>


    {{-- =====================================================
         FILTERS
         ===================================================== --}}
    <section class="graduates-discovery">

        <div class="container">

            <div class="graduates-filter-shell">

                <form
                    method="GET"
                    action="{{ route('public.graduates') }}"
                    id="graduatesForm"
                    class="graduates-filter-form">

                    <div class="graduates-filter-field">

                        <svg
                            class="graduates-search-icon"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            aria-hidden="true">
                            <circle cx="11" cy="11" r="7" />
                            <path d="m20 20-4-4" />
                        </svg>

                        <input
                            type="text"
                            name="search"
                            value="{{ $search }}"
                            placeholder="Search a name..."
                            aria-label="Search graduates">

                    </div>


                    <div class="graduates-filter-field">

                        <select
                            name="school"
                            aria-label="Filter by school">

                            <option value="">
                                All Schools
                            </option>

                            @foreach($schools as $s)

                            <option
                                value="{{ $s->id }}"
                                @if($s->id == $school) selected @endif
                                >
                                {{ $s->name }}
                            </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="graduates-filter-field">

                        <select
                            name="campus"
                            aria-label="Filter by campus">

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


                    <div class="graduates-filter-field">

                        <select
                            name="sort"
                            aria-label="Sort graduates">

                            <option
                                value="name"
                                @if($sort=='name' ) selected @endif>
                                Name A–Z
                            </option>

                            <option
                                value="latest"
                                @if($sort=='latest' ) selected @endif>
                                Latest
                            </option>

                            <option
                                value="oldest"
                                @if($sort=='oldest' ) selected @endif>
                                Oldest
                            </option>

                        </select>

                    </div>


                    <div class="graduates-filter-field">

                        <select
                            name="year"
                            aria-label="Filter by graduation year">

                            <option value="">
                                All Years
                            </option>

                            @foreach($years as $y)

                            <option
                                value="{{ $y }}"
                                @if($y==$year) selected @endif>
                                Class of {{ $y }}
                            </option>

                            @endforeach

                        </select>

                    </div>


                    <button
                        type="submit"
                        class="graduates-filter-button">
                        Explore
                    </button>

                </form>


                @if($search || $school || $campus || $year)

                <div class="graduates-active-filters">

                    <span class="graduates-active-label">
                        Filters
                    </span>


                    @if($search)

                    <div class="graduates-filter-tag">

                        Name: {{ $search }}

                        <a
                            href="{{ route('public.graduates', array_merge(request()->query(), ['search' => null])) }}"
                            aria-label="Remove search filter">
                            ×
                        </a>

                    </div>

                    @endif


                    @if($school)

                    <div class="graduates-filter-tag">

                        School:
                        {{ $schools->find($school)?->name }}

                        <a
                            href="{{ route('public.graduates', array_merge(request()->query(), ['school' => null])) }}"
                            aria-label="Remove school filter">
                            ×
                        </a>

                    </div>

                    @endif


                    @if($campus)

                    <div class="graduates-filter-tag">

                        Campus:
                        {{ $campuses->find($campus)?->name }}

                        <a
                            href="{{ route('public.graduates', array_merge(request()->query(), ['campus' => null])) }}"
                            aria-label="Remove campus filter">
                            ×
                        </a>

                    </div>

                    @endif


                    @if($year)

                    <div class="graduates-filter-tag">

                        Class of {{ $year }}

                        <a
                            href="{{ route('public.graduates', array_merge(request()->query(), ['year' => null])) }}"
                            aria-label="Remove year filter">
                            ×
                        </a>

                    </div>

                    @endif


                    <a
                        href="{{ route('public.graduates') }}"
                        class="graduates-clear">
                        Clear all
                    </a>

                </div>

                @endif

            </div>

        </div>

    </section>


    {{-- =====================================================
         GRADUATE ARCHIVE
         ===================================================== --}}
    <section class="section">

        <div class="container">

            @if($graduates->count() > 0)

            <div class="graduates-archive-header">

                <div>
                    <div class="graduates-archive-kicker">
                        Class Archive
                    </div>

                    <h2>
                        Meet the graduates
                    </h2>
                </div>

                <div class="graduates-count">
                    Showing
                    {{ $graduates->firstItem() }}
                    –
                    {{ $graduates->lastItem() }}
                    of
                    {{ $graduates->total() }}
                </div>

            </div>


            @php
            /*
            * Keep every graduate equal.
            *
            * We group by graduation year only when that
            * information is available. This gives the page
            * an editorial/yearbook structure without making
            * one student visually more important.
            */
            $groupedGraduates = $graduates->getCollection()
            ->groupBy(function ($graduate) {
            if ($graduate->graduation?->ceremony_date) {
            return \Carbon\Carbon::parse(
            $graduate->graduation->ceremony_date
            )->format('Y');
            }

            return 'Class';
            });
            @endphp


            @foreach($groupedGraduates as $groupYear => $groupGraduates)

            <section class="graduates-year-group">

                <div class="graduates-year-heading">

                    <span class="graduates-year-number">
                        {{ $groupYear === 'Class' ? 'Class' : $groupYear }}
                    </span>

                    <span class="graduates-year-line"></span>

                </div>


                <div class="graduates-grid">

                    @foreach($groupGraduates as $graduate)

                    @php
                    $portrait = $graduate->media->first();

                    $graduationYear = null;

                    if ($graduate->graduation?->ceremony_date) {
                    $graduationYear = \Carbon\Carbon::parse(
                    $graduate->graduation->ceremony_date
                    )->format('Y');
                    }
                    @endphp


                    <a
                        href="{{ route('public.graduate.detail', $graduate->id) }}"
                        class="graduate-link"
                        aria-label="View {{ $graduate->name }}'s graduate profile">

                        <article class="graduate-card">

                            <div class="graduate-portrait">

                                @if($portrait)

                                <img
                                    src="{{ Storage::disk('public')->url($portrait->path) }}"
                                    alt="{{ $graduate->name }}"
                                    loading="lazy">

                                @else

                                <div
                                    class="graduate-placeholder"
                                    aria-hidden="true">
                                    {{ strtoupper(substr($graduate->name, 0, 1)) }}
                                </div>

                                @endif


                                @if($graduationYear)

                                <span class="graduate-year-badge">
                                    {{ $graduationYear }}
                                </span>

                                @endif

                            </div>


                            <div class="graduate-card-body">

                                <h3 class="graduate-name">
                                    {{ $graduate->name }}
                                </h3>


                                <p class="graduate-major">
                                    {{ $graduate->major->name ?? 'Major not specified' }}
                                </p>


                                <p class="graduate-school">
                                    {{ $graduate->school->name ?? 'School not specified' }}
                                </p>


                                <div class="graduate-card-footer">

                                    <span class="graduate-school">
                                        {{ $graduationYear
                                                        ? 'Class of ' . $graduationYear
                                                        : 'Graduate'
                                                    }}
                                    </span>


                                    <span class="graduate-view">

                                        Profile

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

                                    </span>

                                </div>

                            </div>

                        </article>

                    </a>

                    @endforeach

                </div>

            </section>

            @endforeach


            {{-- =================================================
                     PAGINATION
                     ================================================= --}}
            @if($graduates->hasPages())

            <nav
                class="graduates-pagination"
                aria-label="Graduate directory pagination">

                @if($graduates->onFirstPage())

                <span class="disabled">
                    ←
                </span>

                @else

                <a
                    href="{{ $graduates->previousPageUrl() }}"
                    aria-label="Previous page">
                    ←
                </a>

                @endif


                @foreach($graduates->getUrlRange(1, $graduates->lastPage()) as $page => $url)

                @if($page == $graduates->currentPage())

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


                @if($graduates->hasMorePages())

                <a
                    href="{{ $graduates->nextPageUrl() }}"
                    aria-label="Next page">
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
            <div class="graduates-empty">

                <div class="graduates-empty-icon">

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.7"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        aria-hidden="true">
                        <circle cx="9" cy="8" r="3" />
                        <path d="M3 21c0-3.3 2.7-6 6-6s6 2.7 6 6" />
                        <path d="M16 3.5a3 3 0 0 1 0 5.8" />
                        <path d="M18 15.5a5.5 5.5 0 0 1 3 5" />
                    </svg>

                </div>


                <h3>
                    No graduates found
                </h3>


                <p>
                    We couldn't find any graduates matching your
                    current search and filters. Try changing your
                    criteria to explore the class archive.
                </p>


                <a
                    href="{{ route('public.graduates') }}"
                    class="btn btn-primary">
                    Browse all graduates
                </a>

            </div>

            @endif

            @if($namedOnly->isNotEmpty())
            <div style="margin-top: 40px; padding-top: 30px; border-top: 1px dashed var(--line); color: var(--ink-soft); font-size: 0.9rem; line-height: 2;">
                <strong style="display:block; color: var(--ink); margin-bottom: 10px;">Also graduating this year</strong>
                {{ $namedOnly->implode(' · ') }}
            </div>
            @endif

        </div>

    </section>

</div>

@endsection