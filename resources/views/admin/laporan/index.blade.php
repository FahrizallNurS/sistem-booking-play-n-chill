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
            <form method="GET" action="{{ route('admin.laporan.index') }}">
                <div class="row align-items-end">

                    {{-- Periode --}}
                    <div class="col-md-2">
                        <div class="form-group mb-0">
                            <label>Periode</label>
                            <select name="periode" id="periode" class="form-control">
                                <option value="harian"   {{ request('periode','harian') === 'harian'   ? 'selected' : '' }}>Harian</option>
                                <option value="mingguan" {{ request('periode') === 'mingguan' ? 'selected' : '' }}>Mingguan</option>
                                <option value="bulanan"  {{ request('periode') === 'bulanan'  ? 'selected' : '' }}>Bulanan</option>
                            </select>
                        </div>
                    </div>

                    {{-- Tanggal --}}
                    <div class="col-md-2" id="filter_tanggal">
                        <div class="form-group mb-0">
                            <label>Tanggal</label>
                            <input type="date" name="tanggal" class="form-control"
                                value="{{ request('tanggal', now()->format('Y-m-d')) }}">
                        </div>
                    </div>

                    {{-- Minggu --}}
                    <div class="col-md-2" id="filter_minggu" style="display:none">
                        <div class="form-group mb-0">
                            <label>Minggu</label>
                            <input type="week" name="minggu" class="form-control"
                                value="{{ request('minggu', now()->format('Y-\WW')) }}">
                        </div>
                    </div>

                    {{-- Bulan --}}
                    <div class="col-md-2" id="filter_bulan" style="display:none">
                        <div class="form-group mb-0">
                            <label>Bulan</label>
                            <input type="month" name="bulan" class="form-control"
                                value="{{ request('bulan', now()->format('Y-m')) }}">
                        </div>
                    </div>

                    {{-- Status Booking --}}
                    <div class="col-md-2">
                        <div class="form-group mb-0">
                            <label>Status Booking</label>
                            <select name="status_booking" class="form-control">
                                <option value="">Semua</option>
                                <option value="ditahan"      {{ request('status_booking') === 'ditahan'      ? 'selected' : '' }}>Ditahan</option>
                                <option value="dikonfirmasi" {{ request('status_booking') === 'dikonfirmasi' ? 'selected' : '' }}>Dikonfirmasi</option>
                                <option value="selesai"      {{ request('status_booking') === 'selesai'      ? 'selected' : '' }}>Selesai</option>
                                <option value="dibatalkan"   {{ request('status_booking') === 'dibatalkan'   ? 'selected' : '' }}>Dibatalkan</option>
                            </select>
                        </div>
                    </div>

                    {{-- Status Bayar --}}
                    <div class="col-md-2">
                        <div class="form-group mb-0">
                            <label>Status Bayar</label>
                            <select name="status_bayar" class="form-control">
                                <option value="">Semua</option>
                                <option value="menunggu" {{ request('status_bayar') === 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                                <option value="dp"       {{ request('status_bayar') === 'dp'       ? 'selected' : '' }}>DP</option>
                                <option value="lunas"    {{ request('status_bayar') === 'lunas'    ? 'selected' : '' }}>Lunas</option>
                            </select>
                        </div>
                    </div>

                    {{-- Jenis Bayar --}}
                    <div class="col-md-2">
                        <div class="form-group mb-0">
                            <label>Jenis Bayar</label>
                            <select name="jenis_bayar" class="form-control">
                                <option value="">Semua</option>
                                <option value="full" {{ request('jenis_bayar') === 'full' ? 'selected' : '' }}>Full Payment</option>
                                <option value="dp"   {{ request('jenis_bayar') === 'dp'   ? 'selected' : '' }}>DP</option>
                            </select>
                        </div>
                    </div>

                    {{-- Tombol --}}
                    <div class="col-md-2 mt-2">
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
                    <h3>{{ $totalBooking }}</h3>
                    <p>Total Booking</p>
                </div>
                <div class="icon"><i class="fas fa-calendar-check"></i></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $totalSelesai }}</h3>
                    <p>Booking Selesai</p>
                </div>
                <div class="icon"><i class="fas fa-check-circle"></i></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $totalDibatalkan }}</h3>
                    <p>Booking Dibatalkan</p>
                </div>
                <div class="icon"><i class="fas fa-times-circle"></i></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
                    <p>Total Pendapatan (Selesai)</p>
                </div>
                <div class="icon"><i class="fas fa-money-bill-wave"></i></div>
            </div>
        </div>
    </div>

    {{-- Tabel --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                Detail Laporan —
                <small class="text-muted">
                    {{ $start->translatedFormat('d F Y') }}
                    @if($start->format('Y-m-d') !== $end->format('Y-m-d'))
                        s/d {{ $end->translatedFormat('d F Y') }}
                    @endif
                </small>
            </h3>
            <div class="card-tools">
            
            <a href="{{ route('admin.laporan.export-pdf', request()->all()) }}" 
                        class="btn btn-danger btn-sm" 
                        target="_blank">
                <i class="fas fa-file-pdf"></i> Export PDF
            </a>
            
            </div>
        </div>
        <div class="card-body p-0">
            <table class="table table-bordered table-hover mb-0">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Kode Booking</th>
                        <th>Pelanggan</th>
                        <th>Ruangan</th>
                        <th>Paket</th>
                        <th>Waktu Main</th>
                        <th>Jenis Bayar</th>
                        <th>Total Harga</th>
                        <th>Status Booking</th>
                        <th>Status Bayar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksis as $i => $t)
                    @php
                        $ph = $t->penetapanHarga;
                        $badgeSewa = match($t->status_sewa) {
                            'dikonfirmasi' => 'success',
                            'dibatalkan'   => 'danger',
                            'selesai'      => 'primary',
                            default        => 'secondary',
                        };
                        $badgeBayar = match($t->status_pembayaran) {
                            'lunas'    => 'success',
                            'dp'       => 'info',
                            default    => 'warning',
                        };
                    @endphp
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td><code>{{ $t->kode_sewa }}</code></td>
                        <td>{{ $t->pengguna->nama_pengguna ?? '-' }}</td>
                        <td>{{ $ph->ruangan->nama_ruangan ?? '-' }}</td>
                        <td>{{ $ph->paket->nama_paket ?? '-' }}</td>
                        <td>
                            {{ \Carbon\Carbon::parse($t->waktu_mulai)->format('d/m/Y H:i') }}
                            — {{ \Carbon\Carbon::parse($t->waktu_selesai)->format('H:i') }}
                        </td>
                        <td>
                            <span class="badge badge-{{ $t->opsi_pembayaran === 'full' ? 'primary' : 'info' }}">
                                {{ $t->opsi_pembayaran === 'full' ? 'Full Payment' : 'DP' }}
                            </span>
                        </td>
                        <td>Rp {{ number_format($t->total_harga, 0, ',', '.') }}</td>
                        <td><span class="badge badge-{{ $badgeSewa }}">{{ ucfirst($t->status_sewa) }}</span></td>
                        <td><span class="badge badge-{{ $badgeBayar }}">{{ ucfirst($t->status_pembayaran) }}</span></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center text-muted py-3">
                            Tidak ada data untuk periode ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                @if($transaksis->isNotEmpty())
                <tfoot>
                    <tr>
                        <th colspan="7" class="text-right">Total Pendapatan (Selesai):</th>
                        <th>Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</th>
                        <th colspan="2"></th>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>

@stop

@section('js')
<script>
    const periode = '{{ request('periode', 'harian') }}';

    function updateFilter(val) {
        document.getElementById('filter_tanggal').style.display = 'none';
        document.getElementById('filter_minggu').style.display  = 'none';
        document.getElementById('filter_bulan').style.display   = 'none';

        if (val === 'harian')   document.getElementById('filter_tanggal').style.display = 'block';
        if (val === 'mingguan') document.getElementById('filter_minggu').style.display  = 'block';
        if (val === 'bulanan')  document.getElementById('filter_bulan').style.display   = 'block';
    }

    // Set saat load
    updateFilter(periode);

    // Set saat ganti
    document.getElementById('periode').addEventListener('change', function() {
        updateFilter(this.value);
    });
</script>
@stop