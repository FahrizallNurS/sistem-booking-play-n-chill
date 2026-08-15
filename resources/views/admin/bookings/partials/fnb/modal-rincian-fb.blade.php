<!-- Modal Rincian Pesanan F&B Mandiri (tanpa booking) -->
<div class="modal fade" id="modalRincianFb" tabindex="-1" role="dialog" aria-labelledby="modalRincianFbLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 8px; border: none;">

            <div class="modal-header border-bottom">
                <h6 class="modal-title font-weight-bold text-dark" id="modalRincianFbLabel">Detail Pesanan</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" style="font-size: 24px;">&times;</span>
                </button>
            </div>

            <div class="modal-body p-4">
                <div class="row mb-4">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <h6 class="text-muted mb-3" style="font-size: 14px;">Informasi Transaksi</h6>

                        <div class="row mb-2" style="font-size: 13px;">
                            <div class="col-4 font-weight-bold text-dark">ID POS</div>
                            <div class="col-8 text-dark" id="rincian-fb-kode-pos">Akan digenerate otomatis</div>
                        </div>
                        <div class="row mb-2" style="font-size: 13px;">
                            <div class="col-4 font-weight-bold text-dark">Nama</div>
                            <div class="col-8 text-dark text-break" id="rincian-fb-nama">-</div>
                        </div>
                        <div class="row mb-2" style="font-size: 13px;">
                            <div class="col-4 font-weight-bold text-dark">No. Telp</div>
                            <div class="col-8 text-dark text-break" id="rincian-fb-no-telp">-</div>
                        </div>
                        <div class="row mb-2" style="font-size: 13px;">
                            <div class="col-4 font-weight-bold text-dark">Tanggal</div>
                            <div class="col-8 text-dark" id="rincian-fb-tanggal">-</div>
                        </div>
                        <div class="row mb-2" style="font-size: 13px;">
                            <div class="col-4 font-weight-bold text-dark">Metode Bayar</div>
                            <div class="col-8 text-dark font-weight-bold text-uppercase" id="rincian-fb-metode-bayar">-</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted mb-1" style="font-size: 14px;">Catatan</label>
                        <div class="text-dark border rounded p-2" id="rincian-fb-catatan" style="min-height: 110px; font-size: 13px; white-space: pre-wrap;">-</div>
                    </div>
                </div>

                <h6 class="text-muted mb-3" style="font-size: 14px;">Rincian Pesanan</h6>
                <div class="table-responsive">
                    <table class="table table-borderless mb-0" style="font-size: 13px;">
                        <thead>
                            <tr class="text-muted border-bottom" style="font-size: 12px;">
                                <th style="width: 5%;">No</th>
                                <th>Produk</th>
                                <th class="text-right">Harga</th>
                                <th class="text-center">Jumlah</th>
                                <th class="text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="rincian-fb-items"></tbody>
                    </table>
                </div>

                <div class="border-top pt-3 mt-2 text-right">
                    <div class="text-muted" style="font-size: 13px;">
                        Subtotal: <span id="rincian-fb-subtotal">Rp 0</span>
                    </div>
                    <div class="font-weight-bold text-dark" style="font-size: 16px;">
                        Total: <span style="color: #6f42c1;" id="rincian-fb-total">Rp 0</span>
                    </div>
                </div>
            </div>

            <div class="modal-footer bg-light border-top-0">
                <button type="button" class="btn btn-outline-secondary font-weight-bold mr-2" data-dismiss="modal">Kembali / Cek Lagi</button>

                <button type="button" id="btn-buat-pesanan-fb" class="btn font-weight-bold" style="background-color: #22c55e; color: #fff;">
                    <i class="fas fa-receipt mr-1"></i> Buat Pesanan
                </button>

                <button type="button" id="btn-cetak-struk-fb" class="btn btn-secondary font-weight-bold d-none">
                    <i class="fas fa-print mr-1"></i> Cetak Struk
                </button>
            </div>
        </div>
    </div>
</div>

