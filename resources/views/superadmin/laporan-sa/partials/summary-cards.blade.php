{{-- Area Filter — dirapikan jadi 4 field: Periode, Rentang Tanggal, Jenis Transaksi, Status Transaksi --}}
<div class="card">
    <div class="card-body">
        <form method="GET" action="{{ route('superadmin.laporan.index') }}" class="row align-items-end">

            {{-- Periode --}}
            <div class="col-md-3">
                <div class="form-group mb-0">
                    <label>Periode</label>
                    <select name="periode" class="form-control">
                        <option value="harian"   {{ request('periode', 'harian') === 'harian'   ? 'selected' : '' }}>Harian</option>
                        <option value="mingguan" {{ request('periode') === 'mingguan' ? 'selected' : '' }}>Mingguan</option>
                        <option value="bulanan"  {{ request('periode') === 'bulanan'  ? 'selected' : '' }}>Bulanan</option>
                    </select>
                </div>
            </div>

            {{-- Rentang Tanggal — pakai daterangepicker aktif, lihat @section('js') di index.blade.php --}}
            <div class="col-md-3">
                <div class="form-group mb-0">
                    <label>Rentang Tanggal</label>
                    <input
                        type="text"
                        name="rentang_tanggal"
                        id="rentang_tanggal"
                        class="form-control"
                        autocomplete="off"
                        value="{{ request('rentang_tanggal', now()->startOfMonth()->format('d M Y') . ' - ' . now()->endOfMonth()->format('d M Y')) }}"
                    >
                </div>
            </div>

            {{-- Jenis Transaksi --}}
            <div class="col-md-2">
                <div class="form-group mb-0">
                    <label>Jenis Transaksi</label>
                    <select name="jenis_transaksi" class="form-control">
                        <option value="semua"   {{ request('jenis_transaksi', 'semua') === 'semua'   ? 'selected' : '' }}>Semua</option>
                        <option value="booking" {{ request('jenis_transaksi') === 'booking' ? 'selected' : '' }}>Booking</option>
                        <option value="fnb"     {{ request('jenis_transaksi') === 'fnb'     ? 'selected' : '' }}>F&amp;B</option>
                    </select>
                </div>
            </div>

            {{-- Status Transaksi — 3 opsi sesuai badge terbaru --}}
            <div class="col-md-2">
                <div class="form-group mb-0">
                    <label>Status Transaksi</label>
                    <select name="status_transaksi" class="form-control">
                        <option value="">Semua</option>
                        <option value="selesai"    {{ request('status_transaksi') === 'selesai'    ? 'selected' : '' }}>Selesai</option>
                        <option value="dibatalkan" {{ request('status_transaksi') === 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                        <option value="refund"     {{ request('status_transaksi') === 'refund'     ? 'selected' : '' }}>Refund</option>
                    </select>
                </div>
            </div>

            {{-- Tombol Filter & Reset --}}
            <div class="col-md-2 d-flex" style="gap: 8px;">
                <button type="submit" class="btn btn-primary flex-fill">
                    <i class="fas fa-search"></i> Filter
                </button>
                <a href="{{ route('superadmin.laporan.index') }}" class="btn btn-secondary flex-fill">
                    Reset
                </a>
            </div>

        </form>
    </div>
</div>