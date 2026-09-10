@extends('public.layout')

@section('title', 'University Digital Yearbook')

@section('content')

@vite(['resources/css/app.css', 'resources/js/app.js'])

<section class="public-hero">
    <div class="public-hero-inner">
        <p class="eyebrow eyebrow-light">{{ $currentYear->title ?? 'Digital Yearbook' }}</p>
        <h1>Every moment,<br>archived and shared.</h1>
        <p>Browse published events, graduation ceremonies, and the graduates who made this year memorable.</p>
        <div class="public-hero-actions">
            <a href="{{ route('public.events') }}" class="button button-red">Explore events <span aria-hidden="true">→</span></a>
            <a href="{{ route('search.index') }}" class="button button-outline-public">Search the archive</a>
        </div>
    </div>
    <div class="public-hero-stats">
        <div><strong>{{ $publishedEventsCount }}</strong><span>events</span></div>
        <div><strong>{{ $publishedGraduatesCount }}</strong><span>graduates</span></div>
    </div>
</section>

<section class="public-section">
    <div class="section-heading">
        <div>
            <p class="eyebrow">Recently published</p>
            <h2>Latest from the archive</h2>
        </div>
        <a href="{{ route('public.events') }}" class="text-link">Browse all events <span aria-hidden="true">→</span></a>
    </div>
    <div class="public-event-grid">
        @forelse ($recentEvents as $event)
        <a href="{{ route('public.event.detail', $event) }}" class="public-event-card">
            <div class="public-event-date">{{ $event->event_date?->format('M d') }}</div>
            <h3>{{ $event->title }}</h3>
            <p>{{ Str::limit($event->description, 110) }}</p>
        </a>
        @empty
        <p class="empty-state">No published events yet — check back soon.</p>
        @endforelse
    </div>
</section>
@endsection