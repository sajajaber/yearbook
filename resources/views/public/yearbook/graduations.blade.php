@extends('public.layout')

@section('title', 'Graduations')

@section('extra-css')
<style>
    .graduations-page {
        --g-accent: var(--red);
        --g-dark: var(--ink);
        --g-muted: var(--ink-soft);
        --g-line: var(--line);
        --g-paper: var(--paper);
        --g-white: var(--white);
        --g-ease: cubic-bezier(.16, 1, .3, 1);
    }

    .graduations-page .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 24px;
    }

    .graduations-page .section {
        padding: 64px 0;
    }

    /* =========================================================
       ARCHIVE-STYLE HERO
    ========================================================= */
    .graduations-hero {
        min-height: 620px;
        padding: 90px 7vw 100px;
        position: relative;
        overflow: hidden;
        isolation: isolate;
        display: flex;
        align-items: flex-end;
        background: var(--g-dark);
        color: #fff;
    }

    .graduations-hero::before,
    .graduations-hero::after {
        content: "";
        position: absolute;
        border: 1px solid rgba(255, 255, 255, .12);
        border-radius: 50%;
        pointer-events: none;
        opacity: 0;
        transform: scale(.72) rotate(-12deg);
        animation: graduationsHeroCircleIn 1.4s cubic-bezier(.16, 1, .3, 1) .15s forwards, graduationsHeroFloat 10s ease-in-out 1.8s infinite;
    }

    .graduations-hero::before {
        width: 520px;
        height: 520px;
        right: -160px;
        top: -180px;
    }

    .graduations-hero::after {
        width: 720px;
        height: 720px;
        right: -260px;
        top: -280px;
        border-color: rgba(255, 255, 255, .06);
        animation: graduationsHeroCircleIn 1.6s cubic-bezier(.16, 1, .3, 1) .25s forwards, graduationsHeroFloatLarge 13s ease-in-out 2s infinite;
    }

    .graduations-hero-grid {
        position: absolute;
        inset: 0;
        opacity: 0;
        background-image: linear-gradient(rgba(255, 255, 255, .5) 1px, transparent 1px), linear-gradient(90deg, rgba(255, 255, 255, .5) 1px, transparent 1px);
        background-size: 80px 80px;
        mask-image: linear-gradient(to right, transparent, black 60%);
        -webkit-mask-image: linear-gradient(to right, transparent, black 60%);
        pointer-events: none;
        transform: scale(1.06);
        animation: graduationsHeroGridIn 1.4s ease .1s forwards;
    }

    .graduations-hero-glow {
        position: absolute;
        width: 500px;
        height: 500px;
        right: 10%;
        bottom: -350px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(255, 176, 52, .2), transparent 68%);
        filter: blur(10px);
        opacity: 0;
        animation: graduationsHeroGlowIn 1.5s ease .45s forwards;
        pointer-events: none;
    }

    .graduations-hero .container {
        position: relative;
        z-index: 2;
        width: 100%;
        max-width: 1050px;
        padding: 0;
        margin: 0;
    }

    .graduations-hero-content {
        max-width: 1050px;
    }

    .graduations-kicker {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 30px;
        color: var(--g-accent);
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 3px;
        text-transform: uppercase;
        opacity: 0;
        transform: translateY(20px);
        animation: graduationsHeroReveal .8s cubic-bezier(.16, 1, .3, 1) .05s forwards;
    }

    .graduations-kicker::before {
        content: "";
        width: 42px;
        height: 2px;
        background: currentColor;
        transform-origin: left;
        transform: scaleX(0);
        animation: graduationsHeroLineIn .7s cubic-bezier(.16, 1, .3, 1) .4s forwards;
    }

    .graduations-hero h1 {
        max-width: 950px;
        margin: 0;
        color: #fff;
        font-size: clamp(58px, 9vw, 132px);
        line-height: .88;
        letter-spacing: -5px;
        font-weight: 800;
        opacity: 0;
        transform: translateY(55px);
        animation: graduationsHeroTitleIn 1.05s cubic-bezier(.16, 1, .3, 1) .12s forwards;
    }

    .graduations-hero h1 em {
        display: block;
        margin-top: 16px;
        color: #aebdcd;
        font-family: "Merriweather", serif;
        font-weight: 400;
        font-size: .56em;
        line-height: 1.2;
        letter-spacing: -2px;
        opacity: 0;
        transform: translateY(25px);
        animation: graduationsHeroReveal .9s cubic-bezier(.16, 1, .3, 1) .38s forwards;
    }

    .graduations-hero-bottom {
        margin-top: 55px;
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        gap: 40px;
        max-width: 850px;
        opacity: 0;
        transform: translateY(25px);
        animation: graduationsHeroReveal .9s cubic-bezier(.16, 1, .3, 1) .52s forwards;
    }

    .graduations-hero-copy {
        max-width: 510px;
        margin: 0;
        color: #b9c6d6;
        font-size: 15px;
        line-height: 1.8;
    }

    .graduations-hero-count {
        flex-shrink: 0;
        text-align: right;
        opacity: 0;
        transform: translateX(25px);
        animation: graduationsHeroCountIn .8s cubic-bezier(.16, 1, .3, 1) .68s forwards;
    }

    .graduations-hero-count strong {
        display: block;
        color: #fff;
        font-size: 44px;
        line-height: 1;
        font-weight: 700;
    }

    .graduations-hero-count span {
        display: block;
        margin-top: 7px;
        color: #8293a7;
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 2px;
    }

    @keyframes graduationsHeroReveal {
        from {
            opacity: 0;
            transform: translateY(30px)
        }

        to {
            opacity: 1;
            transform: translateY(0)
        }
    }

    @keyframes graduationsHeroTitleIn {
        from {
            opacity: 0;
            transform: translateY(55px)
        }

        to {
            opacity: 1;
            transform: translateY(0)
        }
    }

    @keyframes graduationsHeroCountIn {
        from {
            opacity: 0;
            transform: translateX(25px)
        }

        to {
            opacity: 1;
            transform: translateX(0)
        }
    }

    @keyframes graduationsHeroGridIn {
        from {
            opacity: 0;
            transform: scale(1.06)
        }

        to {
            opacity: .08;
            transform: scale(1)
        }
    }

    @keyframes graduationsHeroCircleIn {
        from {
            opacity: 0;
            transform: scale(.72) rotate(-12deg)
        }

        to {
            opacity: 1;
            transform: scale(1) rotate(0)
        }
    }

    @keyframes graduationsHeroLineIn {
        from {
            transform: scaleX(0)
        }

        to {
            transform: scaleX(1)
        }
    }

    @keyframes graduationsHeroFloat {

        0%,
        100% {
            transform: translate3d(0, 0, 0)
        }

        50% {
            transform: translate3d(-18px, 20px, 0)
        }
    }

    @keyframes graduationsHeroFloatLarge {

        0%,
        100% {
            transform: translate3d(0, 0, 0)
        }

        50% {
            transform: translate3d(-25px, 15px, 0)
        }
    }

    @keyframes graduationsHeroGlowIn {
        from {
            opacity: 0;
            transform: scale(.7)
        }

        to {
            opacity: 1;
            transform: scale(1)
        }
    }

    /* Filter bar */
    .graduations-filters {
        position: relative;
        z-index: 3;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 10px;
        margin: -28px 0 40px;
        padding: 16px;
        border: 1px solid var(--g-line);
        border-radius: 18px;
        background: var(--g-white);
        box-shadow: 0 18px 40px -30px rgba(0, 42, 92, .35);
    }

    .graduations-filters form {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 10px;
        width: 100%;
    }

    .filter-select {
        appearance: none;
        min-width: 170px;
        flex: 1 1 170px;
        padding: 11px 36px 11px 16px;
        border: 1px solid var(--g-line);
        border-radius: 999px;
        background: var(--g-paper) url('data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 24 24%22 fill=%22none%22 stroke=%22%2364748b%22 stroke-width=%222%22 stroke-linecap=%22round%22%3E%3Cpath d=%22m6 9 6 6 6-6%22/%3E%3C/svg%3E') no-repeat right 14px center;
        background-size: 12px;
        color: var(--g-dark);
        font: 600 .85rem "Inter", sans-serif;
        cursor: pointer;
        transition: border-color .18s ease, box-shadow .18s ease;
    }

    .filter-select:hover {
        border-color: #b9c9dc;
    }

    .filter-select:focus {
        outline: none;
        border-color: var(--g-accent);
        box-shadow: 0 0 0 3px rgba(255, 176, 52, .18);
    }

    .graduations-clear {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 11px 18px;
        border: 1px solid var(--g-line);
        border-radius: 999px;
        background: var(--g-paper);
        color: var(--g-dark);
        font: 700 .78rem "Inter", sans-serif;
        text-decoration: none;
        white-space: nowrap;
        transition: background .18s ease, border-color .18s ease;
    }

    .graduations-clear:hover {
        background: var(--g-dark);
        border-color: var(--g-dark);
        color: var(--g-white);
    }

    .graduations-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
        gap: 28px;
    }

    .graduation-card-link {
        display: block;
        color: inherit;
        text-decoration: none;
    }

    .graduation-card {
        overflow: hidden;
        height: 100%;
        border: 1px solid var(--g-line);
        border-radius: 20px;
        background: var(--g-white);
        box-shadow: 0 1px 2px rgba(0, 42, 92, .04);
        transition: transform .4s var(--g-ease), box-shadow .4s var(--g-ease), border-color .4s ease;
    }

    .graduation-card-link:hover .graduation-card {
        transform: translateY(-4px);
        border-color: #c9d8e8;
        box-shadow: 0 28px 48px -28px rgba(0, 42, 92, .32);
    }

    .graduation-card-image {
        position: relative;
        aspect-ratio: 16/10;
        overflow: hidden;
        background: linear-gradient(135deg, var(--g-dark) 0%, #1a3f7f 100%);
    }

    .graduation-card-image img {
        width: 100%;
        height: 100%;
        display: block;
        object-fit: cover;
        transition: transform .6s var(--g-ease);
    }

    .graduation-card-link:hover .graduation-card-image img {
        transform: scale(1.04);
    }

    .graduation-card-date {
        position: absolute;
        top: 14px;
        left: 14px;
        padding: 6px 12px;
        border-radius: 999px;
        background: rgba(255, 255, 255, .92);
        backdrop-filter: blur(4px);
        color: var(--g-dark);
        font: 800 .68rem "Inter", sans-serif;
        letter-spacing: .3px;
    }

    .graduation-card-body {
        padding: 22px 24px 26px;
    }

    .graduation-card-title {
        margin: 0 0 8px;
        color: var(--g-dark);
        font-family: "Merriweather", serif;
        font-size: 1.2rem;
        line-height: 1.3;
    }

    .graduation-card-venue {
        display: flex;
        align-items: center;
        gap: 6px;
        margin: 0 0 12px;
        color: var(--g-accent);
        font-size: .74rem;
        font-weight: 700;
        letter-spacing: .3px;
        text-transform: uppercase;
    }

    .graduation-card-desc {
        margin: 0;
        color: var(--g-muted);
        font-size: .92rem;
        line-height: 1.65;
    }

    .graduations-empty {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 10px;
        padding: 70px 24px;
        border: 1px dashed var(--g-line);
        border-radius: 24px;
        background: linear-gradient(180deg, var(--g-white) 0%, var(--g-paper) 100%);
        text-align: center;
    }

    .graduations-empty svg {
        margin-bottom: 6px;
        color: var(--g-accent);
    }

    .graduations-empty h3 {
        margin: 0;
        color: var(--g-dark);
        font-size: 1.2rem;
        font-weight: 800;
    }

    .graduations-empty p {
        max-width: 380px;
        margin: 0;
        color: var(--g-muted);
        line-height: 1.6;
    }

    .pagination {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 7px;
        margin: 42px 0 20px;
    }

    .pagination a,
    .pagination span {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 39px;
        height: 39px;
        padding: 0 10px;
        border: 1px solid var(--g-line);
        border-radius: 9px;
        background: #fff;
        color: var(--g-dark);
        text-decoration: none;
        font-size: .68rem;
        font-weight: 800;
    }

    .pagination .active {
        background: var(--g-dark);
        border-color: var(--g-dark);
        color: #fff;
    }

    @media(max-width:850px) {
        .graduations-hero {
            min-height: 560px;
            padding: 72px 6vw 78px;
        }

        .graduations-hero h1 {
            font-size: clamp(54px, 11vw, 100px);
        }

        .graduations-hero-bottom {
            display: block;
            margin-top: 40px;
            max-width: 700px;
        }

        .graduations-hero-count {
            text-align: left;
            margin-top: 24px;
        }
    }

    @media(max-width:640px) {
        .graduations-hero {
            min-height: 540px;
            padding: 60px 24px 62px;
        }

        .graduations-hero h1 {
            letter-spacing: -3px;
        }

        .graduations-hero h1 em {
            letter-spacing: -1px;
        }

        .graduations-hero-bottom {
            margin-top: 34px;
        }

        .graduations-hero-count strong {
            font-size: 38px;
        }

        .graduations-filters {
            margin-top: -18px;
        }

        .graduations-filters form {
            flex-direction: column;
            align-items: stretch;
        }

        .filter-select {
            width: 100%;
        }

        .graduations-grid {
            grid-template-columns: 1fr;
        }
    }

    @media(prefers-reduced-motion:reduce) {
        .graduations-page * {
            transition: none !important;
            animation: none !important;
        }
    }
</style>
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