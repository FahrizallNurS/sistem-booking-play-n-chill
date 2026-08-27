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
            
            // Jembatan khusus dari modal-fb.blade.php
            window.fnbShowRincianManual = function(cartItems) {
                
                // 1. Ambil data dari form input utama
                const namaPelanggan = $('input[name="nama_pelanggan"]').val();
                const noTelp = $('input[name="no_telp"]').val();
                const catatan = $('textarea[name="catatan"]').val();
                const metodePembayaran = window.fnbState.metodePembayaran;

                // 2. Validasi agar kasir tidak lupa isi form
                if (!namaPelanggan) {
                    Swal.fire('Oops!', 'Nama pelanggan wajib diisi sebelum menyimpan!', 'warning');
                    return;
                }
                if (!metodePembayaran) {
                    Swal.fire('Oops!', 'Pilih metode pembayaran (Tunai/QRIS) terlebih dahulu.', 'warning');
                    return;
                }

                // 3. Susun array keranjang untuk di-lempar ke Controller
                let itemsToSubmit = [];
                for (let id in cartItems) {
                    itemsToSubmit.push({
                        id_produk: parseInt(id),
                        jumlah: cartItems[id].qty
                    });
                }

                // 4. Loading layar
                Swal.fire({
                    title: 'Memproses Transaksi...',
                    text: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // 5. AJAX Utama
                $.ajax({
                    url: "{{ route('admin.fb.transaksi.store') }}", 
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        nama_pelanggan: namaPelanggan,
                        no_telp: noTelp,
                        catatan: catatan,
                        metode_pembayaran: metodePembayaran,
                        items: itemsToSubmit
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.close(); 
                            
                            $('#modalFB').modal('hide');
                            
                            // Jeda agar animasi mulus
                            setTimeout(function() {
                                
                                // Isi Modal Detail
                                $('#rincian-kode-sewa').text(response.data.kode_pos);
                                $('#rincian-nama').text(namaPelanggan);
                                $('#rincian-no-hp').text(noTelp || '-');
                                $('#detail-metode-bayar').text(metodePembayaran);
                                
                                // Reset kolom booking
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
                                
                                // --- JURUS ANTI-OCTOPUS: Hancurkan Tombol Cetak Lama ---
                                let oldBtn = document.getElementById('btn-cetak-struk');
                                if (oldBtn) {
                                    // Clone tombol untuk memutus urat saraf dari script Booking
                                    let newBtn = oldBtn.cloneNode(true);
                                    newBtn.id = 'btn-cetak-struk-fnb'; // Ganti KTP (ID)
                                    oldBtn.parentNode.replaceChild(newBtn, oldBtn);
                                    
                                    newBtn.addEventListener('click', function(e) {
                                        e.preventDefault();
                                        e.stopPropagation(); // Benteng perlindungan
                                        cetakStrukLangsung(response.data.pdf_url);
                                    });
                                }
                                // -----------------------------------------------------

                                $('#modalDetailPesanan').modal('show');
                                
                                // Bersihkan inputan untuk pembeli berikutnya
                                $('input[name="nama_pelanggan"]').val('');
                                $('input[name="no_telp"]').val('');
                                $('textarea[name="catatan"]').val('');
                                
                            }, 400); 
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'Gagal menyimpan pesanan. Silakan coba lagi.';
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            let firstErrorKey = Object.keys(errors)[0];
                            errorMessage = 'Validasi Ditolak: ' + errors[firstErrorKey][0];
                        } else {
                            // 🔹 JURUS MENDETEKSI ERROR ASLI LARAVEL 🔹
                            errorMessage = xhr.responseJSON && xhr.responseJSON.message 
                                           ? xhr.responseJSON.message 
                                           : xhr.responseText;
                            
                            // Potong pesan error jika terlalu panjang
                            if(errorMessage.length > 200) {
                                errorMessage = errorMessage.substring(0, 200) + '...';
                            }
                        }
                        Swal.fire('Error System!', errorMessage, 'error');
                    }
                }); // Penutup $.ajax
            }; // Penutup window.fnbShowRincianManual

        }); // Penutup $(document).ready
    }); // Penutup window.addEventListener


    // --- FUNGSI CETAK "HANTU" (Tanpa pindah tab) ---
    function cetakStrukLangsung(url) {
        let iframe = document.getElementById('frameCetakStruk');
        if (!iframe) {
            iframe = document.createElement('iframe'); 
            iframe.id = 'frameCetakStruk';
            iframe.style.display = 'none';
            document.body.appendChild(iframe); 
        }

        iframe.src = url;
        iframe.onload = function() {
            setTimeout(function() {
                iframe.contentWindow.focus();
                iframe.contentWindow.print();
            }, 600);
        };
    }
</script>
@endpush