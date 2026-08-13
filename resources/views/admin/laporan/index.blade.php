@extends('adminlte::page')
@include('partials.sidebar-admin')
@section('title', 'Riwayat Transaksi')

@section('content_header')
    <h1>Riwayat Transaksi</h1>
@stop

@section('content')

    {{-- Filter --}}
    <div class="card">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.laporan.index') }}">
                <div class="row align-items-end">

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

                    <div class="col-md-2" id="filter_tanggal">
                        <div class="form-group mb-0">
                            <label>Tanggal</label>
                            <input type="date" name="tanggal" class="form-control"
                                value="{{ request('tanggal', now()->format('Y-m-d')) }}">
                        </div>
                    </div>

                    <div class="col-md-2" id="filter_minggu" style="display:none">
                        <div class="form-group mb-0">
                            <label>Minggu</label>
                            <input type="week" name="minggu" class="form-control"
                                value="{{ request('minggu', now()->format('Y-\WW')) }}">
                        </div>
                    </div>

                    <div class="col-md-2" id="filter_bulan" style="display:none">
                        <div class="form-group mb-0">
                            <label>Bulan</label>
                            <input type="month" name="bulan" class="form-control"
                                value="{{ request('bulan', now()->format('Y-m')) }}">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group mb-0">
                            <label>Sumber Pesanan</label>
                            <select name="sumber" class="form-control">
                                <option value="">Semua</option>
                                <option value="Kasir"  {{ request('sumber') === 'Kasir'  ? 'selected' : '' }}>Kasir</option>
                                <option value="Online" {{ request('sumber') === 'Online' ? 'selected' : '' }}>Online</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group mb-0">
                            <label>Jenis Transaksi</label>
                            <select name="jenis_transaksi" class="form-control">
                                <option value="semua"   {{ request('jenis_transaksi','semua') === 'semua'   ? 'selected' : '' }}>Semua</option>
                                <option value="booking" {{ request('jenis_transaksi') === 'booking' ? 'selected' : '' }}>Booking</option>
                                <option value="fnb"     {{ request('jenis_transaksi') === 'fnb'     ? 'selected' : '' }}>F&amp;B</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2 mt-2">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-search"></i> Tampilkan
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>

    @include('admin.laporan.partials.summary-cards')

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
                    class="btn btn-danger btn-sm" target="_blank">
                    <i class="fas fa-file-pdf"></i> Export PDF
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            @include('admin.laporan.partials.table')
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

    updateFilter(periode);
    document.getElementById('periode').addEventListener('change', function() {
        updateFilter(this.value);
    });
</script>
@stop