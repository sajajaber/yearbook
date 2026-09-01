@extends('public.layout')

@section('title', $event->title)

@section('extra-css')
<style>
    .event-hero {
        position: relative;
        height: 400px;
        background: linear-gradient(135deg, rgba(0, 42, 92, 0.7) 0%, rgba(26, 63, 127, 0.7) 100%);
        display: flex;
        align-items: flex-end;
        overflow: hidden;
    }

    .event-hero-image {
        position: absolute;
        inset: 0;
        z-index: -1;
    }

    .event-hero-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .event-hero-content {
        position: relative;
        color: white;
        padding: 40px 20px 20px;
        width: 100%;
    }

    .event-hero-content h1 {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 15px;
    }

    .event-meta {
        display: flex;
        gap: 30px;
        flex-wrap: wrap;
        font-size: 1rem;
    }

    .event-meta-item {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .event-content {
        display: grid;
        grid-template-columns: 1fr 300px;
        gap: 40px;
        margin: 40px 0;
    }

    .event-body {
        background: var(--white);
        padding: 30px;
        border-radius: 12px;
    }

    .event-body h2 {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 15px;
        color: var(--ink);
    }

    .event-body p {
        color: var(--ink-soft);
        line-height: 1.8;
        margin-bottom: 20px;
    }

    .event-sidebar {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .sidebar-card {
        background: var(--white);
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .sidebar-card h3 {
        font-size: 1rem;
        font-weight: 700;
        margin-bottom: 15px;
        color: var(--ink);
    }

    .sidebar-card p {
        font-size: 0.95rem;
        color: var(--ink-soft);
        margin-bottom: 10px;
    }

    .tag-list {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .tag {
        display: inline-block;
        padding: 4px 12px;
        background: var(--paper);
        border-radius: 20px;
        font-size: 0.85rem;
        color: var(--ink);
        font-weight: 600;
    }

    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-top: 30px;
    }

    .gallery-item {
        border-radius: 12px;
        overflow: hidden;
        height: 200px;
        cursor: pointer;
        transition: transform 0.3s ease;
    }

    .gallery-item:hover {
        transform: scale(1.05);
    }

    .gallery-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .related-events {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }

    .share-buttons {
        display: flex;
        gap: 10px;
        margin-top: 15px;
    }

    .share-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: var(--paper);
        color: var(--ink);
        text-decoration: none;
        transition: all 0.3s ease;
        font-weight: 700;
    }

    .share-btn:hover {
        background: var(--red);
        color: white;
        transform: translateY(-3px);
    }

    @media (max-width: 768px) {
        .event-hero {
            height: 250px;
        }

        .event-hero-content h1 {
            font-size: 1.8rem;
        }

        .event-content {
            grid-template-columns: 1fr;
        }

        .event-meta {
            gap: 15px;
            font-size: 0.9rem;
        }
    }
</style>
@endsection

@section('content')
<!-- Event Hero -->
<div class="event-hero">
    @php
        $image = $event->media->first();
    @endphp
    @if($image)
    <div class="event-hero-image">
        <img src="{{ Storage::disk('public')->url($image->path) }}" alt="{{ $event->title }}">
    </div>
    @endif
    <div class="event-hero-content">
        <h1>{{ $event->title }}</h1>
        <div class="event-meta">
            <div class="event-meta-item">
                📅 {{ \Carbon\Carbon::parse($event->event_date)->format('F d, Y') }}
            </div>
            <div class="event-meta-item">
                🏷️ {{ $event->category->name }}
            </div>
            @if($event->location)
            <div class="event-meta-item">
                📍 {{ $event->location }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Content -->
<div class="section">
    <div class="container">
        <div class="event-content">
            <div class="event-body">
                <h2>About This Event</h2>
                <p>{!! nl2br(e($event->description)) !!}</p>

                @if($event->media->count() > 1)
                <h2>Event Gallery</h2>
                <div class="gallery-grid">
                    @foreach($event->media as $media)
                    <div class="gallery-item">
                        <img src="{{ Storage::disk('public')->url($media->path) }}" alt="{{ $media->caption }}">
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            <aside class="event-sidebar">
                <!-- Event Info -->
                <div class="sidebar-card">
                    <h3>📋 Event Details</h3>
                    <p>
                        <strong>Date:</strong><br>
                        {{ \Carbon\Carbon::parse($event->event_date)->format('F d, Y') }}
                    </p>
                    @if($event->location)
                    <p>
                        <strong>Location:</strong><br>
                        {{ $event->location }}
                    </p>
                    @endif
                    <p>
                        <strong>Category:</strong><br>
                        {{ $event->category->name }}
                    </p>
                </div>

                <!-- Campuses -->
                @if($event->campuses->count() > 0)
                <div class="sidebar-card">
                    <h3>🏛️ Campuses</h3>
                    <div class="tag-list">
                        @foreach($event->campuses as $campus)
                        <span class="tag">{{ $campus->name }}</span>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Schools -->
                @if($event->schools->count() > 0)
                <div class="sidebar-card">
                    <h3>🎓 Schools</h3>
                    <div class="tag-list">
                        @foreach($event->schools as $school)
                        <span class="tag">{{ $school->name }}</span>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Share -->
                <div class="sidebar-card">
                    <h3>Share</h3>
                    <div class="share-buttons">
                        <a href="javascript:void(0)" class="share-btn" title="Copy Link" onclick="copyLink()">🔗</a>
                        <a href="javascript:void(0)" class="share-btn" title="Share on Facebook">f</a>
                        <a href="javascript:void(0)" class="share-btn" title="Share on Twitter">𝕏</a>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</div>

<!-- Related Events -->
@if($relatedEvents->count() > 0)
<div class="section" style="background: var(--paper);">
    <div class="container">
        <h2 class="section-title">Related Events</h2>
        <div class="related-events">
            @foreach($relatedEvents as $related)
            <a href="{{ route('public.event.detail', $related->id) }}" style="text-decoration: none; color: inherit;">
                <div class="card">
                    @php
                        $img = $related->media->first();
                    @endphp
                    <div class="card-image">
                        @if($img)
                            <img src="{{ Storage::disk('public')->url($img->path) }}" alt="{{ $related->title }}">
                        @else
                            <div style="width: 100%; height: 100%; background: linear-gradient(135deg, var(--ink) 0%, #1a3f7f 100%);"></div>
                        @endif
                    </div>
                    <div class="card-body">
                        <h3 class="card-title">{{ $related->title }}</h3>
                        <p class="card-text">{{ \Carbon\Carbon::parse($related->event_date)->format('M d, Y') }}</p>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</div>
@endif

<script>
function copyLink() {
    navigator.clipboard.writeText(window.location.href).then(() => {
        alert('Link copied to clipboard!');
    });
}
</script>
@endsection
