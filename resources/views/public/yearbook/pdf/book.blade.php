<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
    /* dompdf-safe CSS only: no grid, no flexbox layout, no transitions/
       animations, no backdrop-filter. Floats + tables + page-break rules. */

    @page {
        margin: 60px 50px;
    }

    body {
        font-family: "DejaVu Serif", Georgia, serif;
        color: #1b2a3d;
        font-size: 12px;
        line-height: 1.6;
    }

    /* ---------- Cover ---------- */

    .cover {
        text-align: center;
        padding-top: 220px;
        page-break-after: always;
    }

    .cover .eyebrow {
        color: #ffb034;
        font-size: 12px;
        letter-spacing: 3px;
        text-transform: uppercase;
        margin-bottom: 18px;
    }

    .cover h1 {
        font-size: 42px;
        color: #002a5c;
        margin: 0 0 14px;
    }

    .cover .subtitle {
        font-size: 16px;
        color: #64748b;
    }

    .cover .rule {
        width: 80px;
        border-top: 3px solid #ffb034;
        margin: 26px auto;
    }

    /* ---------- Table of contents ---------- */

    .toc {
        page-break-after: always;
    }

    .toc h2 {
        font-size: 22px;
        color: #002a5c;
        border-bottom: 2px solid #ffb034;
        padding-bottom: 10px;
        margin-bottom: 20px;
    }

    .toc-item {
        display: block;
        padding: 8px 0;
        border-bottom: 1px dotted #cbd5e1;
        font-size: 13px;
        color: #333333;
    }

    /* ---------- Chapter headings ---------- */

    .chapter {
        page-break-before: always;
    }

    .chapter h2.chapter-title {
        font-size: 26px;
        color: #002a5c;
        border-bottom: 3px solid #ffb034;
        padding-bottom: 10px;
        margin-bottom: 20px;
    }

    .chapter h3.chapter-subtitle {
        font-size: 16px;
        color: #002a5c;
        margin: 26px 0 12px;
    }

    /* ---------- Events ---------- */

    .event-row {
        margin-bottom: 16px;
        padding-bottom: 14px;
        border-bottom: 1px solid #e0e6ef;
    }

    .event-row .event-date {
        color: #ffb034;
        font-weight: bold;
        font-size: 11px;
        text-transform: uppercase;
    }

    .event-row .event-title {
        font-size: 15px;
        font-weight: bold;
        color: #002a5c;
        margin: 4px 0 6px;
    }

    .event-row .event-desc {
        font-size: 11px;
        color: #475569;
    }

    /* ---------- Graduate profile blocks ---------- */

    .grad-block {
        page-break-inside: avoid;
        margin-bottom: 22px;
    }

    .grad-table {
        width: 100%;
        border-collapse: collapse;
    }

    .grad-table td {
        vertical-align: top;
    }

    .grad-portrait-cell {
        width: 78px;
    }

    .grad-portrait {
        width: 70px;
        height: 70px;
        border-radius: 6px;
        object-fit: cover;
    }

    .grad-portrait-fallback {
        width: 70px;
        height: 70px;
        background: #1a3f7f;
        color: #fff;
        text-align: center;
        line-height: 70px;
        font-size: 24px;
        font-weight: bold;
        border-radius: 6px;
    }

    .grad-name {
        font-size: 15px;
        font-weight: bold;
        color: #002a5c;
        margin: 0 0 3px;
    }

    .grad-meta {
        font-size: 10px;
        color: #64748b;
        margin: 0 0 5px;
    }

    .grad-bio {
        font-size: 11px;
        color: #333333;
    }

    .footer-note {
        margin-top: 30px;
        font-size: 9px;
        color: #94a3b8;
        text-align: center;
    }
</style>
</head>
<body>

    <div class="cover">
        <div class="eyebrow">University Annual Yearbook</div>
        <h1>{{ $academicYear->title }}</h1>
        <div class="rule"></div>
        <div class="subtitle">Digital Edition &mdash; Printed {{ now()->format('F j, Y') }}</div>
    </div>

    <div class="toc">
        <h2>Table of Contents</h2>
        <span class="toc-item">Campus Events &amp; Highlights</span>
        @foreach($graduates as $schoolName => $group)
            <span class="toc-item">{{ $schoolName }} &mdash; {{ $group->count() }} {{ \Illuminate\Support\Str::plural('graduate', $group->count()) }}</span>
        @endforeach
    </div>

    <div class="chapter">
        <h2 class="chapter-title">Campus Events &amp; Highlights</h2>

        @forelse($events as $event)
            <div class="event-row">
                <div class="event-date">
                    {{ \Carbon\Carbon::parse($event->event_date)->format('F j, Y') }}
                    @if($event->category) &middot; {{ $event->category->name }} @endif
                </div>
                <div class="event-title">{{ $event->title }}</div>
                @if($event->description)
                    <div class="event-desc">{{ \Illuminate\Support\Str::limit(strip_tags($event->description), 320) }}</div>
                @endif
            </div>
        @empty
            <p>No published events for this academic year.</p>
        @endforelse
    </div>

    @foreach($graduates as $schoolName => $group)
        <div class="chapter">
            <h2 class="chapter-title">{{ $schoolName }}</h2>

            @foreach($group as $graduate)
                <div class="grad-block">
                    <table class="grad-table">
                        <tr>
                            <td class="grad-portrait-cell">
                                @php $portrait = $graduate->media->first(); @endphp
                                @if($portrait)
                                    <img class="grad-portrait" src="{{ public_path('storage/' . $portrait->path) }}" alt="{{ $graduate->name }}">
                                @else
                                    <div class="grad-portrait-fallback">{{ strtoupper(substr($graduate->name, 0, 1)) }}</div>
                                @endif
                            </td>
                            <td>
                                <div class="grad-name">{{ $graduate->name }}</div>
                                <div class="grad-meta">{{ $graduate->major->name ?? '' }} &middot; {{ $graduate->campus->name ?? '' }}</div>
                                @if($graduate->profile_text)
                                    <div class="grad-bio">{{ \Illuminate\Support\Str::limit($graduate->profile_text, 260) }}</div>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            @endforeach
        </div>
    @endforeach

    <div class="footer-note">Generated from the University Annual Yearbook platform on {{ now()->format('F j, Y') }}</div>

</body>
</html>
