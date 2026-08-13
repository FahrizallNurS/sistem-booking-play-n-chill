@extends('adminlte::page')
@include('partials.sidebar-superadmin')

@section('title', 'Dashboard Superadmin')
@section('plugins.Chartjs', true)

@php
    $metrics = [
        ['id' => 'val-pendapatan', 'title' => 'Total Pendapatan', 'value' => $metricsData['total_pendapatan'], 'unit' => null, 'icon' => 'fa-wallet', 'color' => 'primary'],
        ['id' => 'val-booking', 'title' => 'Total Booking', 'value' => $metricsData['total_booking'], 'unit' => 'Sesi', 'icon' => 'fa-calendar-check', 'color' => 'warning'],
        ['id' => 'val-fnb', 'title' => 'Transaksi F&B', 'value' => $metricsData['total_fnb'], 'unit' => 'Struk', 'icon' => 'fa-utensils', 'color' => 'success'],
        ['id' => 'val-rata-rata', 'title' => 'Rata-rata Transaksi', 'value' => $metricsData['rata_rata'], 'unit' => null, 'icon' => 'fa-calculator', 'color' => 'danger'],
    ];

    // Dummy Cards Analitikal Pendukung
    $analisisPendapatan = [
        ['label' => config('category-colors.analisis_pendapatan.booking.label', 'Booking'), 'value' => 'Rp 12.450.000 (64%)', 'percent' => 64, 'color' => config('category-colors.analisis_pendapatan.booking.color', 'primary')],
        ['label' => config('category-colors.analisis_pendapatan.fnb.label', 'F&B'), 'value' => 'Rp 6.800.000 (36%)', 'percent' => 36, 'color' => config('category-colors.analisis_pendapatan.fnb.color', 'success')],
    ];

    $produkLayanan = [
        ['label' => config('category-colors.produk_layanan.vip.label', 'Private Room'), 'value' => '60%', 'percent' => 60, 'color' => config('category-colors.produk_layanan.vip.color', 'info')],
        ['label' => config('category-colors.produk_layanan.regular.label', 'Regular'), 'value' => '40%', 'percent' => 40, 'color' => config('category-colors.produk_layanan.regular.color', 'secondary')],
    ];

    $produkFnb = [
        ['label' => config('category-colors.produk_fnb.best_seller.label', 'Makanan'), 'value' => '56%', 'percent' => 56, 'color' => config('category-colors.produk_fnb.best_seller.color', 'warning')],
        ['label' => config('category-colors.produk_fnb.runner_up.label', 'Minuman'), 'value' => '44%', 'percent' => 44, 'color' => config('category-colors.produk_fnb.runner_up.color', 'danger')],
    ];

    $metodePembayaran = [
        ['label' => config('category-colors.metode_pembayaran.qris.label', 'QRIS'), 'value' => '70%', 'percent' => 70, 'color' => config('category-colors.metode_pembayaran.qris.color', 'purple')],
        ['label' => config('category-colors.metode_pembayaran.cash.label', 'Cash/Tunai'), 'value' => '30%', 'percent' => 30, 'color' => config('category-colors.metode_pembayaran.cash.color', 'teal')],
    ];

    $performaKasir = [
        ['label' => 'Andi Wijaya', 'value' => '(184)', 'percent' => 100, 'color' => 'danger'],
        ['label' => 'Siti Rahma', 'value' => '(114)', 'percent' => 62, 'color' => 'danger-light'],
    ];

    $laporanTransaksi = [
        ['label' => config('category-colors.laporan_transaksi.selesai.label', 'Selesai'), 'value' => '256 (86%)', 'percent' => 86, 'color' => config('category-colors.laporan_transaksi.selesai.color', 'success')],
        ['label' => config('category-colors.laporan_transaksi.dibatalkan.label', 'Dibatalkan'), 'value' => '30 (10%)', 'percent' => 10, 'color' => config('category-colors.laporan_transaksi.dibatalkan.color', 'danger')],
        ['label' => config('category-colors.laporan_transaksi.refund.label', 'Refund'), 'value' => '12 (4%)', 'percent' => 4, 'color' => config('category-colors.laporan_transaksi.refund.color', 'warning')],
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

    {{-- BARIS 1: FILTER DINAMIS --}}
    <div class="row mb-4">
        <div class="col-12">
            <x-filter-card :action="url()->current()">
                <x-filter-select
                    name="periode"
                    label="Periode"
                    :options="['harian' => 'Harian', 'mingguan' => 'Mingguan', 'bulanan' => 'Bulanan']"
                    width="col-md-3 col-sm-6"
                />
                <x-filter-dynamic-date width="col-md-4 col-sm-6" />
            </x-filter-card>
        </div>
    </div>

    {{-- BARIS 2: 4 KOTAK METRIK UTAMA --}}
    <div class="row mb-4">
        @foreach ($metrics as $metric)
            <div class="col-xl-3 col-md-6 mb-3 mb-xl-0">
                <x-metric-card
                    :id="$metric['id']"
                    :title="$metric['title']"
                    :value="$metric['value']"
                    :unit="$metric['unit']"
                    :icon="$metric['icon']"
                    :color="$metric['color']"
                />
            </div>
        @endforeach
    </div>

    {{-- BARIS 3: AREA GRAFIK PEMBANDING --}}
    <div class="row mb-4">
        <div class="col-12">
            <x-card>
                <x-slot name="title">
                    <i class="fas fa-chart-line text-purple mr-2"></i> Grafik Perbandingan Penjualan Total (Booking + F&B)
                </x-slot>

                <div style="position: relative; height: 320px; width: 100%;">
                    <canvas id="grafikPenjualanCanvas"></canvas>
                </div>
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

@section('js')
<script>
    $(document).ready(function() {
        // ========================================================
        // 1. INISIALISASI CHART.JS (PEMBANDING PERIODE)
        // ========================================================
        let ctx = document.getElementById('grafikPenjualanCanvas').getContext('2d');
        
        window.myChartPenjualan = new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($chartData['labels']),
                datasets: [
                    {
                        label: 'Periode Sebelumnya',
                        data: @json($chartData['previousData']),
                        borderColor: '#c9c9d4',
                        backgroundColor: 'transparent',
                        borderDash: [5, 5],
                        borderWidth: 2,
                        pointRadius: 3,
                        pointBackgroundColor: '#c9c9d4',
                        tension: 0.3,
                        fill: false
                    },
                    {
                        label: 'Periode Sekarang',
                        data: @json($chartData['currentData']),
                        borderColor: '#6f42c1',
                        backgroundColor: 'rgba(111, 66, 193, 0.08)',
                        borderWidth: 2.5,
                        pointRadius: 4,
                        pointBackgroundColor: '#6f42c1',
                        tension: 0.3,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        boxWidth: 8,
                        fontColor: '#495057'
                    }
                },
                scales: {
                    xAxes: [{
                        gridLines: { display: false },
                        ticks: { fontColor: '#8a949f' }
                    }],
                    yAxes: [{
                        gridLines: { color: '#f1f3f5', drawBorder: false },
                        ticks: {
                            beginAtZero: true,
                            suggestedMax: {{ $chartData['suggestedMax'] }},
                            fontColor: '#8a949f',
                            callback: function(value) {
                                if (value >= 1000000) {
                                    return (value / 1000000).toFixed(2) + ' jt';
                                } else if (value >= 1000) {
                                    return (value / 1000).toFixed(0) + ' rb';
                                }
                                return value.toFixed(2);
                            }
                        }
                    }]
                },
                tooltips: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                        label: function(tooltipItem, data) {
                            let label = data.datasets[tooltipItem.datasetIndex].label || '';
                            if (label) { label += ': '; }
                            label += 'Rp ' + tooltipItem.yLabel.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                            return label;
                        }
                    }
                }
            }
        });

        // ========================================================
        // 2. LOGIKA SUBMIT FILTER VIA AJAX (FAIL-SAFE)
        // ========================================================
        $(document).on('submit', 'form', function(e) {
            let form = $(this);
            
            // Hanya tangani form filter (yang ada di dalam custom-card)
            if (!form.closest('.custom-card').length) return;

            e.preventDefault();

            let actionUrl = form.attr('action') || window.location.href;
            let formData = form.serialize();

            let submitBtn = form.find('button[type="submit"]');
            let originalBtnHtml = submitBtn.html();
            
            // Indikator Loading
            submitBtn.html('<i class="fas fa-spinner fa-spin mr-2"></i> Memuat...').prop('disabled', true);

            $.ajax({
                url: actionUrl,
                type: 'GET',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response && response.success) {
                        let data = response.data;
                        let metrics = data.metrics;
                        let chart = data.chart;

                        // Update Metrik Ringkasan
                        try {
                            if (metrics.total_pendapatan) $('#val-pendapatan').text(metrics.total_pendapatan);
                            if (metrics.total_booking)    $('#val-booking').text(metrics.total_booking);
                            if (metrics.total_fnb)        $('#val-fnb').text(metrics.total_fnb);
                            if (metrics.rata_rata)        $('#val-rata-rata').text(metrics.rata_rata);
                        } catch (errMetrics) {
                            console.error("Gagal update metrik:", errMetrics);
                        }

                        // Update Grafik Pembanding (Chart.js)
                        try {
                            if (window.myChartPenjualan && chart) {
                                window.myChartPenjualan.data.labels = chart.labels || [];
                                window.myChartPenjualan.data.datasets[0].data = chart.previousData || [];
                                window.myChartPenjualan.data.datasets[1].data = chart.currentData || [];

                                // Penyesuaian versi Chart.js (v2 vs v3)
                                if (window.myChartPenjualan.options.scales.yAxes) {
                                    window.myChartPenjualan.options.scales.yAxes[0].ticks.suggestedMax = chart.suggestedMax || 400000;
                                } else if (window.myChartPenjualan.options.scales.y) {
                                    window.myChartPenjualan.options.scales.y.suggestedMax = chart.suggestedMax || 400000;
                                }

                                window.myChartPenjualan.update();
                            }
                        } catch (errChart) {
                            console.error("Gagal update grafik:", errChart);
                        }
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", error, xhr.responseText);
                    alert("Terjadi kesalahan saat mengambil data filter.");
                },
                complete: function() {
                    // DIJAMIN SELALU mengembalikan tombol ke bentuk awal
                    submitBtn.html(originalBtnHtml).prop('disabled', false);
                }
            });
        });
    });
</script>
@stop