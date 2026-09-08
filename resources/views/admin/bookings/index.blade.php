@extends('adminlte::page')
@include('partials.sidebar-admin')

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

                {{-- Filter Sumber Booking --}}
                <select name="sumber_booking" class="form-control form-control-sm">
                    <option value="">-- Semua Sumber --</option>
                    <option value="Online" {{ request('sumber_booking') === 'Online' ? 'selected' : '' }}>Online</option>
                    <option value="Kasir"  {{ request('sumber_booking') === 'Kasir'  ? 'selected' : '' }}>Kasir</option>
                </select>

                <select name="status_sewa" class="form-control form-control-sm">
                    <option value="">-- Status Sewa --</option>
                    <option value="ditahan"     {{ request('status_sewa') === 'ditahan'     ? 'selected' : '' }}>Ditahan</option>
                    <option value="dikonfirmasi"{{ request('status_sewa') === 'dikonfirmasi'? 'selected' : '' }}>Dikonfirmasi</option>
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
                <a href="{{ route('admin.booking.create') }}" class="btn btn-sm font-weight-bold" style="background-color: #6f42c1; color: white;">
                    <i class="fas fa-plus"></i> Tambah Booking
                </a>
            </form>
        </div>
    </div>  

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover mb-0 text-center align-middle">
                    <thead class="thead-light">
                        <tr>
                            <th>NO</th>
                            <th>Pelanggan</th>
                            <th>Kode Sewa</th>
                            <th>Ruangan</th>
                            <th>Paket</th>
                            <th>Waktu Mulai</th>
                            <th>Durasi</th>
                            <th>Total</th>
                            <th>Sumber</th>
                            <th>Status Pembayaran</th>
                            <th>Status Sewa</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody> 
                        @forelse($bookings as $booking)
                            @php
                                $ph = $booking->penetapanHarga;
                                $strukSudahDicetak = !empty($booking->struk_created_at);
                            @endphp
                            <tr>
                                <td>{{ $bookings->firstItem() + $loop->index }}</td>

                                <td>
                                    {{ $booking->pengguna->nama_pengguna ?? '-' }}
                                </td>

                                <td><code>{{ $booking->kode_sewa }}</code></td>

                                <td>{{ $ph->ruangan->nama_ruangan ?? '-' }}</td>

                                <td>{{ $ph->paket->nama_paket ?? '-' }}</td>

                                <td>{{ \Carbon\Carbon::parse($booking->waktu_mulai)->format('d/m/Y H:i') }}</td>

                                <td>{{ $ph->durasi_jam ?? '-' }} Jam</td>

                                <td>Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</td>

                                <td>
                                    @if($booking->sumber_booking === 'Online')
                                        <span class="badge text-white py-1 px-3" style="background-color: #0084ff; border-radius: 4px; font-weight: 600; font-size: 0.725rem;">
                                            <i class="fas fa-globe mr-1"></i> ONLINE
                                        </span>
                                    @elseif($booking->sumber_booking === 'Kasir')
                                        <span class="badge text-dark py-1 px-3 border" style="background-color: #f3f4f6; border-color: #d1d5db !important; border-radius: 4px; font-weight: 600; font-size: 0.725rem;">
                                            <i class="fas fa-desktop mr-1 text-muted"></i> KASIR
                                        </span>
                                    @else
                                        <span class="badge text-muted py-1 px-3 border" style="background-color: #ffffff; border-color: #e5e7eb !important; border-radius: 4px; font-weight: 600; font-size: 0.725rem;">
                                            <i class="fas fa-question-circle mr-1"></i> TIDAK DIKETAHUI
                                        </span>
                                    @endif
                                </td>

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
                                        $badgeSewa = $booking->status_sewa === 'dikonfirmasi' ? 'success' : 'secondary';
                                        $labelSewa = $booking->status_sewa === 'dikonfirmasi' ? 'Dikonfirmasi' : 'Ditahan';
                                    @endphp
                                    <span class="badge badge-{{ $badgeSewa }}">{{ $labelSewa }}</span>
                                </td>
                                
                                <td class="align-middle text-center">
                                    <div class="d-flex justify-content-center align-items-center" style="gap: 8px;">

                                        {{-- Tombol Detail — disamakan gaya dengan badge di kolom lain --}}
                                        <a href="{{ route('admin.booking.show', $booking->id_transaksi) }}"
                                            class="badge text-white text-nowrap"
                                            style="background-color: #17a2b8; padding: .35rem .75rem; border-radius: 4px; font-weight: 600; font-size: 0.725rem;">
                                            <i class="fas fa-eye mr-1"></i>Detail
                                        </a>

                                        {{-- Tombol Keranjang F&B — disabled kalau struk udah dicetak
                                             (transaksi dianggap selesai). data-* di bawah dipakai
                                             modal-fb.blade.php buat ngisi modal-rincian, karena di
                                             halaman ini datanya sudah ada di server (bukan dari form). --}}
                                        @php
                                            $labelTipeHariBaris = match($ph->tipe_hari ?? null) {
                                                'harian' => 'Senin - Kamis',
                                                'akhir_pekan' => 'Jumat - Minggu',
                                                'liburan' => 'Hari Libur',
                                                default => '-',
                                            };
                                        @endphp
                                        <button type="button"
                                            class="btn p-0 border-0 bg-transparent flex-shrink-0"
                                            @if(!$strukSudahDicetak)
                                                data-toggle="modal"
                                                data-target="#modalFB"
                                                data-id="{{ $booking->id_transaksi }}"
                                                data-kode="{{ $booking->kode_sewa }}"
                                                data-nama="{{ $booking->pengguna->nama_pengguna ?? '-' }}"
                                                data-email="{{ $booking->pengguna->email ?? '-' }}"
                                                data-telp="{{ $booking->pengguna->no_hp ?? '-' }}"
                                                data-ruangan="{{ $ph->ruangan->nama_ruangan ?? '-' }}"
                                                data-paket="{{ $ph->paket->nama_paket ?? '-' }}"
                                                data-waktu-mulai="{{ \Carbon\Carbon::parse($booking->waktu_mulai)->format('d/m/Y H:i') }}"
                                                data-waktu-selesai="{{ \Carbon\Carbon::parse($booking->waktu_selesai)->format('d/m/Y H:i') }}"
                                                data-durasi="{{ $ph->durasi_jam ?? '-' }}"
                                                data-tipe-hari="{{ $labelTipeHariBaris }}"
                                                data-total-harga="{{ $booking->total_harga ?? 0 }}"
                                                data-sisa-bayar="{{ $booking->sisa_bayar ?? 0 }}"
                                                data-metode-bayar="{{ $booking->metode_pembayaran ?? '-' }}"
                                            @else
                                                disabled
                                            @endif
                                            title="{{ $strukSudahDicetak ? 'Transaksi sudah selesai (struk sudah dicetak)' : 'Tambah Pesanan F&B' }}"
                                            style="line-height: 1; {{ $strukSudahDicetak ? 'cursor: not-allowed;' : '' }}">
                                            <i class="fas fa-shopping-cart"
                                                style="color: {{ $strukSudahDicetak ? '#adb5bd' : '#fd7e14' }}; font-size: 1.1rem;"></i>
                                        </button>

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="text-center text-muted py-3">
                                    Tidak ada data booking.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($bookings->hasPages())
            <div class="card-footer">
                {{ $bookings->links('pagination::bootstrap-4') }}
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

    {{-- 🔹 KARANTINA DIMULAI DI SINI 🔹 --}}
    {{-- $produks & $kategoriFnb dikirim dari controller (data ASLI, dipakai di dalam modal-fb) --}}
     @include('admin.bookings.partials.modal-fb')
    @include('admin.bookings.partials.modal-rincian')


    <iframe id="cetak-struk-iframe"></iframe>

@stop

@section('css')
<style>
    #cetak-struk-iframe {
        position: absolute;
        width: 0;
        height: 0;
        border: 0;
        visibility: hidden;
    }
</style>
@stop

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {

        // ================= Konfirmasi Booking (SweetAlert2) =================
        document.querySelectorAll('.btn-konfirmasi-booking').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var formId = this.dataset.formId;
                var kode = this.dataset.kode;
                konfirmasiAksi({
                    title: 'Konfirmasi booking ' + kode + '?',
                    text: 'Booking ini akan diubah statusnya menjadi dikonfirmasi.',
                    icon: 'question',
                    confirmText: 'Ya, konfirmasi',
                }).then(function (result) {
                    if (result.isConfirmed) {
                        document.getElementById(formId).submit();
                    }
                });
            });
        });

        // ================= Hapus Booking (SweetAlert2) =================
        document.querySelectorAll('.btn-hapus-booking').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var formId = this.dataset.formId;
                var kode = this.dataset.kode;
                konfirmasiHapusSubmit(formId, 'booking ' + kode);
            });
        });

    });

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