<!-- Modal Menu F&B -->
<div class="modal fade" id="modalFB" tabindex="-1" role="dialog" aria-labelledby="modalFBLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 8px; border: none;">

            <div class="modal-header border-bottom">
                <h6 class="modal-title font-weight-bold text-dark" id="modalFBLabel">
                    <i class="fas fa-shopping-cart mr-1" style="color: #fd7e14;"></i> Menu F&amp;B
                </h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" style="font-size: 24px;">&times;</span>
                </button>
            </div>

            {{-- BODY: satu-satunya area yang scroll (bawaan modal-dialog-scrollable).
                 Dua kolom sama tinggi (.fnb-panel-col), masing-masing scroll sendiri
                 lewat elemen di dalamnya (.fnb-product-grid / .fnb-cart-list). --}}
            <div class="modal-body p-4">
                <div class="row">
                    <div class="col-lg-7 mb-4 mb-lg-0">
                        @include('admin.bookings.partials.fnb.product-picker', [
                            'produks' => $produks ?? collect(),
                            'kategoriFnb' => $kategoriFnb ?? collect(),
                        ])
                    </div>
                    <div class="col-lg-5">
                        @include('admin.bookings.partials.fnb.cart-panel')
                    </div>
                </div>
            </div>

            {{-- FOOTER: fixed bawaan Bootstrap (gak ikut scroll). Border cuma
                 ditaruh di kolom kanan (.fnb-footer-right) supaya kolom kiri
                 tetap "full" tanpa garis, biar leluasa kalau menu nanti nambah. --}}
            <div class="modal-footer p-0" style="border-top: none; display: block;">
                <div class="px-4 py-3">
                    <div class="row">
                        <div class="col-lg-7 d-none d-lg-block"></div>
                        <div class="col-lg-5 fnb-footer-right">
                            @include('admin.bookings.partials.fnb.payment-footer')
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@push('css')
<style>

    .fnb-cart-item-info { flex: 1; min-width: 0; padding-right: 8px; }
    .fnb-tab-btn { border: 1px solid #ced4da; background-color: #fff; border-radius: 20px; padding: 5px 16px; font-size: 0.8rem; color: #6c757d; cursor: pointer; transition: all .15s ease-in-out; }
    .fnb-tab-btn.active { background-color: #6f42c1; border-color: #6f42c1; color: #fff; font-weight: 600; }

    /* Dua kolom sama tinggi, konten di dalamnya scroll independen */
    .fnb-panel-col { height: 420px; display: flex; flex-direction: column; }

    .fnb-product-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(110px, 1fr)); gap: 12px; align-content: start; flex: 1 1 auto; min-height: 0; overflow-y: auto; padding-right: 4px; }
    .fnb-product-card { border: 1px solid #e5e7eb; border-radius: 8px; padding: 6px; cursor: pointer; transition: all .15s ease-in-out; }
    .fnb-product-card:hover { border-color: #6f42c1; box-shadow: 0 2px 6px rgba(111, 66, 193, 0.15); }
    .fnb-product-card.hidden-category { display: none; }
    .fnb-product-img { width: 100%; aspect-ratio: 1 / 1; border-radius: 6px; overflow: hidden; background-color: #f3f4f6; margin-bottom: 6px; }
    .fnb-product-img img { width: 100%; height: 100%; object-fit: cover; }
    .fnb-product-img-placeholder { display: flex; align-items: center; justify-content: center; width: 100%; height: 100%; color: #c7c7c7; font-size: 1.5rem; }
    .fnb-product-name { font-size: 0.8rem; font-weight: 600; color: #212529; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .fnb-product-harga { font-size: 0.75rem; color: #6f42c1; font-weight: 600; }

    .fnb-cart-list { flex: 1 1 auto; min-height: 0; overflow-y: auto; padding-right: 4px; }
    .fnb-cart-item { display: flex; justify-content: space-between; align-items: center; gap: 8px; padding: 8px 0; border-bottom: 1px solid #f3f4f6; }
    .fnb-cart-item-name { font-size: 0.8rem; font-weight: 600; color: #212529; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .fnb-cart-item-harga { font-size: 0.72rem; color: #6c757d; }

    /* Qty stepper: simbol -/+ digambar pakai CSS (bukan karakter teks),
       supaya presisi center secara matematis, gak bergantung metrik font. */
    .fnb-qty-control { display: flex; align-items: center; border: 1px solid #ced4da; border-radius: 6px; overflow: hidden; flex-shrink: 0; }
    .fnb-qty-control button {
        position: relative;
        border: none; background-color: #f8f9fa;
        width: 24px; height: 24px; padding: 0; margin: 0;
        cursor: pointer; box-sizing: border-box;
    }
    .fnb-qty-control button:hover { background-color: #ece9f7; }
    .fnb-qty-control button::before,
    .fnb-qty-control button::after {
        content: '';
        position: absolute;
        top: 50%; left: 50%;
        background-color: #6f42c1;
        transform: translate(-50%, -50%);
    }
    /* garis horizontal, dipakai di tombol minus & plus */
    .fnb-qty-btn::before { width: 10px; height: 2px; }
    /* garis vertikal, cuma di tombol plus, ditumpuk di atas garis horizontal -> jadi "+" */
    .fnb-qty-btn-plus::after { width: 2px; height: 10px; }

    .fnb-qty-control span { width: 24px; text-align: center; font-size: 0.8rem; line-height: 24px; }

    .fnb-cart-item-subtotal { font-size: 0.8rem; font-weight: 700; width: 85px; text-align: right; flex-shrink: 0; }

    .fnb-payment-option { display: flex; align-items: center; gap: 10px; border: 1.5px solid #dcd6f7; border-radius: 8px; padding: 10px 12px; margin-bottom: 8px; cursor: pointer; transition: all .15s ease-in-out; }
    .fnb-payment-option:hover { border-color: #6f42c1; }
    .fnb-payment-option.active { border-color: #6f42c1; background-color: #faf9ff; }
    .fnb-payment-icon { font-size: 1.1rem; color: #6f42c1; width: 26px; text-align: center; flex-shrink: 0; }
    .fnb-payment-label { font-size: 0.85rem; font-weight: 700; color: #212529; }
    .fnb-payment-desc { font-size: 0.72rem; color: #6c757d; }
    .fnb-payment-radio { margin-left: auto; width: 16px; height: 16px; border-radius: 50%; border: 2px solid #ced4da; flex-shrink: 0; }
    .fnb-payment-option.active .fnb-payment-radio { border-color: #6f42c1; background: radial-gradient(circle, #6f42c1 40%, transparent 44%); }

    /* Garis footer cuma di kolom kanan, sesuai lebar kotak metode pembayaran */
    .fnb-footer-right { border-top: 1px solid #dee2e6; padding-top: 16px; }

    @media (max-width: 991.98px) {
        .fnb-panel-col { height: 320px; }
        .fnb-footer-right { border-top: none; padding-top: 0; margin-top: 8px; }
    }
</style>
@endpush

@push('js')
<script>
window.addEventListener('load', function () {
    $(document).ready(function () {
        let fnbCart = {};
        let fnbMetodePembayaran = null;
        let fnbBookingId = null;
        let fnbKodeSewa = null;
        let fnbBookingData = {};

        // Menyinkronkan state cart & metode bayar ke luar closure ini
        // (window.fnbState), supaya modal-rincian.blade.php -- yang punya
        // script terpisah -- bisa baca data terbaru tanpa perlu tahu
        // detail internal variabel di sini.
        function syncFnbState() {
            window.fnbState = {
                bookingId: fnbBookingId,
                kodeSewa: fnbKodeSewa,
                items: Object.keys(fnbCart).map(function (id) {
                    return { id_produk: parseInt(id, 10), jumlah: fnbCart[id].qty };
                }),
                metodePembayaran: fnbMetodePembayaran,
            };
        }

        $('#modalFB').on('show.bs.modal', function (e) {
            const btn = $(e.relatedTarget);

            // Konteks pemicu modal dibedakan eksplisit lewat 2 sinyal, bukan
            // ditebak dari efek samping (mis. ada/tidaknya fungsi lain):
            // - data-id di tombol -> booking yang SUDAH tersimpan di database
            //   (dipicu dari index.blade.php).
            // - window.fnbContext === 'booking-form' -> ditetapkan secara
            //   eksplisit oleh create.blade.php (form "Tambah Booking") tepat
            //   sebelum modal dibuka, menandakan metode bayar F&B harus ikut
            //   metode bayar yang sudah dipilih di form booking tsb.
            // Kalau bukan keduanya (mis. tambah-pesanan.blade.php / F&B
            // manual berdiri sendiri), metode bayar tetap dipilih manual di
            // modal ini.
            const isBookingExisting = !!btn.data('id');
            const isBookingFormContext = window.fnbContext === 'booking-form';
            const isBookingBaru = !isBookingExisting; // dipakai buat label kode sewa saja

            if (isBookingExisting) {
                fnbBookingId = btn.data('id');
                fnbKodeSewa = btn.data('kode');
                fnbBookingData = {
                    nama: btn.data('nama'),
                    email: btn.data('email'),
                    telp: btn.data('telp'),
                    ruangan: btn.data('ruangan'),
                    paket: btn.data('paket'),
                    waktuMulai: btn.data('waktu-mulai'),
                    waktuSelesai: btn.data('waktu-selesai'),
                    durasi: btn.data('durasi'),
                    tipeHari: btn.data('tipe-hari'),
                    totalHarga: parseInt(btn.data('total-harga'), 10) || 0,
                    sisaBayar: parseInt(btn.data('sisa-bayar'), 10) || 0,
                    metodeBayar: btn.data('metode-bayar'),
                };
            } else if (isBookingFormContext && typeof window.fnbGetFormSnapshot === 'function') {
                fnbBookingId = null;
                fnbKodeSewa = null;
                fnbBookingData = window.fnbGetFormSnapshot();
            } else {
                // F&B manual (tambah-pesanan.blade.php): berdiri sendiri,
                // tidak ada booking induk untuk diwarisi datanya.
                fnbBookingId = null;
                fnbKodeSewa = null;
                fnbBookingData = {};
            }

            fnbCart = {};

            // Metode bayar F&B hanya ikut form booking kalau modal memang
            // dibuka dari konteks form booking. Booking existing & F&B
            // manual tetap dipilih manual seperti biasa.
            fnbMetodePembayaran = isBookingFormContext ? (fnbBookingData.metodeBayar || null) : null;

            $('#modalFBKodeSewa').text(fnbKodeSewa || (isBookingBaru ? 'Baru (belum tersimpan)' : '-'));
            $('#fnb-payment-section').toggle(!isBookingFormContext);
            $('.fnb-payment-option').removeClass('active');
            $('#fnb-metode-pembayaran').val(fnbMetodePembayaran || '');
            $('.fnb-tab-btn').removeClass('active');
            $('.fnb-tab-btn[data-kategori="semua"]').addClass('active');
            $('.fnb-product-card').removeClass('hidden-category');

            renderCart();
            syncFnbState();
        });

        $(document).on('click', '.fnb-tab-btn', function () {
            $('.fnb-tab-btn').removeClass('active');
            $(this).addClass('active');
            const kategori = $(this).data('kategori');

            $('.fnb-product-card').each(function () {
                const cocok = kategori === 'semua' || $(this).data('kategori') === kategori;
                $(this).toggleClass('hidden-category', !cocok);
            });
        });

        $(document).on('click', '.fnb-product-card', function () {
            const id = $(this).data('id');
            const nama = $(this).data('nama');
            const harga = parseInt($(this).data('harga'), 10) || 0;

            if (fnbCart[id]) {
                fnbCart[id].qty += 1;
            } else {
                fnbCart[id] = { nama: nama, harga: harga, qty: 1 };
            }
            renderCart();
        });

        $(document).on('click', '.fnb-qty-btn', function () {
            const id = $(this).data('id');
            const delta = parseInt($(this).data('delta'), 10);

            if (!fnbCart[id]) return;
            fnbCart[id].qty += delta;
            if (fnbCart[id].qty <= 0) delete fnbCart[id];
            renderCart();
        });

        function renderCart() {
            const container = $('#fnb-cart-list');
            const ids = Object.keys(fnbCart);

            if (ids.length === 0) {
                container.html('<small class="text-muted" id="fnb-cart-empty">Belum ada item dipilih.</small>');
            } else {
                let html = '';
                ids.forEach(function (id) {
                    const item = fnbCart[id];
                    const subtotal = item.harga * item.qty;
                    html += `
                        <div class="fnb-cart-item">
                            <div class="fnb-cart-item-info">
                                <!-- Tambahkan title="${item.nama}" agar nama lengkap muncul saat di-hover -->
                                <div class="fnb-cart-item-name" title="${item.nama}">${item.nama}</div>
                                <div class="fnb-cart-item-harga">Rp ${item.harga.toLocaleString('id-ID')}</div>
                            </div>
                            <div class="fnb-qty-control">
                                <button type="button" class="fnb-qty-btn fnb-qty-btn-minus" data-id="${id}" data-delta="-1" aria-label="Kurangi"></button>
                                <span>${item.qty}</span>
                                <button type="button" class="fnb-qty-btn fnb-qty-btn-plus" data-id="${id}" data-delta="1" aria-label="Tambah"></button>
                            </div>
                            <div class="fnb-cart-item-subtotal">Rp ${subtotal.toLocaleString('id-ID')}</div>
                        </div>`;
                });
                container.html(html);
            }
            hitungTotal();
            syncFnbState();
        }

        function hitungTotal() {
            let total = 0;
            Object.values(fnbCart).forEach(function (item) {
                total += item.harga * item.qty;
            });

            $('#fnb-total').text('Rp ' + total.toLocaleString('id-ID'));
            const siapSimpan = Object.keys(fnbCart).length > 0 && fnbMetodePembayaran;
            $('#btn-simpan-fnb').prop('disabled', !siapSimpan);

            return total;
        }

        $(document).on('click', '.fnb-payment-option', function () {
            $('.fnb-payment-option').removeClass('active');
            $(this).addClass('active');
            fnbMetodePembayaran = $(this).data('value');
            $('#fnb-metode-pembayaran').val(fnbMetodePembayaran);
            hitungTotal();
            syncFnbState();
        });

        $('#btn-simpan-fnb').on('click', function () {
            const totalFnb = hitungTotal();
            syncFnbState();


            if (window.isFnbManual) {
                    if (typeof window.fnbShowRincianManual === 'function') {
                        window.fnbShowRincianManual(fnbCart);
                    }
                    $('#modalFB').modal('hide');
                    return;
                }
            $('#rincian-kode-sewa').text(fnbKodeSewa || '-');
            $('#rincian-nama').text(fnbBookingData.nama || '-');
            $('#rincian-email').text(fnbBookingData.email || '-');
            $('#rincian-no-hp').text(fnbBookingData.telp || '-');
            $('#rincian-ruangan').text(fnbBookingData.ruangan || '-');
            $('#rincian-paket').text(fnbBookingData.paket || '-');
            $('#rincian-waktu-mulai').text(fnbBookingData.waktuMulai || '-');
            $('#rincian-waktu-selesai').text(fnbBookingData.waktuSelesai || '-');
            $('#rincian-durasi').text((fnbBookingData.durasi || '-') + ' Jam');
            $('#rincian-tipe-hari').text(fnbBookingData.tipeHari || '-');
            $('#rincian-total-harga').text('Rp ' + fnbBookingData.totalHarga.toLocaleString('id-ID'));
            $('#detail-metode-bayar').text(fnbMetodePembayaran || '-');

            let itemsHtml = '';
            Object.values(fnbCart).forEach(function (item) {
                itemsHtml += `
                    <div class="d-flex justify-content-between mb-1" style="font-size: 13px;">
                        <span>${item.qty}x ${item.nama}</span>
                        <span>Rp ${(item.harga * item.qty).toLocaleString('id-ID')}</span>
                    </div>`;
            });
            $('#rincian-fnb-items').html(itemsHtml);
            $('#rincian-total-fnb').text('Rp ' + totalFnb.toLocaleString('id-ID'));
            $('#rincian-fnb-section').show();

            if (fnbBookingData.sisaBayar > 0) {
                $('#rincian-sisa-booking-nominal').text('Rp ' + fnbBookingData.sisaBayar.toLocaleString('id-ID'));
                $('#rincian-sisa-booking-section').show();
            } else {
                $('#rincian-sisa-booking-section').hide();
            }

            // Cek apakah ini booking baru (fnbBookingId null) atau booking existing
            const tagihanBooking = (!fnbBookingId) ? fnbBookingData.totalHarga : fnbBookingData.sisaBayar;
            const grandTotal = tagihanBooking + totalFnb;
            $('#rincian-grand-total').text('Rp ' + grandTotal.toLocaleString('id-ID'));

            $('#modalFB').modal('hide');
            $('#modalDetailPesanan').modal('show');
        });
    }); 
}); 
</script>
@endpush