@extends('adminlte::page')
@include('partials.sidebar-superadmin')

@section('title', 'Metode Pembayaran - Play N Chill')
@section('plugins.Chartjs', true)

@section('content_header')
<div class="container-fluid py-2">
    <div class="row align-items-center">
        <div class="col-sm-12">
            <h1 class="font-weight-bold text-dark m-0" style="font-size: 28px; letter-spacing: -0.5px;">Metode Pembayaran</h1>
            <p class="text-muted mb-0" style="font-size: 14px;">Laporan jenis pembayaran Play N Chill</p>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="container-fluid pb-4">

    {{-- BARIS 1: FILTER (Card Putih) --}}
    <div class="card shadow-sm border-0 mb-4" style="border-radius: 8px;">
        <div class="card-body">
            <form method="GET" action="{{ route('superadmin.metode-pembayaran') }}">
                <div class="row align-items-end" style="gap: 15px; padding: 0 10px;">
                    
                    <div style="width: 180px;">
                        <label class="custom-label">PERIODE</label>
                        <select name="periode" class="form-control form-control-sm shadow-none" style="border-radius: 6px; height: 36px;">
                            <option value="harian">Harian</option>
                            <option value="mingguan">Mingguan</option>
                            <option value="bulanan">Bulanan</option>
                        </select>
                    </div>

                    <div style="width: 250px;">
                        <label class="custom-label">RENTANG TANGGAL</label>
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-white" style="border-right: none; border-radius: 6px 0 0 6px;"><i class="far fa-calendar-alt text-muted"></i></span>
                            </div>
                            <input type="text" class="form-control shadow-none" placeholder="01 Jun 2026 - 30 Jun 2026" style="border-left: none; height: 36px; border-radius: 0 6px 6px 0;">
                        </div>
                    </div>

                    <div style="width: 200px;">
                        <label class="custom-label">JENIS PEMBAYARAN</label>
                        <select name="jenis" class="form-control form-control-sm shadow-none" style="border-radius: 6px; height: 36px;">
                            <option value="semua">Semua</option>
                            <option value="qris">QRIS</option>
                            <option value="tunai">Tunai</option>
                        </select>
                    </div>

                    <div class="d-flex" style="gap: 10px;">
                        <button type="submit" class="btn btn-primary btn-sm px-4 shadow-sm" style="background-color: #0084ff; border: none; height: 36px; border-radius: 6px; font-weight: 500;">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                        <a href="#" class="btn btn-secondary btn-sm px-4 shadow-sm" style="background-color: #6c757d; border: none; height: 36px; border-radius: 6px; font-weight: 500;">
                            <i class="fas fa-sync-alt"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- BARIS 2: 4 KARTU RINGKASAN (MIRIP STITCH AI) --}}
    <div class="row mb-4">
        {{-- Total Transactions --}}
        <div class="col-md-3">
            <div class="pay-card bg-teal-custom">
                <div class="title">Total Transactions</div>
                <div class="value">{{ $summary['total_trx'] }}</div>
                <i class="fas fa-receipt icon-bg"></i>
            </div>
        </div>
        
        {{-- Total Revenue --}}
        <div class="col-md-3">
            <div class="pay-card bg-green-custom">
                <div class="title">Total Revenue</div>
                <div class="value">Rp {{ number_format($summary['total_revenue'], 0, ',', '.') }}</div>
                <i class="fas fa-money-bill-wave icon-bg"></i>
            </div>
        </div>
        
        {{-- Most Used Method --}}
        <div class="col-md-3">
            <div class="pay-card bg-yellow-custom">
                <div class="title">Most Used Method</div>
                <div class="value">{{ $summary['most_used_name'] }} <span class="sub-val">({{ $summary['most_used_tx'] }} tx)</span></div>
                <i class="fas fa-money-check-alt icon-bg"></i>
            </div>
        </div>
        
        {{-- Highest Revenue Method --}}
        <div class="col-md-3">
            <div class="pay-card bg-red-custom">
                <div class="title">Highest Revenue Method</div>
                <div class="value">{{ $summary['highest_rev_name'] }}</div>
                <div class="sub-val mt-1">(Rp {{ number_format($summary['highest_rev_amount'], 0, ',', '.') }})</div>
                <i class="fas fa-qrcode icon-bg"></i>
            </div>
        </div>
    </div>

    {{-- BARIS 3: GRAFIK --}}
    <div class="card shadow-sm border-0 mb-4" style="border-radius: 8px;">
        <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
            <h3 class="card-title font-weight-bold" style="color: #6f42c1; font-size: 1.1rem;">Grafik Metode Pembayaran</h3>
        </div>
        <div class="card-body">
            <div class="chart-container" style="position: relative; height:350px; width:100%;">
                <canvas id="paymentChart"></canvas>
            </div>
        </div>
    </div>

    {{-- BARIS 4: TABEL DATA --}}
    <div class="card shadow-sm border-0" style="border-radius: 8px;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle text-center mb-0">
                    <thead style="background-color: #f3f4f6;">
                        <tr>
                            <th class="py-3 text-muted border-0" style="font-size: 11px; font-weight: 600;">NO</th>
                            <th class="py-3 text-muted border-0" style="font-size: 11px; font-weight: 600;">METODE PEMBAYARAN</th>
                            <th class="py-3 text-muted border-0" style="font-size: 11px; font-weight: 600;">JUMLAH TRANSAKSI</th>
                            <th class="py-3 text-muted border-0" style="font-size: 11px; font-weight: 600;">KONTRIBUSI (%)</th>
                            <th class="py-3 text-muted border-0" style="font-size: 11px; font-weight: 600;">TOTAL PENDAPATAN (RP)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tableData as $index => $row)
                        <tr>
                            <td class="py-3 text-muted" style="font-size: 13px;">{{ $index + 1 }}</td>
                            <td class="py-3 text-dark font-weight-bold" style="font-size: 13px;">{{ $row['metode'] }}</td>
                            <td class="py-3 text-muted" style="font-size: 13px;">{{ $row['jml_trx'] }}</td>
                            <td class="py-3 text-muted" style="font-size: 13px;">{{ $row['persentase'] }}</td>
                            <td class="py-3 text-dark font-weight-bold" style="font-size: 13px;">Rp {{ number_format($row['total_pendapatan'], 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            {{-- Footer Pagination --}}
            <div class="d-flex justify-content-between align-items-center p-3 border-top bg-white" style="border-radius: 0 0 8px 8px;">
                <span class="text-muted" style="font-size: 13px;">Menampilkan 1 hingga {{ count($tableData) }} dari {{ count($tableData) }} entri</span>
                <div class="btn-group">
                    <button class="btn btn-sm btn-light border text-muted">Sebelumnya</button>
                    <button class="btn btn-sm btn-primary" style="background-color: #6f42c1; border-color: #6f42c1;">1</button>
                    <button class="btn btn-sm btn-light border text-muted">Selanjutnya</button>
                </div>
            </div>
        </div>
    </div>

</div>
@stop

@section('css')
<style>
/* Styling Label Filter */
.custom-label {
    font-size: 10px;
    font-weight: 700;
    color: #4a5568;
    margin-bottom: 5px;
    letter-spacing: 0.5px;
}

/* Styling 4 Kartu Ringkasan (Meniru Desain) */
.pay-card {
    border-radius: 10px;
    padding: 22px 20px;
    color: white;
    position: relative;
    overflow: hidden;
    height: 100%;
    box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    transition: transform 0.2s;
}
.pay-card:hover {
    transform: translateY(-3px);
}
.pay-card .title {
    font-size: 12px;
    font-weight: 600;
    margin-bottom: 6px;
    opacity: 0.9;
}
.pay-card .value {
    font-size: 26px;
    font-weight: 700;
    line-height: 1.2;
}
.pay-card .sub-val {
    font-size: 14px;
    font-weight: normal;
    opacity: 0.9;
}
.pay-card .icon-bg {
    position: absolute;
    right: 15px;
    bottom: -10px;
    font-size: 75px;
    opacity: 0.15;
    transform: rotate(-10deg);
}

/* Warna Kustom Berdasarkan Desain */
.bg-teal-custom { background-color: #0ea5e9; } /* Cyan/Teal */
.bg-green-custom { background-color: #22c55e; } /* Hijau */
.bg-yellow-custom { background-color: #eab308; } /* Kuning/Orange */
.bg-red-custom { background-color: #ef4444; } /* Merah */
</style>
@stop

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('paymentChart').getContext('2d');
        
        // Data dari Controller
        const rawLabels = {!! json_encode($chartLabels) !!};
        const rawDatasets = {!! json_encode($chartDatasets) !!};

        // Format chart jadi garis melengkung tebal (seperti desain)
        const formattedDatasets = rawDatasets.map((item) => {
            return {
                label: item.label,
                data: item.data,
                borderColor: item.color,
                backgroundColor: 'transparent',
                borderWidth: 3,
                tension: 0.4, // Membuat garis jadi kurva mulus
                pointRadius: 0, // Menghilangkan titik (dot) agar mulus
                pointHoverRadius: 6,
                pointHitRadius: 15,
            };
        });

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: rawLabels,
                datasets: formattedDatasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                plugins: {
                    legend: {
                        display: false // Sembunyikan legend bawaan
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': Rp ' + (context.parsed.y / 1000).toLocaleString('id-ID') + 'K';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: '#8a949f', font: { size: 10 } }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f0f0f5', drawBorder: false },
                        ticks: {
                            color: '#8a949f',
                            font: { size: 10 },
                            callback: function(value) {
                                if (value >= 1000000) return (value / 1000000).toFixed(2) + ' jt';
                                if (value >= 1000) return (value / 1000).toFixed(2) + ' rb';
                                return value;
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@stop