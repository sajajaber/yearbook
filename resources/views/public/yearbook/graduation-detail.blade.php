@extends('public.layout')

@section('title', 'Graduation Ceremony')

@section('extra-css')
<style>
    :root {
        --grad-ink: var(--ink, #002a5c);
        --grad-blue: #073972;
        --grad-blue-light: #eaf3fb;
        --grad-gold: #ffb034;
        --grad-gold-light: #fff4df;
        --grad-paper: var(--paper, #f7fbff);
        --grad-white: #fff;
        --grad-muted: var(--ink-soft, #64748b);
        --grad-line: #d8e3ef;
        --grad-shadow: 0 18px 50px rgba(0,42,92,.10);
        --grad-shadow-hover: 0 25px 65px rgba(0,42,92,.17);
        --grad-radius: 20px;
    }

    .graduation-page {
        min-height: 100vh;
        background:
            radial-gradient(circle at 10% 15%, rgba(255,176,52,.07), transparent 28%),
            radial-gradient(circle at 90% 40%, rgba(0,42,92,.06), transparent 30%),
            var(--grad-paper);
    }

    .graduation-hero {
        position: relative;
        min-height: 470px;
        display: flex;
        align-items: flex-end;
        overflow: hidden;
        isolation: isolate;
        background: var(--grad-ink);
    }

    .graduation-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        z-index: 1;
        background: linear-gradient(90deg, rgba(0,20,48,.94), rgba(0,42,92,.70) 45%, rgba(0,42,92,.28));
        pointer-events: none;
    }

    .graduation-hero::after {
        content: '';
        position: absolute;
        width: 420px;
        height: 420px;
        right: -150px;
        top: -180px;
        border: 1px solid rgba(255,255,255,.15);
        border-radius: 50%;
        box-shadow: 0 0 0 55px rgba(255,255,255,.025), 0 0 0 110px rgba(255,255,255,.02);
        z-index: 2;
        pointer-events: none;
    }

    .graduation-hero-image { position: absolute; inset: 0; z-index: 0; overflow: hidden; }
    .graduation-hero-image img {
        width: 100%; height: 100%; object-fit: cover; object-position: center;
        opacity: .88; transform: scale(1.04); animation: heroImageReveal 1.2s ease-out forwards;
        transition: transform 1.2s cubic-bezier(.2,.65,.25,1);
    }
    .graduation-hero:hover .graduation-hero-image img { transform: scale(1.08); }

    .graduation-hero-content,
    .graduation-content {
        width: min(1180px, calc(100% - 48px));
        margin: 0 auto;
    }

    .graduation-hero-content {
        position: relative;
        z-index: 3;
        padding: 80px 0 62px;
        color: white;
    }

    .graduation-eyebrow {
        display: inline-flex; align-items: center; gap: 10px; margin-bottom: 18px;
        padding: 8px 14px; border: 1px solid rgba(255,255,255,.25); border-radius: 999px;
        background: rgba(255,255,255,.08); backdrop-filter: blur(10px); color: white;
        font-size: .72rem; font-weight: 800; letter-spacing: .16em; text-transform: uppercase;
        animation: fadeUp .7s .15s ease-out both;
    }
    .graduation-eyebrow::before { content:''; width:7px; height:7px; border-radius:50%; background:var(--grad-gold); box-shadow:0 0 0 5px rgba(255,176,52,.15); }

    .graduation-hero-content h1 {
        max-width: 850px; margin: 0 0 16px; color: white;
        font-size: clamp(2.8rem, 6vw, 5.4rem); line-height: .98; font-weight: 900;
        letter-spacing: -.055em; animation: fadeUp .8s .28s ease-out both;
    }

    .graduation-date { display:flex; align-items:center; gap:12px; margin:0; color:rgba(255,255,255,.88); font-size:clamp(1rem,2vw,1.25rem); animation:fadeUp .8s .42s ease-out both; }
    .graduation-date-icon { display:grid; place-items:center; width:38px; height:38px; border-radius:12px; background:var(--grad-gold); color:var(--grad-ink); box-shadow:0 8px 25px rgba(255,176,52,.25); }
    .hero-accent-line { width:72px; height:4px; margin-top:28px; border-radius:999px; background:var(--grad-gold); animation:lineReveal .8s .55s ease-out both; }

    .graduation-content { padding:80px 0 100px; }
    .graduation-layout { display:grid; grid-template-columns:minmax(0,1fr) 320px; gap:34px; align-items:start; }

    .ceremony-card, .details-card {
        position:relative; overflow:hidden; border:1px solid var(--grad-line); border-radius:var(--grad-radius);
        background:var(--grad-white); box-shadow:var(--grad-shadow); animation:revealCard .8s ease-out both;
    }
    .ceremony-card { padding:clamp(28px,4vw,48px); }
    .ceremony-card::before { content:''; position:absolute; inset:0 0 auto; height:4px; background:linear-gradient(90deg,var(--grad-gold),var(--grad-ink)); }

    .section-heading { margin-bottom:28px; }
    .section-label { display:block; margin-bottom:9px; color:var(--grad-gold); font-size:.7rem; font-weight:900; letter-spacing:.18em; text-transform:uppercase; }
    .section-heading h2, .graduates-heading h2 { margin:0; color:var(--grad-ink); font-weight:900; letter-spacing:-.04em; }
    .section-heading h2 { font-size:clamp(1.7rem,3vw,2.25rem); }

    .ceremony-description { color:var(--grad-muted); font-size:1.03rem; line-height:1.9; }
    .ceremony-description p { margin:0 0 1em; }
    .ceremony-description p:last-child { margin-bottom:0; }
    .ceremony-description ul, .ceremony-description ol { padding-left:1.5rem; margin:.8em 0 1em; }
    .ceremony-description li { margin:.3em 0; }

    .gallery-heading { display:flex; align-items:flex-end; justify-content:space-between; gap:20px; margin:55px 0 22px; }
    .gallery-heading h2 { margin:0; color:var(--grad-ink); font-size:1.55rem; font-weight:800; }
    .gallery-heading span { color:var(--grad-muted); font-size:.85rem; font-weight:600; }

    .graduation-gallery { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:16px; }
    .graduation-gallery-item {
        position:relative; min-height:245px; overflow:hidden; border-radius:16px; background:var(--grad-blue-light);
        box-shadow:0 8px 25px rgba(0,42,92,.08); animation:galleryReveal .65s ease-out both;
    }
    .graduation-gallery-item:first-child { grid-column:span 2; min-height:330px; }

    .graduation-gallery-item img,
    .graduation-gallery-item video {
        width:100%; height:100%; min-height:inherit; object-fit:cover; display:block;
        transition:transform .8s cubic-bezier(.2,.65,.25,1), filter .5s ease;
    }
    .graduation-gallery-item:hover img { transform:scale(1.065); filter:saturate(1.08); }
    .graduation-gallery-item video { background:#001b3d; }

    .media-document {
        min-height:245px; height:100%; display:flex; flex-direction:column; align-items:center; justify-content:center;
        gap:15px; padding:30px; text-align:center; color:var(--grad-ink);
        background:linear-gradient(135deg,#f8fbff,#eaf3fb);
    }
    .media-document-icon { display:grid; place-items:center; width:64px; height:64px; border-radius:18px; background:var(--grad-gold-light); color:var(--grad-ink); }
    .media-document strong { font-size:1rem; word-break:break-word; }
    .media-document a { display:inline-flex; align-items:center; justify-content:center; padding:9px 14px; border-radius:10px; background:var(--grad-ink); color:#fff; text-decoration:none; font-size:.8rem; font-weight:800; transition:transform .2s, background .2s; }
    .media-document a:hover { transform:translateY(-2px); background:var(--grad-blue); }

    .gallery-overlay { position:absolute; left:18px; bottom:16px; z-index:2; display:flex; align-items:center; gap:8px; padding:7px 11px; border-radius:999px; background:rgba(0,24,54,.72); color:#fff; font-size:.76rem; font-weight:700; backdrop-filter:blur(8px); }
    .gallery-overlay-icon { display:grid; place-items:center; width:26px; height:26px; border-radius:50%; background:rgba(255,255,255,.92); color:var(--grad-ink); }

    .graduation-sidebar { position:sticky; top:25px; }
    .details-card { padding:27px; animation-delay:.15s; }
    .details-card::before { content:''; position:absolute; width:150px; height:150px; right:-85px; top:-85px; border-radius:50%; background:var(--grad-gold-light); }
    .details-header { position:relative; z-index:1; display:flex; align-items:center; gap:12px; margin-bottom:25px; padding-bottom:18px; border-bottom:1px solid var(--grad-line); }
    .details-icon { display:grid; place-items:center; width:42px; height:42px; flex:0 0 42px; border-radius:13px; background:var(--grad-gold-light); color:var(--grad-ink); }
    .details-header h3 { margin:0; color:var(--grad-ink); font-size:1.05rem; font-weight:800; }
    .detail-item { position:relative; z-index:1; padding:17px 0; border-bottom:1px solid #edf2f7; }
    .detail-item:last-child { border-bottom:0; padding-bottom:0; }
    .detail-label { display:block; margin-bottom:7px; color:var(--grad-muted); font-size:.72rem; font-weight:800; letter-spacing:.12em; text-transform:uppercase; }
    .detail-value { display:block; color:var(--grad-ink); font-size:.98rem; font-weight:700; line-height:1.5; }

    .graduates-section { margin-top:80px; }
    .graduates-heading { display:flex; align-items:flex-end; justify-content:space-between; gap:25px; margin-bottom:32px; animation:revealCard .8s .2s ease-out both; }
    .graduates-heading h2 { font-size:clamp(2rem,4vw,3rem); line-height:1; }
    .graduates-count { flex-shrink:0; display:inline-flex; align-items:center; gap:8px; padding:10px 15px; border:1px solid var(--grad-line); border-radius:999px; background:#fff; color:var(--grad-ink); font-size:.85rem; font-weight:800; box-shadow:0 6px 20px rgba(0,42,92,.06); }
    .graduates-count::before { content:''; width:7px; height:7px; border-radius:50%; background:var(--grad-gold); }

    .graduates-grid { display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); gap:22px; }
    .graduate-link { display:block; color:inherit; text-decoration:none; animation:graduateReveal .7s ease-out both; }
    .graduate-card { height:100%; overflow:hidden; border:1px solid var(--grad-line); border-radius:18px; background:#fff; box-shadow:0 10px 30px rgba(0,42,92,.07); transition:transform .45s cubic-bezier(.2,.65,.25,1),box-shadow .45s,border-color .35s; }
    .graduate-link:hover .graduate-card { transform:translateY(-9px); border-color:rgba(255,176,52,.65); box-shadow:var(--grad-shadow-hover); }
    .graduate-image { height:235px; overflow:hidden; background:linear-gradient(135deg,var(--grad-ink),var(--grad-blue)); }
    .graduate-image img { width:100%; height:100%; object-fit:cover; display:block; transition:transform .65s cubic-bezier(.2,.65,.25,1); }
    .graduate-link:hover .graduate-image img { transform:scale(1.07); }
    .graduate-placeholder { width:100%; height:100%; display:grid; place-items:center; color:#fff; font-size:3.4rem; font-weight:900; }
    .graduate-body { position:relative; padding:22px 20px 24px; }
    .graduate-name { margin:0 0 9px; color:var(--grad-ink); font-size:1.1rem; font-weight:800; }
    .graduate-info { margin:0; color:var(--grad-muted); font-size:.84rem; line-height:1.65; }

    .empty-graduates { padding:65px 30px; border:1px dashed #cbd8e6; border-radius:var(--grad-radius); background:rgba(255,255,255,.7); text-align:center; }
    .empty-graduates h3 { margin:0 0 8px; color:var(--grad-ink); }
    .empty-graduates p { max-width:420px; margin:0 auto; color:var(--grad-muted); line-height:1.7; }

    .graduation-pagination { display:flex; justify-content:center; align-items:center; flex-wrap:wrap; gap:7px; margin-top:45px; }
    .graduation-pagination a,.graduation-pagination span { display:grid; place-items:center; min-width:42px; height:42px; padding:0 12px; border:1px solid var(--grad-line); border-radius:11px; background:#fff; color:var(--grad-ink); text-decoration:none; font-size:.86rem; font-weight:800; }
    .graduation-pagination .active { background:var(--grad-ink); border-color:var(--grad-ink); color:#fff; }

    @keyframes heroImageReveal { from{opacity:0;transform:scale(1.12)} to{opacity:.88;transform:scale(1.04)} }
    @keyframes fadeUp { from{opacity:0;transform:translateY(22px)} to{opacity:1;transform:translateY(0)} }
    @keyframes lineReveal { from{opacity:0;width:0} to{opacity:1;width:72px} }
    @keyframes revealCard { from{opacity:0;transform:translateY(30px)} to{opacity:1;transform:translateY(0)} }
    @keyframes galleryReveal { from{opacity:0;transform:translateY(18px)} to{opacity:1;transform:translateY(0)} }
    @keyframes graduateReveal { from{opacity:0;transform:translateY(28px)} to{opacity:1;transform:translateY(0)} }

    @media (max-width:1050px) { .graduates-grid{grid-template-columns:repeat(3,minmax(0,1fr));} }
    @media (max-width:900px) {
        .graduation-layout{grid-template-columns:1fr}.graduation-sidebar{position:static}.graduates-grid{grid-template-columns:repeat(2,minmax(0,1fr));}
    }
    @media (max-width:650px) {
        .graduation-hero{min-height:400px}.graduation-hero-content,.graduation-content{width:min(100% - 30px,1180px)}
        .graduation-hero-content{padding:65px 0 45px}.graduation-content{padding:55px 0 70px}
        .graduation-gallery{grid-template-columns:1fr}.graduation-gallery-item,.graduation-gallery-item:first-child{grid-column:span 1;min-height:240px}
        .graduates-section{margin-top:60px}.graduates-heading{display:block}.graduates-count{margin-top:18px}.graduates-grid{grid-template-columns:1fr;gap:17px}.graduate-image{height:280px}
    }
    @media (prefers-reduced-motion:reduce) { *,*::before,*::after{scroll-behavior:auto!important;animation-duration:.01ms!important;animation-iteration-count:1!important;transition-duration:.01ms!important;} }
</style>
@endsection

@section('content')
@php
    $ceremonyDate = \Carbon\Carbon::parse($graduation->ceremony_date);
    $imageMedia = $graduation->media->first(fn ($media) => $media->type === 'image');
    $galleryMedia = $graduation->media;
@endphp

<div class="graduation-page">
    <section class="graduation-hero">
        @if($imageMedia)
            <div class="graduation-hero-image">
                <img src="{{ $imageMedia->thumbnailUrl() }}" alt="{{ $imageMedia->alt_text ?: 'Graduation ceremony' }}">
            </div>
        @endif

        <div class="graduation-hero-content">
            <span class="graduation-eyebrow">LIU Digital Yearbook</span>
            <h1>Graduation<br>Ceremony</h1>
            <p class="graduation-date">
                <span class="graduation-date-icon" aria-hidden="true">◆</span>
                {{ $ceremonyDate->format('F d, Y') }}
            </p>
            <div class="hero-accent-line"></div>
        </div>
    </section>

    <main class="graduation-content">
        <div class="graduation-layout">
            <article class="ceremony-card">
                <div class="section-heading">
                    <span class="section-label">The Celebration</span>
                    <h2>Ceremony Details</h2>
                </div>

                @if($graduation->description)
                    <div class="ceremony-description">
                        {!! \App\Support\RichText::sanitize($graduation->description) !!}
                    </div>
                @else
                    <p class="ceremony-description">A celebration of achievement, memories, and the next chapter for the graduating class.</p>
                @endif

                @if($galleryMedia->isNotEmpty())
                    <div class="gallery-heading">
                        <h2>Ceremony Media</h2>
                        <span>{{ $galleryMedia->count() }} {{ $galleryMedia->count() === 1 ? 'item' : 'items' }}</span>
                    </div>

                    <div class="graduation-gallery">
                        @foreach($galleryMedia as $index => $media)
                            <div class="graduation-gallery-item" style="animation-delay: {{ min($index * 70, 500) }}ms;">
                                @if($media->type === 'image')
                                    <img
                                        src="{{ $media->thumbnailUrl() }}"
                                        alt="{{ $media->alt_text ?: ($media->caption ?: 'Graduation ceremony photo') }}"
                                        loading="lazy">
                                    <div class="gallery-overlay">
                                        <span class="gallery-overlay-icon">⌕</span>
                                        {{ $media->caption ?: 'View photo' }}
                                    </div>
                                @elseif($media->type === 'video')
                                    <video controls preload="metadata" aria-label="{{ $media->caption ?: 'Graduation ceremony video' }}">
                                        <source src="{{ Storage::disk('public')->url($media->path) }}">
                                        Your browser does not support video playback.
                                    </video>
                                    <div class="gallery-overlay">
                                        <span class="gallery-overlay-icon">▶</span>
                                        {{ $media->caption ?: 'Graduation video' }}
                                    </div>
                                @else
                                    <div class="media-document">
                                        <div class="media-document-icon" aria-hidden="true">▤</div>
                                        <strong>{{ $media->file_name }}</strong>
                                        @if($media->caption)<span>{{ $media->caption }}</span>@endif
                                        <a href="{{ Storage::disk('public')->url($media->path) }}" target="_blank" rel="noopener noreferrer">Open document</a>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </article>

            <aside class="graduation-sidebar">
                <div class="details-card">
                    <div class="details-header">
                        <div class="details-icon" aria-hidden="true">▣</div>
                        <h3>At a Glance</h3>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Date</span>
                        <span class="detail-value">{{ $ceremonyDate->format('F d, Y') }}</span>
                    </div>
                    @if($graduation->venue)
                        <div class="detail-item">
                            <span class="detail-label">Venue</span>
                            <span class="detail-value">{{ $graduation->venue }}</span>
                        </div>
                    @endif
                    @if($graduation->academicYear)
                        <div class="detail-item">
                            <span class="detail-label">Academic Year</span>
                            <span class="detail-value">{{ $graduation->academicYear->title }}</span>
                        </div>
                    @endif
                </div>
            </aside>
        </div>

        <section class="graduates-section">
            <div class="graduates-heading">
                <div>
                    <span class="section-label">The Class</span>
                    <h2>Graduates</h2>
                </div>
                <span class="graduates-count">{{ $graduates->total() }} {{ $graduates->total() === 1 ? 'Graduate' : 'Graduates' }}</span>
            </div>

            @if($graduates->count() > 0)
                <div class="graduates-grid">
                    @foreach($graduates as $index => $graduate)
                        @php $portrait = $graduate->media->first(); @endphp
                        <a href="{{ route('public.graduate.detail', $graduate->id) }}" class="graduate-link" style="animation-delay: {{ min($index * 80, 560) }}ms;">
                            <article class="graduate-card">
                                <div class="graduate-image">
                                    @if($portrait)
                                        <img src="{{ $portrait->thumbnailUrl() }}" alt="{{ $graduate->name }}" loading="lazy">
                                    @else
                                        <div class="graduate-placeholder">{{ strtoupper(substr($graduate->name, 0, 1)) }}</div>
                                    @endif
                                </div>
                                <div class="graduate-body">
                                    <h3 class="graduate-name">{{ $graduate->name }}</h3>
                                    <p class="graduate-info">
                                        {{ $graduate->major?->name ?: 'Graduate' }}
                                        @if($graduate->school)<br><span>{{ $graduate->school->name }}</span>@endif
                                    </p>
                                </div>
                            </article>
                        </a>
                    @endforeach
                </div>

                @if($graduates->hasPages())
                    <div class="graduation-pagination">{!! $graduates->links() !!}</div>
                @endif
            @else
                <div class="empty-graduates">
                    <h3>No published graduates yet</h3>
                    <p>The ceremony is ready, but graduate profiles have not been published yet.</p>
                </div>
            @endif
        </section>
    </main>
</div>
@endsection
