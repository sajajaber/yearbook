@extends('public.layout')

@section('title', 'Yearbook Home')

@section('extra-css')
<style>
    /* ============ Color Palette & Design Tokens ============ */
    :root {
        --navy-dark: #002a5c;
        --navy-mid: #073972;
        --navy-light: #0a4a94;
        --gold-accent: #ffce6b;
        --gold-hover: #f59e0b;
        --blue-accent: #0284c7;
        --blue-subtitle: #d8e3ef;
        --blue-bg-light: #f7fbff;
        --cyan-orb: #4fc3f7;
        --white: #ffffff;
        --surface-border: rgba(0, 42, 92, 0.08);
    }

    /* ============ Global Setup ============ */
    .yearbook-wrapper {
        background-color: #f8fafc;
        color: var(--navy-dark);
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
    }

    /* ============ Editorial Split Hero ============ */
    .hero-container {
        background: linear-gradient(135deg, var(--navy-dark) 0%, var(--navy-mid) 100%);
        color: var(--white);
        position: relative;
        overflow: hidden;
        padding: 80px 20px;
        min-height: 560px;
        display: flex;
        align-items: center;
    }

    /* Decorative Background Accents */
    .hero-glow-cyan {
        position: absolute;
        width: 380px;
        height: 380px;
        background: radial-gradient(circle, rgba(79, 195, 247, 0.25) 0%, transparent 70%);
        top: -100px;
        right: -50px;
        pointer-events: none;
    }

    .hero-glow-gold {
        position: absolute;
        width: 320px;
        height: 320px;
        background: radial-gradient(circle, rgba(255, 206, 107, 0.18) 0%, transparent 70%);
        bottom: -80px;
        left: -40px;
        pointer-events: none;
    }

    .hero-layout {
        max-width: 1200px;
        margin: 0 auto;
        width: 100%;
        display: grid;
        grid-template-columns: 1.1fr 0.9fr;
        gap: 48px;
        align-items: center;
        position: relative;
        z-index: 2;
    }

    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid var(--gold-accent);
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: var(--gold-accent);
        margin-bottom: 24px;
        backdrop-filter: blur(8px);
    }

    .hero-title {
        font-family: "Merriweather", Georgia, serif;
        font-size: clamp(2.5rem, 5vw, 3.8rem);
        font-weight: 800;
        line-height: 1.15;
        margin-bottom: 20px;
        color: var(--white);
    }

    .hero-title span {
        color: var(--gold-accent);
        display: inline-block;
    }

    .hero-subtitle {
        font-size: 1.1rem;
        color: var(--blue-subtitle);
        line-height: 1.6;
        margin-bottom: 32px;
        max-width: 500px;
    }

    /* Search Input Pill */
    .hero-search-box {
        display: flex;
        background: var(--white);
        border-radius: 50px;
        padding: 6px;
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.25);
        max-width: 480px;
    }

    .hero-search-box input {
        flex: 1;
        border: none;
        background: transparent;
        padding: 12px 20px;
        font-size: 0.95rem;
        outline: none;
        color: var(--navy-dark);
    }

    .hero-search-box button {
        background: var(--gold-accent);
        color: var(--navy-dark);
        border: none;
        padding: 12px 28px;
        border-radius: 50px;
        font-weight: 700;
        cursor: pointer;
        transition: background 0.25s ease, transform 0.2s ease;
    }

    .hero-search-box button:hover {
        background: var(--gold-hover);
        transform: scale(1.02);
    }

    /* Hero Frame Visual */
    .hero-frame-container {
        position: relative;
    }

    .hero-frame-card {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 16px;
        padding: 12px;
        backdrop-filter: blur(12px);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.35);
    }

    .hero-frame-image {
        width: 100%;
        height: 340px;
        object-fit: cover;
        border-radius: 10px;
        display: block;
    }

    /* ============ Stats Ribbon ============ */
    .stats-ribbon {
        max-width: 1100px;
        margin: -40px auto 60px;
        background: var(--white);
        border-radius: 16px;
        box-shadow: 0 15px 35px rgba(0, 42, 92, 0.08);
        border: 1px solid var(--surface-border);
        position: relative;
        z-index: 5;
        padding: 28px;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
    }

    .stat-item {
        text-align: center;
        position: relative;
    }

    .stat-item:not(:last-child)::after {
        content: "";
        position: absolute;
        right: 0;
        top: 20%;
        height: 60%;
        width: 1px;
        background: var(--surface-border);
    }

    .stat-value {
        font-family: "Merriweather", serif;
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--navy-dark);
        line-height: 1;
        margin-bottom: 6px;
    }

    .stat-tag {
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--blue-accent);
    }

    /* ============ Quick Nav Cards ============ */
    .section-container {
        max-width: 1100px;
        margin: 0 auto 70px;
        padding: 0 20px;
    }

    .nav-tiles {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
        gap: 20px;
    }

    .nav-tile {
        background: var(--white);
        border: 1px solid var(--surface-border);
        border-radius: 12px;
        padding: 28px 20px;
        text-decoration: none;
        color: var(--navy-dark);
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .nav-tile:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 28px rgba(0, 42, 92, 0.1);
        border-color: var(--gold-accent);
    }

    .nav-tile-icon {
        width: 56px;
        height: 56px;
        background: var(--blue-bg-light);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 16px;
        transition: transform 0.3s ease;
    }

    .nav-tile:hover .nav-tile-icon {
        transform: scale(1.1);
        background: #eef6ff;
    }

    .nav-tile-title {
        font-family: "Merriweather", serif;
        font-size: 1.1rem;
        font-weight: 700;
        margin: 0;
    }

    /* ============ Dedication Pull-Quote ============ */
    .dedication-box {
        background: linear-gradient(135deg, var(--navy-dark) 0%, var(--navy-mid) 100%);
        border-left: 6px solid var(--gold-accent);
        border-radius: 12px;
        padding: 40px;
        color: var(--white);
        margin-bottom: 70px;
        position: relative;
    }

    .dedication-tag {
        font-size: 0.75rem;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: var(--gold-accent);
        font-weight: 700;
        margin-bottom: 12px;
    }

    .dedication-quote {
        font-family: "Merriweather", Georgia, serif;
        font-size: 1.35rem;
        line-height: 1.6;
        font-style: italic;
        color: var(--blue-subtitle);
        margin: 0;
    }

    /* ============ Section Titles ============ */
    .heading-block {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        margin-bottom: 32px;
        border-bottom: 2px solid var(--surface-border);
        padding-bottom: 16px;
    }

    .heading-block-left .kicker {
        font-size: 0.75rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: var(--blue-accent);
        margin-bottom: 4px;
    }

    .heading-block-left h2 {
        font-family: "Merriweather", serif;
        font-size: 2rem;
        font-weight: 800;
        color: var(--navy-dark);
        margin: 0;
    }

    /* ============ Events Auto Slider ============ */
    .events-slider-wrapper {
        position: relative;
        overflow: hidden;
    }

    .events-scroll-row {
        display: flex;
        gap: 24px;
        overflow-x: auto;
        padding-bottom: 16px;
        scroll-behavior: smooth;
        -webkit-overflow-scrolling: touch;
    }

    .events-scroll-row::-webkit-scrollbar {
        height: 6px;
    }

    .events-scroll-row::-webkit-scrollbar-thumb {
        background: var(--navy-light);
        border-radius: 4px;
    }

    .event-card {
        min-width: 280px;
        max-width: 320px;
        flex: 0 0 auto;
        background: var(--white);
        border-radius: 12px;
        border: 1px solid var(--surface-border);
        overflow: hidden;
        text-decoration: none;
        color: inherit;
        scroll-snap-align: start;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        display: flex;
        flex-direction: column;
    }

    .event-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 16px 30px rgba(0, 42, 92, 0.12);
    }

    .event-card-img {
        width: 100%;
        height: 180px;
        object-fit: cover;
        background: var(--navy-dark);
    }

    .event-card-content {
        padding: 20px;
        display: flex;
        flex-direction: column;
        flex: 1;
    }

    .event-date-badge {
        font-size: 0.75rem;
        font-weight: 800;
        color: var(--navy-light);
        text-transform: uppercase;
        margin-bottom: 8px;
    }

    .event-card-title {
        font-family: "Merriweather", serif;
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--navy-dark);
        margin-bottom: 8px;
        line-height: 1.35;
    }

    .event-card-desc {
        font-size: 0.875rem;
        color: var(--navy-mid);
        line-height: 1.5;
        opacity: 0.8;
    }

    /* Carousel Navigation Indicators */
    .slider-indicators {
        display: flex;
        justify-content: center;
        gap: 8px;
        margin-top: 16px;
    }

    .slider-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: var(--surface-border);
        transition: background 0.3s ease, transform 0.3s ease;
        cursor: pointer;
    }

    .slider-dot.active {
        background: var(--navy-light);
        transform: scale(1.2);
    }

    /* ============ Graduations Grid ============ */
    .graduations-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 24px;
    }

    .graduation-tile {
        background: var(--white);
        border-radius: 12px;
        border: 1px solid var(--surface-border);
        overflow: hidden;
        text-decoration: none;
        color: inherit;
        display: flex;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .graduation-tile:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0, 42, 92, 0.1);
    }

    .graduation-tile-img {
        width: 120px;
        height: 100%;
        min-height: 130px;
        object-fit: cover;
        background: var(--navy-dark);
    }

    .graduation-tile-info {
        padding: 20px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .graduation-tile-title {
        font-family: "Merriweather", serif;
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--navy-dark);
        margin-bottom: 6px;
    }

    .graduation-tile-date {
        font-size: 0.85rem;
        color: var(--navy-light);
        font-weight: 600;
    }

    /* ============ Contact CTA Footer Banner ============ */
    .cta-banner {
        background: linear-gradient(135deg, var(--navy-dark) 0%, var(--navy-mid) 100%);
        color: var(--white);
        padding: 60px 20px;
        text-align: center;
        border-top: 4px solid var(--gold-accent);
    }

    .cta-banner-content {
        max-width: 600px;
        margin: 0 auto;
    }

    .cta-banner h2 {
        font-family: "Merriweather", serif;
        font-size: 2.2rem;
        font-weight: 800;
        margin-bottom: 12px;
    }

    .cta-banner p {
        color: var(--blue-subtitle);
        font-size: 1rem;
        margin-bottom: 28px;
    }

    .cta-actions {
        display: flex;
        justify-content: center;
        gap: 16px;
        flex-wrap: wrap;
    }

    .btn-main {
        background: var(--gold-accent);
        color: var(--navy-dark);
        padding: 14px 32px;
        border-radius: 50px;
        font-weight: 700;
        text-decoration: none;
        transition: background 0.25s ease, transform 0.2s ease;
    }

    .btn-main:hover {
        background: var(--gold-hover);
        transform: translateY(-2px);
    }

    .btn-outline {
        border: 2px solid rgba(255, 255, 255, 0.4);
        color: var(--white);
        padding: 12px 28px;
        border-radius: 50px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.25s ease;
    }

    .btn-outline:hover {
        border-color: var(--white);
        background: rgba(255, 255, 255, 0.1);
    }

    /* Responsive Design */
    @media (max-width: 900px) {
        .hero-layout {
            grid-template-columns: 1fr;
            gap: 36px;
        }

        .stats-grid {
            grid-template-columns: 1fr;
            gap: 16px;
        }

        .stat-item:not(:last-child)::after {
            display: none;
        }
    }
