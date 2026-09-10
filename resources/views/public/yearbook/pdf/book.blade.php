<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
    @page { margin: 48px 46px 54px; }
    body { font-family:"DejaVu Serif",Georgia,serif; color:#1b2a3d; font-size:11px; line-height:1.55; }
    .cover { page-break-after:always; text-align:center; padding-top:170px; }
    .cover-top { color:#ffb034; font-family:"DejaVu Sans",Arial,sans-serif; font-size:10px; font-weight:bold; letter-spacing:4px; text-transform:uppercase; }
    .cover-mark { color:#002a5c; font-family:"DejaVu Serif",Georgia,serif; font-size:18px; font-weight:bold; letter-spacing:2px; margin-top:32px; }
    .cover h1 { color:#002a5c; font-size:40px; line-height:1.15; margin:18px 0 12px; }
    .cover-rule { width:70px; border-top:3px solid #ffb034; margin:24px auto; }
    .cover-subtitle { color:#64748b; font-family:"DejaVu Sans",Arial,sans-serif; font-size:11px; letter-spacing:1px; }
    .cover-footer { color:#94a3b8; font-family:"DejaVu Sans",Arial,sans-serif; font-size:8px; margin-top:150px; text-transform:uppercase; letter-spacing:1px; }

    .page-title { color:#002a5c; font-size:24px; margin:0 0 7px; }
    .page-kicker { color:#ffb034; font-family:"DejaVu Sans",Arial,sans-serif; font-size:9px; font-weight:bold; letter-spacing:2px; text-transform:uppercase; margin:0 0 6px; }
    .title-rule { border-top:2px solid #ffb034; margin:0 0 22px; }

    .dedication { page-break-after:always; text-align:center; padding-top:120px; }
    .dedication-box { border:1px solid #d8e3ef; background:#f7fbff; padding:35px 38px; margin:30px 20px; }
    .dedication-mark { color:#ffb034; font-size:30px; line-height:1; }
    .dedication-text { color:#002a5c; font-size:17px; line-height:1.8; font-style:italic; margin:18px 0 0; }

    .toc { page-break-after:always; }
    .toc-section { color:#002a5c; font-family:"DejaVu Sans",Arial,sans-serif; font-size:9px; font-weight:bold; letter-spacing:1.8px; text-transform:uppercase; margin:22px 0 6px; }
    .toc-item { width:100%; padding:7px 0; border-bottom:1px dotted #cbd5e1; font-family:"DejaVu Sans",Arial,sans-serif; font-size:10px; }
    .toc-item .count { float:right; color:#64748b; }

    .chapter { page-break-before:always; }
    .chapter-title-wrap { border-left:5px solid #ffb034; padding-left:14px; margin-bottom:24px; }
    .chapter h2 { color:#002a5c; font-size:25px; margin:0; }
    .chapter-subtitle { color:#64748b; font-family:"DejaVu Sans",Arial,sans-serif; font-size:9px; font-weight:bold; letter-spacing:1.5px; text-transform:uppercase; margin:6px 0 0; }

    .event-row { border-bottom:1px solid #e0e6ef; padding:0 0 14px; margin:0 0 16px; }
    .event-date { color:#c0522a; font-family:"DejaVu Sans",Arial,sans-serif; font-size:8px; font-weight:bold; letter-spacing:1px; text-transform:uppercase; }
    .event-title { color:#002a5c; font-size:14px; font-weight:bold; margin:4px 0; }
    .event-desc { color:#475569; font-family:"DejaVu Sans",Arial,sans-serif; font-size:9px; line-height:1.55; }

    .grad-block { page-break-inside:avoid; border-bottom:1px solid #e0e6ef; padding:0 0 14px; margin:0 0 17px; }
    .grad-table { width:100%; border-collapse:collapse; }
    .grad-table td { vertical-align:top; }
    .grad-portrait-cell { width:76px; }
    .grad-portrait { width:62px; height:62px; object-fit:cover; }
    .grad-portrait-fallback { width:62px; height:62px; background:#002a5c; color:#fff; text-align:center; line-height:62px; font-family:"DejaVu Sans",Arial,sans-serif; font-size:22px; font-weight:bold; }
    .grad-name { color:#002a5c; font-size:13px; font-weight:bold; margin:0 0 3px; }
    .grad-meta { color:#64748b; font-family:"DejaVu Sans",Arial,sans-serif; font-size:8px; margin-bottom:5px; }
    .grad-bio { color:#333; font-family:"DejaVu Sans",Arial,sans-serif; font-size:9px; line-height:1.5; }
    .school-count { color:#64748b; font-family:"DejaVu Sans",Arial,sans-serif; font-size:8px; margin-top:4px; }
    .empty { color:#64748b; font-family:"DejaVu Sans",Arial,sans-serif; font-size:10px; }
    .footer-note { margin-top:28px; color:#94a3b8; font-family:"DejaVu Sans",Arial,sans-serif; font-size:8px; text-align:center; }
</style>
</head>
<body>

<div class="cover">
    <div class="cover-top">University Annual Yearbook</div>
    <div class="cover-mark">LIU</div>
    <h1>{{ $academicYear->title }}</h1>
    <div class="cover-rule"></div>
    <div class="cover-subtitle">Digital Edition</div>
    <div class="cover-footer">Official Yearbook Publication &middot; {{ now()->format('Y') }}</div>
</div>

<div class="dedication">
    <p class="page-kicker">A note to the class</p>
    <h1 class="page-title">Dedication</h1>
    <div class="title-rule"></div>
    <div class="dedication-box">
        <div class="dedication-mark">&ldquo;</div>
        <div class="dedication-text">{{ $academicYear->dedication ?: 'To every student, staff member, and moment that made this year unforgettable — this edition is dedicated to you.' }}</div>
    </div>
</div>

<div class="toc">
    <p class="page-kicker">Inside this edition</p>
    <h1 class="page-title">Contents</h1>
    <div class="title-rule"></div>

    <div class="toc-section">Campus Events &amp; Highlights</div>
    <div class="toc-item">Published events and moments</div>

    <div class="toc-section">Undergraduate Programs</div>
    @forelse ($undergraduates as $schoolName => $group)
        <div class="toc-item">{{ $schoolName }} <span class="count">{{ $group->count() }} {{ \Illuminate\Support\Str::plural('graduate', $group->count()) }}</span></div>
    @empty
        <div class="toc-item">No undergraduate honorees published</div>
    @endforelse

    <div class="toc-section">Graduate Programs</div>
    @forelse ($graduates as $schoolName => $group)
        <div class="toc-item">{{ $schoolName }} <span class="count">{{ $group->count() }} {{ \Illuminate\Support\Str::plural('graduate', $group->count()) }}</span></div>
    @empty
        <div class="toc-item">No graduate honorees published</div>
    @endforelse
</div>

<div class="chapter">
    <div class="chapter-title-wrap">
        <div class="page-kicker">Chapter 01</div>
        <h2>Campus Events &amp; Highlights</h2>
    </div>
    @forelse ($events as $event)
        <div class="event-row">
            <div class="event-date">{{ \Carbon\Carbon::parse($event->event_date)->format('F j, Y') }} @if($event->category)&middot; {{ $event->category->name }}@endif</div>
            <div class="event-title">{{ $event->title }}</div>
            @if($event->description)<div class="event-desc">{{ \Illuminate\Support\Str::limit(strip_tags($event->description), 320) }}</div>@endif
        </div>
    @empty
        <p class="empty">No published events for this academic year.</p>
    @endforelse
</div>

@foreach ($undergraduates as $schoolName => $group)
<div class="chapter">
    <div class="chapter-title-wrap">
        <div class="page-kicker">Undergraduate Class</div>
        <h2>{{ $schoolName }}</h2>
        <div class="school-count">{{ $group->count() }} {{ \Illuminate\Support\Str::plural('graduate', $group->count()) }}</div>
    </div>
    @foreach ($group as $graduate)
        <div class="grad-block">
            <table class="grad-table"><tr>
                <td class="grad-portrait-cell">
                    @php $portrait = $graduate->media->first(); @endphp
                    @if($portrait)
                        <img class="grad-portrait" src="{{ public_path('storage/' . $portrait->path) }}" alt="{{ $graduate->name }}">
                    @else
                        <div class="grad-portrait-fallback">{{ strtoupper(substr($graduate->name,0,1)) }}</div>
                    @endif
                </td>
                <td>
                    <div class="grad-name">{{ $graduate->name }}</div>
                    <div class="grad-meta">{{ $graduate->major->name ?? 'Major' }} &middot; {{ $graduate->campus->name ?? 'Campus' }}</div>
                    @if($graduate->profile_text)<div class="grad-bio">{{ \Illuminate\Support\Str::limit($graduate->profile_text,260) }}</div>@endif
                </td>
            </tr></table>
        </div>
    @endforeach
</div>
@endforeach

@foreach ($graduates as $schoolName => $group)
<div class="chapter">
    <div class="chapter-title-wrap">
        <div class="page-kicker">Graduate Class</div>
        <h2>{{ $schoolName }}</h2>
        <div class="school-count">{{ $group->count() }} {{ \Illuminate\Support\Str::plural('graduate', $group->count()) }}</div>
    </div>
    @foreach ($group as $graduate)
        <div class="grad-block">
            <table class="grad-table"><tr>
                <td class="grad-portrait-cell">
                    @php $portrait = $graduate->media->first(); @endphp
                    @if($portrait)
                        <img class="grad-portrait" src="{{ public_path('storage/' . $portrait->path) }}" alt="{{ $graduate->name }}">
                    @else
                        <div class="grad-portrait-fallback">{{ strtoupper(substr($graduate->name,0,1)) }}</div>
                    @endif
                </td>
                <td>
                    <div class="grad-name">{{ $graduate->name }}</div>
                    <div class="grad-meta">{{ $graduate->major->name ?? 'Major' }} &middot; {{ $graduate->campus->name ?? 'Campus' }}</div>
                    @if($graduate->profile_text)<div class="grad-bio">{{ \Illuminate\Support\Str::limit($graduate->profile_text,260) }}</div>@endif
                </td>
            </tr></table>
        </div>
    @endforeach
</div>
@endforeach

<div class="footer-note">Generated from the University Annual Yearbook platform on {{ now()->format('F j, Y') }}</div>
</body>
</html>
