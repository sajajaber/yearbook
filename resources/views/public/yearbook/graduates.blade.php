@extends('public.layout')

@section('title', 'Graduates | LIU Digital Yearbook')

@section('extra-css')
<style>
    .graduates-page {
        --g-ink: var(--ink, #002a5c);
        --g-blue: #073972;
        --g-gold: #d7ad59;
        --g-accent: var(--red, #ffb034);
        --g-paper: #f5f2eb;
        --g-muted: #718096;
        --g-line: #dfe6ed;
        --g-ease: cubic-bezier(.16, 1, .3, 1);
        background: var(--g-paper);
        color: var(--g-ink);
    }

    /* =========================================================
       EDITORIAL HERO
       ========================================================= */
    .graduates-hero {
        position: relative;
        min-height: 570px;
        padding: 92px 7vw 88px;
        overflow: hidden;
        isolation: isolate;
        display: flex;
        align-items: flex-end;
        background:
            radial-gradient(circle at 82% 20%, rgba(255, 176, 52, .15), transparent 25%),
            linear-gradient(135deg, #061a33 0%, #073972 100%);
        color: #fff;
    }

    .graduates-hero::before {
        content: "";
        position: absolute;
        width: 620px;
        height: 620px;
        right: -240px;
        top: -310px;
        border: 1px solid rgba(255, 255, 255, .12);
        border-radius: 50%;
        box-shadow: 0 0 0 90px rgba(255, 255, 255, .025), 0 0 0 180px rgba(255, 255, 255, .014);
        animation: graduatesFloat 11s ease-in-out infinite;
    }

    .graduates-hero::after {
        content: "";
        position: absolute;
        inset: 0;
        opacity: .07;
        pointer-events: none;
        background-image:
            linear-gradient(rgba(255, 255, 255, .55) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255, 255, 255, .55) 1px, transparent 1px);
        background-size: 74px 74px;
        mask-image: linear-gradient(to right, transparent, black 58%);
        -webkit-mask-image: linear-gradient(to right, transparent, black 58%);
    }

    .graduates-hero-inner {
        position: relative;
        z-index: 2;
        width: min(1180px, 100%);
        margin: 0 auto;
    }

    .graduates-kicker {
        display: flex;
        align-items: center;
        gap: 13px;
        margin-bottom: 27px;
        color: var(--g-accent);
        font-size: 10px;
        font-weight: 900;
        letter-spacing: 3px;
        text-transform: uppercase;
        animation: graduatesReveal .8s var(--g-ease) both;
    }

    .graduates-kicker::before {
        content: "";
        width: 42px;
        height: 2px;
        background: currentColor;
    }

    .graduates-hero h1 {
        max-width: 900px;
        margin: 0;
        color: #fff;
        font-size: clamp(54px, 8.5vw, 116px);
        line-height: .87;
        letter-spacing: -5px;
        font-weight: 850;
        animation: graduatesTitleIn 1s var(--g-ease) .08s both;
    }

    .graduates-hero h1 em {
        display: block;
        margin-top: 17px;
        color: #b6c4d4;
        font-family: "Merriweather", Georgia, serif;
        font-size: .45em;
        font-weight: 400;
        line-height: 1.25;
        letter-spacing: -1.5px;
        animation: graduatesReveal .9s var(--g-ease) .3s both;
    }

    .graduates-hero-bottom {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 40px;
        max-width: 930px;
        margin-top: 48px;
        animation: graduatesReveal .9s var(--g-ease) .45s both;
    }

    .graduates-hero-copy {
        max-width: 540px;
        margin: 0;
        color: #bdcad8;
        font-size: 14px;
        line-height: 1.8;
    }

    .graduates-hero-stat {
        display: flex;
        gap: 34px;
        flex-shrink: 0;
    }

    .hero-stat strong {
        display: block;
        color: #fff;
        font-size: 35px;
        line-height: 1;
    }

    .hero-stat span {
        display: block;
        margin-top: 7px;
        color: #8294a9;
        font-size: 9px;
        font-weight: 800;
        letter-spacing: 1.5px;
        text-transform: uppercase;
    }

    @keyframes graduatesReveal {
        from {
            opacity: 0;
            transform: translateY(26px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes graduatesTitleIn {
        from {
            opacity: 0;
            transform: translateY(48px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes graduatesFloat {

        0%,
        100% {
            transform: translate3d(0, 0, 0);
        }

        50% {
            transform: translate3d(-16px, 18px, 0);
        }
    }

    /* =========================================================
       DIRECTORY WRAPPER / FILTERS
       ========================================================= */
    .graduates-page>main.container {
        width: 100%;
        max-width: 1240px;
        margin: 0 auto;
        padding: 0 32px 80px;
    }

    .graduates-discovery {
        position: relative;
        z-index: 5;
        margin: -22px 0 54px;
    }

    .graduates-filter-shell {
        padding: 9px;
        border: 1px solid rgba(0, 42, 92, .08);
        border-radius: 18px;
        background: rgba(255, 255, 255, .97);
        box-shadow: 0 18px 48px rgba(15, 23, 42, .08);
        backdrop-filter: blur(14px);
    }

    .graduates-filter-form {
        display: grid;
        grid-template-columns:
            minmax(200px, 1.6fr) repeat(5, minmax(105px, 1fr)) 64px;
        gap: 6px;
        align-items: center;
    }

    .graduates-filter-field {
        min-width: 0;
    }

    .graduates-filter-field input,
    .graduates-filter-field select {
        box-sizing: border-box;
        width: 100%;
        height: 50px;
        padding: 0 13px;
        border: 1px solid #edf1f5;
        border-radius: 11px;
        outline: 0;
        background: #f8fafc;
        color: var(--g-ink);
        font-size: .72rem;
        font-weight: 750;
        transition: .25s ease;
    }

    .graduates-filter-field input:focus,
    .graduates-filter-field select:focus {
        border-color: #d6e0e9;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(215, 173, 89, .1);
    }

    .graduates-filter-button {
        box-sizing: border-box;
        width: 64px;
        min-width: 64px;
        height: 50px;
        padding: 0 6px;
        border: 0;
        border-radius: 11px;
        background: var(--g-ink);
        color: #fff;
        cursor: pointer;
        font-size: .58rem;
        font-weight: 900;
        letter-spacing: .4px;
        text-transform: uppercase;
        white-space: nowrap;
        transition:
            transform .35s var(--g-ease),
            box-shadow .35s ease,
            background .25s ease;
    }

    .graduates-filter-button:hover {
        transform: translateY(-2px);
        background: var(--g-blue);
        box-shadow: 0 10px 24px rgba(0, 42, 92, .16);
    }

    .graduates-overview {
        display: grid;
        grid-template-columns: 1.6fr repeat(3, 1fr);
        gap: 12px;
        margin-bottom: 48px;
    }

    .directory-intro,
    .directory-stat {
        min-height: 96px;
        padding: 19px 21px;
        border: 1px solid var(--g-line);
        border-radius: 15px;
        background: rgba(255, 255, 255, .68);
    }

    .directory-intro small,
    .directory-stat small {
        display: block;
        margin-bottom: 8px;
        color: #aa823b;
        font-size: .58rem;
        font-weight: 900;
        letter-spacing: 1.6px;
        text-transform: uppercase;
    }

    .directory-intro strong {
        display: block;
        color: var(--g-ink);
        font-family: "Merriweather", Georgia, serif;
        font-size: 1.18rem;
        line-height: 1.4;
    }

    .directory-stat strong {
        display: block;
        color: var(--g-ink);
        font-size: 1.55rem;
        line-height: 1;
    }

    .directory-stat span {
        display: block;
        margin-top: 7px;
        color: var(--g-muted);
        font-size: .63rem;
        font-weight: 700;
    }

    /* =========================================================
       SCHOOL-FIRST DIRECTORY
       ========================================================= */
    .school-directory {
        margin-bottom: 60px;
    }

    .school-directory-heading {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 34px;
    }

    .directory-kicker {
        margin: 0 0 7px;
        color: #aa823b;
        font-size: .62rem;
        font-weight: 900;
        letter-spacing: 2px;
        text-transform: uppercase;
    }

    .school-directory-heading h2 {
        margin: 0;
        color: var(--g-ink);
        font-size: clamp(2rem, 4vw, 3.2rem);
        line-height: .98;
        letter-spacing: -2px;
    }

    .directory-result-count {
        color: var(--g-muted);
        font-size: .68rem;
        font-weight: 750;
    }

    .school-block {
        position: relative;
        margin-bottom: 54px;
        padding-top: 4px;
    }

    .school-block::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 54px;
        height: 3px;
        border-radius: 999px;
        background: var(--g-gold);
    }

    .school-block-heading {
        display: flex;
        align-items: baseline;
        gap: 13px;
        padding: 22px 0 18px;
        border-bottom: 1px solid var(--g-line);
    }

    .school-block-heading h3 {
        margin: 0;
        color: var(--g-ink);
        font-family: "Merriweather", Georgia, serif;
        font-size: clamp(1.25rem, 2.2vw, 1.7rem);
        font-weight: 700;
    }

    .school-total {
        color: #8c98a5;
        font-size: .63rem;
        font-weight: 800;
        white-space: nowrap;
    }

    .school-degree-section {
        margin-top: 26px;
    }

    .school-degree-label {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 15px;
    }

    .school-degree-label::before {
        content: "";
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: var(--g-gold);
        box-shadow: 0 0 0 4px rgba(215, 173, 89, .12);
    }

    .school-degree-label strong {
        color: var(--g-ink);
        font-size: .7rem;
        font-weight: 900;
        letter-spacing: 1px;
        text-transform: uppercase;
    }

    .school-degree-label span {
        color: #98a3af;
        font-size: .61rem;
        font-weight: 700;
    }

    .graduates-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 18px;
    }

    /* =========================================================
       GRADUATE CARDS
       ========================================================= */
    .graduate-link {
        display: block;
        color: inherit;
        text-decoration: none;
        -webkit-tap-highlight-color: transparent;
    }

    .graduate-card {
        height: 100%;
        overflow: hidden;
        border: 1px solid #e1e7ed;
        border-radius: 17px;
        background: #fff;
        box-shadow: 0 5px 20px rgba(15, 23, 42, .035);
        transition: transform .45s var(--g-ease), box-shadow .45s ease, border-color .3s ease;
    }

    .graduate-link:hover .graduate-card,
    .graduate-link:focus-visible .graduate-card {
        transform: translateY(-6px);
        border-color: rgba(215, 173, 89, .45);
        box-shadow: 0 20px 40px rgba(15, 23, 42, .085);
    }

    .graduate-link:focus-visible {
        outline: 0;
    }

    .graduate-link:focus-visible .graduate-card {
        box-shadow: 0 0 0 3px rgba(215, 173, 89, .22), 0 20px 40px rgba(15, 23, 42, .085);
    }

    .graduate-portrait {
        position: relative;
        height: 285px;
        overflow: hidden;
        background: linear-gradient(145deg, #173d67, #416d94);
    }

    .graduate-portrait::after {
        content: "";
        position: absolute;
        inset: 0;
        pointer-events: none;
        background: linear-gradient(to top, rgba(4, 20, 39, .18), transparent 42%);
    }

    .graduate-portrait img {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform .7s var(--g-ease);
    }

    .graduate-link:hover .graduate-portrait img,
    .graduate-link:focus-visible .graduate-portrait img {
        transform: scale(1.04);
    }

    .graduate-placeholder {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
        color: rgba(255, 255, 255, .78);
        font-family: "Merriweather", Georgia, serif;
        font-size: 5rem;
    }

    .graduate-badge {
        position: absolute;
        z-index: 2;
        padding: 6px 9px;
        border: 1px solid rgba(255, 255, 255, .16);
        border-radius: 999px;
        background: rgba(4, 20, 39, .72);
        color: #fff;
        font-size: .54rem;
        font-weight: 900;
        letter-spacing: .7px;
        text-transform: uppercase;
        backdrop-filter: blur(8px);
    }

    .graduate-degree-badge {
        top: 12px;
        left: 12px;
    }

    .graduate-year-badge {
        right: 12px;
        bottom: 12px;
    }

    .graduate-card-body {
        padding: 19px 19px 17px;
    }

    .graduate-name {
        margin: 0;
        color: var(--g-ink);
        font-size: 1rem;
        line-height: 1.25;
    }

    .graduate-major {
        margin: 7px 0 0;
        color: var(--g-muted);
        font-size: .68rem;
        font-weight: 700;
        line-height: 1.5;
    }

    .graduate-card-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 17px;
        padding-top: 13px;
        border-top: 1px solid #edf1f5;
    }

    .graduate-view {
        color: #aa823b;
        font-size: .62rem;
        font-weight: 900;
        letter-spacing: .5px;
        text-transform: uppercase;
    }

    .name-only-wrap {
        margin-top: 14px;
        padding: 15px;
        border: 1px dashed #d7e0e8;
        border-radius: 12px;
        background: rgba(255, 255, 255, .5);
    }

    .name-only-note {
        margin: 0 0 10px;
        color: var(--g-muted);
        font-size: .65rem;
        font-weight: 700;
    }

    .graduate-name-only-list {
        display: flex;
        flex-wrap: wrap;
        gap: 7px;
    }

    .graduate-name-only {
        padding: 6px 9px;
        border-radius: 999px;
        background: #edf2f6;
        color: var(--g-ink);
        font-size: .62rem;
        font-weight: 750;
    }

    .graduates-empty {
        padding: 72px 30px;
        text-align: center;
        border: 1px dashed #d5e0e8;
        border-radius: 18px;
        background: rgba(255, 255, 255, .75);
    }

    .graduates-empty h3 {
        margin: 0 0 8px;
        color: var(--g-ink);
    }

    .graduates-empty p {
        margin: 0 0 20px;
        color: var(--g-muted);
        font-size: .84rem;
    }

    .graduates-reset {
        color: var(--g-ink);
        font-size: .7rem;
        font-weight: 900;
        text-decoration: none;
    }

    .graduates-pagination {
        display: flex;
        justify-content: center;
        margin: 50px 0 10px;
    }

    .graduates-pagination nav {
        width: 100%;
    }

    /* =========================================================
       RESPONSIVE
       ========================================================= */
    @media (max-width: 1100px) {
        .graduates-filter-form {
            grid-template-columns:
                minmax(165px, 1.5fr) repeat(5, minmax(85px, 1fr)) 64px;
            gap: 5px;
        }

        .graduates-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .graduates-overview {
            grid-template-columns: 1.5fr repeat(3, 1fr);
        }
    }

    @media (max-width:850px) {
        .graduates-hero {
            min-height: 530px;
            padding: 72px 6vw 70px;
        }

        .graduates-hero-bottom {
            display: block;
        }

        .graduates-hero-stat {
            margin-top: 28px;
        }

        .graduates-overview {
            grid-template-columns: 1fr 1fr;
        }

        .directory-intro {
            grid-column: 1 / -1;
        }

        .graduates-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .graduates-filter-form {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .graduates-filter-button {
            width: 100%;
        }
    }

    @media (max-width:650px) {
        .graduates-page>main.container {
            padding: 0 20px 65px;
        }

        .graduates-hero {
            padding: 60px 24px 62px;
        }

        .graduates-hero h1 {
            letter-spacing: -3px;
        }

        .graduates-overview {
            grid-template-columns: 1fr 1fr;
        }

        .school-directory-heading {
            align-items: flex-start;
            flex-direction: column;
        }

        .graduates-grid {
            grid-template-columns: 1fr 1fr;
            gap: 13px;
        }

        .graduate-portrait {
            height: 245px;
        }
    }

    @media (max-width:480px) {
        .graduates-page>main.container {
            padding: 0 16px 55px;
        }

        .graduates-filter-form,
        .graduates-overview,
        .graduates-grid {
            grid-template-columns: 1fr;
        }

        .directory-intro {
            grid-column: auto;
        }

        .graduates-hero-stat {
            gap: 22px;
        }

        .hero-stat strong {
            font-size: 29px;
        }

        .graduate-portrait {
            height: 300px;
        }

        .school-block-heading {
            align-items: flex-start;
            flex-wrap: wrap;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .graduates-page * {
            animation: none !important;
            transition: none !important;
        }

        .graduate-link:hover .graduate-card,
        .graduate-link:focus-visible .graduate-card,
        .graduate-link:hover .graduate-portrait img,
        .graduate-link:focus-visible .graduate-portrait img {
            transform: none;
        }
    }
</style>
@endsection

@section('content')
@php
$visibleGrouped = $graduates
->groupBy(fn($graduate) => $graduate->school?->name ?? 'Unassigned')
->sortKeys();

$namedGrouped = $namedOnly
->groupBy(fn($graduate) => $graduate->school?->name ?? 'Unassigned')
->sortKeys();

$schoolNames = $visibleGrouped->keys()
->merge($namedGrouped->keys())
->unique()
->sort()
->values();

$directoryTotal = $graduates->count() + $namedOnly->count();
$schoolCount = $schoolNames->count();

$visibleUndergraduates = $graduates->where('degree_level', 'undergraduate')->count();
$visibleGraduates = $graduates->where('degree_level', 'graduate')->count();
$namedUndergraduates = $namedOnly->where('degree_level', 'undergraduate')->count();
$namedGraduates = $namedOnly->where('degree_level', 'graduate')->count();
@endphp

<div class="graduates-page">
    <section class="graduates-hero">
        <div class="graduates-hero-inner">
            <div class="graduates-kicker">The graduating community</div>
            <h1>Graduates <em>A record of achievement, ambition, and the people who shaped each class.</em></h1>
            <div class="graduates-hero-bottom">
                <p class="graduates-hero-copy">Explore the LIU Digital Yearbook graduate directory across schools, degrees, campuses, and academic years.</p>
                <div class="graduates-hero-stat">
                    <div class="hero-stat"><strong>{{ $directoryTotal }}</strong><span>Directory entries</span></div>
                    <div class="hero-stat"><strong>{{ $schoolCount }}</strong><span>Schools</span></div>
                </div>
            </div>
        </div>
    </section>

    <main class="container">
        <section class="graduates-discovery" aria-label="Graduate filters">
            <div class="graduates-filter-shell">
                <form method="GET" action="{{ route('public.graduates') }}" class="graduates-filter-form">
                    <div class="graduates-filter-field">
                        <input type="search" name="search" value="{{ $search }}" placeholder="Search graduates…" aria-label="Search graduates">
                    </div>

                    <div class="graduates-filter-field">
                        <select name="year" aria-label="Academic year">
                            <option value="">All academic years</option>
                            @foreach($years as $academicYear)
                            <option value="{{ $academicYear->id }}" @selected((string) $year==(string) $academicYear->id)>
                                {{ $academicYear->title }}{{ $academicYear->status === 'active' ? ' · Current' : '' }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="graduates-filter-field">
                        <select name="degree" aria-label="Degree level">
                            <option value="">All degrees</option>
                            <option value="undergraduate" @selected($degree==='undergraduate' )>Undergraduate</option>
                            <option value="graduate" @selected($degree==='graduate' )>Graduate</option>
                        </select>
                    </div>

                    <div class="graduates-filter-field">
                        <select name="school" aria-label="School">
                            <option value="">All schools</option>
                            @foreach($schools as $item)
                            <option value="{{ $item->id }}" @selected((string) $school===(string) $item->id)>{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="graduates-filter-field">
                        <select name="campus" aria-label="Campus">
                            <option value="">All campuses</option>
                            @foreach($campuses as $item)
                            <option value="{{ $item->id }}" @selected((string) $campus===(string) $item->id)>{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="graduates-filter-field">
                        <select name="sort" aria-label="Sort graduates">
                            <option value="name" @selected($sort==='name' )>Name A–Z</option>
                            <option value="name_desc" @selected($sort==='name_desc' )>Name Z–A</option>
                            <option value="latest" @selected($sort==='latest' )>Newest first</option>
                            <option value="oldest" @selected($sort==='oldest' )>Oldest first</option>
                        </select>
                    </div>

                    <button class="graduates-filter-button" type="submit">Filter</button>
                </form>
            </div>
        </section>

        <section class="graduates-overview" aria-label="Directory overview">
            <div class="directory-intro">
                <small>Digital yearbook directory</small>
                <strong>Every school. Every class. One graduating community.</strong>
            </div>

            <div class="directory-stat">
                <small>Profiles</small>
                <strong>{{ $graduates->count() }}</strong>
                <span>public profiles</span>
            </div>

            <div class="directory-stat">
                <small>Undergraduate</small>
                <strong>{{ $visibleUndergraduates + $namedUndergraduates }}</strong>
                <span>directory entries</span>
            </div>

            <div class="directory-stat">
                <small>Graduate</small>
                <strong>{{ $visibleGraduates + $namedGraduates }}</strong>
                <span>directory entries</span>
            </div>
        </section>

        @if($directoryTotal > 0)
        <section class="school-directory">
            <div class="school-directory-heading">
                <div>
                    <p class="directory-kicker">The directory</p>
                    <h2>Browse by school</h2>
                </div>
                <span class="directory-result-count">{{ $directoryTotal }} {{ \Illuminate\Support\Str::plural('entry', $directoryTotal) }}</span>
            </div>

            @foreach($schoolNames as $schoolName)
            @php
            $schoolVisible = $visibleGrouped->get($schoolName, collect());
            $schoolNamed = $namedGrouped->get($schoolName, collect());
            $schoolTotal = $schoolVisible->count() + $schoolNamed->count();
            $degreeGroups = collect(['undergraduate', 'graduate'])->mapWithKeys(function ($level) use ($schoolVisible, $schoolNamed) {
            return [$level => [
            'visible' => $schoolVisible->where('degree_level', $level)->values(),
            'named' => $schoolNamed->where('degree_level', $level)->values(),
            ]];
            });
            @endphp

            <section class="school-block">
                <div class="school-block-heading">
                    <h3>{{ $schoolName }}</h3>
                    <span class="school-total">{{ $schoolTotal }} {{ Str::plural('graduate', $schoolTotal) }}</span>
                </div>

                @foreach($degreeGroups as $degreeLevel => $group)
                @if($group['visible']->isNotEmpty() || $group['named']->isNotEmpty())
                @php $degreeTotal = $group['visible']->count() + $group['named']->count(); @endphp
                <div class="school-degree-section">
                    <div class="school-degree-label">
                        <strong>{{ $degreeLevel === 'graduate' ? 'Graduate' : 'Undergraduate' }}</strong>
                        <span>{{ $degreeTotal }} {{ Str::plural('entry', $degreeTotal) }}</span>
                    </div>

                    @if($group['visible']->isNotEmpty())
                    <div class="graduates-grid">
                        @foreach($group['visible'] as $graduate)
                        @php
                        $portrait = $graduate->portraitMedia ?? $graduate->media->firstWhere('type', 'image');
                        $graduateYear = $graduate->academicYear?->title ?? $graduate->graduation?->academicYear?->title;
                        @endphp

                        <a class="graduate-link" href="{{ route('public.graduate.detail', ['public_slug' => $graduate->public_slug]) }}">
                            <article class="graduate-card">
                                <div class="graduate-portrait">
                                    @if($portrait)
                                    <img src="{{ $portrait->thumbnailUrl() }}" alt="{{ $graduate->name }}" loading="lazy">
                                    @else
                                    <span class="graduate-placeholder">{{ mb_strtoupper(mb_substr($graduate->name, 0, 1)) }}</span>
                                    @endif

                                    <span class="graduate-badge graduate-degree-badge">{{ $degreeLevel === 'graduate' ? 'Graduate' : 'Undergraduate' }}</span>
                                    @if($graduateYear)
                                    <span class="graduate-badge graduate-year-badge">{{ $graduateYear }}</span>
                                    @endif
                                </div>

                                <div class="graduate-card-body">
                                    <h4 class="graduate-name">{{ $graduate->name }}</h4>
                                    @if($graduate->major)
                                    <p class="graduate-major">{{ $graduate->major->name }}</p>
                                    @endif
                                    <div class="graduate-card-footer">
                                        <span class="graduate-view">View profile →</span>
                                    </div>
                                </div>
                            </article>
                        </a>
                        @endforeach
                    </div>
                    @endif

                    @if($group['named']->isNotEmpty())
                    <div class="name-only-wrap">
                        <p class="name-only-note">Additional graduates listed by name only.</p>
                        <div class="graduate-name-only-list" aria-label="Graduates listed by name only">
                            @foreach($group['named'] as $graduate)
                            <span class="graduate-name-only">{{ $graduate->name }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
                @endif
                @endforeach
            </section>
            @endforeach
        </section>
        @else
        <section class="graduates-empty">
            <h3>No graduates found</h3>
            <p>Try changing your search or filter selections.</p>
            <a class="graduates-reset" href="{{ route('public.graduates') }}">Reset filters</a>
        </section>
        @endif

        @if(method_exists($graduates, 'links'))
        <div class="graduates-pagination">{{ $graduates->links() }}</div>
        @endif
    </main>
</div>
@endsection