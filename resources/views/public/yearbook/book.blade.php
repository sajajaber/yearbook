@extends('public.layout')

@section('title', $academicYear->title . ' Yearbook')

@section('extra-css')
    @vite('resources/css/pages/book.css')
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
                <p>{{ Str::limit(strip_tags(\App\Support\RichText::sanitize($event->description)), 160) }}</p>
            </div>
        </a>
        @empty
        <p class="empty-state">No published events for this edition yet.</p>
        @endforelse
    </section>
</div>
@endsection