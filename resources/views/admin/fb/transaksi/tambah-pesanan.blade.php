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
                        {{-- 🔹 TAMBAHKAN name="nama_pelanggan" --}}
                        <input type="text" name="nama_pelanggan" class="form-control custom-input shadow-none" placeholder="Masukkan nama...." required>
                    </div>
                    
                    <div class="col-12 col-md-6">
                        <label class="form-label text-muted" style="font-size: 0.9rem;">No Telp.</label>
                        {{-- 🔹 TAMBAHKAN name="no_telp" --}}
                        <input type="text" name="no_telp" class="form-control custom-input shadow-none" placeholder="08...">
                    </div>
                </div>

                <div class="mb-2">
                    <label class="form-label text-muted" style="font-size: 0.9rem;">Catatan</label>
                    {{-- 🔹 TAMBAHKAN name="catatan" --}}
                    <textarea name="catatan" class="form-control custom-input shadow-none" rows="10"></textarea>
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

{{-- PANGGIL FILE MODAL DARI SINI (Harus di dalam @section('content')) --}}
@include('admin.bookings.partials.modal-fb')
@include('admin.bookings.partials.fnb.modal-rincian-fb')
@stop

@push('css')
<style>
    /* Styling Dasar Form (Untuk halaman tambah-pesanan) */
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

    /* CSS KHUSUS MODAL (Sebaiknya disatukan di sini agar tidak error saat dipanggil) */
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

@push('js')
<script>
    window.isFnbManual = true;
</script>

<style>
    /* Styling Dasar Form (Tetap dipertahankan) */
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
</style>
@endpush