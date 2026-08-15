@extends('adminlte::page')
@include('partials.sidebar-admin')
@section('title', 'Tambah Booking')

@section('content_header')
    <h1>Tambah Booking</h1>
@stop

@section('content')

    {{-- $ruangans & $pakets dikirim dari controller (data ASLI, bukan dummy) --}}

    <div id="alert-container"></div>

    @include('admin.bookings.partials.create.form-pelanggan')
    @include('admin.bookings.partials.create.form-booking')

    {{-- Tombol Aksi --}}
    <div class="d-flex flex-wrap justify-content-end" style="gap: 10px; margin-top: 1.5rem; margin-bottom: 2rem;">
        <button type="button" id="btnTambahFnb" class="btn font-weight-bold px-4"
            style="background-color: #fd7e14; color: #fff;" title="Tambah pesanan F&B ke booking ini">
            <i class="fas fa-shopping-cart mr-1"></i> Tambahkan pesanan F&B
        </button>
        <button type="button" id="btnBookingTanpaFnb" class="btn font-weight-bold px-4"
            style="background-color: #ece9f7; color: #6f42c1;">
            <i class="fas fa-clipboard-list mr-1"></i> Buat booking tanpa pesanan F&B
        </button>
    </div>

    @include('admin.bookings.partials.modal-rincian')
    @include('admin.bookings.partials.modal-fb')

    <iframe id="cetak-struk-iframe"></iframe>

@stop

@section('css')
<style>
    .btn-payment-option {
        height: calc(1.5em + .75rem + 2px);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        transition: all .15s ease-in-out;
    }
    .btn-payment-option.active-payment {
        background-color: #6f42c1;
        border-color: #6f42c1;
        color: #fff;
    }
    .btn-payment-option:not(.active-payment) {
        background-color: #fff;
        border-color: #ced4da;
        color: #6c757d;
    }

    /* ============= Dropdown Ruangan & Paket ============= */
    .select-pnc {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        background-color: #fff;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 8'%3E%3Cpath fill='%236f42c1' d='M1 1l5 5 5-5'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 14px center;
        background-size: 11px 7px;
        padding-right: 36px;
        border: 1px solid #ced4da;
        border-radius: 6px;
        height: calc(1.5em + .75rem + 2px);
        cursor: pointer;
        transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
    }
    .select-pnc:focus {
        border-color: #6f42c1;
        box-shadow: 0 0 0 0.2rem rgba(111, 66, 193, 0.15);
        outline: none;
    }
    .select-pnc:hover {
        border-color: #b8aee0;
    }

    /* ============= Kartu Durasi & Harga ============= */
    #durasi-options .btn-durasi-option {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 3px;
        min-width: 132px;
        padding: 10px 18px;
        border: 1.5px solid #dcd6f7;
        border-radius: 10px;
        background-color: #fff;
        cursor: pointer;
        transition: all .15s ease-in-out;
    }
    #durasi-options .btn-durasi-option:hover {
        border-color: #6f42c1;
        background-color: #faf9ff;
    }
    #durasi-options .btn-durasi-option .durasi-option-jam {
        font-weight: 700;
        font-size: 0.95rem;
        color: #2c2c2c;
        line-height: 1.2;
    }
    #durasi-options .btn-durasi-option .durasi-option-tipe {
        font-size: 0.75rem;
        color: #6c757d;
        line-height: 1.2;
    }
    #durasi-options .btn-durasi-option .durasi-option-harga {
        font-weight: 600;
        font-size: 0.85rem;
        color: #6f42c1;
        margin-top: 3px;
        line-height: 1.2;
    }
    #durasi-options .btn-durasi-option.active-durasi {
        background-color: #6f42c1;
        border-color: #6f42c1;
    }
    #durasi-options .btn-durasi-option.active-durasi .durasi-option-jam,
    #durasi-options .btn-durasi-option.active-durasi .durasi-option-tipe,
    #durasi-options .btn-durasi-option.active-durasi .durasi-option-harga {
        color: #fff;
    }

    /* Iframe struk disembunyikan tapi tetap "hidup" (bukan display:none)
       supaya window.print() dari dalam iframe tetap bisa jalan di semua browser */
    #cetak-struk-iframe {
        position: absolute;
        width: 0;
        height: 0;
        border: 0;
        visibility: hidden;
    }

    /* Saat print, hanya tampilkan area struk (#print-area) di dalam modal */
    @media print {
        body * { visibility: hidden; }
        #print-area, #print-area * { visibility: visible; }
        #print-area {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
        }
    }
