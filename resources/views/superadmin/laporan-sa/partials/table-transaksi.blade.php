@php
    $jenis = request('jenis_transaksi', 'semua');
@endphp

<div class="card">
    <div class="card-header d-flex align-items-center">
        <h3 class="card-title font-weight-bold mb-0">
            @if($jenis === 'booking')
                Tabel Booking
            @elseif($jenis === 'fnb')
                Tabel F&amp;B
            @else
                Daftar Transaksi
            @endif
        </h3>
        <div class="card-tools ml-auto">
           <a href="{{ route('superadmin.laporan.export-excel', request()->all()) }}" class="btn btn-default btn-sm mr-1" target="_blank">
                <i class="fas fa-file-excel text-success"></i> EXCEL
            </a>
            <a href="{{ route('superadmin.laporan.export-pdf', request()->all()) }}" class="btn btn-default btn-sm" target="_blank">
                <i class="fas fa-file-pdf"></i> PDF
            </a>
        </div>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 text-center align-middle" style="font-size: 14px;">
                <thead style="background-color: #f4f0fb; color: #495057;">
                    <tr>
                        <th class="border-top-0 border-bottom-0 py-3">NO.</th>

                        @if($jenis === 'booking')
                            <th class="border-top-0 border-bottom-0 py-3">KODE BOOKING</th>
                        @elseif($jenis === 'fnb')
                            <th class="border-top-0 border-bottom-0 py-3">KODE POS</th>
                        @else
                            <th class="border-top-0 border-bottom-0 py-3">JENIS TRANSAKSI</th>
                        @endif

                        <th class="border-top-0 border-bottom-0 py-3">PELANGGAN</th>
                        <th class="border-top-0 border-bottom-0 py-3">KASIR</th>
                        <th class="border-top-0 border-bottom-0 py-3">METODE</th>
                        <th class="border-top-0 border-bottom-0 py-3">TOTAL</th>
                        <th class="border-top-0 border-bottom-0 py-3">SUMBER</th>
                        <th class="border-top-0 border-bottom-0 py-3">STATUS TRANSAKSI</th>
                        <th class="border-top-0 border-bottom-0 py-3">AKSI</th>
                    </tr>
                </thead>
                <tbody id="table-body" style="min-height: 530px;">
                    @include('superadmin.laporan-sa.partials.table-rows', ['transaksis' => $transaksisPaged])
                </tbody>
            </table>
        </div>
    </div>

    <div class="card-footer d-flex align-items-center bg-white" style="width: 100%;">
        <span id="pagination-info" class="text-muted" style="font-size: 13px;">
            Menampilkan {{ $transaksisPaged->firstItem() ?? 0 }} hingga {{ $transaksisPaged->lastItem() ?? 0 }} dari {{ $transaksisPaged->total() }} entri
        </span>
        <div id="pagination-links" style="margin-left: auto;">
            @include('superadmin.laporan-sa.partials.pagination-links', ['transaksisPaged' => $transaksisPaged])
        </div>
    </div>
</div>

@once
    <style>
    /* Paksa <ul class="pagination"> nempel ke kanan card-footer, bukan ngambang ke tengah */
    .pagination-nav-wrap .pagination {
        justify-content: flex-end;
        margin-bottom: 0;
    }
    </style>
@endonce