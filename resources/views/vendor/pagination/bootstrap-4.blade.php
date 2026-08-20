@if ($paginator->hasPages())
    <div class="btn-group btn-group-pagination" role="group">
        {{-- Tombol Sebelumnya --}}
        @if ($paginator->onFirstPage())
            <span class="btn btn-sm btn-light border text-muted disabled">Sebelumnya</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="btn btn-sm btn-light border text-muted" rel="prev">Sebelumnya</a>
        @endif

        {{-- Angka Halaman --}}
        @for ($p = 1; $p <= $paginator->lastPage(); $p++)
            @if ($p == $paginator->currentPage())
                <span class="btn btn-sm text-white" style="background-color: #6f42c1; border-color: #6f42c1;">{{ $p }}</span>
            @else
                <a href="{{ $paginator->url($p) }}" class="btn btn-sm btn-light border text-muted">{{ $p }}</a>
            @endif
        @endfor

        {{-- Tombol Selanjutnya --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="btn btn-sm btn-light border text-muted" rel="next">Selanjutnya</a>
        @else
            <span class="btn btn-sm btn-light border text-muted disabled">Selanjutnya</span>
        @endif
    </div>
@endif