</style>
@stop

@section('js')
<script>
$(document).ready(function () {

    const penetapanHargaUrl = "{{ route('admin.booking.penetapan-harga') }}";
    const storeUrl = "{{ route('admin.booking.manual.store') }}";
    const csrfToken = "{{ csrf_token() }}";

    let selectedPricing = null; // { id_penetapan_harga, durasi_jam, tipe_hari, harga }

    // Label ramah-pengguna untuk tipe_hari (samakan dengan mapping di struk)
    const tipeHariLabels = {
        'harian':      'Senin - Kamis',
        'akhir_pekan': 'Jumat - Minggu',
        'liburan':     'Hari Libur',
    };

    function labelTipeHari(raw) {
        return tipeHariLabels[raw] || raw;
    }

    // ================= Toggle Metode Pembayaran =================
    $('.btn-payment-option').on('click', function () {
        $('.btn-payment-option').removeClass('active-payment');
        $(this).addClass('active-payment');
        $('#metode_pembayaran').val($(this).data('value'));
    });

    // ================= Fetch kombinasi durasi+tipe_hari+harga saat Ruangan/Paket berubah =================
    function muatOpsiDurasi() {
        const idRuangan = $('#select_ruangan').val();
        const idPaket   = $('#select_paket').val();

        selectedPricing = null;
        $('#id_penetapan_harga').val('');
        $('#waktu_selesai_preview').val('-');

        const container = $('#durasi-options');
        container.empty();

        if (!idRuangan || !idPaket) {
            container.html('<small class="text-muted">Pilih ruangan &amp; paket terlebih dahulu.</small>');
            return;
        }

        container.html('<small class="text-muted"><i class="fas fa-spinner fa-spin mr-1"></i> Memuat opsi harga...</small>');

        $.getJSON(penetapanHargaUrl, { ruangan: idRuangan, paket: idPaket })
            .done(function (res) {
                container.empty();

                if (!res.options || res.options.length === 0) {
                    container.html('<small class="text-danger">Tidak ada kombinasi durasi/harga untuk ruangan &amp; paket ini.</small>');
                    return;
                }

                res.options.forEach(function (opt) {
                    const tipeHari = labelTipeHari(opt.tipe_hari);
                    const hargaFormatted = Number(opt.harga).toLocaleString('id-ID');

                    const btn = $('<button type="button" class="btn-durasi-option"></button>')
                        .attr('data-id', opt.id_penetapan_harga)
                        .attr('data-durasi', opt.durasi_jam)
                        .attr('data-tipe-hari', tipeHari)
                        .attr('data-harga', opt.harga);

                    btn.append($('<span class="durasi-option-jam"></span>').text(opt.durasi_jam + ' Jam'));
                    btn.append($('<span class="durasi-option-tipe"></span>').text(tipeHari));
                    btn.append($('<span class="durasi-option-harga"></span>').text('Rp ' + hargaFormatted));

                    container.append(btn);
                });
            })
            .fail(function () {
                container.html('<small class="text-danger">Gagal memuat data harga. Coba lagi.</small>');
            });
    }

    $('#select_ruangan, #select_paket').on('change', muatOpsiDurasi);

    // ================= Pilih salah satu opsi durasi =================
    $(document).on('click', '.btn-durasi-option', function () {
        $('.btn-durasi-option').removeClass('active-durasi');
        $(this).addClass('active-durasi');

        selectedPricing = {
            id_penetapan_harga: $(this).data('id'),
            durasi_jam: $(this).data('durasi'),
            tipe_hari: $(this).data('tipe-hari'),
            harga: $(this).data('harga'),
        };

        $('#id_penetapan_harga').val(selectedPricing.id_penetapan_harga);
        updateWaktuSelesaiPreview();
    });

    // ================= Preview Waktu Selesai (otomatis dari Waktu Mulai + Durasi) =================
    function updateWaktuSelesaiPreview() {
        const waktuMulai = $('#waktu_mulai').val(); // format: YYYY-MM-DDTHH:mm
        if (!waktuMulai || !selectedPricing) {
            $('#waktu_selesai_preview').val('-');
            return;
        }

        const mulai = new Date(waktuMulai);
        mulai.setHours(mulai.getHours() + parseInt(selectedPricing.durasi_jam, 10));

        const pad = n => String(n).padStart(2, '0');
        const formatted = pad(mulai.getDate()) + '/' + pad(mulai.getMonth() + 1) + '/' + mulai.getFullYear() +
            ' ' + pad(mulai.getHours()) + ':' + pad(mulai.getMinutes());

        $('#waktu_selesai_preview').val(formatted);
    }

    $('#waktu_mulai').on('change', updateWaktuSelesaiPreview);

    // ================= Alert helper =================
    function tampilkanAlert(type, message) {
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>`;
        $('#alert-container').html(alertHtml);
        $('html, body').animate({ scrollTop: 0 }, 300);
    }

    // ================= Validasi sebelum tampilkan modal rincian =================
    function validasiForm() {
        if (!$('input[name="nama_pelanggan"]').val().trim()) {
            tampilkanAlert('danger', 'Nama pelanggan wajib diisi.');
            return false;
        }
        if (!$('#select_ruangan').val()) {
            tampilkanAlert('danger', 'Silakan pilih ruangan.');
            return false;
        }
        if (!$('#select_paket').val()) {
            tampilkanAlert('danger', 'Silakan pilih paket.');
            return false;
        }
        if (!selectedPricing) {
            tampilkanAlert('danger', 'Silakan pilih durasi & harga.');
            return false;
        }
        if (!$('#waktu_mulai').val()) {
            tampilkanAlert('danger', 'Silakan isi waktu mulai.');
            return false;
        }
        if (!$('#metode_pembayaran').val()) {
            tampilkanAlert('danger', 'Silakan pilih metode pembayaran (Tunai / QRIS).');
            return false;
        }
        return true;
    }

    // ================= Isi preview modal rincian dari data form saat ini =================
    function isiModalRincian() {
        const namaRuangan = $('#select_ruangan option:selected').text();
        const namaPaket   = $('#select_paket option:selected').text();
        const waktuMulaiRaw = $('#waktu_mulai').val();

        $('#rincian-kode-sewa').text('Akan digenerate otomatis setelah disimpan');
        $('#rincian-nama').text($('input[name="nama_pelanggan"]').val() || '-');
        $('#rincian-email').text($('input[name="email"]').val() || '-');
        $('#rincian-no-hp').text($('input[name="no_telp"]').val() || '-');
        $('#rincian-ruangan').text(namaRuangan);
        $('#rincian-paket').text(namaPaket);

        if (waktuMulaiRaw) {
            const d = new Date(waktuMulaiRaw);
            const pad = n => String(n).padStart(2, '0');
            $('#rincian-waktu-mulai').text(pad(d.getDate()) + '/' + pad(d.getMonth() + 1) + '/' + d.getFullYear() +
                ' ' + pad(d.getHours()) + ':' + pad(d.getMinutes()));
        }

        $('#rincian-waktu-selesai').text($('#waktu_selesai_preview').val());
        $('#rincian-durasi').text(selectedPricing.durasi_jam + ' Jam');
        $('#rincian-tipe-hari').text(selectedPricing.tipe_hari);
        $('#rincian-total-harga').text('Rp ' + Number(selectedPricing.harga).toLocaleString('id-ID'));
        $('#rincian-grand-total').text('Rp ' + Number(selectedPricing.harga).toLocaleString('id-ID'));
        $('#detail-metode-bayar').text($('#metode_pembayaran').val());
    }

    // ================= Status "sudah tersimpan" untuk modal rincian =================
    // Admin bisa buat beberapa booking berturut-turut di halaman ini tanpa
    // refresh (lihat resetForm()). Flag ini yang membedakan klik pertama
    // pada "Cetak Struk" (submit booking baru) dari klik berikutnya pada
    // tombol yang sama setelah labelnya berubah jadi "Cetak Ulang" (print
    // ulang saja, jangan submit dobel). Direset tiap kali admin mulai alur
    // booking baru lagi.
    let bookingSudahTersimpan = false;

    function resetRincianModalState() {
        bookingSudahTersimpan = false;
        $('#btn-selesai').addClass('d-none');
        $('#btn-cetak-struk').html('<i class="fas fa-print mr-1"></i> Cetak Struk').prop('disabled', false);
    }

    $('#btnBookingTanpaFnb').on('click', function () {
        if (!validasiForm()) return;

        // Pastikan tidak ada sisa item F&B / status "sudah tersimpan" dari
        // booking sebelumnya di halaman yang sama.
        window.fnbState = null;
        resetRincianModalState();

        isiModalRincian();
        $('#modalDetailPesanan').modal('show');
    });

    // ================= Snapshot data form, dipakai modal F&B saat dibuka dari halaman ini =================
    // modal-fb.blade.php dipakai bersama index.blade.php (tambah F&B ke booking
    // yang SUDAH ada di database, datanya dari data-* attribute baris tabel).
    // Di halaman ini booking belum tersimpan, jadi kita sediakan data ringkasan
    // dari form yang sedang diisi lewat fungsi global ini.
    window.fnbGetFormSnapshot = function () {
        const waktuMulaiRaw = $('#waktu_mulai').val();
        let waktuMulaiFormatted = '-';

        if (waktuMulaiRaw) {
            const d = new Date(waktuMulaiRaw);
            const pad = n => String(n).padStart(2, '0');
            waktuMulaiFormatted = pad(d.getDate()) + '/' + pad(d.getMonth() + 1) + '/' + d.getFullYear() +
                ' ' + pad(d.getHours()) + ':' + pad(d.getMinutes());
        }

        return {
            nama: $('input[name="nama_pelanggan"]').val() || '-',
            email: $('input[name="email"]').val() || '-',
            telp: $('input[name="no_telp"]').val() || '-',
            ruangan: $('#select_ruangan option:selected').text(),
            paket: $('#select_paket option:selected').text(),
            waktuMulai: waktuMulaiFormatted,
            waktuSelesai: $('#waktu_selesai_preview').val(),
            durasi: selectedPricing ? selectedPricing.durasi_jam : '-',
            tipeHari: selectedPricing ? labelTipeHari(selectedPricing.tipe_hari) : '-',
            totalHarga: selectedPricing ? (parseInt(selectedPricing.harga, 10) || 0) : 0,
            sisaBayar: 0, // booking manual selalu full payment, tidak ada sisa
            metodeBayar: $('#metode_pembayaran').val() || '-',
        };
    };

    // ================= Tombol "Tambahkan pesanan F&B" =================
    $('#btnTambahFnb').on('click', function () {
        if (!validasiForm()) return;
        resetRincianModalState();

        // Tandai eksplisit: modal F&B ini dibuka dari form "Tambah Booking",
        // supaya modal-fb.blade.php tahu harus mewarisi metode bayar dari
        // form ini dan menyembunyikan pilihan metode bayar manual.
        window.fnbContext = 'booking-form';

        $('#modalFB').modal('show');
    });

    // ================= Fungsi trigger print dari iframe =================
    function triggerPrintStruk() {
        const iframe = document.getElementById('cetak-struk-iframe');
        if (iframe && iframe.contentWindow) {
            iframe.contentWindow.focus();
            iframe.contentWindow.print();
        }
    }

    // ================= Override submit "Cetak Struk" di modal rincian =================
    // modal-rincian.blade.php dipakai bersama index.blade.php. Di sana klik
    // "Cetak Struk" submit ke endpoint cetak-struk booking yang SUDAH ada.
    // Di halaman ini booking BELUM ada, jadi kita daftarkan strategi submit
    // sendiri (dipanggil dari modal-rincian.blade.php kalau terdaftar):
    // simpan booking baru + item F&B (kalau ada) dalam SATU request ke
    // storeManual, baru cetak lewat iframe. Ini titik ekstensi supaya
    // modal-rincian.blade.php tidak perlu tahu detail form/endpoint halaman ini.
    window.fnbSubmitOverride = function (btnCetak, fnbState) {
        // Klik kedua dst. pada tombol yang sama (label sudah "Cetak Ulang")
        // cuma print ulang PDF yang sudah ada, bukan submit ulang data booking.
        if (bookingSudahTersimpan) {
            triggerPrintStruk();
            return;
        }

        const items = (fnbState && Array.isArray(fnbState.items)) ? fnbState.items : [];
        const originalText = btnCetak.html();
        btnCetak.html('<i class="fas fa-spinner fa-spin mr-1"></i> Menyimpan...').prop('disabled', true);

        const payload = {
            _token: csrfToken,
            nama_pelanggan: $('input[name="nama_pelanggan"]').val(),
            no_telp: $('input[name="no_telp"]').val(),
            email: $('input[name="email"]').val(),
            ruangan: $('#select_ruangan').val(),
            paket: $('#select_paket').val(),
            id_penetapan_harga: $('#id_penetapan_harga').val(),
            waktu_mulai: $('#waktu_mulai').val(),
            metode_pembayaran: $('#metode_pembayaran').val(),
            catatan: $('#catatan').val(),
            items: items,
        };

        $.ajax({
            url: storeUrl,
            type: 'POST',
            data: payload,

            success: function (response) {
                tampilkanAlert('success', 'Pesanan berhasil dibuat!');

                const pdfUrl = response.data.pdf_url;
                const iframe = document.getElementById('cetak-struk-iframe');

                // Kembalikan tombol ke keadaan siap-klik (sebelumnya tombol
                // "nyangkut" di state disabled/"Menyimpan..." karena tidak
                // pernah di-reset di sini)
                btnCetak.html('<i class="fas fa-print mr-1"></i> Cetak Struk').prop('disabled', false);

                if (pdfUrl) {
                    // Begitu PDF selesai dimuat di iframe, langsung trigger print
                    iframe.onload = function () {
                        triggerPrintStruk();

                        // Langsung tampilkan "Selesai" begitu dialog print kebuka,
                        // gak gantung ke onafterprint yang perilakunya gak konsisten
                        // antar browser (bisa fire walau user klik Cancel).
                        $('#btn-selesai').removeClass('d-none');
                        $('#btn-cetak-struk').html('<i class="fas fa-print mr-1"></i> Cetak Ulang');
                    };
                    iframe.src = pdfUrl;
                }

                bookingSudahTersimpan = true;

                // Booking sudah tersimpan — bersihkan form & state F&B supaya
                // siap dipakai untuk booking berikutnya begitu modal ditutup.
                resetForm();
                window.fnbState = null;
            },

            error: function (xhr) {
                btnCetak.html(originalText).prop('disabled', false);

                if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                    const errors = xhr.responseJSON.errors;
                    const firstError = Object.values(errors)[0][0];
                    tampilkanAlert('danger', firstError);
                } else {
                    tampilkanAlert('danger', 'Terjadi kesalahan saat menyimpan pesanan. Silakan coba lagi.');
                }
            }
        });
    };

    function resetForm() {
        $('input[name="nama_pelanggan"]').val('');
        $('input[name="no_telp"]').val('');
        $('input[name="email"]').val('');
        $('#select_ruangan').val('');
        $('#select_paket').val('');
        $('#durasi-options').html('<small class="text-muted">Pilih ruangan &amp; paket terlebih dahulu.</small>');
        $('#id_penetapan_harga').val('');
        $('#waktu_mulai').val('');
        $('#waktu_selesai_preview').val('-');
        $('.btn-payment-option').removeClass('active-payment');
        $('#metode_pembayaran').val(''); $('#catatan').val('');
        selectedPricing = null;
    }

});
</script>
@stop