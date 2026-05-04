@extends('adminlte::page')

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
                                <option value="ditahan" {{ request('status_booking') == 'ditahan' ? 'selected' : '' }}>Pending (Ditahan)</option>
                                <option value="dikonfirmasi" {{ request('status_booking') == 'dikonfirmasi' ? 'selected' : '' }}>Dikonfirmasi</option>
                                <option value="dibatalkan" {{ request('status_booking') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                                <option value="selesai" {{ request('status_booking') == 'selesai' ? 'selected' : '' }}>Selesai</option>
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
        {{-- Total Booking --}}
        <div class="col-md-3">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $summary['total_booking'] }}</h3>
                    <p>Total Booking</p>
                </div>
                <div class="icon"><i class="fas fa-calendar-check"></i></div>
            </div>
        </div>

        {{-- Booking Confirmed --}}
        <div class="col-md-3">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $summary['confirmed'] }}</h3>
                    <p>Booking Confirmed</p>
                </div>
                <div class="icon"><i class="fas fa-check-circle"></i></div>
            </div>
        </div>

        {{-- Booking Cancelled --}}
        <div class="col-md-3">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $summary['cancelled'] }}</h3>
                    <p>Booking Cancelled</p>
                </div>
                <div class="icon"><i class="fas fa-times-circle"></i></div>
            </div>
        </div>

        {{-- Total Pendapatan --}}
        <div class="col-md-3">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>Rp {{ number_format($summary['pendapatan'], 0, ',', '.') }}</h3>
                    <p>Total Pendapatan (Selesai)</p>
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
                    @forelse($laporan as $key => $item)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $item->id_transaksi }}</td>
                        <td>{{ $item->user->nama_pengguna }}</td>
                        <td>{{ $item->penetapanHarga->ruangan->nama_ruangan }}</td>
                        <td>{{ $item->penetapanHarga->paket->nama_paket }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->waktu_mulai)->format('d/m/Y') }}</td>
                        <td>Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                        <td>
                            {{-- Warna Badge sesuai ENUM status_sewa --}}
                            @if($item->status_sewa == 'dikonfirmasi')
                                <span class="badge badge-success">Dikonfirmasi</span>
                            @elseif($item->status_sewa == 'ditahan')
                                <span class="badge badge-warning">Ditahan</span>
                            @elseif($item->status_sewa == 'dibatalkan')
                                <span class="badge badge-danger">Dibatalkan</span>
                            @elseif($item->status_sewa == 'selesai')
                                <span class="badge badge-info">Selesai</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $item->status_pembayaran == 'lunas' ? 'badge-success' : 'badge-secondary' }}">
                                {{ ucfirst($item->status_pembayaran) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center">Data laporan tidak ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="6" class="text-right">Total Pendapatan:</th>
                        <th>Rp {{ number_format($summary['pendapatan'], 0, ',', '.') }}</th>
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