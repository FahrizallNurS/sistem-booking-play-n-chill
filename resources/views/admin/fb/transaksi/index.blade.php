@extends('adminlte::page')

@include('partials.sidebar-admin')

@section('title', 'Kelola Transaksi F&B - Play N Chill')

@section('content_header')
    <div class="px-2">
        <h1 class="text-dark fw-bold" style="font-size: 1.8rem;">Kelola Transaksi F&B</h1>
    </div>
@stop

@section('content')
<div class="container-fluid px-2 pb-4">
    
    {{-- 1. BAGIAN TOOLBAR (Filter Tanggal, Status, Tombol) --}}
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 8px;">
        <div class="card-body py-3">
            {{-- Menggunakan Flexbox agar kiri dan kanan terpisah sempurna --}}
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                
                {{-- Kumpulan Filter (Kiri) --}}
                <div class="d-flex flex-wrap align-items-center" style="gap: 12px;">
                    
                    {{-- Input Tanggal (Tinggi disamakan 38px) --}}
                    <input type="date" class="form-control shadow-none text-muted" style="width: 180px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 0.9rem; padding: 0.45rem 0.75rem; height: 38px;">

                    {{-- Dropdown Status (Tinggi disamakan 38px) --}}
                    <select class="form-select shadow-none text-muted" style="width: 160px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 0.9rem; cursor: pointer; padding: 0.45rem 2rem 0.45rem 0.75rem; height: 38px;">
                        <option value="">Semua Status</option>
                        <option value="menunggu">Menunggu</option>
                        <option value="selesai">Selesai</option>
                    </select>

                    {{-- Tombol Filter & Reset --}}
                    <div class="d-flex" style="gap: 8px;">
                        <button class="btn btn-primary px-3 shadow-sm" style="border-radius: 6px; font-weight: 500; background-color: #0084ff; border: none; font-size: 0.875rem; height: 38px; display: flex; align-items: center;">
                            <i class="fas fa-filter me-1"></i> Filter
                        </button>
                        <button class="btn btn-secondary px-3 text-white shadow-sm" style="background-color: #6c757d; border-radius: 6px; border: none; font-weight: 500; font-size: 0.875rem; height: 38px; display: flex; align-items: center;">
                            <i class="fas fa-sync-alt me-1"></i> Reset
                        </button>
                    </div>

                </div>

                {{-- Tombol Tambah Pesanan (Kanan - Otomatis terdorong ke ujung) --}}
                <div>
                    <a href="{{ url('/admin/bookings/partials/create') }}" class="btn btn-purple px-4 w-100 shadow-sm text-decoration-none" style="border-radius: 6px; font-weight: 500; font-size: 0.875rem; height: 38px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-plus me-1"></i> Tambah Pesanan
                    </a>
                </div>

            </div>
        </div>
    </div>

    {{-- 2. BAGIAN TABEL DATA --}}
    <div class="card border-0 shadow-sm" style="border-radius: 8px;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-center">
                    <thead class="bg-light text-dark fw-bold" style="font-size: 0.85rem;">
                        <tr>
                            <th class="py-3 border-0" style="width: 8%;">No</th>
                            <th class="border-0">ID POS/Struk</th>
                            <th class="border-0">Nama</th>
                            <th class="border-0">Waktu Pesan</th>
                            <th class="border-0">Status Pesanan</th>
                            <th class="border-0" style="width: 12%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 0.9rem; color: #374151;">
                        
                        {{-- Data Dummy 1 --}}
                        <tr>
                            <td>1</td>
                            <td class="fw-semibold">POS-001</td>
                            <td>Gojo Satoru</td>
                            <td>24/06/2026 16:00</td>
                            <td>
                                <span class="badge text-dark py-1 px-3" style="background-color: #ffc107; border-radius: 4px; font-weight: 600; font-size: 0.75rem;">MENUNGGU</span>
                            </td>
                            <td>
                                <button class="btn btn-primary btn-sm shadow-sm" style="border-radius: 4px; padding: 0.2rem 1rem;">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>

                        {{-- Data Dummy 2 --}}
                        <tr>
                            <td>2</td>
                            <td class="fw-semibold">POS-002</td>
                            <td>Muhammad Ali</td>
                            <td>24/06/2026 18:00</td>
                            <td>
                                <span class="badge text-white py-1 px-3" style="background-color: #28a745; border-radius: 4px; font-weight: 600; font-size: 0.75rem;">SELESAI</span>
                            </td>
                            <td>
                                <button class="btn btn-primary btn-sm shadow-sm" style="border-radius: 4px; padding: 0.2rem 1rem;">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>

            {{-- 3. BAGIAN PAGINATION BAWAH --}}
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center p-3 border-top bg-light">
                <span class="text-muted" style="font-size: 0.85rem;">Menampilkan 1 sampai 2 dari 2 entri</span>
                <nav class="mt-2 mt-md-0">
                    <ul class="pagination pagination-sm mb-0">
                        <li class="page-item disabled"><a class="page-link text-muted" href="#">Sebelumnya</a></li>
                        <li class="page-item active"><a class="page-link" href="#" style="background-color: #5b21b6; border-color: #5b21b6;">1</a></li>
                        <li class="page-item disabled"><a class="page-link text-muted" href="#">Selanjutnya</a></li>
                    </ul>
                </nav>
            </div>
            
        </div>
    </div>

</div>
@stop

@push('css')
<style>

    .btn-purple {
        background-color: #5b21b6;
        color: #ffffff;
        font-weight: 500;
        border: none;
        transition: 0.2s;
    }
    .btn-purple:hover {
        background-color: #4c1d95;
        color: #ffffff;
    }

    .table td {
        border-bottom: 1px solid #f3f4f6;
        padding-top: 1rem;
        padding-bottom: 1rem;
    }
</style>
@endpush