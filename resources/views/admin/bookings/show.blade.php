@extends('adminlte::page')

@section('title', 'Detail Booking')

@section('content_header')
    <h1>Detail Booking</h1>
@stop

@section('content')

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @php
        $ph = $booking->penetapanHarga;
    @endphp

    <div class="row">

        {{-- Informasi Booking --}}
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informasi Booking</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.booking.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">

                        {{-- Info Pelanggan --}}
                        <div class="col-md-6">
                            <h5 class="text-muted mb-3">Informasi Pelanggan</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="130">Nama</th>
                                    <td>{{ $booking->pengguna->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ $booking->pengguna->email ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>No. HP</th>
                                    <td>{{ $booking->pengguna->no_hp ?? $booking->pengguna->phone ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>

                        {{-- Detail Booking --}}
                        <div class="col-md-6">
                            <h5 class="text-muted mb-3">Detail Booking</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="130">Kode Sewa</th>
                                    <td><code>{{ $booking->kode_sewa }}</code></td>
                                </tr>
                                <tr>
                                    <th>Ruangan</th>
                                    <td>{{ $ph->ruangan->nama_ruangan ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Kategori</th>
                                    <td>{{ $ph->ruangan->kategori ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Paket</th>
                                    <td>{{ $ph->paket->nama_paket ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Waktu Mulai</th>
                                    <td>{{ \Carbon\Carbon::parse($booking->waktu_mulai)->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Waktu Selesai</th>
                                    <td>{{ \Carbon\Carbon::parse($booking->waktu_selesai)->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Durasi</th>
                                    <td>{{ $ph->durasi_jam ?? '-' }} Jam</td>
                                </tr>
                                <tr>
                                    <th>Tipe Hari</th>
                                    <td>{{ ucfirst($ph->tipe_hari ?? '-') }}</td>
                                </tr>
                                <tr>
                                    <th>Total Harga</th>
                                    <td>Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <th>Opsi Bayar</th>
                                    <td>{{ $booking->opsi_pembayaran === 'full' ? 'Full Payment' : 'DP' }}</td>
                                </tr>
                                @if($booking->opsi_pembayaran === 'dp')
                                <tr>
                                    <th>Jumlah DP</th>
                                    <td>Rp {{ number_format($booking->jumlah_dp, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <th>Sisa Bayar</th>
                                    <td>Rp {{ number_format($booking->sisa_bayar, 0, ',', '.') }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <th>Status Sewa</th>
                                    <td>
                                        @php
                                            $badgeSewa = match($booking->status_sewa) {
                                                'dikonfirmasi' => 'success',
                                                'dibatalkan'   => 'danger',
                                                'selesai'      => 'primary',
                                                default        => 'secondary',
                                            };
                                        @endphp
                                        <span class="badge badge-{{ $badgeSewa }}">
                                            {{ ucfirst($booking->status_sewa) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Status Bayar</th>
                                    <td>
                                        @php
                                            $badgeBayar = match($booking->status_pembayaran) {
                                                'lunas'    => 'success',
                                                'dp'       => 'info',
                                                default    => 'warning',
                                            };
                                        @endphp
                                        <span class="badge badge-{{ $badgeBayar }}">
                                            {{ ucfirst($booking->status_pembayaran) }}
                                        </span>
                                    </td>
                                </tr>
                                @if($booking->catatan_pembayaran)
                                <tr>
                                    <th>Catatan</th>
                                    <td>{{ $booking->catatan_pembayaran }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Panel Aksi --}}
        <div class="col-md-4">

            {{-- Input Pembayaran --}}
            @if(in_array($booking->status_sewa, ['ditahan', 'dikonfirmasi']) && $booking->status_pembayaran !== 'lunas')
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h3 class="card-title">Update Pembayaran</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.booking.pembayaran', $booking->id_transaksi) }}" method="POST">
                        @csrf @method('PATCH')
                        <div class="form-group">
                            <label>Total Harga</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="text" class="form-control"
                                    value="{{ number_format($booking->total_harga, 0, ',', '.') }}" disabled>
                            </div>
                        </div>
                        @if($booking->opsi_pembayaran === 'dp')
                        <div class="form-group">
                            <label>Sisa Bayar</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="text" class="form-control"
                                    value="{{ number_format($booking->sisa_bayar, 0, ',', '.') }}" disabled>
                            </div>
                        </div>
                        @endif
                        <div class="form-group">
                            <label>Catatan Pembayaran</label>
                            <textarea name="catatan_pembayaran" class="form-control" rows="2"
                                placeholder="Opsional">{{ $booking->catatan_pembayaran }}</textarea>
                        </div>
                        <div class="form-group">
                            <label>Tandai Sebagai</label>
                            <select name="status_pembayaran" class="form-control" required>
                                <option value="dp"    {{ $booking->status_pembayaran === 'dp'    ? 'selected' : '' }}>DP</option>
                                <option value="lunas" {{ $booking->status_pembayaran === 'lunas' ? 'selected' : '' }}>Lunas</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success btn-block">
                            <i class="fas fa-save"></i> Simpan Pembayaran
                        </button>
                    </form>
                </div>
            </div>
            @endif

            {{-- Aksi Booking --}}
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title">Aksi Booking</h3>
                </div>
                <div class="card-body">

                    @if($booking->status_sewa === 'ditahan')
                        {{-- Konfirmasi --}}
                        <form action="{{ route('admin.booking.konfirmasi', $booking->id_transaksi) }}"
                            method="POST" class="mb-2"
                            onsubmit="return confirm('Konfirmasi booking {{ $booking->kode_sewa }}?')">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-success btn-block">
                                <i class="fas fa-check"></i> Konfirmasi Booking
                            </button>
                        </form>

                        {{-- Tolak --}}
                        <button type="button" class="btn btn-danger btn-block"
                            data-toggle="modal" data-target="#modalTolak"
                            data-id="{{ $booking->id_transaksi }}"
                            data-kode="{{ $booking->kode_sewa }}">
                            <i class="fas fa-times"></i> Tolak Booking
                        </button>

                    @elseif($booking->status_sewa === 'dikonfirmasi')
                        {{-- Selesai --}}
                        <form action="{{ route('admin.booking.selesai', $booking->id_transaksi) }}"
                            method="POST"
                            onsubmit="return confirm('Tandai booking ini selesai?')">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-info btn-block">
                                <i class="fas fa-flag-checkered"></i> Tandai Selesai
                            </button>
                        </form>

                    @else
                        <p class="text-muted text-center mb-0">
                            Tidak ada aksi tersedia untuk status <strong>{{ $booking->status_sewa }}</strong>.
                        </p>
                    @endif

                </div>
            </div>

        </div>
    </div>

    {{-- Modal Tolak --}}
    <div class="modal fade" id="modalTolak" tabindex="-1">
        <div class="modal-dialog">
            <form id="formTolak" method="POST">
                @csrf @method('PATCH')
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title">Tolak Booking</h5>
                        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
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
                        <button type="submit" class="btn btn-danger">Tolak Booking</button>
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
        $(this).find('#formTolak').attr('action', '/admin/booking/' + id + '/tolak');
        $(this).find('#modalKode').text(kode);
    });
</script>
@stop