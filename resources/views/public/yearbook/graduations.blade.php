@extends('public.layout')

@section('title', 'Graduations')

@section('extra-css')
<style>
    .graduations-page {
        --g-accent: var(--red);
        --g-dark: var(--ink);
        --g-muted: var(--ink-soft);
        --g-line: var(--line);
        --g-paper: var(--paper);
        --g-white: var(--white);
        --g-ease: cubic-bezier(.16, 1, .3, 1);
    }

    /* .container / .section have no global CSS anywhere in the app — scoped
       here so this page gets a sane max-width without affecting any other
       page that happens to reuse the same generic class names. */

    .graduations-page .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 24px;
    }

    .graduations-page .section {
        padding: 64px 0;
    }

    /* Hero */

    .graduations-hero {
        position: relative;
        overflow: hidden;
        padding: 76px 0 56px;
        border-bottom: 1px solid var(--g-line);
    }

    .graduations-hero::before {
        content: '';
        position: absolute;
        width: 420px;
        height: 420px;
        top: -280px;
        right: -140px;
        border: 1px solid rgba(0, 42, 92, .08);
        border-radius: 50%;
        pointer-events: none;
    }

    .graduations-kicker {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 16px;
        color: var(--g-accent);
        font-size: .7rem;
        font-weight: 800;
        letter-spacing: 2.5px;
        text-transform: uppercase;
    }

    .graduations-kicker::before {
        content: '';
        width: 30px;
        height: 2px;
        background: currentColor;
    }

    .graduations-hero h1 {
        position: relative;
        z-index: 1;
        max-width: 720px;
        margin: 0;
        color: var(--g-dark);
        font-family: "Merriweather", serif;
        font-size: clamp(2.2rem, 4.4vw, 3.4rem);
        line-height: 1.08;
        letter-spacing: -.5px;
    }

    .graduations-hero p {
        position: relative;
        z-index: 1;
        max-width: 520px;
        margin: 16px 0 0;
        color: var(--g-muted);
        font-size: 1.02rem;
        line-height: 1.6;
    }

    /* Filter bar */

    .graduations-filters {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 10px;
        margin: -28px 0 40px;
        padding: 16px;
        border: 1px solid var(--g-line);
        border-radius: 18px;
        background: var(--g-white);
        box-shadow: 0 18px 40px -30px rgba(0, 42, 92, .35);
    }

    .graduations-filters form {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 10px;
        width: 100%;
    }

    .filter-select {
        appearance: none;
        min-width: 170px;
        flex: 1 1 170px;
        padding: 11px 36px 11px 16px;
        border: 1px solid var(--g-line);
        border-radius: 999px;
        background: var(--g-paper) url('data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 24 24%22 fill=%22none%22 stroke=%22%2364748b%22 stroke-width=%222%22 stroke-linecap=%22round%22%3E%3Cpath d=%22m6 9 6 6 6-6%22/%3E%3C/svg%3E') no-repeat right 14px center;
        background-size: 12px;
        color: var(--g-dark);
        font: 600 .85rem "Inter", sans-serif;
        cursor: pointer;
        transition: border-color .18s ease, box-shadow .18s ease;
    }

    .filter-select:hover {
        border-color: #b9c9dc;
    }

    .filter-select:focus {
        outline: none;
        border-color: var(--g-accent);
        box-shadow: 0 0 0 3px rgba(255, 176, 52, .18);
    }

    .graduations-clear {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 11px 18px;
        border: 1px solid var(--g-line);
        border-radius: 999px;
        background: var(--g-paper);
        color: var(--g-dark);
        font: 700 .78rem "Inter", sans-serif;
        text-decoration: none;
        white-space: nowrap;
        transition: background .18s ease, border-color .18s ease;
    }

    .graduations-clear:hover {
        background: var(--g-dark);
        border-color: var(--g-dark);
        color: var(--g-white);
    }

    /* Card grid */

    .graduations-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
        gap: 28px;
    }

    .graduation-card-link {
        display: block;
        color: inherit;
        text-decoration: none;
    }

    .graduation-card {
        overflow: hidden;
        height: 100%;
        border: 1px solid var(--g-line);
        border-radius: 20px;
        background: var(--g-white);
        box-shadow: 0 1px 2px rgba(0, 42, 92, .04);
        transition: transform .4s var(--g-ease), box-shadow .4s var(--g-ease), border-color .4s ease;
    }

    .graduation-card-link:hover .graduation-card {
        transform: translateY(-4px);
        border-color: #c9d8e8;
        box-shadow: 0 28px 48px -28px rgba(0, 42, 92, .32);
    }

    .graduation-card-image {
        position: relative;
        aspect-ratio: 16 / 10;
        overflow: hidden;
        background: linear-gradient(135deg, var(--g-dark) 0%, #1a3f7f 100%);
    }

    .graduation-card-image img {
        width: 100%;
        height: 100%;
        display: block;
        object-fit: cover;
        transition: transform .6s var(--g-ease);
    }

    .graduation-card-link:hover .graduation-card-image img {
        transform: scale(1.04);
    }

    .graduation-card-date {
        position: absolute;
        top: 14px;
        left: 14px;
        padding: 6px 12px;
        border-radius: 999px;
        background: rgba(255, 255, 255, .92);
        backdrop-filter: blur(4px);
        color: var(--g-dark);
        font: 800 .68rem "Inter", sans-serif;
        letter-spacing: .3px;
    }

    .graduation-card-body {
        padding: 22px 24px 26px;
    }

    .graduation-card-title {
        margin: 0 0 8px;
        color: var(--g-dark);
        font-family: "Merriweather", serif;
        font-size: 1.2rem;
        line-height: 1.3;
    }

    .graduation-card-venue {
        display: flex;
        align-items: center;
        gap: 6px;
        margin: 0 0 12px;
        color: var(--g-accent);
        font-size: .74rem;
        font-weight: 700;
        letter-spacing: .3px;
        text-transform: uppercase;
    }

    .graduation-card-desc {
        margin: 0;
        color: var(--g-muted);
        font-size: .92rem;
        line-height: 1.65;
    }

    /* Empty state */

    .graduations-empty {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 10px;
        padding: 70px 24px;
        border: 1px dashed var(--g-line);
        border-radius: 24px;
        background: linear-gradient(180deg, var(--g-white) 0%, var(--g-paper) 100%);
        text-align: center;
    }

    .graduations-empty svg {
        margin-bottom: 6px;
        color: var(--g-accent);
    }

    .graduations-empty h3 {
        margin: 0;
        color: var(--g-dark);
        font-size: 1.2rem;
        font-weight: 800;
    }

    .graduations-empty p {
        max-width: 380px;
        margin: 0;
        color: var(--g-muted);
        line-height: 1.6;
    }

    @media (max-width: 640px) {
        .graduations-hero {
            padding: 56px 0 36px;
        }

        .graduations-filters {
            margin-top: -18px;
        }

        .graduations-filters form {
            flex-direction: column;
            align-items: stretch;
        }

        .filter-select {
            width: 100%;
        }

        .graduations-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<div class="graduations-page">
    <!-- Hero -->
    <div class="graduations-hero">
        <div class="container">
            <span class="graduations-kicker">Yearbook archive</span>
            <h1>Graduations</h1>
            <p>Celebrate the graduation ceremonies that brought each class across the stage.</p>
        </div>
    </div>

    <div class="section">
        <div class="container">
            <!-- Filters -->
            <div class="graduations-filters">
                <form method="GET" action="{{ route('public.graduations') }}">
                    <select name="year" class="filter-select" onchange="this.form.submit()">
                        <option value="">All Years</option>
                        @foreach($years as $year)
                        <option value="{{ $year }}" @if($year==request('year')) selected @endif>{{ $year }}</option>
                        @endforeach
                    </select>
                    <select name="campus" class="filter-select" onchange="this.form.submit()">
                        <option value="">All Campuses</option>
                        @foreach($campuses as $campus)
                        <option value="{{ $campus->id }}" @if($campus->id == request('campus')) selected @endif>{{ $campus->name }}</option>
                        @endforeach
                    </select>
                    @if(request('year') || request('campus'))
                    <a href="{{ route('public.graduations') }}" class="graduations-clear">Clear filters</a>
                    @endif
                </form>
            </div>

            @if($graduations->count() > 0)
            <div class="graduations-grid">
                @foreach($graduations as $graduation)
                <a href="{{ route('public.graduation.detail', $graduation->id) }}" class="graduation-card-link">
                    <div class="graduation-card">
                        @php
                        $image = $graduation->media->first();
                        @endphp
                        <div class="graduation-card-image">
                            @if($image)
                            <img src="{{ Storage::disk('public')->url($image->path) }}" alt="Graduation">
                            @endif
                            <span class="graduation-card-date">{{ \Carbon\Carbon::parse($graduation->ceremony_date)->format('M d, Y') }}</span>
                        </div>
                        <div class="graduation-card-body">
                            <h3 class="graduation-card-title">Graduation Ceremony</h3>
                            <p class="graduation-card-venue">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 21s7-7.58 7-12A7 7 0 0 0 5 9c0 4.42 7 12 7 12Z" />
                                    <circle cx="12" cy="9" r="2.4" />
                                </svg>
                                {{ $graduation->venue }}
                            </p>
                            @if($graduation->description)
                            <p class="graduation-card-desc">{{ Str::limit($graduation->description, 110) }}</p>
                            @endif
                        </div>
                    </div>
                </a>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($graduations->hasPages())
            <div class="pagination">
                @if($graduations->onFirstPage())
                <span>←</span>
                @else
                <a href="{{ $graduations->previousPageUrl() }}">←</a>
                @endif

                @foreach($graduations->getUrlRange(1, $graduations->lastPage()) as $page => $url)
                @if($page == $graduations->currentPage())
                <span class="active">{{ $page }}</span>
                @else
                <a href="{{ $url }}">{{ $page }}</a>
                @endif
                @endforeach

                @if($graduations->hasMorePages())
                <a href="{{ $graduations->nextPageUrl() }}">→</a>
                @else
                <span>→</span>
                @endif
            </div>
            @endif
            @else
            <div class="graduations-empty">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                    <path d="M22 10 12 5 2 10l10 5 10-5Z" />
                    <path d="M6 12v5c0 1.5 2.7 3 6 3s6-1.5 6-3v-5" />
                </svg>
                <h3>No graduations found</h3>
                <p>Try a different year or campus, or check back once new ceremonies are added.</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection