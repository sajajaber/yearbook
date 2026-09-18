@extends('public.layout')

@section('title', 'Graduations | LIU Digital Yearbook')

@section('extra-css')
    @vite('resources/css/pages/graduations.css')
@endsection

@section('content')
<div class="graduations-page">
    <section class="graduations-hero">
        <div class="graduations-hero-grid"></div>
        <div class="graduations-hero-glow"></div>
        <div class="container">
            <div class="graduations-hero-content">
                <div class="graduations-kicker">Ceremonies & milestones</div>
                <h1>Graduations<em>the moments that become memories.</em></h1>
                <div class="graduations-hero-bottom">
                    <p class="graduations-hero-copy">Celebrate the graduation ceremonies that brought each class across the stage, and revisit the places, people and milestones that shaped every celebration.</p>
                    <div class="graduations-hero-count"><strong>{{ $graduations->total() }}</strong><span>{{ Str::plural('ceremony', $graduations->total()) }}</span></div>
                </div>
            </div>
        </div>
    </section>

    <div class="section">
        <div class="container">
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