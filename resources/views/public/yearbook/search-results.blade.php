@extends('public.layout')

@section('title', 'Search Results')

@section('content')
<!-- Hero -->
<div class="hero">
    <div class="container">
        <h1>Search Results</h1>
        <p @if($query) >Found results for "{{ $query }}"@endif</p>
    </div>
</div>

<!-- Search Bar -->
<div class="section" style="padding: 40px 20px;">
    <div class="container">
        <form method="GET" action="{{ route('public.search') }}" style="display: grid; grid-template-columns: 1fr 150px; gap: 10px;">
            <input type="text" name="q" value="{{ $query }}" placeholder="Search events, graduates, graduations..." style="padding: 12px 16px; border: 1px solid var(--line); border-radius: 6px; font-size: 0.95rem;">
            <button type="submit" class="btn btn-primary" style="padding: 12px 24px;">Search</button>
        </form>
    </div>
</div>

@if($query)
<!-- Results -->
<div class="section">
    <div class="container">
        <!-- Events -->
        @if(isset($results['events']) && $results['events']->count() > 0)
        <div style="margin-bottom: 60px;">
            <h2 class="section-title">Events ({{ $results['events']->count() }})</h2>
            <div class="grid grid-3">
                @foreach($results['events'] as $event)
                <a href="{{ route('public.event.detail', $event->id) }}" style="text-decoration: none; color: inherit;">
                    <div class="card">
                        @php
                            $image = $event->media->first();
                        @endphp
                        <div class="card-image">
                            @if($image)
                                <img src="{{ Storage::disk('public')->url($image->path) }}" alt="{{ $event->title }}">
                            @else
                                <div style="width: 100%; height: 100%; background: linear-gradient(135deg, var(--ink) 0%, #1a3f7f 100%);"></div>
                            @endif
                        </div>
                        <div class="card-body">
                            <h3 class="card-title">{{ $event->title }}</h3>
                            <p class="card-text">{{ Str::limit($event->description, 100) }}</p>
                            <p style="font-size: 0.85rem; color: var(--ink-soft);">{{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}</p>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
            <div style="text-align: center; margin-top: 20px;">
                <a href="{{ route('public.events', ['search' => $query]) }}" class="btn btn-secondary">View All Events →</a>
            </div>
        </div>
        @endif

        <!-- Graduates -->
        @if(isset($results['graduates']) && $results['graduates']->count() > 0)
        <div style="margin-bottom: 60px;">
            <h2 class="section-title">Graduates ({{ $results['graduates']->count() }})</h2>
            <div class="grid grid-4">
                @foreach($results['graduates'] as $graduate)
                <a href="{{ route('public.graduate.detail', $graduate->id) }}" style="text-decoration: none; color: inherit;">
                    <div class="card" style="text-align: center;">
                        @php
                            $portrait = $graduate->media->first();
                        @endphp
                        <div class="card-image" style="height: 200px;">
                            @if($portrait)
                                <img src="{{ Storage::disk('public')->url($portrait->path) }}" alt="{{ $graduate->name }}">
                            @else
                                <div style="width: 100%; height: 100%; background: linear-gradient(135deg, var(--ink) 0%, #1a3f7f 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 3rem;">{{ strtoupper(substr($graduate->name, 0, 1)) }}</div>
                            @endif
                        </div>
                        <div class="card-body">
                            <h3 class="card-title">{{ $graduate->name }}</h3>
                            <p class="card-text" style="font-size: 0.9rem;">{{ $graduate->major->name ?? 'Major' }}</p>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
            <div style="text-align: center; margin-top: 20px;">
                <a href="{{ route('public.graduates', ['search' => $query]) }}" class="btn btn-secondary">View All Graduates →</a>
            </div>
        </div>
        @endif

        <!-- Graduations -->
        @if(isset($results['graduations']) && $results['graduations']->count() > 0)
        <div>
            <h2 class="section-title">Graduations ({{ $results['graduations']->count() }})</h2>
            <div class="grid grid-2">
                @foreach($results['graduations'] as $graduation)
                <a href="{{ route('public.graduation.detail', $graduation->id) }}" style="text-decoration: none; color: inherit;">
                    <div class="card">
                        @php
                            $image = $graduation->media->first();
                        @endphp
                        <div class="card-image">
                            @if($image)
                                <img src="{{ Storage::disk('public')->url($image->path) }}" alt="Graduation">
                            @else
                                <div style="width: 100%; height: 100%; background: linear-gradient(135deg, var(--ink) 0%, #1a3f7f 100%);"></div>
                            @endif
                        </div>
                        <div class="card-body">
                            <h3 class="card-title">Graduation Ceremony</h3>
                            <p class="card-text">{{ \Carbon\Carbon::parse($graduation->ceremony_date)->format('F d, Y') }}</p>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        <!-- No Results -->
        @if(
            (empty($results['events']) || $results['events']->count() === 0) &&
            (empty($results['graduates']) || $results['graduates']->count() === 0) &&
            (empty($results['graduations']) || $results['graduations']->count() === 0)
        )
        <div style="text-align: center; padding: 60px 20px;">
            <p style="font-size: 1.2rem; color: var(--ink-soft); margin-bottom: 20px;">No results found for "{{ $query }}"</p>
            <p style="color: var(--ink-soft); margin-bottom: 30px;">Try searching with different keywords or browse our yearbook sections.</p>
            <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
                <a href="{{ route('public.events') }}" class="btn btn-primary">Browse Events</a>
                <a href="{{ route('public.graduates') }}" class="btn btn-secondary">Browse Graduates</a>
                <a href="{{ route('public.graduations') }}" class="btn btn-secondary">Browse Graduations</a>
            </div>
        </div>
        @endif
    </div>
</div>
@else
<!-- Empty State -->
<div class="section">
    <div class="container" style="text-align: center;">
        <p style="font-size: 1.1rem; color: var(--ink-soft); margin-bottom: 20px;">Start searching to find events, graduates, and graduations</p>
    </div>
</div>
@endif
@endsection
