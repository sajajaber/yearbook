@extends('public.layout')

@section('title', 'Graduates')

@section('extra-css')
<style>
    .graduates-page { --g-ink:var(--ink,#002a5c); --g-gold:#d7ad59; --g-muted:#718096; --g-line:#e2e8ef; --g-paper:#f7f9fb; --g-ease:cubic-bezier(.16,1,.3,1); background:#f4f1eb; }

    /* =========================================================
       ARCHIVE-STYLE HERO
    ========================================================= */
    .graduates-hero { min-height:620px; padding:90px 7vw 100px; position:relative; overflow:hidden; isolation:isolate; display:flex; align-items:flex-end; background:var(--g-ink); color:#fff; }
    .graduates-hero::before,.graduates-hero::after { content:""; position:absolute; border:1px solid rgba(255,255,255,.12); border-radius:50%; pointer-events:none; opacity:0; transform:scale(.72) rotate(-12deg); animation:graduatesHeroCircleIn 1.4s cubic-bezier(.16,1,.3,1) .15s forwards,graduatesHeroFloat 10s ease-in-out 1.8s infinite; }
    .graduates-hero::before { width:520px; height:520px; right:-160px; top:-180px; }
    .graduates-hero::after { width:720px; height:720px; right:-260px; top:-280px; border-color:rgba(255,255,255,.06); animation:graduatesHeroCircleIn 1.6s cubic-bezier(.16,1,.3,1) .25s forwards,graduatesHeroFloatLarge 13s ease-in-out 2s infinite; }
    .graduates-hero-grid { position:absolute; inset:0; opacity:0; background-image:linear-gradient(rgba(255,255,255,.5) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.5) 1px,transparent 1px); background-size:80px 80px; mask-image:linear-gradient(to right,transparent,black 60%); -webkit-mask-image:linear-gradient(to right,transparent,black 60%); pointer-events:none; transform:scale(1.06); animation:graduatesHeroGridIn 1.4s ease .1s forwards; }
    .graduates-hero-glow { position:absolute; width:480px; height:480px; right:12%; bottom:-340px; border-radius:50%; background:radial-gradient(circle,rgba(255,176,52,.2),transparent 68%); filter:blur(10px); opacity:0; animation:graduatesHeroGlowIn 1.5s ease .45s forwards; pointer-events:none; }
    .graduates-hero-inner { position:relative; z-index:2; width:100%; max-width:1180px; margin:0 auto; display:block; }
    .graduates-hero-content { max-width:1050px; }
    .graduates-kicker { display:flex; align-items:center; gap:14px; margin-bottom:30px; color:var(--red,#ffb034); font-size:11px; font-weight:800; letter-spacing:3px; text-transform:uppercase; opacity:0; transform:translateY(20px); animation:graduatesHeroReveal .8s cubic-bezier(.16,1,.3,1) .05s forwards; }
    .graduates-kicker::before { content:""; width:42px; height:2px; background:currentColor; transform-origin:left; transform:scaleX(0); animation:graduatesHeroLineIn .7s cubic-bezier(.16,1,.3,1) .4s forwards; }
    .graduates-hero h1 { margin:0; max-width:950px; color:#fff; font-size:clamp(58px,9vw,132px); line-height:.88; letter-spacing:-5px; font-weight:800; opacity:0; transform:translateY(55px); animation:graduatesHeroTitleIn 1.05s cubic-bezier(.16,1,.3,1) .12s forwards; }
    .graduates-hero h1 em { display:block; margin-top:16px; color:#aebdcd; font-family:"Merriweather",serif; font-weight:400; font-size:.56em; line-height:1.2; letter-spacing:-2px; opacity:0; transform:translateY(25px); animation:graduatesHeroReveal .9s cubic-bezier(.16,1,.3,1) .38s forwards; }
    .graduates-hero-bottom { margin-top:55px; display:flex; justify-content:space-between; align-items:flex-end; gap:40px; max-width:850px; opacity:0; transform:translateY(25px); animation:graduatesHeroReveal .9s cubic-bezier(.16,1,.3,1) .52s forwards; }
    .graduates-hero-copy { max-width:510px; margin:0; color:#b9c6d6; font-size:15px; line-height:1.8; }
    .graduates-hero-count { flex-shrink:0; text-align:right; opacity:0; transform:translateX(25px); animation:graduatesHeroCountIn .8s cubic-bezier(.16,1,.3,1) .68s forwards; }
    .graduates-hero-count strong { display:block; color:#fff; font-size:44px; line-height:1; font-weight:700; }
    .graduates-hero-count span { display:block; margin-top:7px; color:#8293a7; font-size:10px; text-transform:uppercase; letter-spacing:2px; }

    @keyframes graduatesHeroReveal { from{opacity:0;transform:translateY(30px)} to{opacity:1;transform:translateY(0)} }
    @keyframes graduatesHeroTitleIn { from{opacity:0;transform:translateY(55px)} to{opacity:1;transform:translateY(0)} }
    @keyframes graduatesHeroCountIn { from{opacity:0;transform:translateX(25px)} to{opacity:1;transform:translateX(0)} }
    @keyframes graduatesHeroGridIn { from{opacity:0;transform:scale(1.06)} to{opacity:.08;transform:scale(1)} }
    @keyframes graduatesHeroCircleIn { from{opacity:0;transform:scale(.72) rotate(-12deg)} to{opacity:1;transform:scale(1) rotate(0)} }
    @keyframes graduatesHeroLineIn { from{transform:scaleX(0)} to{transform:scaleX(1)} }
    @keyframes graduatesHeroFloat { 0%,100%{transform:translate3d(0,0,0)} 50%{transform:translate3d(-18px,20px,0)} }
    @keyframes graduatesHeroFloatLarge { 0%,100%{transform:translate3d(0,0,0)} 50%{transform:translate3d(-25px,15px,0)} }
    @keyframes graduatesHeroGlowIn { from{opacity:0;transform:scale(.7)} to{opacity:1;transform:scale(1)} }

    .graduates-discovery { position:relative; z-index:3; margin:-18px 0 56px; }
    .graduates-filter-shell { padding:8px; border:1px solid rgba(0,42,92,.08); border-radius:18px; background:rgba(255,255,255,.97); box-shadow:0 14px 40px rgba(15,23,42,.055); backdrop-filter:blur(12px); }
    .graduates-filter-form { display:grid; grid-template-columns:minmax(190px,1.5fr) repeat(4,minmax(115px,1fr)) auto; gap:7px; }
    .graduates-filter-field { min-width:0; }
    .graduates-filter-field input,.graduates-filter-field select { box-sizing:border-box; width:100%; height:52px; padding:0 13px; border:1px solid #edf1f5; border-radius:11px; background:#f8fafc; color:var(--g-ink); font-size:.74rem; font-weight:700; }
    .graduates-filter-field input:focus,.graduates-filter-field select:focus { outline:none; border-color:#d8e1ea; background:#fff; box-shadow:0 0 0 3px rgba(215,173,89,.08); }
    .graduates-filter-button { min-width:95px; height:52px; border:0; border-radius:11px; background:var(--g-ink); color:#fff; font-size:.7rem; font-weight:900; letter-spacing:.5px; text-transform:uppercase; cursor:pointer; transition:transform .35s var(--g-ease),background .25s ease,box-shadow .35s ease; }
    .graduates-filter-button:hover { transform:translateY(-2px); background:#123f6e; box-shadow:0 10px 24px rgba(0,42,92,.14); }
    .graduates-active-filters { display:flex; flex-wrap:wrap; align-items:center; gap:7px; margin-top:10px; padding:0 4px; }
    .graduates-active-label { color:#8995a2; font-size:.61rem; font-weight:900; letter-spacing:1.1px; text-transform:uppercase; }
    .graduates-filter-tag { padding:6px 9px; border:1px solid var(--g-line); border-radius:999px; background:#f8fafc; color:var(--g-ink); font-size:.63rem; font-weight:800; }
    .graduates-filter-tag a { margin-left:5px; color:#8b97a4; text-decoration:none; }
    .graduates-clear { color:#9c7b38; font-size:.64rem; font-weight:900; text-decoration:none; }
    .graduates-header { display:flex; align-items:end; justify-content:space-between; gap:20px; margin-bottom:24px; }
    .graduates-kicker-small { margin-bottom:7px; color:#b48738; font-size:.65rem; font-weight:900; letter-spacing:2px; text-transform:uppercase; }
    .graduates-header h2 { margin:0; color:var(--g-ink); font-size:clamp(1.8rem,3vw,2.5rem); line-height:1; letter-spacing:-1px; }
    .graduates-count { color:var(--g-muted); font-size:.7rem; font-weight:700; }
    .graduates-view-tools { display:flex; align-items:center; gap:4px; padding:4px; border:1px solid var(--g-line); border-radius:12px; background:#fff; }
    .graduates-view-button { border:0; border-radius:8px; padding:9px 13px; background:transparent; color:var(--g-muted); font-size:.65rem; font-weight:900; letter-spacing:.5px; text-transform:uppercase; cursor:pointer; transition:all .25s ease; }
    .graduates-view-button:hover { background:var(--g-paper); color:var(--g-ink); }
    .graduates-view-button.is-active { background:var(--g-ink); color:#fff; }
    .graduates-year-group { margin-bottom:50px; }
    .graduates-year-heading { display:flex; align-items:center; gap:13px; margin-bottom:17px; }
    .graduates-year-number { padding:7px 10px; border:1px solid #dce5ed; border-radius:8px; background:#eef3f7; color:var(--g-ink); font-size:.65rem; font-weight:900; }
    .graduates-year-line { flex:1; height:1px; background:var(--g-line); }
    .graduates-grid { display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); gap:20px; }
    .graduate-link { display:block; color:inherit; text-decoration:none; }
    .graduate-card { height:100%; overflow:hidden; border:1px solid #e3e9ef; border-radius:18px; background:#fff; box-shadow:0 5px 20px rgba(15,23,42,.035); transition:transform .45s var(--g-ease),box-shadow .45s ease,border-color .3s ease; }
    .graduate-link:hover .graduate-card { transform:translateY(-5px); border-color:rgba(215,173,89,.42); box-shadow:0 18px 38px rgba(15,23,42,.075); }
    .graduate-portrait { position:relative; height:300px; overflow:hidden; background:linear-gradient(145deg,#173d67,#416d94); }
    .graduate-portrait img { display:block; width:100%; height:100%; object-fit:cover; transition:transform .7s var(--g-ease); }
    .graduate-link:hover .graduate-portrait img { transform:scale(1.035); }
    .graduate-placeholder { display:flex; align-items:center; justify-content:center; width:100%; height:100%; color:rgba(255,255,255,.92); font-size:4rem; font-weight:900; }
    .graduate-badge { position:absolute; z-index:1; top:13px; padding:6px 9px; border-radius:999px; font-size:.59rem; font-weight:900; box-shadow:0 5px 13px rgba(0,0,0,.09); }
    .graduate-degree-badge { left:13px; background:var(--g-gold); color:var(--g-ink); }
    .graduate-year-badge { right:13px; max-width:62%; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; background:rgba(255,255,255,.95); color:var(--g-ink); }
    .graduate-card-body { padding:18px 18px 19px; }
    .graduate-name { margin:0 0 7px; color:var(--g-ink); font-size:1rem; line-height:1.25; font-weight:850; }
    .graduate-major { margin:0 0 4px; color:#334e6b; font-size:.73rem; font-weight:700; line-height:1.45; }
    .graduate-school { margin:0; color:var(--g-muted); font-size:.65rem; line-height:1.5; }
    .graduate-card-footer { display:flex; align-items:center; justify-content:space-between; gap:10px; margin-top:14px; padding-top:12px; border-top:1px solid #edf1f4; }
    .graduate-view { color:var(--g-ink); font-size:.63rem; font-weight:900; transition:color .25s ease; }
    .graduate-link:hover .graduate-view { color:#9c7b38; }
    .graduates-grid.view-list { display:flex; flex-direction:column; gap:0; }
    .graduates-grid.view-list .graduate-link { display:block; }
    .graduates-grid.view-list .graduate-card { display:grid; grid-template-columns:112px minmax(0,1fr) auto; align-items:center; gap:22px; min-height:126px; padding:17px 19px; border-radius:0; border-left:0; border-right:0; box-shadow:none; background:transparent; }
    .graduates-grid.view-list .graduate-card:first-child { border-top:1px solid var(--g-line); }
    .graduates-grid.view-list .graduate-link:hover .graduate-card { transform:none; background:#fff; box-shadow:0 10px 28px rgba(15,23,42,.06); }
    .graduates-grid.view-list .graduate-portrait { width:112px; height:90px; border-radius:12px; }
    .graduates-grid.view-list .graduate-badge { top:8px; }
    .graduates-grid.view-list .graduate-card-body { padding:0; }
    .graduates-grid.view-list .graduate-card-footer { margin:0; padding:0; border:0; }
    .graduates-empty { padding:70px 30px; text-align:center; border:1px dashed #dce5ed; border-radius:18px; background:#fff; }
    .graduates-empty h3 { margin:0 0 8px; color:var(--g-ink); }
    .graduates-empty p { margin:0 0 20px; color:var(--g-muted); font-size:.85rem; }
    .graduates-named-only { margin-top:35px; padding:24px 0; border-top:1px dashed var(--g-line); color:var(--g-muted); font-size:.8rem; line-height:2; }
    .graduates-named-only strong { display:block; color:var(--g-ink); }
    .graduates-pagination { display:flex; justify-content:center; flex-wrap:wrap; gap:7px; margin:42px 0 72px; }
    .graduates-pagination a,.graduates-pagination span { display:inline-flex; align-items:center; justify-content:center; min-width:39px; height:39px; padding:0 10px; border:1px solid var(--g-line); border-radius:9px; background:#fff; color:var(--g-ink); text-decoration:none; font-size:.68rem; font-weight:800; }
    @media(max-width:1100px){ .graduates-filter-form{grid-template-columns:repeat(3,minmax(0,1fr));} .graduates-grid{grid-template-columns:repeat(3,minmax(0,1fr));} }
    @media(max-width:850px){ .graduates-hero{min-height:560px;padding:72px 6vw 78px;} .graduates-hero-inner{display:block;} .graduates-hero h1{font-size:clamp(54px,11vw,100px);} .graduates-hero-bottom{display:block;margin-top:40px;max-width:700px;} .graduates-hero-count{text-align:left;margin-top:24px;} .graduates-hero-copy{max-width:650px;} .graduates-grid{grid-template-columns:repeat(2,minmax(0,1fr));} }
    @media(max-width:650px){ .graduates-hero{min-height:540px;padding:60px 24px 62px;} .graduates-hero h1{letter-spacing:-3px;} .graduates-hero h1 em{letter-spacing:-1px;} .graduates-hero-bottom{margin-top:34px;} .graduates-hero-count strong{font-size:38px;} .graduates-filter-form{grid-template-columns:1fr 1fr;} .graduates-header{align-items:flex-start;flex-direction:column;} .graduates-view-tools{align-self:flex-end;} .graduates-grid{grid-template-columns:1fr;} .graduates-grid.view-list .graduate-card{grid-template-columns:78px minmax(0,1fr);gap:15px;padding:15px 4px;} .graduates-grid.view-list .graduate-portrait{width:78px;height:78px;} .graduates-grid.view-list .graduate-card-footer{display:none;} }
    @media(max-width:430px){ .graduates-filter-form{grid-template-columns:1fr;} }
    @media(prefers-reduced-motion:reduce){ .graduates-page *{transition:none!important;animation:none!important;} }
</style>
@endsection

@section('content')
<div class="graduates-page">
    <section class="graduates-hero">
        <div class="graduates-hero-grid"></div>
        <div class="graduates-hero-glow"></div>
        <div class="graduates-hero-inner">
            <div class="graduates-hero-content">
                <div class="graduates-kicker">Faces of our year</div>
                <h1>Graduates<em>the people behind the story.</em></h1>
                <div class="graduates-hero-bottom">
                    <p class="graduates-hero-copy">Explore the graduating community by academic year, degree level, school and campus. Every profile is part of the yearbook record.</p>
                    <div class="graduates-hero-count"><strong>{{ $graduates->total() }}</strong><span>{{ Str::plural('graduate', $graduates->total()) }}</span></div>
                </div>
            </div>
        </div>
    </section>

    <main class="container">
        <section class="graduates-discovery">
            <div class="graduates-filter-shell">
                <form method="GET" action="{{ route('public.graduates') }}" class="graduates-filter-form">
                    <div class="graduates-filter-field"><input type="search" name="search" value="{{ $search }}" placeholder="Search by name..."></div>
                    <div class="graduates-filter-field"><select name="year"><option value="">All academic years</option>@foreach($years as $academicYear)<option value="{{ $academicYear->id }}" @selected((string)$year === (string)$academicYear->id)>{{ $academicYear->title }}{{ $academicYear->status === 'active' ? ' · Current' : '' }}</option>@endforeach</select></div>
                    <div class="graduates-filter-field"><select name="degree"><option value="">All degree levels</option><option value="undergraduate" @selected($degree === 'undergraduate')>Undergraduate</option><option value="graduate" @selected($degree === 'graduate')>Graduate</option></select></div>
                    <div class="graduates-filter-field"><select name="school"><option value="">All schools</option>@foreach($schools as $item)<option value="{{ $item->id }}" @selected((string)$school === (string)$item->id)>{{ $item->name }}</option>@endforeach</select></div>
                    <div class="graduates-filter-field"><select name="campus"><option value="">All campuses</option>@foreach($campuses as $item)<option value="{{ $item->id }}" @selected((string)$campus === (string)$item->id)>{{ $item->name }}</option>@endforeach</select></div>
                    <button class="graduates-filter-button" type="submit">Filter</button>
                </form>
            </div>
        </section>

        <section>
            <div class="graduates-header">
                <div><div class="graduates-kicker-small">The directory</div><h2>Graduates</h2></div>
                <div style="display:flex;align-items:center;gap:16px;flex-wrap:wrap;justify-content:flex-end;">
                    <span class="graduates-count">{{ $graduates->total() }} {{ Str::plural('profile', $graduates->total()) }}</span>
                    <div class="graduates-view-tools" aria-label="Choose graduate view">
                        <button type="button" class="graduates-view-button is-active" data-graduates-view="cards">Cards</button>
                        <button type="button" class="graduates-view-button" data-graduates-view="list">List</button>
                    </div>
                </div>
            </div>

            @if($graduates->count())
                @php($currentGroup = null)
                <div class="graduates-year-group">
                    <div class="graduates-year-heading"><span class="graduates-year-number">{{ $year ? optional($years->firstWhere('id', $year))->title : 'Selected year' }}</span><span class="graduates-year-line"></span></div>
                    <div class="graduates-grid" id="graduates-grid">
                        @foreach($graduates as $graduate)
                            @php($portrait = $graduate->portraitMedia ?? $graduate->media->firstWhere('type', 'image'))
                            @php($graduateYear = $graduate->academicYear?->title ?? $graduate->graduation?->academicYear?->title)
                            <a class="graduate-link" href="{{ route('public.graduate.detail', $graduate->id) }}">
                                <article class="graduate-card">
                                    <div class="graduate-portrait">
                                        @if($portrait)<img src="{{ $portrait->thumbnailUrl() }}" alt="{{ $graduate->name }}" loading="lazy">@else<span class="graduate-placeholder">{{ mb_strtoupper(mb_substr($graduate->name,0,1)) }}</span>@endif
                                        <span class="graduate-badge graduate-degree-badge">{{ $graduate->degree_level === 'graduate' ? 'Graduate' : 'Undergraduate' }}</span>
                                        @if($graduateYear)<span class="graduate-badge graduate-year-badge">{{ $graduateYear }}</span>@endif
                                    </div>
                                    <div class="graduate-card-body">
                                        <h3 class="graduate-name">{{ $graduate->name }}</h3>
                                        @if($graduate->major)<p class="graduate-major">{{ $graduate->major->name }}</p>@endif
                                        @if($graduate->school)<p class="graduate-school">{{ $graduate->school->name }}</p>@endif
                                        <div class="graduate-card-footer"><span class="graduate-view">View profile →</span></div>
                                    </div>
                                </article>
                            </a>
                        @endforeach
                    </div>
                </div>

                <div class="graduates-pagination">{!! $graduates->onEachSide(1)->links('pagination::simple-tailwind') !!}</div>
            @else
                <div class="graduates-empty"><h3>No graduate profiles found</h3><p>Try another academic year, degree level, school, campus or search term.</p><a class="graduate-view" href="{{ route('public.graduates') }}">Reset filters →</a></div>
            @endif

            @if($namedOnly->count())
                <div class="graduates-named-only"><strong>Additional graduates</strong>{{ $namedOnly->implode(' · ') }}</div>
            @endif
        </section>
    </main>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded',function(){const grid=document.getElementById('graduates-grid');if(!grid)return;const buttons=document.querySelectorAll('[data-graduates-view]');const key='yearbook-graduates-view';const apply=mode=>{grid.classList.toggle('view-list',mode==='list');buttons.forEach(b=>b.classList.toggle('is-active',b.dataset.graduatesView===mode));try{localStorage.setItem(key,mode)}catch(e){}};let mode='cards';try{mode=localStorage.getItem(key)==='list'?'list':'cards'}catch(e){}buttons.forEach(b=>b.addEventListener('click',()=>apply(b.dataset.graduatesView)));apply(mode);});
</script>
@endpush
