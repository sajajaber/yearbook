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
                margin: -3px 0 72px;
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
                box-sizing: border-box;
            }

            .graduation-pagination-view a:hover {
                border-color: var(--grad-ink, #002a5c) !important;
                background: var(--grad-ink, #002a5c) !important;
                color: #fff !important;
            }

            .graduation-pagination-view .graduation-pagination-disabled {
                color: var(--grad-ink, #002a5c) !important;
                opacity: .45;
                cursor: not-allowed;
            }

            @media (max-width: 640px) {
                .graduation-pagination-view {
                    gap: 5px;
                    margin-bottom: 55px;
                }

                .graduation-pagination-view a,
                .graduation-pagination-view span {
                    min-width: 34px !important;
                    height: 34px !important;
                    padding: 0 8px !important;
                }
            }
        </style>

        @if ($paginator->onFirstPage())
            <span class="graduation-pagination-disabled" aria-disabled="true" aria-label="Previous">
                <span aria-hidden="true">‹</span>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Previous">
                <span aria-hidden="true">‹</span>
            </a>
        @endif

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Next">
                <span aria-hidden="true">›</span>
            </a>
        @else
            <span class="graduation-pagination-disabled" aria-disabled="true" aria-label="Next">
                <span aria-hidden="true">›</span>
            </span>
        @endif
    </nav>
@endif
