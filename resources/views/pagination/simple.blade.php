@if ($paginator->hasPages())
    <div class="pager">
        <span class="muted">Halaman {{ $paginator->currentPage() }} dari {{ $paginator->lastPage() }}</span>
        <span>
            @if ($paginator->onFirstPage())
                <span class="btn ghost small" style="opacity:.4">Sebelumnya</span>
            @else
                <a class="btn ghost small" href="{{ $paginator->previousPageUrl() }}">Sebelumnya</a>
            @endif
            @if ($paginator->hasMorePages())
                <a class="btn ghost small" href="{{ $paginator->nextPageUrl() }}">Berikutnya</a>
            @else
                <span class="btn ghost small" style="opacity:.4">Berikutnya</span>
            @endif
        </span>
    </div>
@endif
