@extends('public.layout')

@section('title', 'Graduates | LIU Digital Yearbook')

@section('extra-css')
<style>
    .graduates-page {
        --g-ink: var(--ink, #002a5c);
        --g-gold: #d7ad59;
        --g-muted: #718096;
        --g-line: #e2e8ef;
        --g-ease: cubic-bezier(.16, 1, .3, 1);
        background: #f4f1eb;
    }

    .graduates-hero {
        min-height: 620px;
        padding: 90px 7vw 100px;
        position: relative;
        overflow: hidden;
        isolation: isolate;
        display: flex;
        align-items: flex-end;
        background: var(--g-ink);
        color: #fff;
    }

    .graduates-hero::before,
    .graduates-hero::after {
        content: "";
        position: absolute;
        border: 1px solid rgba(255,255,255,.12);
        border-radius: 50%;
        pointer-events: none;
        opacity: 0;
        animation: graduatesCircleIn 1.4s var(--g-ease) .15s forwards, graduatesFloat 10s ease-in-out 1.8s infinite;
    }

    .graduates-hero::before { width: 520px; height: 520px; right: -160px; top: -180px; }
    .graduates-hero::after { width: 720px; height: 720px; right: -260px; top: -280px; border-color: rgba(255,255,255,.06); animation-delay: .25s, 2s; }

    .graduates-hero-grid {
        position: absolute;
        inset: 0;
        opacity: 0;
        background-image: linear-gradient(rgba(255,255,255,.5) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.5) 1px, transparent 1px);
        background-size: 80px 80px;
        mask-image: linear-gradient(to right, transparent, black 60%);
        -webkit-mask-image: linear-gradient(to right, transparent, black 60%);
        pointer-events: none;
        animation: graduatesGridIn 1.4s ease .1s forwards;
    }

    .graduates-hero-glow {
        position: absolute;
        width: 480px;
        height: 480px;
        right: 12%;
        bottom: -340px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(255,176,52,.2), transparent 68%);
        filter: blur(10px);
        opacity: 0;
        animation: graduatesGlowIn 1.5s ease .45s forwards;
    }

    .graduates-hero-inner { position: relative; z-index: 2; width: 100%; max-width: 1180px; margin: 0 auto; }
    .graduates-hero-content { max-width: 1050px; }

    .graduates-kicker {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 30px;
        color: var(--red, #ffb034);
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 3px;
        text-transform: uppercase;
        opacity: 0;
        transform: translateY(20px);
        animation: graduatesReveal .8s var(--g-ease) .05s forwards;
    }

    .graduates-kicker::before { content: ""; width: 42px; height: 2px; background: currentColor; }

    .graduates-hero h1 {
        margin: 0;
        max-width: 950px;
        color: #fff;
        font-size: clamp(58px, 9vw, 132px);
        line-height: .88;
        letter-spacing: -5px;
        font-weight: 800;
        opacity: 0;
        transform: translateY(55px);
        animation: graduatesTitleIn 1.05s var(--g-ease) .12s forwards;
    }

    .graduates-hero h1 em {
        display: block;
        margin-top: 16px;
        color: #aebdcd;
        font-family: "Merriweather", serif;
        font-weight: 400;
        font-size: .56em;
        line-height: 1.2;
        letter-spacing: -2px;
        opacity: 0;
        animation: graduatesReveal .9s var(--g-ease) .38s forwards;
    }

    .graduates-hero-bottom {
        margin-top: 55px;
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        gap: 40px;
        max-width: 850px;
        opacity: 0;
        animation: graduatesReveal .9s var(--g-ease) .52s forwards;
    }

    .graduates-hero-copy { max-width: 510px; margin: 0; color: #b9c6d6; font-size: 15px; line-height: 1.8; }
    .graduates-hero-count { flex-shrink: 0; text-align: right; }
    .graduates-hero-count strong { display: block; color: #fff; font-size: 44px; line-height: 1; }
    .graduates-hero-count span { display: block; margin-top: 7px; color: #8293a7; font-size: 10px; text-transform: uppercase; letter-spacing: 2px; }

    @keyframes graduatesReveal { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes graduatesTitleIn { from { opacity: 0; transform: translateY(55px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes graduatesGridIn { from { opacity: 0; transform: scale(1.06); } to { opacity: .08; transform: scale(1); } }
    @keyframes graduatesCircleIn { from { opacity: 0; transform: scale(.72) rotate(-12deg); } to { opacity: 1; transform: scale(1) rotate(0); } }
    @keyframes graduatesFloat { 0%,100% { transform: translate3d(0,0,0); } 50% { transform: translate3d(-18px,20px,0); } }
    @keyframes graduatesGlowIn { from { opacity: 0; transform: scale(.7); } to { opacity: 1; transform: scale(1); } }

    .graduates-page > main.container { width: 100%; max-width: 1240px; margin: 0 auto; padding: 0 32px; }
    .graduates-discovery { position: relative; z-index: 3; margin: -18px 0 56px; }

    .graduates-filter-shell {
        padding: 8px;
        border: 1px solid rgba(0,42,92,.08);
        border-radius: 18px;
        background: rgba(255,255,255,.97);
        box-shadow: 0 14px 40px rgba(15,23,42,.055);
        backdrop-filter: blur(12px);
    }

    .graduates-filter-form { display: grid; grid-template-columns: minmax(190px,1.5fr) repeat(4,minmax(115px,1fr)) auto; gap: 7px; }
    .graduates-filter-field { min-width: 0; }
    .graduates-filter-field input,
    .graduates-filter-field select {
        box-sizing: border-box;
        width: 100%;
        height: 52px;
        padding: 0 13px;
        border: 1px solid #edf1f5;
        border-radius: 11px;
        background: #f8fafc;
        color: var(--g-ink);
        font-size: .74rem;
        font-weight: 700;
    }

    .graduates-filter-field input:focus,
    .graduates-filter-field select:focus { outline: none; border-color: #d8e1ea; background: #fff; box-shadow: 0 0 0 3px rgba(215,173,89,.08); }
    .graduates-filter-button { min-width: 95px; height: 52px; border: 0; border-radius: 11px; background: var(--g-ink); color: #fff; font-size: .7rem; font-weight: 900; letter-spacing: .5px; text-transform: uppercase; cursor: pointer; transition: transform .35s var(--g-ease), background .25s ease, box-shadow .35s ease; }
    .graduates-filter-button:hover { transform: translateY(-2px); background: #123f6e; box-shadow: 0 10px 24px rgba(0,42,92,.14); }

    .graduates-header { display: flex; align-items: end; justify-content: space-between; gap: 20px; margin-bottom: 34px; }
    .graduates-kicker-small { margin-bottom: 7px; color: #b48738; font-size: .65rem; font-weight: 900; letter-spacing: 2px; text-transform: uppercase; }
    .graduates-header h2 { margin: 0; color: var(--g-ink); font-size: clamp(1.8rem,3vw,2.5rem); line-height: 1; letter-spacing: -1px; }
    .graduates-count { color: var(--g-muted); font-size: .7rem; font-weight: 700; }

    .degree-section { margin-bottom: 68px; }
    .degree-heading { display: flex; align-items: center; gap: 16px; margin: 0 0 34px; }
    .degree-heading::before { content: ""; width: 4px; height: 42px; border-radius: 4px; background: var(--g-gold); flex-shrink: 0; }
    .degree-heading-copy { min-width: 0; }
    .degree-eyebrow { margin: 0 0 5px; color: #b48738; font-size: .62rem; font-weight: 900; letter-spacing: 2px; text-transform: uppercase; }
    .degree-title { margin: 0; color: var(--g-ink); font-size: clamp(2rem,4vw,3rem); line-height: 1; letter-spacing: -1.5px; }
    .degree-count { margin-left: auto; color: var(--g-muted); font-size: .68rem; font-weight: 800; white-space: nowrap; }

    .school-section { margin-bottom: 44px; }
    .school-heading { display: flex; align-items: center; gap: 14px; margin-bottom: 17px; }
    .school-heading h3 { margin: 0; color: var(--g-ink); font-family: "Merriweather", serif; font-size: 1.12rem; font-weight: 700; }
    .school-count { color: #8c98a5; font-size: .63rem; font-weight: 800; white-space: nowrap; }
    .school-line { flex: 1; height: 1px; background: var(--g-line); }

    .graduates-grid { display: grid; grid-template-columns: repeat(4,minmax(0,1fr)); gap: 20px; }
    .graduate-link { display: block; color: inherit; text-decoration: none; -webkit-tap-highlight-color: transparent; }
    .graduate-card { height: 100%; overflow: hidden; border: 1px solid #e3e9ef; border-radius: 18px; background: #fff; box-shadow: 0 5px 20px rgba(15,23,42,.035); transition: transform .45s var(--g-ease), box-shadow .45s ease, border-color .3s ease; }
    .graduate-link:hover .graduate-card, .graduate-link:focus-visible .graduate-card { transform: translateY(-5px); border-color: rgba(215,173,89,.42); box-shadow: 0 18px 38px rgba(15,23,42,.075); }
    .graduate-link:focus-visible { outline: none; }
    .graduate-link:focus-visible .graduate-card { box-shadow: 0 0 0 3px rgba(215,173,89,.25), 0 18px 38px rgba(15,23,42,.075); }

    .graduate-portrait { position: relative; height: 300px; overflow: hidden; background: linear-gradient(145deg,#173d67,#416d94); }
    .graduate-portrait img { display: block; width: 100%; height: 100%; object-fit: cover; transition: transform .7s var(--g-ease); }
    .graduate-link:hover .graduate-portrait img, .graduate-link:focus-visible .graduate-portrait img { transform: scale(1.035); }
    .graduate-placeholder { display: flex; align-items: center; justify-content: center; width: 100%; height: 100%; color: rgba(255,255,255,.92); font-size: 4rem; font-weight: 900; }
    .graduate-badge { position: absolute; z-index: 1; top: 13px; padding: 6px 9px; border-radius: 999px; font-size: .59rem; font-weight: 900; box-shadow: 0 5px 13px rgba(0,0,0,.09); }
    .graduate-degree-badge { left: 13px; background: var(--g-gold); color: var(--g-ink); }
    .graduate-year-badge { right: 13px; max-width: 62%; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; background: rgba(255,255,255,.95); color: var(--g-ink); }
    .graduate-card-body { padding: 18px 18px 19px; }
    .graduate-name { margin: 0 0 7px; color: var(--g-ink); font-size: 1rem; line-height: 1.25; font-weight: 850; }
    .graduate-major { margin: 0 0 4px; color: #334e6b; font-size: .73rem; font-weight: 700; line-height: 1.45; }
    .graduate-school { margin: 0; color: var(--g-muted); font-size: .65rem; line-height: 1.5; }
    .graduate-card-footer { display: flex; align-items: center; justify-content: space-between; gap: 10px; margin-top: 14px; padding-top: 12px; border-top: 1px solid #edf1f4; }
    .graduate-view { color: var(--g-ink); font-size: .63rem; font-weight: 900; transition: color .25s ease, transform .25s ease; }
    .graduate-link:hover .graduate-view, .graduate-link:focus-visible .graduate-view { color: #9c7b38; transform: translateX(2px); }

    .graduates-empty { padding: 70px 30px; text-align: center; border: 1px dashed #dce5ed; border-radius: 18px; background: #fff; }
    .graduates-empty h3 { margin: 0 0 8px; color: var(--g-ink); }
    .graduates-empty p { margin: 0 0 20px; color: var(--g-muted); font-size: .85rem; }
    .graduates-named-only { margin: 20px 0 55px; padding: 24px 0; border-top: 1px dashed var(--g-line); color: var(--g-muted); font-size: .8rem; line-height: 2; }
    .graduates-named-only strong { display: block; margin-bottom: 4px; color: var(--g-ink); }
    .graduates-pagination { display: flex; justify-content: center; flex-wrap: wrap; gap: 7px; margin: 42px 0 72px; }
    .graduates-pagination a, .graduates-pagination span { display: inline-flex; align-items: center; justify-content: center; min-width: 39px; height: 39px; padding: 0 10px; border: 1px solid var(--g-line); border-radius: 9px; background: #fff; color: var(--g-ink); text-decoration: none; font-size: .68rem; font-weight: 800; }

    @media (max-width:1100px) { .graduates-filter-form { grid-template-columns: repeat(3,minmax(0,1fr)); } .graduates-grid { grid-template-columns: repeat(3,minmax(0,1fr)); } }
    @media (max-width:850px) { .graduates-hero { min-height:560px; padding:72px 6vw 78px; } .graduates-hero h1 { font-size:clamp(54px,11vw,100px); } .graduates-hero-bottom { display:block; margin-top:40px; } .graduates-hero-count { text-align:left; margin-top:24px; } .graduates-grid { grid-template-columns:repeat(2,minmax(0,1fr)); } }
    @media (max-width:650px) { .graduates-page > main.container { padding:0 20px; } .graduates-hero { min-height:540px; padding:60px 24px 62px; } .graduates-hero h1 { letter-spacing:-3px; } .graduates-filter-form { grid-template-columns:1fr 1fr; } .graduates-header { align-items:flex-start; } .degree-heading { align-items:flex-start; } .degree-count { margin-top:10px; } .graduates-grid { grid-template-columns:1fr; } }
    @media (max-width:430px) { .graduates-page > main.container { padding:0 16px; } .graduates-filter-form { grid-template-columns:1fr; } .school-heading { align-items:flex-start; } .school-line { display:none; } }
    @media (prefers-reduced-motion:reduce) { .graduates-page * { transition:none !important; animation:none !important; } .graduate-link:hover .graduate-card, .graduate-link:focus-visible .graduate-card, .graduate-link:hover .graduate-portrait img, .graduate-link:focus-visible .graduate-portrait img { transform:none; } }
</style>
@endsection

@section('content')
@php
    $groupedGraduates = $graduates->getCollection()
        ->groupBy('degree_level')
        ->map(fn($students) => $students->groupBy(fn($graduate) => $graduate->school?->name ?? 'Unassigned')->sortKeys());

    $degreeOrder = ['undergraduate', 'graduate'];
    $degreeLabels = ['undergraduate' => 'Undergraduates', 'graduate' => 'Graduates'];
@endphp

<div class="graduates-page">
    <section class="graduates-hero">
        <div class="graduates-hero-grid"></div>
        <div class="graduates-hero-glow"></div>
        <div class="graduates-hero-inner">
            <div class="graduates-hero-content">
                <div class="graduates-kicker">Faces of our year</div>
                <h1>Graduates<em>the people behind the story.</em></h1>
                <div class="graduates-hero-bottom">
                    <p class="graduates-hero-copy">Explore the graduating community by degree level, school, academic year and campus. Each profile is part of the yearbook record.</p>
                    <div class="graduates-hero-count">
                        <strong>{{ $graduates->total() }}</strong>
                        <span>{{ Str::plural('graduate', $graduates->total()) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <main class="container">
        <section class="graduates-discovery">
            <div class="graduates-filter-shell">
                <form method="GET" action="{{ route('public.graduates') }}" class="graduates-filter-form">
                    <div class="graduates-filter-field"><input type="search" name="search" value="{{ $search }}" placeholder="Search by name..."></div>
                    <div class="graduates-filter-field">
                        <select name="year">
                            <option value="">All academic years</option>
                            @foreach($years as $academicYear)
                                <option value="{{ $academicYear->id }}" @selected((string)$year === (string)$academicYear->id)>{{ $academicYear->title }}{{ $academicYear->status === 'active' ? ' · Current' : '' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="graduates-filter-field">
                        <select name="degree">
                            <option value="">All degree levels</option>
                            <option value="undergraduate" @selected($degree === 'undergraduate')>Undergraduate</option>
                            <option value="graduate" @selected($degree === 'graduate')>Graduate</option>
                        </select>
                    </div>
                    <div class="graduates-filter-field">
                        <select name="school">
                            <option value="">All schools</option>
                            @foreach($schools as $item)
                                <option value="{{ $item->id }}" @selected((string)$school === (string)$item->id)>{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="graduates-filter-field">
                        <select name="campus">
                            <option value="">All campuses</option>
                            @foreach($campuses as $item)
                                <option value="{{ $item->id }}" @selected((string)$campus === (string)$item->id)>{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button class="graduates-filter-button" type="submit">Filter</button>
                </form>
            </div>
        </section>

        <section>
            <div class="graduates-header">
                <div>
                    <div class="graduates-kicker-small">The directory</div>
                    <h2>Graduates</h2>
                </div>
                <span class="graduates-count">{{ $graduates->total() }} {{ Str::plural('profile', $graduates->total()) }}</span>
            </div>

            @if($graduates->count())
                @foreach($degreeOrder as $degreeLevel)
                    @if(isset($groupedGraduates[$degreeLevel]) && $groupedGraduates[$degreeLevel]->isNotEmpty())
                        @php($degreeStudents = $groupedGraduates[$degreeLevel]->flatten(1))
                        <section class="degree-section">
                            <div class="degree-heading">
                                <div class="degree-heading-copy">
                                    <p class="degree-eyebrow">Academic level</p>
                                    <h3 class="degree-title">{{ $degreeLabels[$degreeLevel] }}</h3>
                                </div>
                                <span class="degree-count">{{ $degreeStudents->count() }} {{ Str::plural('profile', $degreeStudents->count()) }}</span>
                            </div>

                            @foreach($groupedGraduates[$degreeLevel] as $schoolName => $schoolGraduates)
                                <section class="school-section">
                                    <div class="school-heading">
                                        <h3>{{ $schoolName }}</h3>
                                        <span class="school-count">{{ $schoolGraduates->count() }} {{ Str::plural('graduate', $schoolGraduates->count()) }}</span>
                                        <span class="school-line"></span>
                                    </div>

                                    <div class="graduates-grid">
                                        @foreach($schoolGraduates as $graduate)
                                            @php($portrait = $graduate->portraitMedia ?? $graduate->media->firstWhere('type', 'image'))
                                            @php($graduateYear = $graduate->academicYear?->title ?? $graduate->graduation?->academicYear?->title)
                                            <a class="graduate-link" href="{{ route('public.graduate.detail', $graduate->id) }}">
                                                <article class="graduate-card">
                                                    <div class="graduate-portrait">
                                                        @if($portrait)
                                                            <img src="{{ $portrait->thumbnailUrl() }}" alt="{{ $graduate->name }}" loading="lazy">
                                                        @else
                                                            <span class="graduate-placeholder">{{ mb_strtoupper(mb_substr($graduate->name, 0, 1)) }}</span>
                                                        @endif
                                                        <span class="graduate-badge graduate-degree-badge">{{ $degreeLevel === 'graduate' ? 'Graduate' : 'Undergraduate' }}</span>
                                                        @if($graduateYear)<span class="graduate-badge graduate-year-badge">{{ $graduateYear }}</span>@endif
                                                    </div>
                                                    <div class="graduate-card-body">
                                                        <h3 class="graduate-name">{{ $graduate->name }}</h3>
                                                        @if($graduate->major)<p class="graduate-major">{{ $graduate->major->name }}</p>@endif
                                                        <p class="graduate-school">{{ $schoolName }}</p>
                                                        <div class="graduate-card-footer"><span class="graduate-view">View profile →</span></div>
                                                    </div>
                                                </article>
                                            </a>
                                        @endforeach
                                    </div>
                                </section>
                            @endforeach
                        </section>
                    @endif
                @endforeach

                <div class="graduates-pagination">
                    {!! $graduates->onEachSide(1)->links('pagination::simple-tailwind') !!}
                </div>
            @else
                <div class="graduates-empty">
                    <h3>No graduate profiles found</h3>
                    <p>Try another academic year, degree level, school, campus or search term.</p>
                    <a class="graduate-view" href="{{ route('public.graduates') }}">Reset filters →</a>
                </div>
            @endif

            @if($namedOnly->count())
                <div class="graduates-named-only">
                    <strong>Additional graduates</strong>
                    These graduates are listed by name only because public profile consent was not granted.<br>
                    {{ $namedOnly->implode(' · ') }}
                </div>
            @endif
        </section>
    </main>
</div>
@endsection