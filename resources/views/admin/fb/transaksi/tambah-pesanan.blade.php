@extends('adminlte::page')

@include('partials.sidebar-admin')

@section('title', 'Tambah Pesanan F&B - Play N Chill')

@section('content_header')
    <div class="px-2 pt-3 pb-2">
        <h1 class="text-dark fw-normal" style="font-size: 1.8rem;">Tambah Pesanan F&B</h1>
    </div>
@stop

@section('content')
<div class="container-fluid px-2 pb-5">
    
    <form action="{{ url('/admin/fb/transaksi/store') }}" method="POST">
        @csrf
        
        {{-- CARD INFORMASI PELANGGAN --}}
        <div class="card shadow-none" style="border: 1px solid #d1d5db; border-radius: 6px;">
            <div class="card-body p-4 p-md-5">
                
                <h5 class="text-dark fw-bold mb-4" style="font-size: 1.05rem;">
                    <i class="far fa-user me-2 text-dark"></i> Informasi Pelanggan
                </h5>
                
                <div class="row mb-4 g-4">
                    <div class="col-12 col-md-6">
                        <label class="form-label text-muted" style="font-size: 0.9rem;">
                            Nama Pelanggan <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="nama_pelanggan" class="form-control custom-input shadow-none" placeholder="Masukkan nama...." required>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label text-muted" style="font-size: 0.9rem;">No Telp.</label>
                        <input type="text" name="no_telp" class="form-control custom-input shadow-none" placeholder="08...">
                    </div>

                    {{-- DIBERI CLASS col-12 AGAR MELEBAR PENUH 100% --}}
                    <div class="col-12 mb-2">
                        <label class="form-label text-muted" style="font-size: 0.9rem;">Catatan</label>
                        <textarea name="catatan" class="form-control custom-input shadow-none w-100" rows="10"></textarea>
                    </div>
                </div>

            </div>
        </div>

        {{-- AREA TOMBOL BAWAH --}}
        <div class="d-flex justify-content-end mt-4">
            <button type="button" data-toggle="modal" data-target="#modalFB" class="btn px-5 py-2 shadow-sm d-flex align-items-center justify-content-center" style="background-color: #f07b55; color: #ffffff; border-radius: 6px; font-weight: 600; font-size: 0.95rem; border: none;">
                <i class="fas fa-shopping-cart me-2"></i> Pilih Menu F&B
            </button>
        </div>

    </form>
</div>

{{-- Iframe struk disembunyikan tapi tetap "hidup" (bukan display:none)
     supaya window.print() dari dalam iframe tetap bisa jalan di semua browser.
     Dibutuhkan oleh triggerPrintStrukRincian() di modal-rincian-fb.blade.php. --}}
<iframe id="cetak-struk-iframe" style="position:absolute; width:0; height:0; border:0; visibility:hidden;"></iframe>

{{-- PANGGIL FILE MODAL DARI SINI --}}
@include('admin.bookings.partials.modal-fb')
@include('admin.bookings.partials.fnb.modal-rincian-fb')
@stop

@push('css')
<style>
    /* Styling Dasar Form */
    .custom-input {
        border: 1px solid #d1d5db;
        border-radius: 4px;
        padding: 10px 15px;
        font-size: 0.95rem;
        color: #374151;
    }
    .custom-input:focus {
        border-color: #f07b55;
        box-shadow: 0 0 0 0.25rem rgba(240, 123, 85, 0.15);
    }
    .custom-input::placeholder {
        color: #9ca3af;
    }
    .btn:hover {
        opacity: 0.9;
    }

    /* CSS KHUSUS MODAL */
    .border-end-lg {
        border-right: 1px solid #e5e7eb;
    }
    @media (max-width: 991.98px) {
        .border-end-lg {
            border-right: none;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 1.5rem;
            margin-bottom: 1.5rem;
        }
    }
    .max-h-500 {
        max-height: 450px;
    }
    .product-card {
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .product-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 6px rgba(0,0,0,0.1) !important;
        border-color: #5b21b6 !important;
    }
    .btn-qty {
        background-color: #f9fafb;
    }
    .btn-qty:hover {
        background-color: #e5e7eb;
    }
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1; 
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #d1d5db; 
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #9ca3af; 
    }
