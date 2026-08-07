@extends('adminlte::page')
@include('partials.sidebar-superadmin')

@section('title', 'Analisis Pendapatan')
@section('plugins.Daterangepicker', true)
@section('plugins.Chartjs', true)

@php
    // 1. DUMMY DATA GRAFIK (5 Kategori)
    $chartLabels = [];
    $dataRegular = []; $dataPrivate = []; $dataCowork = []; $dataInternal = []; $dataBebas = [];
    
    for ($i = 0; $i < 30; $i++) {
        $chartLabels[] = \Carbon\Carbon::create(2026, 6, $i + 1)->translatedFormat('d M');
        $dataRegular[] = rand(3000000, 6000000);
        $dataPrivate[] = rand(4000000, 8000000);
        $dataCowork[] = rand(1000000, 3000000);
        $dataInternal[] = rand(500000, 1500000);
        $dataBebas[] = rand(2000000, 5000000);
    }

    // Label & warna kategori ditarik dari config/category-colors.php,
    // supaya sinkron dengan sumber yang sama dipakai di halaman lain.
    $kategoriRuangan = config('category-colors.kategori_ruangan');

    $chartDatasets = [
        ['label' => $kategoriRuangan['regular']['label'], 'data' => $dataRegular, 'color' => $kategoriRuangan['regular']['color']],
        ['label' => $kategoriRuangan['private_room']['label'], 'data' => $dataPrivate, 'color' => $kategoriRuangan['private_room']['color']],
        ['label' => $kategoriRuangan['coworking']['label'], 'data' => $dataCowork, 'color' => $kategoriRuangan['coworking']['color']],
        ['label' => $kategoriRuangan['internal_event']['label'], 'data' => $dataInternal, 'color' => $kategoriRuangan['internal_event']['color']],
        ['label' => $kategoriRuangan['pas_bebas']['label'], 'data' => $dataBebas, 'color' => $kategoriRuangan['pas_bebas']['color']],
    ];

    // 2. DUMMY DATA TABEL
    // 'color' di sini nama warna saja (tanpa prefix "bg-"), dipakai oleh <x-progress-bar>.
    $tableData = [
        ['kategori' => 'Regular', 'produk' => 'Layanan', 'trx' => 245, 'total' => 2850000, 'persen' => 22.8, 'rata' => 11632, 'color' => 'purple'],
        ['kategori' => 'Private Room', 'produk' => 'Layanan', 'trx' => 128, 'total' => 3100000, 'persen' => 24.9, 'rata' => 24218, 'color' => 'success'],
        ['kategori' => 'Cowork', 'produk' => 'Layanan', 'trx' => 84, 'total' => 2450000, 'persen' => 19.6, 'rata' => 29166, 'color' => 'warning'],
    ];
@endphp

@section('content_header')
<div class="container-fluid py-2">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <h1 class="font-weight-bold text-dark m-0" style="font-size: 28px; letter-spacing: -0.5px;">Analisis Pendapatan</h1>
            <p class="text-muted mb-0" style="font-size: 14px;">Analisis performa pendapatan berdasarkan kategori bisnis Play N Chill.</p>
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
                <x-filter-select name="periode" label="Periode" :options="['bulanan' => 'Bulanan', 'mingguan' => 'Mingguan']" width="col-md-2 col-sm-6"/>
                <x-filter-date-range name="rentang_tanggal" placeholder="01 Jun 2026 - 30 Jun 2026" width="col-md-3 col-sm-6"/>
                <x-filter-select name="kategori" label="Kategori" :options="['semua' => 'Semua']" width="col-md-3 col-sm-6"/>
            </x-filter-card>
        </div>
    </div>

    {{-- BARIS 2: GRAFIK & LEGEND KUSTOM --}}
    <div class="row mb-2">
        <div class="col-12">
            <x-card title="Grafik Penjualan Kategori">
                
                {{-- Canvas Chart --}}
                <div class="chart-container" style="position: relative; height:400px; width:100%;">
                    <x-chart id="revenueAnalysisChart" :labels="$chartLabels" :datasets="$chartDatasets" height="400px" />
                </div>

                {{-- Penampung Box Legend di bawah grafik --}}
                <div id="custom-legend-boxes" class="d-flex flex-wrap gap-3 mt-4"></div>
            </x-card>
        </div>
    </div>

    {{-- BARIS 3: TABEL DATA --}}
    <div class="row">
        <div class="col-12">
            {{-- Panggil Komponen Tabel Fleksibel --}}
            <x-table>
                {{-- Slot Header (Isi Kolom) --}}
                <x-slot name="head">
                    <th class="py-3 px-4 border-0">No.</th>
                    <th class="py-3 border-0">Kategori</th>
                    <th class="py-3 border-0">Produk</th>
                    <th class="py-3 border-0 text-center">Jml Transaksi</th>
                    <th class="py-3 border-0 text-right">Total Pendapatan</th>
                    <th class="py-3 border-0 text-center">Kontribusi %</th>
                    <th class="py-3 border-0 text-right">Rata-rata/Trx</th>
                </x-slot>

                {{-- Slot Body (Isi Data Baris) --}}
                @foreach($tableData as $index => $row)
                    <tr>
                        <td class="px-4 text-muted">{{ $index + 1 }}</td>
                        <td class="font-weight-bold text-purple">{{ $row['kategori'] }}</td>
                        <td class="text-muted">{{ $row['produk'] }}</td>
                        <td class="text-center">{{ $row['trx'] }}</td>
                        <td class="text-right font-weight-bold">Rp {{ number_format($row['total'], 0, ',', '.') }}</td>
                        <td class="text-center" style="width: 150px;">
                            <x-progress-bar :percent="$row['persen']" :color="$row['color']" height="6px" />
                            <span class="font-weight-bold" style="font-size: 12px;">{{ $row['persen'] }}%</span>
                        </td>
                        <td class="text-right text-muted">Rp {{ number_format($row['rata'], 0, ',', '.') }}</td>
                    </tr>
                @endforeach

                <x-slot name="footer">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <span class="text-muted" style="font-size: 13px;">Menampilkan 1 hingga {{ count($tableData) }} dari {{ count($tableData) }} entri</span>
                        <div class="btn-group">
                            <button class="btn btn-sm btn-light border text-muted">Sebelumnya</button>
                            <button class="btn btn-sm btn-primary" style="background-color: #6f42c1; border-color: #6f42c1;">1</button>
                            <button class="btn btn-sm btn-light border text-muted">Selanjutnya</button>
                        </div>
                    </div>
                </x-slot>
            </x-table>
        </div>
    </div>

</div>
@stop