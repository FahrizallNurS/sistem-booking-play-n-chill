@php
    $current = $paginator->currentPage();
    $last    = $paginator->lastPage();
    $delta   = 2;
    $start   = max(1, $current - $delta);
    $end     = min($last, $current + $delta);
@endphp

<div class="btn-group btn-group-pagination" role="group">
    {{-- Tombol Sebelumnya --}}
    <a href="{{ $paginator->onFirstPage() ? '#' : $paginator->previousPageUrl() }}"
       class="btn btn-sm btn-light border text-muted {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
        Sebelumnya
    </a>

    {{-- Halaman pertama + ellipsis kiri --}}
    @if($start > 1)
        <a href="{{ $paginator->url(1) }}" class="btn btn-sm btn-light border text-muted">1</a>
        @if($start > 2)
            <span class="btn btn-sm btn-light border text-muted disabled">...</span>
        @endif
    @endif

    {{-- Window nomor halaman (current ± {{ $delta }}) --}}
    @for ($p = $start; $p <= $end; $p++)
        <a href="{{ $paginator->url($p) }}"
           class="btn btn-sm {{ $p == $current ? 'btn-primary' : 'btn-light border text-muted' }}"
           @if($p == $current) style="background-color: #6f42c1; border-color: #6f42c1;" @endif>
            {{ $p }}
        </a>
    @endfor

    {{-- Ellipsis kanan + halaman terakhir --}}
    @if($end < $last)
        @if($end < $last - 1)
            <span class="btn btn-sm btn-light border text-muted disabled">...</span>
        @endif
        <a href="{{ $paginator->url($last) }}" class="btn btn-sm btn-light border text-muted">{{ $last }}</a>
    @endif

    {{-- Tombol Selanjutnya --}}
    <a href="{{ $paginator->hasMorePages() ? $paginator->nextPageUrl() : '#' }}"
       class="btn btn-sm btn-light border text-muted {{ $paginator->hasMorePages() ? '' : 'disabled' }}">
        Selanjutnya
    </a>
</div>

@once
    <style>
    /* Paksa tombol pagination nempel rapat tanpa celah, gak ngandelin default .btn-group */
    .btn-group-pagination {
        display: inline-flex;
        gap: 0;
        flex-wrap: wrap;
    }
    .btn-group-pagination .btn {
        border-radius: 0 !important;
        margin-left: -1px !important;
        position: relative;
    }
    .btn-group-pagination .btn:hover,
    .btn-group-pagination .btn:focus {
        z-index: 1;
    }
    .btn-group-pagination .btn:first-child {
        margin-left: 0 !important;
        border-top-left-radius: .2rem !important;
        border-bottom-left-radius: .2rem !important;
    }
    .btn-group-pagination .btn:last-child {
        border-top-right-radius: .2rem !important;
        border-bottom-right-radius: .2rem !important;
    }
    </style>
@endonce