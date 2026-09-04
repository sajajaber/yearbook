@extends('public.layout')

@section('title', $academicYear->title . ' Yearbook')

@section('extra-css')
<style>
    .book-hero {
        background: var(--ink);
        color: var(--white);
        text-align: center;
        padding: 70px 24px 50px;
        margin: 0 -32px;
    }

    .book-hero .eyebrow-light {
        color: #ffce6b;
    }

    .book-hero h1 {
        color: var(--white);
        font-size: clamp(30px, 4.5vw, 48px);
        margin: 10px 0 8px;
    }

    .book-hero p {
        color: #b9c6d6;
        font-size: 14px;
        margin: 0 0 22px;
    }

    .book-hero .button {
        margin: 0 5px;
    }

    .book-nav {
        position: sticky;
        top: 0;
        z-index: 20;
        background: var(--white);
        border-bottom: 1px solid var(--line);
        display: flex;
        justify-content: center;
        gap: 6px;
        padding: 14px 20px;
        flex-wrap: wrap;
    }

    .book-nav button {
        background: transparent;
        border: 0;
        padding: 8px 16px;
        font: 700 11px "Inter", sans-serif;
        letter-spacing: .6px;
        text-transform: uppercase;
        color: var(--ink-soft);
        cursor: pointer;
        border-bottom: 2px solid transparent;
        transition: color .2s ease, border-color .2s ease;
    }

    .book-nav button:hover {
        color: var(--ink);
    }

    .book-nav button.is-active {
        color: var(--ink);
        border-bottom-color: var(--red);
    }

    .book-section {
        max-width: 1000px;
        margin: 0 auto;
        padding: 50px 24px 70px;
    }

    .dedication-panel {
        text-align: center;
        max-width: 620px;
        margin: 20px auto 0;
        padding: 50px 20px;
    }

    .dedication-panel p {
        font: italic 20px/1.7 "Merriweather", serif;
        color: var(--ink);
    }

    .dedication-panel .rule {
        width: 60px;
        border-top: 3px solid var(--red);
        margin: 24px auto;
    }

    .school-block {
        margin-bottom: 48px;
    }

    .school-block-heading {
        display: flex;
        align-items: baseline;
        gap: 12px;
        border-bottom: 2px solid var(--ink);
        padding-bottom: 10px;
        margin-bottom: 22px;
    }

    .school-block-heading h3 {
        color: var(--ink);
        font: 700 22px "Merriweather", serif;
        margin: 0;
    }

    .school-block-heading span {
        color: var(--ink-soft);
        font-size: 12px;
    }

    .student-photo-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 20px;
        margin-bottom: 22px;
    }

    .student-photo-card {
        text-align: center;
        text-decoration: none;
        color: inherit;
        display: block;
    }

    .student-photo-frame {
        width: 100%;
        aspect-ratio: 3/4;
        overflow: hidden;
        border-radius: 8px;
        background: linear-gradient(135deg, var(--ink) 0%, #1a3f7f 100%);
        margin-bottom: 10px;
        transition: transform .3s ease, box-shadow .3s ease;
    }

    .student-photo-card:hover .student-photo-frame {
        transform: translateY(-4px);
        box-shadow: 0 12px 22px rgba(19, 42, 58, .18);
    }

    .student-photo-frame img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .student-photo-frame .initial {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font: 700 32px "Merriweather", serif;
    }

    .student-photo-card .student-name {
        font-size: 13px;
        font-weight: 700;
        color: var(--ink);
    }

    .student-photo-card .student-major {
        font-size: 11px;
        color: var(--ink-soft);
    }

    .named-only-list {
        border-top: 1px dashed var(--line);
        padding-top: 16px;
        color: var(--ink-soft);
        font-size: 12px;
        line-height: 2;
    }

    .named-only-list strong {
        color: var(--ink);
        display: block;
        margin-bottom: 6px;
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: .6px;
    }

    .chrono-event {
        display: grid;
        grid-template-columns: 90px 1fr;
        gap: 24px;
        padding: 22px 0;
        border-bottom: 1px solid var(--line);
        text-decoration: none;
        color: inherit;
    }

    .chrono-event-date {
        text-align: right;
        color: var(--red);
        font: 700 26px/1 "Merriweather", serif;
    }

    .chrono-event-date small {
        display: block;
        color: var(--ink-soft);
        font-size: 10px;
        margin-top: 4px;
        text-transform: uppercase;
    }

    .chrono-event h4 {
        margin: 0 0 6px;
        color: var(--ink);
        font-size: 17px;
    }

    .chrono-event p {
        margin: 0;
        color: var(--ink-soft);
        font-size: 13px;
    }

    [x-cloak] {
        display: none !important;
    }
</style>
@endsection

@section('content')
<div class="book-hero">
    <p class="eyebrow eyebrow-light">{{ $academicYear->title }}</p>
    <h1>{{ $academicYear->title }} Yearbook</h1>
    @if ($graduation)
    <p>Ceremony held {{ \Carbon\Carbon::parse($graduation->ceremony_date)->format('F j, Y') }}{{ $graduation->venue ? ' at ' . $graduation->venue : '' }}</p>
    @endif
    <a href="{{ route('public.book.pdf', $academicYear->id) }}" class="button button-red">Download PDF <span aria-hidden="true">&darr;</span></a>
    <a href="{{ route('public.archive') }}" class="button button-outline-public">Back to archive</a>
</div>

<div x-data="{ tab: 'dedication' }">
    <nav class="book-nav">
        <button :class="{ 'is-active': tab === 'dedication' }" @click="tab = 'dedication'">Dedication</button>
        <button :class="{ 'is-active': tab === 'undergraduates' }" @click="tab = 'undergraduates'">Undergraduates</button>
        <button :class="{ 'is-active': tab === 'graduates' }" @click="tab = 'graduates'">Graduates</button>
        <button :class="{ 'is-active': tab === 'events' }" @click="tab = 'events'">Events</button>
    </nav>

    {{-- Dedication --}}
    <section class="book-section" x-show="tab === 'dedication'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
        <div class="dedication-panel">
            <div class="rule"></div>
            <p>{{ $academicYear->dedication ?: 'To every student, staff member, and moment that made this year unforgettable — this edition is dedicated to you.' }}</p>
            <div class="rule"></div>
        </div>
    </section>

    {{-- Undergraduates --}}
    <section class="book-section" x-show="tab === 'undergraduates'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
        @forelse ($undergraduates as $schoolName => $group)
        @include('public.yearbook._school-block', ['schoolName' => $schoolName, 'group' => $group])
        @empty
        <p class="empty-state">No undergraduate profiles published for this edition yet.</p>
        @endforelse
    </section>

    {{-- Graduates --}}
    <section class="book-section" x-show="tab === 'graduates'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
        @forelse ($graduates as $schoolName => $group)
        @include('public.yearbook._school-block', ['schoolName' => $schoolName, 'group' => $group])
        @empty
        <p class="empty-state">No graduate profiles published for this edition yet.</p>
        @endforelse
    </section>

    {{-- Events --}}
    <section class="book-section" x-show="tab === 'events'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
        @forelse ($events as $event)
        <a href="{{ route('public.event.detail', $event->id) }}" class="chrono-event">
            <div class="chrono-event-date">
                {{ \Carbon\Carbon::parse($event->event_date)->format('d') }}
                <small>{{ \Carbon\Carbon::parse($event->event_date)->format('M Y') }}</small>
            </div>
            <div>
                <h4>{{ $event->title }}</h4>
                <p>{{ Str::limit($event->description, 160) }}</p>
            </div>
        </a>
        @empty
        <p class="empty-state">No published events for this edition yet.</p>
        @endforelse
    </section>
</div>
@endsection