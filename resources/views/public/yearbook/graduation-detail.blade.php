@extends('public.layout')

@section('title', 'Graduation Ceremony')

@section('extra-css')
    @vite('resources/css/pages/graduation-detail.css')
@endsection

@section('content')

@php
$ceremonyDate = \Carbon\Carbon::parse($graduation->ceremony_date);
$imageMedia = $graduation->media->first(fn ($media) => $media->type === 'image');
$galleryMedia = $graduation->media;
@endphp

<div class="graduation-page">

    {{-- =====================================================
         HERO
         ===================================================== --}}

    <section class="graduation-hero">

        @if($imageMedia)
        <div class="graduation-hero-image">
            <img
                src="{{ $imageMedia->thumbnailUrl() }}"
                alt="{{ $imageMedia->alt_text ?: 'Graduation ceremony' }}">
        </div>
        @endif

        <div class="graduation-hero-content">
            <span class="graduation-eyebrow">
                LIU Digital Yearbook
            </span>

            <h1>
                Graduation<br>Ceremony
            </h1>

            <p class="graduation-date">
                <span class="graduation-date-icon" aria-hidden="true">
                    ◆
                </span>

                {{ $ceremonyDate->format('F d, Y') }}
            </p>

            <div class="hero-accent-line"></div>
        </div>

    </section>

    {{-- =====================================================
         MAIN CONTENT
         ===================================================== --}}

    <main class="graduation-content">

        <div class="graduation-layout">

            {{-- =================================================
                 CEREMONY CONTENT
                 ================================================= --}}

            <article class="ceremony-card">

                <div class="section-heading">
                    <span class="section-label">
                        The Celebration
                    </span>

                    <h2>
                        Ceremony Details
                    </h2>
                </div>

                @if($graduation->description)

                <div class="ceremony-description">
                    {!! \App\Support\RichText::sanitize($graduation->description) !!}
                </div>

                @else

                <p class="ceremony-description">
                    A celebration of achievement, memories, and the next chapter for the graduating class.
                </p>

                @endif

                {{-- =================================================
                     SPEAKERS AND AWARD RECIPIENTS
                     ================================================= --}}

                @if(!empty($graduation->speakers) || !empty($graduation->award_recipients))
                <div class="participants-grid">
                    @if(!empty($graduation->speakers))
                    <section class="participants-card">
                        <span class="section-label">On Stage</span>
                        <h2>Speakers</h2>
                        <ul class="participants-list">
                            @foreach($graduation->speakers as $speaker)
                                @if(filled($speaker))
                                <li>{{ $speaker }}</li>
                                @endif
                            @endforeach
                        </ul>
                    </section>
                    @endif

                    @if(!empty($graduation->award_recipients))
                    <section class="participants-card">
                        <span class="section-label">Recognition</span>
                        <h2>Award Recipients</h2>
                        <ul class="participants-list">
                            @foreach($graduation->award_recipients as $recipient)
                                @if(!empty($recipient['name']))
                                <li>
                                    <strong>{{ $recipient['name'] }}</strong>
                                    @if(!empty($recipient['award']))
                                        <span>{{ $recipient['award'] }}</span>
                                    @endif
                                </li>
                                @endif
                            @endforeach
                        </ul>
                    </section>
                    @endif
                </div>
                @endif

                {{-- =================================================
                     CEREMONY MEDIA
                     ================================================= --}}

                @if($galleryMedia->isNotEmpty())

                <div class="gallery-heading">
                    <h2>
                        Ceremony Media
                    </h2>

                    <span>
                        {{ $galleryMedia->count() }}
                        {{ $galleryMedia->count() === 1 ? 'item' : 'items' }}
                    </span>
                </div>

                <div class="graduation-gallery">

                    @foreach($galleryMedia as $index => $media)

                    <div
                        class="graduation-gallery-item"
                        style="animation-delay: {{ min($index * 70, 500) }}ms;">

                        @if($media->type === 'image')

                        <img
                            src="{{ $media->thumbnailUrl() }}"
                            alt="{{ $media->alt_text ?: ($media->caption ?: 'Graduation ceremony photo') }}"
                            loading="lazy">

                        @if($media->caption)
                        <div class="gallery-caption">
                            {{ $media->caption }}
                        </div>
                        @endif

                        @elseif($media->type === 'video')

                        <video
                            controls
                            preload="metadata"
                            aria-label="{{ $media->caption ?: 'Graduation ceremony video' }}">
                            <source src="{{ Storage::disk('public')->url($media->path) }}">
                            Your browser does not support video playback.
                        </video>

                        @if($media->caption)
                        <div class="gallery-caption">
                            {{ $media->caption }}
                        </div>
                        @endif

                        @else

                        <div class="media-document">

                            <div
                                class="media-document-icon"
                                aria-hidden="true">
                                ▤
                            </div>

                            <strong>
                                {{ $media->file_name }}
                            </strong>

                            @if($media->caption)
                            <span>
                                {{ $media->caption }}
                            </span>
                            @endif

                            <a
                                href="{{ Storage::disk('public')->url($media->path) }}"
                                target="_blank"
                                rel="noopener noreferrer">
                                Open document
                            </a>

                        </div>

                        @endif

                    </div>

                    @endforeach

                </div>

                @endif

            </article>

            {{-- =================================================
                 SIDEBAR
                 ================================================= --}}

            <aside class="graduation-sidebar">

                <div class="details-card">

                    <div class="details-header">

                        <div
                            class="details-icon"
                            aria-hidden="true">
                            ▣
                        </div>

                        <h3>
                            At a Glance
                        </h3>

                    </div>

                    <div class="detail-item">

                        <span class="detail-label">
                            Date
                        </span>

                        <span class="detail-value">
                            {{ $ceremonyDate->format('F d, Y') }}
                        </span>

                    </div>

                    @if($graduation->venue)

                    <div class="detail-item">

                        <span class="detail-label">
                            Venue
                        </span>

                        <span class="detail-value">
                            {{ $graduation->venue }}
                        </span>

                    </div>

                    @endif

                    @if($graduation->academicYear)

                    <div class="detail-item">

                        <span class="detail-label">
                            Academic Year
                        </span>

                        <span class="detail-value">
                            {{ $graduation->academicYear->title }}
                        </span>

                    </div>

                    @endif

                </div>

            </aside>

        </div>

        {{-- =====================================================
             GRADUATES
             ===================================================== --}}

        <section class="graduates-section">

            <div class="graduates-heading">

                <div>

                    <span class="section-label">
                        The Class
                    </span>

                    <h2>
                        Graduates
                    </h2>

                </div>

                <span class="graduates-count">
                    {{ $graduates->total() }}
                    {{ $graduates->total() === 1 ? 'Graduate' : 'Graduates' }}
                </span>

            </div>

            @if($graduates->count() > 0)

            <div class="graduates-grid">

                @foreach($graduates as $index => $graduate)

                @php
                $portrait = $graduate->media->first();
                @endphp

                <a
                    href="{{ route('public.graduate.detail', ['student_reference' => $graduate->student_reference]) }}"
                    class="graduate-link"
                    style="animation-delay: {{ min($index * 80, 560) }}ms;">

                    <article class="graduate-card">

                        <div class="graduate-image">

                            @if($portrait)

                            <img
                                src="{{ $portrait->thumbnailUrl() }}"
                                alt="{{ $graduate->name }}"
                                loading="lazy">

                            @else

                            <div class="graduate-placeholder">
                                {{ strtoupper(substr($graduate->name, 0, 1)) }}
                            </div>

                            @endif

                        </div>

                        <div class="graduate-body">

                            <h3 class="graduate-name">
                                {{ $graduate->name }}
                            </h3>

                            <p class="graduate-info">

                                {{ $graduate->major?->name ?: 'Graduate' }}

                                @if($graduate->school)
                                <br>
                                <span>
                                    {{ $graduate->school->name }}
                                </span>
                                @endif

                            </p>

                        </div>

                    </article>

                </a>

                @endforeach

            </div>

            @if($graduates->hasPages())

            <div class="graduation-pagination">
                {!! $graduates->links() !!}
            </div>

            @endif

            @else

            <div class="empty-graduates">

                <h3>
                    No published graduates yet
                </h3>

                <p>
                    The ceremony is ready, but graduate profiles have not been published yet.
                </p>

            </div>

            @endif

        </section>

    </main>

</div>

@endsection