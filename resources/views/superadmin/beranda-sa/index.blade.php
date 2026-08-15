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
                    default="bulanan"
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

    {{-- BARIS 4: 6 CARD ANALITIK (data asli dari $analysisCardsData) --}}
    <div class="row">
        <div class="col-xl-4 col-md-6 mb-4">
            <x-analysis-card
                title="Analisis Pendapatan"
                :items="$analysisCardsData['analisisPendapatan']"
                containerId="items-analisis-pendapatan"
                :link="route('superadmin.analisis-pendapatan')"
            />
        </div>
        <div class="col-xl-4 col-md-6 mb-4">
            <x-analysis-card
                title="Produk Layanan / Sewa"
                :items="$analysisCardsData['produkLayanan']"
                containerId="items-produk-layanan"
                :link="route('superadmin.produk-layanan')"
            />
        </div>
        <div class="col-xl-4 col-md-6 mb-4">
            <x-analysis-card
                title="Produk F&B"
                :items="$analysisCardsData['produkFnb']"
                containerId="items-produk-fnb"
                :link="route('superadmin.produk-fnb')"
            />
        </div>
        <div class="col-xl-4 col-md-6 mb-4">
            <x-analysis-card
                title="Metode Pembayaran"
                :items="$analysisCardsData['metodePembayaran']"
                containerId="items-metode-pembayaran"
                :link="route('superadmin.metode-pembayaran')"
            />
        </div>
        <div class="col-xl-4 col-md-6 mb-4">
            <x-analysis-card
                title="Performa Kasir"
                :items="$analysisCardsData['performaKasir']"
                containerId="items-performa-kasir"
                :link="route('superadmin.penjualan-kasir')"
            />
        </div>
        <div class="col-xl-4 col-md-6 mb-4">
            <x-analysis-card
                title="Laporan Transaksi"
                :items="$analysisCardsData['laporanTransaksi']"
                containerId="items-laporan-transaksi"
                :link="route('superadmin.laporan.index')"
            />
        </div>
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

        // Pemetaan key response.data.cards -> id container di DOM
        const CARD_CONTAINER_MAP = {
            analisisPendapatan: 'items-analisis-pendapatan',
            produkLayanan:      'items-produk-layanan',
            produkFnb:          'items-produk-fnb',
            metodePembayaran:   'items-metode-pembayaran',
            performaKasir:      'items-performa-kasir',
            laporanTransaksi:   'items-laporan-transaksi',
        };

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
                        let cards = data.cards;

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

                        // Update 6 Card Analitik (HTML sudah dirender server-side)
                        try {
                            if (cards) {
                                Object.keys(CARD_CONTAINER_MAP).forEach(function (key) {
                                    if (typeof cards[key] !== 'undefined') {
                                        $('#' + CARD_CONTAINER_MAP[key]).html(cards[key]);
                                    }
                                });
                            }
                        } catch (errCards) {
                            console.error("Gagal update card analitik:", errCards);
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