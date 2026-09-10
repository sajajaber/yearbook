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
        /* Shared LIU pagination styles */
        .pagination,.graduates-pagination,.events-pagination,.media-pagination{display:flex;align-items:center;justify-content:center;flex-wrap:wrap;gap:6px;margin-top:32px}
        .pagination a,.pagination span,.graduates-pagination a,.graduates-pagination span,.events-pagination a,.events-pagination span,.media-pagination a,.media-pagination span{display:inline-flex;align-items:center;justify-content:center;min-width:36px;height:36px;padding:0 10px;border:1px solid var(--line);border-radius:0;background:#fff;color:var(--ink);font:600 10px/1 "Inter",sans-serif;letter-spacing:.4px;text-decoration:none;transition:background .18s ease,color .18s ease,border-color .18s ease}
        .pagination a:hover,.graduates-pagination a:hover,.events-pagination a:hover,.media-pagination a:hover{border-color:var(--ink);background:var(--ink);color:#fff}
        .pagination .active span,.graduates-pagination .active,.events-pagination .active,.media-pagination .active{border-color:var(--ink);background:var(--ink);color:#fff}
        .pagination .disabled span,.graduates-pagination .disabled,.events-pagination .disabled,.media-pagination .disabled{color:#a7b3c0;background:#f8fafc;cursor:not-allowed}
        nav[aria-label="Pagination Navigation"] svg{width:14px;height:14px}
        nav[aria-label="Pagination Navigation"] a,nav[aria-label="Pagination Navigation"] span{border-radius:0!important;box-shadow:none!important}

        /* LIU account dropdown */
        .site-dropdown-menu{min-width:190px;overflow:hidden;border:1px solid var(--line);background:#fff;box-shadow:0 14px 35px rgba(0,42,92,.12);border-radius:0!important}
        .site-dropdown-content{padding:6px 0;background:#fff;border:0!important;border-radius:0!important}
        .site-dropdown-link{display:block;width:100%;padding:11px 16px;color:var(--ink)!important;background:#fff;font:600 11px/1.4 "Inter",sans-serif!important;letter-spacing:.8px;text-transform:uppercase;text-decoration:none;transition:background .18s ease,color .18s ease}
        .site-dropdown-link:hover,.site-dropdown-link:focus{background:#f7fbff!important;color:var(--ink)!important;outline:none}
        .site-dropdown-link:active{background:#eef5fb!important}
        .site-dropdown-content form{margin:0}
        .site-dropdown-trigger button{border:0!important;border-radius:0!important;background:transparent!important;box-shadow:none!important}

        @media (max-width:640px){.pagination,.graduates-pagination,.events-pagination,.media-pagination{gap:5px}.pagination a,.pagination span,.graduates-pagination a,.graduates-pagination span,.events-pagination a,.events-pagination span,.media-pagination a,.media-pagination span{min-width:34px;height:34px;padding:0 8px}}
    </style>
</head>

<body>
    <div class="site-shell">
        @include('layouts.navigation')

        @isset($header)
        <header class="page-header">
            <div class="page-header-inner">
                {{ $header }}
            </div>
        </header>
        @endisset

        <main class="page-content">
            {{ $slot }}
        </main>
    </div>
</body>

</html>
