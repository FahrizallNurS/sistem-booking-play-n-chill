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

    {{-- BARIS 1: FILTER --}}
    <div class="row mb-4">
        <div class="col-12">
            <x-filter-card id="filter-form" :action="url()->current()">
                <x-filter-select name="periode" label="PERIODE" :options="['harian' => 'Harian', 'mingguan' => 'Mingguan', 'bulanan' => 'Bulanan']" width="col-md-2 col-sm-6" default="harian"/>
                
                {{-- Kalender Dinamis Seragam dengan Halaman Lain --}}
                <x-filter-dynamic-date width="col-md-4 col-sm-6" />
                
                <x-filter-select name="jenis" label="JENIS PEMBAYARAN" :options="['semua' => 'Semua', 'qris' => 'QRIS', 'tunai' => 'Tunai']" width="col-md-3 col-sm-6"/>
            </x-filter-card>
        </div>
    </div>

    {{-- BARIS 2: 4 KARTU RINGKASAN --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="pay-card bg-teal-custom">
                <div class="title">Total Transactions</div>
                <div class="value" id="summary-total-trx">{{ $summary['total_trx'] }}</div>
                <i class="fas fa-receipt icon-bg"></i>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="pay-card bg-green-custom">
                <div class="title">Total Revenue</div>
                <div class="value" id="summary-total-rev">Rp {{ number_format($summary['total_revenue'], 0, ',', '.') }}</div>
                <i class="fas fa-money-bill-wave icon-bg"></i>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="pay-card bg-yellow-custom">
                <div class="title">Most Used Method</div>
                <div class="value"><span id="summary-most-used-name">{{ $summary['most_used_name'] }}</span> <span class="sub-val" id="summary-most-used-tx">({{ $summary['most_used_tx'] }} tx)</span></div>
                <i class="fas fa-money-check-alt icon-bg"></i>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="pay-card bg-red-custom">
                <div class="title">Highest Revenue Method</div>
                <div class="value" id="summary-highest-rev-name">{{ $summary['highest_rev_name'] }}</div>
                <div class="sub-val mt-1" id="summary-highest-rev-amount">(Rp {{ number_format($summary['highest_rev_amount'], 0, ',', '.') }})</div>
                <i class="fas fa-qrcode icon-bg"></i>
            </div>
        </div>
    </div>

   {{-- BARIS 3: GRAFIK --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0" style="border-radius: 8px;">
                
                {{-- CLASS D-FLEX & W-100 AGAR MEMENUHI LEBAR KOTAK --}}
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0 d-flex align-items-center w-100">
                    
                    {{-- CLASS 'card-title' DIHAPUS AGAR FLEXBOX BERJALAN LANCAR --}}
                    <h3 class="font-weight-bold m-0" style="color: #6f42c1; font-size: 1.1rem;">Grafik Metode Pembayaran</h3>

                    {{-- CLASS 'ml-auto' UNTUK DORONG KE KANAN, 'mr-4' UNTUK JARAK/GAP --}}
                    <div class="d-flex align-items-center ml-auto mr-4 mt-2 mt-sm-0" style="gap: 15px; font-size: 14px; color: #4a5568; font-weight: 500;">
                        <div class="d-flex align-items-center">
                            <span style="display: inline-block; width: 14px; height: 14px; border-radius: 50%; background-color: #0ea5e9; margin-right: 8px;"></span>
                            QRIS
                        </div>
                        <div class="d-flex align-items-center">
                            <span style="display: inline-block; width: 14px; height: 14px; border-radius: 50%; background-color: #22c55e; margin-right: 8px;"></span>
                            Tunai
                        </div>
                    </div>
                </div>
                
                {{-- TAG </div> YANG BERLEBIHAN DI SINI SUDAH DIHAPUS --}}

                <div class="card-body">
                    <div class="chart-container" style="position: relative; height:350px; width:100%;">
                        {{-- Menggunakan komponen x-chart agar seragam --}}
                        <x-chart id="paymentChart" :labels="$chartLabels" :datasets="$chartDatasets" height="350px" :interactive="true" />
                    </div>
                </div>
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
                    <tbody id="payment-table-body">
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
            
            <div class="d-flex justify-content-between align-items-center p-3 border-top bg-white" style="border-radius: 0 0 8px 8px;">
                <span id="footer-info" class="text-muted" style="font-size: 13px;">Menampilkan 1 hingga {{ count($tableData) }} dari {{ count($tableData) }} entri</span>
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
.pay-card:hover { transform: translateY(-3px); }
.pay-card .title { font-size: 12px; font-weight: 600; margin-bottom: 6px; opacity: 0.9; }
.pay-card .value { font-size: 26px; font-weight: 700; line-height: 1.2; }
.pay-card .sub-val { font-size: 14px; font-weight: normal; opacity: 0.9; }
.pay-card .icon-bg { position: absolute; right: 15px; bottom: -10px; font-size: 75px; opacity: 0.15; transform: rotate(-10deg); }

.bg-teal-custom { background-color: #0ea5e9; } 
.bg-green-custom { background-color: #22c55e; } 
.bg-yellow-custom { background-color: #eab308; } 
.bg-red-custom { background-color: #ef4444; } 
</style>
@stop

@section('js')
<script>
$(document).ready(function() {
    $('.custom-input').css('pointer-events', 'auto').removeAttr('readonly');

    $('#filter-form').on('submit', function(e) {
        e.preventDefault(); 
        
        let form = $(this);
        let btn = form.find('button[type="submit"]');
        let origBtn = btn.html();

        btn.html('<i class="fas fa-spinner fa-spin mr-2"></i> Memuat...').prop('disabled', true);

        $.get(form.attr('action') || window.location.href, form.serialize(), function(res) {
            if(res.success) {
                // Update 4 Kartu Ringkasan
                $('#summary-total-trx').text(res.summary.total_trx);
                $('#summary-total-rev').text('Rp ' + new Intl.NumberFormat('id-ID').format(res.summary.total_revenue));
                $('#summary-most-used-name').text(res.summary.most_used_name);
                $('#summary-most-used-tx').text('(' + res.summary.most_used_tx + ' tx)');
                $('#summary-highest-rev-name').text(res.summary.highest_rev_name);
                $('#summary-highest-rev-amount').text('(Rp ' + new Intl.NumberFormat('id-ID').format(res.summary.highest_rev_amount) + ')');

                // Update Grafik
                const chartInstance = window.__pncCharts['paymentChart'];
                if (chartInstance) {
                    chartInstance.data.labels = res.labels;
                    chartInstance.data.datasets = res.datasets.map(function(item) {
                        return {
                            label: item.label,
                            data: item.data,
                            borderColor: item.color,
                            backgroundColor: 'transparent',
                            borderWidth: 3,
                            tension: 0.4,
                            pointRadius: 0,
                            pointHoverRadius: 6,
                            pointHitRadius: 15,
                        };
                    });
                    chartInstance.update();
                }
                
                // Update Tabel
                if(res.html !== undefined) {
                    $('#payment-table-body').html(res.html);
                    $('#footer-info').text('Menampilkan 1 hingga ' + res.total + ' dari ' + res.total + ' entri');
                }
            }
            
            btn.html(origBtn).prop('disabled', false);
        }).fail(function() {
            alert('Terjadi kesalahan saat memuat data metode pembayaran.');
            btn.html(origBtn).prop('disabled', false);
        });
    });
});
</script>
@stop