@extends('adminlte::page')
@include('partials.sidebar-admin')
@section('title', 'Tambah Booking')

@section('content_header')
    <h1>Tambah Booking</h1>
@stop

@section('content')

    @php
        //dummy data
        $ruangans = [
            ['id' => 1, 'nama_ruangan' => 'PS5 Regular'],
            ['id' => 2, 'nama_ruangan' => 'PS5 VIP'],
            ['id' => 3, 'nama_ruangan' => 'Gaming Private Room'],
            ['id' => 4, 'nama_ruangan' => 'VIP Room'],
        ];

        $pakets = [
            ['id' => 1, 'nama_paket' => 'Paket Reguler 1 Jam',  'category' => 'PlayStation', 'sub_category' => 'PS5',        'sku' => 'PKT-PS5-001', 'price' => 25000],
            ['id' => 2, 'nama_paket' => 'Paket Reguler 2 Jam',  'category' => 'PlayStation', 'sub_category' => 'PS5',        'sku' => 'PKT-PS5-002', 'price' => 45000],
            ['id' => 3, 'nama_paket' => 'Paket VIP 1 Jam',      'category' => 'VIP',         'sub_category' => 'VIP Room',   'sku' => 'PKT-VIP-001', 'price' => 50000],
            ['id' => 4, 'nama_paket' => 'Paket Switch 1 Jam',   'category' => 'Nintendo',    'sub_category' => 'Switch',     'sku' => 'PKT-NSW-001', 'price' => 20000],
        ];

        $durasiOptions = [1, 2, 3, 4, 5];
    @endphp

    @include('admin.bookings.partials.create.form-pelanggan')
    @include('admin.bookings.partials.create.form-booking')

    {{-- Tombol Aksi --}}
    <div class="d-flex flex-wrap justify-content-end" style="gap: 10px; margin-top: 1.5rem; margin-bottom: 2rem;">
        <button type="button" id="btnTambahFnb" class="btn font-weight-bold px-4"
            style="background-color: #fd7e14; color: #fff;">
            <i class="fas fa-shopping-cart mr-1"></i> Tambahkan pesanan F&B
        </button>
        <button type="button" id="btnBookingTanpaFnb" class="btn font-weight-bold px-4"
            style="background-color: #ece9f7; color: #6f42c1;">
            <i class="fas fa-clipboard-list mr-1"></i> Buat booking tanpa pesanan F&B
        </button>
    </div>

    @include('admin.bookings.partials.modal-fb')
    @include('admin.bookings.partials.modal-rincian')

@stop

@section('css')
<style>
    /* Tombol metode pembayaran mengikuti tinggi form-control AdminLTE */
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
</style>
@stop

@section('js')
<script>
$(document).ready(function () {

    // ================= Toggle Metode Pembayaran (Cash / QRIS) =================
    $('.btn-payment-option').on('click', function () {
        $('.btn-payment-option').removeClass('active-payment');
        $(this).addClass('active-payment');
        $('#metode_pembayaran').val($(this).data('value'));
    });

    // ================= Preview Info Paket (Category/Sub Category/SKU/Price) =================
    var paketData = @json($pakets);

    $('#select_paket').on('change', function () {
        var idPaket = $(this).val();
        var paket = paketData.find(function (p) { return p.id == idPaket; });

        if (paket) {
            $('#preview_category').text(paket.category);
            $('#preview_subcategory').text(paket.sub_category);
            $('#preview_sku').text(paket.sku);
            $('#preview_price').text('Rp ' + Number(paket.price).toLocaleString('id-ID'));
        } else {
            $('#preview_category, #preview_subcategory, #preview_sku, #preview_price').text('-');
        }
    });

    // ================= Validasi sederhana sebelum lanjut (frontend only) =================
    function validasiMetodePembayaran() {
        if (!$('#metode_pembayaran').val()) {
            alert('Silakan pilih metode pembayaran (Cash / QRIS) terlebih dahulu.');
            return false;
        }
        return true;
    }

    $('#btnTambahFnb').on('click', function () {
        // Cek validasi pembayaran terlebih dahulu
        if (!validasiMetodePembayaran()) return;
        $('#modalFB').modal('show');
    });

    $('#btnBookingTanpaFnb').on('click', function () {
            if (!validasiMetodePembayaran()) return;

            let btnSubmit = $(this);
            let originalText = btnSubmit.html();
            btnSubmit.html('<i class="fas fa-spinner fa-spin mr-1"></i> Memproses...').prop('disabled', true);

            let metodeTerpilih = $('#metode_pembayaran').val();

            setTimeout(function() {
                btnSubmit.html(originalText).prop('disabled', false);
                $('#detail-metode-bayar').text(metodeTerpilih.toUpperCase());

                // SEMBUNYIKAN BAGIAN F&B
                $('#rincian-fnb-section').hide();

                $('#modalDetailPesanan').modal('show');
            }, 1000); 
        });

    // ================= Transisi Simpan F&B =================
   $('#btn-simpan-fnb').on('click', function () {
        let btnSubmit = $(this);
        let originalText = btnSubmit.html();
        btnSubmit.html('<i class="fas fa-spinner fa-spin mr-1"></i> Memproses...').prop('disabled', true);

        // --- SIMULASI UI (Karena Backend Belum Ada) ---
        setTimeout(function() { 
            btnSubmit.html(originalText).prop('disabled', false);
            $('#modalFB').modal('hide');

            setTimeout(function() {
                // TAMPILKAN KEMBALI BAGIAN F&B
                $('#rincian-fnb-section').show();
                
                $('#modalDetailPesanan').modal('show');
            }, 500); 
        }, 1000);
    });

        /* MATIKAN SEMENTARA AJAX-NYA AGAR TIDAK ERROR
        $.ajax({
            url: '/url-endpoint-simpan-anda', 
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                metode_pembayaran: $('#metode_pembayaran').val()
            },
            success: function (response) {
                btnSubmit.html(originalText).prop('disabled', false);
                $('#modalFB').modal('hide');
                setTimeout(function() {
                    $('#modalDetailPesanan').modal('show');
                }, 500); 
            },
            error: function (xhr) {
                btnSubmit.html(originalText).prop('disabled', false);
                alert('Terjadi kesalahan saat menyimpan pesanan. Silakan coba lagi.');
            }
        });
        */
    });
</script>
@stop