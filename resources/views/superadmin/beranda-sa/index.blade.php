@extends('adminlte::page')
@include('partials.sidebar-superadmin')

@section('title', 'Dashboard Superadmin')
@section('plugins.Daterangepicker', true)
@section('plugins.Chartjs', true)

@php
    // 1. DUMMY DATA METRIK & ANALISIS (Nanti diganti variabel dari Controller)
    $metrics = [
        ['title' => 'Total Pendapatan', 'value' => 'Rp 19.250.000', 'unit' => null, 'icon' => 'fa-wallet', 'color' => 'primary'],
        ['title' => 'Total Booking', 'value' => '142', 'unit' => 'Sesi', 'icon' => 'fa-calendar-check', 'color' => 'warning'],
        ['title' => 'Transaksi F&B', 'value' => '298', 'unit' => 'Struk', 'icon' => 'fa-utensils', 'color' => 'success'],
        ['title' => 'Rata-rata Transaksi', 'value' => 'Rp 43.800', 'unit' => null, 'icon' => 'fa-calculator', 'color' => 'danger'],
    ];

    // Label & warna kategori ditarik dari config/category-colors.php.
    // Nilai/persentase di bawah ini masih dummy, menunggu data asli dari Controller.
    $analisisPendapatan = [
        ['label' => config('category-colors.analisis_pendapatan.booking.label'), 'value' => 'Rp 12.450.000 (64%)', 'percent' => 64, 'color' => config('category-colors.analisis_pendapatan.booking.color')],
        ['label' => config('category-colors.analisis_pendapatan.fnb.label'), 'value' => 'Rp 6.800.000 (36%)', 'percent' => 36, 'color' => config('category-colors.analisis_pendapatan.fnb.color')],
    ];

    $produkLayanan = [
        ['label' => config('category-colors.produk_layanan.vip.label'), 'value' => '60%', 'percent' => 60, 'color' => config('category-colors.produk_layanan.vip.color')],
        ['label' => config('category-colors.produk_layanan.regular.label'), 'value' => '40%', 'percent' => 40, 'color' => config('category-colors.produk_layanan.regular.color')],
    ];

    $produkFnb = [
        ['label' => config('category-colors.produk_fnb.best_seller.label'), 'value' => '56%', 'percent' => 56, 'color' => config('category-colors.produk_fnb.best_seller.color')],
        ['label' => config('category-colors.produk_fnb.runner_up.label'), 'value' => '44%', 'percent' => 44, 'color' => config('category-colors.produk_fnb.runner_up.color')],
    ];

    $metodePembayaran = [
        ['label' => config('category-colors.metode_pembayaran.qris.label'), 'value' => '70%', 'percent' => 70, 'color' => config('category-colors.metode_pembayaran.qris.color')],
        ['label' => config('category-colors.metode_pembayaran.cash.label'), 'value' => '30%', 'percent' => 30, 'color' => config('category-colors.metode_pembayaran.cash.color')],
    ];

    // Performa Kasir sengaja TIDAK ditarik dari config: warna di sini menandakan
    // peringkat (kasir teratas vs lainnya), bukan identitas kategori bisnis yang tetap.
    $performaKasir = [
        ['label' => 'Andi Wijaya', 'value' => '(184)', 'percent' => 100, 'color' => 'danger'],
        ['label' => 'Siti Rahma', 'value' => '(114)', 'percent' => 62, 'color' => 'danger-light'],
    ];

    $laporanTransaksi = [
        ['label' => config('category-colors.laporan_transaksi.selesai.label'), 'value' => '256 (86%)', 'percent' => 86, 'color' => config('category-colors.laporan_transaksi.selesai.color')],
        ['label' => config('category-colors.laporan_transaksi.dibatalkan.label'), 'value' => '30 (10%)', 'percent' => 10, 'color' => config('category-colors.laporan_transaksi.dibatalkan.color')],
        ['label' => config('category-colors.laporan_transaksi.refund.label'), 'value' => '12 (4%)', 'percent' => 4, 'color' => config('category-colors.laporan_transaksi.refund.color')],
    ];

    // 2. LOGIKA DATA CHART (Nanti ini yang Anda fetch dari Controller)
    $chartLabels = [];
    $pendapatanData = [];
    $sebelumnyaData = [];
    
    // Generate Dummy Wavy Data
    for ($i = 0; $i < 30; $i++) {
        $chartLabels[] = \Carbon\Carbon::create(2026, 6, $i + 1)->translatedFormat('d M');
        $pendapatanData[] = (int) round(340000 + 210000 * sin($i / 3.1), -3);
        $sebelumnyaData[] = (int) round(430000 + 260000 * sin(($i / 3.6) + 1.6), -3);
    }

    // Strukturkan menjadi array dinamis. Anda bisa nambah 3, 4, atau 5 pembanding di sini!

    $chartDatasets = [
        [
            'label' => 'Periode Sebelumnya',
            'data' => $sebelumnyaData, // Gunakan variabel asli ini
            'color' => '#c9c9d4' 
        ],
        [
            'label' => 'Total Pendapatan',
            'data' => $pendapatanData,  // Gunakan variabel asli ini
            'color' => '#6f42c1',
            'fill' => true 
        ]
    ];