</style>
@endpush

@push('js')
<script>
    window.isFnbManual = true;

    window.addEventListener('load', function () {
        $(document).ready(function () {

            // Dipanggil oleh modal-rincian-fb.blade.php pas submit (klik
            // "Cetak Struk") -- gantiin akses langsung ke response.data yang
            // dulu dipakai, karena submit sekarang terjadi belakangan.
            window.fnbManualGetCustomerData = function () {
                return {
                    nama_pelanggan: $('input[name="nama_pelanggan"]').val(),
                    no_telp: $('input[name="no_telp"]').val(),
                    catatan: $('textarea[name="catatan"]').val(),
                };
            };

            window.fnbManualResetForm = function () {
                $('input[name="nama_pelanggan"]').val('');
                $('input[name="no_telp"]').val('');
                $('textarea[name="catatan"]').val('');
            };

            // Jembatan khusus dari modal-fb.blade.php. Sekarang HANYA
            // menyiapkan tampilan rincian dari data LOKAL (belum submit) --
            // submit sesungguhnya terjadi di modal-rincian-fb.blade.php pas
            // klik "Cetak Struk", supaya kasir sempat isi Uang Diterima
            // dulu sebelum data ini benar-benar dikirim ke server.
            window.fnbShowRincianManual = function (cartItems) {

                const namaPelanggan = $('input[name="nama_pelanggan"]').val();
                const noTelp = $('input[name="no_telp"]').val();
                const metodePembayaran = window.fnbState.metodePembayaran;

                // Validasi agar kasir tidak lupa isi form
                if (!namaPelanggan) {
                    Swal.fire('Oops!', 'Nama pelanggan wajib diisi sebelum menyimpan!', 'warning');
                    return;
                }
                if (!metodePembayaran) {
                    Swal.fire('Oops!', 'Pilih metode pembayaran (Tunai/QRIS) terlebih dahulu.', 'warning');
                    return;
                }

                $('#modalFB').modal('hide');

                setTimeout(function () {
                    // Isi Modal Detail dari data LOKAL (belum ada dari server)
                    $('#rincian-kode-sewa').text('Akan digenerate otomatis setelah disimpan');
                    $('#rincian-nama').text(namaPelanggan);
                    $('#rincian-no-hp').text(noTelp || '-');
                    $('#detail-metode-bayar').text(metodePembayaran);

                    // Reset kolom booking (tidak relevan untuk F&B mandiri)
                    $('#rincian-email, #rincian-ruangan, #rincian-paket, #rincian-waktu-mulai, #rincian-waktu-selesai, #rincian-durasi, #rincian-tipe-hari, #rincian-total-harga').text('-');

                    let itemsHtml = '';
                    let grandTotal = 0;
                    for (let id in cartItems) {
                        let item = cartItems[id];
                        let subtotal = item.harga * item.qty;
                        grandTotal += subtotal;
                        itemsHtml += `
                            <div class="d-flex justify-content-between mb-1" style="font-size: 13px;">
                                <span>${item.qty}x ${item.nama}</span>
                                <span>Rp ${subtotal.toLocaleString('id-ID')}</span>
                            </div>`;
                    }
                    $('#rincian-fnb-items').html(itemsHtml);
                    $('#rincian-total-fnb').text('Rp ' + grandTotal.toLocaleString('id-ID'));
                    $('#rincian-grand-total').text('Rp ' + grandTotal.toLocaleString('id-ID'));

                    $('#rincian-sisa-booking-section').hide();
                    $('#rincian-fnb-section').show();

                    // Pastikan tombol cetak dalam keadaan siap-klik (bukan
                    // "nyangkut" disabled dari percobaan sebelumnya)
                    $('#btn-cetak-struk').removeClass('d-none')
                        .prop('disabled', false)
                        .html('<i class="fas fa-print mr-1"></i> Cetak Struk');
                    $('#btn-selesai').addClass('d-none');

                    $('#modalDetailPesanan').modal('show');

                }, 400);
            }; // Penutup window.fnbShowRincianManual

        }); // Penutup $(document).ready
    }); // Penutup window.addEventListener
</script>
@endpush