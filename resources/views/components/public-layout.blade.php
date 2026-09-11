<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'University Digital Yearbook' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Merriweather:wght@400;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .public-footer{position:relative;margin-top:auto;overflow:hidden;background:linear-gradient(135deg,#002a5c 0%,#001d42 100%);color:#fff;border-top:4px solid #ffb034}
        .public-footer::before{content:"";position:absolute;width:420px;height:420px;right:-180px;top:-250px;border:1px solid rgba(255,176,52,.18);border-radius:50%;pointer-events:none}
        .public-footer::after{content:"";position:absolute;width:250px;height:250px;left:-160px;bottom:-180px;border:1px solid rgba(255,255,255,.07);border-radius:50%;pointer-events:none}
        .public-footer-inner{position:relative;z-index:1;max-width:1280px;margin:0 auto;padding:58px 32px 28px}
        .public-footer-top{display:grid;grid-template-columns:minmax(240px,.9fr) 1.6fr;gap:70px;padding-bottom:44px}
        .public-footer-brand{max-width:360px}
        .public-footer-brand .footer-eyebrow{margin:0 0 13px;color:#ffce6b;font-size:10px;font-weight:700;letter-spacing:2px;text-transform:uppercase}
        .public-footer-brand h2{margin:0;color:#fff;font-family:"Merriweather",Georgia,serif;font-size:clamp(23px,3vw,32px);line-height:1.25}
        .public-footer-brand p:not(.footer-eyebrow){margin:13px 0 0;color:#d8e3ef;font-family:"Merriweather",Georgia,serif;font-size:14px;font-style:italic;line-height:1.6}
        .footer-gold-line{width:46px;height:3px;margin-top:24px;background:#ffb034}
        .public-footer-contact{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:26px 38px}
        .footer-contact-item{display:flex;align-items:flex-start;gap:14px;min-width:0}
        .footer-contact-icon{flex:0 0 40px;width:40px;height:40px;display:inline-flex;align-items:center;justify-content:center;border:1px solid rgba(255,176,52,.35);background:rgba(255,255,255,.055);color:#ffce6b;transition:transform .25s ease,background .25s ease,border-color .25s ease}
        .footer-contact-item:hover .footer-contact-icon{transform:translateY(-3px);background:rgba(255,176,52,.12);border-color:rgba(255,176,52,.65)}
        .footer-contact-icon svg{width:18px;height:18px;stroke:currentColor;fill:none;stroke-width:1.7;stroke-linecap:round;stroke-linejoin:round}
        .footer-contact-copy{min-width:0}
        .footer-contact-copy strong{display:block;margin-bottom:5px;color:#fff;font-size:10px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase}
        .footer-contact-copy a,.footer-contact-copy span{display:block;color:#d8e3ef;font-size:12px;line-height:1.55;overflow-wrap:anywhere;transition:color .2s ease}
        .footer-contact-copy a:hover{color:#ffce6b}
        .public-footer-bottom{display:flex;align-items:center;justify-content:space-between;gap:20px;padding-top:22px;border-top:1px solid rgba(216,227,239,.16);color:#aebfd2;font-size:10px;letter-spacing:.5px}
        .public-footer-bottom a{color:#ffce6b;font-weight:700;transition:color .2s ease}
        .public-footer-bottom a:hover{color:#fff}
        @media(max-width:820px){.public-footer-top{grid-template-columns:1fr;gap:40px}}
        @media(max-width:560px){.public-footer-inner{padding:46px 20px 22px}.public-footer-contact{grid-template-columns:1fr;gap:22px}.public-footer-bottom{align-items:flex-start;flex-direction:column;gap:10px}}
    </style>
</head>
<body>
    <div class="site-shell public-shell">
        <nav class="site-nav">
            <div class="nav-inner">
                <a href="{{ route('public.home') }}" class="brand-lockup">
                    <span class="brand-mark">LIU</span>
                    <span class="brand-copy">Digital<br><small>Yearbook</small></span>
                </a>
                <div class="nav-links public-nav-links">
                    <a href="{{ route('public.home') }}" class="nav-item {{ request()->routeIs('public.home') ? 'is-active' : '' }}">Home</a>
                    <a href="{{ route('public.events') }}" class="nav-item {{ request()->routeIs('public.events') ? 'is-active' : '' }}">Events</a>
                    <a href="{{ route('public.graduates') }}" class="nav-item {{ request()->routeIs('public.graduates') ? 'is-active' : '' }}">Graduates</a>
                    <a href="{{ route('public.archive') }}" class="nav-item {{ request()->routeIs('public.archive') ? 'is-active' : '' }}">Archive</a>
                    <a href="{{ route('search.index') }}" class="nav-item {{ request()->routeIs('search.*') ? 'is-active' : '' }}">Search</a>
                </div>
                <a href="{{ route('login') }}" class="public-staff-link">Staff login</a>
            </div>
        </nav>
        <main class="page-content">{{ $slot }}</main>
        <footer class="public-footer">
            <div class="public-footer-inner">
                <div class="public-footer-top">
                    <div class="public-footer-brand">
                        <p class="footer-eyebrow">Lebanese International University</p>
                        <h2>Lebanese International University</h2>
                        <p>Excellence in Education</p>
                        <div class="footer-gold-line" aria-hidden="true"></div>
                    </div>
                    <div class="public-footer-contact">
                        <div class="footer-contact-item">
                            <span class="footer-contact-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="14" rx="1.5"></rect><path d="m3 7 9 6 9-6"></path></svg></span>
                            <div class="footer-contact-copy"><strong>Email</strong><a href="mailto:info@liu.edu.lb">info@liu.edu.lb</a></div>
                        </div>
                        <div class="footer-contact-item">
                            <span class="footer-contact-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M7.2 3.5 5 4.4c-.8.3-1.3 1.1-1.1 2 1.2 6.8 6.9 12.5 13.7 13.7.9.2 1.7-.3 2-1.1l.9-2.2c.3-.7 0-1.5-.7-1.9l-2.8-1.4c-.6-.3-1.4-.2-1.8.4l-1.1 1.3c-2.3-1.1-4.1-2.9-5.2-5.2l1.3-1.1c.5-.4.7-1.2.4-1.8L9.1 4.2c-.4-.7-1.2-1-1.9-.7Z"></path></svg></span>
                            <div class="footer-contact-copy"><strong>Phone</strong><a href="tel:+9611705080">+961-1-705080</a></div>
                        </div>
                        <div class="footer-contact-item">
                            <span class="footer-contact-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M6 4h12v16H6z"></path><path d="M9 8h6M9 12h6M9 16h4"></path></svg></span>
                            <div class="footer-contact-copy"><strong>Fax</strong><span>+961-1-306044</span></div>
                        </div>
                        <div class="footer-contact-item">
                            <span class="footer-contact-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M12 21s7-6.1 7-12a7 7 0 1 0-14 0c0 5.9 7 12 7 12Z"></path><circle cx="12" cy="9" r="2.2"></circle></svg></span>
                            <div class="footer-contact-copy"><strong>Address</strong><span>Mousaitbeh, P.O. Box 14-6404, Beirut, Lebanon</span></div>
                        </div>
                    </div>
                </div>
                <div class="public-footer-bottom">
                    <span>© {{ now()->year }} Lebanese International University — Digital Yearbook</span>
                    <a href="{{ route('search.index') }}">Search the archive</a>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
