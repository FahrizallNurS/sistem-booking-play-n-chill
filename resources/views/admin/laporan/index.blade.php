@extends('adminlte::page')
@include('partials.sidebar-admin')
@section('title', 'Laporan')

@section('content_header')
    <h1>Laporan Booking</h1>
@stop

@section('content')

    {{-- Filter --}}
    <div class="card">
        <div class="card-body">
            <form method="GET" action="#">
                <div class="row align-items-end">
                    <div class="col-md-3">
                        <div class="form-group mb-0">
                            <label>Periode</label>
                            <select name="periode" id="periode" class="form-control">
                                <option value="harian">Harian</option>
                                <option value="mingguan">Mingguan</option>
                                <option value="bulanan">Bulanan</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3" id="filter_tanggal">
                        <div class="form-group mb-0">
                            <label>Tanggal</label>
                            <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="col-md-3" id="filter_minggu" style="display:none">
                        <div class="form-group mb-0">
                            <label>Minggu</label>
                            <input type="week" name="minggu" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3" id="filter_bulan" style="display:none">
                        <div class="form-group mb-0">
                            <label>Bulan</label>
                            <input type="month" name="bulan" class="form-control" value="{{ date('Y-m') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-0">
                            <label>Status Booking</label>
                            <select name="status_booking" class="form-control">
                                <option value="">Semua Status</option>
                                <option value="pending">Pending</option>
                                <option value="confirmed">Confirmed</option>
                                <option value="cancelled">Cancelled</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 mt-2">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-search"></i> Tampilkan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="row">
        <div class="col-md-3">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>12</h3>
                    <p>Total Booking</p>
                </div>
                <div class="icon"><i class="fas fa-calendar-check"></i></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>8</h3>
                    <p>Booking Confirmed</p>
                </div>
                <div class="icon"><i class="fas fa-check-circle"></i></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>2</h3>
                    <p>Booking Cancelled</p>
                </div>
                <div class="icon"><i class="fas fa-times-circle"></i></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>Rp 1.200.000</h3>
                    <p>Total Pendapatan</p>
                </div>
                <div class="icon"><i class="fas fa-money-bill-wave"></i></div>
            </div>
        </div>
    </div>

    {{-- Tabel Laporan --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Detail Laporan</h3>
            <div class="card-tools">
                <a href="#" class="btn btn-danger btn-sm">
                    <i class="fas fa-file-pdf"></i> Export PDF
                </a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Kode Booking</th>
                        <th>Pelanggan</th>
                        <th>Ruangan</th>
                        <th>Paket</th>
                        <th>Tanggal</th>
                        <th>Total Harga</th>
                        <th>Status Booking</th>
                        <th>Status Bayar</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Dummy data --}}
                    <tr>
                        <td>1</td>
                        <td>BK-001</td>
                        <td>John Doe</td>
                        <td>Reguler - 01</td>
                        <td>Paket PS4 1 Jam</td>
                        <td>05/04/2026</td>
                        <td>Rp 50.000</td>
                        <td><span class="badge badge-success">Confirmed</span></td>
                        <td><span class="badge badge-success">Paid</span></td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>BK-002</td>
                        <td>Jane Doe</td>
                        <td>VIP - 01</td>
                        <td>Paket Gaming 2 Jam</td>
                        <td>05/04/2026</td>
                        <td>Rp 150.000</td>
                        <td><span class="badge badge-secondary">Pending</span></td>
                        <td><span class="badge badge-warning">Unpaid</span></td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>BK-003</td>
                        <td>Bob Smith</td>
                        <td>VVIP - 01</td>
                        <td>Paket Karaoke 1 Jam</td>
                        <td>05/04/2026</td>
                        <td>Rp 200.000</td>
                        <td><span class="badge badge-danger">Cancelled</span></td>
                        <td><span class="badge badge-danger">Unpaid</span></td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="6" class="text-right">Total Pendapatan:</th>
                        <th>Rp 1.200.000</th>
                        <th colspan="2"></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

@stop

@section('js')
<script>
    // Switch filter input berdasarkan periode
    document.getElementById('periode').addEventListener('change', function() {
        const periode = this.value;
        document.getElementById('filter_tanggal').style.display = 'none';
        document.getElementById('filter_minggu').style.display = 'none';
        document.getElementById('filter_bulan').style.display = 'none';

        if (periode === 'harian') document.getElementById('filter_tanggal').style.display = 'block';
        else if (periode === 'mingguan') document.getElementById('filter_minggu').style.display = 'block';
        else if (periode === 'bulanan') document.getElementById('filter_bulan').style.display = 'block';
    });
</script>
@stop