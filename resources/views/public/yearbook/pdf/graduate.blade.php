<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
    /* dompdf-safe CSS only: no grid, no flexbox layout, no transitions/
       animations, no backdrop-filter. Floats + tables + inline-block. */

    @page {
        margin: 60px 50px;
    }

    body {
        font-family: "DejaVu Serif", Georgia, serif;
        color: #1b2a3d;
        font-size: 12px;
        line-height: 1.6;
    }

    .header-band {
        background: #002a5c;
        color: #ffffff;
        padding: 24px 28px;
        margin-bottom: 24px;
    }

    .header-table {
        width: 100%;
        border-collapse: collapse;
    }

    .header-table td {
        vertical-align: middle;
    }

    .portrait-cell {
        width: 130px;
    }

    .portrait {
        width: 120px;
        height: 120px;
        border-radius: 8px;
        object-fit: cover;
    }

    .portrait-fallback {
        width: 120px;
        height: 120px;
        background: #1a3f7f;
        color: #fff;
        text-align: center;
        line-height: 120px;
        font-size: 40px;
        font-weight: bold;
        border-radius: 8px;
    }

    .name {
        font-size: 26px;
        font-weight: bold;
        margin: 0 0 6px;
        color: #ffffff;
    }

    .badge {
        display: inline-block;
        background: rgba(255, 255, 255, .15);
        border: 1px solid rgba(255, 255, 255, .4);
        color: #ffffff;
        padding: 4px 10px;
        margin: 0 6px 6px 0;
        font-size: 10px;
        border-radius: 3px;
    }

    h2.section-heading {
        font-size: 15px;
        color: #002a5c;
        border-bottom: 2px solid #ffb034;
        padding-bottom: 6px;
        margin: 22px 0 10px;
    }

    p {
        margin: 0 0 10px;
        color: #333333;
    }

    .quote {
        font-style: italic;
        border-left: 3px solid #ffb034;
        padding: 10px 16px;
        background: #f7fbff;
        margin: 14px 0;
    }

    ul.achievements {
        margin: 0;
        padding-left: 18px;
    }

    ul.achievements li {
        margin-bottom: 6px;
        color: #333333;
    }

    .facts-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    .facts-table td {
        padding: 6px 0;
        border-bottom: 1px solid #e0e6ef;
        font-size: 11px;
    }

    .facts-label {
        color: #64748b;
        width: 40%;
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

    <div class="header-band">
        <table class="header-table">
            <tr>
                <td class="portrait-cell">
                    @if($graduate->media->first())
                        <img class="portrait" src="{{ public_path('storage/' . $graduate->media->first()->path) }}" alt="{{ $graduate->name }}">
                    @else
                        <div class="portrait-fallback">{{ strtoupper(substr($graduate->name, 0, 1)) }}</div>
                    @endif
                </td>
                <td>
                    <div class="name">{{ $graduate->name }}</div>
                    <span class="badge">{{ $graduate->major->name ?? 'Major' }}</span>
                    <span class="badge">{{ $graduate->school->name ?? 'School' }}</span>
                    <span class="badge">{{ $graduate->campus->name ?? 'Campus' }}</span>
                    @if($graduate->graduation)
                        <span class="badge">Class of {{ \Carbon\Carbon::parse($graduate->graduation->ceremony_date)->format('Y') }}</span>
                    @endif
                </td>
            </tr>
        </table>
    </div>

    @if($graduate->profile_text)
        <h2 class="section-heading">Biography</h2>
        <p>{{ $graduate->profile_text }}</p>
    @endif

    @if($graduate->quote)
        <div class="quote">&ldquo;{{ $graduate->quote }}&rdquo;</div>
    @endif

    @if($graduate->achievements)
        <h2 class="section-heading">Academic Achievements</h2>
        <ul class="achievements">
            @foreach((is_array($graduate->achievements) ? $graduate->achievements : array_filter(explode("\n", $graduate->achievements))) as $item)
                <li>{{ trim($item) }}</li>
            @endforeach
        </ul>
    @endif

    @if($graduate->activities)
        <h2 class="section-heading">University Activities</h2>
        <ul class="achievements">
            @foreach((is_array($graduate->activities) ? $graduate->activities : array_filter(explode("\n", $graduate->activities))) as $item)
                <li>{{ trim($item) }}</li>
            @endforeach
        </ul>
    @endif

    @if($graduate->future_plans)
        <h2 class="section-heading">Future Plans</h2>
        <p>{{ $graduate->future_plans }}</p>
    @endif

    <h2 class="section-heading">Quick Facts</h2>
    <table class="facts-table">
        @if($graduate->graduation)
        <tr><td class="facts-label">Graduation Year</td><td>{{ \Carbon\Carbon::parse($graduate->graduation->ceremony_date)->format('Y') }}</td></tr>
        @endif
        <tr><td class="facts-label">School</td><td>{{ $graduate->school->name ?? 'N/A' }}</td></tr>
        <tr><td class="facts-label">Major</td><td>{{ $graduate->major->name ?? 'N/A' }}</td></tr>
        <tr><td class="facts-label">Campus</td><td>{{ $graduate->campus->name ?? 'N/A' }}</td></tr>
    </table>

    <div class="footer-note">Generated from the University Annual Yearbook on {{ now()->format('F j, Y') }}</div>

</body>
</html>
