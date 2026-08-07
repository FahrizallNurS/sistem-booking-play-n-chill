@extends('adminlte::page')
@include('partials.sidebar-superadmin')

@section('title', 'Produk Layanan - Play N Chill')
@section('plugins.Daterangepicker', true)
@section('plugins.Chartjs', true)

@section('content_header')
<div class="container-fluid py-2">
    <div class="row align-items-center">
        <div class="col-sm-12">
            <h1 class="font-weight-bold text-dark m-0" style="font-size: 28px; letter-spacing: -0.5px;">Produk Layanan</h1>
            <p class="text-muted mb-0" style="font-size: 14px;">Analisis performa pendapatan berdasarkan jenis layanan Play N Chill.</p>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="container-fluid pb-4">

    {{-- BARIS 1: FILTER (Dengan tambahan Sub Kategori) --}}
    <div class="row mb-4">
        <div class="col-12">
            <x-filter-card :action="url()->current()">
                <x-filter-select name="periode" label="PERIODE" :options="['bulanan' => 'BULANAN', 'mingguan' => 'MINGGUAN']" width="col-md-2 col-sm-6"/>
                <x-filter-date-range name="rentang_tanggal" label="RENTANG TANGGAL" placeholder="01 Jun 2026 - 30 Jun 2026" width="col-md-3 col-sm-6"/>
                <x-filter-select name="kategori" label="KATEGORI" :options="['semua' => 'Semua']" width="col-md-2 col-sm-6"/>
                <x-filter-select name="sub_kategori" label="SUB KATEGORI" :options="['semua' => 'Semua']" width="col-md-2 col-sm-6"/>
            </x-filter-card>
        </div>
    </div>

    {{-- BARIS 2: GRAFIK & LEGEND KUSTOM --}}
    <div class="row mb-2">
        <div class="col-12">
            <x-card>
                <x-slot name="title">
                    <span class="font-weight-bold text-purple" style="font-size: 1.1rem;">Grafik Penjualan layanan</span>
                </x-slot>
                
                <x-slot name="tools">
                    {{-- Legend Header (Titik-titik warna) seperti di desain --}}
                    <div id="chart-header-legend" class="d-none d-md-flex gap-3 align-items-center" style="font-size: 12px;"></div>
                </x-slot>

                {{-- Canvas Chart --}}
                <div class="chart-container mt-3" style="position: relative; height:400px; width:100%;">
                    <x-chart id="serviceAnalysisChart" :labels="$chartLabels" :datasets="$chartDatasets" height="400px" />
                </div>

                {{-- Penampung Box Legend di bawah grafik --}}
                <div id="custom-legend-boxes" class="d-flex flex-wrap gap-3 mt-4"></div>
            </x-card>
        </div>
    </div>

    {{-- BARIS 3: TABEL DATA --}}
    <div class="row mt-4">
        <div class="col-12">
            <x-table>
                {{-- Slot Header (Isi Kolom Sesuai Desain Produk Layanan) --}}
                <x-slot name="head">
                    {{-- Menggunakan warna background pink/ungu muda halus sesuai gambar --}}
                    <tr style="background-color: #faf5ff;">
                        <th class="py-3 px-4 border-0 text-muted" style="font-size: 11px;">NO</th>
                        <th class="py-3 border-0 text-muted" style="font-size: 11px;">NAMA PAKET</th>
                        <th class="py-3 border-0 text-muted" style="font-size: 11px;">KATEGORI</th>
                        <th class="py-3 border-0 text-muted" style="font-size: 11px;">SUB KATEGORI</th>
                        <th class="py-3 border-0 text-muted" style="font-size: 11px;">HARGA JUAL</th>
                        <th class="py-3 border-0 text-muted text-center" style="font-size: 11px;">SATUAN JAM</th>
                        <th class="py-3 border-0 text-muted" style="font-size: 11px;">SKU</th>
                    </tr>
                </x-slot>

                {{-- Slot Body (Isi Data Baris) --}}
                @foreach($tableData as $index => $row)
                    <tr>
                        <td class="px-4 text-muted py-3" style="font-size: 13px;">{{ $index + 1 }}</td>
                        <td class="text-dark py-3" style="font-size: 13px;">{{ $row['paket'] }}</td>
                        <td class="text-muted py-3" style="font-size: 13px;">{{ $row['kategori'] }}</td>
                        <td class="text-muted py-3" style="font-size: 13px;">{{ $row['sub'] }}</td>
                        <td class="text-muted py-3" style="font-size: 13px;">Rp. {{ number_format($row['harga'], 2, ',', '.') }}</td>
                        <td class="text-muted py-3 text-center" style="font-size: 13px;">{{ $row['jam'] }}</td>
                        <td class="text-muted py-3" style="font-size: 13px;">{{ $row['sku'] }}</td>
                    </tr>
                @endforeach

                {{-- Slot Footer (Pagination) --}}
                <x-slot name="footer">
                    <span class="text-muted" style="font-size: 13px;">Menampilkan 1 hingga 5 dari 5 entri</span>
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
/* CSS Tambahan untuk interaksi Box Legend */
.legend-box {
    transition: all 0.2s ease;
    user-select: none;
}
.legend-box:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 6px rgba(0,0,0,0.1) !important;
}
.legend-box.hidden-dataset {
    opacity: 0.5;
    background-color: #f8f9fa !important;
}
.text-purple { color: #6f42c1; }

/* Mengatur jarak antar filter label agar uppercase & rapi */
label {
    font-size: 11px !important;
    font-weight: 600 !important;
    color: #4a5568;
    margin-bottom: 4px;
}
</style>
@stop

@section('js')
<script>
    let serviceChart; 

    function toggleDataset(index) {
        const isHidden = serviceChart.isDatasetVisible(index);
        
        // Sembunyikan atau tampilkan dataset di chart
        if (isHidden) {
            serviceChart.show(index);
        } else {
            serviceChart.hide(index);
        }
        
        // Ubah tampilan visual box legend (tambah class opacity)
        const box = document.getElementById(`legend-box-${index}`);
        box.classList.toggle('hidden-dataset');
    }

    document.addEventListener('DOMContentLoaded', function () {
        const chartElement = document.getElementById('serviceAnalysisChart');
        if (!chartElement || typeof Chart === 'undefined') return;

        const rawLabels = JSON.parse(chartElement.getAttribute('data-labels') || '[]');
        const rawDatasets = JSON.parse(chartElement.getAttribute('data-datasets') || '[]');

        // Format dataset untuk 4 garis melengkung (tanpa fill)
        const formattedDatasets = rawDatasets.map((item) => {
            return {
                label: item.label,
                data: item.data,
                borderColor: item.color,
                backgroundColor: 'transparent',
                borderWidth: 3,
                tension: 0.4, // Kurva melengkung
                pointRadius: 0, // Hilangkan titik default
                pointHoverRadius: 6,
                pointHitRadius: 10,
            };
        });

        // 1. Generate Header Legend (Titik-titik warna di atas)
        const headerLegendContainer = document.getElementById('chart-header-legend');
        if (headerLegendContainer) {
            let headerHTML = '';
            rawDatasets.forEach((item) => {
                headerHTML += `
                    <div class="d-flex align-items-center gap-1 mr-3">
                        <div class="rounded-circle" style="width: 10px; height: 10px; background-color: ${item.color};"></div>
                        <span class="text-dark">${item.label}</span>
                    </div>
                `;
            });
            headerLegendContainer.innerHTML = headerHTML;
        }

        // 2. Generate Box Legend Kustom di bawah grafik
        const legendContainer = document.getElementById('custom-legend-boxes');
        if (legendContainer) {
            let legendHTML = '';
            
            // Loop 4 kotak legend utama
            rawDatasets.forEach((item, index) => {
                legendHTML += `
                    <div id="legend-box-${index}" class="legend-box border rounded-3 p-3 bg-white shadow-sm flex-fill" style="min-width: 160px; border-top: 4px solid ${item.color} !important; cursor: pointer;" onclick="toggleDataset(${index})">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="font-weight-bold text-dark" style="font-size: 12px;">${item.label}</span>
                            <i class="fas fa-times text-muted" style="font-size: 12px;"></i>
                        </div>
                    </div>
                `;
            });
            
            // Tambahkan Tombol "Tambah Pembanding" di akhir
            legendHTML += `
                <div class="legend-box border rounded-3 p-3 bg-white shadow-sm flex-fill d-flex align-items-center justify-content-center" style="min-width: 160px; cursor: pointer;" onclick="alert('Fitur Tambah Pembanding')">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle d-flex align-items-center justify-content-center mr-2" style="width: 20px; height: 20px; background-color: #e5e7eb;">
                            <div class="rounded-circle" style="width: 12px; height: 12px; background-color: #a78bfa;"></div>
                        </div>
                        <span class="font-weight-bold" style="font-size: 13px; color: #374151;">Tambah Pembanding</span>
                    </div>
                </div>
            `;
            
            legendContainer.innerHTML = legendHTML;
        }

        const ctx = chartElement.getContext('2d');
        serviceChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: rawLabels,
                datasets: formattedDatasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { display: false }, // Matikan legend bawaan
                    tooltip: {
                        callbacks: {
                            label: function (ctx) {
                                return ctx.dataset.label + ': Rp ' + (ctx.parsed.y / 1000).toLocaleString('id-ID') + 'K';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: '#8a949f', font: { size: 11 } }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f0f0f5', drawBorder: false },
                        ticks: {
                            color: '#8a949f',
                            font: { size: 11 },
                            callback: function (value) { 
                                return 'RP' + (value / 1000) + 'K'; 
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@stop