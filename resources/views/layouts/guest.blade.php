<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Merriweather:wght@400;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root { --ink:#002a5c; --ink-soft:#64748b; --red:#ffb034; --paper:#f7fbff; --white:#fff; --line:#d8e3ef; }
        .guest-page { min-height:100vh; display:flex; align-items:center; justify-content:center; padding:32px 20px; background:var(--paper); font-family:'Inter',sans-serif; }
        .guest-card { width:100%; max-width:430px; background:var(--white); border:1px solid var(--line); padding:42px 40px; }
        .guest-brand { text-align:center; margin-bottom:34px; }
        .guest-brand-mark { display:inline-block; color:var(--ink); font-family:'Merriweather',serif; font-size:38px; font-weight:700; line-height:1; letter-spacing:-1px; }
        .guest-brand-title { margin-top:8px; color:var(--ink-soft); font-size:10px; font-weight:600; letter-spacing:2px; text-transform:uppercase; }
        .guest-heading { margin:0; color:var(--ink); font-family:'Merriweather',serif; font-size:26px; font-weight:700; line-height:1.25; }
        .guest-subtitle { margin:8px 0 28px; color:var(--ink-soft); font-size:13px; line-height:1.6; }
        .guest-form label { color:var(--ink); font-size:11px; font-weight:600; letter-spacing:.6px; text-transform:uppercase; }
        .guest-form input[type="email"], .guest-form input[type="password"] { width:100%; margin-top:7px; padding:12px 13px; border:1px solid var(--line); border-radius:0; background:#fff; color:var(--ink); font-size:14px; outline:none; }
        .guest-form input[type="email"]:focus, .guest-form input[type="password"]:focus { border-color:var(--ink); box-shadow:none; }
        .guest-actions { display:flex; align-items:center; justify-content:space-between; gap:16px; margin-top:26px; }
        .guest-remember { display:flex; align-items:center; gap:8px; color:var(--ink-soft); font-size:12px; }
        .guest-remember input { accent-color:var(--ink); }
        .guest-forgot { color:var(--ink-soft); font-size:11px; text-decoration:none; }
        .guest-forgot:hover { color:var(--ink); }
        .guest-button { width:100%; margin-top:22px; padding:13px 18px; border:0; border-radius:0; background:var(--ink); color:#fff; font-size:11px; font-weight:700; letter-spacing:1.4px; text-transform:uppercase; cursor:pointer; }
        .guest-button:hover { background:#0a3b70; }
        .guest-error { margin-top:7px; color:#b42318; font-size:11px; }
        .guest-status { margin-bottom:20px; padding:10px 12px; border-left:3px solid var(--red); background:#fff8e8; color:var(--ink); font-size:12px; }
        @media (max-width:520px) { .guest-page{padding:20px 16px;} .guest-card{padding:34px 24px;} .guest-actions{align-items:flex-start;flex-direction:column;gap:12px;} }
    </style>
</head>
<body>
    <main class="guest-page">
        <section class="guest-card">
            <a href="/" class="guest-brand" style="display:block;text-decoration:none;">
                <span class="guest-brand-mark">LIU</span>
                <div class="guest-brand-title">Digital Yearbook</div>
            </a>
            {{ $slot }}
        </section>
    </main>
</body>
</html>