@push('js')
<script>
$(document).ready(function () {
    let rincianFbCart = {};
    let rincianFbPosData = null; // hasil sukses simpan (kode_pos, pdf_url), dipakai pas Cetak Struk

    // Extension point ini dipanggil dari modal-fb.blade.php pas tombol
    // "Simpan" diklik DAN konteksnya F&B mandiri (window.isFnbManual).
    // fnbCart dikirim langsung sebagai argumen karena butuh nama & harga per
    // item untuk ditampilkan di tabel -- window.fnbState tidak menyimpan itu
    // (cuma id_produk & jumlah, sengaja diringkas untuk payload submit).
    window.fnbShowRincianManual = function (cart) {
        rincianFbCart = cart;
        rincianFbPosData = null;

        const state = window.fnbState || {};

        $('#rincian-fb-kode-pos').text('Akan digenerate otomatis');
        $('#rincian-fb-nama').text($('input[name="nama_pelanggan"]').val() || '-');
        $('#rincian-fb-no-telp').text($('input[name="no_telp"]').val() || '-');
        $('#rincian-fb-catatan').text($('textarea[name="catatan"]').val() || '-');
        $('#rincian-fb-tanggal').text(new Date().toLocaleString('id-ID', { dateStyle: 'medium', timeStyle: 'short' }));
        $('#rincian-fb-metode-bayar').text(state.metodePembayaran || '-');

        let html = '';
        let total = 0;
        let no = 1;

        Object.keys(cart).forEach(function (id) {
            const item = cart[id];
            const subtotal = item.harga * item.qty;
            total += subtotal;

            html += `
                <tr>
                    <td>${no}</td>
                    <td>${item.nama}</td>
                    <td class="text-right">Rp ${item.harga.toLocaleString('id-ID')}</td>
                    <td class="text-center">${item.qty}</td>
                    <td class="text-right">Rp ${subtotal.toLocaleString('id-ID')}</td>
                </tr>`;
            no++;
        });

        $('#rincian-fb-items').html(html);
        $('#rincian-fb-subtotal').text('Rp ' + total.toLocaleString('id-ID'));
        $('#rincian-fb-total').text('Rp ' + total.toLocaleString('id-ID'));

        $('#btn-buat-pesanan-fb').removeClass('d-none').prop('disabled', false)
            .html('<i class="fas fa-receipt mr-1"></i> Buat Pesanan');
        $('#btn-cetak-struk-fb').addClass('d-none');

        $('#modalRincianFb').modal('show');
    };

    $('#btn-buat-pesanan-fb').on('click', function () {
        const btn = $(this);
        const state = window.fnbState || {};

        const items = Object.keys(rincianFbCart).map(function (id) {
            return { id_produk: parseInt(id, 10), jumlah: rincianFbCart[id].qty };
        });

        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Memproses...');

        $.ajax({
            url: '{{ route('admin.fb.transaksi.store') }}',
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: {
                nama_pelanggan: $('input[name="nama_pelanggan"]').val(),
                no_telp: $('input[name="no_telp"]').val(),
                catatan: $('textarea[name="catatan"]').val(),
                metode_pembayaran: state.metodePembayaran,
                items: items,
            },
            success: function (res) {
                if (res.success) {
                    rincianFbPosData = res.data;
                    $('#rincian-fb-kode-pos').text(res.data.kode_pos);
                    btn.addClass('d-none');
                    $('#btn-cetak-struk-fb').removeClass('d-none');
                }
            },
            error: function (xhr) {
                const msg = xhr.responseJSON?.errors
                    ? Object.values(xhr.responseJSON.errors).flat().join('\n')
                    : 'Gagal menyimpan pesanan. Silakan coba lagi.';
                alert(msg);
                btn.prop('disabled', false).html('<i class="fas fa-receipt mr-1"></i> Buat Pesanan');
            }
        });
    });

    // Beda dari btn-cetak-struk booking: PDF sudah jadi di step "Buat
    // Pesanan" sebelumnya, jadi di sini tinggal window.open langsung,
    // gak perlu trik tab-kosong + AJAX lagi.
    $('#btn-cetak-struk-fb').on('click', function () {
        if (rincianFbPosData && rincianFbPosData.pdf_url) {
            window.open(rincianFbPosData.pdf_url, '_blank');
        }
    });
});
</script>
@endpush