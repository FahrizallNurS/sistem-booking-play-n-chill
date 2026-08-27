@extends('adminlte::page')
@include('partials.sidebar-admin')
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
                        <a href="{{ url()->previous() }}" class="btn btn-secondary btn-sm">
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
                                    <td>{{ $booking->pengguna->nama_pengguna ?? '-' }}</td>
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
                                                'refund'   => 'dark',
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
                            </table> {{-- 🔹 TUTUP TABEL UTAMANYA DI SINI 🔹 --}}

                            {{-- 🔹 MULAI: RINCIAN PESANAN F&B 🔹 --}}
                            @if(isset($pos) && count($posDetails) > 0)
                                <hr class="mt-4 mb-4" style="border-top: 1px dashed #d1d5db;">
                                
                                <h5 class="text-muted mb-3" style="font-size: 1rem;">Rincian Pesanan F&B</h5>
                                
                                <table class="table table-borderless table-sm mb-0">
                                    @foreach($posDetails as $item)
                                    <tr>
                                        <td class="px-0 text-dark" style="font-size: 0.95rem;">{{ $item['qty'] }}x {{ $item['name'] }}</td>
                                        <td class="px-0 text-right text-dark" style="font-size: 0.95rem;">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                    
                                    <tr>
                                        <td colspan="2" class="px-0 text-right pt-3">
                                            <h6 class="font-weight-bold text-dark mb-0" style="font-size: 1rem;">
                                                Total F&B: Rp {{ number_format($pos->total_pos, 0, ',', '.') }}
                                            </h6>
                                        </td>
                                    </tr>
                                </table>
                            @endif
                        <!-- </div>  -->
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Panel Aksi --}}
        <div class="col-md-4">

            {{-- Struk --}}
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h3 class="card-title"><i class="fas fa-receipt mr-1"></i> Struk</h3>
                </div>
                <div class="card-body">
                    @if($booking->status_pembayaran !== 'lunas')
                        <button type="button" class="btn btn-secondary btn-block" disabled
                            title="Struk hanya tersedia setelah status pembayaran lunas">
                            <i class="fas fa-print mr-1"></i> Cetak Struk
                        </button>
                        <small class="text-muted d-block text-center mt-2">
                            Tersedia setelah pembayaran berstatus <strong>Lunas</strong>.
                        </small>
                    @elseif(!$booking->struk_created_at)
                        <button type="button" id="btn-cetak-struk-show" class="btn btn-success btn-block"
                            data-toggle="modal" data-target="#modalMetodeBayarStruk">
                            <i class="fas fa-print mr-1"></i> Cetak Struk
                        </button>
                        <small class="text-muted d-block text-center mt-2">
                            Belum pernah dicetak.
                        </small>
                    @else
                        <button type="button" id="btn-cetak-ulang-struk" class="btn btn-success btn-block"
                            data-pdf-url="{{ asset('assets/struk/' . $booking->kode_sewa . '.pdf') }}">
                            <i class="fas fa-print mr-1"></i> Cetak Ulang
                        </button>
                        <a href="{{ asset('assets/struk/' . $booking->kode_sewa . '.pdf') }}" target="_blank"
                            class="btn btn-outline-success btn-block btn-sm mt-2">
                            <i class="fas fa-receipt mr-1"></i> Lihat Struk (PDF)
                        </a>
                        <small class="text-muted d-block text-center mt-2">
                            Pertama kali dicetak: {{ \Carbon\Carbon::parse($booking->struk_created_at)->format('d/m/Y H:i') }}
                        </small>
                    @endif
                </div>
            </div>

            {{-- Update Pembayaran --}}
            @if(in_array($booking->status_sewa, ['ditahan', 'dikonfirmasi', 'selesai']) && $booking->status_pembayaran !== 'lunas')
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h3 class="card-title"><i class="fas fa-money-bill-wave mr-1"></i> Update Pembayaran</h3>
                </div>
                <div class="card-body">
                    <form id="formPembayaran"
                        action="{{ route('admin.booking.pembayaran', $booking->id_transaksi) }}" method="POST">
                        @csrf @method('PATCH')

                        <div class="form-group">
                            <label class="font-weight-bold text-muted small mb-1">Total Harga</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="text" class="form-control bg-light"
                                    value="{{ number_format($booking->total_harga, 0, ',', '.') }}" disabled>
                            </div>
                        </div>

                        @if($booking->opsi_pembayaran === 'dp')
                        <div class="form-group">
                            <label class="font-weight-bold text-muted small mb-1">Sisa Bayar</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="text" class="form-control bg-light text-danger font-weight-bold"
                                    value="{{ number_format($booking->sisa_bayar, 0, ',', '.') }}" disabled>
                            </div>
                        </div>
                        @endif

                        <div class="form-group">
                            <label class="font-weight-bold text-muted small mb-1">Catatan Pembayaran</label>
                            <textarea name="catatan_pembayaran" class="form-control" rows="2"
                                placeholder="Opsional">{{ $booking->catatan_pembayaran }}</textarea>
                        </div>

                        <div class="form-group mb-3">
                            <label class="font-weight-bold text-muted small mb-1">Tandai Sebagai</label>
                            <select name="status_pembayaran" class="form-control" required>
                                <option value="dp"    {{ $booking->status_pembayaran === 'dp'    ? 'selected' : '' }}>DP</option>
                                <option value="lunas" {{ $booking->status_pembayaran === 'lunas' ? 'selected' : '' }}>Lunas</option>
                            </select>
                        </div>

                        <button type="button" class="btn btn-success btn-block btn-simpan-pembayaran">
                            <i class="fas fa-save mr-1"></i> Simpan Pembayaran
                        </button>
                    </form>
                </div>
            </div>
            @endif

            {{-- Aksi Booking --}}
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h3 class="card-title"><i class="fas fa-tasks mr-1"></i> Aksi Booking</h3>
                </div>
                <div class="card-body">

                     @if(in_array($booking->status_sewa, ['ditahan', 'dikonfirmasi']))
                        <button type="button" class="btn btn-outline-primary btn-block mb-2"
                            data-toggle="modal" data-target="#modalUbahJadwal"
                            data-id="{{ $booking->id_transaksi }}"
                            data-kode="{{ $booking->kode_sewa }}"
                            data-waktu-mulai="{{ \Carbon\Carbon::parse($booking->waktu_mulai)->format('Y-m-d\TH:i') }}">
                            <i class="fas fa-calendar-alt mr-1"></i> Ubah Jadwal
                        </button>
                        <hr class="my-2">
                    @endif

                    @if($booking->status_sewa === 'ditahan')
                        <div class="d-flex flex-column" style="gap: 8px;">
                            <form id="formKonfirmasiShow"
                                action="{{ route('admin.booking.konfirmasi', $booking->id_transaksi) }}"
                                method="POST" class="m-0">
                                @csrf @method('PATCH')
                                <button type="button" class="btn btn-success btn-block btn-konfirmasi-booking-show"
                                    data-kode="{{ $booking->kode_sewa }}">
                                    <i class="fas fa-check mr-1"></i> Konfirmasi Booking
                                </button>
                            </form>

                            <button type="button" class="btn btn-outline-danger btn-block"
                                data-toggle="modal" data-target="#modalTolak"
                                data-id="{{ $booking->id_transaksi }}"
                                data-kode="{{ $booking->kode_sewa }}">
                                <i class="fas fa-times mr-1"></i> Tolak Booking
                            </button>
                        </div>

                    @elseif($booking->status_sewa === 'dikonfirmasi')
                        <div class="d-flex flex-column" style="gap: 8px;">
                            <form id="formSelesai"
                                action="{{ route('admin.booking.selesai', $booking->id_transaksi) }}"
                                method="POST" class="m-0">
                                @csrf @method('PATCH')
                                <button type="button" class="btn btn-success btn-block btn-selesai-booking">
                                    <i class="fas fa-flag-checkered mr-1"></i> Tandai Selesai
                                </button>
                            </form>

                            <hr class="my-1">

                            <button type="button" class="btn btn-outline-danger btn-block"
                                data-toggle="modal" data-target="#modalBatalkan"
                                data-id="{{ $booking->id_transaksi }}"
                                data-kode="{{ $booking->kode_sewa }}">
                                <i class="fas fa-ban mr-1"></i> Refund Booking
                            </button>
                        </div>

                    @else
                        <div class="text-center text-muted py-2">
                            <i class="fas fa-info-circle mb-1" style="font-size: 1.25rem;"></i>
                            <p class="mb-0">
                                Tidak ada aksi tersedia untuk status<br>
                                <strong class="text-dark">{{ ucfirst($booking->status_sewa) }}</strong>
                            </p>
                        </div>
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

        <div class="modal fade" id="modalUbahJadwal" tabindex="-1">
        <div class="modal-dialog">
            <form id="formUbahJadwal" method="POST">
                @csrf @method('PATCH')
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Ubah Jadwal Booking</h5>
                        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <p>Booking: <strong id="modalKodeJadwal"></strong></p>
                        <div class="form-group">
                            <label>Waktu Mulai Baru <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="waktu_mulai" id="inputWaktuMulaiBaru"
                                class="form-control" required>
                            <small class="text-muted d-block mt-1">
                                Durasi ({{ $ph->durasi_jam ?? '-' }} jam) dan ruangan tidak berubah.
                                Waktu selesai dihitung ulang otomatis.
                            </small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i> Simpan Jadwal
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Refund --}}
    <div class="modal fade" id="modalBatalkan" tabindex="-1">
        <div class="modal-dialog">
            <form id="formBatalkan" method="POST">
                @csrf @method('PATCH')
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title">Refund Booking</h5>
                        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <p>Booking: <strong id="modalKodeBatal"></strong></p>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-1"></i>
                            Booking yang sudah dikonfirmasi akan direfund. Tindakan ini tidak bisa dibatalkan.
                        </div>
                        <div class="form-group">
                            <label>Alasan Refund <span class="text-danger">*</span></label>
                            <textarea name="catatan_pembatalan" class="form-control" rows="3"
                                placeholder="Tulis alasan refund..." required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-ban"></i> Refund Booking
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Pilih Metode Pembayaran (khusus cetak struk pertama kali dari
         halaman ini). Ditempatkan terpisah dari modal-fb karena halaman ini
         SENGAJA tidak menyertakan pesanan F&B (lihat catatan di footer modal
         di bawah) -- cetak struk di sini murni untuk booking ruangan saja. --}}
    <div class="modal fade" id="modalMetodeBayarStruk" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title"><i class="fas fa-print mr-1"></i> Cetak Struk</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p class="mb-3">
                        Booking: <strong>{{ $booking->kode_sewa }}</strong><br>
                        <small class="text-muted">
                            Pilih metode pembayaran yang dipakai untuk pelunasan ini.
                            Metode bisa berbeda dari metode pembayaran awal booking
                            (mis. DP online lalu dilunasi tunai di kasir).
                        </small>
                    </p>
                    <div class="form-group mb-0">
                        <label class="font-weight-bold text-muted small mb-2 d-block">Metode Pembayaran</label>
                        <div class="d-flex" style="gap: 10px;">
                            <button type="button" class="btn btn-outline-secondary flex-fill btn-metode-bayar-struk" data-value="TUNAI">
                                <i class="fas fa-money-bill-wave mr-1"></i> Tunai
                            </button>
                            <button type="button" class="btn btn-outline-secondary flex-fill btn-metode-bayar-struk" data-value="QRIS">
                                <i class="fas fa-qrcode mr-1"></i> QRIS
                            </button>
                        </div>
                        <input type="hidden" id="inputMetodeBayarStruk" value="">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" id="btn-konfirmasi-cetak-struk" class="btn btn-success" disabled>
                        <i class="fas fa-print mr-1"></i> Cetak Sekarang
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Iframe struk disembunyikan tapi tetap "hidup" (bukan display:none)
         supaya window.print() dari dalam iframe tetap bisa jalan di semua
         browser. Pola sama seperti create.blade.php & index.blade.php. --}}
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
    .btn-metode-bayar-struk.active-metode {
        background-color: #6f42c1;
        border-color: #6f42c1;
        color: #fff;
    }
