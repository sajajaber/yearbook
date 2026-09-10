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
        /* Simple LIU editorial pagination */
        .liu-pagination {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin: 34px 0 8px;
            padding: 16px 0 0;
            border-top: 1px solid var(--line);
        }

        .liu-pagination-summary {
            color: var(--ink-soft);
            font: 11px/1.4 "Inter", sans-serif;
        }

        .liu-pagination-summary strong {
            color: var(--ink);
            font-weight: 700;
        }

        .liu-pagination-controls {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .liu-page-number,
        .liu-page-arrow {
            width: 34px;
            height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid transparent;
            background: transparent;
            color: var(--ink-soft);
            font: 600 11px/1 "Inter", sans-serif;
            text-decoration: none;
            transition: .18s ease;
        }

        .liu-page-number:hover,
        .liu-page-arrow:hover {
            border-color: var(--line);
            background: #fff;
            color: var(--ink);
        }

        .liu-page-number.is-current {
            background: var(--ink);
            border-color: var(--ink);
            color: #fff;
        }

        .liu-page-arrow {
            border-color: var(--line);
            background: #fff;
            font-size: 14px;
        }

        .liu-page-arrow.is-disabled {
            color: #b8c2cc;
            background: #f8fafc;
            cursor: default;
        }

        /* Existing account dropdown styling */
        .site-dropdown-menu{min-width:190px;overflow:hidden;border:1px solid var(--line);background:#fff;box-shadow:0 14px 35px rgba(0,42,92,.12);border-radius:0!important}
        .site-dropdown-content{padding:6px 0;background:#fff;border:0!important;border-radius:0!important}
        .site-dropdown-link{display:block;width:100%;padding:11px 16px;color:var(--ink)!important;background:#fff;font:600 11px/1.4 "Inter",sans-serif!important;letter-spacing:.8px;text-transform:uppercase;text-decoration:none;transition:background .18s ease,color .18s ease}
        .site-dropdown-link:hover,.site-dropdown-link:focus{background:#f7fbff!important;color:var(--ink)!important;outline:none}
        .site-dropdown-link:active{background:#eef5fb!important}
        .site-dropdown-content form{margin:0}
        .site-dropdown-trigger button{border:0!important;border-radius:0!important;background:transparent!important;box-shadow:none!important}

        @media (max-width:640px) {
            .liu-pagination {
                align-items: flex-start;
                flex-direction: column;
                gap: 10px;
            }

            .liu-pagination-controls {
                width: 100%;
                justify-content: center;
            }
        }
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