@endphp

@section('content_header')
<div class="container-fluid py-2">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <h1 class="font-weight-bold text-dark m-0" style="font-size: 28px; letter-spacing: -0.5px;">Dashboard</h1>
            <p class="text-muted mb-0" style="font-size: 14px;">Ringkasan performa dan analisis bisnis PlayNChill.</p>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="container-fluid">

    {{-- BARIS 1: FILTER UTAMA PENCARIAN --}}
    <div class="row mb-4">
        <div class="col-12">
            <x-filter-card :action="url()->current()">
                <x-filter-select
                    name="periode"
                    label="Periode"
                    :options="['bulanan' => 'Bulanan', 'mingguan' => 'Mingguan', 'harian' => 'Harian']"
                    width="col-md-3 col-sm-6"
                />
                <x-filter-date-range
                    name="rentang_tanggal"
                    placeholder="01 Jun 2026 - 30 Jun 2026"
                    width="col-md-4 col-sm-6"
                />
            </x-filter-card>
        </div>
    </div>

    {{-- BARIS 2: 4 KOTAK METRIK UTAMA --}}
    <div class="row mb-4">
        @foreach ($metrics as $metric)
            <div class="col-xl-3 col-md-6 mb-3 mb-xl-0">
                <x-metric-card
                    :title="$metric['title']"
                    :value="$metric['value']"
                    :unit="$metric['unit']"
                    :icon="$metric['icon']"
                    :color="$metric['color']"
                />
            </div>
        @endforeach
    </div>

    {{-- BARIS 3: AREA GRAFIK UTAMA DENGAN X-CARD BARU --}}
    <div class="row mb-4">
        <div class="col-12">
            <x-card>
                <x-slot name="title">
                    <i class="fas fa-chart-line text-purple mr-2"></i> Grafik Penjualan Kategori
                </x-slot>
                
                <x-slot name="header">
                    {{-- Legend akan di-generate otomatis oleh JavaScript di sini --}}
                    <div id="chart-legend-container" class="d-flex align-items-center" style="gap: 20px;"></div>
                </x-slot>

                <x-chart id="grafikPenjualan" :labels="$chartLabels" :datasets="$chartDatasets" />
            </x-card>
        </div>
    </div>

    {{-- BARIS 4: 6 CARD ANALITIK --}}
    <div class="row">
        <div class="col-xl-4 col-md-6 mb-4"><x-analysis-card title="Analisis Pendapatan" :items="$analisisPendapatan" /></div>
        <div class="col-xl-4 col-md-6 mb-4"><x-analysis-card title="Produk Layanan / Sewa" :items="$produkLayanan" /></div>
        <div class="col-xl-4 col-md-6 mb-4"><x-analysis-card title="Produk F&B" :items="$produkFnb" /></div>
        <div class="col-xl-4 col-md-6 mb-4"><x-analysis-card title="Metode Pembayaran" :items="$metodePembayaran" /></div>
        <div class="col-xl-4 col-md-6 mb-4"><x-analysis-card title="Performa Kasir" :items="$performaKasir" /></div>
        <div class="col-xl-4 col-md-6 mb-4"><x-analysis-card title="Laporan Transaksi" :items="$laporanTransaksi" /></div>
    </div>

</div>
@stop