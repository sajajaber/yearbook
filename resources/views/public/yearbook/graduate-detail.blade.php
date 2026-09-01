@extends('public.layout')

@section('title', $graduate->name)

@section('extra-css')
<style>
    .profile-header {
        display: grid;
        grid-template-columns: 300px 1fr;
        gap: 40px;
        margin-bottom: 40px;
        align-items: start;
    }

    .profile-photo {
        border-radius: 12px;
        overflow: hidden;
        height: 400px;
        background: linear-gradient(135deg, var(--ink) 0%, #1a3f7f 100%);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .profile-photo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .profile-photo-initial {
        font-size: 6rem;
        font-weight: 800;
        color: white;
    }

    .profile-info h1 {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 10px;
        color: var(--ink);
    }

    .profile-badges {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }

    .badge {
        display: inline-block;
        padding: 8px 16px;
        background: var(--paper);
        border-radius: 6px;
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--ink);
    }

    .profile-meta {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
        margin-bottom: 30px;
    }

    .meta-item {
        background: var(--paper);
        padding: 15px;
        border-radius: 8px;
    }

    .meta-label {
        font-size: 0.85rem;
        color: var(--ink-soft);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 5px;
    }

    .meta-value {
        font-size: 1rem;
        font-weight: 700;
        color: var(--ink);
    }

    .profile-body {
        display: grid;
        grid-template-columns: 1fr 300px;
        gap: 40px;
    }

    .section-card {
        background: var(--white);
        padding: 30px;
        border-radius: 12px;
        margin-bottom: 30px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .section-card h2 {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 20px;
        color: var(--ink);
        border-bottom: 2px solid var(--red);
        padding-bottom: 15px;
    }

    .section-card p {
        color: var(--ink-soft);
        line-height: 1.8;
        margin-bottom: 15px;
    }

    .achievements-list {
        list-style: none;
    }

    .achievements-list li {
        padding: 12px 0;
        border-bottom: 1px solid var(--line);
        color: var(--ink-soft);
    }

    .achievements-list li:last-child {
        border-bottom: none;
    }

    .achievements-list li::before {
        content: '✓ ';
        color: var(--red);
        font-weight: 700;
        margin-right: 8px;
    }

    .sidebar-widget {
        background: var(--white);
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }

    .sidebar-widget h3 {
        font-size: 1rem;
        font-weight: 700;
        margin-bottom: 15px;
        color: var(--ink);
    }

    .sidebar-widget p {
        font-size: 0.95rem;
        color: var(--ink-soft);
        line-height: 1.6;
    }

    .quote-box {
        background: linear-gradient(135deg, var(--paper) 0%, rgba(255, 176, 52, 0.05) 100%);
        padding: 20px;
        border-left: 4px solid var(--red);
        border-radius: 8px;
        font-style: italic;
        color: var(--ink);
        margin: 20px 0;
    }

    .qr-section {
        text-align: center;
        padding: 20px;
        background: var(--paper);
        border-radius: 8px;
    }

    .qr-section p {
        font-size: 0.9rem;
        color: var(--ink-soft);
        margin-bottom: 15px;
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
        background: var(--red);
        color: white;
        text-decoration: none;
        transition: all 0.3s ease;
        font-weight: 700;
    }

    .share-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 15px -3px rgba(255, 176, 52, 0.2);
    }

    @media (max-width: 768px) {
        .profile-header {
            grid-template-columns: 1fr;
        }

        .profile-photo {
            height: 300px;
        }

        .profile-body {
            grid-template-columns: 1fr;
        }

        .profile-meta {
            grid-template-columns: 1fr;
        }

        .profile-info h1 {
            font-size: 1.8rem;
        }
    }
</style>
@endsection

