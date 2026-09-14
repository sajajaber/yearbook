@if ($paginator->hasPages())
    <nav class="yearbook-pagination" role="navigation" aria-label="Pagination Navigation">
        <style>
            .yearbook-pagination{display:flex;align-items:center;justify-content:center;width:100%;margin:0;padding:8px 0;box-sizing:border-box}
            .yearbook-pagination-controls{display:flex;align-items:center;justify-content:center;gap:5px;flex-wrap:wrap}
            .yearbook-pagination-controls a,.yearbook-pagination-controls span{display:inline-flex;align-items:center;justify-content:center;min-width:36px;height:36px;padding:0 9px;border:1px solid #d8e3ef;border-radius:8px;background:#fff;color:#002a5c;font:700 .72rem/1 "Inter",sans-serif;text-decoration:none;box-sizing:border-box;transition:background .18s ease,border-color .18s ease,color .18s ease}
            .yearbook-pagination-controls a:hover{border-color:#d8e3ef;background:#f8fafc;color:#002a5c}
            .yearbook-pagination-controls a:focus-visible{outline:2px solid #ffb034;outline-offset:2px}
            .yearbook-pagination-controls .is-current{border-color:#002a5c;background:#002a5c;color:#fff}
            .yearbook-pagination-controls .is-disabled{border-color:#edf2f7;background:#f8fafc;color:#b5bfca;cursor:not-allowed}
            .yearbook-pagination-controls .is-ellipsis{min-width:20px;padding:0;border-color:transparent;background:transparent;color:#94a3b8}
            .yearbook-pagination-label{gap:5px;padding:0 11px!important}
            @media(max-width:560px){.yearbook-pagination-controls{gap:4px}.yearbook-pagination-controls a,.yearbook-pagination-controls span{min-width:34px;height:34px;padding:0 7px}.yearbook-pagination-label{min-width:34px!important;padding:0!important}.yearbook-pagination-label span:not([aria-hidden]){display:none}}
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
