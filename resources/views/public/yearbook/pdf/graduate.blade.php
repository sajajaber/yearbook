<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
@page { margin:48px 46px 54px; }
body { font-family:"DejaVu Serif",Georgia,serif; color:#1b2a3d; font-size:11px; line-height:1.6; }
.top-line { border-top:4px solid #ffb034; margin-bottom:18px; }
.header-band { background:#002a5c; color:#fff; padding:24px 26px; }
.header-table { width:100%; border-collapse:collapse; }
.header-table td { vertical-align:middle; }
.portrait-cell { width:116px; }
.portrait { width:102px; height:102px; object-fit:cover; }
.portrait-fallback { width:102px; height:102px; background:#0a3b70; color:#fff; text-align:center; line-height:102px; font-family:"DejaVu Sans",Arial,sans-serif; font-size:34px; font-weight:bold; }
.eyebrow { color:#ffce6b; font-family:"DejaVu Sans",Arial,sans-serif; font-size:8px; font-weight:bold; letter-spacing:2px; text-transform:uppercase; margin-bottom:7px; }
.name { color:#fff; font-size:25px; line-height:1.2; margin:0 0 9px; }
.badge { display:inline-block; border:1px solid rgba(255,255,255,.35); color:#fff; padding:4px 8px; margin:0 5px 5px 0; font-family:"DejaVu Sans",Arial,sans-serif; font-size:8px; }
.section { margin-top:24px; }
h2.section-heading { color:#002a5c; font-family:"DejaVu Sans",Arial,sans-serif; font-size:10px; font-weight:bold; letter-spacing:1.7px; text-transform:uppercase; border-bottom:2px solid #ffb034; padding-bottom:6px; margin:0 0 10px; }
p { margin:0 0 10px; color:#333; }
.quote { border-left:4px solid #ffb034; background:#f7fbff; padding:12px 16px; margin:18px 0; color:#002a5c; font-size:13px; line-height:1.7; font-style:italic; }
ul.achievements { margin:0; padding-left:18px; }
ul.achievements li { margin-bottom:5px; color:#333; }
.facts-table { width:100%; border-collapse:collapse; margin-top:5px; }
.facts-table td { padding:7px 0; border-bottom:1px solid #e0e6ef; font-family:"DejaVu Sans",Arial,sans-serif; font-size:9px; }
.facts-label { color:#64748b; width:35%; font-weight:bold; }
.links-table { width:100%; border-collapse:collapse; }
.links-table td { padding:5px 0; font-family:"DejaVu Sans",Arial,sans-serif; font-size:9px; }
.links-table a { color:#002a5c; text-decoration:none; }
.footer-note { margin-top:34px; padding-top:10px; border-top:1px solid #d8e3ef; color:#94a3b8; font-family:"DejaVu Sans",Arial,sans-serif; font-size:8px; text-align:center; }
</style>
</head>
<body>
<div class="top-line"></div>
<div class="header-band">
<table class="header-table"><tr>
<td class="portrait-cell">
@if($graduate->portraitMedia)
<img class="portrait" src="{{ public_path('storage/' . $graduate->portraitMedia->path) }}" alt="{{ $graduate->name }}">
@elseif($graduate->media->first())
<img class="portrait" src="{{ public_path('storage/' . $graduate->media->first()->path) }}" alt="{{ $graduate->name }}">
@else
<div class="portrait-fallback">{{ strtoupper(substr($graduate->name,0,1)) }}</div>
@endif
</td>
<td>
<div class="eyebrow">LIU Digital Yearbook</div>
<div class="name">{{ $graduate->name }}</div>
<span class="badge">{{ ucfirst($graduate->degree_level ?? 'Graduate') }}</span>
<span class="badge">{{ $graduate->major->name ?? 'Major' }}</span>
<span class="badge">{{ $graduate->school->name ?? 'School' }}</span>
<span class="badge">{{ $graduate->campus->name ?? 'Campus' }}</span>
@if($graduate->graduation)
<span class="badge">Class of {{ \Carbon\Carbon::parse($graduate->graduation->ceremony_date)->format('Y') }}</span>
@endif
</td>
</tr></table>
</div>

<div class="section">
<h2 class="section-heading">Identification</h2>
<table class="facts-table">
@if($graduate->student_reference)
<tr><td class="facts-label">Student Reference</td><td>{{ $graduate->student_reference }}</td></tr>
@endif
<tr><td class="facts-label">Degree Level</td><td>{{ ucfirst($graduate->degree_level ?? 'Graduate') }}</td></tr>
@if($graduate->graduation)
<tr><td class="facts-label">Graduation Year</td><td>{{ \Carbon\Carbon::parse($graduate->graduation->ceremony_date)->format('Y') }}</td></tr>
@endif
<tr><td class="facts-label">School</td><td>{{ $graduate->school->name ?? 'N/A' }}</td></tr>
<tr><td class="facts-label">Major</td><td>{{ $graduate->major->name ?? 'N/A' }}</td></tr>
<tr><td class="facts-label">Campus</td><td>{{ $graduate->campus->name ?? 'N/A' }}</td></tr>
</table>
</div>

@if($graduate->profile_text)
<div class="section"><h2 class="section-heading">Profile</h2><p>{{ $graduate->profile_text }}</p></div>
@endif

@if($graduate->quote)
<div class="quote">&ldquo;{{ $graduate->quote }}&rdquo;</div>
@endif

@if($graduate->gpa !== null || $graduate->achievements || $graduate->projects)
<div class="section">
<h2 class="section-heading">Academic Highlights</h2>
@if($graduate->gpa !== null)<p><strong>GPA:</strong> {{ number_format((float) $graduate->gpa, 2) }} / 4.00</p>@endif
@if($graduate->achievements)
<ul class="achievements">
@foreach((is_array($graduate->achievements) ? $graduate->achievements : array_filter(explode("\n", $graduate->achievements))) as $item)
<li>{{ trim($item) }}</li>
@endforeach
</ul>
@endif
@if($graduate->projects)
<p><strong>Projects / Research:</strong></p>
<ul class="achievements">
@foreach((is_array($graduate->projects) ? $graduate->projects : array_filter(explode("\n", $graduate->projects))) as $item)
<li>{{ trim($item) }}</li>
@endforeach
</ul>
@endif
</div>
@endif

@if($graduate->activities)
<div class="section">
<h2 class="section-heading">University Engagement</h2>
<ul class="achievements">
@foreach((is_array($graduate->activities) ? $graduate->activities : array_filter(explode("\n", $graduate->activities))) as $item)
<li>{{ trim($item) }}</li>
@endforeach
</ul>
</div>
@endif

@if($graduate->internships || $graduate->certifications_training)
<div class="section">
<h2 class="section-heading">Professional Exposure</h2>
@if($graduate->internships)
<p><strong>Internships / Work Experience</strong></p>
<ul class="achievements">
@foreach((is_array($graduate->internships) ? $graduate->internships : array_filter(explode("\n", $graduate->internships))) as $item)
<li>{{ trim($item) }}</li>
@endforeach
</ul>
@endif
@if($graduate->certifications_training)
<p><strong>Certifications &amp; Training</strong></p>
<p>{{ $graduate->certifications_training }}</p>
@endif
</div>
@endif

@if($graduate->professional_interests || $graduate->future_plans)
<div class="section">
<h2 class="section-heading">Personal &amp; Professional Elements</h2>
@if($graduate->professional_interests)<p><strong>Professional Interests:</strong> {{ $graduate->professional_interests }}</p>@endif
@if($graduate->future_plans)<p><strong>Future Plans:</strong> {{ $graduate->future_plans }}</p>@endif
</div>
@endif

@if($graduate->approved_links && is_array($graduate->approved_links) && count($graduate->approved_links) > 0)
<div class="section">
<h2 class="section-heading">Approved Links</h2>
<table class="links-table">
@foreach($graduate->approved_links as $link)
<tr><td><a href="{{ $link }}">{{ $link }}</a></td></tr>
@endforeach
</table>
</div>
@endif

@if($graduate->resumeMedia)
<div class="section">
<h2 class="section-heading">Resume / CV</h2>
<p>The graduate has an approved resume/CV available from the digital profile.</p>
</div>
@endif

<div class="footer-note">Generated from the University Annual Yearbook on {{ now()->format('F j, Y') }}</div>
</body>
</html>