</style>
@stop

@section('js')
<script>

    document.addEventListener('DOMContentLoaded', function () {

        // ================= Simpan Pembayaran (SweetAlert2) =================
        const btnSimpanPembayaran = document.querySelector('.btn-simpan-pembayaran');
        if (btnSimpanPembayaran) {
            btnSimpanPembayaran.addEventListener('click', function () {
                konfirmasiAksi({
                    title: 'Simpan perubahan pembayaran?',
                    text: 'Status dan catatan pembayaran akan diperbarui.',
                    icon: 'question',
                    confirmText: 'Ya, simpan',
                }).then(function (result) {
                    if (result.isConfirmed) {
                        document.getElementById('formPembayaran').submit();
                    }
                });
            });
        }

        // ================= Konfirmasi Booking (SweetAlert2) =================
        const btnKonfirmasiShow = document.querySelector('.btn-konfirmasi-booking-show');
        if (btnKonfirmasiShow) {
            btnKonfirmasiShow.addEventListener('click', function () {
                var kode = this.dataset.kode;
                konfirmasiAksi({
                    title: 'Konfirmasi booking ' + kode + '?',
                    text: 'Booking ini akan diubah statusnya menjadi dikonfirmasi.',
                    icon: 'question',
                    confirmText: 'Ya, konfirmasi',
                }).then(function (result) {
                    if (result.isConfirmed) {
                        document.getElementById('formKonfirmasiShow').submit();
                    }
                });
            });
        }

        // ================= Tandai Selesai (SweetAlert2) =================
        const btnSelesai = document.querySelector('.btn-selesai-booking');
        if (btnSelesai) {
            btnSelesai.addEventListener('click', function () {
                konfirmasiAksi({
                    title: 'Tandai booking ini selesai?',
                    text: 'Status booking akan diubah menjadi selesai.',
                    icon: 'question',
                    confirmText: 'Ya, selesai',
                }).then(function (result) {
                    if (result.isConfirmed) {
                        document.getElementById('formSelesai').submit();
                    }
                });
            });
        }

        // ================= Cetak Struk (pilih metode bayar) =================
        $(document).on('click', '.btn-metode-bayar-struk', function () {
            $('.btn-metode-bayar-struk').removeClass('active-metode');
            $(this).addClass('active-metode');
            $('#inputMetodeBayarStruk').val($(this).data('value'));
            $('#btn-konfirmasi-cetak-struk').prop('disabled', false);
        });

        // Reset pilihan tiap modal dibuka ulang, biar gak kebawa dari
        // percobaan sebelumnya (mis. abis klik Batal).
        $('#modalMetodeBayarStruk').on('show.bs.modal', function () {
            $('.btn-metode-bayar-struk').removeClass('active-metode');
            $('#inputMetodeBayarStruk').val('');
            $('#btn-konfirmasi-cetak-struk').prop('disabled', true)
                .html('<i class="fas fa-print mr-1"></i> Cetak Sekarang');
        });

        function triggerPrintStrukShow(onDone) {
            const iframe = document.getElementById('cetak-struk-iframe');
            if (!iframe || !iframe.contentWindow) return;

            const win = iframe.contentWindow;
            let done = false;

            const finish = function () {
                if (done) return; // guard biar callback nggak double-fire
                done = true;
                win.removeEventListener('afterprint', finish);
                clearTimeout(fallbackTimer);
                if (typeof onDone === 'function') onDone();
            };

            // Fallback safety net kalau afterprint nggak nembak (mis. Safari lama)
            const fallbackTimer = setTimeout(finish, 8000);

            win.addEventListener('afterprint', finish);
            win.focus();
            win.print();
        }

        // admin tinggal klik tombol ini lagi tanpa batas.
        const btnCetakUlang = document.getElementById('btn-cetak-ulang-struk');
        if (btnCetakUlang) {
            btnCetakUlang.addEventListener('click', function () {
                const iframe = document.getElementById('cetak-struk-iframe');
                const pdfUrl = this.dataset.pdfUrl;
                if (!iframe || !pdfUrl) return;

                const btn = $(this);
                const originalHtml = btn.html();
                btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Menyiapkan...');

                iframe.onload = function () {
                    triggerPrintStrukShow(function () {
                        btn.prop('disabled', false).html(originalHtml);
                    });
                };
                iframe.src = pdfUrl + '?t=' + Date.now();
            });
        }

        $('#btn-konfirmasi-cetak-struk').on('click', function () {
        const metodePembayaran = $('#inputMetodeBayarStruk').val();
        if (!metodePembayaran) return;

        const btn = $(this);
        const iframe = document.getElementById('cetak-struk-iframe');

        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Memproses...');

        $.ajax({
            url: '{{ route('admin.booking.cetak-struk', $booking->id_transaksi) }}',
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: {
                metode_pembayaran: metodePembayaran,
                items: [],
            },
            success: function (res) {
                if (res.success && iframe) {
                    iframe.onload = function () {
                        triggerPrintStrukShow(function () {
                            location.reload();
                        });
                    };
                    iframe.src = res.data.pdf_url;
                }
            },
            error: function (xhr) {
                const msg = xhr.responseJSON?.errors
                    ? Object.values(xhr.responseJSON.errors).flat().join('\n')
                    : 'Gagal mencetak struk. Silakan coba lagi.';
                alert(msg);
                btn.prop('disabled', false).html('<i class="fas fa-print mr-1"></i> Cetak Sekarang');
            }
        });
    });

    });

    $('#modalTolak').on('show.bs.modal', function (e) {
        var btn  = $(e.relatedTarget);
        var id   = btn.data('id');
        var kode = btn.data('kode');
        $(this).find('#formTolak').attr('action', '/admin/booking/' + id + '/tolak');
        $(this).find('#modalKode').text(kode);
    });

    $('#modalBatalkan').on('show.bs.modal', function (e) {
        var btn  = $(e.relatedTarget);
        var id   = btn.data('id');
        var kode = btn.data('kode');
        $(this).find('#formBatalkan').attr('action', '/admin/booking/' + id + '/batalkan');
        $(this).find('#modalKodeBatal').text(kode);
    });

    $('#modalUbahJadwal').on('show.bs.modal', function (e) {
    var btn        = $(e.relatedTarget);
    var id         = btn.data('id');
    var kode       = btn.data('kode');
    var waktuMulai = btn.data('waktu-mulai');
    $(this).find('#formUbahJadwal').attr('action', '/admin/booking/' + id + '/ubah-jadwal');
    $(this).find('#modalKodeJadwal').text(kode);
    $(this).find('#inputWaktuMulaiBaru').val(waktuMulai);
    });
    
</script>
@stop