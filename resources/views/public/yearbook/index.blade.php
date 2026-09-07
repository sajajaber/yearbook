@extends('public.layout')

@section('title', 'Digital Yearbook - Home')

@section('extra-css')
<style>
    .featured-section {
        background: linear-gradient(135deg, var(--ink) 0%, #1a3f7f 100%);
        color: white;
        padding: 60px 20px;
        margin-bottom: 40px;
    }

    .featured-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
        max-width: 1280px;
        margin: 0 auto;
    }

    .featured-card {
        position: relative;
        border-radius: 12px;
        overflow: hidden;
        height: 300px;
        cursor: pointer;
        group: all;
    }

    .featured-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }

    .featured-card:hover img {
        transform: scale(1.1);
    }

    .featured-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, transparent 0%, rgba(0, 0, 0, 0.7) 100%);
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        padding: 20px;
        transition: all 0.3s ease;
    }

    .featured-overlay h3 {
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .featured-overlay p {
        font-size: 0.9rem;
        opacity: 0.9;
    }

    .cta-buttons {
        display: flex;
        gap: 15px;
        justify-content: center;
        margin-top: 40px;
        flex-wrap: wrap;
    }

    .timeline-preview {
        position: relative;
        padding: 40px 0;
    }

    .timeline-item {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: 40px;
        align-items: center;
        margin-bottom: 60px;
        animation: fadeInUp 0.6s ease-out;
    }

    .timeline-item:nth-child(even) {
        grid-template-columns: 2fr 1fr;
    }

    .timeline-item:nth-child(even) .timeline-image {
        order: 2;
    }

    .timeline-date {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--red);
        margin-bottom: 10px;
    }

    .timeline-image {
        border-radius: 12px;
        overflow: hidden;
        height: 300px;
    }

    .timeline-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .timeline-content h3 {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 15px;
        color: var(--ink);
    }

    .timeline-content p {
        color: var(--ink-soft);
        margin-bottom: 20px;
        line-height: 1.7;
    }

    @media (max-width: 768px) {

        .timeline-item,
        .timeline-item:nth-child(even) {
            grid-template-columns: 1fr;
        }

        .timeline-item:nth-child(even) .timeline-image {
            order: auto;
        }

        .featured-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<!-- Hero -->
@if($heroImages->count() > 0)
<div class="hero hero-carousel" id="heroCarousel" data-count="{{ $heroImages->count() }}">
    <div class="hero-slides">
        @foreach($heroImages as $index => $image)
        <div class="hero-slide {{ $index === 0 ? 'is-active' : '' }}" style="background-image: linear-gradient(135deg, rgba(0, 42, 92, 0.75) 0%, rgba(26, 63, 127, 0.65) 100%), url('{{ Storage::disk('public')->url($image->path) }}');"></div>
        @endforeach
    </div>
    <div class="container">
        <h1>University Annual Yearbook</h1>
        <p>Explore events, graduations, and celebrate the achievements of our graduates</p>
        <div class="cta-buttons">
            <a href="{{ route('public.events') }}" class="btn btn-primary">Browse Events</a>
            <a href="{{ route('public.graduates') }}" class="btn btn-secondary">Meet Graduates</a>
        </div>
    </div>
    @if($heroImages->count() > 1)
    <div class="hero-dots">
        @foreach($heroImages as $index => $image)
        <button type="button" class="hero-dot {{ $index === 0 ? 'is-active' : '' }}" data-slide="{{ $index }}" aria-label="Show slide {{ $index + 1 }}"></button>
        @endforeach
    </div>
    @endif
</div>
@else
<div class="hero">
    <div class="container">
        <h1>University Annual Yearbook</h1>
        <p>Explore events, graduations, and celebrate the achievements of our graduates</p>
        <div class="cta-buttons">
            <a href="{{ route('public.events') }}" class="btn btn-primary">Browse Events</a>
            <a href="{{ route('public.graduates') }}" class="btn btn-secondary">Meet Graduates</a>
        </div>
    </div>
</div>
@endif

<!-- Statistics -->
<div class="section">
    <div class="container">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number">{{ $stats['graduates'] }}</div>
                <div class="stat-label">Graduates</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $stats['events'] }}</div>
                <div class="stat-label">Events</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $stats['schools'] }}</div>
                <div class="stat-label">Schools</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $stats['campuses'] }}</div>
                <div class="stat-label">Campuses</div>
            </div>
        </div>
    </div>
</div>

