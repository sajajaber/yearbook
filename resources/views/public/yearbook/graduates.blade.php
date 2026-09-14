@extends('public.layout')

@section('title', 'Graduates')

@section('extra-css')
<style>
    .graduates-page {
        --g-ink: var(--ink, #002a5c);
        --g-blue: #073972;
        --g-gold: var(--red, #ffb034);
        --g-paper: var(--paper, #f7fbff);
        --g-line: var(--line, #d8e3ef);
        --g-muted: var(--ink-soft, #64748b);
        --g-white: #fff;
        --g-ease: cubic-bezier(.16,1,.3,1);
    }

    .graduates-hero {
        position: relative;
        overflow: hidden;
        padding: 76px 0 58px;
        background:
            radial-gradient(circle at 88% 15%, rgba(255,176,52,.18), transparent 25%),
            linear-gradient(135deg, #f7fbff 0%, #fff 58%, #eef5fb 100%);
    }

    .graduates-hero::before {
        content: 'YEARBOOK';
        position: absolute;
        right: -22px;
        bottom: -34px;
        color: rgba(0,42,92,.035);
        font-size: clamp(5rem,13vw,12rem);
        font-weight: 900;
        letter-spacing: -.08em;
        pointer-events: none;
    }

    .graduates-hero-inner {
        position: relative;
        z-index: 1;
        display: grid;
        grid-template-columns: minmax(0,1.15fr) minmax(280px,.7fr);
        gap: 60px;
        align-items: end;
    }

    .graduates-kicker {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 18px;
        color: var(--g-gold);
        font-size: .7rem;
        font-weight: 900;
        letter-spacing: 2.6px;
        text-transform: uppercase;
    }

    .graduates-kicker::before {
        content: '';
        width: 34px;
        height: 2px;
        background: currentColor;
    }

    .graduates-hero h1 {
        max-width: 850px;
        margin: 0;
        color: var(--g-ink);
        font-size: clamp(3.5rem,7.5vw,7rem);
        line-height: .86;
        letter-spacing: -4px;
        font-weight: 900;
    }

    .graduates-hero-copy {
        margin: 0 0 5px auto;
        max-width: 430px;
        color: var(--g-muted);
        font-size: 1rem;
        line-height: 1.8;
    }

    .graduates-discovery {
        position: relative;
        z-index: 3;
        margin-top: -22px;
        margin-bottom: 62px;
    }

    .graduates-degree-switcher {
        display: grid;
        grid-template-columns: repeat(3,1fr);
        gap: 8px;
        padding: 8px;
        margin-bottom: 10px;
        border: 1px solid rgba(0,42,92,.1);
        border-radius: 18px;
        background: rgba(255,255,255,.94);
        box-shadow: 0 18px 50px rgba(0,42,92,.09);
        backdrop-filter: blur(14px);
    }

    .graduates-degree-switcher a {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 54px;
        padding: 10px 16px;
        border-radius: 12px;
        color: var(--g-ink);
        text-decoration: none;
        font-size: .78rem;
        font-weight: 850;
        transition: all .35s var(--g-ease);
    }

    .graduates-degree-switcher a:hover {
        background: var(--g-paper);
        transform: translateY(-2px);
    }

    .graduates-degree-switcher a.is-active {
        background: var(--g-ink);
        color: #fff;
        box-shadow: 0 9px 22px rgba(0,42,92,.18);
    }

    .graduates-degree-switcher a.is-active::after {
        content: '';
        width: 6px;
        height: 6px;
        margin-left: 8px;
        border-radius: 50%;
        background: var(--g-gold);
    }

    .graduates-filter-shell {
        padding: 8px;
        border: 1px solid var(--g-line);
        border-radius: 18px;
        background: #fff;
        box-shadow: 0 14px 38px rgba(0,42,92,.065);
    }

    .graduates-filter-form {
        display: grid;
        grid-template-columns: minmax(210px,1.5fr) repeat(5,minmax(120px,1fr)) auto;
        gap: 7px;
    }

    .graduates-filter-field { min-width: 0; position: relative; }

    .graduates-filter-field input,
    .graduates-filter-field select {
        box-sizing: border-box;
        width: 100%;
        height: 56px;
        padding: 0 14px;
        border: 1px solid transparent;
        border-radius: 11px;
        background: var(--g-paper);
        color: var(--g-ink);
        font-size: .78rem;
        font-weight: 700;
        transition: all .25s ease;
    }

    .graduates-filter-field input { padding-left: 42px; }

    .graduates-filter-field select {
        appearance: none;
        padding-right: 34px;
        background-image: linear-gradient(45deg,transparent 50%,#64748b 50%),linear-gradient(135deg,#64748b 50%,transparent 50%);
        background-position: calc(100% - 16px) 24px,calc(100% - 11px) 24px;
        background-size: 5px 5px,5px 5px;
        background-repeat: no-repeat;
    }

    .graduates-filter-field input:hover,
    .graduates-filter-field select:hover,
    .graduates-filter-field input:focus,
    .graduates-filter-field select:focus {
        outline: none;
        background: #fff;
        border-color: rgba(0,42,92,.14);
        box-shadow: 0 0 0 3px rgba(255,176,52,.1);
    }

    .graduates-search-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        width: 17px;
        height: 17px;
        color: var(--g-muted);
        transform: translateY(-50%);
        pointer-events: none;
    }

    .graduates-filter-button {
        min-width: 104px;
        height: 56px;
        border: 0;
        border-radius: 11px;
        background: var(--g-ink);
        color: #fff;
        font-size: .78rem;
        font-weight: 850;
        cursor: pointer;
        transition: all .35s var(--g-ease);
    }

    .graduates-filter-button:hover {
        background: var(--g-gold);
        color: var(--g-ink);
        transform: translateY(-2px);
        box-shadow: 0 10px 24px rgba(0,42,92,.15);
    }

    .graduates-active-filters {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        align-items: center;
        margin-top: 11px;
        padding: 0 4px;
    }

    .graduates-active-label {
        color: var(--g-muted);
        font-size: .65rem;
        font-weight: 900;
        letter-spacing: 1px;
        text-transform: uppercase;
    }

    .graduates-filter-tag {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 6px 10px;
        border: 1px solid var(--g-line);
        border-radius: 999px;
        background: var(--g-paper);
        color: var(--g-ink);
        font-size: .68rem;
        font-weight: 750;
    }

    .graduates-filter-tag a {
        color: var(--g-muted);
        text-decoration: none;
        font-size: 1rem;
        line-height: 1;
    }

    .graduates-clear {
        color: var(--g-gold);
        font-size: .68rem;
        font-weight: 850;
        text-decoration: none;
    }

    .graduates-archive-header {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 30px;
    }

    .graduates-archive-kicker {
        margin-bottom: 7px;
        color: var(--g-gold);
        font-size: .67rem;
        font-weight: 900;
        letter-spacing: 2px;
        text-transform: uppercase;
    }

    .graduates-archive-header h2 {
        margin: 0;
        color: var(--g-ink);
        font-size: clamp(1.8rem,3vw,2.7rem);
        line-height: 1;
        letter-spacing: -1.2px;
    }

    .graduates-count { color: var(--g-muted); font-size: .75rem; font-weight: 700; }

    .graduates-year-group { margin-bottom: 56px; }

    .graduates-year-heading {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 20px;
    }

    .graduates-year-number {
        padding: 7px 11px;
        border-radius: 8px;
        background: var(--g-ink);
        color: #fff;
        font-size: .72rem;
        font-weight: 850;
        letter-spacing: .5px;
    }

    .graduates-year-line { flex: 1; height: 1px; background: var(--g-line); }

    .graduates-grid {
        display: grid;
        grid-template-columns: repeat(4,minmax(0,1fr));
        gap: 22px;
    }

    .graduate-link {
        display: block;
        color: inherit;
        text-decoration: none;
    }

    .graduate-card {
        height: 100%;
        overflow: hidden;
        border: 1px solid var(--g-line);
        border-radius: 18px;
        background: #fff;
        transition: transform .45s var(--g-ease),box-shadow .45s ease,border-color .3s ease;
    }

    .graduate-link:hover .graduate-card {
        transform: translateY(-7px);
        border-color: rgba(255,176,52,.5);
        box-shadow: 0 22px 48px rgba(0,42,92,.12);
    }

    .graduate-portrait {
        position: relative;
        height: 315px;
        overflow: hidden;
        background: linear-gradient(145deg,var(--g-ink),var(--g-blue));
    }

    .graduate-portrait img {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform .7s var(--g-ease);
    }

    .graduate-link:hover .graduate-portrait img { transform: scale(1.045); }

    .graduate-portrait::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(to top,rgba(0,0,0,.28),transparent 45%);
        pointer-events: none;
    }

    .graduate-placeholder {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
        color: #fff;
        font-size: 4rem;
        font-weight: 900;
    }

    .graduate-year-badge,
    .graduate-degree-badge {
        position: absolute;
        z-index: 1;
        top: 13px;
        padding: 6px 9px;
        border-radius: 999px;
        font-size: .61rem;
        font-weight: 900;
        box-shadow: 0 5px 14px rgba(0,0,0,.12);
    }

    .graduate-year-badge {
        right: 13px;
        max-width: 62%;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        background: #fff;
        color: var(--g-ink);
    }

    .graduate-degree-badge {
        left: 13px;
        background: var(--g-gold);
        color: var(--g-ink);
    }

    .graduate-card-body { padding: 19px 20px 20px; }

    .graduate-name {
        margin: 0 0 8px;
        color: var(--g-ink);
        font-size: 1.04rem;
        line-height: 1.2;
        font-weight: 850;
    }

    .graduate-major {
        margin: 0 0 4px;
        color: var(--g-ink);
        font-size: .76rem;
        font-weight: 700;
        line-height: 1.4;
    }

    .graduate-school {
        margin: 0;
        color: var(--g-muted);
        font-size: .68rem;
        line-height: 1.5;
    }

    .graduate-card-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        margin-top: 16px;
        padding-top: 12px;
        border-top: 1px solid var(--g-line);
    }

    .graduate-view {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        color: var(--g-ink);
        font-size: .66rem;
        font-weight: 900;
        transition: gap .3s var(--g-ease),color .25s ease;
    }

    .graduate-link:hover .graduate-view { gap: 9px; color: var(--g-gold); }

    .graduates-empty {
        max-width: 650px;
        margin: 10px auto 80px;
        padding: 70px 30px;
        text-align: center;
        border: 1px dashed var(--g-line);
        border-radius: 20px;
        background: #fff;
    }

    .graduates-empty h3 { margin: 0 0 8px; color: var(--g-ink); font-size: 1.35rem; }
    .graduates-empty p { margin: 0 auto 22px; max-width: 460px; color: var(--g-muted); line-height: 1.7; font-size: .9rem; }

    .graduates-pagination {
        display: flex;
        justify-content: center;
        gap: 7px;
        margin: 48px 0 75px;
    }

    .graduates-pagination a,.graduates-pagination span {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 40px;
        height: 40px;
        padding: 0 10px;
        border: 1px solid var(--g-line);
        border-radius: 9px;
        background: #fff;
        color: var(--g-ink);
        text-decoration: none;
        font-size: .74rem;
        font-weight: 800;
    }

    .graduates-pagination .active { background: var(--g-ink); color: #fff; border-color: var(--g-ink); }
    .graduates-pagination .disabled { opacity: .4; }

    .graduates-named-only {
        margin-top: 40px;
        padding: 26px 0;
        border-top: 1px dashed var(--g-line);
        color: var(--g-muted);
        font-size: .85rem;
        line-height: 2;
    }

    .graduates-named-only strong { display:block; margin-bottom: 5px; color: var(--g-ink); }

    @media (max-width: 1150px) {
        .graduates-filter-form { grid-template-columns: repeat(3,minmax(0,1fr)); }
        .graduates-filter-button { width:100%; }
        .graduates-grid { grid-template-columns: repeat(3,minmax(0,1fr)); }
    }

    @media (max-width: 900px) {
        .graduates-hero { padding: 58px 0 48px; }
        .graduates-hero-inner { grid-template-columns:1fr; gap:22px; }
        .graduates-hero-copy { margin:0; }
        .graduates-grid { grid-template-columns:repeat(2,minmax(0,1fr)); }
    }

    @media (max-width: 650px) {
        .graduates-hero h1 { font-size: clamp(3rem,15vw,5rem); letter-spacing:-2.5px; }
        .graduates-degree-switcher { grid-template-columns:1fr; }
        .graduates-filter-form { grid-template-columns:1fr; }
        .graduates-archive-header { align-items:flex-start; flex-direction:column; gap:8px; }
        .graduates-grid { grid-template-columns:1fr; gap:16px; }
        .graduate-portrait { height:340px; }
    }

    @media (max-width:420px) { .graduate-portrait { height:290px; } }

    @media (prefers-reduced-motion:reduce) {
        *,*::before,*::after { animation-duration:.01ms!important; transition-duration:.01ms!important; }
    }
</style>
@endsection

@section('content')
<div class="graduates-page">
    <section class="graduates-hero">
        <div class="container">
            <div class="graduates-hero-inner">
                <div>
                    <div class="graduates-kicker">The Yearbook Directory</div>
                    <h1>Faces of<br>Our Year</h1>
                </div>
                <p class="graduates-hero-copy">
                    Explore the people who shaped the yearbook — from undergraduate journeys to graduate achievements, organized by academic year.
                </p>
            </div>
        </div>
    </section>

    <section class="graduates-discovery">
        <div class="container">
            <nav class="graduates-degree-switcher" aria-label="Graduate level">
                <a href="{{ route('public.graduates', request()->except('degree','page')) }}" class="{{ !$degree ? 'is-active' : '' }}">All Students</a>
                <a href="{{ route('public.graduates', array_merge(request()->except('degree','page'), ['degree'=>'undergraduate'])) }}" class="{{ $degree === 'undergraduate' ? 'is-active' : '' }}">Undergraduates</a>
                <a href="{{ route('public.graduates', array_merge(request()->except('degree','page'), ['degree'=>'graduate'])) }}" class="{{ $degree === 'graduate' ? 'is-active' : '' }}">Graduate Students</a>
            </nav>

            <div class="graduates-filter-shell">
                <form method="GET" action="{{ route('public.graduates') }}" class="graduates-filter-form">
                    <div class="graduates-filter-field">
                        <svg class="graduates-search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg>
                        <input type="text" name="search" value="{{ $search }}" placeholder="Search a name..." aria-label="Search graduates">
                    </div>

                    <div class="graduates-filter-field">
                        <select name="degree" aria-label="Filter by degree level">
                            <option value="">All Degree Levels</option>
                            <option value="undergraduate" @selected($degree === 'undergraduate')>Undergraduate</option>
                            <option value="graduate" @selected($degree === 'graduate')>Graduate</option>
                        </select>
                    </div>

                    <div class="graduates-filter-field">
                        <select name="year" aria-label="Filter by academic year">
                            <option value="">All Academic Years</option>
                            @foreach($years as $academicYear)
                                <option value="{{ $academicYear->id }}" @selected((string)$academicYear->id === (string)$year)>{{ $academicYear->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="graduates-filter-field">
                        <select name="school" aria-label="Filter by school">
                            <option value="">All Schools</option>
                            @foreach($schools as $s)
                                <option value="{{ $s->id }}" @selected($s->id == $school)>{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="graduates-filter-field">
                        <select name="campus" aria-label="Filter by campus">
                            <option value="">All Campuses</option>
                            @foreach($campuses as $c)
                                <option value="{{ $c->id }}" @selected($c->id == $campus)>{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="graduates-filter-field">
                        <select name="sort" aria-label="Sort graduates">
                            <option value="name" @selected($sort === 'name')>Name A–Z</option>
                            <option value="latest" @selected($sort === 'latest')>Latest</option>
                            <option value="oldest" @selected($sort === 'oldest')>Oldest</option>
                        </select>
                    </div>

                    <button type="submit" class="graduates-filter-button">Explore</button>
                </form>

                @if($search || $school || $campus || $year || $degree)
                    <div class="graduates-active-filters">
                        <span class="graduates-active-label">Active</span>
                        @if($degree)<span class="graduates-filter-tag">Degree: {{ $degree === 'undergraduate' ? 'Undergraduate' : 'Graduate' }} <a href="{{ route('public.graduates', array_merge(request()->query(), ['degree'=>null])) }}" aria-label="Remove degree filter">×</a></span>@endif
                        @if($year)<span class="graduates-filter-tag">Academic Year: {{ $years->firstWhere('id',$year)?->title ?? $year }} <a href="{{ route('public.graduates', array_merge(request()->query(), ['year'=>null])) }}" aria-label="Remove year filter">×</a></span>@endif
                        @if($school)<span class="graduates-filter-tag">School: {{ $schools->find($school)?->name }} <a href="{{ route('public.graduates', array_merge(request()->query(), ['school'=>null])) }}" aria-label="Remove school filter">×</a></span>@endif
                        @if($campus)<span class="graduates-filter-tag">Campus: {{ $campuses->find($campus)?->name }} <a href="{{ route('public.graduates', array_merge(request()->query(), ['campus'=>null])) }}" aria-label="Remove campus filter">×</a></span>@endif
                        @if($search)<span class="graduates-filter-tag">Name: {{ $search }} <a href="{{ route('public.graduates', array_merge(request()->query(), ['search'=>null])) }}" aria-label="Remove search filter">×</a></span>@endif
                        <a class="graduates-clear" href="{{ route('public.graduates') }}">Clear all</a>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            @if($graduates->count() > 0)
                <div class="graduates-archive-header">
                    <div>
                        <div class="graduates-archive-kicker">Academic Year Archive</div>
                        <h2>{{ $degree === 'undergraduate' ? 'Undergraduate students' : ($degree === 'graduate' ? 'Graduate students' : 'The graduating community') }}</h2>
                    </div>
                    <div class="graduates-count">Showing {{ $graduates->firstItem() }}–{{ $graduates->lastItem() }} of {{ $graduates->total() }}</div>
                </div>

                @php
                    $groupedGraduates = $graduates->getCollection()->groupBy(function ($graduate) {
                        return $graduate->academicYear?->title ?? $graduate->graduation?->academicYear?->title ?? 'Academic Year';
                    });
                @endphp

                @foreach($groupedGraduates as $groupYear => $groupGraduates)
                    <section class="graduates-year-group">
                        <div class="graduates-year-heading">
                            <span class="graduates-year-number">{{ $groupYear }}</span>
                            <span class="graduates-year-line"></span>
                        </div>

                        <div class="graduates-grid">
                            @foreach($groupGraduates as $graduate)
                                @php
                                    $portrait = $graduate->portraitMedia ?? $graduate->media->first();
                                    $academicYearTitle = $graduate->academicYear?->title ?? $graduate->graduation?->academicYear?->title;
                                    $degreeLabel = $graduate->degree_level === 'undergraduate' ? 'Undergraduate' : 'Graduate';
                                @endphp

                                <a href="{{ route('public.graduate.detail', $graduate->id) }}" class="graduate-link" aria-label="View {{ $graduate->name }}'s profile">
                                    <article class="graduate-card">
                                        <div class="graduate-portrait">
                                            @if($portrait)
                                                <img src="{{ $portrait->thumbnailUrl() }}" alt="{{ $graduate->name }}" loading="lazy">
                                            @else
                                                <div class="graduate-placeholder" aria-hidden="true">{{ strtoupper(mb_substr($graduate->name,0,1)) }}</div>
                                            @endif
                                            <span class="graduate-degree-badge">{{ $degreeLabel }}</span>
                                            @if($academicYearTitle)<span class="graduate-year-badge">{{ $academicYearTitle }}</span>@endif
                                        </div>
                                        <div class="graduate-card-body">
                                            <h3 class="graduate-name">{{ $graduate->name }}</h3>
                                            <p class="graduate-major">{{ $graduate->major?->name ?? 'Major not specified' }}</p>
                                            <p class="graduate-school">{{ $graduate->school?->name ?? 'School not specified' }}</p>
                                            <div class="graduate-card-footer">
                                                <span class="graduate-school">{{ $degreeLabel }}</span>
                                                <span class="graduate-view">Profile <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14"/><path d="m13 6 6 6-6 6"/></svg></span>
                                            </div>
                                        </div>
                                    </article>
                                </a>
                            @endforeach
                        </div>
                    </section>
                @endforeach

                @if($graduates->hasPages())
                    <nav class="graduates-pagination" aria-label="Graduate directory pagination">
                        @if($graduates->onFirstPage())<span class="disabled">←</span>@else<a href="{{ $graduates->previousPageUrl() }}">←</a>@endif
                        @foreach($graduates->getUrlRange(1,$graduates->lastPage()) as $page => $url)
                            @if($page == $graduates->currentPage())<span class="active" aria-current="page">{{ $page }}</span>@else<a href="{{ $url }}">{{ $page }}</a>@endif
                        @endforeach
                        @if($graduates->hasMorePages())<a href="{{ $graduates->nextPageUrl() }}">→</a>@else<span class="disabled">→</span>@endif
                    </nav>
                @endif
            @else
                <div class="graduates-empty">
                    <h3>No students found</h3>
                    <p>No students match the selected academic year, degree level, school, campus, or search. Try clearing a filter and explore the directory again.</p>
                    <a href="{{ route('public.graduates') }}" class="btn btn-primary">Browse all students</a>
                </div>
            @endif

            @if($namedOnly->isNotEmpty())
                <div class="graduates-named-only">
                    <strong>Also in the yearbook</strong>
                    {{ $namedOnly->implode(' · ') }}
                </div>
            @endif
        </div>
    </section>
</div>
@endsection
