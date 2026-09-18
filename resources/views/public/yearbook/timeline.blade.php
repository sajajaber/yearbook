@extends('public.layout')

@section('title', 'Timeline | LIU Digital Yearbook')

@section('extra-css')
    @vite('resources/css/pages/timeline.css')
@endsection

@section('content')

<div class="timeline-page">


    {{-- =====================================================
     HERO
====================================================== --}}

    <section class="timeline-hero">

        <div class="timeline-hero-grid"></div>
        <div class="timeline-hero-circle"></div>

        <div class="timeline-hero-content">

            <div class="timeline-kicker">
                LIU · University Archive
            </div>

            <h1>
                Life,
                <span>in moments.</span>
            </h1>

            <div class="timeline-hero-bottom">

                <p class="timeline-hero-description">
                    Explore the moments that shaped university life —
                    from ceremonies and celebrations to the everyday
                    events that became part of the story.
                </p>

                <span class="timeline-hero-scroll">
                    Scroll to explore ↓
                </span>

            </div>

        </div>

    </section>


    {{-- =====================================================
     FILTERS
====================================================== --}}

    <section class="timeline-controls">

        <div class="timeline-controls-inner">

            <div class="timeline-controls-label">
                <span>Archive</span>
                <strong>Browse events</strong>
            </div>

            <form
                id="timeline-filter-form"
                method="GET"
                action="{{ route('public.timeline') }}"
                class="timeline-filters">

                <div class="timeline-filter">

                    <select
                        name="year"
                        onchange="this.form.submit()"
                        aria-label="Filter by year">
                        <option value="">
                            All Years
                        </option>

                        @foreach($years as $year)

                        <option
                            value="{{ $year }}"
                            @if($year==request('year')) selected @endif>
                            {{ $year }}
                        </option>

                        @endforeach

                    </select>

                </div>


                <div class="timeline-filter">

                    <select
                        name="category"
                        onchange="this.form.submit()"
                        aria-label="Filter by category">
                        <option value="">
                            All Categories
                        </option>

                        @foreach($categories as $cat)

                        <option
                            value="{{ $cat->id }}"
                            @if($cat->id == request('category')) selected @endif
                            >
                            {{ $cat->name }}
                        </option>

                        @endforeach

                    </select>

                </div>


                <div class="timeline-filter">

                    <select
                        name="month"
                        onchange="this.form.submit()"
                        aria-label="Filter by month">
                        <option value="">
                            All Months
                        </option>

                        @for($m = 1; $m <= 12; $m++)

                            <option
                            value="{{ $m }}"
                            @if($m==request('month')) selected @endif>
                            {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                            </option>

                            @endfor

                    </select>

                </div>


                @if(request('year') || request('category') || request('month'))

                <a
                    href="{{ route('public.timeline') }}"
                    class="timeline-clear">
                    Clear filters
                </a>

                @endif

            </form>

        </div>

    </section>


    {{-- =====================================================
     TIMELINE
====================================================== --}}

    <main class="timeline-main">

        @if($events->count() > 0)

        <div class="timeline-feed">

            @php
            $currentMonthYear = '';
            @endphp


            @foreach($events as $event)

            @php
            $eventMonthYear = \Carbon\Carbon::parse(
            $event->event_date
            )->format('F Y');

            $image = $event->media->first();
            @endphp


            {{-- MONTH --}}
            @if($currentMonthYear !== $eventMonthYear)

            <div class="timeline-month-divider">

                <span class="timeline-month-badge">
                    {{ $eventMonthYear }}
                </span>

            </div>

            @php
            $currentMonthYear = $eventMonthYear;
            @endphp

            @endif


            {{-- EVENT --}}
            <article class="timeline-item">

                <div class="timeline-event-date">

                    <strong>
                        {{ \Carbon\Carbon::parse($event->event_date)->format('F d, Y') }}
                    </strong>

                    <span>
                        {{ \Carbon\Carbon::parse($event->event_date)->format('l') }}
                    </span>

                </div>


                <a
                    href="{{ route('public.event.detail', $event->id) }}"
                    class="event-card {{ !$image ? 'no-image' : '' }}">

                    {{-- IMAGE --}}
                    @if($image)

                    <div class="event-card-media">

                        <img
                            src="{{ Storage::disk('public')->url($image->path) }}"
                            alt="{{ $event->title }}"
                            loading="lazy">

                    </div>

                    @endif


                    {{-- CONTENT --}}
                    <div class="event-card-body">

                        @if($event->category)

                        <span class="event-category">
                            {{ $event->category->name }}
                        </span>

                        @endif


                        <h2 class="event-title">
                            {{ $event->title }}
                        </h2>


                        @if($event->description)

                        <p class="event-desc">
                            {{ Str::limit(strip_tags(\App\Support\RichText::sanitize($event->description)), 160) }}
                        </p>

                        @endif


                        @if($event->campuses->count())

                        <div class="event-campuses">

                            @foreach($event->campuses as $campus)

                            <span class="campus-badge">
                                {{ $campus->name }}
                            </span>

                            @endforeach

                        </div>

                        @endif

                    </div>

                </a>

            </article>

            @endforeach

        </div>

        @else

        <div class="timeline-empty">

            <div class="timeline-empty-icon">

                <svg
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round">
                    <circle cx="12" cy="12" r="9" />
                    <path d="M12 7v5l3 2" />
                </svg>

            </div>

            <h3>
                No moments found
            </h3>

            <p>
                Nothing matches your current filters.
                Try widening your search to discover more events.
            </p>

            <a
                href="{{ route('public.timeline') }}"
                class="timeline-clear">
                Reset filters
            </a>

        </div>

        @endif

    </main>


    {{-- =====================================================
     END
====================================================== --}}

    @if($events->count() > 0)

    <section class="timeline-end">

        <div class="timeline-end-kicker">
            LIU · Digital Yearbook
        </div>

        <h2>
            The moments become the memories.
        </h2>

    </section>

    @endif
    

</div>

@endsection