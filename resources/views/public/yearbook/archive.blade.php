@extends('public.layout')

@section('title', 'Digital Yearbook Archive')

@section('extra-css')
<style>
    .archive-hero {
        background: var(--ink);
        color: var(--white);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        min-height: 420px;
        padding: 60px 24px;
        margin: 0 -32px;
        position: relative;
        overflow: hidden;
    }

    .archive-hero-mark {
        font: 700 22px "Merriweather", serif;
        letter-spacing: 6px;
        color: var(--red);
        text-transform: uppercase;
        margin-bottom: 18px;
    }

    .archive-hero h1 {
        color: var(--white);
        font-size: clamp(32px, 5vw, 56px);
        line-height: 1.1;
        max-width: 760px;
        margin: 0 0 18px;
    }

    .archive-hero p {
        color: #b9c6d6;
        font-size: 15px;
        max-width: 520px;
        margin: 0;
    }

    .edition-archive-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 22px;
        margin: 36px 0 60px;
    }

    .edition-archive-card {
        background: var(--white);
        border: 1px solid var(--line);
        border-top: 4px solid var(--red);
        padding: 26px;
        text-decoration: none;
        color: inherit;
        transition: transform .25s ease, box-shadow .25s ease;
        display: block;
    }

    .edition-archive-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 14px 28px rgba(19, 42, 58, .12);
    }

    .edition-archive-card.is-current {
        border-top-color: var(--ink);
    }

    .edition-archive-card .eyebrow {
        margin-bottom: 6px;
    }

    .edition-archive-card h3 {
        font-size: 22px;
        margin: 0 0 12px;
        color: var(--ink);
    }

    .edition-archive-meta {
        display: flex;
        gap: 18px;
        color: var(--ink-soft);
        font-size: 12px;
        margin-bottom: 18px;
    }

    .edition-archive-actions {
        display: flex;
        gap: 10px;
    }

    .edition-archive-actions .button-outline-archive {
        font-size: 10px;
        padding: 9px 14px;
        border: 1px solid var(--line);
        color: var(--ink);
        text-decoration: none;
        text-transform: uppercase;
        letter-spacing: .5px;
        font-weight: 700;
    }

    .edition-archive-actions .button-outline-archive:hover {
        border-color: var(--ink);
    }
</style>
@endsection

@section('content')
<div class="archive-hero">
    <div class="archive-hero-mark">LIU &middot; Digital Yearbook</div>
    <h1>Every year, archived and shared.</h1>
    <p>Browse past editions of the university yearbook — campus events, ceremonies, and the graduates who shaped each year.</p>
</div>

<div class="public-section">
    <div class="section-heading">
        <div>
            <p class="eyebrow">Editions</p>
            <h2>Yearbook archive</h2>
        </div>
    </div>

    <div class="edition-archive-grid">
        @forelse ($academicYears as $year)
        <a href="{{ route('public.book', $year->id) }}" class="edition-archive-card {{ $year->status === 'active' ? 'is-current' : '' }}">
            <p class="eyebrow">{{ $year->status === 'active' ? 'Current edition' : 'Archived' }}</p>
            <h3>{{ $year->title }}</h3>
            <div class="edition-archive-meta">
                <span>{{ $year->event_count }} events</span>
                <span>{{ $year->graduate_count }} graduates</span>
            </div>
            <div class="edition-archive-actions">
                <span class="button-outline-archive">Open yearbook</span>
                <a href="{{ route('public.book.pdf', $year->id) }}" class="button-outline-archive" onclick="event.stopPropagation()">Download PDF</a>
            </div>
        </a>
        @empty
        <p class="empty-state">No yearbook editions published yet.</p>
        @endforelse
    </div>
</div>
@endsection