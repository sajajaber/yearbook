@if ($paginator->hasPages())
    <nav class="yearbook-pagination" role="navigation" aria-label="Pagination Navigation">
        <style>
            .yearbook-pagination {
                display: flex !important;
                align-items: center !important;
                justify-content: space-between !important;
                gap: 22px !important;
                width: 100% !important;
                margin: 42px 0 72px !important;
                padding: 14px 16px !important;
                border: 1px solid #d8e3ef !important;
                border-radius: 14px !important;
                background: rgba(255,255,255,.92) !important;
                box-sizing: border-box !important;
            }

            .yearbook-pagination-summary {
                flex: 0 0 auto;
                color: #64748b !important;
                font: 700 .7rem/1.4 "Inter", sans-serif !important;
                letter-spacing: .02em;
                white-space: nowrap;
            }

            .yearbook-pagination-summary strong {
                color: #002a5c !important;
                font-weight: 900 !important;
            }

            .yearbook-pagination-controls {
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                gap: 6px !important;
                flex-wrap: wrap !important;
            }

            .yearbook-pagination-controls a,
            .yearbook-pagination-controls span {
                display: inline-flex !important;
                align-items: center !important;
                justify-content: center !important;
                min-width: 38px !important;
                height: 38px !important;
                padding: 0 10px !important;
                border: 1px solid #d8e3ef !important;
                border-radius: 9px !important;
                background: #fff !important;
                color: #002a5c !important;
                font: 800 .7rem/1 "Inter", sans-serif !important;
                text-decoration: none !important;
                box-sizing: border-box !important;
                transition: transform .18s ease, background .18s ease, color .18s ease, border-color .18s ease, box-shadow .18s ease !important;
            }

            .yearbook-pagination-controls a:hover {
                border-color: #002a5c !important;
                background: #002a5c !important;
                color: #fff !important;
                transform: translateY(-1px);
                box-shadow: 0 5px 14px rgba(0,42,92,.12);
            }

            .yearbook-pagination-controls a:focus-visible {
                outline: 3px solid rgba(255,176,52,.35) !important;
                outline-offset: 2px !important;
            }

            .yearbook-pagination-controls .is-current {
                border-color: #002a5c !important;
                background: #002a5c !important;
                color: #fff !important;
                cursor: default !important;
            }

            .yearbook-pagination-controls .is-disabled {
                border-color: #edf2f7 !important;
                background: #f8fafc !important;
                color: #a7b3c0 !important;
                cursor: not-allowed !important;
            }

            .yearbook-pagination-controls .is-ellipsis {
                min-width: 24px !important;
                padding: 0 3px !important;
                border-color: transparent !important;
                background: transparent !important;
                color: #94a3b8 !important;
                cursor: default !important;
            }

            .yearbook-pagination-label {
                display: inline-flex !important;
                align-items: center !important;
                gap: 6px !important;
                padding: 0 8px !important;
                font-size: .65rem !important;
                letter-spacing: .02em !important;
            }

            @media (max-width: 720px) {
                .yearbook-pagination {
                    flex-direction: column !important;
                    align-items: stretch !important;
                    gap: 12px !important;
                    margin: 34px 0 55px !important;
                    padding: 13px !important;
                }

                .yearbook-pagination-summary {
                    text-align: center;
                }

                .yearbook-pagination-controls {
                    width: 100% !important;
                }

                .yearbook-pagination-controls a,
                .yearbook-pagination-controls span {
                    min-width: 36px !important;
                    height: 36px !important;
                }
            }
        </style>

        <div class="yearbook-pagination-summary">
            Showing <strong>{{ $paginator->firstItem() }}</strong>–<strong>{{ $paginator->lastItem() }}</strong>
            of <strong>{{ $paginator->total() }}</strong>
            <span aria-hidden="true">·</span>
            Page <strong>{{ $paginator->currentPage() }}</strong> of <strong>{{ $paginator->lastPage() }}</strong>
        </div>

        <div class="yearbook-pagination-controls">
            @if ($paginator->onFirstPage())
                <span class="is-disabled yearbook-pagination-label" aria-disabled="true" aria-label="Previous page">
                    <span aria-hidden="true">‹</span>
                    <span>Previous</span>
                </span>
            @else
                <a class="yearbook-pagination-label" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Previous page">
                    <span aria-hidden="true">‹</span>
                    <span>Previous</span>
                </a>
            @endif

            @php
                $start = max(1, $paginator->currentPage() - 2);
                $end = min($paginator->lastPage(), $paginator->currentPage() + 2);
            @endphp

            @if ($start > 1)
                <a href="{{ $paginator->url(1) }}" aria-label="Go to first page">1</a>
                @if ($start > 2)
                    <span class="is-ellipsis" aria-hidden="true">…</span>
                @endif
            @endif

            @for ($page = $start; $page <= $end; $page++)
                @if ($page == $paginator->currentPage())
                    <span class="is-current" aria-current="page">{{ $page }}</span>
                @else
                    <a href="{{ $paginator->url($page) }}" aria-label="Go to page {{ $page }}">{{ $page }}</a>
                @endif
            @endfor

            @if ($end < $paginator->lastPage())
                @if ($end < $paginator->lastPage() - 1)
                    <span class="is-ellipsis" aria-hidden="true">…</span>
                @endif
                <a href="{{ $paginator->url($paginator->lastPage()) }}" aria-label="Go to last page">{{ $paginator->lastPage() }}</a>
            @endif

            @if ($paginator->hasMorePages())
                <a class="yearbook-pagination-label" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Next page">
                    <span>Next</span>
                    <span aria-hidden="true">›</span>
                </a>
            @else
                <span class="is-disabled yearbook-pagination-label" aria-disabled="true" aria-label="Next page">
                    <span>Next</span>
                    <span aria-hidden="true">›</span>
                </span>
            @endif
        </div>
    </nav>
@endif
