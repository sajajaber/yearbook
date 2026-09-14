@if ($paginator->hasPages())
    <nav class="graduation-pagination-view" role="navigation" aria-label="Pagination Navigation">
        <style>
            .graduation-pagination-view {
                display: flex;
                align-items: center;
                justify-content: center;
                flex-wrap: wrap;
                gap: 7px;
                width: 100%;
            }

            .graduation-pagination-view a,
            .graduation-pagination-view span {
                display: inline-flex !important;
                align-items: center !important;
                justify-content: center !important;
                min-width: 39px !important;
                height: 39px !important;
                padding: 0 10px !important;
                border: 1px solid var(--grad-line, #d8e3ef) !important;
                border-radius: 9px !important;
                background: #fff !important;
                color: var(--grad-ink, #002a5c) !important;
                font: 800 .68rem/1 "Inter", sans-serif !important;
                text-decoration: none !important;
                transition: background .18s ease, color .18s ease, border-color .18s ease, transform .18s ease !important;
                box-sizing: border-box;
            }

            .graduation-pagination-view a:hover {
                border-color: var(--grad-ink, #002a5c) !important;
                background: var(--grad-ink, #002a5c) !important;
                color: #fff !important;
                transform: translateY(-1px);
            }

            .graduation-pagination-view .graduation-pagination-current {
                border-color: var(--grad-ink, #002a5c) !important;
                background: var(--grad-ink, #002a5c) !important;
                color: #fff !important;
            }

            .graduation-pagination-view .graduation-pagination-disabled {
                color: #a7b3c0 !important;
                background: #f8fafc !important;
                cursor: not-allowed;
            }

            .graduation-pagination-view .graduation-pagination-ellipsis {
                min-width: 30px !important;
                padding: 0 5px !important;
                border-color: transparent !important;
                background: transparent !important;
                color: #8995a2 !important;
            }

            @media (max-width: 640px) {
                .graduation-pagination-view {
                    gap: 5px;
                }

                .graduation-pagination-view a,
                .graduation-pagination-view span {
                    min-width: 34px !important;
                    height: 34px !important;
                    padding: 0 8px !important;
                }
            }
        </style>

        <div class="graduation-pagination-links">
            @if ($paginator->onFirstPage())
                <span class="graduation-pagination-disabled" aria-disabled="true" aria-label="Previous">
                    <span aria-hidden="true">‹</span>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Previous">
                    <span aria-hidden="true">‹</span>
                </a>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="graduation-pagination-ellipsis" aria-hidden="true">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="graduation-pagination-current" aria-current="page">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" aria-label="Go to page {{ $page }}">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Next">
                    <span aria-hidden="true">›</span>
                </a>
            @else
                <span class="graduation-pagination-disabled" aria-disabled="true" aria-label="Next">
                    <span aria-hidden="true">›</span>
                </span>
            @endif
        </div>
    </nav>
@endif
