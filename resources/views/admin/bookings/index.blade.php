@extends('adminlte::page')

@section('title', 'Kelola Booking')

@section('content_header')
    <h1>Kelola Booking</h1>
@stop

@section('content')

    @if(session('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {{ session('error') }}
        </div>
    @endif

    {{-- Filter --}}
    <div class="card card-outline card-secondary mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.booking.index') }}" class="form-inline flex-wrap" style="gap: 8px;">
                <input type="text" name="search" value="{{ request('search') }}"
                    class="form-control form-control-sm" placeholder="Cari kode / nama...">

                <select name="status_sewa" class="form-control form-control-sm">
                    <option value="">-- Status Sewa --</option>
                    <option value="ditahan"     {{ request('status_sewa') === 'ditahan'     ? 'selected' : '' }}>Ditahan</option>
                    <option value="dikonfirmasi"{{ request('status_sewa') === 'dikonfirmasi'? 'selected' : '' }}>Dikonfirmasi</option>
                    <option value="dibatalkan"  {{ request('status_sewa') === 'dibatalkan'  ? 'selected' : '' }}>Dibatalkan</option>
                    <option value="selesai"     {{ request('status_sewa') === 'selesai'     ? 'selected' : '' }}>Selesai</option>
                </select>

                <select name="status_pembayaran" class="form-control form-control-sm">
                    <option value="">-- Status Pembayaran --</option>
                    <option value="menunggu" {{ request('status_pembayaran') === 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                    <option value="dp"       {{ request('status_pembayaran') === 'dp'       ? 'selected' : '' }}>DP</option>
                    <option value="lunas"    {{ request('status_pembayaran') === 'lunas'    ? 'selected' : '' }}>Lunas</option>
                </select>

                <input type="date" name="tanggal" value="{{ request('tanggal') }}"
                    class="form-control form-control-sm">

                <select name="ruangan" class="form-control form-control-sm">
                    <option value="">-- Semua Ruangan --</option>
                    @foreach($ruangans as $r)
                        <option value="{{ $r->id_ruangan }}" {{ request('ruangan') == $r->id_ruangan ? 'selected' : '' }}>
                            {{ $r->nama_ruangan }}
                        </option>
                    @endforeach
                </select>

                <button type="submit" class="btn btn-sm btn-primary">
                    <i class="fas fa-search"></i> Filter
                </button>
                <a href="{{ route('admin.booking.index') }}" class="btn btn-sm btn-secondary">
                    <i class="fas fa-sync"></i> Reset
                </a>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Booking</h3>
            <div class="card-tools">
                <a href="{{ route('admin.booking.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Tambah Booking Manual
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <table class="table table-bordered table-hover mb-0">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Pelanggan</th>
                        <th>Kode Sewa</th>
                        <th>Ruangan</th>
                        <th>Paket</th>
                        <th>Waktu Mulai</th>
                        <th>Durasi</th>
                        <th>Total</th>
                        <th>Status Pembayaran</th>
                        <th>Status Sewa</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                        @php
                            $ph = $booking->penetapanHarga;
                        @endphp
                        <tr>
                            <td>{{ $bookings->firstItem() + $loop->index }}</td>

                            <td>
                                {{ $booking->pengguna->name ?? '-' }}
                            </td>

                            <td><code>{{ $booking->kode_sewa }}</code></td>

                            <td>{{ $ph->ruangan->nama_ruangan ?? '-' }}</td>

                            <td>{{ $ph->paket->nama_paket ?? '-' }}</td>

                            <td>{{ \Carbon\Carbon::parse($booking->waktu_mulai)->format('d/m/Y H:i') }}</td>

                            <td>{{ $ph->durasi_jam ?? '-' }} Jam</td>

                            <td>Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</td>

                            {{-- Status Pembayaran --}}
                            <td>
                                @php
                                    $badgePembayaran = match($booking->status_pembayaran) {
                                        'lunas'    => 'success',
                                        'dp'       => 'info',
                                        default    => 'warning',
                                    };
                                    $labelPembayaran = match($booking->status_pembayaran) {
                                        'lunas'    => 'Lunas',
                                        'dp'       => 'DP',
                                        default    => 'Menunggu',
                                    };
                                @endphp
                                <span class="badge badge-{{ $badgePembayaran }}">{{ $labelPembayaran }}</span>
                            </td>

                            {{-- Status Sewa --}}
                            <td>
                                @php
                                    $badgeSewa = match($booking->status_sewa) {
                                        'dikonfirmasi' => 'success',
                                        'dibatalkan'   => 'danger',
                                        'selesai'      => 'primary',
                                        default        => 'secondary',
                                    };
                                    $labelSewa = match($booking->status_sewa) {
                                        'dikonfirmasi' => 'Dikonfirmasi',
                                        'dibatalkan'   => 'Dibatalkan',
                                        'selesai'      => 'Selesai',
                                        default        => 'Ditahan',
                                    };
                                @endphp
                                <span class="badge badge-{{ $badgeSewa }}">{{ $labelSewa }}</span>
                            </td>

                            {{-- Aksi --}}
                            <td>
                                <a href="{{ route('admin.booking.show', $booking->id_transaksi) }}"
                                    class="btn btn-info btn-xs">
                                    <i class="fas fa-eye"></i> Detail
                                </a>

                                @if($booking->status_sewa === 'ditahan')
                                    {{-- Konfirmasi --}}
                                    <form action="{{ route('admin.booking.konfirmasi', $booking->id_transaksi) }}"
                                        method="POST" class="d-inline"
                                        onsubmit="return confirm('Konfirmasi booking {{ $booking->kode_sewa }}?')">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn btn-success btn-xs">
                                            <i class="fas fa-check"></i> Konfirmasi
                                        </button>
                                    </form>

                                    {{-- Tolak --}}
                                    <button type="button" class="btn btn-warning btn-xs"
                                        data-toggle="modal"
                                        data-target="#modalTolak"
                                        data-id="{{ $booking->id_transaksi }}"
                                        data-kode="{{ $booking->kode_sewa }}">
                                        <i class="fas fa-times"></i> Tolak
                                    </button>

                                    {{-- Hapus --}}
                                    <form action="{{ route('admin.booking.destroy', $booking->id_transaksi) }}"
                                        method="POST" class="d-inline"
                                        onsubmit="return confirm('Hapus booking {{ $booking->kode_sewa }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-xs">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-center text-muted py-3">
                                Tidak ada data booking.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($bookings->hasPages())
            <div class="card-footer">
                {{ $bookings->links() }}
            </div>
        @endif
    </div>

    {{-- Modal Tolak --}}
    <div class="modal fade" id="modalTolak" tabindex="-1">
        <div class="modal-dialog">
            <form id="formTolak" method="POST">
                @csrf @method('PATCH')
                <div class="modal-content">
                    <div class="modal-header bg-warning">
                        <h5 class="modal-title">Tolak Booking</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <p>Booking: <strong id="modalKode"></strong></p>
                        <div class="form-group">
                            <label>Alasan Penolakan <span class="text-danger">*</span></label>
                            <textarea name="alasan_tolak" class="form-control" rows="3"
                                placeholder="Tulis alasan penolakan..." required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning">Tolak Booking</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@stop

@section('js')
<script>
    $('#modalTolak').on('show.bs.modal', function (e) {
        var btn  = $(e.relatedTarget);
        var id   = btn.data('id');
        var kode = btn.data('kode');
        var url  = '/admin/booking/' + id + '/tolak';
        $(this).find('#formTolak').attr('action', url);
        $(this).find('#modalKode').text(kode);
    });
</script>
@stop