</style>
@endsection

@section('content')
<div class="yearbook-wrapper">

    <!-- Hero Section -->
    <section class="hero-container">
        <div class="hero-glow-cyan"></div>
        <div class="hero-glow-gold"></div>

        <div class="hero-layout">
            <div class="hero-text-side">
                @if($currentYear)
                <div class="hero-badge">✦ {{ $currentYear->title }} Edition</div>
                <h1 class="hero-title">Preserving <span>Memories</span>, Celebrating Excellence.</h1>
                @else
                <div class="hero-badge">✦ Archive Edition</div>
                <h1 class="hero-title">Academic <span>Yearbook</span></h1>
                @endif

                <p class="hero-subtitle">
                    Discover campus history, celebrate graduating classes, and explore the landmark events that defined our journey.
                </p>

                <form method="POST" action="{{ route('search.perform') }}" class="hero-search-box">
                    @csrf
                    <input type="text" name="q" placeholder="Search graduates, events, or stories..." required>
                    <button type="submit">Search</button>
                </form>
            </div>

            <div class="hero-frame-container">
                <div class="hero-frame-card">
                    @if($heroImages->isNotEmpty())
                    <img src="{{ asset('storage/' . $heroImages->first()->path) }}" alt="Yearbook Preview" class="hero-frame-image">
                    @else
                    <div class="hero-frame-image" style="background: var(--navy-mid); display: flex; align-items: center; justify-content: center; color: var(--blue-subtitle);">
                        Yearbook Collection
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Key Statistics Ribbon -->
    <div class="stats-ribbon">
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-value">{{ $stats['undergraduates'] }}</div>
                <div class="stat-tag">Undergraduates</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ $stats['graduates'] }}</div>
                <div class="stat-tag">Postgraduates</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ $stats['events'] }}</div>
                <div class="stat-tag">Campus Events</div>
            </div>
        </div>
    </div>

    <!-- Main Content Container -->
    <div class="section-container">

        <!-- Navigation Shortcut Tiles -->
        <div class="nav-tiles">
            <a href="{{ route('public.timeline') }}" class="nav-tile">
                <div class="nav-tile-icon">📅</div>
                <h3 class="nav-tile-title">Timeline</h3>
            </a>
            <a href="{{ route('public.graduates') }}" class="nav-tile">
                <div class="nav-tile-icon">🎓</div>
                <h3 class="nav-tile-title">Graduates</h3>
            </a>
            <a href="{{ route('public.archive') }}" class="nav-tile">
                <div class="nav-tile-icon">🖼️</div>
                <h3 class="nav-tile-title">Galleries</h3>
            </a>
            <a href="{{ route('public.book.pdf', $currentYear->id ?? 0) }}" class="nav-tile">
                <div class="nav-tile-icon">📖</div>
                <h3 class="nav-tile-title">Print Edition</h3>
            </a>
        </div>

        <!-- Dedication Section -->
        @if($currentYear && $currentYear->dedication)
        <div class="dedication-box" style="margin-top: 50px;">
            <div class="dedication-tag">Yearbook Dedication</div>
            <p class="dedication-quote">"{{ $currentYear->dedication }}"</p>
        </div>
        @endif

        <!-- Featured Events Auto-Slider -->
        @if($featuredEvents->isNotEmpty())
        <div style="margin-top: 60px;">
            <div class="heading-block">
                <div class="heading-block-left">
                    <div class="kicker">Highlights</div>
                    <h2>Featured Events</h2>
                </div>
            </div>


            <section class="campus-marquee-section" aria-label="LIU campuses">
  <div class="container">
    <div class="campus-marquee-intro">
      <span class="campus-eyebrow">10 Campuses Worldwide</span>
      <h2 class="campus-heading">Across Lebanon &amp; Beyond</h2>
      <p class="campus-subtext">Seven campuses in Lebanon plus international campuses in Yemen, Senegal, and Mauritania bringing world-class education to every community we serve.</p>
    </div>
  </div>

  <div class="campus-track-wrap">
    <div class="campus-track-fade-left" aria-hidden="true"></div>
    <div class="campus-track-fade-right" aria-hidden="true"></div>

    <div class="campus-track" id="campusTrack">
            <div class="campus-mq-card" aria-hidden="false">
        <div class="campus-mq-img">
          <div class="campus-mq-img-bg bg-campus-beirut" style="background-image:url('https://admincms.liu.edu.lb/Admin_CMS26/uploads/images/img_69f1edbc7976f1.75486401.jpg')"></div>
          <div class="campus-mq-overlay"></div>
        </div>
        <div class="campus-mq-body">
          <span class="campus-mq-type">Main Campus</span>
          <h4 class="campus-mq-name">Beirut</h4>
          <p class="campus-mq-loc">
            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>
            Mouseitbeh, Beirut          </p>
        </div>
      </div>
            <div class="campus-mq-card" aria-hidden="false">
        <div class="campus-mq-img">
          <div class="campus-mq-img-bg bg-campus-bekaa" style="background-image:url('https://admincms.liu.edu.lb/Admin_CMS26/uploads/images/img_69f1ede6de82a7.53351121.jpg')"></div>
          <div class="campus-mq-overlay"></div>
        </div>
        <div class="campus-mq-body">
          <span class="campus-mq-type">Bekaa Campus</span>
          <h4 class="campus-mq-name">Bekaa</h4>
          <p class="campus-mq-loc">
            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>
            Bekaa Valley          </p>
        </div>
      </div>
            <div class="campus-mq-card" aria-hidden="false">
        <div class="campus-mq-img">
          <div class="campus-mq-img-bg bg-campus-saida" style="background-image:url('https://admincms.liu.edu.lb/Admin_CMS26/uploads/images/img_69f1edc5f42380.20841864.jpg')"></div>
          <div class="campus-mq-overlay"></div>
        </div>
        <div class="campus-mq-body">
          <span class="campus-mq-type">South Campus</span>
          <h4 class="campus-mq-name">Saida</h4>
          <p class="campus-mq-loc">
            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>
            Sidon, South Lebanon          </p>
        </div>
      </div>
            <div class="campus-mq-card" aria-hidden="false">
        <div class="campus-mq-img">
          <div class="campus-mq-img-bg bg-campus-nabatieh" style="background-image:url('https://admincms.liu.edu.lb/Admin_CMS26/uploads/images/img_69f1ee031aeaa3.07545058.jpg')"></div>
          <div class="campus-mq-overlay"></div>
        </div>
        <div class="campus-mq-body">
          <span class="campus-mq-type">South Campus</span>
          <h4 class="campus-mq-name">Nabatieh</h4>
          <p class="campus-mq-loc">
            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>
            Nabatieh Governorate          </p>
        </div>
      </div>
            <div class="campus-mq-card" aria-hidden="false">
        <div class="campus-mq-img">
          <div class="campus-mq-img-bg bg-campus-tripoli" style="background-image:url('https://admincms.liu.edu.lb/Admin_CMS26/uploads/images/img_69f1eecc60f477.72673930.jpg')"></div>
          <div class="campus-mq-overlay"></div>
        </div>
        <div class="campus-mq-body">
          <span class="campus-mq-type">North Campus</span>
          <h4 class="campus-mq-name">Tripoli</h4>
          <p class="campus-mq-loc">
            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>
            Tripoli, North Lebanon          </p>
        </div>
      </div>
            <div class="campus-mq-card" aria-hidden="false">
        <div class="campus-mq-img">
          <div class="campus-mq-img-bg bg-campus-mount" style="background-image:url('https://admincms.liu.edu.lb/Admin_CMS26/uploads/images/img_69f1ef00ec4b76.22853073.jpg')"></div>
          <div class="campus-mq-overlay"></div>
        </div>
        <div class="campus-mq-body">
          <span class="campus-mq-type">Mountain Campus</span>
          <h4 class="campus-mq-name">Mount Lebanon</h4>
          <p class="campus-mq-loc">
            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>
            Mount Lebanon          </p>
        </div>
      </div>
            <div class="campus-mq-card" aria-hidden="false">
        <div class="campus-mq-img">
          <div class="campus-mq-img-bg bg-campus-tyre" style="background-image:url('https://admincms.liu.edu.lb/Admin_CMS26/uploads/images/img_69f1edccdc4bb7.85841464.jpg')"></div>
          <div class="campus-mq-overlay"></div>
        </div>
        <div class="campus-mq-body">
          <span class="campus-mq-type">South Campus</span>
          <h4 class="campus-mq-name">Tyre</h4>
          <p class="campus-mq-loc">
            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>
            Tyre (Sour)          </p>
        </div>
      </div>
            <div class="campus-mq-card" aria-hidden="false">
        <div class="campus-mq-img">
          <div class="campus-mq-img-bg bg-campus-rayak" style="background-image:url('https://admincms.liu.edu.lb/Admin_CMS26/uploads/images/img_69f1edc138b486.23271269.jpg')"></div>
          <div class="campus-mq-overlay"></div>
        </div>
        <div class="campus-mq-body">
          <span class="campus-mq-type">Bekaa Campus</span>
          <h4 class="campus-mq-name">Rayak</h4>
          <p class="campus-mq-loc">
            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>
            Rayak, Bekaa          </p>
        </div>
      </div>
            <div class="campus-mq-card" aria-hidden="false">
        <div class="campus-mq-img">
          <div class="campus-mq-img-bg bg-campus-akkar" style="background-image:url('https://admincms.liu.edu.lb/Admin_CMS26/uploads/images/img_69f1eddf0de1e5.11140105.jpg')"></div>
          <div class="campus-mq-overlay"></div>
        </div>
        <div class="campus-mq-body">
          <span class="campus-mq-type">North Campus</span>
          <h4 class="campus-mq-name">Akkar</h4>
          <p class="campus-mq-loc">
            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>
            Akkar, North Lebanon          </p>
        </div>
      </div>
            <div class="campus-mq-card" aria-hidden="false">
        <div class="campus-mq-img">
          <div class="campus-mq-img-bg bg-campus-yemen" style="background-image:url('https://admincms.liu.edu.lb/Admin_CMS26/uploads/images/img_69f1ee5d33e079.67491444.jpg')"></div>
          <div class="campus-mq-overlay"></div>
        </div>
        <div class="campus-mq-body">
          <span class="campus-mq-type">LIU Yemen</span>
          <h4 class="campus-mq-name">Yemen</h4>
          <p class="campus-mq-loc">
            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>
            Sana'a, Yemen          </p>
        </div>
      </div>
            <div class="campus-mq-card" aria-hidden="false">
        <div class="campus-mq-img">
          <div class="campus-mq-img-bg bg-campus-senegal" style="background-image:url('https://admincms.liu.edu.lb/Admin_CMS26/uploads/images/img_69f1ee5d344fc6.61631554.jpg')"></div>
          <div class="campus-mq-overlay"></div>
        </div>
        <div class="campus-mq-body">
          <span class="campus-mq-type">LIU Senegal</span>
          <h4 class="campus-mq-name">Senegal</h4>
          <p class="campus-mq-loc">
            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>
            Dakar, Senegal          </p>
        </div>
      </div>
            <div class="campus-mq-card" aria-hidden="false">
        <div class="campus-mq-img">
          <div class="campus-mq-img-bg bg-campus-mauritania" style="background-image:url('https://admincms.liu.edu.lb/Admin_CMS26/uploads/images/img_69f1ee5d349778.31520671.jpg')"></div>
          <div class="campus-mq-overlay"></div>
        </div>
        <div class="campus-mq-body">
          <span class="campus-mq-type">LIU Mauritania</span>
          <h4 class="campus-mq-name">Mauritania</h4>
          <p class="campus-mq-loc">
            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>
            Nouakchott, Mauritania          </p>
        </div>
      </div>
            <div class="campus-mq-card" aria-hidden="true">
        <div class="campus-mq-img">
          <div class="campus-mq-img-bg bg-campus-beirut" style="background-image:url('https://admincms.liu.edu.lb/Admin_CMS26/uploads/images/img_69f1edbc7976f1.75486401.jpg')"></div>
          <div class="campus-mq-overlay"></div>
        </div>
        <div class="campus-mq-body">
          <span class="campus-mq-type">Main Campus</span>
          <h4 class="campus-mq-name">Beirut</h4>
          <p class="campus-mq-loc">
            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>
            Mouseitbeh, Beirut          </p>
        </div>
      </div>
            <div class="campus-mq-card" aria-hidden="true">
        <div class="campus-mq-img">
          <div class="campus-mq-img-bg bg-campus-bekaa" style="background-image:url('https://admincms.liu.edu.lb/Admin_CMS26/uploads/images/img_69f1ede6de82a7.53351121.jpg')"></div>
          <div class="campus-mq-overlay"></div>
        </div>
        <div class="campus-mq-body">
          <span class="campus-mq-type">Bekaa Campus</span>
          <h4 class="campus-mq-name">Bekaa</h4>
          <p class="campus-mq-loc">
            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>
            Bekaa Valley          </p>
        </div>
      </div>
            <div class="campus-mq-card" aria-hidden="true">
        <div class="campus-mq-img">
          <div class="campus-mq-img-bg bg-campus-saida" style="background-image:url('https://admincms.liu.edu.lb/Admin_CMS26/uploads/images/img_69f1edc5f42380.20841864.jpg')"></div>
          <div class="campus-mq-overlay"></div>
        </div>
        <div class="campus-mq-body">
          <span class="campus-mq-type">South Campus</span>
          <h4 class="campus-mq-name">Saida</h4>
          <p class="campus-mq-loc">
            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>
            Sidon, South Lebanon          </p>
        </div>
      </div>
            <div class="campus-mq-card" aria-hidden="true">
        <div class="campus-mq-img">
          <div class="campus-mq-img-bg bg-campus-nabatieh" style="background-image:url('https://admincms.liu.edu.lb/Admin_CMS26/uploads/images/img_69f1ee031aeaa3.07545058.jpg')"></div>
          <div class="campus-mq-overlay"></div>
        </div>
        <div class="campus-mq-body">
          <span class="campus-mq-type">South Campus</span>
          <h4 class="campus-mq-name">Nabatieh</h4>
          <p class="campus-mq-loc">
            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>
            Nabatieh Governorate          </p>
        </div>
      </div>
            <div class="campus-mq-card" aria-hidden="true">
        <div class="campus-mq-img">
          <div class="campus-mq-img-bg bg-campus-tripoli" style="background-image:url('https://admincms.liu.edu.lb/Admin_CMS26/uploads/images/img_69f1eecc60f477.72673930.jpg')"></div>
          <div class="campus-mq-overlay"></div>
        </div>
        <div class="campus-mq-body">
          <span class="campus-mq-type">North Campus</span>
          <h4 class="campus-mq-name">Tripoli</h4>
          <p class="campus-mq-loc">
            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>
            Tripoli, North Lebanon          </p>
        </div>
      </div>
            <div class="campus-mq-card" aria-hidden="true">
        <div class="campus-mq-img">
          <div class="campus-mq-img-bg bg-campus-mount" style="background-image:url('https://admincms.liu.edu.lb/Admin_CMS26/uploads/images/img_69f1ef00ec4b76.22853073.jpg')"></div>
          <div class="campus-mq-overlay"></div>
        </div>
        <div class="campus-mq-body">
          <span class="campus-mq-type">Mountain Campus</span>
          <h4 class="campus-mq-name">Mount Lebanon</h4>
          <p class="campus-mq-loc">
            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>
            Mount Lebanon          </p>
        </div>
      </div>
            <div class="campus-mq-card" aria-hidden="true">
        <div class="campus-mq-img">
          <div class="campus-mq-img-bg bg-campus-tyre" style="background-image:url('https://admincms.liu.edu.lb/Admin_CMS26/uploads/images/img_69f1edccdc4bb7.85841464.jpg')"></div>
          <div class="campus-mq-overlay"></div>
        </div>
        <div class="campus-mq-body">
          <span class="campus-mq-type">South Campus</span>
          <h4 class="campus-mq-name">Tyre</h4>
          <p class="campus-mq-loc">
            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>
            Tyre (Sour)          </p>
        </div>
      </div>
            <div class="campus-mq-card" aria-hidden="true">
        <div class="campus-mq-img">
          <div class="campus-mq-img-bg bg-campus-rayak" style="background-image:url('https://admincms.liu.edu.lb/Admin_CMS26/uploads/images/img_69f1edc138b486.23271269.jpg')"></div>
          <div class="campus-mq-overlay"></div>
        </div>
        <div class="campus-mq-body">
          <span class="campus-mq-type">Bekaa Campus</span>
          <h4 class="campus-mq-name">Rayak</h4>
          <p class="campus-mq-loc">
            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>
            Rayak, Bekaa          </p>
        </div>
      </div>
            <div class="campus-mq-card" aria-hidden="true">
        <div class="campus-mq-img">
          <div class="campus-mq-img-bg bg-campus-akkar" style="background-image:url('https://admincms.liu.edu.lb/Admin_CMS26/uploads/images/img_69f1eddf0de1e5.11140105.jpg')"></div>
          <div class="campus-mq-overlay"></div>
        </div>
        <div class="campus-mq-body">
          <span class="campus-mq-type">North Campus</span>
          <h4 class="campus-mq-name">Akkar</h4>
          <p class="campus-mq-loc">
            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>
            Akkar, North Lebanon          </p>
        </div>
      </div>
            <div class="campus-mq-card" aria-hidden="true">
        <div class="campus-mq-img">
          <div class="campus-mq-img-bg bg-campus-yemen" style="background-image:url('https://admincms.liu.edu.lb/Admin_CMS26/uploads/images/img_69f1ee5d33e079.67491444.jpg')"></div>
          <div class="campus-mq-overlay"></div>
        </div>
        <div class="campus-mq-body">
          <span class="campus-mq-type">LIU Yemen</span>
          <h4 class="campus-mq-name">Yemen</h4>
          <p class="campus-mq-loc">
            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>
            Sana'a, Yemen          </p>
        </div>
      </div>
            <div class="campus-mq-card" aria-hidden="true">
        <div class="campus-mq-img">
          <div class="campus-mq-img-bg bg-campus-senegal" style="background-image:url('https://admincms.liu.edu.lb/Admin_CMS26/uploads/images/img_69f1ee5d344fc6.61631554.jpg')"></div>
          <div class="campus-mq-overlay"></div>
        </div>
        <div class="campus-mq-body">
          <span class="campus-mq-type">LIU Senegal</span>
          <h4 class="campus-mq-name">Senegal</h4>
          <p class="campus-mq-loc">
            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>
            Dakar, Senegal          </p>
        </div>
      </div>
            <div class="campus-mq-card" aria-hidden="true">
        <div class="campus-mq-img">
          <div class="campus-mq-img-bg bg-campus-mauritania" style="background-image:url('https://admincms.liu.edu.lb/Admin_CMS26/uploads/images/img_69f1ee5d349778.31520671.jpg')"></div>
          <div class="campus-mq-overlay"></div>
        </div>
        <div class="campus-mq-body">
          <span class="campus-mq-type">LIU Mauritania</span>
          <h4 class="campus-mq-name">Mauritania</h4>
          <p class="campus-mq-loc">
            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>
            Nouakchott, Mauritania          </p>
        </div>
      </div>
          </div>
  </div>
