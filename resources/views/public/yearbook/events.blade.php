@extends('public.layout')

@section('title', 'Events')

@section('extra-css')
<style>
    .filters-bar {
        background: var(--white);
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 30px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .search-filters {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-bottom: 20px;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
    }

    .filter-group label {
        font-size: 0.9rem;
        font-weight: 600;
        margin-bottom: 8px;
        color: var(--ink);
    }

    .filter-group input,
    .filter-group select {
        padding: 10px 12px;
        border: 1px solid var(--line);
        border-radius: 6px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }

    .filter-group input:focus,
    .filter-group select:focus {
        outline: none;
        border-color: var(--red);
        box-shadow: 0 0 0 3px rgba(255, 176, 52, 0.1);
    }

    .filter-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .active-filters {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid var(--line);
    }

    .filter-tag {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 12px;
        background: var(--paper);
        border-radius: 20px;
        font-size: 0.85rem;
        color: var(--ink);
    }

    .filter-tag a {
        cursor: pointer;
        color: var(--ink-soft);
        text-decoration: none;
        font-weight: 700;
        transition: color 0.3s ease;
    }

    .filter-tag a:hover {
        color: var(--red);
    }

    .event-card-large {
        display: grid;
        grid-template-columns: 250px 1fr;
        gap: 20px;
        align-items: start;
        transition: all 0.3s ease;
    }

    .event-card-large:hover {
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.15);
    }

    .event-card-large .card-image {
        height: 200px;
        margin: 0;
    }

    .results-info {
        color: var(--ink-soft);
        font-size: 0.95rem;
        margin-bottom: 20px;
    }

    @media (max-width: 768px) {
        .search-filters {
            grid-template-columns: 1fr;
        }

        .event-card-large {
            grid-template-columns: 1fr;
        }

        .active-filters {
            flex-wrap: wrap;
        }
    }
</style>
@endsection

@section('content')
<!-- Hero -->
<div class="hero">
    <div class="container">
        <h1>Events</h1>
        <p>Explore all events from the academic year</p>
    </div>
</div>

<!-- Filters -->
<div class="section">
    <div class="container">
        <div class="filters-bar">
            <form method="GET" action="{{ route('public.events') }}" id="filterForm">
                <div class="search-filters">
                    <div class="filter-group">
                        <label>Search</label>
                        <input type="text" name="search" value="{{ $search }}" placeholder="Event title or description...">
                    </div>
                    <div class="filter-group">
                        <label>Category</label>
                        <select name="category">
                            <option value="">All Categories</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" @if($cat->id == $category) selected @endif>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>Campus</label>
                        <select name="campus">
                            <option value="">All Campuses</option>
                            @foreach($campuses as $c)
                            <option value="{{ $c->id }}" @if($c->id == $campus) selected @endif>{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>Sort By</label>
                        <select name="sort">
                            <option value="latest" @if($sort==='latest' ) selected @endif>Latest First</option>
                            <option value="oldest" @if($sort==='oldest' ) selected @endif>Oldest First</option>
                            <option value="alphabetical" @if($sort==='alphabetical' ) selected @endif>Alphabetical</option>
                        </select>
                    </div>
                </div>

                <div class="filter-actions">
                    <button type="submit" class="btn btn-primary">Filter Events</button>
                    @if($search || $category || $campus)
                    <a href="{{ route('public.events') }}" class="btn btn-secondary">Clear All</a>
                    @endif
                </div>

                @if($search || $category || $campus)
                <div class="active-filters">
                    @if($search)
                    <div class="filter-tag">
                        Search: {{ $search }}
                        <a href="{{ route('public.events', array_merge(request()->query(), ['search' => null])) }}">×</a>
                    </div>
                    @endif
                    @if($category)
                    <div class="filter-tag">
                        Category: {{ $categories->find($category)?->name }}
                        <a href="{{ route('public.events', array_merge(request()->query(), ['category' => null])) }}">×</a>
                    </div>
                    @endif
                    @if($campus)
                    <div class="filter-tag">
                        Campus: {{ $campuses->find($campus)?->name }}
                        <a href="{{ route('public.events', array_merge(request()->query(), ['campus' => null])) }}">×</a>
                    </div>
                    @endif
                </div>
                @endif
            </form>
        </div>

        <!-- Results -->
        @if($events->count() > 0)
        <div class="results-info">
            Showing {{ $events->firstItem() }} to {{ $events->lastItem() }} of {{ $events->total() }} events
        </div>

        <div class="grid grid-1" style="gap: 20px;">
            @foreach($events as $event)
            <a href="{{ route('public.event.detail', $event->id) }}" style="text-decoration: none; color: inherit;">
                <div class="card">
                    <div class="event-card-large">
                        @php
                        $image = $event->media->first();
                        @endphp
                        @if($image)
                        <div class="card-image">
                            <img src="{{ Storage::disk('public')->url($image->path) }}" alt="{{ $event->title }}">
                        </div>
                        @else
                        <div class="card-image" style="background: linear-gradient(135deg, var(--ink) 0%, #1a3f7f 100%);"></div>
                        @endif

                        <div class="card-body">
                            <div class="card-meta" style="margin-bottom: 15px;">
                                <span style="background: var(--paper); padding: 4px 12px; border-radius: 20px; font-size: 0.85rem; color: var(--ink);">{{ $event->category->name }}</span>
                                <span>{{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}</span>
                            </div>
                            <h3 class="card-title" style="font-size: 1.3rem;">{{ $event->title }}</h3>
                            <p class="card-text">{{ Str::limit($event->description, 200) }}</p>

                            <div style="display: flex; gap: 10px; flex-wrap: wrap; margin-top: 15px;">
                                @foreach($event->campuses as $c)
                                <span style="font-size: 0.8rem; padding: 4px 8px; background: rgba(255, 176, 52, 0.1); color: var(--red); border-radius: 4px;">{{ $c->name }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($events->hasPages())
        <div class="pagination">
            @if($events->onFirstPage())
            <span>←</span>
            @else
            <a href="{{ $events->previousPageUrl() }}">←</a>
            @endif

            @foreach($events->getUrlRange(1, $events->lastPage()) as $page => $url)
            @if($page == $events->currentPage())
            <span class="active">{{ $page }}</span>
            @else
            <a href="{{ $url }}">{{ $page }}</a>
            @endif
            @endforeach

            @if($events->hasMorePages())
            <a href="{{ $events->nextPageUrl() }}">→</a>
            @else
            <span>→</span>
            @endif
        </div>
        @endif
        @else
        <div style="text-align: center; padding: 60px 20px;">
            <p style="font-size: 1.1rem; color: var(--ink-soft); margin-bottom: 20px;">No events found matching your filters.</p>
            <a href="{{ route('public.events') }}" class="btn btn-primary">Browse All Events</a>
        </div>
        @endif
    </div>
</div>
@endsection