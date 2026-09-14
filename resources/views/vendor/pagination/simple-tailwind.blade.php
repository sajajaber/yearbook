@if ($paginator->hasPages())
    <nav class="yearbook-pagination" role="navigation" aria-label="Pagination Navigation">
        <style>
            .yearbook-pagination{display:flex;justify-content:center;width:100%;margin:0;padding:18px 0;box-sizing:border-box}
            .yearbook-pagination-controls{display:flex;align-items:center;justify-content:center;gap:18px;flex-wrap:wrap}
            .yearbook-pagination-controls a,.yearbook-pagination-controls span{display:inline-flex;align-items:center;justify-content:center;height:32px;min-width:20px;padding:0 3px;border:0;background:transparent;color:#64748b;font:600 .72rem/1 "Inter",sans-serif;text-decoration:none;box-sizing:border-box;transition:color .18s ease,opacity .18s ease}
            .yearbook-pagination-controls a:hover{color:#002a5c}
            .yearbook-pagination-controls a:focus-visible{outline:2px solid #ffb034;outline-offset:5px;border-radius:2px}
            .yearbook-pagination-controls .is-current{color:#002a5c;position:relative}
            .yearbook-pagination-controls .is-current:after{content:"";position:absolute;left:3px;right:3px;bottom:1px;height:2px;background:#ffb034;border-radius:2px}
            .yearbook-pagination-controls .is-disabled{color:#cbd5e1;cursor:not-allowed}
            .yearbook-pagination-controls .is-ellipsis{min-width:12px;padding:0;color:#94a3b8}
            .yearbook-pagination-label{gap:7px!important;color:#002a5c!important;font-weight:700!important}
            .yearbook-pagination-label span[aria-hidden="true"]{font-size:1.05rem;line-height:1;font-weight:400}
            @media(max-width:560px){.yearbook-pagination{padding:14px 0}.yearbook-pagination-controls{gap:11px}.yearbook-pagination-controls a,.yearbook-pagination-controls span{height:30px;min-width:18px}.yearbook-pagination-label span:not([aria-hidden]){display:none}.yearbook-pagination-label{padding:0 4px!important}}
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
