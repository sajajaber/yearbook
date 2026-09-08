@extends('public.layout')

@section('title', 'Yearbook Home')

@section('extra-css')
<style>
    /* ============ Hero Section ============ */
    .hero-carousel {
        position: relative;
        height: 500px;
        background: linear-gradient(135deg, #002a5c 0%, #073972 100%);
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .hero-carousel-track {
        display: flex;
        height: 100%;
        animation: carousel-scroll 20s linear infinite;
    }

    .hero-carousel-slide {
        min-width: 100%;
        height: 100%;
        position: relative;
    }

    .hero-carousel-slide img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .hero-carousel-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(0, 42, 92, 0.6) 0%, rgba(7, 57, 114, 0.6) 100%);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: var(--white);
        text-align: center;
        padding: 40px 20px;
        z-index: 2;
    }

    .hero-content {
        z-index: 2;
        max-width: 700px;
    }

    .hero-year {
        font-size: 16px;
        letter-spacing: 2px;
        text-transform: uppercase;
        margin-bottom: 20px;
        color: #ffce6b;
    }

    .hero-title {
        font-size: clamp(2.5rem, 5vw, 4rem);
        font-family: "Merriweather", serif;
        font-weight: 700;
        line-height: 1.2;
        margin-bottom: 20px;
    }

    /* Shimmering gradient text applied to each revealed word */
    .hero-title.hero-title-gradient .reveal-word {
        background: linear-gradient(90deg, #fff 0%, #ffce6b 50%, #fff 100%);
        background-size: 220% auto;
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        animation: shine-text 5s linear infinite;
    }

    @keyframes shine-text {
        to {
            background-position: -220% center;
        }
    }

    .hero-subtitle {
        font-size: 1.1rem;
        color: #d8e3ef;
        margin-bottom: 40px;
        max-width: 500px;
        margin-left: auto;
        margin-right: auto;
    }

    .hero-search {
        display: flex;
        gap: 10px;
        max-width: 500px;
        margin: 0 auto;
        background: var(--white);
        padding: 8px;
        border-radius: 8px;
        transition: box-shadow 0.4s ease, transform 0.4s ease;
    }

    .hero-search:focus-within {
        box-shadow: 0 12px 32px rgba(0, 0, 0, 0.25);
        transform: translateY(-2px);
    }

    .hero-search input {
        flex: 1;
        border: none;
        padding: 12px 16px;
        font-size: 1rem;
        outline: none;
    }

    .hero-search button {
        padding: 12px 24px;
        background: var(--red);
        color: var(--white);
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-weight: 600;
        transition: background 0.3s;
    }

    .hero-search button:hover {
        background: #ff9800;
    }

    @keyframes carousel-scroll {
        0% {
            transform: translateX(0);
        }

        25% {
            transform: translateX(-100%);
        }

        50% {
            transform: translateX(-200%);
        }

        75% {
            transform: translateX(-300%);
        }

        100% {
            transform: translateX(-400%);
        }
    }

    /* Floating decorative blobs behind the hero content */
    .hero-float-shape {
        position: absolute;
        border-radius: 50%;
        filter: blur(50px);
        opacity: 0.28;
        pointer-events: none;
        z-index: 1;
        animation: float-shape 14s ease-in-out infinite;
    }

    .hero-float-shape.shape-1 {
        width: 280px;
        height: 280px;
        background: #ffce6b;
        top: -80px;
        left: -80px;
        animation-delay: 0s;
    }

    .hero-float-shape.shape-2 {
        width: 360px;
        height: 360px;
        background: #ff9800;
        bottom: -120px;
        right: -100px;
        animation-delay: -5s;
    }

    .hero-float-shape.shape-3 {
        width: 200px;
        height: 200px;
        background: #4fc3f7;
        top: 35%;
        right: 12%;
        animation-delay: -9s;
    }

    @keyframes float-shape {

        0%,
        100% {
            transform: translate(0, 0) scale(1);
        }

        33% {
            transform: translate(24px, -32px) scale(1.08);
        }

        66% {
            transform: translate(-24px, 22px) scale(0.94);
        }
    }

    /* ============ Scroll progress bar ============ */
    .scroll-progress {
        position: fixed;
        top: 0;
        left: 0;
        height: 3px;
        width: 0%;
        background: linear-gradient(90deg, #ffce6b, var(--red));
        z-index: 9999;
        transition: width 0.1s ease-out;
    }

    /* ============ Generic fade-in-up (headers, blurbs) ============ */
    [data-fade] {
        opacity: 0;
        transform: translateY(24px);
        transition: opacity 0.7s cubic-bezier(0.16, 1, 0.3, 1),
            transform 0.7s cubic-bezier(0.16, 1, 0.3, 1);
    }

    [data-fade].is-visible {
        opacity: 1;
        transform: translateY(0);
    }

    /* ============ Word-by-word scroll reveal ============ */
    .reveal-word {
        display: inline-block;
        opacity: 0;
        transform: translateY(110%);
        transition: opacity 600ms cubic-bezier(0.16, 1, 0.3, 1),
            transform 600ms cubic-bezier(0.16, 1, 0.3, 1);
    }

    [data-reveal].is-revealed .reveal-word {
        opacity: 1;
        transform: translateY(0);
    }

    /* ============ Stats Section ============ */
    .stats-section {
        background: var(--white);
        padding: 60px 20px;
        margin-bottom: 40px;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 30px;
        max-width: 900px;
        margin: 0 auto;
    }

    .stat-card {
        text-align: center;
    }

    .stat-number {
        font-size: clamp(2rem, 5vw, 3.5rem);
        font-family: "Merriweather", serif;
        font-weight: 700;
        color: var(--red);
        margin-bottom: 8px;
        font-variant-numeric: tabular-nums;
    }

    .stat-label {
        font-size: 0.9rem;
        color: var(--ink-soft);
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 600;
    }

    /* ============ Dedication Section ============ */
    .dedication-section {
        background: linear-gradient(135deg, #002a5c 0%, #073972 60%, #0a4a94 100%);
        background-size: 200% 200%;
        animation: gradient-shift 12s ease infinite;
        color: var(--white);
        padding: 60px 40px;
        text-align: center;
        margin-bottom: 60px;
    }

    @keyframes gradient-shift {
        0% {
            background-position: 0% 50%;
        }

        50% {
            background-position: 100% 50%;
        }

        100% {
            background-position: 0% 50%;
        }
    }

    .dedication-content {
        max-width: 800px;
        margin: 0 auto;
    }

    .dedication-label {
        font-size: 12px;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: #ffce6b;
        margin-bottom: 16px;
    }

    .dedication-text {
        font-size: clamp(1.3rem, 3vw, 1.8rem);
        font-family: "Merriweather", serif;
        font-weight: 700;
        line-height: 1.6;
        color: #d8e3ef;
    }

    /* ============ Navigation Boxes ============ */
    .nav-boxes-section {
        padding: 40px 20px;
        margin-bottom: 60px;
    }

    .nav-boxes {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 24px;
        max-width: 1000px;
        margin: 0 auto;
    }

    .nav-box {
        position: relative;
        overflow: hidden;
        background: var(--white);
        border: 2px solid var(--line);
        border-radius: 12px;
        padding: 32px 24px;
        text-align: center;
        transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1),
            border-color 0.3s ease,
            box-shadow 0.3s ease;
        text-decoration: none;
        color: inherit;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        min-height: 200px;
    }

    .nav-box::before {
        content: '';
        position: absolute;
        top: 0;
        left: -75%;
        width: 50%;
        height: 100%;
        background: linear-gradient(120deg, transparent, rgba(255, 255, 255, 0.55), transparent);
        transform: skewX(-20deg);
        transition: left 0.7s ease;
        pointer-events: none;
    }

    .nav-box:hover::before {
        left: 125%;
    }

    .nav-box:hover {
        border-color: var(--red);
        box-shadow: 0 14px 34px rgba(0, 0, 0, 0.12);
        transform: translateY(-6px);
    }

    .nav-box-icon {
        font-size: 3rem;
        margin-bottom: 16px;
        transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    .nav-box:hover .nav-box-icon {
        transform: scale(1.2) rotate(-6deg);
    }

    .nav-box-title {
        font-size: 1.3rem;
        font-family: "Merriweather", serif;
        font-weight: 700;
        color: var(--ink);
    }

    /* ============ Staggered reveal (used on grids) ============ */
    .stagger-item {
        opacity: 0;
        transform: translateY(30px);
        transition: opacity 0.6s cubic-bezier(0.16, 1, 0.3, 1),
            transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .stagger-item.is-visible {
        opacity: 1;
        transform: translateY(0);
    }

    /* ============ Featured Events Section ============ */
    .featured-events-section {
        padding: 60px 20px;
        background: #f7fbff;
        margin-bottom: 60px;
    }

    .section-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .section-header .eyebrow {
        color: var(--red);
        position: relative;
        display: inline-block;
        padding-bottom: 8px;
    }

    .section-header .eyebrow::after {
        content: '';
        position: absolute;
        left: 50%;
        bottom: 0;
        width: 0;
        height: 2px;
        background: var(--red);
        transform: translateX(-50%);
        transition: width 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .section-header.is-visible .eyebrow::after {
        width: 44px;
    }

    .section-header h2 {
        font-size: clamp(2rem, 4vw, 2.8rem);
        font-family: "Merriweather", serif;
        font-weight: 700;
        color: var(--ink);
        margin-top: 8px;
    }

    .featured-events-carousel {
        position: relative;
        max-width: 1200px;
        margin: 0 auto;
        display: flex;
        gap: 20px;
        overflow-x: auto;
        scroll-behavior: smooth;
        padding-bottom: 20px;
    }

    .featured-events-carousel::-webkit-scrollbar {
        height: 6px;
    }

    .featured-events-carousel::-webkit-scrollbar-track {
        background: var(--line);
        border-radius: 3px;
    }

    .featured-events-carousel::-webkit-scrollbar-thumb {
        background: var(--red);
        border-radius: 3px;
    }

    .event-card-featured {
        min-width: 300px;
        background: var(--white);
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1),
            box-shadow 0.4s ease;
        text-decoration: none;
        color: inherit;
    }

    .event-card-featured:hover {
        transform: translateY(-10px);
        box-shadow: 0 16px 32px rgba(0, 0, 0, 0.18);
    }

    .event-card-featured-image {
        width: 100%;
        height: 200px;
        background: linear-gradient(135deg, #002a5c 0%, #073972 100%);
        object-fit: cover;
        transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .event-card-featured:hover .event-card-featured-image {
        transform: scale(1.08);
    }

    .event-card-featured-body {
        padding: 20px;
    }

    .event-card-featured-date {
        font-size: 0.85rem;
        color: var(--red);
        font-weight: 600;
        margin-bottom: 8px;
    }

    .event-card-featured-title {
        position: relative;
        display: inline-block;
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--ink);
        margin-bottom: 8px;
        line-height: 1.4;
    }

    .event-card-featured-title::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: -4px;
        width: 0;
        height: 2px;
        background: var(--red);
        transition: width 0.4s ease;
    }

    .event-card-featured:hover .event-card-featured-title::after {
        width: 36px;
    }

    .event-card-featured-desc {
        font-size: 0.9rem;
        color: var(--ink-soft);
        line-height: 1.5;
    }

    /* ============ Graduations Section ============ */
    .graduations-section {
        padding: 60px 20px;
        margin-bottom: 60px;
    }

    .graduations-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
        max-width: 1200px;
        margin: 0 auto;
    }

    .graduation-card {
        position: relative;
        overflow: hidden;
        background: var(--white);
        border: 1px solid var(--line);
        border-radius: 12px;
        transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1),
            box-shadow 0.4s ease;
        text-decoration: none;
        color: inherit;
    }

    .graduation-card:hover {
        box-shadow: 0 14px 34px rgba(0, 0, 0, 0.12);
        transform: translateY(-6px);
    }

    .graduation-card-image {
        width: 100%;
        height: 200px;
        background: linear-gradient(135deg, #002a5c 0%, #073972 100%);
        object-fit: cover;
        transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .graduation-card:hover .graduation-card-image {
        transform: scale(1.06);
    }

    .graduation-card-body {
        padding: 24px;
    }

    .graduation-card-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--ink);
        margin-bottom: 8px;
    }

    .graduation-card-meta {
        font-size: 0.9rem;
        color: var(--ink-soft);
        margin-bottom: 12px;
    }

    .graduation-card-link {
        color: var(--red);
        text-decoration: none;
        font-weight: 600;
        font-size: 0.95rem;
    }

    .graduation-card-link:hover {
        text-decoration: underline;
    }

    /* ============ Contact Section ============ */
    .contact-section {
        background: linear-gradient(135deg, #002a5c 0%, #073972 60%, #0a4a94 100%);
        background-size: 200% 200%;
        animation: gradient-shift 12s ease infinite;
        color: var(--white);
        padding: 60px 40px;
        text-align: center;
    }

    .contact-content {
        max-width: 600px;
        margin: 0 auto;
    }

    .contact-title {
        font-size: 2rem;
        font-family: "Merriweather", serif;
        font-weight: 700;
        margin-bottom: 16px;
    }

    .contact-text {
        font-size: 1rem;
        color: #d8e3ef;
        margin-bottom: 32px;
        line-height: 1.6;
    }

    .contact-buttons {
        display: flex;
        gap: 16px;
        justify-content: center;
        flex-wrap: wrap;
    }

    /* ============ Rotating-arrow magnetic CTA button ============ */
    .btn-cta {
        display: inline-flex;
        align-items: stretch;
        text-decoration: none;
        gap: 2px;
        transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .btn-cta-label {
        display: inline-flex;
        align-items: center;
        height: 3.5rem;
        padding: 0 1.5rem;
        background: var(--red);
        color: var(--white);
        font-weight: 600;
        white-space: nowrap;
        transition: background 0.3s;
    }

    .btn-cta:hover .btn-cta-label {
        background: #ff9800;
    }

    .btn-cta-icon-wrap {
        position: relative;
        width: 3.5rem;
        height: 3.5rem;
        overflow: hidden;
        flex-shrink: 0;
    }

    .btn-cta-icon {
        position: absolute;
        inset: 0;
        display: grid;
        place-items: center;
        background: var(--red);
        color: var(--white);
        font-size: 1.1rem;
        transition: transform 0.45s cubic-bezier(0.4, 0, 0.2, 1),
            background 0.3s;
    }

    .btn-cta-icon-main {
        transform: rotate(0deg);
        transform-origin: 0% 0%;
    }

    .btn-cta-icon-ghost {
        transform: rotate(-90deg);
        transform-origin: 100% 100%;
    }

    .btn-cta:hover .btn-cta-icon-main {
        transform: rotate(90deg);
        background: #ff9800;
    }

    .btn-cta:hover .btn-cta-icon-ghost {
        transform: rotate(0deg);
        background: #ff9800;
    }

    .btn-contact-secondary {
        display: inline-flex;
        align-items: center;
        padding: 12px 32px;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 600;
        background: transparent;
        color: var(--white);
        border: 2px solid var(--white);
        transition: all 0.3s;
    }

    .btn-contact-secondary:hover {
        background: var(--white);
        color: #002a5c;
    }

    @media (max-width: 768px) {
        .hero-carousel {
            height: 350px;
        }

        .hero-search {
            flex-direction: column;
        }

        .stats-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .featured-events-carousel {
            gap: 12px;
        }

        .event-card-featured {
            min-width: 250px;
        }

        .nav-boxes {
            grid-template-columns: repeat(2, 1fr);
        }

        .hero-float-shape {
            display: none;
        }

        @keyframes carousel-scroll {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(-500%);
            }
        }
    }

    /* Stat cards get a lift as their numbers count up */
    .stat-card {
        transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .stat-card:has(.stat-number[data-counter]) {
        transform: translateY(4px);
    }

    /* Hero image slides get a soft crossfade instead of a hard cut */
    .hero-carousel-slide img {
        transition: opacity 0.8s ease;
    }

    /* Nav box + graduation/event cards: add a subtle depth-tilt on hover */
    .nav-box,
    .graduation-card,
    .event-card-featured {
        will-change: transform;
    }

    .nav-box:hover,
    .graduation-card:hover,
    .event-card-featured:hover {
        box-shadow:
            0 20px 40px rgba(0, 42, 92, 0.14),
            0 2px 8px rgba(0, 42, 92, 0.08);
    }

    /* Text link underline sweep */
    .text-link,
    .graduation-card-link {
        position: relative;
    }

    .text-link::after,
    .graduation-card-link::after {
        content: "";
        position: absolute;
        left: 0;
        bottom: -2px;
        width: 100%;
        height: 1px;
        background: currentColor;
        transform: scaleX(0);
        transform-origin: right;
        transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .text-link:hover::after,
    .graduation-card-link:hover::after {
        transform: scaleX(1);
        transform-origin: left;
    }

    /* Grain/texture overlay on the hero for depth */
    .hero-carousel::before {
        content: "";
        position: absolute;
        inset: 0;
        z-index: 3;
        pointer-events: none;
        background-image: radial-gradient(rgba(255, 255, 255, 0.04) 1px, transparent 1px);
        background-size: 3px 3px;
        opacity: 0.5;
    }

    /* Section headers slide + fade rather than pop */
    .section-header {
        opacity: 0;
        transform: translateY(20px);
        transition: opacity 0.7s cubic-bezier(0.16, 1, 0.3, 1),
            transform 0.7s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .section-header.is-visible {
        opacity: 1;
        transform: translateY(0);
    }
</style>
@endsection

@section('content')
<!-- Hero Carousel Section -->
<div class="hero-carousel">
    <div class="hero-float-shape shape-1"></div>


    @if($heroImages->isNotEmpty())
    <div class="hero-carousel-track">
        @foreach($heroImages as $image)
        <div class="hero-carousel-slide">
            <img src="{{ asset('storage/' . $image->path) }}" alt="{{ $image->alt_text ?? 'Hero image' }}" loading="lazy">
        </div>
        @endforeach
        @if($heroImages->count() === 1)
        <div class="hero-carousel-slide">
            <img src="{{ asset('storage/' . $heroImages->first()->path) }}" alt="{{ $heroImages->first()->alt_text ?? 'Hero image' }}" loading="lazy">
        </div>
        @endif
    </div>
    @else
    <div style="background: linear-gradient(135deg, #002a5c 0%, #073972 100%); width: 100%; height: 100%;"></div>
    @endif

    <div class="hero-carousel-overlay">
        <div class="hero-content">
            @if($currentYear)
            <div class="hero-year">{{ $currentYear->title }} Yearbook</div>
            <h1 class="hero-title hero-title-gradient" data-reveal>{{ $currentYear->title }}</h1>
            @else
            <h1 class="hero-title hero-title-gradient" data-reveal>Yearbook</h1>
            @endif

            <p class="hero-subtitle" data-reveal>Explore stories, celebrate graduates, and relive campus moments.</p>

            <form method="POST" action="{{ route('search.perform') }}" class="hero-search">
                @csrf
                <input type="text" name="q" placeholder="Search for events, graduates, or memories..." required>
                <button type="submit">Search</button>
            </form>
        </div>
    </div>
</div>

<!-- Stats Section -->
<section class="stats-section">
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number" data-counter>{{ $stats['undergraduates'] }}</div>
            <div class="stat-label">Undergraduates</div>
        </div>
        <div class="stat-card">
            <div class="stat-number" data-counter>{{ $stats['graduates'] }}</div>
            <div class="stat-label">Graduate Degrees</div>
        </div>
        <div class="stat-card">
            <div class="stat-number" data-counter>{{ $stats['events'] }}</div>
            <div class="stat-label">Campus Events</div>
        </div>
    </div>
</section>

<!-- Dedication Section -->
@if($currentYear && $currentYear->dedication)
<section class="dedication-section">
    <div class="dedication-content" data-fade>
        <div class="dedication-label">Dedication</div>
        <p class="dedication-text">{{ $currentYear->dedication }}</p>
    </div>
</section>
@endif

<!-- Navigation Boxes -->
<section class="nav-boxes-section">
    <div class="nav-boxes stagger-group">
        <a href="{{ route('public.timeline') }}" class="nav-box stagger-item">
            <div class="nav-box-icon">📅</div>
            <h3 class="nav-box-title">Timeline</h3>
        </a>
        <a href="{{ route('public.graduates') }}" class="nav-box stagger-item">
            <div class="nav-box-icon">👥</div>
            <h3 class="nav-box-title">Graduates</h3>
        </a>
        <a href="{{ route('public.archive') }}" class="nav-box stagger-item">
            <div class="nav-box-icon">🖼️</div>
            <h3 class="nav-box-title">Galleries</h3>
        </a>
        <a href="{{ route('public.book.pdf', $currentYear->id ?? 0) }}" class="nav-box stagger-item">
            <div class="nav-box-icon">📖</div>
            <h3 class="nav-box-title">Print Edition</h3>
        </a>
    </div>
</section>

<!-- Featured Events Section -->
@if($featuredEvents->isNotEmpty())
<section class="featured-events-section">
    <div class="section-header" data-fade>
        <p class="eyebrow">Moments Matter</p>
        <h2>Featured Events</h2>
    </div>

    <div class="featured-events-carousel stagger-group" id="featuredCarousel">
        @foreach($featuredEvents as $event)
        <a href="{{ route('public.event.detail', $event->id) }}" class="event-card-featured stagger-item">
            @php $image = $event->media->first(); @endphp
            @if($image)
            <img src="{{ asset('storage/' . $image->path) }}" alt="{{ $event->title }}" class="event-card-featured-image" loading="lazy">
            @else
            <div class="event-card-featured-image"></div>
            @endif
            <div class="event-card-featured-body">
                <div class="event-card-featured-date">
                    {{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}
                </div>
                <h3 class="event-card-featured-title">{{ $event->title }}</h3>
                <p class="event-card-featured-desc">
                    {{ Str::limit($event->description, 80) }}
                </p>
            </div>
        </a>
        @endforeach
    </div>
</section>
@endif

<!-- Graduations Section -->
@if($graduations->isNotEmpty())
<section class="graduations-section">
    <div class="section-header" data-fade>
        <p class="eyebrow">Milestones</p>
        <h2>Graduations</h2>
    </div>

    <div class="graduations-grid stagger-group">
        @foreach($graduations as $graduation)
        <a href="{{ route('public.graduation.detail', $graduation->id) }}" class="graduation-card stagger-item">
            @php $image = $graduation->media->first(); @endphp
            @if($image)
            <img src="{{ asset('storage/' . $image->path) }}" alt="{{ $graduation->name }}" class="graduation-card-image" loading="lazy">
            @else
            <div class="graduation-card-image"></div>
            @endif
            <div class="graduation-card-body">
                <h3 class="graduation-card-title">{{ $graduation->name ?? 'Graduation Ceremony' }}</h3>
                <p class="graduation-card-meta">
                    {{ \Carbon\Carbon::parse($graduation->created_at)->format('F Y') }}
                </p>
                <a href="{{ route('public.graduation.detail', $graduation->id) }}" class="graduation-card-link">
                    View Details →
                </a>
            </div>
        </a>
        @endforeach
    </div>
</section>
@endif

<!-- Contact Section -->
<section class="contact-section">
    <div class="contact-content" data-fade>
        <h2 class="contact-title">Have Questions?</h2>
        <p class="contact-text">
            Need more information about our yearbook, events, or alumni network? We'd love to hear from you.
        </p>
        <div class="contact-buttons">
            <a href="mailto:yearbook@university.edu" class="btn-cta">
                <span class="btn-cta-label">Send Email</span>
                <span class="btn-cta-icon-wrap">
                    <span class="btn-cta-icon btn-cta-icon-main">→</span>
                    <span class="btn-cta-icon btn-cta-icon-ghost">→</span>
                </span>
            </a>
            <a href="{{ route('public.events') }}" class="btn-contact-secondary">
                Browse All Events
            </a>
        </div>
    </div>
</section>
@endsection