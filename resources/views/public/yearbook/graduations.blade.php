@extends('public.layout')

@section('title', 'Graduations')

@section('extra-css')
<style>
    /* =========================================================
       GRADUATIONS — ARCHIVE-INSPIRED EDITORIAL SYSTEM
    ========================================================= */
    .graduations-page {
        background: #f4f1eb;
        margin: 0 -32px;
        color: var(--ink);
    }

    .graduations-hero {
        min-height: 620px;
        padding: 90px 7vw 92px;
        position: relative;
        overflow: hidden;
        isolation: isolate;
        display: flex;
        align-items: flex-end;
        background: var(--ink);
        color: #fff;
    }

    .graduations-hero::before,
    .graduations-hero::after {
        content: '';
        position: absolute;
        border: 1px solid rgba(255,255,255,.12);
        border-radius: 50%;
        pointer-events: none;
        opacity: 0;
        transform: scale(.72) rotate(-12deg);
        animation: graduationCircleIn 1.5s cubic-bezier(.16,1,.3,1) .12s forwards,
                   graduationFloat 11s ease-in-out 1.8s infinite;
    }

    .graduations-hero::before {
        width: 520px;
        height: 520px;
        right: -150px;
        top: -185px;
    }

    .graduations-hero::after {
        width: 760px;
        height: 760px;
        right: -280px;
        top: -325px;
        border-color: rgba(255,255,255,.055);
        animation-duration: 1.7s, 14s;
        animation-delay: .22s, 2s;
    }

    .graduations-hero-grid {
        position: absolute;
        inset: 0;
        opacity: 0;
        pointer-events: none;
        background-image:
            linear-gradient(rgba(255,255,255,.55) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255,255,255,.55) 1px, transparent 1px);
        background-size: 80px 80px;
        mask-image: linear-gradient(to right, transparent, black 58%);
        -webkit-mask-image: linear-gradient(to right, transparent, black 58%);
        transform: scale(1.06);
        animation: graduationGridIn 1.4s ease .08s forwards;
    }

    .graduations-hero-glow {
        position: absolute;
        width: 430px;
        height: 430px;
        right: 12%;
        bottom: -260px;
        border-radius: 50%;
        background: rgba(255,176,52,.15);
        filter: blur(80px);
        pointer-events: none;
        animation: graduationGlow 8s ease-in-out 1.4s infinite alternate;
    }

    .graduations-hero-content {
        position: relative;
        z-index: 2;
        width: 100%;
        max-width: 1180px;
        margin: 0 auto;
    }

    .graduations-kicker {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 28px;
        color: var(--red);
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 3px;
        text-transform: uppercase;
        opacity: 0;
        transform: translateY(20px);
        animation: graduationReveal .8s cubic-bezier(.16,1,.3,1) .05s forwards;
    }

    .graduations-kicker::before {
        content: '';
        width: 42px;
        height: 2px;
        background: currentColor;
        transform-origin: left;
        transform: scaleX(0);
        animation: graduationLineIn .7s cubic-bezier(.16,1,.3,1) .4s forwards;
    }

    .graduations-hero h1 {
        margin: 0;
        max-width: 1050px;
        color: #fff;
        font-size: clamp(58px, 9vw, 128px);
        line-height: .86;
        letter-spacing: -5px;
        font-weight: 900;
        opacity: 0;
        transform: translateY(55px);
        animation: graduationTitleIn 1.05s cubic-bezier(.16,1,.3,1) .12s forwards;
    }

    .graduations-hero h1 em {
        display: block;
        margin-top: 14px;
        color: #aebdcd;
        font-family: 'Merriweather', serif;
        font-size: .52em;
        line-height: 1.2;
        font-weight: 400;
        letter-spacing: -2px;
        opacity: 0;
        transform: translateY(25px);
        animation: graduationReveal .9s cubic-bezier(.16,1,.3,1) .38s forwards;
    }

    .graduations-hero-bottom {
        max-width: 900px;
        margin-top: 52px;
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        gap: 40px;
        opacity: 0;
        transform: translateY(25px);
        animation: graduationReveal .9s cubic-bezier(.16,1,.3,1) .52s forwards;
    }

    .graduations-hero-copy {
        max-width: 530px;
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
        animation: graduationCountIn .8s cubic-bezier(.16,1,.3,1) .68s forwards;
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

    @keyframes graduationReveal { from { opacity:0; transform:translateY(30px); } to { opacity:1; transform:translateY(0); } }
    @keyframes graduationTitleIn { from { opacity:0; transform:translateY(55px); } to { opacity:1; transform:translateY(0); } }
    @keyframes graduationCountIn { from { opacity:0; transform:translateX(25px); } to { opacity:1; transform:translateX(0); } }
    @keyframes graduationGridIn { from { opacity:0; transform:scale(1.06); } to { opacity:.08; transform:scale(1); } }
    @keyframes graduationCircleIn { from { opacity:0; transform:scale(.72) rotate(-12deg); } to { opacity:1; transform:scale(1) rotate(0); } }
    @keyframes graduationLineIn { from { transform:scaleX(0); } to { transform:scaleX(1); } }
    @keyframes graduationFloat { 0%,100% { transform:translate3d(0,0,0); } 50% { transform:translate3d(-18px,20px,0); } }
    @keyframes graduationGlow { from { transform:translate3d(0,0,0) scale(.9); } to { transform:translate3d(-35px,-20px) scale(1.08); } }

    .graduations-discovery {
        position: relative;
        z-index: 3;
        margin: -22px 7vw 56px;
    }

    .graduations-filter-shell {
        padding: 8px;
        border: 1px solid rgba(0,42,92,.08);
        border-radius: 18px;
        background: rgba(255,255,255,.97);
        box-shadow: 0 18px 45px rgba(15,23,42,.09);
        backdrop-filter: blur(12px);
    }

    .graduations-filter-form {
        display: grid;
        grid-template-columns: repeat(2,minmax(0,1fr)) auto;
        gap: 7px;
    }

    .graduations-filter-form select,
    .graduations-filter-clear {
        box-sizing: border-box;
        width: 100%;
        min-height: 52px;
        padding: 0 14px;
        border: 1px solid #edf1f5;
        border-radius: 11px;
        background: #f8fafc;
        color: var(--ink);
        font-size: .74rem;
        font-weight: 700;
    }

    .graduations-filter-form select:focus {
        outline: none;
        border-color: #d7e0e8;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(255,176,52,.08);
    }

    .graduations-filter-clear {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 90px;
        text-decoration: none;
        background: var(--ink);
        color: #fff;
        transition: transform .35s cubic-bezier(.16,1,.3,1), background .25s ease, box-shadow .35s ease;
    }

    .graduations-filter-clear:hover {
        transform: translateY(-2px);
        background: #123f6e;
        box-shadow: 0 10px 24px rgba(0,42,92,.14);
    }

    .graduations-content {
        padding: 0 7vw 80px;
    }

    .graduations-section-heading {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 22px;
    }

    .graduations-section-label {
        margin-bottom: 7px;
        color: #b48738;
        font-size: .65rem;
        font-weight: 900;
        letter-spacing: 2px;
        text-transform: uppercase;
    }

    .graduations-section-heading h2 {
        margin: 0;
        color: var(--ink);
        font-size: clamp(1.8rem,3vw,2.5rem);
        line-height: 1;
        letter-spacing: -1px;
    }

    .graduations-grid {
        display: grid;
        grid-template-columns: repeat(2,minmax(0,1fr));
        gap: 20px;
    }

    .graduation-card-link {
        display: block;
        color: inherit;
        text-decoration: none;
    }

    .graduation-card {
        height: 100%;
        overflow: hidden;
        border: 1px solid #dfe6ed;
        border-radius: 18px;
        background: #fff;
        box-shadow: 0 6px 24px rgba(15,23,42,.045);
        transition: transform .5s cubic-bezier(.16,1,.3,1), box-shadow .45s ease, border-color .3s ease;
    }

    .graduation-card-link:hover .graduation-card {
        transform: translateY(-6px);
        border-color: rgba(255,176,52,.45);
        box-shadow: 0 20px 45px rgba(15,23,42,.09);
    }

    .graduation-card-image {
        position: relative;
        height: 290px;
        overflow: hidden;
        background: linear-gradient(135deg,#002a5c,#123f6e);
    }

    .graduation-card-image::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(0,27,61,.58), transparent 55%);
        pointer-events: none;
    }

    .graduation-card-image img {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform .75s cubic-bezier(.16,1,.3,1), filter .5s ease;
    }

    .graduation-card-link:hover .graduation-card-image img {
        transform: scale(1.045);
        filter: saturate(1.06);
    }

    .graduation-card-body {
        padding: 22px;
    }

    .graduation-card-kicker {
        margin-bottom: 9px;
        color: #b48738;
        font-size: .62rem;
        font-weight: 900;
        letter-spacing: 1.8px;
        text-transform: uppercase;
    }

    .graduation-card-title {
        margin: 0 0 10px;
        color: var(--ink);
        font-family: 'Merriweather',serif;
        font-size: 1.35rem;
        line-height: 1.25;
    }

    .graduation-card-meta {
        margin: 0;
        color: #64748b;
        font-size: .76rem;
        line-height: 1.7;
    }

    .graduation-card-description {
        margin: 12px 0 0;
        color: #718096;
        font-size: .72rem;
        line-height: 1.7;
    }

    .graduations-empty {
        padding: 70px 30px;
        text-align: center;
        border: 1px dashed #d8e1e9;
        border-radius: 18px;
        background: #fff;
    }

    .graduations-empty p {
        margin: 0;
        color: #64748b;
        font-size: .85rem;
    }

    @media (max-width: 760px) {
        .graduations-page { margin: 0 -16px; }
        .graduations-hero { min-height: 540px; padding: 70px 24px 62px; }
        .graduations-hero h1 { letter-spacing: -2.5px; }
        .graduations-hero-bottom { flex-direction: column; align-items: flex-start; gap: 20px; }
        .graduations-hero-count { text-align: left; }
        .graduations-discovery { margin-left: 24px; margin-right: 24px; }
        .graduations-content { padding-left: 24px; padding-right: 24px; }
        .graduations-filter-form { grid-template-columns: 1fr; }
        .graduations-grid { grid-template-columns: 1fr; }
    }

    @media (max-width: 480px) {
        .graduations-hero { min-height: 500px; padding: 58px 20px 48px; }
        .graduations-hero h1 { font-size: clamp(50px,15vw,78px); }
        .graduations-discovery { margin-left: 20px; margin-right: 20px; }
        .graduations-content { padding-left: 20px; padding-right: 20px; }
    }

    @media (prefers-reduced-motion: reduce) {
        .graduations-page *,
        .graduations-page::before,
        .graduations-page::after { animation: none !important; transition: none !important; }
    }
