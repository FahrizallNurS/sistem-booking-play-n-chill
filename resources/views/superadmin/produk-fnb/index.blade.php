@extends('adminlte::page')
@include('partials.sidebar-superadmin')

@section('title', 'Produk F&B - Play N Chill')
@section('plugins.Daterangepicker', true)
@section('plugins.Chartjs', true)
@section('content_header')

<div class="container-fluid py-2">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <h1 class="font-weight-bold text-dark m-0" style="font-size: 28px; letter-spacing: -0.5px;">Produk F&B</h1>
            <p class="text-muted mb-0" style="font-size: 14px;">Analisis performa pendapatan berdasarkan jenis produk F&B Play N Chill.</p>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="container-fluid pb-4">

    {{-- BARIS 1: FILTER --}}
    <div class="row mb-4">
        <div class="col-12">
            <x-filter-card :action="url()->current()">
                <x-filter-select name="periode" label="PERIODE" :options="['harian' => 'Harian', 'mingguan' => 'Mingguan', 'bulanan' => 'Bulanan']" width="col-md-2 col-sm-6"/>
                <x-filter-date-range name="rentang_tanggal" label="RENTANG TANGGAL" placeholder="01 Jun 2026 - 30 Jun 2026" width="col-md-3 col-sm-6"/>
                <x-filter-select name="kategori" label="KATEGORI" :options="['semua' => 'Semua']" width="col-md-2 col-sm-6"/>
                <x-filter-select name="sub_kategori" label="SUB KATEGORI" :options="['semua' => 'Semua']" width="col-md-2 col-sm-6"/>
            </x-filter-card>
        </div>
    </div>

    {{-- BARIS 2: GRAFIK & LEGEND KUSTOM --}}
    {{-- Legend dot di header, kotak legend interaktif (toggle + "Tambah Pembanding")
         semuanya sudah ditangani oleh <x-chart :interactive="true">, sama seperti di
         halaman Produk Layanan. Halaman ini tidak perlu tulis ulang JS chart apapun. --}}
    <div class="row mb-2">
        <div class="col-12">
            <x-card>
                <x-slot name="title">
                    <span class="font-weight-bold text-purple" style="font-size: 1.1rem;">Grafik Penjualan Produk F&B</span>
                </x-slot>

                <x-slot name="header">
                    <div id="fnbAnalysisChart-header-legend" class="d-none d-md-flex gap-3 align-items-center" style="font-size: 12px;"></div>
                </x-slot>

                <div class="chart-container mt-3" style="position: relative; height:400px; width:100%;">
                    <x-chart id="fnbAnalysisChart" :labels="$chartLabels" :datasets="$chartDatasets" height="400px" :interactive="true" />
                </div>

                <div id="fnbAnalysisChart-legend-boxes" class="d-flex flex-wrap gap-3 mt-4"></div>
            </x-card>
        </div>
    </div>

    {{-- BARIS 3: TABEL DATA PRODUK --}}
    <div class="row mt-4">
        <div class="col-12">
            <x-table>
                <x-slot name="head">
                    <tr style="background-color: #faf5ff;">
                        <th class="py-3 px-4 border-0 text-muted" style="font-size: 11px;">NO</th>
                        <th class="py-3 border-0 text-muted" style="font-size: 11px;">FOTO</th>
                        <th class="py-3 border-0 text-muted" style="font-size: 11px;">NAMA PRODUK</th>
                        <th class="py-3 border-0 text-muted" style="font-size: 11px;">KATEGORI PRODUK</th>
                        <th class="py-3 border-0 text-muted" style="font-size: 11px;">SUB.KATEGORI PRODUK</th>
                        <th class="py-3 border-0 text-muted text-right" style="font-size: 11px;">HARGA BELI</th>
                        <th class="py-3 border-0 text-muted text-right" style="font-size: 11px;">HARGA JUAL</th>
                        <th class="py-3 border-0 text-muted" style="font-size: 11px;">SKU</th>
                        <th class="py-3 border-0 text-muted text-center" style="font-size: 11px;">STOCK</th>
                        <th class="py-3 border-0 text-muted text-center" style="font-size: 11px;">STATUS</th>
                    </tr>
                </x-slot>

                @foreach($tableData as $index => $row)
                    <tr>
                        <td class="px-4 text-muted py-2" style="font-size: 13px;">{{ $index + 1 }}</td>
                        <td class="py-2">
                            @if (!empty($row['foto']))
                                <img src="{{ $row['foto'] }}"
                                     alt="{{ $row['nama'] }}"
                                     class="rounded"
                                     style="width: 44px; height: 44px; object-fit: cover; border: 1px solid #e5e7eb;"
                                     onerror="this.onerror=null; this.src='{{ asset('images/logo_dumb.png') }}';">
                            @else
                                <div class="d-flex align-items-center justify-content-center rounded bg-light text-muted" style="width: 44px; height: 44px; border: 1px solid #e5e7eb;">
                                    <i class="fas fa-image"></i>
                                </div>
                            @endif
                        </td>

                        <td class="text-dark font-weight-bold py-2" style="font-size: 13px;">{{ $row['nama'] }}</td>
                        <td class="text-muted py-2" style="font-size: 13px;">{{ $row['kategori'] }}</td>
                        <td class="text-muted py-2" style="font-size: 13px;">{{ $row['sub_kategori'] }}</td>
                        <td class="text-muted text-right py-2" style="font-size: 13px;">Rp. {{ number_format($row['harga_beli'], 2, ',', '.') }}</td>
                        <td class="text-muted text-right py-2" style="font-size: 13px;">Rp. {{ number_format($row['harga_jual'], 2, ',', '.') }}</td>
                        <td class="text-muted py-2" style="font-size: 13px;">{{ $row['sku'] }}</td>
                        <td class="text-muted text-center py-2" style="font-size: 13px;">{{ $row['stock'] }}</td>
                        <td class="text-center py-2">
                            <x-badge :variant="$statusBadgeVariant[$row['status']] ?? 'secondary'" :label="$row['status']" />
                        </td>
                    </tr>
                @endforeach

                <x-slot name="footer">
                    <span class="text-muted" style="font-size: 13px;">Menampilkan 1 hingga {{ count($tableData) }} dari {{ count($tableData) }} entri</span>
                    <div class="btn-group">
                        <button class="btn btn-sm btn-light border text-muted">Sebelumnya</button>
                        <button class="btn btn-sm btn-primary" style="background-color: #6f42c1; border-color: #6f42c1;">1</button>
                        <button class="btn btn-sm btn-light border text-muted">Selanjutnya</button>
                    </div>
                </x-slot>
            </x-table>
        </div>
    </div>

</div>
@stop

@section('css')
<style>
.text-purple { color: #6f42c1; }

label {
    font-size: 11px !important;
    font-weight: 600 !important;
    color: #4a5568;
    margin-bottom: 4px;
}
</style>
@stop