<!-- Featured Events -->
@if($featuredEvents->count() > 0)
<div class="featured-section">
    <div class="container" style="margin-bottom: 20px;">
        <h2 style="font-size: 1.8rem; margin-bottom: 10px;">Featured Events</h2>
        <p style="opacity: 0.9;">Highlights from the academic year</p>
    </div>
    <div class="featured-grid">
        @foreach($featuredEvents as $event)
        <a href="{{ route('public.event.detail', $event->id) }}" class="featured-card">
            @php
            $image = $event->media->first();
            @endphp
            @if($image)
            <img src="{{ Storage::disk('public')->url($image->path) }}" alt="{{ $event->title }}">
            @else
            <div style="width: 100%; height: 100%; background: linear-gradient(135deg, var(--ink) 0%, #1a3f7f 100%);"></div>
            @endif
            <div class="featured-overlay">
                <h3>{{ $event->title }}</h3>
                <p>{{ $event->category->name ?? 'Event' }}</p>
            </div>
        </a>
        @endforeach
    </div>
</div>
@endif

<!-- Recent Events -->
@if($recentEvents->count() > 0)
<div class="section">
    <div class="container">
        <h2 class="section-title">Latest Events</h2>
        <p class="section-subtitle">Discover what's happening this year</p>

        <div class="grid grid-3">
            @foreach($recentEvents as $event)
            <a href="{{ route('public.event.detail', $event->id) }}" style="text-decoration: none; color: inherit;">
                <div class="card">
                    @php
                    $image = $event->media->first();
                    @endphp
                    <div class="card-image">
                        @if($image)
                        <img src="{{ Storage::disk('public')->url($image->path) }}" alt="{{ $event->title }}">
                        @else
                        <div style="width: 100%; height: 100%; background: var(--paper);"></div>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="card-meta">
                            <span>{{ $event->category->name ?? 'Event' }}</span>
                            <span>{{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}</span>
                        </div>
                        <h3 class="card-title">{{ $event->title }}</h3>
                        <p class="card-text">{{ Str::limit($event->description, 100) }}</p>
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        <div style="text-align: center; margin-top: 40px;">
            <a href="{{ route('public.events') }}" class="btn btn-primary">View All Events →</a>
        </div>
    </div>
</div>
@endif

<!-- Latest Graduation -->
@if($latestGraduation)
<div class="section" style="background: var(--paper);">
    <div class="container">
        <h2 class="section-title">Latest Graduation</h2>

        <div class="timeline-item">
            @php
            $image = $latestGraduation->media->first();
            @endphp
            <div class="timeline-image">
                @if($image)
                <img src="{{ Storage::disk('public')->url($image->path) }}" alt="Graduation">
                @else
                <div style="width: 100%; height: 100%; background: linear-gradient(135deg, var(--ink) 0%, #1a3f7f 100%);"></div>
                @endif
            </div>
            <div class="timeline-content">
                <div class="timeline-date">{{ \Carbon\Carbon::parse($latestGraduation->ceremony_date)->format('F Y') }}</div>
                <h3>Graduation Ceremony</h3>
                <p>{{ $latestGraduation->description }}</p>
                <p><strong>Location:</strong> {{ $latestGraduation->venue }}</p>
                <a href="{{ route('public.graduation.detail', $latestGraduation->id) }}" class="btn btn-primary">View Ceremony Details →</a>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Call to Action -->
<div class="section">
    <div class="container" style="text-align: center;">
        <h2 class="section-title">Explore More</h2>
        <p class="section-subtitle">Browse our comprehensive yearbook archive</p>

        <div class="grid grid-3" style="margin-top: 40px;">
            <div class="card" style="text-align: center; border: none; box-shadow: none;">
                <div class="card-body">
                    <div style="font-size: 3rem; margin-bottom: 15px;">📅</div>
                    <h3 class="card-title">Timeline</h3>
                    <p class="card-text">Explore events chronologically throughout the year</p>
                    <a href="{{ route('public.timeline') }}" class="card-link">View Timeline →</a>
                </div>
            </div>
            <div class="card" style="text-align: center; border: none; box-shadow: none;">
                <div class="card-body">
                    <div style="font-size: 3rem; margin-bottom: 15px;">🎓</div>
                    <h3 class="card-title">Graduates</h3>
                    <p class="card-text">Meet the accomplished graduates from this year</p>
                    <a href="{{ route('public.graduates') }}" class="card-link">Browse Graduates →</a>
                </div>
            </div>
            <div class="card" style="text-align: center; border: none; box-shadow: none;">
                <div class="card-body">
                    <div style="font-size: 3rem; margin-bottom: 15px;">🎉</div>
                    <h3 class="card-title">Graduations</h3>
                    <p class="card-text">Celebrate the graduation ceremonies</p>
                    <a href="{{ route('public.graduations') }}" class="card-link">See Graduations →</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection