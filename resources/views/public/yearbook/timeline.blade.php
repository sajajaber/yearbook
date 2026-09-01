@extends('public.layout')

@section('title', 'Timeline')

@section('extra-css')
<style>
    .timeline-container {
        position: relative;
        padding: 40px 0;
    }

    .timeline-vertical {
        position: relative;
    }

    .timeline-vertical::before {
        content: '';
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
        width: 2px;
        height: 100%;
        background: linear-gradient(180deg, var(--red) 0%, transparent 100%);
    }

    .timeline-entry {
        margin-bottom: 50px;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 40px;
        align-items: center;
        position: relative;
        animation: fadeInUp 0.6s ease-out;
    }

    .timeline-entry:nth-child(odd) .timeline-dot {
        left: 50%;
        margin-left: -8px;
    }

    .timeline-entry:nth-child(even) {
        grid-template-columns: 1fr 1fr;
    }

    .timeline-entry:nth-child(even) .timeline-content {
        order: -1;
    }

    .timeline-dot {
        position: absolute;
        top: 0;
        width: 16px;
        height: 16px;
        background: var(--red);
        border: 3px solid white;
        border-radius: 50%;
        box-shadow: 0 0 0 3px var(--red);
        left: -8px;
        z-index: 10;
    }

    .timeline-content {
        position: relative;
    }

    .timeline-date {
        font-size: 0.9rem;
        color: var(--red);
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 10px;
    }

    .timeline-title {
        font-size: 1.4rem;
        font-weight: 700;
        margin-bottom: 15px;
        color: var(--ink);
    }

    .timeline-description {
        color: var(--ink-soft);
        line-height: 1.7;
        margin-bottom: 15px;
    }

    .timeline-image {
        border-radius: 12px;
        overflow: hidden;
        height: 250px;
        background: var(--paper);
    }

    .timeline-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }

    .timeline-image:hover img {
        transform: scale(1.05);
    }

    .filters-section {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-bottom: 40px;
    }

    .filter-select {
        padding: 10px 15px;
        border: 1px solid var(--line);
        border-radius: 6px;
        font-size: 0.95rem;
        background: var(--white);
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .filter-select:focus {
        outline: none;
        border-color: var(--red);
        box-shadow: 0 0 0 3px rgba(255, 176, 52, 0.1);
    }

    .clear-filters {
        display: inline-block;
        padding: 10px 15px;
        background: var(--paper);
        border: 1px solid var(--line);
        border-radius: 6px;
        cursor: pointer;
        text-decoration: none;
        color: var(--ink);
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }

    .clear-filters:hover {
        background: var(--white);
        border-color: var(--ink);
    }

    .event-badge {
        display: inline-block;
        padding: 4px 12px;
        background: var(--paper);
        color: var(--ink);
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        margin-right: 10px;
    }

    @media (max-width: 768px) {
        .timeline-vertical::before {
            left: 20px;
        }

        .timeline-entry {
            grid-template-columns: 1fr;
            gap: 20px;
            margin-left: 50px;
        }

        .timeline-entry:nth-child(even) {
            grid-template-columns: 1fr;
        }

        .timeline-entry:nth-child(even) .timeline-content {
            order: auto;
        }

        .timeline-dot {
            left: 12px;
            margin-left: 0;
        }

        .timeline-image {
            height: 200px;
        }

        .timeline-title {
            font-size: 1.2rem;
        }

        .filters-section {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<!-- Hero -->
<div class="hero">
    <div class="container">
        <h1>Year Timeline</h1>
        <p>Browse events chronologically throughout the academic year</p>
    </div>
</div>

<!-- Filters -->
<div class="section">
    <div class="container">
        <div class="filters-section">
            <select class="filter-select" onchange="filterTimeline()">
                <option value="">All Years</option>
                @foreach($years as $year)
                <option value="{{ $year }}" @if($year == request('year')) selected @endif>{{ $year }}</option>
                @endforeach
            </select>

            <select class="filter-select" onchange="filterTimeline()">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->id }}" @if($cat->id == request('category')) selected @endif>{{ $cat->name }}</option>
                @endforeach
            </select>

            <select class="filter-select" onchange="filterTimeline()">
                <option value="">All Months</option>
                @for($m = 1; $m <= 12; $m++)
                <option value="{{ $m }}" @if($m == request('month')) selected @endif>{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                @endfor
            </select>

            @if(request('year') || request('category') || request('month'))
            <a href="{{ route('public.timeline') }}" class="clear-filters">Clear Filters</a>
            @endif
        </div>
    </div>
</div>

<!-- Timeline -->
<div class="section">
    <div class="container">
        @if($events->count() > 0)
            <div class="timeline-container">
                <div class="timeline-vertical">
                    @foreach($events as $event)
                    <a href="{{ route('public.event.detail', $event->id) }}" style="text-decoration: none; color: inherit;">
                        <div class="timeline-entry">
                            <div class="timeline-dot"></div>
                            <div class="timeline-content">
                                <div class="timeline-date">{{ \Carbon\Carbon::parse($event->event_date)->format('F d, Y') }}</div>
                                <h3 class="timeline-title">{{ $event->title }}</h3>
                                <div style="margin-bottom: 10px;">
                                    <span class="event-badge">{{ $event->category->name }}</span>
                                    @foreach($event->campuses as $campus)
                                    <span class="event-badge">{{ $campus->name }}</span>
                                    @endforeach
                                </div>
                                <p class="timeline-description">{{ Str::limit($event->description, 150) }}</p>
                            </div>
                            @php
                                $image = $event->media->first();
                            @endphp
                            @if($image)
                            <div class="timeline-image">
                                <img src="{{ Storage::disk('public')->url($image->path) }}" alt="{{ $event->title }}">
                            </div>
                            @endif
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
        @else
            <div style="text-align: center; padding: 60px 20px;">
                <p style="font-size: 1.1rem; color: var(--ink-soft); margin-bottom: 20px;">No events found for your selected filters.</p>
                <a href="{{ route('public.timeline') }}" class="btn btn-primary">Reset Filters</a>
            </div>
        @endif
    </div>
</div>

<script>
function filterTimeline() {
    // Gather selected values
    const yearSelect = document.querySelector('select[onchange="filterTimeline()"]');
    // This is a simple implementation - in production you'd want more sophisticated filtering
    const form = document.createElement('form');
    form.method = 'GET';
    form.action = '{{ route("public.timeline") }}';
    
    const selects = document.querySelectorAll('.filter-select');
    selects.forEach((select, index) => {
        if (select.value) {
            const input = document.createElement('input');
            input.type = 'hidden';
            if (index === 0) input.name = 'year';
            if (index === 1) input.name = 'category';
            if (index === 2) input.name = 'month';
            input.value = select.value;
            form.appendChild(input);
        }
    });
    
    document.body.appendChild(form);
    form.submit();
}
</script>
@endsection
