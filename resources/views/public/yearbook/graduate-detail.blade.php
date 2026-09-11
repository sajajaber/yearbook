@extends('public.layout')

@section('title', $graduate->name)

@section('extra-css')

<style>
    /* =========================================================
       GRADUATE PROFILE — EDITORIAL YEARBOOK DESIGN
       ========================================================= */

    :root {
        --profile-ink: var(--ink, #002a5c);
        --profile-deep: #001b3d;
        --profile-blue: #073972;
        --profile-gold: var(--red, #ffb034);
        --profile-paper: var(--paper, #f7fbff);
        --profile-white: var(--white, #ffffff);
        --profile-muted: var(--ink-soft, #64748b);
        --profile-line: var(--line, #d8e3ef);

        --profile-radius: 20px;

        --profile-shadow:
            0 12px 40px rgba(0, 42, 92, 0.07);

        --profile-shadow-hover:
            0 22px 55px rgba(0, 42, 92, 0.13);

        --profile-transition:
            300ms cubic-bezier(.2, .8, .2, 1);
    }


    /* =========================================================
       PAGE
       ========================================================= */

    .graduate-page {
        background: var(--profile-paper);
        color: var(--profile-ink);
        overflow: hidden;
    }


    /* =========================================================
       HERO
       ========================================================= */

    .profile-hero {
        position: relative;
        min-height: 510px;

        display: flex;
        align-items: flex-end;

        overflow: hidden;
        isolation: isolate;

        background:
            radial-gradient(circle at 88% 18%,
                rgba(255, 176, 52, .16),
                transparent 25%),
            linear-gradient(135deg,
                var(--profile-deep) 0%,
                var(--profile-blue) 100%);
    }

    .profile-hero::before {
        content: "";

        position: absolute;

        width: 520px;
        height: 520px;

        right: -250px;
        top: -280px;

        border: 1px solid rgba(255, 176, 52, .18);
        border-radius: 50%;

        box-shadow:
            0 0 0 80px rgba(255, 176, 52, .025),
            0 0 0 160px rgba(255, 176, 52, .018);

        pointer-events: none;
    }

    .profile-hero::after {
        content: "";

        position: absolute;
        inset: 0;

        z-index: -1;

        opacity: .13;

        pointer-events: none;

        background-image:
            linear-gradient(rgba(255, 255, 255, .08) 1px,
                transparent 1px),
            linear-gradient(90deg,
                rgba(255, 255, 255, .08) 1px,
                transparent 1px);

        background-size: 50px 50px;

        mask-image:
            linear-gradient(to bottom,
                black,
                transparent 85%);
    }

    .profile-hero .container {
        width: min(1180px, calc(100% - 48px));
        margin: 0 auto;
    }

    .profile-header {
        position: relative;

        display: grid;

        grid-template-columns:
            285px minmax(0, 1fr);

        gap: 48px;

        align-items: end;

        padding: 90px 0 65px;
    }


    /* =========================================================
       PORTRAIT
       ========================================================= */

    .profile-photo {
        position: relative;

        height: 355px;

        overflow: hidden;

        border: 5px solid rgba(255, 255, 255, .12);
        border-radius: 22px;

        background:
            linear-gradient(135deg,
                #24406f,
                #163261);

        display: flex;
        align-items: center;
        justify-content: center;

        box-shadow:
            0 30px 65px rgba(0, 0, 0, .35),
            0 0 0 1px rgba(255, 255, 255, .05);

        animation:
            profilePhotoReveal 800ms cubic-bezier(.2, .8, .2, 1) both;

        transition:
            transform 500ms cubic-bezier(.2, .8, .2, 1),
            box-shadow 500ms ease;
    }

    .profile-photo::after {
        content: "";

        position: absolute;
        inset: 0;

        pointer-events: none;

        background:
            linear-gradient(145deg,
                rgba(255, 255, 255, .15),
                transparent 35%,
                rgba(0, 0, 0, .2));
    }

    .profile-photo:hover {
        transform:
            translateY(-8px) rotate(-1deg) scale(1.015);

        box-shadow:
            0 40px 75px rgba(0, 0, 0, .4),
            0 0 0 1px rgba(255, 176, 52, .2);
    }

    .profile-photo img {
        width: 100%;
        height: 100%;

        object-fit: cover;

        transition:
            transform 700ms cubic-bezier(.2, .8, .2, 1),
            filter 500ms ease;
    }

    .profile-photo:hover img {
        transform: scale(1.045);
        filter: saturate(1.05);
    }

    .profile-photo-initial {
        position: relative;
        z-index: 1;

        display: flex;
        align-items: center;
        justify-content: center;

        width: 100%;
        height: 100%;

        font-family:
            "Merriweather",
            Georgia,
            serif;

        font-size: 6rem;
        font-weight: 800;

        color: #fff;
    }


    /* =========================================================
       PROFILE INFO
       ========================================================= */

    .profile-info {
        padding-bottom: 8px;

        animation:
            profileInfoReveal 800ms 120ms cubic-bezier(.2, .8, .2, 1) both;
    }

    .profile-info h1 {
        max-width: 850px;

        margin: 0 0 22px;

        color: #fff;

        font-family:
            "Merriweather",
            Georgia,
            serif;

        font-size:
            clamp(2.5rem, 5vw, 4.7rem);

        font-weight: 900;

        line-height: 1.04;

        letter-spacing: -.045em;

        text-wrap: balance;
    }

    .profile-badges {
        display: flex;
        flex-wrap: wrap;

        gap: 9px;

        margin-bottom: 27px;
    }

    .badge {
        display: inline-flex;
        align-items: center;

        min-height: 36px;

        padding: 7px 13px;

        border:
            1px solid rgba(255, 255, 255, .17);

        border-radius: 999px;

        background:
            rgba(255, 255, 255, .08);

        backdrop-filter: blur(12px);

        color:
            rgba(255, 255, 255, .92);

        font-size: .77rem;
        font-weight: 700;

        transition:
            transform var(--profile-transition),
            background var(--profile-transition),
            border-color var(--profile-transition),
            color var(--profile-transition);
    }

    .badge:hover {
        transform: translateY(-3px);

        background:
            rgba(255, 176, 52, .14);

        border-color:
            rgba(255, 176, 52, .4);

        color: #fff;
    }

    .profile-actions {
        display: flex;
        flex-wrap: wrap;

        gap: 10px;
    }

    .profile-actions .btn {
        transition:
            transform var(--profile-transition),
            box-shadow var(--profile-transition),
            background var(--profile-transition);
    }

    .profile-actions .btn:hover {
        transform: translateY(-3px);
    }


    /* =========================================================
       MAIN CONTENT CONTAINER
       ========================================================= */

    .profile-body {
        position: relative;

        width: min(1120px, calc(100% - 48px));

        display: grid;

        grid-template-columns:
            minmax(0, 1fr) 320px;

        gap: 36px;

        margin: -45px auto 0;

        padding-bottom: 90px;

        align-items: start;
    }

    .profile-body>div {
        min-width: 0;
    }


    /* =========================================================
       SCROLL REVEAL
       ========================================================= */

    .scroll-reveal {
        opacity: 0;

        transform:
            translateY(35px) scale(.985);

        transition:
            opacity 700ms cubic-bezier(.2, .8, .2, 1),
            transform 700ms cubic-bezier(.2, .8, .2, 1);
    }

    .scroll-reveal.is-visible {
        opacity: 1;

        transform:
            translateY(0) scale(1);
    }

    .scroll-reveal:nth-child(2) {
        transition-delay: 70ms;
    }

    .scroll-reveal:nth-child(3) {
        transition-delay: 120ms;
    }

    .scroll-reveal:nth-child(4) {
        transition-delay: 170ms;
    }

    .scroll-reveal:nth-child(5) {
        transition-delay: 220ms;
    }

    .scroll-reveal:nth-child(6) {
        transition-delay: 270ms;
    }


    /* =========================================================
       CONTENT CARDS
       ========================================================= */

    .section-card {
        position: relative;

        overflow: hidden;

        width: 100%;

        margin-bottom: 22px;

        padding: 31px;

        border:
            1px solid var(--profile-line);

        border-radius:
            var(--profile-radius);

        background:
            var(--profile-white);

        box-shadow:
            var(--profile-shadow);

        transition:
            transform var(--profile-transition),
            box-shadow var(--profile-transition),
            border-color var(--profile-transition);
    }

    .section-card::before {
        content: "";

        position: absolute;

        top: 0;
        left: 0;

        width: 100%;
        height: 3px;

        background:
            linear-gradient(90deg,
                var(--profile-gold),
                transparent);

        opacity: 0;

        transition:
            opacity var(--profile-transition);
    }

    .section-card:hover {
        transform: translateY(-4px);

        box-shadow:
            var(--profile-shadow-hover);

        border-color:
            rgba(0, 42, 92, .13);
    }

    .section-card:hover::before {
        opacity: 1;
    }

    .section-card h2 {
        position: relative;

        display: block;

        margin: 0 0 21px;

        padding: 0 0 16px;

        border-bottom:
            1px solid var(--profile-line);

        color:
            var(--profile-ink);

        font-family:
            "Merriweather",
            Georgia,
            serif;

        font-size: 1.35rem;

        font-weight: 800;

        line-height: 1.3;
    }

    .section-card h2::after {
        content: "";

        position: absolute;

        bottom: -1px;
        left: 0;

        width: 42px;
        height: 3px;

        border-radius: 999px;

        background:
            var(--profile-gold);
    }

    .section-card p {
        margin: 0;

        color: #465970;

        font-size: .98rem;

        line-height: 1.9;
    }


    /* =========================================================
       QUOTE
       ========================================================= */

    .quote-box {
        position: relative;

        margin: 25px 0;

        padding: 30px 34px;

        overflow: hidden;

        border:
            1px solid rgba(255, 176, 52, .28);

        border-left:
            4px solid var(--profile-gold);

        border-radius: 16px;

        background:
            linear-gradient(135deg,
                #fff,
                rgba(255, 176, 52, .08));

        color:
            var(--profile-ink);

        font-family:
            "Merriweather",
            Georgia,
            serif;

        font-size: 1.08rem;

        line-height: 1.8;

        box-shadow:
            0 8px 30px rgba(0, 42, 92, .04);

        transition:
            transform var(--profile-transition),
            box-shadow var(--profile-transition);
    }

    .quote-box::before {
        content: "“";

        position: absolute;

        top: -18px;
        right: 20px;

        color:
            rgba(255, 176, 52, .15);

        font-family:
            Georgia,
            serif;

        font-size: 9rem;

        line-height: 1;
    }

    .quote-box:hover {
        transform: translateY(-4px);

        box-shadow:
            var(--profile-shadow);
    }


    /* =========================================================
       ACHIEVEMENTS / LISTS
       ========================================================= */

    .achievements-list {
        list-style: none;

        padding: 0;
        margin: 0;
    }

    .achievements-list li {
        position: relative;

        display: flex;
        align-items: flex-start;

        gap: 13px;

        padding: 14px 0;

        border-bottom:
            1px solid var(--profile-line);

        color: #465970;

        line-height: 1.65;

        transition:
            padding-left var(--profile-transition),
            color var(--profile-transition);
    }

    .achievements-list li:first-child {
        padding-top: 2px;
    }

    .achievements-list li:last-child {
        padding-bottom: 2px;

        border-bottom: none;
    }

    .achievements-list li::before {
        content: "✓";

        display: flex;

        flex: 0 0 25px;

        width: 25px;
        height: 25px;

        align-items: center;
        justify-content: center;

        margin-top: 1px;

        border-radius: 50%;

        background:
            rgba(255, 176, 52, .13);

        color:
            #b87900;

        font-size: .72rem;

        font-weight: 900;

        transition:
            transform var(--profile-transition),
            background var(--profile-transition);
    }

    .achievements-list li:hover {
        padding-left: 5px;

        color:
            var(--profile-ink);
    }

    .achievements-list li:hover::before {
        transform: scale(1.1);

        background:
            rgba(255, 176, 52, .22);
    }


    /* =========================================================
       SIDEBAR
       ========================================================= */

    .profile-body aside {
        position: sticky;
        top: 30px;
    }

    .sidebar-widget {
        position: relative;

        overflow: hidden;

        margin-bottom: 17px;

        padding: 23px;

        border:
            1px solid var(--profile-line);

        border-radius:
            var(--profile-radius);

        background:
            var(--profile-white);

        box-shadow:
            0 8px 30px rgba(0, 42, 92, .045);

        transition:
            transform var(--profile-transition),
            box-shadow var(--profile-transition),
            border-color var(--profile-transition);
    }

    .sidebar-widget::before {
        content: "";

        position: absolute;

        top: 0;
        left: 0;

        width: 100%;
        height: 3px;

        background:
            linear-gradient(90deg,
                var(--profile-gold),
                transparent);

        opacity: 0;

        transition:
            opacity var(--profile-transition);
    }

    .sidebar-widget:hover {
        transform: translateY(-4px);

        box-shadow:
            var(--profile-shadow);

        border-color:
            rgba(0, 42, 92, .13);
    }

    .sidebar-widget:hover::before {
        opacity: 1;
    }

    .sidebar-widget h3 {
        display: flex;
        align-items: center;

        gap: 9px;

        margin: 0 0 15px;

        color:
            var(--profile-ink);

        font-size: .78rem;

        font-weight: 800;

        letter-spacing: .09em;

        text-transform: uppercase;
    }

    .sidebar-widget h3::before {
        content: "";

        width: 7px;
        height: 7px;

        border-radius: 50%;

        background:
            var(--profile-gold);

        box-shadow:
            0 0 0 4px rgba(255, 176, 52, .12);
    }

    .sidebar-widget p {
        margin: 0 0 10px;

        color:
            var(--profile-muted);

        font-size: .9rem;

        line-height: 1.65;
    }


    /* =========================================================
       QUICK FACTS
       ========================================================= */

    .quick-fact {
        padding: 12px 0;

        border-bottom:
            1px solid var(--profile-line);
    }

    .quick-fact:first-child {
        padding-top: 0;
    }

    .quick-fact:last-child {
        padding-bottom: 0;

        border-bottom: none;
    }

    .quick-fact-label {
        display: block;

        margin-bottom: 4px;

        color:
            var(--profile-muted);

        font-size: .68rem;

        font-weight: 800;

        letter-spacing: .08em;

        text-transform: uppercase;
    }

    .quick-fact-value {
        color:
            var(--profile-ink);

        font-size: .91rem;

        font-weight: 650;

        line-height: 1.5;
    }


    /* =========================================================
       SHARE BUTTONS
       ========================================================= */

    .share-buttons {
        display: flex;
        justify-content: flex-start;
        flex-wrap: wrap;

        gap: 9px;

        margin-top: 15px;
    }

    .share-btn {
        display: inline-flex;

        align-items: center;
        justify-content: center;

        width: 42px;
        height: 42px;

        padding: 0;

        border:
            1px solid var(--profile-line);

        border-radius: 12px;

        background:
            var(--profile-paper);

        color:
            var(--profile-ink);

        text-decoration: none;

        font-size: .95rem;

        font-weight: 800;

        cursor: pointer;

        transition:
            transform var(--profile-transition),
            color var(--profile-transition),
            background var(--profile-transition),
            border-color var(--profile-transition),
            box-shadow var(--profile-transition);
    }

    .share-btn:hover {
        transform: translateY(-4px);

        background:
            var(--profile-ink);

        border-color:
            var(--profile-ink);

        color: #fff;

        box-shadow:
            0 8px 20px rgba(0, 42, 92, .14);
    }

    .share-btn.facebook-btn:hover {
        background: #1877f2;
        border-color: #1877f2;
        color: #fff;
    }

    .share-btn.x-btn:hover {
        background: #000;
        border-color: #000;
        color: #fff;
    }

    .share-btn.linkedin-btn:hover {
        background: #0a66c2;
        border-color: #0a66c2;
        color: #fff;
    }


    /* =========================================================
       QR CODE
       ========================================================= */

    .qr-section {
        text-align: center;
    }

    .qr-section img {
        display: block;

        width: 160px;
        height: 160px;

        margin: 20px auto 15px;

        padding: 10px;

        border:
            1px solid var(--profile-line);

        border-radius: 14px;

        background: #fff;

        transition:
            transform 400ms cubic-bezier(.2, .8, .2, 1),
            box-shadow 400ms ease;
    }

    .qr-section img:hover {
        transform:
            translateY(-5px) scale(1.03);

        box-shadow:
            0 15px 35px rgba(0, 42, 92, .12);
    }


    /* =========================================================
       GALLERY
       ========================================================= */

    .gallery-grid {
        display: grid;

        grid-template-columns:
            repeat(3, minmax(0, 1fr));

        gap: 12px;
    }

    .gallery-item {
        position: relative;

        overflow: hidden;

        height: 175px;

        border-radius: 13px;

        background:
            var(--profile-ink);

        cursor: pointer;
    }

    .gallery-item::after {
        content: "";

        position: absolute;
        inset: 0;

        background:
            linear-gradient(to top,
                rgba(0, 27, 61, .35),
                transparent 55%);

        opacity: 0;

        transition:
            opacity var(--profile-transition);
    }

    .gallery-item img {
        width: 100%;
        height: 100%;

        object-fit: cover;

        transition:
            transform 600ms cubic-bezier(.2, .8, .2, 1),
            filter 400ms ease;
    }

    .gallery-item:hover img {
        transform: scale(1.08);

        filter: saturate(1.08);
    }

    .gallery-item:hover::after {
        opacity: 1;
    }


    /* =========================================================
       BUTTONS
       ========================================================= */

    .profile-actions .btn-primary {
        box-shadow:
            0 8px 20px rgba(0, 0, 0, .15);
    }

    .profile-actions .btn-primary:hover {
        box-shadow:
            0 12px 28px rgba(0, 0, 0, .22);
    }


    /* =========================================================
       RESPONSIVE — TABLET
       ========================================================= */

    @media (max-width: 1050px) {

        .profile-header {
            grid-template-columns:
                245px minmax(0, 1fr);

            gap: 35px;
        }

        .profile-photo {
            height: 315px;
        }

        .profile-body {
            grid-template-columns:
                minmax(0, 1fr) 300px;

            gap: 28px;

            width: min(1050px,
                    calc(100% - 40px));
        }
    }


    /* =========================================================
       RESPONSIVE — TABLET / SMALL LAPTOP
       ========================================================= */

    @media (max-width: 900px) {

        .profile-hero {
            min-height: auto;
        }

        .profile-header {
            grid-template-columns: 1fr;

            align-items: start;

            padding: 75px 0 55px;
        }

        .profile-photo {
            width: 250px;
            height: 310px;
        }

        .profile-body {
            grid-template-columns: 1fr;

            margin-top: 25px;

            width:
                min(760px,
                    calc(100% - 40px));
        }

        .profile-body aside {
            position: static;
        }

        .sidebar-widget {
            margin-bottom: 20px;
        }
    }


    /* =========================================================
       RESPONSIVE — MOBILE
       ========================================================= */

    @media (max-width: 650px) {

        .profile-hero .container,
        .profile-body {
            width:
                calc(100% - 32px);

            margin-left: auto;
            margin-right: auto;
        }

        .profile-header {
            padding-top: 60px;
        }

        .profile-photo {
            width: 210px;
            height: 270px;
        }

        .profile-info h1 {
            font-size:
                clamp(2.2rem,
                    10vw,
                    3.4rem);
        }

        .profile-badges {
            gap: 7px;
        }

        .badge {
            font-size: .72rem;
        }

        .profile-actions {
            flex-direction: column;
            align-items: stretch;
        }

        .profile-actions .btn {
            width: 100%;

            justify-content: center;
        }

        .section-card {
            padding: 23px;
        }

        .quote-box {
            padding: 25px 22px;
        }

        .gallery-grid {
            grid-template-columns:
                repeat(2, minmax(0, 1fr));
        }

        .gallery-item {
            height: 160px;
        }
    }


    /* =========================================================
       RESPONSIVE — SMALL MOBILE
       ========================================================= */

    @media (max-width: 420px) {

        .profile-photo {
            width: 100%;

            max-width: 230px;

            height: 285px;
        }

        .gallery-grid {
            grid-template-columns: 1fr;
        }

        .gallery-item {
            height: 220px;
        }

        .sidebar-widget {
            padding: 20px;
        }

        .section-card {
            padding: 20px;
        }
    }


    /* =========================================================
       PRINT
       ========================================================= */

    @media print {

        .profile-hero {
            min-height: auto;

            padding: 0;

            background:
                #fff !important;
        }

        .profile-hero::before,
        .profile-hero::after {
            display: none;
        }

        .profile-header {
            padding: 20px 0;

            grid-template-columns:
                180px 1fr;
        }

        .profile-photo {
            width: 180px;
            height: 220px;

            box-shadow: none;

            border:
                1px solid #ccc;
        }

        .profile-info h1 {
            color:
                #000 !important;

            font-size: 30px;
        }

        .badge {
            color: #000;

            border-color: #999;

            background: none;
        }

        .profile-actions,
        .qr-section,
        .share-buttons {
            display: none !important;
        }

        .profile-body {
            margin-top: 20px;

            grid-template-columns: 1fr;

            width: 100%;
        }

        .section-card,
        .sidebar-widget {
            box-shadow: none;

            border:
                1px solid #ccc;

            break-inside: avoid;
        }

        .sidebar-widget {
            display: none;
        }

        .scroll-reveal {
            opacity: 1 !important;

            transform: none !important;
        }
    }


    /* =========================================================
       ANIMATIONS
       ========================================================= */

    @keyframes profilePhotoReveal {

        from {
            opacity: 0;

            transform:
                translateY(25px) scale(.96);
        }

        to {
            opacity: 1;

            transform:
                translateY(0) scale(1);
        }
    }

    @keyframes profileInfoReveal {

        from {
            opacity: 0;

            transform:
                translateY(25px);
        }

        to {
            opacity: 1;

            transform:
                translateY(0);
        }
    }


    /* =========================================================
       ACCESSIBILITY
       ========================================================= */

    @media (prefers-reduced-motion: reduce) {

        *,
        *::before,
        *::after {
            animation-duration:
                .01ms !important;

            animation-iteration-count:
                1 !important;

            transition-duration:
                .01ms !important;

            scroll-behavior:
                auto !important;
        }

        .scroll-reveal {
            opacity: 1 !important;
            transform: none !important;
        }
    }
</style>

@endsection

@section('content')

<div class="profile-hero">
    <div class="container">

        <div class="profile-header">

            {{-- Graduate Portrait --}}
            <div class="profile-photo">

                @php
                $portrait = $graduate->media->first();
                @endphp

                @if($portrait)

                <img
                    src="{{ Storage::disk('public')->url($portrait->path) }}"
                    alt="{{ $graduate->name }}">

                @else

                <div class="profile-photo-initial">
                    {{ strtoupper(substr($graduate->name, 0, 1)) }}
                </div>

                @endif

            </div>


            {{-- Graduate Information --}}
            <div class="profile-info">

                <h1>
                    {{ $graduate->name }}
                </h1>


                <div class="profile-badges">

                    <span class="badge">
                        {{ $graduate->major->name ?? 'Major' }}
                    </span>

                    <span class="badge">
                        {{ $graduate->school->name ?? 'School' }}
                    </span>

                    <span class="badge">
                        {{ $graduate->campus->name ?? 'Campus' }}
                    </span>

                    @if($graduate->graduation)

                    <span class="badge">

                        Class of
                        {{ \Carbon\Carbon::parse($graduate->graduation->ceremony_date)->format('Y') }}

                    </span>

                    @endif

                </div>


                {{-- Profile Actions --}}
                <div class="profile-actions print-hide" style="color:white">

                    <a
                        href="{{ route('public.graduate.pdf', $graduate->id) }}"
                        class="btn btn-primary">

                        Download PDF

                        <span aria-hidden="true">
                            &darr;
                        </span>

                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

<div class="container">

    <div class="profile-body">

        {{-- =====================================================
         MAIN CONTENT
         ===================================================== --}}

        <div>

            {{-- Biography --}}
            @if($graduate->profile_text)

            <div class="section-card scroll-reveal">

                <h2>
                    Biography
                </h2>

                <p>
                    {!! nl2br(e($graduate->profile_text)) !!}
                </p>

            </div>

            @endif


            {{-- Quote --}}
            @if($graduate->quote)

            <div class="quote-box scroll-reveal">

                &ldquo;{{ $graduate->quote }}&rdquo;

            </div>

            @endif


            {{-- Academic Achievements --}}
            @if($graduate->achievements)

            <div class="section-card scroll-reveal">

                <h2>
                    Academic Achievements
                </h2>

                <ul class="achievements-list">

                    @foreach(
                    is_array($graduate->achievements)
                    ? $graduate->achievements
                    : array_filter(explode("\n", $graduate->achievements))
                    as $achievement
                    )

                    <li>
                        {{ trim($achievement) }}
                    </li>

                    @endforeach

                </ul>

            </div>

            @endif


            {{-- University Activities --}}
            @if($graduate->activities)

            <div class="section-card scroll-reveal">

                <h2>
                    University Activities
                </h2>

                <ul class="achievements-list">

                    @foreach(
                    is_array($graduate->activities)
                    ? $graduate->activities
                    : array_filter(explode("\n", $graduate->activities))
                    as $activity
                    )

                    <li>
                        {{ trim($activity) }}
                    </li>

                    @endforeach

                </ul>

            </div>

            @endif


            {{-- Projects & Research --}}
            @if($graduate->projects)

            <div class="section-card scroll-reveal">

                <h2>
                    Projects &amp; Research
                </h2>

                <p>

                    {!! nl2br(
                    e(
                    is_array($graduate->projects)
                    ? implode("\n", $graduate->projects)
                    : $graduate->projects
                    )
                    ) !!}

                </p>

            </div>

            @endif


            {{-- Professional Experience --}}
            @if($graduate->internships)

            <div class="section-card scroll-reveal">

                <h2>
                    Professional Experience
                </h2>

                <p>

                    {!! nl2br(
                    e(
                    is_array($graduate->internships)
                    ? implode("\n", $graduate->internships)
                    : $graduate->internships
                    )
                    ) !!}

                </p>

            </div>

            @endif


            {{-- Future Plans --}}
            @if($graduate->future_plans)

            <div class="section-card scroll-reveal">

                <h2>
                    Future Plans
                </h2>

                <p>
                    {!! nl2br(e($graduate->future_plans)) !!}
                </p>

            </div>

            @endif


            {{-- Photo Gallery --}}
            @if($graduate->media->count() > 1)

            <div class="section-card scroll-reveal">

                <h2>
                    Photo Gallery
                </h2>

                <div class="gallery-grid">

                    @foreach($graduate->media as $media)

                    <div class="gallery-item">

                        <img
                            src="{{ Storage::disk('public')->url($media->path) }}"
                            alt="{{ $graduate->name }}"
                            loading="lazy">

                    </div>

                    @endforeach

                </div>

            </div>

            @endif

        </div>


        {{-- =====================================================
         SIDEBAR
         ===================================================== --}}

        <aside class="print-hide">

            {{-- Share Profile --}}
            <div class="sidebar-widget scroll-reveal">

                <h3>
                    Share Profile
                </h3>

                <p>
                    Share this profile with others
                </p>

                <div class="share-buttons">

                    {{-- Facebook --}}
                    <a
                        href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($qrUrl) }}"
                        class="share-btn facebook-btn"
                        title="Share on Facebook"
                        aria-label="Share on Facebook"
                        target="_blank"
                        rel="noopener noreferrer">

                        f

                    </a>


                    {{-- X / Twitter --}}
                    <a
                        href="https://twitter.com/intent/tweet?url={{ urlencode($qrUrl) }}&text={{ urlencode("Check out {$graduate->name}'s graduate profile.") }}"
                        class="share-btn x-btn"
                        title="Share on X"
                        aria-label="Share on X"
                        target="_blank"
                        rel="noopener noreferrer">

                        𝕏

                    </a>


                    {{-- LinkedIn --}}
                    <a
                        href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode($qrUrl) }}"
                        class="share-btn linkedin-btn"
                        title="Share on LinkedIn"
                        aria-label="Share on LinkedIn"
                        target="_blank"
                        rel="noopener noreferrer">

                        in

                    </a>

                </div>

            </div>


            {{-- QR Code --}}
            <div class="sidebar-widget qr-section scroll-reveal">

                <h3>
                    QR Code
                </h3>

                <p>
                    Scan to view this profile
                </p>

                <img
                    src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode($qrUrl) }}"
                    alt="QR Code for {{ $graduate->name }}'s profile"
                    loading="lazy">

            </div>


            {{-- Quick Facts --}}
            <div class="sidebar-widget scroll-reveal">

                <h3>
                    Quick Facts
                </h3>


                @if($graduate->graduation)

                <div class="quick-fact">

                    <span class="quick-fact-label">
                        Graduation Year
                    </span>

                    <span class="quick-fact-value">

                        {{ \Carbon\Carbon::parse($graduate->graduation->ceremony_date)->format('Y') }}

                    </span>

                </div>

                @endif


                <div class="quick-fact">

                    <span class="quick-fact-label">
                        School
                    </span>

                    <span class="quick-fact-value">
                        {{ $graduate->school->name ?? 'N/A' }}
                    </span>

                </div>


                <div class="quick-fact">

                    <span class="quick-fact-label">
                        Major
                    </span>

                    <span class="quick-fact-value">
                        {{ $graduate->major->name ?? 'N/A' }}
                    </span>

                </div>


                <div class="quick-fact">

                    <span class="quick-fact-label">
                        Campus
                    </span>

                    <span class="quick-fact-value">
                        {{ $graduate->campus->name ?? 'N/A' }}
                    </span>

                </div>

            </div>

        </aside>

    </div>

</div>

{{-- =========================================================
SCROLL REVEAL SCRIPT
========================================================= --}}

<script>
    document.addEventListener('DOMContentLoaded', function() {

        const revealElements =
            document.querySelectorAll('.scroll-reveal');

        if (!revealElements.length) {
            return;
        }

        /*
         * Respect users who prefer reduced motion.
         */
        const reducedMotion =
            window.matchMedia(
                '(prefers-reduced-motion: reduce)'
            ).matches;

        if (reducedMotion) {

            revealElements.forEach(function(element) {
                element.classList.add('is-visible');
            });

            return;
        }


        /*
         * IntersectionObserver reveals each card
         * as it enters the viewport.
         */
        const observer =
            new IntersectionObserver(
                function(entries, observer) {

                    entries.forEach(function(entry) {

                        if (!entry.isIntersecting) {
                            return;
                        }

                        entry.target.classList.add(
                            'is-visible'
                        );

                        /*
                         * Stop observing after the
                         * animation has happened.
                         */
                        observer.unobserve(
                            entry.target
                        );

                    });

                }, {
                    threshold: 0.12,

                    rootMargin: '0px 0px -50px 0px'
                }
            );


        revealElements.forEach(function(element) {

            observer.observe(element);

        });

    });
</script>

@endsection