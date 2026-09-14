@extends('public.layout')

@section('title', 'Events | LIU Digital Yearbook')

@section('extra-css')
<style>
    .events-page { --e-ink:var(--ink,#002a5c); --e-gold:var(--red,#ffb034); --e-muted:#aebdcd; --e-line:#d8e3ef; --e-paper:#f7fbff; --e-ease:cubic-bezier(.16,1,.3,1); background:#f7fbff; }

    /* Keep the public events content centered and comfortably inset from the viewport edges. */
    .events-page > main.container {
        width:100%;
        max-width:1240px;
        margin:0 auto;
        padding-left:32px;
        padding-right:32px;
        box-sizing:border-box;
    }

    .events-hero { position:relative; min-height:590px; padding:90px 7vw 82px; display:flex; align-items:flex-end; overflow:hidden; isolation:isolate; color:#fff; background:var(--e-ink); }
    .events-hero::before,.events-hero::after { content:''; position:absolute; border:1px solid rgba(255,255,255,.12); border-radius:50%; pointer-events:none; opacity:0; transform:scale(.72) rotate(-12deg); animation:eventsCircleIn 1.4s cubic-bezier(.16,1,.3,1) .15s forwards,eventsFloat 11s ease-in-out 1.8s infinite; }
    .events-hero::before { width:520px; height:520px; right:-150px; top:-190px; }
    .events-hero::after { width:760px; height:760px; right:-280px; top:-330px; border-color:rgba(255,255,255,.055); animation-duration:1.7s,14s; animation-delay:.25s,2s; }
    .events-hero-grid { position:absolute; inset:0; z-index:-1; opacity:0; background-image:linear-gradient(rgba(255,255,255,.55) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.55) 1px,transparent 1px); background-size:80px 80px; mask-image:linear-gradient(to right,transparent,black 58%); -webkit-mask-image:linear-gradient(to right,transparent,black 58%); transform:scale(1.06); animation:eventsGridIn 1.4s ease .1s forwards; }
    .events-hero-glow { position:absolute; z-index:-1; width:430px; height:430px; right:12%; bottom:-260px; border-radius:50%; background:rgba(255,176,52,.15); filter:blur(80px); animation:eventsGlow 7s ease-in-out 1.5s infinite alternate; }
    .events-hero-inner { position:relative; z-index:2; width:100%; max-width:1180px; margin:0 auto; display:block; }
    .events-kicker { display:flex; align-items:center; gap:14px; margin-bottom:28px; color:var(--e-gold); font-size:11px; font-weight:800; letter-spacing:3px; text-transform:uppercase; opacity:0; transform:translateY(20px); animation:eventsReveal .8s cubic-bezier(.16,1,.3,1) .05s forwards; }
    .events-kicker:before { content:''; width:42px; height:2px; background:currentColor; transform-origin:left; transform:scaleX(0); animation:eventsLineIn .7s cubic-bezier(.16,1,.3,1) .4s forwards; }
    .events-hero h1 { max-width:1050px; margin:0; color:#fff; font-size:clamp(58px,9vw,128px); line-height:.86; letter-spacing:-5px; font-weight:900; opacity:0; transform:translateY(55px); animation:eventsTitleIn 1.05s cubic-bezier(.16,1,.3,1) .12s forwards; }
    .events-hero h1 em { display:block; margin-top:14px; color:#aebdcd; font-family:'Merriweather',serif; font-size:.52em; line-height:1.2; font-weight:400; letter-spacing:-2px; opacity:0; transform:translateY(25px); animation:eventsReveal .9s cubic-bezier(.16,1,.3,1) .38s forwards; }
    .events-hero-bottom { max-width:880px; margin-top:52px; display:flex; justify-content:space-between; align-items:flex-end; gap:40px; opacity:0; transform:translateY(25px); animation:eventsReveal .9s cubic-bezier(.16,1,.3,1) .52s forwards; }
    .events-hero-copy { max-width:520px; margin:0; color:#b9c6d6; font-size:15px; line-height:1.8; }
    .events-hero-count { flex-shrink:0; text-align:right; opacity:0; transform:translateX(25px); animation:eventsCountIn .8s cubic-bezier(.16,1,.3,1) .68s forwards; }
    .events-hero-count strong { display:block; color:#fff; font-size:44px; line-height:1; font-weight:700; }
    .events-hero-count span { display:block; margin-top:7px; color:#8293a7; font-size:10px; text-transform:uppercase; letter-spacing:2px; }

    @keyframes eventsReveal { from{opacity:0;transform:translateY(30px)} to{opacity:1;transform:translateY(0)} }
    @keyframes eventsTitleIn { from{opacity:0;transform:translateY(55px)} to{opacity:1;transform:translateY(0)} }
    @keyframes eventsCountIn { from{opacity:0;transform:translateX(25px)} to{opacity:1;transform:translateX(0)} }
    @keyframes eventsGridIn { from{opacity:0;transform:scale(1.06)} to{opacity:.08;transform:scale(1)} }
    @keyframes eventsCircleIn { from{opacity:0;transform:scale(.72) rotate(-12deg)} to{opacity:1;transform:scale(1) rotate(0)} }
    @keyframes eventsLineIn { from{transform:scaleX(0)} to{transform:scaleX(1)} }
    @keyframes eventsFloat { 0%,100%{transform:translate3d(0,0,0)} 50%{transform:translate3d(-18px,20px,0)} }
    @keyframes eventsGlow { from{transform:translate3d(0,0,0) scale(.9)} to{transform:translate3d(-35px,-20px) scale(1.08)} }

    .events-discovery { margin:-22px 0 56px; position:relative; z-index:3; }
    .events-filter-shell { padding:8px; border:1px solid rgba(0,42,92,.08); border-radius:18px; background:rgba(255,255,255,.97); box-shadow:0 18px 45px rgba(15,23,42,.09); backdrop-filter:blur(12px); }
    .events-filter-form { display:grid; grid-template-columns:minmax(190px,1.5fr) repeat(4,minmax(120px,1fr)) auto; gap:7px; }
    .events-filter-field { min-width:0; }
    .events-filter-field input,.events-filter-field select { box-sizing:border-box; width:100%; height:52px; padding:0 13px; border:1px solid #edf1f5; border-radius:11px; background:#f8fafc; color:var(--e-ink); font-size:.74rem; font-weight:700; }
    .events-filter-field input:focus,.events-filter-field select:focus { outline:none; border-color:#d7e0e8; background:#fff; box-shadow:0 0 0 3px rgba(255,176,52,.08); }
    .events-filter-button { min-width:95px; height:52px; border:0; border-radius:11px; background:var(--e-ink); color:#fff; font-size:.7rem; font-weight:900; letter-spacing:.5px; text-transform:uppercase; cursor:pointer; transition:transform .35s var(--e-ease),background .25s ease,box-shadow .35s ease; }
    .events-filter-button:hover { transform:translateY(-2px); background:#123f6e; box-shadow:0 10px 24px rgba(0,42,92,.14); }
    .events-active-filters { display:flex; flex-wrap:wrap; align-items:center; gap:7px; margin-top:10px; padding:0 4px; }
    .events-active-label { color:#8995a2; font-size:.61rem; font-weight:900; letter-spacing:1.1px; text-transform:uppercase; }
    .events-filter-tag { padding:6px 9px; border:1px solid var(--e-line); border-radius:999px; background:#f8fafc; color:var(--e-ink); font-size:.63rem; font-weight:800; }
    .events-filter-tag a { margin-left:5px; color:#8b97a4; text-decoration:none; }
    .events-clear { color:#9c7b38; font-size:.64rem; font-weight:900; text-decoration:none; }
    .events-section-intro { display:flex; align-items:end; justify-content:space-between; gap:20px; margin-bottom:18px; }
    .events-section-label { margin-bottom:7px; color:#b48738; font-size:.65rem; font-weight:900; letter-spacing:2px; text-transform:uppercase; }
    .events-section-intro h2 { margin:0; color:var(--e-ink); font-size:clamp(1.8rem,3vw,2.5rem); line-height:1; letter-spacing:-1px; }
    .events-results { color:#64748b; font-size:.7rem; font-weight:700; }
    .events-view-tools { display:flex; align-items:center; gap:4px; padding:4px; border:1px solid var(--e-line); border-radius:12px; background:#fff; }
    .events-view-button { border:0; border-radius:8px; padding:9px 13px; background:transparent; color:#64748b; font-size:.65rem; font-weight:900; letter-spacing:.5px; text-transform:uppercase; cursor:pointer; transition:all .25s ease; }
    .events-view-button:hover { background:#f7f9fb; color:var(--e-ink); }
    .events-view-button.is-active { background:var(--e-ink); color:#fff; }
    .events-list { border-top:2px solid var(--e-ink); }
    .event-list-link { display:block; color:inherit; text-decoration:none; }
    .event-list-item { display:grid; grid-template-columns:145px minmax(0,1fr) 34px; gap:26px; align-items:center; min-height:142px; padding:24px 10px; border-bottom:1px solid var(--e-line); transition:padding .45s var(--e-ease),background .35s ease,transform .45s var(--e-ease),box-shadow .35s ease; }
    .event-list-link:hover .event-list-item { padding-left:17px; padding-right:3px; background:#fff; transform:translateX(3px); box-shadow:0 12px 28px rgba(15,23,42,.045); }
    .event-list-date { color:#b48738; font-size:.68rem; font-weight:900; letter-spacing:1px; line-height:1.5; text-transform:uppercase; }
    .event-list-meta { display:flex; flex-wrap:wrap; gap:7px; margin-bottom:7px; }
    .event-category { padding:4px 8px; border:1px solid rgba(255,176,52,.3); border-radius:999px; background:rgba(255,176,52,.08); color:#a77d34; font-size:.59rem; font-weight:900; letter-spacing:.4px; text-transform:uppercase; }
    .event-list-location { color:#64748b; font-size:.66rem; font-weight:700; }
    .event-list-title { margin:0; color:var(--e-ink); font-family:'Merriweather',serif; font-size:clamp(1.05rem,2vw,1.45rem); line-height:1.3; font-weight:700; transition:color .25s ease,transform .4s var(--e-ease); }
    .event-list-link:hover .event-list-title { color:#a77d34; transform:translateX(4px); }
    .event-list-description { max-width:760px; margin:7px 0 0; color:#64748b; font-size:.77rem; line-height:1.65; }
    .event-list-arrow { display:flex; align-items:center; justify-content:center; width:34px; height:34px; border:1px solid var(--e-line); border-radius:50%; color:var(--e-ink); transition:all .35s var(--e-ease); }
    .event-list-link:hover .event-list-arrow { border-color:#c8a15b; background:#fff8ea; transform:translateX(4px) rotate(-4deg); }
    .events-list.view-large { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:18px; border-top:0; }
    .events-list.view-large .event-list-item { height:100%; min-height:285px; grid-template-columns:minmax(0,1fr) 34px; grid-template-rows:auto 1fr; align-items:start; gap:18px; padding:28px; border:1px solid var(--e-line); border-radius:18px; background:#fff; box-shadow:0 9px 30px rgba(15,23,42,.035); }
    .events-list.view-large .event-list-date { grid-column:1/-1; }
    .events-list.view-large .event-list-main { grid-column:1; }
    .events-list.view-large .event-list-arrow { grid-column:2; grid-row:2; }
    .events-list.view-large .event-list-link:hover .event-list-item { padding:28px; transform:translateY(-5px); background:#fff; box-shadow:0 20px 45px rgba(15,23,42,.08); }
    .events-empty { padding:70px 30px; text-align:center; border:1px dashed #dce5ed; border-radius:18px; background:#fff; }
    .events-empty h3 { margin:0 0 8px; color:var(--e-ink); }
    .events-empty p { margin:0; color:#64748b; font-size:.85rem; }
    .events-pagination { display:flex; justify-content:center; flex-wrap:wrap; gap:7px; margin:42px 0 72px; }
    .events-pagination a,.events-pagination span { display:inline-flex; align-items:center; justify-content:center; min-width:39px; height:39px; padding:0 10px; border:1px solid var(--e-line); border-radius:9px; background:#fff; color:var(--e-ink); text-decoration:none; font-size:.68rem; font-weight:800; }
    .events-pagination .active { border-color:var(--e-ink); background:var(--e-ink); color:#fff; }

    @media(max-width:1050px){ .events-filter-form{grid-template-columns:repeat(3,minmax(0,1fr));} }
    @media(max-width:850px){ .events-hero{min-height:520px;padding:78px 6vw 65px;} .events-hero-inner{grid-template-columns:1fr;gap:28px;} .events-hero-bottom{align-items:flex-start;flex-direction:column;gap:22px;} .events-hero-count{text-align:left;} .events-list.view-large{grid-template-columns:1fr;} }
    @media(max-width:650px){ .events-page > main.container{padding-left:24px;padding-right:24px;} .events-hero{min-height:500px;padding:62px 24px 52px;} .events-hero h1{letter-spacing:-2.5px;} .events-filter-form{grid-template-columns:1fr 1fr;} .events-section-intro{align-items:flex-start;flex-direction:column;} .events-view-tools{align-self:flex-end;} .event-list-item{grid-template-columns:88px minmax(0,1fr) 30px;gap:15px;padding:20px 5px;} .events-list.view-large .event-list-item{grid-template-columns:minmax(0,1fr) 30px;padding:23px;} .events-list.view-large .event-list-link:hover .event-list-item{padding:23px;} }
    @media(max-width:430px){ .events-page > main.container{padding-left:18px;padding-right:18px;} .events-filter-form{grid-template-columns:1fr;} .event-list-item{grid-template-columns:1fr 30px;} .event-list-date{grid-column:1/-1;} .event-list-main{grid-column:1;} }
    @media(prefers-reduced-motion:reduce){ .events-page *{scroll-behavior:auto!important;transition:none!important;animation:none!important;} .events-hero::before,.events-hero::after,.events-hero-glow,.events-hero-grid{animation:none!important;} }
</style>
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
                    <div class="events-filter-field"><select name="year"><option value="">All academic years</option>@foreach($years as $academicYear)<option value="{{ $academicYear->id }}" @selected((string)$year === (string)$academicYear->id)>{{ $academicYear->title }}{{ $academicYear->status === 'active' ? ' · Current' : '' }}</option>@endforeach</select></div>
                    <div class="events-filter-field"><select name="category"><option value="">All categories</option>@foreach($categories as $item)<option value="{{ $item->id }}" @selected((string)$category === (string)$item->id)>{{ $item->name }}</option>@endforeach</select></div>
                    <div class="events-filter-field"><select name="campus"><option value="">All campuses</option>@foreach($campuses as $item)<option value="{{ $item->id }}" @selected((string)$campus === (string)$item->id)>{{ $item->name }}</option>@endforeach</select></div>
                    <div class="events-filter-field"><select name="sort"><option value="latest" @selected($sort === 'latest')>Newest first</option><option value="oldest" @selected($sort === 'oldest')>Oldest first</option><option value="alphabetical" @selected($sort === 'alphabetical')>A–Z</option></select></div>
                    <button class="events-filter-button" type="submit">Filter</button>
                </form>
            </div>
        </section>

        <section>
            <div class="events-section-intro">
                <div><div class="events-section-label">The archive</div><h2>Events &amp; experiences</h2></div>
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
                <div class="events-empty"><h3>No events found</h3><p>Try a different academic year, category or search term.</p></div>
            @endif
        </section>
    </main>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded',function(){const list=document.getElementById('events-list');if(!list)return;const buttons=document.querySelectorAll('[data-events-view]');const key='yearbook-events-view';const apply=mode=>{list.classList.toggle('view-large',mode==='large');buttons.forEach(b=>b.classList.toggle('is-active',b.dataset.eventsView===mode));try{localStorage.setItem(key,mode)}catch(e){}};let mode='list';try{mode=localStorage.getItem(key)==='large'?'large':'list'}catch(e){}buttons.forEach(b=>b.addEventListener('click',()=>apply(b.dataset.eventsView)));apply(mode);});
</script>
@endpush