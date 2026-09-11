@extends('public.layout')

@section('title', 'Graduation Ceremony')

@section('extra-css')
<style>
    /* =========================================================
       GRADUATION CEREMONY — EDITORIAL YEARBOOK DESIGN
       ========================================================= */

    :root {
        --grad-ink: var(--ink, #002a5c);
        --grad-blue: #073972;
        --grad-blue-light: #eaf3fb;
        --grad-gold: #ffb034;
        --grad-gold-light: #fff4df;
        --grad-paper: var(--paper, #f7fbff);
        --grad-white: #ffffff;
        --grad-muted: var(--ink-soft, #64748b);
        --grad-line: #d8e3ef;
        --grad-shadow: 0 18px 50px rgba(0, 42, 92, 0.10);
        --grad-shadow-hover: 0 25px 65px rgba(0, 42, 92, 0.17);
        --grad-radius: 20px;
    }

    /* =========================================================
       PAGE
       ========================================================= */

    .graduation-page {
        background:
            radial-gradient(circle at 10% 15%,
                rgba(255, 176, 52, 0.07),
                transparent 28%),
            radial-gradient(circle at 90% 40%,
                rgba(0, 42, 92, 0.06),
                transparent 30%),
            var(--grad-paper);
        min-height: 100vh;
    }

    /* =========================================================
       HERO
       ========================================================= */

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
        content: "";
        position: absolute;
        inset: 0;
        z-index: 1;
        background:
            linear-gradient(90deg,
                rgba(0, 20, 48, 0.92) 0%,
                rgba(0, 42, 92, 0.72) 45%,
                rgba(0, 42, 92, 0.30) 100%);
        pointer-events: none;
    }

    .graduation-hero::after {
        content: "";
        position: absolute;
        width: 420px;
        height: 420px;
        right: -150px;
        top: -180px;
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 50%;
        box-shadow:
            0 0 0 55px rgba(255, 255, 255, 0.025),
            0 0 0 110px rgba(255, 255, 255, 0.02);
        z-index: 2;
        pointer-events: none;
    }

    .graduation-hero-image {
        position: absolute;
        inset: 0;
        z-index: 0;
        overflow: hidden;
    }

    .graduation-hero-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        opacity: 0.88;
        transform: scale(1.04);
        animation: heroImageReveal 1.2s ease-out forwards;
        transition: transform 1.2s cubic-bezier(0.2, 0.65, 0.25, 1);
    }

    .graduation-hero:hover .graduation-hero-image img {
        transform: scale(1.08);
    }

    .graduation-hero-content {
        position: relative;
        z-index: 3;
        width: min(1180px, calc(100% - 48px));
        margin: 0 auto;
        padding: 80px 0 62px;
        color: var(--grad-white);
    }

    .graduation-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 18px;
        padding: 8px 14px;
        border: 1px solid rgba(255, 255, 255, 0.25);
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.08);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        font-size: 0.72rem;
        font-weight: 800;
        letter-spacing: 0.16em;
        text-transform: uppercase;
        color: #ffffff;
        opacity: 0;
        animation: fadeUp 0.7s 0.15s ease-out forwards;
    }

    .graduation-eyebrow::before {
        content: "";
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: var(--grad-gold);
        box-shadow: 0 0 0 5px rgba(255, 176, 52, 0.15);
    }

    .graduation-hero-content h1 {
        max-width: 850px;
        margin: 0 0 16px;
        font-size: clamp(2.8rem, 6vw, 5.4rem);
        line-height: 0.98;
        font-weight: 900;
        letter-spacing: -0.055em;
        text-wrap: balance;
        opacity: 0;
        animation: fadeUp 0.8s 0.28s ease-out forwards;
    }

    .graduation-date {
        display: flex;
        align-items: center;
        gap: 12px;
        margin: 0;
        color: rgba(255, 255, 255, 0.88);
        font-size: clamp(1rem, 2vw, 1.25rem);
        font-weight: 500;
        opacity: 0;
        animation: fadeUp 0.8s 0.42s ease-out forwards;
    }

    .graduation-date-icon {
        display: grid;
        place-items: center;
        width: 38px;
        height: 38px;
        border-radius: 12px;
        background: var(--grad-gold);
        color: var(--grad-ink);
        font-size: 1rem;
        font-weight: 900;
        box-shadow: 0 8px 25px rgba(255, 176, 52, 0.25);
    }

    .hero-accent-line {
        width: 72px;
        height: 4px;
        margin-top: 28px;
        border-radius: 999px;
        background: var(--grad-gold);
        opacity: 0;
        animation: lineReveal 0.8s 0.55s ease-out forwards;
    }

    /* =========================================================
       MAIN CONTENT
       ========================================================= */

    .graduation-content {
        width: min(1180px, calc(100% - 48px));
        margin: 0 auto;
        padding: 80px 0 100px;
    }

    .graduation-layout {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 320px;
        gap: 34px;
        align-items: start;
    }

    /* =========================================================
       CONTENT CARD
       ========================================================= */

    .ceremony-card {
        position: relative;
        overflow: hidden;
        padding: clamp(28px, 4vw, 48px);
        border: 1px solid var(--grad-line);
        border-radius: var(--grad-radius);
        background: var(--grad-white);
        box-shadow: var(--grad-shadow);
        opacity: 0;
        transform: translateY(30px);
        animation: revealCard 0.8s 0.15s ease-out forwards;
    }

    .ceremony-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg,
                var(--grad-gold),
                var(--grad-ink));
    }

    .section-heading {
        margin-bottom: 28px;
    }

    .section-label {
        display: block;
        margin-bottom: 9px;
        color: var(--grad-gold);
        font-size: 0.7rem;
        font-weight: 900;
        letter-spacing: 0.18em;
        text-transform: uppercase;
    }

    .section-heading h2 {
        margin: 0;
        color: var(--grad-ink);
        font-size: clamp(1.7rem, 3vw, 2.25rem);
        line-height: 1.1;
        font-weight: 850;
        letter-spacing: -0.035em;
    }

    .ceremony-description {
        margin: 0;
        color: var(--grad-muted);
        font-size: 1.03rem;
        line-height: 1.9;
    }

    /* =========================================================
       PHOTO GALLERY
       ========================================================= */

    .gallery-heading {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 20px;
        margin-top: 55px;
        margin-bottom: 22px;
    }

    .gallery-heading h2 {
        margin: 0;
        color: var(--grad-ink);
        font-size: 1.55rem;
        font-weight: 800;
        letter-spacing: -0.025em;
    }

    .gallery-heading span {
        color: var(--grad-muted);
        font-size: 0.85rem;
        font-weight: 600;
    }

    .graduation-gallery {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
    }

    .graduation-gallery-item {
        position: relative;
        height: 245px;
        overflow: hidden;
        border-radius: 16px;
        background: var(--grad-blue-light);
        cursor: pointer;
        box-shadow: 0 8px 25px rgba(0, 42, 92, 0.08);
    }

    .graduation-gallery-item:first-child {
        grid-column: span 2;
        height: 330px;
    }

    .graduation-gallery-item::after {
        content: "";
        position: absolute;
        inset: 0;
        background:
            linear-gradient(180deg,
                transparent 45%,
                rgba(0, 24, 54, 0.48) 100%);
        opacity: 0;
        transition: opacity 0.4s ease;
    }

    .graduation-gallery-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition:
            transform 0.8s cubic-bezier(0.2, 0.65, 0.25, 1),
            filter 0.5s ease;
    }

    .graduation-gallery-item:hover img {
        transform: scale(1.065);
        filter: saturate(1.08);
    }

    .graduation-gallery-item:hover::after {
        opacity: 1;
    }

    .gallery-overlay {
        position: absolute;
        left: 20px;
        bottom: 18px;
        z-index: 2;
        display: flex;
        align-items: center;
        gap: 8px;
        color: white;
        font-size: 0.8rem;
        font-weight: 700;
        letter-spacing: 0.04em;
        opacity: 0;
        transform: translateY(10px);
        transition:
            opacity 0.35s ease,
            transform 0.35s ease;
    }

    .graduation-gallery-item:hover .gallery-overlay {
        opacity: 1;
        transform: translateY(0);
    }

    .gallery-overlay-icon {
        display: grid;
        place-items: center;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.92);
        color: var(--grad-ink);
    }

    /* =========================================================
       SIDEBAR DETAILS
       ========================================================= */

    .graduation-sidebar {
        position: sticky;
        top: 25px;
    }

    .details-card {
        position: relative;
        overflow: hidden;
        padding: 27px;
        border: 1px solid var(--grad-line);
        border-radius: var(--grad-radius);
        background: var(--grad-white);
        box-shadow: var(--grad-shadow);
        opacity: 0;
        transform: translateY(30px);
        animation: revealCard 0.8s 0.3s ease-out forwards;
    }

    .details-card::before {
        content: "";
        position: absolute;
        width: 150px;
        height: 150px;
        right: -85px;
        top: -85px;
        border-radius: 50%;
        background: var(--grad-gold-light);
    }

    .details-header {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 25px;
        padding-bottom: 18px;
        border-bottom: 1px solid var(--grad-line);
    }

    .details-icon {
        display: grid;
        place-items: center;
        width: 42px;
        height: 42px;
        flex: 0 0 42px;
        border-radius: 13px;
        background: var(--grad-gold-light);
        color: var(--grad-ink);
    }

    .details-header h3 {
        margin: 0;
        color: var(--grad-ink);
        font-size: 1.05rem;
        font-weight: 800;
    }

    .detail-item {
        position: relative;
        z-index: 1;
        padding: 17px 0;
        border-bottom: 1px solid #edf2f7;
    }

    .detail-item:last-child {
        border-bottom: 0;
        padding-bottom: 0;
    }

    .detail-label {
        display: block;
        margin-bottom: 7px;
        color: var(--grad-muted);
        font-size: 0.72rem;
        font-weight: 800;
        letter-spacing: 0.12em;
        text-transform: uppercase;
    }

    .detail-value {
        display: block;
        color: var(--grad-ink);
        font-size: 0.98rem;
        font-weight: 700;
        line-height: 1.5;
    }

    /* =========================================================
       GRADUATES HEADER
       ========================================================= */

    .graduates-section {
        margin-top: 80px;
    }

    .graduates-heading {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 25px;
        margin-bottom: 32px;
        opacity: 0;
        transform: translateY(25px);
        animation: revealCard 0.8s 0.4s ease-out forwards;
    }

    .graduates-heading-copy {
        max-width: 700px;
    }

    .graduates-heading .section-label {
        margin-bottom: 10px;
    }

    .graduates-heading h2 {
        margin: 0;
        color: var(--grad-ink);
        font-size: clamp(2rem, 4vw, 3rem);
        line-height: 1;
        font-weight: 900;
        letter-spacing: -0.045em;
    }

    .graduates-count {
        flex-shrink: 0;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 15px;
        border: 1px solid var(--grad-line);
        border-radius: 999px;
        background: var(--grad-white);
        color: var(--grad-ink);
        font-size: 0.85rem;
        font-weight: 800;
        box-shadow: 0 6px 20px rgba(0, 42, 92, 0.06);
    }

    .graduates-count::before {
        content: "";
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: var(--grad-gold);
    }

    /* =========================================================
       GRADUATE CARDS
       ========================================================= */

    .graduates-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 22px;
    }

    .graduate-link {
        display: block;
        color: inherit;
        text-decoration: none;
        opacity: 0;
        transform: translateY(28px);
        animation: graduateReveal 0.7s ease-out forwards;
    }

    .graduate-card {
        position: relative;
        height: 100%;
        overflow: hidden;
        border: 1px solid var(--grad-line);
        border-radius: 18px;
        background: var(--grad-white);
        box-shadow: 0 10px 30px rgba(0, 42, 92, 0.07);
        transition:
            transform 0.45s cubic-bezier(0.2, 0.65, 0.25, 1),
            box-shadow 0.45s ease,
            border-color 0.35s ease;
    }

    .graduate-link:hover .graduate-card {
        transform: translateY(-9px);
        border-color: rgba(255, 176, 52, 0.65);
        box-shadow: var(--grad-shadow-hover);
    }

    .graduate-image {
        position: relative;
        height: 235px;
        overflow: hidden;
        background:
            linear-gradient(135deg,
                var(--grad-ink),
                var(--grad-blue));
    }

    .graduate-image::after {
        content: "";
        position: absolute;
        inset: 0;
        background:
            linear-gradient(180deg,
                transparent 55%,
                rgba(0, 24, 54, 0.25));
        pointer-events: none;
    }

    .graduate-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition:
            transform 0.65s cubic-bezier(0.2, 0.65, 0.25, 1),
            filter 0.45s ease;
    }

    .graduate-link:hover .graduate-image img {
        transform: scale(1.07);
        filter: saturate(1.06);
    }

    .graduate-placeholder {
        width: 100%;
        height: 100%;
        display: grid;
        place-items: center;
        color: white;
        font-size: 3.4rem;
        font-weight: 900;
        background:
            radial-gradient(circle at 25% 20%,
                rgba(255, 176, 52, 0.22),
                transparent 30%),
            linear-gradient(135deg,
                var(--grad-ink),
                var(--grad-blue));
    }

    .graduate-body {
        position: relative;
        padding: 22px 20px 24px;
    }

    .graduate-body::before {
        content: "";
        position: absolute;
        top: 0;
        left: 20px;
        width: 34px;
        height: 3px;
        border-radius: 99px;
        background: var(--grad-gold);
        transform: scaleX(0.55);
        transform-origin: left;
        transition: transform 0.4s ease;
    }

    .graduate-link:hover .graduate-body::before {
        transform: scaleX(1);
    }

    .graduate-name {
        margin: 0 0 9px;
        color: var(--grad-ink);
        font-size: 1.1rem;
        line-height: 1.25;
        font-weight: 800;
        letter-spacing: -0.02em;
        transition: color 0.3s ease;
    }

    .graduate-link:hover .graduate-name {
        color: var(--grad-blue);
    }

    .graduate-info {
        margin: 0;
        color: var(--grad-muted);
        font-size: 0.84rem;
        line-height: 1.65;
    }

    .graduate-school {
        display: inline-block;
        margin-top: 4px;
        font-weight: 600;
    }

    .graduate-arrow {
        position: absolute;
        right: 18px;
        bottom: 18px;
        display: grid;
        place-items: center;
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: var(--grad-blue-light);
        color: var(--grad-ink);
        font-size: 1rem;
        font-weight: 800;
        opacity: 0;
        transform: translateX(-7px);
        transition:
            opacity 0.35s ease,
            transform 0.35s ease,
            background 0.35s ease;
    }

    .graduate-link:hover .graduate-arrow {
        opacity: 1;
        transform: translateX(0);
        background: var(--grad-gold-light);
    }

    /* =========================================================
       EMPTY STATE
       ========================================================= */

    .empty-graduates {
        padding: 65px 30px;
        border: 1px dashed #cbd8e6;
        border-radius: var(--grad-radius);
        background: rgba(255, 255, 255, 0.7);
        text-align: center;
    }

    .empty-icon {
        display: grid;
        place-items: center;
        width: 58px;
        height: 58px;
        margin: 0 auto 17px;
        border-radius: 18px;
        background: var(--grad-blue-light);
        color: var(--grad-ink);
        font-size: 1.5rem;
    }

    .empty-graduates h3 {
        margin: 0 0 8px;
        color: var(--grad-ink);
        font-size: 1.2rem;
        font-weight: 800;
    }

    .empty-graduates p {
        max-width: 420px;
        margin: 0 auto;
        color: var(--grad-muted);
        line-height: 1.7;
    }

    /* =========================================================
       PAGINATION
       ========================================================= */

    .graduation-pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-wrap: wrap;
        gap: 7px;
        margin-top: 45px;
    }

    .graduation-pagination a,
    .graduation-pagination span {
        display: grid;
        place-items: center;
        min-width: 42px;
        height: 42px;
        padding: 0 12px;
        border: 1px solid var(--grad-line);
        border-radius: 11px;
        background: var(--grad-white);
        color: var(--grad-ink);
        text-decoration: none;
        font-size: 0.86rem;
        font-weight: 800;
        transition:
            transform 0.25s ease,
            background 0.25s ease,
            color 0.25s ease,
            border-color 0.25s ease,
            box-shadow 0.25s ease;
    }

    .graduation-pagination a:hover {
        transform: translateY(-3px);
        border-color: var(--grad-gold);
        background: var(--grad-gold-light);
        box-shadow: 0 8px 20px rgba(255, 176, 52, 0.15);
    }

    .graduation-pagination .active {
        border-color: var(--grad-ink);
        background: var(--grad-ink);
        color: white;
        box-shadow: 0 8px 20px rgba(0, 42, 92, 0.18);
    }

    .graduation-pagination .disabled {
        opacity: 0.35;
        cursor: not-allowed;
    }

    /* =========================================================
       ACCESSIBILITY
       ========================================================= */

    .graduate-link:focus-visible,
    .graduation-pagination a:focus-visible {
        outline: 3px solid rgba(255, 176, 52, 0.55);
        outline-offset: 4px;
        border-radius: 18px;
    }

    /* =========================================================
       ANIMATIONS
       ========================================================= */

    @keyframes heroImageReveal {
        from {
            opacity: 0;
            transform: scale(1.12);
        }

        to {
            opacity: 0.88;
            transform: scale(1.04);
        }
    }

    @keyframes fadeUp {
        from {
            opacity: 0;
            transform: translateY(22px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes lineReveal {
        from {
            opacity: 0;
            width: 0;
        }

        to {
            opacity: 1;
            width: 72px;
        }
    }

    @keyframes revealCard {
        from {
            opacity: 0;
            transform: translateY(30px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes graduateReveal {
        from {
            opacity: 0;
            transform: translateY(28px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* =========================================================
       RESPONSIVE
       ========================================================= */

    @media (max-width: 1050px) {
        .graduates-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }

    @media (max-width: 900px) {
        .graduation-hero {
            min-height: 430px;
        }

        .graduation-layout {
            grid-template-columns: 1fr;
        }

        .graduation-sidebar {
            position: static;
        }

        .graduates-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .details-card {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0 28px;
        }

        .details-header {
            grid-column: 1 / -1;
        }

        .detail-item:last-child {
            padding-bottom: 17px;
        }
    }

    @media (max-width: 650px) {
        .graduation-hero {
            min-height: 400px;
        }

        .graduation-hero-content,
        .graduation-content {
            width: min(100% - 30px, 1180px);
        }

        .graduation-hero-content {
            padding: 65px 0 45px;
        }

        .graduation-content {
            padding: 55px 0 70px;
        }

        .graduation-hero-content h1 {
            font-size: clamp(2.5rem, 13vw, 4rem);
        }

        .graduation-layout {
            gap: 22px;
        }

        .ceremony-card {
            padding: 26px 21px;
            border-radius: 17px;
        }

        .details-card {
            display: block;
            padding: 23px;
            border-radius: 17px;
        }

        .graduation-gallery {
            grid-template-columns: 1fr;
        }

        .graduation-gallery-item,
        .graduation-gallery-item:first-child {
            grid-column: span 1;
            height: 240px;
        }

        .graduates-section {
            margin-top: 60px;
        }

        .graduates-heading {
            display: block;
        }

        .graduates-heading h2 {
            font-size: 2.25rem;
        }

        .graduates-count {
            margin-top: 18px;
        }

        .graduates-grid {
            grid-template-columns: 1fr;
            gap: 17px;
        }

        .graduate-image {
            height: 280px;
        }
    }

    @media (max-width: 430px) {
        .graduation-hero::after {
            display: none;
        }

        .graduation-eyebrow {
            font-size: 0.63rem;
            letter-spacing: 0.12em;
        }

        .graduation-date {
            font-size: 0.92rem;
        }

        .gallery-heading {
            display: block;
        }

        .gallery-heading span {
            display: block;
            margin-top: 6px;
        }
    }

    /* =========================================================
       REDUCED MOTION
       ========================================================= */

    @media (prefers-reduced-motion: reduce) {

        *,
        *::before,
        *::after {
            scroll-behavior: auto !important;
            animation-duration: 0.01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: 0.01ms !important;
        }
    }
</style>
@endsection

@section('content')

@php
$image = $graduation->media->first();
$ceremonyDate = \Carbon\Carbon::parse($graduation->ceremony_date);
@endphp

<div class="graduation-page">

    {{-- =====================================================
         HERO
         ===================================================== --}}
    <section class="graduation-hero">

        @if($image)
        <div class="graduation-hero-image">
            <img
                src="{{ Storage::disk('public')->url($image->path) }}"
                alt="Graduation ceremony">
        </div>
        @endif

        <div class="graduation-hero-content">

            <span class="graduation-eyebrow">
                LIU Digital Yearbook
            </span>

            <h1 style="color: white;">
                Graduation<br>
                Ceremony
            </h1>

            <p class="graduation-date">
                <span class="graduation-date-icon">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <rect x="3" y="4" width="18" height="17" rx="3" stroke="currentColor" stroke-width="2" />
                        <path d="M16 2V6M8 2V6M3 10H21" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                    </svg>
                </span>

                {{ $ceremonyDate->format('F d, Y') }}
            </p>

            <div class="hero-accent-line"></div>

        </div>
    </section>


    {{-- =====================================================
         MAIN CONTENT
         ===================================================== --}}
    <main class="graduation-content">

        <div class="graduation-layout">

            {{-- =================================================
                 CEREMONY INFORMATION
                 ================================================= --}}
            <article class="ceremony-card">

                <div class="section-heading">
                    <span class="section-label">
                        The Celebration
                    </span>

                    <h2>
                        Ceremony Details
                    </h2>
                </div>

                @if($graduation->description)
                <p class="ceremony-description">
                    {{ $graduation->description }}
                </p>
                @else
                <p class="ceremony-description">
                    A celebration of achievement, memories, and the next chapter
                    for the graduating class.
                </p>
                @endif


                {{-- =================================================
                     PHOTO GALLERY
                     ================================================= --}}
                @if($graduation->media->count() > 1)

                <div class="gallery-heading">
                    <h2>Ceremony Photos</h2>

                    <span>
                        {{ $graduation->media->count() }} photos
                    </span>
                </div>

                <div class="graduation-gallery">

                    @foreach($graduation->media as $media)

                    <div class="graduation-gallery-item">

                        <img
                            src="{{ Storage::disk('public')->url($media->path) }}"
                            alt="Graduation ceremony photo"
                            loading="lazy">

                        <div class="gallery-overlay">

                            <span class="gallery-overlay-icon">
                                <svg
                                    width="15"
                                    height="15"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    aria-hidden="true">
                                    <circle
                                        cx="11"
                                        cy="11"
                                        r="7"
                                        stroke="currentColor"
                                        stroke-width="2" />
                                    <path
                                        d="M20 20L16 16"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        stroke-linecap="round" />
                                </svg>
                            </span>

                            View photo

                        </div>

                    </div>

                    @endforeach

                </div>

                @endif

            </article>


            {{-- =================================================
                 SIDEBAR
                 ================================================= --}}
            <aside class="graduation-sidebar">

                <div class="details-card">

                    <div class="details-header">

                        <div class="details-icon">
                            <svg
                                width="21"
                                height="21"
                                viewBox="0 0 24 24"
                                fill="none"
                                aria-hidden="true">
                                <path
                                    d="M7 3H17C18.1 3 19 3.9 19 5V19C19 20.1 18.1 21 17 21H7C5.9 21 5 20.1 5 19V5C5 3.9 5.9 3 7 3Z"
                                    stroke="currentColor"
                                    stroke-width="1.8" />
                                <path
                                    d="M8 8H16M8 12H16M8 16H13"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    stroke-linecap="round" />
                            </svg>
                        </div>

                        <h3>At a Glance</h3>

                    </div>


                    <div class="detail-item">

                        <span class="detail-label">
                            Date
                        </span>

                        <span class="detail-value">
                            {{ $ceremonyDate->format('F d, Y') }}
                        </span>

                    </div>


                    @if($graduation->venue)

                    <div class="detail-item">

                        <span class="detail-label">
                            Venue
                        </span>

                        <span class="detail-value">
                            {{ $graduation->venue }}
                        </span>

                    </div>

                    @endif

                </div>

            </aside>

        </div>


        {{-- =====================================================
             GRADUATES
             ===================================================== --}}
        <section class="graduates-section">

            <div class="graduates-heading">

                <div class="graduates-heading-copy">

                    <span class="section-label">
                        The Class
                    </span>

                    <h2>
                        Graduates
                    </h2>

                </div>

                <span class="graduates-count">
                    {{ $graduates->total() }}
                    {{ $graduates->total() === 1 ? 'Graduate' : 'Graduates' }}
                </span>

            </div>


            @if($graduates->count() > 0)

            <div class="graduates-grid">

                @foreach($graduates as $index => $graduate)

                @php
                $portrait = $graduate->media->first();
                $delay = min($index * 80, 560);
                @endphp

                <a
                    href="{{ route('public.graduate.detail', $graduate->id) }}"
                    class="graduate-link"
                    style="animation-delay: {{ $delay }}ms;">

                    <article class="graduate-card">

                        <div class="graduate-image">

                            @if($portrait)

                            <img
                                src="{{ Storage::disk('public')->url($portrait->path) }}"
                                alt="{{ $graduate->name }}"
                                loading="lazy">

                            @else

                            <div class="graduate-placeholder">
                                {{ strtoupper(substr($graduate->name, 0, 1)) }}
                            </div>

                            @endif

                        </div>


                        <div class="graduate-body">

                            <h3 class="graduate-name">
                                {{ $graduate->name }}
                            </h3>

                            <p class="graduate-info">

                                @if($graduate->major)
                                {{ $graduate->major->name }}
                                @else
                                Major
                                @endif

                                <br>

                                <span class="graduate-school">
                                    @if($graduate->school)
                                    {{ $graduate->school->name }}
                                    @else
                                    School
                                    @endif
                                </span>

                            </p>


                            <span class="graduate-arrow" aria-hidden="true">
                                →
                            </span>

                        </div>

                    </article>

                </a>

                @endforeach

            </div>


            {{-- =================================================
                     PAGINATION
                     ================================================= --}}
            @if($graduates->hasPages())

            <nav
                class="graduation-pagination"
                aria-label="Graduate pagination">

                @if($graduates->onFirstPage())

                <span class="disabled" aria-hidden="true">
                    ←
                </span>

                @else

                <a
                    href="{{ $graduates->previousPageUrl() }}"
                    aria-label="Previous page">
                    ←
                </a>

                @endif


                @foreach($graduates->getUrlRange(1, $graduates->lastPage()) as $page => $url)

                @if($page == $graduates->currentPage())

                <span
                    class="active"
                    aria-current="page">
                    {{ $page }}
                </span>

                @else

                <a href="{{ $url }}">
                    {{ $page }}
                </a>

                @endif

                @endforeach


                @if($graduates->hasMorePages())

                <a
                    href="{{ $graduates->nextPageUrl() }}"
                    aria-label="Next page">
                    →
                </a>

                @else

                <span class="disabled" aria-hidden="true">
                    →
                </span>

                @endif

            </nav>

            @endif

            @else

            <div class="empty-graduates">

                <div class="empty-icon">
                    🎓
                </div>

                <h3>
                    No graduate profiles yet
                </h3>

                <p>
                    Graduate profiles for this ceremony will appear here
                    once they are published.
                </p>

            </div>

            @endif

        </section>

    </main>

</div>

@endsection