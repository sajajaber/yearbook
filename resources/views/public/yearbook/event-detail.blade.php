@extends('public.layout')

@section('title', $event->title . ' | LIU Digital Yearbook')

@section('extra-css')
    @vite('resources/css/pages/event-detail.css')
@endsection

@section('content')

@php
$heroImage = $event->media->first();
@endphp

<div class="event-page">

    {{-- =====================================================
         HERO
         ===================================================== --}}
    <header class="event-hero">

        @if($heroImage)
        <div class="event-hero-image">
            <img
                src="{{ Storage::disk('public')->url($heroImage->path) }}"
                alt="{{ $event->title }}">
        </div>
        @else
        <div class="event-hero-placeholder"></div>
        @endif

        <div class="event-hero-pattern"></div>

        <div class="event-hero-inner">

            <div class="event-breadcrumb">
                <a href="{{ route('public.events') }}">
                    Events
                </a>

                <span class="event-breadcrumb-separator">/</span>

                <span>{{ $event->category->name }}</span>
            </div>

            <div class="event-category-label">
                <span class="event-category-dot"></span>
                {{ $event->category->name }}
            </div>

            <h1>{{ $event->title }}</h1>

            <div class="event-meta">

                <div class="event-meta-item">
                    <span class="event-meta-icon">◷</span>
                    <span>
                        {{ \Carbon\Carbon::parse($event->event_date)->format('F d, Y') }}
                    </span>
                </div>

                @if($event->location)
                <div class="event-meta-item">
                    <span class="event-meta-icon">⌖</span>
                    <span>{{ $event->location }}</span>
                </div>
                @endif

                <div class="event-meta-item">
                    <span class="event-meta-icon">◆</span>
                    <span>{{ $event->category->name }}</span>
                </div>

            </div>

        </div>
    </header>


    {{-- =====================================================
         MAIN CONTENT
         ===================================================== --}}
    <main class="event-main">

        <div class="event-layout">

            {{-- Article --}}
            <article class="event-article">

                <section class="event-description">

                    <h2 class="event-section-heading">
                        About This Event
                    </h2>

                    <p>
                        {!! nl2br(e($event->description)) !!}
                    </p>

                </section>


                {{-- Gallery --}}
                @if($event->media->count() > 1)

                <section class="event-gallery">

                    <h2 class="event-section-heading">
                        Event Gallery
                    </h2>

                    <div class="event-gallery-grid">

                        @foreach($event->media as $media)

                        <div
                            class="event-gallery-item"
                            onclick="openGalleryImage(this)">

                            <img
                                src="{{ Storage::disk('public')->url($media->path) }}"
                                alt="{{ $media->caption ?: $event->title }}"
                                loading="lazy">

                            @if($media->caption)
                            <div class="event-gallery-caption">
                                {{ $media->caption }}
                            </div>
                            @endif

                        </div>

                        @endforeach

                    </div>

                </section>

                @endif

            </article>


            {{-- Sidebar --}}
            <aside class="event-sidebar">

                {{-- Event Details --}}
                <div class="event-sidebar-card">

                    <h3 class="event-sidebar-title">
                        <span class="event-sidebar-title-icon">i</span>
                        Event Details
                    </h3>

                    {{-- Date --}}
                    <div class="event-detail">
                        <span class="event-detail-label">
                            Date
                        </span>

                        <div class="event-detail-value">
                            {{ \Carbon\Carbon::parse($event->event_date)->format('F d, Y') }}
                        </div>
                    </div>

                    {{-- Location --}}
                    @if($event->location)
                    <div class="event-detail">
                        <span class="event-detail-label">
                            Location
                        </span>

                        <div class="event-detail-value">
                            {{ $event->location }}
                        </div>
                    </div>
                    @endif

                    {{-- Category --}}
                    <div class="event-detail">
                        <span class="event-detail-label">
                            Category
                        </span>

                        <div class="event-detail-value">
                            {{ $event->category->name }}
                        </div>
                    </div>

                    {{-- Campuses --}}
                    @if($event->campuses->count() > 0)
                    <div class="event-detail">

                        <span class="event-detail-label">
                            Campuses
                        </span>

                        <div class="event-tags">
                            @foreach($event->campuses as $campus)
                            <span class="event-tag">
                                {{ $campus->name }}
                            </span>
                            @endforeach
                        </div>

                    </div>
                    @endif

                    {{-- Schools --}}
                    @if($event->schools->count() > 0)
                    <div class="event-detail">

                        <span class="event-detail-label">
                            Schools
                        </span>

                        <div class="event-tags">
                            @foreach($event->schools as $school)
                            <span class="event-tag">
                                {{ $school->name }}
                            </span>
                            @endforeach
                        </div>

                    </div>
                    @endif

                </div>


                {{-- Share --}}
                <div class="event-sidebar-card">

                    <h3 class="event-sidebar-title">
                        <span class="event-sidebar-title-icon">↗</span>
                        Share Event
                    </h3>

                    <div class="event-share-buttons">

                        <button
                            type="button"
                            class="event-share-btn copy"
                            title="Copy link"
                            onclick="copyEventLink()"
                            aria-label="Copy event link">
                            ↗
                        </button>

                        <button
                            type="button"
                            class="event-share-btn"
                            title="Share on Facebook"
                            onclick="shareFacebook()"
                            aria-label="Share on Facebook">
                            f
                        </button>

                        <button
                            type="button"
                            class="event-share-btn"
                            title="Share on X"
                            onclick="shareX()"
                            aria-label="Share on X">
                            𝕏
                        </button>

                    </div>

                </div>

            </aside>

        </div>

    </main>


    {{-- =====================================================
         RELATED EVENTS
         ===================================================== --}}
