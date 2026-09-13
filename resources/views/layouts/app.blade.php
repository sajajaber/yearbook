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
        /* ========================================
           ADMIN LAYOUT / RESPONSIVE SYSTEM
           ======================================== */
        .site-shell,
        .page-content,
        .page-header,
        .page-header-inner {
            min-width: 0;
        }

        .page-content {
            overflow-x: hidden;
        }

        .page-header-inner {
            width: 100%;
            max-width: 1280px;
            margin: 0 auto;
        }

        .admin-table-wrap,
        .table-responsive,
        .responsive-table,
        .overflow-x-auto {
            width: 100%;
            max-width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .admin-table-wrap > table,
        .table-responsive > table,
        .responsive-table > table {
            min-width: 680px;
        }

        img,
        video,
        iframe {
            max-width: 100%;
        }

        input,
        select,
        textarea,
        button {
            max-width: 100%;
        }

        textarea {
            resize: vertical;
        }

        .admin-form-grid,
        .form-grid,
        .settings-grid,
        .media-grid,
        .card-grid {
            min-width: 0;
        }

        /* Pagination */
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
            flex-wrap: wrap;
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

        /* Account dropdown */
        .site-dropdown-menu {
            min-width: 190px;
            overflow: hidden;
            border: 1px solid var(--line);
            background: #fff;
            box-shadow: 0 14px 35px rgba(0,42,92,.12);
            border-radius: 0 !important;
        }

        .site-dropdown-content {
            padding: 6px 0;
            background: #fff;
            border: 0 !important;
            border-radius: 0 !important;
        }

        .site-dropdown-link {
            display: block;
            width: 100%;
            padding: 11px 16px;
            color: var(--ink) !important;
            background: #fff;
            font: 600 11px/1.4 "Inter", sans-serif !important;
            letter-spacing: .8px;
            text-transform: uppercase;
            text-decoration: none;
            transition: background .18s ease,color .18s ease;
        }

        .site-dropdown-link:hover,
        .site-dropdown-link:focus {
            background: #f7fbff !important;
            color: var(--ink) !important;
            outline: none;
        }

        .site-dropdown-link:active {
            background: #eef5fb !important;
        }

        .site-dropdown-content form {
            margin: 0;
        }

        .site-dropdown-trigger button {
            border: 0 !important;
            border-radius: 0 !important;
            background: transparent !important;
            box-shadow: none !important;
        }

        /* Prevent common admin headers from overflowing */
        .dashboard-heading,
        .section-heading,
        .panel-header,
        .graduate-toolbar,
        .filter-ribbon-heading,
        .event-toolbar-tools,
        .graduate-toolbar-tools,
        .edition-card-top,
        .edition-card-meta,
        .edition-actions {
            min-width: 0;
        }

        /* ========================================
           TABLET
           ======================================== */
        @media (max-width: 1100px) {
            .nav-inner,
            .dashboard-heading,
            .dashboard-wrap,
            .page-header-inner {
                max-width: 100%;
            }

            .dashboard-heading,
            .dashboard-wrap {
                padding-left: 24px;
                padding-right: 24px;
            }

            .stat-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .content-grid {
                grid-template-columns: 1fr;
            }

            .graduate-grid,
            .edition-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .school-grid {
                grid-template-columns: 1fr;
            }
        }

        /* ========================================
           MOBILE ADMIN
           ======================================== */
        @media (max-width: 639px) {
            .nav-inner {
                min-height: 58px;
                padding: 0 16px;
            }

            .brand-lockup {
                gap: 9px;
            }

            .brand-mark {
                font-size: 18px;
            }

            .brand-copy {
                padding-left: 9px;
                font-size: 8px;
                letter-spacing: 1.1px;
            }

            .mobile-menu {
                padding: 8px 16px 18px;
            }

            .mobile-menu a {
                padding: 12px 0;
            }

            .dashboard-heading {
                padding: 28px 16px 20px;
                align-items: flex-start;
                flex-direction: column;
                gap: 16px;
            }

            .dashboard-heading > * {
                max-width: 100%;
            }

            .dashboard-heading .button,
            .dashboard-heading a.button,
            .dashboard-heading button {
                width: 100%;
                justify-content: center;
            }

            .dashboard-wrap {
                padding: 0 16px 40px;
            }

            .welcome-banner,
            .graduation-intro,
            .graduate-intro {
                min-height: 0;
                padding: 28px 22px;
                flex-direction: column;
                align-items: flex-start;
                gap: 24px;
            }

            .banner-mark,
            .edition-count,
            .graduate-intro-stats {
                align-self: flex-start;
            }

            .banner-mark {
                font-size: 38px;
            }

            .edition-count strong {
                font-size: 44px;
            }

            .graduate-intro-stats {
                gap: 20px;
                flex-wrap: wrap;
            }

            .graduate-intro-stats strong {
                font-size: 30px;
            }

            .stat-grid,
            .content-grid,
            .graduate-grid,
            .edition-grid,
            .school-grid {
                grid-template-columns: 1fr;
            }

            .stat-grid {
                border-left: 0;
                margin: 16px 0 28px;
            }

            .stat-card {
                border-left: 1px solid var(--line);
            }

            .panel {
                padding: 20px;
            }

            .panel-header,
            .section-heading,
            .filter-ribbon-heading,
            .graduate-toolbar {
                flex-direction: column;
                align-items: stretch;
                gap: 12px;
            }

            .graduate-toolbar,
            .filter-ribbon {
                padding: 14px;
            }

            .graduate-toolbar-tools,
            .event-toolbar-tools,
            .ribbon-fields,
            .graduate-filters {
                width: 100%;
                flex-direction: column;
                align-items: stretch;
            }

            .graduate-toolbar-tools > *,
            .event-toolbar-tools > *,
            .ribbon-fields > *,
            .graduate-filters > * {
                width: 100%;
            }

            .directory-filter,
            .filter-button,
            .clear-filters {
                width: 100%;
                min-height: 40px;
            }

            .view-switcher {
                width: 100%;
            }

            .view-button {
                flex: 1;
            }

            .workflow-row {
                grid-template-columns: 1fr auto;
                gap: 9px;
            }

            .workflow-track {
                grid-column: 1 / -1;
                grid-row: 2;
            }

            .workflow-row strong {
                text-align: right;
            }

            .school-item,
            .graduate-details div {
                align-items: flex-start;
                flex-direction: column;
            }

            .mini-stats {
                gap: 18px;
                flex-wrap: wrap;
            }

            .edition-card,
            .graduate-card {
                padding: 18px;
            }

            .edition-card-top,
            .edition-card-meta,
            .edition-actions,
            .graduate-card-top,
            .graduate-footer {
                flex-wrap: wrap;
            }

            .liu-pagination {
                align-items: stretch;
                flex-direction: column;
                gap: 10px;
            }

            .liu-pagination-controls {
                width: 100%;
                justify-content: center;
            }
        }

        /* Very small phones */
        @media (max-width: 380px) {
            .nav-inner {
                padding: 0 12px;
            }

            .brand-copy {
                display: none;
            }

            .dashboard-heading,
            .dashboard-wrap {
                padding-left: 12px;
                padding-right: 12px;
            }

            .welcome-banner,
            .graduation-intro,
            .graduate-intro {
                padding: 24px 18px;
            }

            .panel {
                padding: 16px;
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
