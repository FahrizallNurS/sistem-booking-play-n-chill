<div class="btn-group btn-group-pagination" role="group">
    {{-- Tombol Sebelumnya --}}
    <a href="{{ $transaksisPaged->onFirstPage() ? '#' : $transaksisPaged->previousPageUrl() }}"
       class="btn btn-sm btn-light border text-muted {{ $transaksisPaged->onFirstPage() ? 'disabled' : '' }}">
        Sebelumnya
    </a>

    {{-- Angka Halaman --}}
    @for ($p = 1; $p <= $transaksisPaged->lastPage(); $p++)
        <a href="{{ $transaksisPaged->url($p) }}"
           class="btn btn-sm {{ $p == $transaksisPaged->currentPage() ? 'btn-primary' : 'btn-light border text-muted' }}"
           @if($p == $transaksisPaged->currentPage()) style="background-color: #6f42c1; border-color: #6f42c1;" @endif>
            {{ $p }}
        </a>
    @endfor

    {{-- Tombol Selanjutnya --}}
    <a href="{{ $transaksisPaged->hasMorePages() ? $transaksisPaged->nextPageUrl() : '#' }}"
       class="btn btn-sm btn-light border text-muted {{ $transaksisPaged->hasMorePages() ? '' : 'disabled' }}">
        Selanjutnya
    </a>
</div>

@once
    <style>
    /* Paksa tombol pagination nempel rapat tanpa celah, gak ngandelin default .btn-group */
    .btn-group-pagination {
        display: inline-flex;
        gap: 0;
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