@section('content')
<!-- Hero Background -->
<div style="background: linear-gradient(135deg, var(--ink) 0%, var(--primary-light) 100%); padding: 40px 20px; margin-bottom: 40px;">
    <div class="container">
        <div class="profile-header">
            <div class="profile-photo">
                @php
                    $portrait = $graduate->media->first();
                @endphp
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
                </div>

                <div class="profile-meta">
                    <div class="meta-item">
                        <div class="meta-label">School</div>
                        <div class="meta-value">{{ $graduate->school->name ?? 'N/A' }}</div>
                    </div>
                    <div class="meta-item">
                        <div class="meta-label">Major</div>
                        <div class="meta-value">{{ $graduate->major->name ?? 'N/A' }}</div>
                    </div>
                    <div class="meta-item">
                        <div class="meta-label">Campus</div>
                        <div class="meta-value">{{ $graduate->campus->name ?? 'N/A' }}</div>
                    </div>
                    <div class="meta-item">
                        <div class="meta-label">Graduation</div>
                        <div class="meta-value">
                            @if($graduate->graduation)
                                {{ \Carbon\Carbon::parse($graduate->graduation->ceremony_date)->format('Y') }}
                            @else
                                N/A
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Content -->
<div class="section">
    <div class="container">
        <div class="profile-body">
            <div>
                <!-- Biography -->
                @if($graduate->profile_text)
                <div class="section-card">
                    <h2>Biography</h2>
                    <p>{!! nl2br(e($graduate->profile_text)) !!}</p>
                </div>
                @endif

                <!-- Quote -->
                @if($graduate->quote)
                <div class="quote-box">
                    "{{ $graduate->quote }}"
                </div>
                @endif

                <!-- Achievements -->
                @if($graduate->achievements)
                <div class="section-card">
                    <h2>Academic Achievements</h2>
                    <ul class="achievements-list">
                        @foreach(array_filter(explode("\n", $graduate->achievements)) as $achievement)
                        <li>{{ trim($achievement) }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- Activities -->
                @if($graduate->activities)
                <div class="section-card">
                    <h2>University Activities</h2>
                    <ul class="achievements-list">
                        @foreach(array_filter(explode("\n", $graduate->activities)) as $activity)
                        <li>{{ trim($activity) }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- Projects -->
                @if($graduate->projects)
                <div class="section-card">
                    <h2>Projects & Research</h2>
                    <p>{!! nl2br(e($graduate->projects)) !!}</p>
                </div>
                @endif

                <!-- Internships -->
                @if($graduate->internships)
                <div class="section-card">
                    <h2>Professional Experience</h2>
                    <p>{!! nl2br(e($graduate->internships)) !!}</p>
                </div>
                @endif

                <!-- Future Plans -->
                @if($graduate->future_plans)
                <div class="section-card">
                    <h2>Future Plans</h2>
                    <p>{!! nl2br(e($graduate->future_plans)) !!}</p>
                </div>
                @endif

                <!-- Media Gallery -->
                @if($graduate->media->count() > 1)
                <div class="section-card">
                    <h2>Photo Gallery</h2>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px;">
                        @foreach($graduate->media as $media)
                        <img src="{{ Storage::disk('public')->url($media->path) }}" alt="{{ $graduate->name }}" style="border-radius: 8px; width: 100%; height: 150px; object-fit: cover;">
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <aside>
                <!-- Share Profile -->
                <div class="sidebar-widget">
                    <h3>Share Profile</h3>
                    <p style="font-size: 0.9rem; margin-bottom: 15px;">Share this profile with others</p>
                    <div class="share-buttons">
                        <a href="javascript:void(0)" class="share-btn" title="Copy Link" onclick="copyLink()">🔗</a>
                        <a href="javascript:void(0)" class="share-btn" title="Share on Facebook">f</a>
                        <a href="javascript:void(0)" class="share-btn" title="Share on Twitter">𝕏</a>
                    </div>
                </div>

                <!-- QR Code -->
                <div class="sidebar-widget qr-section">
                    <h3>QR Code</h3>
                    <p>Scan to view this profile</p>
                    <div style="background: white; padding: 15px; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode(route('public.graduate.detail', $graduate->id)) }}" alt="QR Code">
                    </div>
                </div>

                <!-- Quick Facts -->
                <div class="sidebar-widget">
                    <h3>Quick Facts</h3>
                    @if($graduate->graduation)
                    <p>
                        <strong style="color: var(--ink);">Graduation Year</strong><br>
                        {{ \Carbon\Carbon::parse($graduate->graduation->ceremony_date)->format('Y') }}
                    </p>
                    @endif
                    <p>
                        <strong style="color: var(--ink);">School</strong><br>
                        {{ $graduate->school->name ?? 'N/A' }}
                    </p>
                    <p>
                        <strong style="color: var(--ink);">Major</strong><br>
                        {{ $graduate->major->name ?? 'N/A' }}
                    </p>
                    <p>
                        <strong style="color: var(--ink);">Campus</strong><br>
                        {{ $graduate->campus->name ?? 'N/A' }}
                    </p>
                </div>
            </aside>
        </div>
    </div>
</div>

<script>
function copyLink() {
    navigator.clipboard.writeText(window.location.href).then(() => {
        alert('Profile link copied to clipboard!');
    });
}
</script>
@endsection
