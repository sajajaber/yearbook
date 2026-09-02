@extends('public.layout')

@section('title', $graduate->name)

@section('extra-css')
<style>
    .profile-hero {
        background: linear-gradient(135deg, var(--ink) 0%, #1a3f7f 100%);
        padding: 56px 20px 90px;
        position: relative;
        overflow: hidden;
    }

    .profile-hero::after {
        content: "";
        position: absolute;
        width: 420px;
        height: 420px;
        border-radius: 50%;
        border: 1px solid rgba(255, 176, 52, .22);
        left: -160px;
        bottom: -220px;
    }

    .profile-header {
        display: grid;
        grid-template-columns: 260px 1fr;
        gap: 44px;
        align-items: end;
        position: relative;
    }

    .profile-photo {
        border-radius: 16px;
        overflow: hidden;
        height: 320px;
        background: linear-gradient(135deg, #24406f 0%, #163261 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 30px 50px -18px rgba(0, 0, 0, .45);
        transition: transform .5s cubic-bezier(.16, 1, .3, 1);
    }

    .profile-photo:hover {
        transform: rotate(-1deg) scale(1.02);
    }

    .profile-photo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .profile-photo-initial {
        font-family: "Merriweather", Georgia, serif;
        font-size: 5.5rem;
        font-weight: 700;
        color: #fff;
    }

    .profile-info h1 {
        font-family: "Merriweather", Georgia, serif;
        font-size: clamp(28px, 4vw, 42px);
        font-weight: 700;
        margin: 0 0 14px;
        color: #fff;
    }

    .profile-badges {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-bottom: 22px;
    }

    .badge {
        display: inline-block;
        padding: 7px 15px;
        background: rgba(255, 255, 255, .12);
        border: 1px solid rgba(255, 255, 255, .25);
        border-radius: 999px;
        font-size: 12px;
        font-weight: 600;
        color: #fff;
    }

    .profile-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    /* Content grid */

    .profile-body {
        display: grid;
        grid-template-columns: 1fr 300px;
        gap: 32px;
        margin-top: -56px;
        position: relative;
    }

    @media (max-width: 900px) {
        .profile-header { grid-template-columns: 1fr; }
        .profile-photo { height: 280px; max-width: 260px; }
        .profile-body { grid-template-columns: 1fr; margin-top: 24px; }
    }

    .section-card {
        background: var(--white);
        padding: 30px;
        border-radius: 16px;
        margin-bottom: 24px;
        box-shadow: var(--shadow-soft, 0 1px 3px rgba(0,0,0,.08));
        border: 1px solid var(--line);
    }

    .section-card h2 {
        font-family: "Merriweather", Georgia, serif;
        font-size: 1.4rem;
        font-weight: 700;
        margin: 0 0 18px;
        color: var(--ink);
        padding-bottom: 14px;
        border-bottom: 2px solid var(--red);
        display: inline-block;
    }

    .section-card p {
        color: var(--ink-soft);
        line-height: 1.8;
        margin: 0 0 14px;
    }

    .achievements-list { list-style: none; padding: 0; margin: 0; }

    .achievements-list li {
        padding: 12px 0;
        border-bottom: 1px solid var(--line);
        color: var(--ink-soft);
        display: flex;
        gap: 10px;
    }

    .achievements-list li:last-child { border-bottom: none; }

    .achievements-list li::before {
        content: "\2713";
        color: var(--red);
        font-weight: 700;
        flex: none;
    }

    .quote-box {
        background: linear-gradient(135deg, var(--paper) 0%, rgba(255, 176, 52, .08) 100%);
        padding: 24px;
        border-left: 4px solid var(--red);
        border-radius: 10px;
        font-style: italic;
        font-family: "Merriweather", Georgia, serif;
        color: var(--ink);
        margin: 22px 0;
        font-size: 1.05rem;
    }

    .sidebar-widget {
        background: var(--white);
        padding: 22px;
        border-radius: 16px;
        border: 1px solid var(--line);
        margin-bottom: 20px;
    }

    .sidebar-widget h3 {
        font-size: .95rem;
        font-weight: 700;
        margin: 0 0 14px;
        color: var(--ink);
        text-transform: uppercase;
        letter-spacing: .5px;
    }

    .sidebar-widget p {
        font-size: .92rem;
        color: var(--ink-soft);
        line-height: 1.6;
        margin: 0 0 10px;
    }

    .qr-section { text-align: center; }

    .qr-section img {
        border-radius: 10px;
        border: 1px solid var(--line);
        padding: 14px;
        background: #fff;
    }

    .share-buttons {
        display: flex;
        gap: 10px;
        justify-content: center;
        margin-top: 15px;
    }

    .share-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: var(--paper);
        color: var(--ink);
        text-decoration: none;
        font-weight: 700;
        border: 1px solid var(--line);
        transition: all var(--transition-fast, .2s ease);
    }

    .share-btn:hover {
        background: var(--red);
        border-color: var(--red);
        transform: translateY(-3px);
    }

    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 14px;
    }

    .gallery-grid img {
        border-radius: 10px;
        width: 100%;
        height: 150px;
        object-fit: cover;
        transition: transform var(--transition-base, .3s ease);
    }

    .gallery-grid img:hover {
        transform: scale(1.04);
    }

    /* Print fallback for this page specifically */
    @media print {
        .profile-hero { background: #fff !important; padding: 0; }
        .profile-info h1 { color: #000 !important; }
        .badge { border-color: #999; color: #000; background: none; }
        .profile-actions, .qr-section, .share-buttons { display: none !important; }
        .profile-body { margin-top: 20px; grid-template-columns: 1fr; }
        .section-card, .sidebar-widget { box-shadow: none; border: 1px solid #ccc; break-inside: avoid; }
    }
</style>
@endsection

@section('content')
<div class="profile-hero">
    <div class="container">
        <div class="profile-header">
            <div class="profile-photo">
                @php $portrait = $graduate->media->first(); @endphp
                @if($portrait)
                    <img src="{{ Storage::disk('public')->url($portrait->path) }}" alt="{{ $graduate->name }}">
                @else
                    <div class="profile-photo-initial">{{ strtoupper(substr($graduate->name, 0, 1)) }}</div>
                @endif
            </div>

            <div class="profile-info">
                <h1>{{ $graduate->name }}</h1>
                <div class="profile-badges">
                    <span class="badge">{{ $graduate->major->name ?? 'Major' }}</span>
                    <span class="badge">{{ $graduate->school->name ?? 'School' }}</span>
                    <span class="badge">{{ $graduate->campus->name ?? 'Campus' }}</span>
                    @if($graduate->graduation)
                        <span class="badge">Class of {{ \Carbon\Carbon::parse($graduate->graduation->ceremony_date)->format('Y') }}</span>
                    @endif
                </div>
                <div class="profile-actions print-hide">
                    <a href="{{ route('public.graduate.pdf', $graduate->id) }}" class="btn btn-primary">
                        Download PDF <span aria-hidden="true">&darr;</span>
                    </a>
                    <button type="button" class="btn btn-outline" onclick="window.print()">
                        Print page
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="profile-body">
        <div>
            @if($graduate->profile_text)
            <div class="section-card">
                <h2>Biography</h2>
                <p>{!! nl2br(e($graduate->profile_text)) !!}</p>
            </div>
            @endif

            @if($graduate->quote)
            <div class="quote-box">&ldquo;{{ $graduate->quote }}&rdquo;</div>
            @endif

            @if($graduate->achievements)
            <div class="section-card">
                <h2>Academic Achievements</h2>
                <ul class="achievements-list">
                    @foreach((is_array($graduate->achievements) ? $graduate->achievements : array_filter(explode("\n", $graduate->achievements))) as $achievement)
                    <li>{{ trim($achievement) }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            @if($graduate->activities)
            <div class="section-card">
                <h2>University Activities</h2>
                <ul class="achievements-list">
                    @foreach((is_array($graduate->activities) ? $graduate->activities : array_filter(explode("\n", $graduate->activities))) as $activity)
                    <li>{{ trim($activity) }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            @if($graduate->projects)
            <div class="section-card">
                <h2>Projects &amp; Research</h2>
                <p>{!! nl2br(e(is_array($graduate->projects) ? implode("\n", $graduate->projects) : $graduate->projects)) !!}</p>
            </div>
            @endif

            @if($graduate->internships)
            <div class="section-card">
                <h2>Professional Experience</h2>
                <p>{!! nl2br(e(is_array($graduate->internships) ? implode("\n", $graduate->internships) : $graduate->internships)) !!}</p>
            </div>
            @endif

            @if($graduate->future_plans)
            <div class="section-card">
                <h2>Future Plans</h2>
                <p>{!! nl2br(e($graduate->future_plans)) !!}</p>
            </div>
            @endif

            @if($graduate->media->count() > 1)
            <div class="section-card">
                <h2>Photo Gallery</h2>
                <div class="gallery-grid">
                    @foreach($graduate->media as $media)
                    <img src="{{ Storage::disk('public')->url($media->path) }}" alt="{{ $graduate->name }}">
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <aside class="print-hide">
            <div class="sidebar-widget">
                <h3>Share Profile</h3>
                <p>Share this profile with others</p>
                <div class="share-buttons">
                    <a href="javascript:void(0)" class="share-btn" title="Copy Link" onclick="copyLink()">&#128279;</a>
                    <a href="javascript:void(0)" class="share-btn" title="Share on Facebook">f</a>
                    <a href="javascript:void(0)" class="share-btn" title="Share on Twitter">&#120143;</a>
                </div>
            </div>

            <div class="sidebar-widget qr-section">
                <h3>QR Code</h3>
                <p>Scan to view this profile</p>
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode($qrUrl) }}" alt="QR Code">
            </div>

            <div class="sidebar-widget">
                <h3>Quick Facts</h3>
                @if($graduate->graduation)
                <p><strong style="color: var(--ink);">Graduation Year</strong><br>
                    {{ \Carbon\Carbon::parse($graduate->graduation->ceremony_date)->format('Y') }}</p>
                @endif
                <p><strong style="color: var(--ink);">School</strong><br>{{ $graduate->school->name ?? 'N/A' }}</p>
                <p><strong style="color: var(--ink);">Major</strong><br>{{ $graduate->major->name ?? 'N/A' }}</p>
                <p><strong style="color: var(--ink);">Campus</strong><br>{{ $graduate->campus->name ?? 'N/A' }}</p>
            </div>
        </aside>
    </div>
</div>
@endsection

@section('extra-js')
<script>
function copyLink() {
    navigator.clipboard.writeText(window.location.href).then(() => {
        alert('Profile link copied to clipboard!');
    });
}
</script>
@endsection
