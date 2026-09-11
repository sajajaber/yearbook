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

        --graduates-ease: cubic-bezier(.16, 1, .3, 1);
        --graduates-ease-soft: cubic-bezier(.22, 1, .36, 1);

    }


    /* =========================================================
       PAGE LOAD ANIMATIONS
       ========================================================= */

    @keyframes graduatesRevealUp {

        from {
            opacity: 0;
            transform: translateY(28px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }

    }

    @keyframes graduatesFadeIn {

        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }

    }

    @keyframes graduatesLineReveal {

        from {
            transform: scaleX(0);
            transform-origin: left;
        }

        to {
            transform: scaleX(1);
            transform-origin: left;
        }

    }

    @keyframes graduatesHeroDecor {

        from {
            opacity: 0;
            transform: scale(.92);
        }

        to {
            opacity: 1;
            transform: scale(1);
        }

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

        animation:
            graduatesHeroDecor 1.2s var(--graduates-ease-soft) .15s both;

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

        animation-delay: .3s;

    }


    .graduates-hero-inner {

        position: relative;
        z-index: 1;

        display: grid;

        grid-template-columns:
            minmax(0, 1.2fr) minmax(280px, 0.8fr);

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

        opacity: 0;

        animation:
            graduatesRevealUp .7s var(--graduates-ease) .05s forwards;

    }


    .graduates-kicker::before {

        content: '';

        width: 30px;
        height: 2px;

        background: currentColor;

        transform: scaleX(0);
        transform-origin: left;

        animation:
            graduatesLineReveal .7s var(--graduates-ease) .35s forwards;

    }


    .graduates-hero h1 {

        max-width: 850px;

        margin: 0;

        color: var(--graduates-dark);

        font-size: clamp(3.3rem, 7vw, 6.4rem);

        line-height: 0.88;

        letter-spacing: -3.5px;

        font-weight: 800;

        opacity: 0;

        animation:
            graduatesRevealUp .95s var(--graduates-ease) .15s forwards;

    }


    .graduates-hero-copy {

        max-width: 420px;

        margin: 0 0 7px auto;

        color: var(--graduates-muted);

        font-size: 1rem;

        line-height: 1.8;

        opacity: 0;

        animation:
            graduatesRevealUp .9s var(--graduates-ease) .35s forwards;

    }


    /* =========================================================
       FILTERS
       ========================================================= */

    .graduates-discovery {

        position: relative;

        z-index: 5;

        margin-bottom: 65px;

        opacity: 0;

        animation:
            graduatesRevealUp .8s var(--graduates-ease) .5s forwards;

    }


    .graduates-filter-shell {

        padding: 7px;

        border: 1px solid var(--graduates-line);

        border-radius: 17px;

        background: var(--graduates-white);

        box-shadow:
            0 12px 35px rgba(0, 0, 0, 0.055),
            0 2px 6px rgba(0, 0, 0, 0.025);

        transition:
            border-color .35s ease,
            box-shadow .45s ease,
            transform .45s var(--graduates-ease);

    }


    .graduates-filter-shell:hover {

        border-color: rgba(0, 42, 92, .18);

        box-shadow:
            0 18px 45px rgba(0, 0, 0, .075),
            0 3px 8px rgba(0, 0, 0, .025);

        transform: translateY(-2px);

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
            border-color .3s ease,
            background .3s ease,
            box-shadow .3s ease,
            transform .3s var(--graduates-ease);

    }


    .graduates-filter-field input {

        padding-left: 43px;

    }


    .graduates-filter-field input::placeholder {

        color: var(--graduates-muted);

        transition: color .25s ease;

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

        box-shadow:
            0 0 0 3px rgba(255, 176, 52, 0.1);

        transform: translateY(-1px);

    }


    .graduates-filter-field input:focus::placeholder {

        color: rgba(100, 116, 139, .45);

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

        transition:
            color .3s ease,
            transform .3s var(--graduates-ease);

    }


    .graduates-filter-field:focus-within .graduates-search-icon {

        color: var(--graduates-accent);

        transform:
            translateY(-50%) scale(1.08);

    }


    .graduates-filter-button {

        position: relative;

        display: inline-flex;

        align-items: center;
        justify-content: center;

        min-width: 105px;
        height: 57px;

        padding: 0 18px;

        overflow: hidden;

        border: 0;
        border-radius: 11px;

        background: var(--graduates-dark);

        color: var(--graduates-white);

        font-size: 0.82rem;
        font-weight: 750;

        cursor: pointer;

        transition:
            transform .4s var(--graduates-ease),
            background .3s ease,
            box-shadow .4s ease;

    }


    .graduates-filter-button::before {

        content: '';

        position: absolute;

        inset: 0;

        background:
            linear-gradient(110deg,
                transparent 20%,
                rgba(255, 255, 255, .15) 50%,
                transparent 80%);

        transform: translateX(-120%);

        transition:
            transform .7s var(--graduates-ease);

    }


    .graduates-filter-button:hover {

        background: var(--graduates-accent);

        transform: translateY(-3px);

        box-shadow:
            0 10px 24px rgba(0, 0, 0, .13);

    }


    .graduates-filter-button:hover::before {

        transform: translateX(120%);

    }


    .graduates-filter-button:active {

        transform: translateY(-1px) scale(.98);

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

        animation:
            graduatesFadeIn .5s ease .1s both;

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

        transition:
            transform .3s var(--graduates-ease),
            border-color .3s ease,
            background .3s ease;

    }


    .graduates-filter-tag:hover {

        border-color: rgba(0, 42, 92, .2);

        background: var(--graduates-white);

        transform: translateY(-2px);

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
            background .25s ease,
            color .25s ease,
            transform .25s var(--graduates-ease);

    }


    .graduates-filter-tag a:hover {

        background: var(--graduates-dark);

        color: var(--graduates-white);

        transform: rotate(90deg);

    }


    .graduates-clear {

        margin-left: 3px;

        color: var(--graduates-accent);

        font-size: 0.7rem;
        font-weight: 750;

        text-decoration: none;

        transition:
            color .25s ease,
            transform .3s var(--graduates-ease);

    }


    .graduates-clear:hover {

        color: var(--graduates-dark);

        transform: translateX(3px);

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

        opacity: 0;

        animation:
            graduatesRevealUp .8s var(--graduates-ease) .7s forwards;

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

        opacity: 0;

        animation:
            graduatesRevealUp .7s var(--graduates-ease) .8s forwards;

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

        transform: scaleX(0);

        transform-origin: left;

        animation:
            graduatesLineReveal .8s var(--graduates-ease) .95s forwards;

    }


    /* =========================================================
       EQUAL GRADUATE GRID
       ========================================================= */

    .graduates-grid {

        display: grid;

        grid-template-columns:
            repeat(4, minmax(0, 1fr));

        gap: 24px;

    }


    .graduate-link {

        display: block;

        color: inherit;

        text-decoration: none;

        opacity: 0;

        animation:
            graduatesRevealUp .75s var(--graduates-ease) both;

    }


    /*
     * Page-load stagger.
     *
     * This happens once when the page loads.
     * It is NOT connected to scrolling.
     */

    .graduate-link:nth-child(1) {
        animation-delay: .85s;
    }

    .graduate-link:nth-child(2) {
        animation-delay: .95s;
    }

    .graduate-link:nth-child(3) {
        animation-delay: 1.05s;
    }

    .graduate-link:nth-child(4) {
        animation-delay: 1.15s;
    }

    .graduate-link:nth-child(5) {
        animation-delay: 1.25s;
    }

    .graduate-link:nth-child(6) {
        animation-delay: 1.35s;
    }

    .graduate-link:nth-child(7) {
        animation-delay: 1.45s;
    }

    .graduate-link:nth-child(8) {
        animation-delay: 1.55s;
    }


    .graduate-card {

        height: 100%;

        overflow: hidden;

        border: 1px solid var(--graduates-line);

        border-radius: 17px;

        background: var(--graduates-white);

        transition:
            transform .5s var(--graduates-ease),
            box-shadow .5s ease,
            border-color .35s ease;

    }


    .graduate-link:hover .graduate-card {

        transform: translateY(-7px);

        border-color:
            rgba(255, 176, 52, 0.45);

        box-shadow:
            0 20px 42px rgba(0, 0, 0, .09);

    }


    /* =========================================================
       GRADUATE PORTRAIT
       ========================================================= */

    .graduate-portrait {

        position: relative;

        height: 330px;

        overflow: hidden;

        background: var(--graduates-paper);

    }


    .graduate-portrait::after {

        content: '';

        position: absolute;

        inset: 0;

        background:
            linear-gradient(to top,
                rgba(0, 0, 0, .16),
                transparent 35%);

        opacity: 0;

        pointer-events: none;

        transition:
            opacity .45s ease;

    }


    .graduate-link:hover .graduate-portrait::after {

        opacity: 1;

    }


    .graduate-portrait img {

        display: block;

        width: 100%;
        height: 100%;

        object-fit: cover;

        transition:
            transform .75s var(--graduates-ease);

    }


    .graduate-link:hover .graduate-portrait img {

        transform: scale(1.055);

    }


    .graduate-placeholder {

        display: flex;

        align-items: center;
        justify-content: center;

        width: 100%;
        height: 100%;

        background:
            linear-gradient(135deg,
                var(--graduates-dark),
                #1a3f7f);

        color: #fff;

        font-size: 3.8rem;

        font-weight: 800;

        transition:
            transform .7s var(--graduates-ease);

    }


    .graduate-link:hover .graduate-placeholder {

        transform: scale(1.035);

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

        box-shadow:
            0 4px 12px rgba(0, 0, 0, .12);

        transition:
            transform .4s var(--graduates-ease),
            box-shadow .4s ease;

    }


    .graduate-link:hover .graduate-year-badge {

        transform: translateY(-3px);

        box-shadow:
            0 7px 16px rgba(0, 0, 0, .16);

    }


    /* =========================================================
       CARD CONTENT
       ========================================================= */

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

        transition:
            color .3s ease,
            transform .4s var(--graduates-ease);

    }


    .graduate-link:hover .graduate-name {

        color: var(--graduates-accent);

        transform: translateX(2px);

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
            gap .35s var(--graduates-ease),
            color .3s ease;

    }


    .graduate-link:hover .graduate-view {

        gap: 9px;

        color: var(--graduates-accent);

    }


    .graduate-view svg {

        width: 13px;
        height: 13px;

        transition:
            transform .4s var(--graduates-ease);

    }


    .graduate-link:hover .graduate-view svg {

        transform: translateX(2px);

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

        animation:
            graduatesRevealUp .8s var(--graduates-ease) .7s both;

    }


    .graduates-empty-icon {

        display: flex;

        align-items: center;
        justify-content: center;

        width: 66px;
        height: 66px;

        margin: 0 auto 20px;

        border-radius: 50%;

        background:
            rgba(255, 176, 52, 0.1);

        color: var(--graduates-accent);

        transition:
            transform .45s var(--graduates-ease),
            background .3s ease;

    }


    .graduates-empty:hover .graduates-empty-icon {

        transform: translateY(-4px) scale(1.04);

        background:
            rgba(255, 176, 52, 0.16);

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

        opacity: 0;

        animation:
            graduatesFadeIn .8s ease 1.1s forwards;

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
            background .3s ease,
            border-color .3s ease,
            color .3s ease,
            transform .4s var(--graduates-ease),
            box-shadow .3s ease;

    }


    .graduates-pagination a:hover {

        border-color: var(--graduates-accent);

        background: var(--graduates-paper);

        transform: translateY(-3px);

        box-shadow:
            0 7px 18px rgba(0, 0, 0, .08);

    }


    .graduates-pagination .active {

        border-color: var(--graduates-dark);

        background: var(--graduates-dark);

        color: var(--graduates-white);

        box-shadow:
            0 5px 15px rgba(0, 42, 92, .12);

    }


    .graduates-pagination .disabled {

        opacity: 0.4;

    }


    /* =========================================================
       NAMED-ONLY / EXTRA GRADUATES
       ========================================================= */

    .graduates-named-only {

        margin-top: 40px;

        padding-top: 30px;

        border-top: 1px dashed var(--graduates-line);

        color: var(--graduates-muted);

        font-size: 0.9rem;

        line-height: 2;

        opacity: 0;

        animation:
            graduatesFadeIn .7s ease 1.15s forwards;

    }


    .graduates-named-only strong {

        display: block;

        margin-bottom: 10px;

        color: var(--graduates-dark);

    }


    /* =========================================================
       RESPONSIVE
       ========================================================= */

    @media (max-width: 1100px) {

        .graduates-filter-form {

            grid-template-columns:
                repeat(3, minmax(0, 1fr));

        }


        .graduates-filter-button {

            width: 100%;

        }


        .graduates-grid {

            grid-template-columns:
                repeat(3, minmax(0, 1fr));

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

            grid-template-columns:
                repeat(2, minmax(0, 1fr));

        }

    }


    @media (max-width: 650px) {

        .graduates-hero h1 {

            font-size:
                clamp(3rem, 14vw, 5rem);

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


    /* =========================================================
       ACCESSIBILITY
       ========================================================= */

    @media (prefers-reduced-motion: reduce) {

        *,
        *::before,
        *::after {

            animation-duration: .01ms !important;

            animation-iteration-count: 1 !important;

            transition-duration: .01ms !important;

            scroll-behavior: auto !important;

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

                    {{-- Search --}}

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
                            <circle
                                cx="11"
                                cy="11"
                                r="7" />

                            <path d="m20 20-4-4" />
                        </svg>

                        <input
                            type="text"
                            name="search"
                            value="{{ $search }}"
                            placeholder="Search a name..."
                            aria-label="Search graduates">

                    </div>


                    {{-- School --}}

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
                                @selected($s->id == $school)
                                >
                                {{ $s->name }}
                            </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Campus --}}

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
                                @selected($c->id == $campus)
                                >
                                {{ $c->name }}
                            </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Sort --}}

                    <div class="graduates-filter-field">

                        <select
                            name="sort"
                            aria-label="Sort graduates">

                            <option
                                value="name"
                                @selected($sort=='name' )>
                                Name A–Z
                            </option>

                            <option
                                value="latest"
                                @selected($sort=='latest' )>
                                Latest
                            </option>

                            <option
                                value="oldest"
                                @selected($sort=='oldest' )>
                                Oldest
                            </option>

                        </select>

                    </div>


                    {{-- Year --}}

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
                                @selected($y==$year)>
                                Class of {{ $y }}
                            </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Submit --}}

                    <button
                        type="submit"
                        class="graduates-filter-button">
                        Explore
                    </button>

                </form>


                {{-- Active Filters --}}

                @if($search || $school || $campus || $year)

                <div class="graduates-active-filters">

                    <span class="graduates-active-label">
                        Filters
                    </span>


                    @if($search)

                    <div class="graduates-filter-tag">

                        Name: {{ $search }}

                        <a
                            href="{{ route(
                                        'public.graduates',
                                        array_merge(
                                            request()->query(),
                                            ['search' => null]
                                        )
                                    ) }}"
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
                            href="{{ route(
                                        'public.graduates',
                                        array_merge(
                                            request()->query(),
                                            ['school' => null]
                                        )
                                    ) }}"
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
                            href="{{ route(
                                        'public.graduates',
                                        array_merge(
                                            request()->query(),
                                            ['campus' => null]
                                        )
                                    ) }}"
                            aria-label="Remove campus filter">
                            ×
                        </a>

                    </div>

                    @endif


                    @if($year)

                    <div class="graduates-filter-tag">

                        Class of {{ $year }}

                        <a
                            href="{{ route(
                                        'public.graduates',
                                        array_merge(
                                            request()->query(),
                                            ['year' => null]
                                        )
                                    ) }}"
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

            {{-- Archive Header --}}

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


            {{-- =================================================
                     GROUP GRADUATES BY YEAR
                     ================================================= --}}

            @php

            /*
            * Keep every graduate equal.
            *
            * Graduates are grouped by ceremony year
            * when that information exists.
            */

            $groupedGraduates = $graduates
            ->getCollection()
            ->groupBy(function ($graduate) {

            if (
            $graduate->graduation?->ceremony_date
            ) {

            return \Carbon\Carbon::parse(
            $graduate->graduation->ceremony_date
            )->format('Y');

            }

            return 'Class';

            });

            @endphp


            @foreach($groupedGraduates as $groupYear => $groupGraduates)

            <section class="graduates-year-group">

                {{-- Year Heading --}}

                <div class="graduates-year-heading">

                    <span class="graduates-year-number">

                        {{
                                    $groupYear === 'Class'
                                        ? 'Class'
                                        : $groupYear
                                }}

                    </span>

                    <span class="graduates-year-line"></span>

                </div>


                {{-- Graduate Grid --}}

                <div class="graduates-grid">

                    @foreach($groupGraduates as $graduate)

                    @php

                    $portrait =
                    $graduate->media->first();

                    $graduationYear = null;

                    if (
                    $graduate->graduation?->ceremony_date
                    ) {

                    $graduationYear =
                    \Carbon\Carbon::parse(
                    $graduate
                    ->graduation
                    ->ceremony_date
                    )->format('Y');

                    }

                    @endphp


                    <a
                        href="{{ route(
                                        'public.graduate.detail',
                                        $graduate->id
                                    ) }}"
                        class="graduate-link"
                        aria-label="View {{ $graduate->name }}'s graduate profile">

                        <article class="graduate-card">


                            {{-- Portrait --}}

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
                                    {{
                                                        strtoupper(
                                                            substr(
                                                                $graduate->name,
                                                                0,
                                                                1
                                                            )
                                                        )
                                                    }}
                                </div>

                                @endif


                                {{-- Graduation Year --}}

                                @if($graduationYear)

                                <span
                                    class="graduate-year-badge">
                                    {{ $graduationYear }}
                                </span>

                                @endif

                            </div>


                            {{-- Card Content --}}

                            <div class="graduate-card-body">

                                <h3 class="graduate-name">
                                    {{ $graduate->name }}
                                </h3>


                                <p class="graduate-major">

                                    {{
                                                    $graduate->major->name
                                                    ?? 'Major not specified'
                                                }}

                                </p>


                                <p class="graduate-school">

                                    {{
                                                    $graduate->school->name
                                                    ?? 'School not specified'
                                                }}

                                </p>


                                {{-- Card Footer --}}

                                <div class="graduate-card-footer">

                                    <span class="graduate-school">

                                        {{
                                                        $graduationYear
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


                @foreach(
                $graduates->getUrlRange(
                1,
                $graduates->lastPage()
                ) as $page => $url
                )

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

                        <circle
                            cx="9"
                            cy="8"
                            r="3" />

                        <path
                            d="M3 21c0-3.3 2.7-6 6-6s6 2.7 6 6" />

                        <path
                            d="M16 3.5a3 3 0 0 1 0 5.8" />

                        <path
                            d="M18 15.5a5.5 5.5 0 0 1 3 5" />

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


            {{-- =================================================
                 NAMED-ONLY GRADUATES
                 ================================================= --}}

            @if($namedOnly->isNotEmpty())

            <div class="graduates-named-only">

                <strong>
                    Also graduating this year
                </strong>

                {{ $namedOnly->implode(' · ') }}

            </div>

            @endif

        </div>

    </section>

</div>

@endsection