<!--     @if($relatedEvents->count() > 0)

    <section class="related-section">

        <div class="related-inner">

            <div class="related-heading">

                <div>
                    <h2>More From the Yearbook</h2>

                    <p>
                        Explore other moments and events from the community.
                    </p>
                </div>

            </div>


            <div class="related-events-grid">

                @foreach($relatedEvents as $related)

                @php
                $relatedImage = $related->media->first();
                @endphp

                <a
                    href="{{ route('public.event.detail', $related->id) }}"
                    class="related-event-card">

                    <div class="related-event-image">

                        @if($relatedImage)

                        <img
                            src="{{ Storage::disk('public')->url($relatedImage->path) }}"
                            alt="{{ $related->title }}"
                            loading="lazy">

                        @endif

                    </div>

                    <div class="related-event-content">

                        <span class="related-event-date">
                            {{ \Carbon\Carbon::parse($related->event_date)->format('M d, Y') }}
                        </span>

                        <h3 class="related-event-title">
                            {{ $related->title }}
                        </h3>

                        <span class="related-event-arrow">
                            View event →
                        </span>

                    </div>

                </a>

                @endforeach

            </div>

        </div>

    </section>

    @endif -->

</div>


{{-- Toast --}}
<div
    id="eventToast"
    class="event-toast"
    role="status"
    aria-live="polite"></div>


<script>
    function showEventToast(message) {
        const toast = document.getElementById('eventToast');

        toast.textContent = message;
        toast.classList.add('show');

        clearTimeout(window.eventToastTimer);

        window.eventToastTimer = setTimeout(() => {
            toast.classList.remove('show');
        }, 2200);
    }


    async function copyEventLink() {
        try {
            await navigator.clipboard.writeText(window.location.href);
            showEventToast('Event link copied');
        } catch (error) {
            showEventToast('Unable to copy the link');
        }
    }


    function shareFacebook() {
        const url = encodeURIComponent(window.location.href);

        window.open(
            `https://www.facebook.com/sharer/sharer.php?u=${url}`,
            '_blank',
            'width=700,height=500'
        );
    }


    function shareX() {
        const url = encodeURIComponent(window.location.href);
        const text = encodeURIComponent(
            document.title
        );

        window.open(
            `https://twitter.com/intent/tweet?url=${url}&text=${text}`,
            '_blank',
            'width=700,height=500'
        );
    }


    function openGalleryImage(element) {
        const image = element.querySelector('img');

        if (!image) {
            return;
        }

        /*
         * Keeps the interaction lightweight for now.
         * The gallery can later be upgraded to a proper
         * lightbox without changing the Blade structure.
         */
        window.open(image.src, '_blank');
    }
</script>

@endsection