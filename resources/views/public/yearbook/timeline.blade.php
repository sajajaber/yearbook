@extends('public.layout')

@section('title', 'Timeline')

@section('extra-css')
<style>
    /* ---------- Header & Filters ---------- */
    .timeline-header-block {
        background: linear-gradient(to bottom, var(--paper) 0%, var(--white) 100%);
        padding: 60px 0 40px;
        border-bottom: 1px solid var(--line);
        margin-bottom: 40px;
        text-align: center;
    }

    .timeline-title {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--ink);
        margin-bottom: 12px;
    }

    .timeline-subtitle {
        color: var(--ink-soft);
        font-size: 1.1rem;
        max-width: 600px;
        margin: 0 auto 30px;
    }

    .filters-section {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        align-items: center;
        gap: 12px;
        max-width: 800px;
        margin: 0 auto;
    }

    .filter-select {
        flex: 1 1 140px;
        max-width: 200px;
        padding: 10px 16px;
        background: var(--white);
        border: 1px solid var(--line);
        border-radius: 8px;
        font-size: 0.95rem;
        color: var(--ink);
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%23888' stroke-width='2' fill='none' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 16px center;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
        transition: all 0.2s ease;
    }

    .filter-select:hover {
        border-color: var(--ink-soft);
    }

    .filter-select:focus {
        outline: none;
        border-color: var(--red);
        box-shadow: 0 0 0 3px rgba(255, 176, 52, 0.15);
    }

    .clear-filters {
        padding: 10px 20px;
        background: var(--ink);
        color: var(--white);
        border-radius: 8px;
        font-size: 0.9rem;
        font-weight: 600;
        text-decoration: none;
        transition: background 0.2s;
    }

    .clear-filters:hover {
        background: var(--red);
    }

    /* ---------- Timeline Structure ---------- */
    .timeline-feed {
        position: relative;
        max-width: 800px;
        margin: 0 auto;
        padding-bottom: 80px;
    }

    /* Left Vertical Line */
    .timeline-feed::before {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        left: 32px;
        width: 3px;
        background: var(--line);
        border-radius: 3px;
    }

    /* Month/Year Divider */
    .timeline-month-divider {
        position: relative;
        display: flex;
        align-items: center;
        margin: 40px 0 30px;
        z-index: 2;
    }

    .timeline-month-badge {
        background: var(--paper);
        border: 1px solid var(--line);
        color: var(--ink);
        font-weight: 700;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        padding: 8px 16px;
        border-radius: 20px;
        margin-left: 10px;
        /* Aligns with the line */
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
    }

    /* ---------- Event Card ---------- */
    .timeline-item {
        position: relative;
        padding-left: 80px;
        /* Space for the line and dot */
        margin-bottom: 32px;
        opacity: 0;
        transform: translateY(20px);
        animation: fadeInUp 0.5s ease forwards;
    }

    /* Staggered animation */
    .timeline-item:nth-child(1) {
        animation-delay: 0.05s;
    }

    .timeline-item:nth-child(2) {
        animation-delay: 0.1s;
    }

    .timeline-item:nth-child(3) {
        animation-delay: 0.15s;
    }

    .timeline-item:nth-child(4) {
        animation-delay: 0.2s;
    }

    /* The Dot */
    .timeline-item::before {
        content: '';
        position: absolute;
        left: 25px;
        /* Center perfectly on the 32px line */
        top: 24px;
        width: 17px;
        height: 17px;
        background: var(--red);
        border: 3px solid var(--white);
        border-radius: 50%;
        box-shadow: 0 0 0 3px var(--line);
        z-index: 2;
        transition: transform 0.3s ease, background 0.3s ease;
    }

    .timeline-item:hover::before {
        transform: scale(1.3);
        background: var(--ink);
    }

    /* The Card Link Wrapper */
    .event-card {
        display: flex;
        flex-direction: row;
        background: var(--white);
        border: 1px solid var(--line);
        border-radius: 16px;
        text-decoration: none;
        color: inherit;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.02);
        transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
    }

    .event-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
        border-color: rgba(255, 176, 52, 0.4);
    }

    /* Card Content */
    .event-card-body {
        padding: 24px 28px;
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .event-meta {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 10px;
    }

    .event-date {
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--red);
    }

    .event-category {
        font-size: 0.75rem;
        font-weight: 600;
        padding: 3px 10px;
        background: var(--paper);
        border-radius: 12px;
        color: var(--ink-soft);
    }

    .event-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--ink);
        margin-bottom: 12px;
        line-height: 1.3;
    }

    .event-desc {
        font-size: 0.95rem;
        color: var(--ink-soft);
        line-height: 1.6;
        margin-bottom: 16px;
    }

    .event-campuses {
        display: flex;
        gap: 8px;
        margin-top: auto;
    }

    .campus-badge {
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--ink);
        border: 1px solid var(--line);
        padding: 4px 10px;
        border-radius: 6px;
    }

    /* Card Image */
    .event-card-media {
        width: 240px;
        flex-shrink: 0;
        border-left: 1px solid var(--line);
        position: relative;
    }

    .event-card-media img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .event-card:hover .event-card-media img {
        transform: scale(1.05);
    }

    /* ---------- Empty State ---------- */
    .timeline-empty {
        text-align: center;
        padding: 60px 20px;
        background: var(--white);
        border: 2px dashed var(--line);
        border-radius: 16px;
        color: var(--ink-soft);
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* ---------- Mobile Adjustments ---------- */
    @media (max-width: 768px) {
        .timeline-feed::before {
            left: 24px;
        }

        .timeline-month-divider {
            margin-left: -8px;
        }

        .timeline-item {
            padding-left: 60px;
        }

        .timeline-item::before {
            left: 17px;
            top: 30px;
        }

        .event-card {
            flex-direction: column-reverse;
            /* Image on top, text below */
        }

        .event-card-media {
            width: 100%;
            height: 180px;
            border-left: none;
            border-bottom: 1px solid var(--line);
        }

        .event-card-media img {
            position: relative;
            /* Reset absolute positioning for mobile */
        }

        .filter-select {
            flex: 1 1 100%;
            max-width: 100%;
        }

        .clear-filters {
            width: 100%;
            text-align: center;
        }
    }
</style>
@endsection

@section('content')

<!-- Unified Header & Filters -->
<div class="timeline-header-block">
    <div class="container">
        <h1 class="timeline-title">Year Timeline</h1>
        <p class="timeline-subtitle">Browse key events chronologically throughout the academic year.</p>

        <form id="timeline-filter-form" method="GET" action="{{ route('public.timeline') }}" class="filters-section">
            <select name="year" class="filter-select" onchange="this.form.submit()">
                <option value="">All Years</option>
                @foreach($years as $year)
                <option value="{{ $year }}" @if($year==request('year')) selected @endif>{{ $year }}</option>
                @endforeach
            </select>

            <select name="category" class="filter-select" onchange="this.form.submit()">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->id }}" @if($cat->id == request('category')) selected @endif>{{ $cat->name }}</option>
                @endforeach
            </select>

            <select name="month" class="filter-select" onchange="this.form.submit()">
                <option value="">All Months</option>
                @for($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" @if($m==request('month')) selected @endif>{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                    @endfor
            </select>

            @if(request('year') || request('category') || request('month'))
            <a href="{{ route('public.timeline') }}" class="clear-filters">Clear Filters</a>
            @endif
        </form>
    </div>
</div>

<!-- Timeline Feed -->
<div class="section">
    <div class="container">
        @if($events->count() > 0)
        <div class="timeline-feed">

            @php
            $currentMonthYear = '';
            @endphp

            @foreach($events as $event)

            {{-- DYNAMIC MONTH GROUPING LOGIC --}}
            @php
            $eventMonthYear = \Carbon\Carbon::parse($event->event_date)->format('F Y');
            @endphp

            @if($currentMonthYear !== $eventMonthYear)
            <div class="timeline-month-divider">
                <span class="timeline-month-badge">{{ $eventMonthYear }}</span>
            </div>
            @php $currentMonthYear = $eventMonthYear; @endphp
            @endif
            {{-- END LOGIC --}}

            <div class="timeline-item">
                <a href="{{ route('public.event.detail', $event->id) }}" class="event-card">

                    <!-- Card Text Content -->
                    <div class="event-card-body">
                        <div class="event-meta">
                            <span class="event-date">{{ \Carbon\Carbon::parse($event->event_date)->format('M d') }}</span>
                            <span class="event-category">{{ $event->category->name }}</span>
                        </div>

                        <h3 class="event-title">{{ $event->title }}</h3>
                        <p class="event-desc">{{ Str::limit($event->description, 140) }}</p>

                        <div class="event-campuses">
                            @foreach($event->campuses as $campus)
                            <span class="campus-badge">{{ $campus->name }}</span>
                            @endforeach
                        </div>
                    </div>

                    <!-- Card Image (Only renders if it exists) -->
                    @php
                    $image = $event->media->first();
                    @endphp

                    @if($image)
                    <div class="event-card-media">
                        <img src="{{ Storage::disk('public')->url($image->path) }}" alt="{{ $event->title }}">
                    </div>
                    @endif

                </a>
            </div>
            @endforeach

        </div>
        @else
        <div class="timeline-empty">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin-bottom: 16px; opacity: 0.5;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h3>No events found</h3>
            <p style="margin-bottom: 20px;">Try adjusting your filters to see more results.</p>
            <a href="{{ route('public.timeline') }}" class="clear-filters">Reset Filters</a>
        </div>
        @endif
    </div>
</div>
@endsection