</section>


            <div class="events-slider-wrapper">
                <div class="events-scroll-row" id="featuredEventsRow">
                    @foreach($featuredEvents as $event)
                    <a href="{{ route('public.event.detail', $event->id) }}" class="event-card">
                        @php $image = $event->media->first(); @endphp
                        @if($image)
                        <img src="{{ asset('storage/' . $image->path) }}" alt="{{ $event->title }}" class="event-card-img" loading="lazy">
                        @else
                        <div class="event-card-img"></div>
                        @endif
                        <div class="event-card-content">
                            <span class="event-date-badge">{{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}</span>
                            <h3 class="event-card-title">{{ $event->title }}</h3>
                            <p class="event-card-desc">{{ Str::limit($event->description, 80) }}</p>
                        </div>
                    </a>
                    @endforeach
                </div>
                <div class="slider-indicators" id="sliderIndicators"></div>
            </div>
        </div>
        @endif

        <!-- Graduations Section -->
        @if($graduations->isNotEmpty())
        <div style="margin-top: 60px;">
            <div class="heading-block">
                <div class="heading-block-left">
                    <div class="kicker">Commencement</div>
                    <h2>Graduations</h2>
                </div>
            </div>

            <div class="graduations-grid">
                @foreach($graduations as $graduation)
                <a href="{{ route('public.graduation.detail', $graduation->id) }}" class="graduation-tile">
                    @php $image = $graduation->media->first(); @endphp
                    @if($image)
                    <img src="{{ asset('storage/' . $image->path) }}" alt="{{ $graduation->name }}" class="graduation-tile-img" loading="lazy">
                    @else
                    <div class="graduation-tile-img"></div>
                    @endif
                    <div class="graduation-tile-info">
                        <h3 class="graduation-tile-title">{{ $graduation->name ?? 'Graduation Ceremony' }}</h3>
                        <span class="graduation-tile-date">{{ \Carbon\Carbon::parse($graduation->created_at)->format('F Y') }}</span>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

    </div>

    <!-- Contact & Inquiry Banner -->
    <section class="cta-banner">
        <div class="cta-banner-content">
            <h2>Have Questions?</h2>
            <p>Reach out to the yearbook committee for questions regarding submissions, photo archives, or order requests.</p>
            <div class="cta-actions">
                <a href="mailto:yearbook@university.edu" class="btn-main">Send Email</a>
                <a href="{{ route('public.events') }}" class="btn-outline">Explore Events</a>
            </div>
        </div>
    </section>

</div>
@endsection

@section('extra-js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const slider = document.getElementById('featuredEventsRow');
        const indicatorsContainer = document.getElementById('sliderIndicators');
        if (!slider) return;

        const cards = slider.querySelectorAll('.event-card');
        if (cards.length === 0) return;

        let autoSlideTimer = null;
        let isUserHovered = false;

        // 1. Calculate scroll step (Card width + Gap)
        function getStepWidth() {
            const card = cards[0];
            const gap = 24; // Matches gap: 24px in CSS
            return card.offsetWidth + gap;
        }

        // 2. Perform the auto-scroll step
        function autoScrollNext() {
            if (isUserHovered) return;

            // Check if content actually overflows container
            const maxScroll = slider.scrollWidth - slider.clientWidth;
            if (maxScroll <= 0) return;

            const stepWidth = getStepWidth();

            // If we reached or passed the end, loop back smoothly to start
            if (slider.scrollLeft >= maxScroll - 10) {
                slider.scrollTo({
                    left: 0,
                    behavior: 'smooth'
                });
            } else {
                slider.scrollBy({
                    left: stepWidth,
                    behavior: 'smooth'
                });
            }
        }

        // 3. Setup dynamic navigation dots
        if (indicatorsContainer && cards.length > 1) {
            indicatorsContainer.innerHTML = ''; // Clear previous

            cards.forEach((_, idx) => {
                const dot = document.createElement('div');
                dot.classList.add('slider-dot');
                if (idx === 0) dot.classList.add('active');

                dot.addEventListener('click', () => {
                    slider.scrollTo({
                        left: getStepWidth() * idx,
                        behavior: 'smooth'
                    });
                });

                indicatorsContainer.appendChild(dot);
            });

            const dots = indicatorsContainer.querySelectorAll('.slider-dot');

            // Sync active dot state on manual or automatic scroll
            slider.addEventListener('scroll', () => {
                const stepWidth = getStepWidth();
                const activeIndex = Math.min(
                    Math.round(slider.scrollLeft / stepWidth),
                    dots.length - 1
                );

                dots.forEach((dot, idx) => {
                    dot.classList.toggle('active', idx === activeIndex);
                });
            });
        }

        // 4. Start automatic timer loop (every 3 seconds)
        function startTimer() {
            if (!autoSlideTimer) {
                autoSlideTimer = setInterval(autoScrollNext, 3000);
            }
        }

        function stopTimer() {
            if (autoSlideTimer) {
                clearInterval(autoSlideTimer);
                autoSlideTimer = null;
            }
        }

        // Pause on user interaction (hover/touch) and resume when leaving
        slider.addEventListener('mouseenter', () => isUserHovered = true);
        slider.addEventListener('mouseleave', () => isUserHovered = false);
        slider.addEventListener('touchstart', () => isUserHovered = true, {
            passive: true
        });
        slider.addEventListener('touchend', () => isUserHovered = false, {
            passive: true
        });

        // Initialize auto-scroll
        startTimer();
    });
</script>

@endsection