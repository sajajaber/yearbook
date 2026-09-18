@extends('public.layout')

@section('title', 'Events | LIU Digital Yearbook')

@section('extra-css')
    @vite('resources/css/pages/events.css')
@endsection

@section('content')
<div class="events-page">
    <section class="events-hero">
        <div class="events-hero-grid"></div>
        <div class="events-hero-glow"></div>
        <div class="container events-hero-inner">
            <div class="events-kicker">Yearbook Journal</div>
            <h1>Moments<br><em>that mattered.</em></h1>
            <div class="events-hero-bottom">
                <p class="events-hero-copy">A curated record of the gatherings, milestones, celebrations and stories that shaped the academic year.</p>
                <div class="events-hero-count"><strong>{{ $events->total() }}</strong><span>archived events</span></div>
            </div>
        </div>
    </section>

    <main class="container">
        <section class="events-discovery">
            <div class="events-filter-shell">
                <form method="GET" action="{{ route('public.events') }}" class="events-filter-form">
                    <div class="events-filter-field"><input type="search" name="search" value="{{ $search }}" placeholder="Search events..."></div>
                    <div class="events-filter-field"><select name="year">
                            <option value="">All academic years</option>@foreach($years as $academicYear)<option value="{{ $academicYear->id }}" @selected((string)$year===(string)$academicYear->id)>{{ $academicYear->title }}{{ $academicYear->status === 'active' ? ' · Current' : '' }}</option>@endforeach
                        </select></div>
                    <div class="events-filter-field"><select name="category">
                            <option value="">All categories</option>@foreach($categories as $item)<option value="{{ $item->id }}" @selected((string)$category===(string)$item->id)>{{ $item->name }}</option>@endforeach
                        </select></div>
                    <div class="events-filter-field"><select name="campus">
                            <option value="">All campuses</option>@foreach($campuses as $item)<option value="{{ $item->id }}" @selected((string)$campus===(string)$item->id)>{{ $item->name }}</option>@endforeach
                        </select></div>
                    <div class="events-filter-field"><select name="sort">
                            <option value="latest" @selected($sort==='latest' )>Newest first</option>
                            <option value="oldest" @selected($sort==='oldest' )>Oldest first</option>
                            <option value="alphabetical" @selected($sort==='alphabetical' )>A–Z</option>
                            <option value="featured" @selected($sort==='featured' )>Featured first</option>
                        </select></div>
                    <button class="events-filter-button" type="submit">Filter</button>
                </form>
            </div>
        </section>

        <section>
            <div class="events-section-intro">
                <div>
                    <div class="events-section-label">The archive</div>
                    <h2>Events &amp; experiences</h2>
                </div>
                <div style="display:flex;align-items:center;gap:16px;flex-wrap:wrap;justify-content:flex-end;">
                    <span class="events-results">{{ $events->total() }} {{ Str::plural('event', $events->total()) }}</span>
                    <div class="events-view-tools" aria-label="Choose event view">
                        <button type="button" class="events-view-button is-active" data-events-view="list">List</button>
                        <button type="button" class="events-view-button" data-events-view="large">Large</button>
                    </div>
                </div>
            </div>

            @if($events->count())
            <div class="events-list" id="events-list">
                @foreach($events as $event)
                <a class="event-list-link" href="{{ route('public.event.detail', $event->id) }}">
                    <article class="event-list-item">
                        <div class="event-list-date">{{ optional($event->event_date)->format('d M Y') ?? \Carbon\Carbon::parse($event->event_date)->format('d M Y') }}</div>
                        <div class="event-list-main">
                            <div class="event-list-meta">
                                @if($event->category)<span class="event-category">{{ $event->category->name }}</span>@endif
                                @if($event->location)<span class="event-list-location">{{ $event->location }}</span>@endif
                            </div>
                            <h3 class="event-list-title">{{ $event->title }}</h3>
                            @if($event->description)<p class="event-list-description">{{ Str::limit(strip_tags(\App\Support\RichText::sanitize($event->description)), 190) }}</p>@endif
                        </div>
                        <span class="event-list-arrow" aria-hidden="true">→</span>
                    </article>
                </a>
                @endforeach
            </div>
            <div class="events-pagination">{!! $events->onEachSide(1)->links('pagination::simple-tailwind') !!}</div>
            @else
            <div class="events-empty">
                <h3>No events found</h3>
                <p>Try a different academic year, category or search term.</p>
            </div>
            @endif
        </section>
    </main>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const list = document.getElementById('events-list');
        if (!list) return;
        const buttons = document.querySelectorAll('[data-events-view]');
        const key = 'yearbook-events-view';
        const apply = mode => {
            list.classList.toggle('view-large', mode === 'large');
            buttons.forEach(b => b.classList.toggle('is-active', b.dataset.eventsView === mode));
            try {
                localStorage.setItem(key, mode)
            } catch (e) {}
        };
        let mode = 'list';
        try {
            mode = localStorage.getItem(key) === 'large' ? 'large' : 'list'
        } catch (e) {}
        buttons.forEach(b => b.addEventListener('click', () => apply(b.dataset.eventsView)));
        apply(mode);
    });
</script>
@endpush