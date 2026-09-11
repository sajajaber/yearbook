@extends('public.layout')

@section('title', $event->title)

@section('extra-css')
<style>
    /* =========================================================
       EVENT DETAIL — EDITORIAL YEARBOOK DESIGN
       Consistent with LIU Digital Yearbook Design System
       ========================================================= */

    :root {
        --event-ink: var(--ink, #002a5c);
        --event-ink-deep: #001b3d;
        --event-blue: #073972;
        --event-gold: var(--red, #ffb034);
        --event-paper: var(--paper, #f7fbff);
        --event-white: var(--white, #ffffff);
        --event-muted: var(--ink-soft, #64748b);
        --event-line: var(--line, #d8e3ef);
        --event-shadow: 0 18px 50px rgba(0, 42, 92, 0.08);
        --event-shadow-hover: 0 24px 65px rgba(0, 42, 92, 0.15);
        --event-radius: 20px;
        --event-transition: 280ms cubic-bezier(.2, .8, .2, 1);
    }

    /* =========================================================
       HERO
       ========================================================= */

    .event-page {
        background: var(--event-paper);
        color: var(--event-ink);
        overflow: hidden;
    }

    .event-hero {
        position: relative;
        min-height: 540px;
        display: flex;
        align-items: flex-end;
        isolation: isolate;
        overflow: hidden;
        background:
            radial-gradient(circle at 85% 15%,
                rgba(255, 176, 52, 0.18),
                transparent 28%),
            linear-gradient(135deg,
                var(--event-ink-deep),
                var(--event-blue));
    }

    .event-hero-image {
        position: absolute;
        inset: 0;
        z-index: -3;
        overflow: hidden;
    }

    .event-hero-image::after {
        content: "";
        position: absolute;
        inset: 0;
        background:
            linear-gradient(180deg,
                rgba(0, 27, 61, 0.08) 0%,
                rgba(0, 27, 61, 0.20) 35%,
                rgba(0, 27, 61, 0.94) 100%);
    }

    .event-hero-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transform: scale(1.02);
        animation: eventHeroZoom 1.4s ease-out both;
    }

    .event-hero-pattern {
        position: absolute;
        inset: 0;
        z-index: -2;
        opacity: 0.16;
        pointer-events: none;
        background-image:
            linear-gradient(rgba(255, 255, 255, 0.07) 1px,
                transparent 1px),
            linear-gradient(90deg,
                rgba(255, 255, 255, 0.07) 1px,
                transparent 1px);
        background-size: 50px 50px;
        mask-image: linear-gradient(to bottom,
                black,
                transparent 80%);
    }

    .event-hero-inner {
        width: min(1180px, calc(100% - 48px));
        margin: 0 auto;
        padding: 100px 0 58px;
    }

    .event-breadcrumb {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 28px;
        color: rgba(255, 255, 255, 0.72);
        font-size: 0.78rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        animation: eventReveal 700ms ease both;
    }

    .event-breadcrumb a {
        color: rgba(255, 255, 255, 0.72);
        text-decoration: none;
        transition: color var(--event-transition);
    }

    .event-breadcrumb a:hover {
        color: var(--event-gold);
    }

    .event-breadcrumb-separator {
        opacity: 0.45;
    }

    .event-category-label {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 18px;
        padding: 8px 13px;
        border: 1px solid rgba(255, 255, 255, 0.18);
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.09);
        backdrop-filter: blur(12px);
        color: var(--event-gold);
        font-size: 0.76rem;
        font-weight: 800;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        animation: eventReveal 700ms 80ms ease both;
    }

    .event-category-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: var(--event-gold);
        box-shadow: 0 0 0 4px rgba(255, 176, 52, 0.15);
    }

    .event-hero h1 {
        max-width: 900px;
        margin: 0 0 28px;
        color: #fff;
        font-family: "Merriweather", Georgia, serif;
        font-size: clamp(2.4rem, 5vw, 4.8rem);
        font-weight: 900;
        line-height: 1.05;
        letter-spacing: -0.045em;
        text-wrap: balance;
        animation: eventReveal 700ms 140ms ease both;
    }

    .event-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        animation: eventReveal 700ms 220ms ease both;
    }

    .event-meta-item {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        min-height: 42px;
        padding: 9px 14px;
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.09);
        backdrop-filter: blur(14px);
        color: rgba(255, 255, 255, 0.92);
        font-size: 0.86rem;
        font-weight: 600;
        transition:
            transform var(--event-transition),
            background var(--event-transition),
            border-color var(--event-transition);
    }

    .event-meta-item:hover {
        transform: translateY(-3px);
        background: rgba(255, 255, 255, 0.14);
        border-color: rgba(255, 176, 52, 0.4);
    }

    .event-meta-icon {
        display: grid;
        width: 25px;
        height: 25px;
        place-items: center;
        border-radius: 8px;
        background: rgba(255, 176, 52, 0.16);
        color: var(--event-gold);
        font-size: 0.78rem;
    }

    /* =========================================================
       MAIN CONTENT
       ========================================================= */

    .event-main {
        position: relative;
        width: min(1180px, calc(100% - 48px));
        margin: 0 auto;
        padding: 72px 0 90px;
    }

    .event-layout {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 320px;
        gap: 48px;
        align-items: start;
    }

    .event-article {
        min-width: 0;
    }

    .event-section-heading {
        margin: 0 0 24px;
        color: var(--event-ink);
        font-family: "Merriweather", Georgia, serif;
        font-size: clamp(1.55rem, 2.5vw, 2rem);
        font-weight: 800;
        line-height: 1.25;
        letter-spacing: -0.025em;
    }

    .event-description {
        position: relative;
        margin-bottom: 58px;
    }

    .event-description::before {
        content: "";
        display: block;
        width: 48px;
        height: 4px;
        margin-bottom: 24px;
        border-radius: 999px;
        background: var(--event-gold);
    }

    .event-description p {
        max-width: 820px;
        margin: 0;
        color: #42536a;
        font-size: 1.08rem;
        line-height: 1.95;
    }

    /* =========================================================
       SIDEBAR
       ========================================================= */

    .event-sidebar {
        position: sticky;
        top: 30px;
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .event-sidebar-card {
        position: relative;
        overflow: hidden;
        padding: 24px;
        border: 1px solid var(--event-line);
        border-radius: var(--event-radius);
        background: var(--event-white);
        box-shadow: 0 8px 30px rgba(0, 42, 92, 0.045);
        transition:
            transform var(--event-transition),
            box-shadow var(--event-transition),
            border-color var(--event-transition);
    }

    .event-sidebar-card:hover {
        transform: translateY(-4px);
        border-color: rgba(0, 42, 92, 0.14);
        box-shadow: var(--event-shadow);
    }

    .event-sidebar-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 3px;
        background: linear-gradient(90deg,
                var(--event-gold),
                transparent);
        opacity: 0;
        transition: opacity var(--event-transition);
    }

    .event-sidebar-card:hover::before {
        opacity: 1;
    }

    .event-sidebar-title {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 0 0 20px;
        color: var(--event-ink);
        font-size: 0.92rem;
        font-weight: 800;
        letter-spacing: 0.02em;
        text-transform: uppercase;
    }

    .event-sidebar-title-icon {
        display: grid;
        width: 30px;
        height: 30px;
        place-items: center;
        border-radius: 9px;
        background: rgba(255, 176, 52, 0.13);
        color: #c47b00;
        font-size: 0.78rem;
    }

    .event-detail {
        padding: 14px 0;
        border-bottom: 1px solid var(--event-line);
    }

    .event-detail:first-of-type {
        padding-top: 0;
    }

    .event-detail:last-child {
        padding-bottom: 0;
        border-bottom: 0;
    }

    .event-detail-label {
        display: block;
        margin-bottom: 5px;
        color: var(--event-muted);
        font-size: 0.72rem;
        font-weight: 800;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .event-detail-value {
        color: var(--event-ink);
        font-size: 0.93rem;
        font-weight: 650;
        line-height: 1.5;
    }

    .event-detail .event-tags {
        margin-top: 8px;
    }

    .event-detail .event-tag {
        background: #f4f8fc;
    }

    .event-detail .event-tag:hover {
        background: rgba(255, 176, 52, 0.12);
    }

    /* =========================================================
       TAGS
       ========================================================= */

    .event-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .event-tag {
        display: inline-flex;
        align-items: center;
        padding: 7px 11px;
        border: 1px solid var(--event-line);
        border-radius: 999px;
        background: var(--event-paper);
        color: var(--event-ink);
        font-size: 0.78rem;
        font-weight: 700;
        transition:
            transform var(--event-transition),
            background var(--event-transition),
            border-color var(--event-transition);
    }

    .event-tag:hover {
        transform: translateY(-2px);
        border-color: rgba(255, 176, 52, 0.5);
        background: rgba(255, 176, 52, 0.10);
    }

    /* =========================================================
       SHARE
       ========================================================= */

    .event-share-buttons {
        display: flex;
        gap: 9px;
    }

    .event-share-btn {
        position: relative;
        display: grid;
        width: 42px;
        height: 42px;
        place-items: center;
        border: 1px solid var(--event-line);
        border-radius: 12px;
        background: var(--event-white);
        color: var(--event-ink);
        text-decoration: none;
        cursor: pointer;
        font-size: 0.85rem;
        font-weight: 800;
        transition:
            transform var(--event-transition),
            color var(--event-transition),
            background var(--event-transition),
            border-color var(--event-transition);
    }

    .event-share-btn:hover {
        transform: translateY(-4px);
        border-color: var(--event-ink);
        background: var(--event-ink);
        color: #fff;
    }

    .event-share-btn.copy:hover {
        border-color: var(--event-gold);
        background: var(--event-gold);
        color: var(--event-ink);
    }

    /* =========================================================
       GALLERY
       ========================================================= */

    .event-gallery {
        margin-top: 20px;
    }

    .event-gallery-grid {
        display: grid;
        grid-template-columns: repeat(12, 1fr);
        gap: 14px;
    }

    .event-gallery-item {
        position: relative;
        grid-column: span 4;
        height: 245px;
        overflow: hidden;
        border-radius: 16px;
        background: var(--event-ink);
        cursor: pointer;
        isolation: isolate;
    }

    .event-gallery-item:nth-child(1) {
        grid-column: span 8;
        height: 360px;
    }

    .event-gallery-item:nth-child(4n + 2) {
        grid-column: span 4;
    }

    .event-gallery-item::after {
        content: "";
        position: absolute;
        inset: 0;
        z-index: 1;
        background: linear-gradient(to top,
                rgba(0, 27, 61, 0.6),
                transparent 45%);
        opacity: 0.65;
        transition: opacity var(--event-transition);
    }

    .event-gallery-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition:
            transform 600ms cubic-bezier(.2, .8, .2, 1),
            filter 400ms ease;
    }

    .event-gallery-item:hover img {
        transform: scale(1.07);
        filter: saturate(1.08);
    }

    .event-gallery-item:hover::after {
        opacity: 0.9;
    }

    .event-gallery-caption {
        position: absolute;
        right: 14px;
        bottom: 13px;
        left: 14px;
        z-index: 2;
        color: #fff;
        font-size: 0.78rem;
        font-weight: 600;
        opacity: 0;
        transform: translateY(8px);
        transition:
            opacity var(--event-transition),
            transform var(--event-transition);
    }

    .event-gallery-item:hover .event-gallery-caption {
        opacity: 1;
        transform: translateY(0);
    }

    /* =========================================================
       RELATED EVENTS
       ========================================================= */

    .related-section {
        position: relative;
        padding: 82px 0 100px;
        background: #eef5fb;
        border-top: 1px solid var(--event-line);
    }

    .related-section::before {
        content: "";
        position: absolute;
        inset: 0;
        pointer-events: none;
        opacity: 0.45;
        background-image:
            radial-gradient(circle at 10% 10%,
                rgba(0, 42, 92, 0.08) 1px,
                transparent 1px);
        background-size: 26px 26px;
        mask-image: linear-gradient(135deg,
                black,
                transparent 65%);
    }

    .related-inner {
        position: relative;
        width: min(1180px, calc(100% - 48px));
        margin: 0 auto;
    }

    .related-heading {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 28px;
    }

    .related-heading h2 {
        margin: 0;
        color: var(--event-ink);
        font-family: "Merriweather", Georgia, serif;
        font-size: clamp(1.7rem, 3vw, 2.4rem);
        letter-spacing: -0.035em;
    }

    .related-heading p {
        margin: 6px 0 0;
        color: var(--event-muted);
        font-size: 0.9rem;
    }

    .related-events-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
    }

    .related-event-card {
        display: block;
        overflow: hidden;
        border: 1px solid rgba(0, 42, 92, 0.08);
        border-radius: var(--event-radius);
        background: var(--event-white);
        color: inherit;
        text-decoration: none;
        box-shadow: 0 8px 25px rgba(0, 42, 92, 0.05);
        transition:
            transform 350ms cubic-bezier(.2, .8, .2, 1),
            box-shadow 350ms ease,
            border-color 350ms ease;
    }

    .related-event-card:hover {
        transform: translateY(-8px);
        border-color: rgba(0, 42, 92, 0.14);
        box-shadow: var(--event-shadow-hover);
    }

    .related-event-image {
        position: relative;
        height: 215px;
        overflow: hidden;
        background:
            linear-gradient(135deg,
                var(--event-ink),
                var(--event-blue));
    }

    .related-event-image::after {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(to top,
                rgba(0, 27, 61, 0.35),
                transparent 50%);
    }

    .related-event-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 500ms ease;
    }

    .related-event-card:hover .related-event-image img {
        transform: scale(1.06);
    }

    .related-event-content {
        padding: 21px;
    }

    .related-event-date {
        display: block;
        margin-bottom: 8px;
        color: #9a6500;
        font-size: 0.72rem;
        font-weight: 800;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .related-event-title {
        display: -webkit-box;
        overflow: hidden;
        margin: 0;
        color: var(--event-ink);
        font-family: "Merriweather", Georgia, serif;
        font-size: 1rem;
        font-weight: 800;
        line-height: 1.45;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    .related-event-arrow {
        display: inline-flex;
        margin-top: 16px;
        color: var(--event-ink);
        font-size: 0.78rem;
        font-weight: 800;
        transition:
            color var(--event-transition),
            transform var(--event-transition);
    }

    .related-event-card:hover .related-event-arrow {
        color: #a66d00;
        transform: translateX(4px);
    }

    /* =========================================================
       EMPTY MEDIA STATE
       ========================================================= */

    .event-hero-placeholder {
        position: absolute;
        inset: 0;
        z-index: -3;
        background:
            radial-gradient(circle at 80% 20%,
                rgba(255, 176, 52, 0.18),
                transparent 25%),
            linear-gradient(135deg,
                #001b3d,
                #073972);
    }

    /* =========================================================
       TOAST
       ========================================================= */

    .event-toast {
        position: fixed;
        right: 24px;
        bottom: 24px;
        z-index: 100;
        padding: 12px 17px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        background: var(--event-ink);
        color: #fff;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.18);
        font-size: 0.82rem;
        font-weight: 700;
        opacity: 0;
        pointer-events: none;
        transform: translateY(15px);
        transition:
            opacity 250ms ease,
            transform 250ms ease;
    }

    .event-toast.show {
        opacity: 1;
        transform: translateY(0);
    }

    /* =========================================================
       ANIMATIONS
       ========================================================= */

    @keyframes eventHeroZoom {
        from {
            transform: scale(1.08);
        }

        to {
            transform: scale(1.02);
        }
    }

    @keyframes eventReveal {
        from {
            opacity: 0;
            transform: translateY(18px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* =========================================================
       RESPONSIVE
       ========================================================= */

    @media (max-width: 1000px) {
        .event-layout {
            grid-template-columns: minmax(0, 1fr) 330px;
            gap: 30px;
        }

        .event-gallery-item {
            grid-column: span 6;
        }

        .event-gallery-item:nth-child(1) {
            grid-column: span 12;
            height: 320px;
        }

        .related-events-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 760px) {
        .event-layout {
            grid-template-columns: 1fr;
        }

    .event-hero-inner,
    .event-main,
    .related-inner {
        width: min(100% - 32px, 1180px);
    }

    .event-hero-inner {
        padding: 90px 0 38px;
    }

    .event-breadcrumb {
        margin-bottom: 22px;
    }

    .event-hero h1 {
        margin-bottom: 22px;
        font-size: clamp(2.1rem, 10vw, 3.2rem);
    }

    .event-meta {
        display: grid;
        grid-template-columns: 1fr;
    }

    .event-meta-item {
        width: fit-content;
        max-width: 100%;
    }

    .event-main {
        padding: 52px 0 70px;
    }

    .event-layout {
        grid-template-columns: 1fr;
    }

    .event-sidebar {
        position: static;
    }

    .event-description {
        margin-bottom: 45px;
    }

    .event-description p {
        font-size: 1rem;
        line-height: 1.85;
    }

    .event-gallery-grid {
        grid-template-columns: 1fr 1fr;
    }

    .event-gallery-item,
    .event-gallery-item:nth-child(1),
    .event-gallery-item:nth-child(4n + 2) {
        grid-column: span 1;
        height: 190px;
    }

    .event-gallery-item:nth-child(1) {
        grid-column: span 2;
        height: 250px;
    }

    .event-gallery-caption {
        opacity: 1;
        transform: none;
    }

    .related-section {
        padding: 60px 0 75px;
    }

    .related-heading {
        display: block;
    }

    .related-events-grid {
        grid-template-columns: 1fr;
    }

    .related-event-image {
        height: 230px;
    }
    }

    @media (max-width: 480px) {
        .event-gallery-grid {
            display: grid;
            grid-template-columns: 1fr;
        }

        .event-gallery-item,
        .event-gallery-item:nth-child(1),
        .event-gallery-item:nth-child(4n + 2) {
            grid-column: span 1;
            height: 220px;
        }

        .event-gallery-item:nth-child(1) {
            height: 260px;
        }

        .event-sidebar-card {
            padding: 20px;
        }

        .event-toast {
            right: 16px;
            bottom: 16px;
            left: 16px;
            text-align: center;
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
$heroImage = $event->media->first();
@endphp

<div class="event-page">

    {{-- =====================================================
         HERO
         ===================================================== --}}
    <header class="event-hero">

        @if($heroImage)
        <div class="event-hero-image">
            <img
                src="{{ Storage::disk('public')->url($heroImage->path) }}"
                alt="{{ $event->title }}">
        </div>
        @else
        <div class="event-hero-placeholder"></div>
        @endif

        <div class="event-hero-pattern"></div>

        <div class="event-hero-inner">

            <div class="event-breadcrumb">
                <a href="{{ route('public.events') }}">
                    Events
                </a>

                <span class="event-breadcrumb-separator">/</span>

                <span>{{ $event->category->name }}</span>
            </div>

            <div class="event-category-label">
                <span class="event-category-dot"></span>
                {{ $event->category->name }}
            </div>

            <h1>{{ $event->title }}</h1>

            <div class="event-meta">

                <div class="event-meta-item">
                    <span class="event-meta-icon">◷</span>
                    <span>
                        {{ \Carbon\Carbon::parse($event->event_date)->format('F d, Y') }}
                    </span>
                </div>

                @if($event->location)
                <div class="event-meta-item">
                    <span class="event-meta-icon">⌖</span>
                    <span>{{ $event->location }}</span>
                </div>
                @endif

                <div class="event-meta-item">
                    <span class="event-meta-icon">◆</span>
                    <span>{{ $event->category->name }}</span>
                </div>

            </div>

        </div>
    </header>


    {{-- =====================================================
         MAIN CONTENT
         ===================================================== --}}
    <main class="event-main">

        <div class="event-layout">

            {{-- Article --}}
            <article class="event-article">

                <section class="event-description">

                    <h2 class="event-section-heading">
                        About This Event
                    </h2>

                    <p>
                        {!! nl2br(e($event->description)) !!}
                    </p>

                </section>


                {{-- Gallery --}}
                @if($event->media->count() > 1)

                <section class="event-gallery">

                    <h2 class="event-section-heading">
                        Event Gallery
                    </h2>

                    <div class="event-gallery-grid">

                        @foreach($event->media as $media)

                        <div
                            class="event-gallery-item"
                            onclick="openGalleryImage(this)">

                            <img
                                src="{{ Storage::disk('public')->url($media->path) }}"
                                alt="{{ $media->caption ?: $event->title }}"
                                loading="lazy">

                            @if($media->caption)
                            <div class="event-gallery-caption">
                                {{ $media->caption }}
                            </div>
                            @endif

                        </div>

                        @endforeach

                    </div>

                </section>

                @endif

            </article>


            {{-- Sidebar --}}
            <aside class="event-sidebar">

                {{-- Event Details --}}
                <div class="event-sidebar-card">

                    <h3 class="event-sidebar-title">
                        <span class="event-sidebar-title-icon">i</span>
                        Event Details
                    </h3>

                    {{-- Date --}}
                    <div class="event-detail">
                        <span class="event-detail-label">
                            Date
                        </span>

                        <div class="event-detail-value">
                            {{ \Carbon\Carbon::parse($event->event_date)->format('F d, Y') }}
                        </div>
                    </div>

                    {{-- Location --}}
                    @if($event->location)
                    <div class="event-detail">
                        <span class="event-detail-label">
                            Location
                        </span>

                        <div class="event-detail-value">
                            {{ $event->location }}
                        </div>
                    </div>
                    @endif

                    {{-- Category --}}
                    <div class="event-detail">
                        <span class="event-detail-label">
                            Category
                        </span>

                        <div class="event-detail-value">
                            {{ $event->category->name }}
                        </div>
                    </div>

                    {{-- Campuses --}}
                    @if($event->campuses->count() > 0)
                    <div class="event-detail">

                        <span class="event-detail-label">
                            Campuses
                        </span>

                        <div class="event-tags">
                            @foreach($event->campuses as $campus)
                            <span class="event-tag">
                                {{ $campus->name }}
                            </span>
                            @endforeach
                        </div>

                    </div>
                    @endif

                    {{-- Schools --}}
                    @if($event->schools->count() > 0)
                    <div class="event-detail">

                        <span class="event-detail-label">
                            Schools
                        </span>

                        <div class="event-tags">
                            @foreach($event->schools as $school)
                            <span class="event-tag">
                                {{ $school->name }}
                            </span>
                            @endforeach
                        </div>

                    </div>
                    @endif

                </div>


                {{-- Share --}}
                <div class="event-sidebar-card">

                    <h3 class="event-sidebar-title">
                        <span class="event-sidebar-title-icon">↗</span>
                        Share Event
                    </h3>

                    <div class="event-share-buttons">

                        <button
                            type="button"
                            class="event-share-btn copy"
                            title="Copy link"
                            onclick="copyEventLink()"
                            aria-label="Copy event link">
                            ↗
                        </button>

                        <button
                            type="button"
                            class="event-share-btn"
                            title="Share on Facebook"
                            onclick="shareFacebook()"
                            aria-label="Share on Facebook">
                            f
                        </button>

                        <button
                            type="button"
                            class="event-share-btn"
                            title="Share on X"
                            onclick="shareX()"
                            aria-label="Share on X">
                            𝕏
                        </button>

                    </div>

                </div>

            </aside>

        </div>

    </main>


    {{-- =====================================================
         RELATED EVENTS
         ===================================================== --}}
<!--     @if($relatedEvents->count() > 0)

    <section class="related-section">

        <div class="related-inner">

            <div class="related-heading">

                <div>
                    <h2>More From the Yearbook</h2>

                    <p>
                        Explore other moments and events from the community.
                    </p>
                </div>

            </div>


            <div class="related-events-grid">

                @foreach($relatedEvents as $related)

                @php
                $relatedImage = $related->media->first();
                @endphp

                <a
                    href="{{ route('public.event.detail', $related->id) }}"
                    class="related-event-card">

                    <div class="related-event-image">

                        @if($relatedImage)

                        <img
                            src="{{ Storage::disk('public')->url($relatedImage->path) }}"
                            alt="{{ $related->title }}"
                            loading="lazy">

                        @endif

                    </div>

                    <div class="related-event-content">

                        <span class="related-event-date">
                            {{ \Carbon\Carbon::parse($related->event_date)->format('M d, Y') }}
                        </span>

                        <h3 class="related-event-title">
                            {{ $related->title }}
                        </h3>

                        <span class="related-event-arrow">
                            View event →
                        </span>

                    </div>

                </a>

                @endforeach

            </div>

        </div>

    </section>

    @endif -->

</div>


{{-- Toast --}}
<div
    id="eventToast"
    class="event-toast"
    role="status"
    aria-live="polite"></div>


<script>
    function showEventToast(message) {
        const toast = document.getElementById('eventToast');

        toast.textContent = message;
        toast.classList.add('show');

        clearTimeout(window.eventToastTimer);

        window.eventToastTimer = setTimeout(() => {
            toast.classList.remove('show');
        }, 2200);
    }


    async function copyEventLink() {
        try {
            await navigator.clipboard.writeText(window.location.href);
            showEventToast('Event link copied');
        } catch (error) {
            showEventToast('Unable to copy the link');
        }
    }


    function shareFacebook() {
        const url = encodeURIComponent(window.location.href);

        window.open(
            `https://www.facebook.com/sharer/sharer.php?u=${url}`,
            '_blank',
            'width=700,height=500'
        );
    }


    function shareX() {
        const url = encodeURIComponent(window.location.href);
        const text = encodeURIComponent(
            document.title
        );

        window.open(
            `https://twitter.com/intent/tweet?url=${url}&text=${text}`,
            '_blank',
            'width=700,height=500'
        );
    }


    function openGalleryImage(element) {
        const image = element.querySelector('img');

        if (!image) {
            return;
        }

        /*
         * Keeps the interaction lightweight for now.
         * The gallery can later be upgraded to a proper
         * lightbox without changing the Blade structure.
         */
        window.open(image.src, '_blank');
    }
</script>

@endsection