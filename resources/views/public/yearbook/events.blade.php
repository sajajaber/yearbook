@extends('public.layout')

@section('title', 'Events')

@section('extra-css')
<style>
    .events-page {
        --events-accent: var(--red);
        --events-dark: var(--ink);
        --events-muted: var(--ink-soft);
        --events-line: var(--line);
        --events-paper: var(--paper);
        --events-white: var(--white);
    }

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
    }

    .events-hero-inner {
        position: relative;
        z-index: 1;
        display: grid;
        grid-template-columns: minmax(0, 1.2fr) minmax(260px, .8fr);
        gap: 60px;
        align-items: end;
    }

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
    }

    .events-kicker::before {
        content: '';
        width: 30px;
        height: 2px;
        background: currentColor;
    }

    .events-hero h1 {
        max-width: 850px;
        margin: 0;
        color: var(--events-dark);
        font-size: clamp(3.2rem, 8vw, 7rem);
        line-height: .9;
        letter-spacing: -4px;
        font-weight: 800;
    }

    .events-hero-description {
        max-width: 440px;
        margin: 0 0 5px auto;
        color: var(--events-muted);
        font-size: .98rem;
        line-height: 1.8;
    }

    .events-discovery {
        position: relative;
        z-index: 2;
        margin: 30px 0 70px;
    }

    .events-filter-shell {
        padding: 7px;
        border: 1px solid var(--events-line);
        border-radius: 14px;
        background: var(--events-white);
    }

    .events-filter-form {
        display: grid;
        grid-template-columns: minmax(220px, 1.5fr) repeat(3, minmax(150px, 1fr)) auto;
        gap: 7px;
    }

    .events-filter-field { min-width: 0; }

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
        transition: border-color .2s ease, background .2s ease;
    }

    .events-filter-field input:focus,
    .events-filter-field select:focus {
        border-color: var(--events-accent);
        background: var(--events-white);
    }

    .events-filter-field select { cursor: pointer; }

    .events-filter-button {
        height: 52px;
        min-width: 110px;
        padding: 0 18px;
        border: 0;
        border-radius: 9px;
        background: var(--events-dark);
        color: #fff;
        font-size: .76rem;
        font-weight: 800;
        letter-spacing: .7px;
        text-transform: uppercase;
        cursor: pointer;
        transition: opacity .2s ease, transform .2s ease;
    }

    .events-filter-button:hover {
        opacity: .92;
        transform: translateY(-1px);
    }

    .events-active-filters {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 10px;
        padding: 0 4px 2px;
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
    }

    .events-filter-tag a {
        color: var(--events-muted);
        text-decoration: none;
    }

    .events-filter-tag a:hover { color: var(--events-dark); }

    .events-clear {
        color: var(--events-accent);
        font-size: .68rem;
        font-weight: 800;
        text-decoration: none;
    }

    .events-section-intro {
        display: flex;
        align-items: end;
        justify-content: space-between;
        gap: 25px;
        margin-bottom: 28px;
    }

    .events-section-label { margin-bottom: 7px; }

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

    /* The Events page is intentionally text-first. Media belongs on the event detail page. */
    .events-list {
        border-top: 2px solid var(--events-dark);
    }

    .event-list-link {
        display: block;
        color: inherit;
        text-decoration: none;
    }

    .event-list-item {
        display: grid;
        grid-template-columns: 150px minmax(0, 1fr) 34px;
        gap: 28px;
        align-items: center;
        min-height: 128px;
        padding: 24px 10px;
        border-bottom: 1px solid var(--events-line);
        transition: padding .25s ease, background .25s ease;
    }

    .event-list-link:hover .event-list-item {
        padding-left: 18px;
        padding-right: 2px;
        background: var(--events-paper);
    }

    .event-list-date {
        color: var(--events-accent);
        font-size: .72rem;
        font-weight: 800;
        letter-spacing: 1.2px;
        line-height: 1.5;
        text-transform: uppercase;
    }

    .event-list-main { min-width: 0; }

    .event-list-meta {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 7px;
    }

    .event-category {
        padding: 4px 8px;
        border: 1px solid rgba(255, 176, 52, .3);
        background: rgba(255, 176, 52, .08);
        color: var(--events-accent);
        font-size: .62rem;
        font-weight: 800;
        letter-spacing: .5px;
        text-transform: uppercase;
    }

    .event-list-location {
        color: var(--events-muted);
        font-size: .67rem;
        font-weight: 650;
    }

    .event-list-title {
        margin: 0;
        color: var(--events-dark);
        font-family: 'Merriweather', serif;
        font-size: clamp(1.05rem, 2vw, 1.45rem);
        line-height: 1.3;
        font-weight: 700;
    }

    .event-list-description {
        max-width: 720px;
        margin: 7px 0 0;
        color: var(--events-muted);
        font-size: .8rem;
        line-height: 1.65;
    }

    .event-list-arrow {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 34px;
        height: 34px;
        border: 1px solid var(--events-line);
        color: var(--events-dark);
        font-size: 1rem;
        transition: background .2s ease, color .2s ease, border-color .2s ease;
    }

    .event-list-link:hover .event-list-arrow {
        border-color: var(--events-dark);
        background: var(--events-dark);
        color: #fff;
    }

    .events-pagination {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-wrap: wrap;
        gap: 6px;
        margin: 48px 0 80px;
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
        background: var(--events-white);
        color: var(--events-dark);
        font-size: .72rem;
        font-weight: 800;
        text-decoration: none;
        transition: background .2s ease, color .2s ease, border-color .2s ease;
    }

    .events-pagination a:hover,
    .events-pagination .active {
        border-color: var(--events-dark);
        background: var(--events-dark);
        color: #fff;
    }

    .events-pagination .disabled {
        color: #a7b3c0;
        background: #f8fafc;
        cursor: not-allowed;
    }

    .events-empty {
        padding: 70px 30px;
        border-top: 2px solid var(--events-dark);
        border-bottom: 1px solid var(--events-line);
        text-align: center;
    }

    .events-empty h3 {
        margin: 0 0 8px;
        color: var(--events-dark);
        font-family: 'Merriweather', serif;
    }

    .events-empty p {
        max-width: 500px;
        margin: 0 auto 22px;
        color: var(--events-muted);
        font-size: .85rem;
        line-height: 1.7;
    }

    .events-empty a {
        display: inline-flex;
        padding: 11px 17px;
        background: var(--events-dark);
        color: #fff;
        font-size: .7rem;
        font-weight: 800;
        letter-spacing: .8px;
        text-decoration: none;
        text-transform: uppercase;
    }

    @media (max-width: 1000px) {
        .events-filter-form { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .events-filter-button { width: 100%; }
    }

    @media (max-width: 800px) {
        .events-hero { padding: 58px 0 50px; }
        .events-hero-inner { grid-template-columns: 1fr; gap: 25px; }
        .events-hero-description { margin: 0; }
        .events-filter-form { grid-template-columns: 1fr; }
        .events-section-intro { align-items: flex-start; flex-direction: column; }
        .event-list-item { grid-template-columns: 105px minmax(0, 1fr) 30px; gap: 18px; }
    }

    @media (max-width: 560px) {
        .events-hero h1 { letter-spacing: -2.5px; }
        .events-discovery { margin-bottom: 48px; }
        .event-list-item {
            grid-template-columns: 1fr 30px;
            gap: 10px 15px;
            padding: 22px 5px;
        }
        .event-list-date { grid-column: 1 / -1; }
        .event-list-description { font-size: .76rem; }
        .events-pagination { margin-top: 38px; }
        .events-pagination a, .events-pagination span { min-width: 34px; height: 34px; }
    }

    @media (prefers-reduced-motion: reduce) {
        .event-list-item,
        .event-list-arrow,
        .events-filter-button,
        .events-pagination a { transition: none; }
    }
</style>
@endsection

@section('content')
<div class="events-page">
    <section class="events-hero">
        <div class="container">
            <div class="events-hero-inner">
                <div>
                    <div class="events-kicker">Campus Life</div>
                    <h1>Events<br>Worth Remembering</h1>
                </div>
                <p class="events-hero-description">
                    Explore the celebrations, gatherings, achievements, and experiences
                    that shaped the university year. Select an event to open its full story
                    and media gallery.
                </p>
            </div>
        </div>
    </section>

    <section class="events-discovery">
        <div class="container">
            <div class="events-filter-shell">
                <form method="GET" action="{{ route('public.events') }}" class="events-filter-form">
                    <div class="events-filter-field">
                        <input type="text" name="search" value="{{ $search }}" placeholder="Search events..." aria-label="Search events">
                    </div>

                    <div class="events-filter-field">
                        <select name="category" aria-label="Filter by category">
                            <option value="">All Categories</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" @selected($cat->id == $category)>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="events-filter-field">
                        <select name="campus" aria-label="Filter by campus">
                            <option value="">All Campuses</option>
                            @foreach($campuses as $c)
                                <option value="{{ $c->id }}" @selected($c->id == $campus)>{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="events-filter-field">
                        <select name="sort" aria-label="Sort events">
                            <option value="latest" @selected($sort == 'latest')>Latest First</option>
                            <option value="oldest" @selected($sort == 'oldest')>Oldest First</option>
                            <option value="alphabetical" @selected($sort == 'alphabetical')>Alphabetical</option>
                        </select>
                    </div>

                    <button type="submit" class="events-filter-button">Explore</button>
                </form>

                @if($search || $category || $campus)
                    <div class="events-active-filters">
                        <span class="events-active-label">Filters</span>

                        @if($search)
                            <div class="events-filter-tag">
                                Search: {{ $search }}
                                <a href="{{ route('public.events', array_merge(request()->query(), ['search' => null, 'page' => null])) }}" aria-label="Remove search filter">×</a>
                            </div>
                        @endif

                        @if($category)
                            <div class="events-filter-tag">
                                Category: {{ $categories->find($category)?->name }}
                                <a href="{{ route('public.events', array_merge(request()->query(), ['category' => null, 'page' => null])) }}" aria-label="Remove category filter">×</a>
                            </div>
                        @endif

                        @if($campus)
                            <div class="events-filter-tag">
                                Campus: {{ $campuses->find($campus)?->name }}
                                <a href="{{ route('public.events', array_merge(request()->query(), ['campus' => null, 'page' => null])) }}" aria-label="Remove campus filter">×</a>
                            </div>
                        @endif

                        <a href="{{ route('public.events') }}" class="events-clear">Clear all</a>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            @if($events->count() > 0)
                <div class="events-section-intro">
                    <div>
                        <div class="events-section-label">The Archive</div>
                        <h2>University events</h2>
                    </div>
                    <div class="events-results">
                        Showing {{ $events->firstItem() }}–{{ $events->lastItem() }} of {{ $events->total() }} events
                    </div>
                </div>

                @php($events->appends(request()->query()))

                <div class="events-list">
                    @foreach($events as $event)
                        <a href="{{ route('public.event.detail', $event->id) }}" class="event-list-link" aria-label="View {{ $event->title }}">
                            <article class="event-list-item">
                                <div class="event-list-date">
                                    {{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}
                                </div>

                                <div class="event-list-main">
                                    <div class="event-list-meta">
                                        @if($event->category)
                                            <span class="event-category">{{ $event->category->name }}</span>
                                        @endif
                                        @if($event->location)
                                            <span class="event-list-location">{{ $event->location }}</span>
                                        @endif
                                    </div>

                                    <h3 class="event-list-title">{{ $event->title }}</h3>

                                    @if($event->description)
                                        <p class="event-list-description">{{ Str::limit($event->description, 180) }}</p>
                                    @endif
                                </div>

                                <span class="event-list-arrow" aria-hidden="true">→</span>
                            </article>
                        </a>
                    @endforeach
                </div>

                @if($events->hasPages())
                    <nav class="events-pagination" aria-label="Events pagination">
                        @if($events->onFirstPage())
                            <span class="disabled" aria-disabled="true">←</span>
                        @else
                            <a href="{{ $events->previousPageUrl() }}" aria-label="Previous page">←</a>
                        @endif

                        @foreach($events->getUrlRange(1, $events->lastPage()) as $page => $url)
                            @if($page == $events->currentPage())
                                <span class="active" aria-current="page">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}">{{ $page }}</a>
                            @endif
                        @endforeach

                        @if($events->hasMorePages())
                            <a href="{{ $events->nextPageUrl() }}" aria-label="Next page">→</a>
                        @else
                            <span class="disabled" aria-disabled="true">→</span>
                        @endif
                    </nav>
                @endif
            @else
                <div class="events-empty">
                    <h3>No events found</h3>
                    <p>We couldn't find any published events matching your current search and filters.</p>
                    <a href="{{ route('public.events') }}">Browse all events</a>
                </div>
            @endif
        </div>
    </section>
</div>
@endsection