</style>
@endsection

@section('content')
<div class="graduations-page">
    <section class="graduations-hero">
        <div class="graduations-hero-grid"></div>
        <div class="graduations-hero-glow"></div>
        <div class="graduations-hero-content">
            <div class="graduations-kicker">Ceremonies & milestones</div>
            <h1>Graduations<em>the moments that become memories.</em></h1>
            <div class="graduations-hero-bottom">
                <p class="graduations-hero-copy">Explore graduation ceremonies across the university — from the people who crossed the stage to the places and moments that defined each celebration.</p>
                <div class="graduations-hero-count">
                    <strong>{{ $graduations->total() }}</strong>
                    <span>{{ Str::plural('ceremony', $graduations->total()) }}</span>
                </div>
            </div>
        </div>
    </section>

    <section class="graduations-discovery">
        <div class="graduations-filter-shell">
            <form method="GET" action="{{ route('public.graduations') }}" class="graduations-filter-form">
                <select name="year" onchange="this.form.submit()">
                    <option value="">All Years</option>
                    @foreach($years as $year)
                    <option value="{{ $year }}" @selected($year == request('year'))>{{ $year }}</option>
                    @endforeach
                </select>
                <select name="campus" onchange="this.form.submit()">
                    <option value="">All Campuses</option>
                    @foreach($campuses as $campus)
                    <option value="{{ $campus->id }}" @selected($campus->id == request('campus'))>{{ $campus->name }}</option>
                    @endforeach
                </select>
                @if(request('year') || request('campus'))
                    <a href="{{ route('public.graduations') }}" class="graduations-filter-clear">Clear filters</a>
                @endif
            </form>
        </div>
    </section>

    <main class="graduations-content">
        <div class="graduations-section-heading">
            <div>
                <div class="graduations-section-label">The ceremony archive</div>
                <h2>Graduation editions</h2>
            </div>
        </div>

        @if($graduations->count() > 0)
            <div class="graduations-grid">
                @foreach($graduations as $graduation)
                <a href="{{ route('public.graduation.detail', $graduation->id) }}" class="graduation-card-link">
                    <article class="graduation-card">
                        @php($image = $graduation->media->first())
                        <div class="graduation-card-image">
                            @if($image)
                                <img src="{{ Storage::disk('public')->url($image->path) }}" alt="Graduation ceremony">
                            @endif
                        </div>
                        <div class="graduation-card-body">
                            <div class="graduation-card-kicker">Graduation ceremony</div>
                            <h3 class="graduation-card-title">{{ \Carbon\Carbon::parse($graduation->ceremony_date)->format('F d, Y') }}</h3>
                            <p class="graduation-card-meta"><strong>Venue:</strong> {{ $graduation->venue }}</p>
                            @if($graduation->description)
                                <p class="graduation-card-description">{{ Str::limit($graduation->description, 120) }}</p>
                            @endif
                        </div>
                    </article>
                </a>
                @endforeach
            </div>

            @if($graduations->hasPages())
            <div class="pagination" style="margin:40px 0 20px;">
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
                <p>No graduations found.</p>
            </div>
        @endif
    </main>
</div>
@endsection
