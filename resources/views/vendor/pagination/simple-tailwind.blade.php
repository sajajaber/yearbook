@if ($paginator->hasPages())
    <nav class="yearbook-pagination" role="navigation" aria-label="Pagination Navigation">
        <style>
            .yearbook-pagination{display:flex;justify-content:center;width:100%;margin:0;padding:24px 0 8px;box-sizing:border-box;border-top:1px solid #d8e3ef}
            .yearbook-pagination-controls{display:flex;align-items:center;justify-content:center;gap:22px;flex-wrap:wrap}
            .yearbook-pagination-controls a,.yearbook-pagination-controls span{display:inline-flex;align-items:center;justify-content:center;height:34px;min-width:26px;padding:0 5px;border:0;background:transparent;color:#64748b;font:600 .74rem/1 "Inter",sans-serif;letter-spacing:.02em;text-decoration:none;box-sizing:border-box;transition:color .2s ease,background-color .2s ease,transform .2s ease}
            .yearbook-pagination-controls a:hover{color:#a56a00;background:rgba(255,176,52,.09);border-radius:5px;transform:translateY(-1px)}
            .yearbook-pagination-controls a:focus-visible{outline:2px solid #ffb034;outline-offset:4px;border-radius:4px}
            .yearbook-pagination-controls .is-current{position:relative;min-width:34px;height:34px;padding:0 9px;background:#002a5c;color:#fff;border-radius:5px;font-weight:700;box-shadow:inset 3px 0 0 #ffb034}
            .yearbook-pagination-controls .is-disabled{color:#cbd5e1;cursor:not-allowed}
            .yearbook-pagination-controls .is-ellipsis{min-width:12px;padding:0;color:#94a3b8;background:transparent!important;transform:none!important}
            .yearbook-pagination-label{gap:7px!important;min-width:auto!important;padding:0 2px!important;color:#002a5c!important;font-size:.68rem!important;font-weight:700!important;letter-spacing:.08em!important;text-transform:uppercase}
            .yearbook-pagination-label span[aria-hidden="true"]{font-size:1.15rem;line-height:1;font-weight:400;letter-spacing:0}
            @media(max-width:560px){.yearbook-pagination{padding:18px 0 6px}.yearbook-pagination-controls{gap:10px}.yearbook-pagination-controls a,.yearbook-pagination-controls span{height:32px;min-width:24px}.yearbook-pagination-controls .is-current{min-width:32px;height:32px}.yearbook-pagination-label span:not([aria-hidden]){display:none}.yearbook-pagination-label{padding:0 5px!important;font-size:0!important}.yearbook-pagination-label span[aria-hidden="true"]{font-size:1.2rem}}
        </style>
        <div class="yearbook-pagination-controls">
            @if ($paginator->onFirstPage())
                <span class="is-disabled yearbook-pagination-label" aria-disabled="true" aria-label="Previous page"><span aria-hidden="true">‹</span><span>Previous</span></span>
            @else
                <a class="yearbook-pagination-label" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Previous page"><span aria-hidden="true">‹</span><span>Previous</span></a>
            @endif
            @php $start=max(1,$paginator->currentPage()-2); $end=min($paginator->lastPage(),$paginator->currentPage()+2); @endphp
            @if ($start > 1)
                <a href="{{ $paginator->url(1) }}" aria-label="Go to first page">1</a>
                @if ($start > 2)<span class="is-ellipsis" aria-hidden="true">…</span>@endif
            @endif
            @for ($page=$start; $page <= $end; $page++)
                @if ($page == $paginator->currentPage())
                    <span class="is-current" aria-current="page">{{ $page }}</span>
                @else
                    <a href="{{ $paginator->url($page) }}" aria-label="Go to page {{ $page }}">{{ $page }}</a>
                @endif
            @endfor
            @if ($end < $paginator->lastPage())
                @if ($end < $paginator->lastPage()-1)<span class="is-ellipsis" aria-hidden="true">…</span>@endif
                <a href="{{ $paginator->url($paginator->lastPage()) }}" aria-label="Go to last page">{{ $paginator->lastPage() }}</a>
            @endif
            @if ($paginator->hasMorePages())
                <a class="yearbook-pagination-label" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Next page"><span>Next</span><span aria-hidden="true">›</span></a>
            @else
                <span class="is-disabled yearbook-pagination-label" aria-disabled="true" aria-label="Next page"><span>Next</span><span aria-hidden="true">›</span></span>
            @endif
        </div>
    </nav>
@endif
