@if ($paginator->hasPages())
    @php
        $current = $paginator->currentPage();
        $last = $paginator->lastPage();
        $start = max(1, $current - 2);
        $end = min($last, $current + 2);
    @endphp
    <nav>
        <ul class="pagination justify-content-center m-0">
            @if ($paginator->onFirstPage())
                <li class="page-item disabled"><span class="page-link">Prev</span></li>
            @else
                <li class="page-item"><a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">Prev</a></li>
            @endif

            @if ($start > 1)
                <li class="page-item"><a class="page-link" href="{{ $paginator->url(1) }}">1</a></li>
                @if ($start > 2)
                    <li class="page-item disabled"><span class="page-link">…</span></li>
                @endif
            @endif

            @for ($page = $start; $page <= $end; $page++)
                @if ($page == $current)
                    <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                @else
                    <li class="page-item"><a class="page-link" href="{{ $paginator->url($page) }}">{{ $page }}</a></li>
                @endif
            @endfor

            @if ($end < $last)
                @if ($end < $last - 1)
                    <li class="page-item disabled"><span class="page-link">…</span></li>
                @endif
                <li class="page-item"><a class="page-link" href="{{ $paginator->url($last) }}">{{ $last }}</a></li>
            @endif

            @if ($paginator->hasMorePages())
                <li class="page-item"><a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">Next</a></li>
            @else
                <li class="page-item disabled"><span class="page-link">Next</span></li>
            @endif
        </ul>
    </nav>

    <style>
    .pagination .page-link{border-radius:8px;border-color:#e5e7eb;padding:.4rem .75rem;color:#374151}
    .pagination .page-item.active .page-link{background:#0d6efd;border-color:#0d6efd;color:#fff}
    .pagination .page-item.disabled .page-link{color:#9ca3af;background:#f3f4f6;border-color:#e5e7eb}
    .pagination .page-link:hover{background:#f3f4f6;color:#111827}
    </style>
@endif


