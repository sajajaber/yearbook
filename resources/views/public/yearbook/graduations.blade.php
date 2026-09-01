@extends('public.layout')

@section('title', 'Graduations')

@section('content')
<!-- Hero -->
<div class="hero">
    <div class="container">
        <h1>Graduations</h1>
        <p>Celebrate the graduation ceremonies</p>
    </div>
</div>

<!-- Filters -->
<div class="section">
    <div class="container">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 40px;">
            <form method="GET" action="{{ route('public.graduations') }}" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 10px; width: 100%;">
                <select name="year" class="filter-select" onchange="this.form.submit()">
                    <option value="">All Years</option>
                    @foreach($years as $year)
                    <option value="{{ $year }}" @if($year == request('year')) selected @endif>{{ $year }}</option>
                    @endforeach
                </select>
                <select name="campus" class="filter-select" onchange="this.form.submit()">
                    <option value="">All Campuses</option>
                    @foreach($campuses as $campus)
                    <option value="{{ $campus->id }}" @if($campus->id == request('campus')) selected @endif>{{ $campus->name }}</option>
                    @endforeach
                </select>
                @if(request('year') || request('campus'))
                <a href="{{ route('public.graduations') }}" style="padding: 10px 15px; background: var(--paper); border: 1px solid var(--line); border-radius: 6px; text-decoration: none; color: var(--ink);">Clear</a>
                @endif
            </form>
        </div>

        @if($graduations->count() > 0)
            <div class="grid grid-2">
                @foreach($graduations as $graduation)
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
                            <p class="card-text">
                                <strong>Date:</strong> {{ \Carbon\Carbon::parse($graduation->ceremony_date)->format('F d, Y') }}<br>
                                <strong>Venue:</strong> {{ $graduation->venue }}
                            </p>
                            <p style="font-size: 0.9rem; color: var(--ink-soft); margin-top: 10px;">{{ Str::limit($graduation->description, 100) }}</p>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($graduations->hasPages())
            <div class="pagination" style="margin-top: 40px;">
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
            <div style="text-align: center; padding: 60px 20px;">
                <p style="font-size: 1.1rem; color: var(--ink-soft);">No graduations found.</p>
            </div>
        @endif
    </div>
</div>

<style>
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
</style>
@endsection
