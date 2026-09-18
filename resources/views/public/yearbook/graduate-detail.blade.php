@extends('public.layout')

@section('title', $graduate->name . ' | LIU Digital Yearbook')

@section('extra-css')
    @vite('resources/css/pages/graduate-detail.css')
@endsection

@section('content')
<div class="profile-hero">
    <div class="container">
        <div class="profile-header">
            <div class="profile-photo">@php $portrait=$graduate->media->first(); @endphp @if($portrait)<img src="{{ Storage::disk('public')->url($portrait->path) }}" alt="{{ $graduate->name }}">@else<div class="profile-photo-initial">{{ strtoupper(substr($graduate->name,0,1)) }}</div>@endif</div>
            <div class="profile-info">
                <h1>{{ $graduate->name }}</h1>
                <div class="profile-badges"><span class="badge">{{ $graduate->major->name ?? 'Major' }}</span><span class="badge">{{ $graduate->school->name ?? 'School' }}</span><span class="badge">{{ $graduate->campus->name ?? 'Campus' }}</span>@if($graduate->graduation)<span class="badge">Class of {{ \Carbon\Carbon::parse($graduate->graduation->ceremony_date)->format('Y') }}</span>@endif</div>
                <div class="profile-actions print-hide"><a href="{{ route('public.graduate.pdf', ['student_reference' => $graduate->student_reference]) }}" class="btn btn-primary" style="color: white">Download PDF <span aria-hidden="true">&darr;</span></a></div>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="profile-body">
        <div>
            @if($graduate->profile_text)<div class="section-card scroll-reveal">
                <h2>Biography</h2>
                <p>{!! nl2br(e($graduate->profile_text)) !!}</p>
            </div>@endif
            @if($graduate->quote)<div class="quote-box scroll-reveal">&ldquo;{{ $graduate->quote }}&rdquo;</div>@endif
            @if($graduate->achievements)<div class="section-card scroll-reveal">
                <h2>Academic Achievements</h2>
                <ul class="achievements-list">@foreach(is_array($graduate->achievements)?$graduate->achievements:array_filter(explode("\n",$graduate->achievements)) as $achievement)<li>{{ trim($achievement) }}</li>@endforeach</ul>
            </div>@endif
            @if($graduate->activities)<div class="section-card scroll-reveal">
                <h2>University Activities</h2>
                <ul class="achievements-list">@foreach(is_array($graduate->activities)?$graduate->activities:array_filter(explode("\n",$graduate->activities)) as $activity)<li>{{ trim($activity) }}</li>@endforeach</ul>
            </div>@endif
            @if($graduate->projects)<div class="section-card scroll-reveal">
                <h2>Projects &amp; Research</h2>
                <p>{!! nl2br(e(is_array($graduate->projects)?implode("\n",$graduate->projects):$graduate->projects)) !!}</p>
            </div>@endif
            @if($graduate->internships)<div class="section-card scroll-reveal">
                <h2>Professional Experience</h2>
                <p>{!! nl2br(e(is_array($graduate->internships)?implode("\n",$graduate->internships):$graduate->internships)) !!}</p>
            </div>@endif
            @if($graduate->future_plans)<div class="section-card scroll-reveal">
                <h2>Future Plans</h2>
                <p>{!! nl2br(e($graduate->future_plans)) !!}</p>
            </div>@endif
            @if($graduate->resumeMedia)<div class="resume-card scroll-reveal">
                <div class="resume-copy">
                    <div class="resume-icon" aria-hidden="true">CV</div>
                    <p class="resume-eyebrow">Professional Profile</p>
                    <h2>Resume / CV</h2>
                    <p>A closer look at this graduate's academic and professional journey.</p>
                </div><a href="{{ route('public.graduate.resume', ['student_reference' => $graduate->student_reference]) }}" class="resume-button" target="_blank" rel="noopener">View Resume <span aria-hidden="true">&rarr;</span></a>
            </div>@endif
            @if($graduate->media->count()>1)<div class="section-card scroll-reveal">
                <h2>Photo Gallery</h2>
                <div class="gallery-grid">@foreach($graduate->media as $media)<div class="gallery-item"><img src="{{ Storage::disk('public')->url($media->path) }}" alt="{{ $graduate->name }}" loading="lazy"></div>@endforeach</div>
            </div>@endif
        </div>
        <aside class="print-hide">
            <div class="sidebar-widget scroll-reveal">
                <h3>Share Profile</h3>
                <p>Share this profile with others</p>
                <div class="share-buttons"><a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($qrUrl) }}" class="share-btn facebook-btn" title="Share on Facebook" aria-label="Share on Facebook" target="_blank" rel="noopener noreferrer">f</a><a href="https://twitter.com/intent/tweet?url={{ urlencode($qrUrl) }}&text={{ urlencode("Check out {$graduate->name}'s graduate profile.") }}" class="share-btn x-btn" title="Share on X" aria-label="Share on X" target="_blank" rel="noopener noreferrer">𝕏</a><a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode($qrUrl) }}" class="share-btn linkedin-btn" title="Share on LinkedIn" aria-label="Share on LinkedIn" target="_blank" rel="noopener noreferrer">in</a></div>
            </div>
            <div class="sidebar-widget qr-section scroll-reveal">
                <h3>QR Code</h3>
                <p>Scan to view this profile</p><img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode($qrUrl) }}" alt="QR Code for {{ $graduate->name }}'s profile" loading="lazy">
            </div>
            <div class="sidebar-widget scroll-reveal">
                <h3>Quick Facts</h3>@if($graduate->graduation)<div class="quick-fact"><span class="quick-fact-label">Graduation Year</span><span class="quick-fact-value">{{ \Carbon\Carbon::parse($graduate->graduation->ceremony_date)->format('Y') }}</span></div>@endif<div class="quick-fact"><span class="quick-fact-label">School</span><span class="quick-fact-value">{{ $graduate->school->name ?? 'N/A' }}</span></div>
                <div class="quick-fact"><span class="quick-fact-label">Major</span><span class="quick-fact-value">{{ $graduate->major->name ?? 'N/A' }}</span></div>
                <div class="quick-fact"><span class="quick-fact-label">Campus</span><span class="quick-fact-value">{{ $graduate->campus->name ?? 'N/A' }}</span></div>@if($graduate->gpa !== null && $graduate->gpa !== '')<div class="quick-fact"><span class="quick-fact-label">GPA</span><span class="quick-fact-value">{{ number_format((float) $graduate->gpa, 2) }}{{ $graduate->gpa_scale ? ' / ' . number_format((float) $graduate->gpa_scale, 2) : '' }}</span></div>@endif
            </div>
        </aside>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const e = document.querySelectorAll('.scroll-reveal');
        if (!e.length) return;
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            e.forEach(x => x.classList.add('is-visible'));
            return
        }
        const o = new IntersectionObserver(function(a, o) {
            a.forEach(x => {
                if (x.isIntersecting) {
                    x.target.classList.add('is-visible');
                    o.unobserve(x.target)
                }
            })
        }, {
            threshold: .12,
            rootMargin: '0px 0px -40px'
        });
        e.forEach(x => o.observe(x))
    });
</script>
@endsection