<!-- Modal Detail Pesanan -->
<div class="modal fade" id="modalDetailPesanan" tabindex="-1" role="dialog" aria-labelledby="modalDetailLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 8px; border: none;">

            {{-- Header Modal --}}
            <div class="modal-header border-bottom">
                <h6 class="modal-title font-weight-bold text-dark" id="modalDetailLabel">Konfirmasi Pesanan</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" style="font-size: 24px;">&times;</span>
                </button>
            </div>

            {{-- Body Modal (Otomatis Scrollable jika konten panjang) --}}
            <div class="modal-body p-4">
                <div class="row">

                    {{-- Kiri: Informasi Pelanggan --}}
                    <div class="col-md-5 mb-4 mb-md-0">
                        <h6 class="text-muted mb-3" style="font-size: 14px;">Informasi Pelanggan</h6>

                        <div class="row mb-2" style="font-size: 13px;">
                            <div class="col-4 font-weight-bold text-dark">Nama</div>
                            <div class="col-8 text-dark text-break" id="rincian-nama">-</div>
                        </div>
                        <div class="row mb-2" style="font-size: 13px;">
                            <div class="col-4 font-weight-bold text-dark">Email</div>
                            <div class="col-8 text-dark text-break" id="rincian-email">-</div>
                        </div>
                        <div class="row mb-2" style="font-size: 13px;">
                            <div class="col-4 font-weight-bold text-dark">No. HP</div>
                            <div class="col-8 text-dark text-break" id="rincian-no-hp">-</div>
                        </div>
                    </div>

                    {{-- Kanan: Detail Booking --}}
                    <div class="col-md-7 pl-md-4">
                        <h6 class="text-muted mb-3" style="font-size: 14px;">Detail Booking</h6>

                        <div class="row mb-2" style="font-size: 13px;">
                            <div class="col-4 font-weight-bold text-dark">Kode Sewa</div>
                            <div class="col-8 font-style-italic text-muted text-break">
                                Akan digenerate otomatis setelah disimpan
                            </div>
                        </div>
                        <div class="row mb-2" style="font-size: 13px;">
                            <div class="col-4 font-weight-bold text-dark">Ruangan</div>
                            <div class="col-8 text-dark text-break" id="rincian-ruangan">-</div>
                        </div>
                        <div class="row mb-2" style="font-size: 13px;">
                            <div class="col-4 font-weight-bold text-dark">Paket</div>
                            <div class="col-8 text-dark text-break" id="rincian-paket">-</div>
                        </div>
                        <div class="row mb-2" style="font-size: 13px;">
                            <div class="col-4 font-weight-bold text-dark">Waktu Mulai</div>
                            <div class="col-8 text-dark text-break" id="rincian-waktu-mulai">-</div>
                        </div>
                        <div class="row mb-2" style="font-size: 13px;">
                            <div class="col-4 font-weight-bold text-dark">Waktu Selesai</div>
                            <div class="col-8 text-dark text-break" id="rincian-waktu-selesai">-</div>
                        </div>
                        <div class="row mb-2" style="font-size: 13px;">
                            <div class="col-4 font-weight-bold text-dark">Durasi</div>
                            <div class="col-8 text-dark text-break" id="rincian-durasi">-</div>
                        </div>
                        <div class="row mb-2" style="font-size: 13px;">
                            <div class="col-4 font-weight-bold text-dark">Tipe Hari</div>
                            <div class="col-8 text-dark text-break" id="rincian-tipe-hari">-</div>
                        </div>
                        <div class="row mb-2" style="font-size: 13px;">
                            <div class="col-4 font-weight-bold text-dark">Total Harga</div>
                            <div class="col-8 text-dark text-break font-weight-bold" id="rincian-total-harga">-</div>
                        </div>
                        <div class="row mb-2 align-items-center" style="font-size: 13px;">
                            <div class="col-4 font-weight-bold text-dark">Status Sewa</div>
                            <div class="col-8">
                                <span class="badge badge-success py-1 px-2">Dikonfirmasi</span>
                            </div>
                        </div>
                        <div class="row mb-2 align-items-center" style="font-size: 13px;">
                            <div class="col-4 font-weight-bold text-dark">Status Bayar</div>
                            <div class="col-8">
                                <span class="badge badge-success py-1 px-2">Lunas</span>
                            </div>
                        </div>
                        <div class="row mb-4 align-items-center" style="font-size: 13px;">
                            <div class="col-4 font-weight-bold text-dark">Metode Pembayaran</div>
                            <div class="col-8 text-dark text-break font-weight-bold text-uppercase" id="detail-metode-bayar">
                                -
                            </div>
                        </div>

                        <div class="border-top pt-4 mb-3"></div>

                        {{-- Sisa Booking: muncul cuma di skenario booking DP online yang
                             dilunasin di kasir. Baris ini terkunci (bukan item F&B),
                             angkanya diisi dari sisi JS pas alur checkout F&B dipicu.
                             Sengaja disembunyikan default — belum ada trigger backend
                             yang ngisi ini, menyusul di task checkout F&B. --}}
                        <div id="rincian-sisa-booking-section" style="display: none;">
                            <div class="d-flex justify-content-between align-items-center mb-2" style="font-size: 13px;">
                                <span class="font-weight-bold text-dark">Sisa Booking (belum lunas)</span>
                                <span class="font-weight-bold" id="rincian-sisa-booking-nominal" style="color: #dc3545;">Rp 0</span>
                            </div>
                        </div>

                        {{-- Rincian F&B: diisi lewat JS di modal-fb.blade.php pas klik
                             "Simpan Pesanan" --}}
                        <div id="rincian-fnb-section" style="display: none;">
                            <h6 class="text-muted mb-3" style="font-size: 14px;">Rincian Pesanan F&B</h6>
                            <div id="rincian-fnb-items"></div>
                            <div class="text-right border-top pt-3">
                                <span class="font-weight-bold text-dark" style="font-size: 13px;">
                                    Total F&B: <span id="rincian-total-fnb">Rp 0</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer bg-light border-top-0 d-flex justify-content-between">
                <div class="d-flex align-items-center">
                    <h5 class="mb-0 font-weight-bold text-dark mr-2">Grand Total:</h5>
                    <h5 class="mb-0 font-weight-bold" style="color: #6f42c1;" id="rincian-grand-total">Rp 0</h5>
                </div>
                <div>
                    <button type="button" class="btn btn-outline-secondary font-weight-bold mr-2" data-dismiss="modal">Kembali / Cek Lagi</button>
                   {{-- Tombol Cetak (Muncul duluan) --}}
                    <button type="button" id="btn-cetak-struk" class="btn btn-secondary font-weight-bold">
                        <i class="fas fa-print mr-1"></i> Cetak Struk
                    </button>
                    
                    {{-- Tombol Selesai (Disembunyikan pake d-none) --}}
                    <a href="{{ route('admin.booking.index') }}" id="btn-selesai" class="btn btn-success font-weight-bold shadow-sm d-none">
                        <i class="fas fa-check-circle mr-1"></i> Selesai
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@push('js')
<script>
$(document).on('click', '#btn-cetak-struk', function () {
    const state = window.fnbState;
    const btn = $(this);

    // Titik ekstensi: halaman lain (mis. create.blade.php, alur "Tambah
    // Booking" yang belum punya id_transaksi) bisa mendaftarkan strategi
    // submit sendiri lewat window.fnbSubmitOverride, tanpa file ini perlu
    // tahu detail form/endpoint halaman tersebut. Kalau tidak ada yang
    // mendaftar, perilaku default di bawah ini (submit ke cetak-struk booking
    // yang sudah ada) tetap berjalan seperti biasa.
    if (typeof window.fnbSubmitOverride === 'function') {
        window.fnbSubmitOverride(btn, state);
        return;
    }

    if (!state || !state.bookingId) {
        alert('Data pesanan tidak ditemukan. Silakan ulangi dari awal.');
        return;
    }

    // PENTING: window.open() harus dipanggil di sini, LANGSUNG di dalam
    // event klik (synchronous) -- BUKAN di dalam callback AJAX (async).
    // Kalau dipanggil setelah nunggu response server, browser anggap ini
    // bukan aksi user asli dan memblokirnya (kadang malah redirect tab
    // aktif, bukan cuma diblokir diam-diam). Trik-nya: buka tab kosong
    // dulu sekarang, baru isi alamatnya (location.href) setelah PDF siap.
    const strukWindow = window.open('', '_blank');

    btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Memproses...');

    $.ajax({
        url: '/admin/booking/' + state.bookingId + '/cetak-struk',
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: {
            metode_pembayaran: state.metodePembayaran,
            items: state.items,
        },
        success: function (res) {
            if (res.success) {
                if (strukWindow) {
                    strukWindow.location.href = res.data.pdf_url;
                } else {
                    // Fallback kalau tab kosong tadi ternyata tetap diblokir
                    window.open(res.data.pdf_url, '_blank');
                }
                btn.addClass('d-none');
                $('#btn-selesai').removeClass('d-none');
            }
        },
        error: function (xhr) {
            if (strukWindow) strukWindow.close(); // tutup tab kosong kalau gagal
            const msg = xhr.responseJSON?.errors
                ? Object.values(xhr.responseJSON.errors).flat().join('\n')
                : 'Gagal mencetak struk. Silakan coba lagi.';
            alert(msg);
            btn.prop('disabled', false).html('<i class="fas fa-print mr-1"></i> Cetak Struk');
        }
    });
});
</script>
@endpush