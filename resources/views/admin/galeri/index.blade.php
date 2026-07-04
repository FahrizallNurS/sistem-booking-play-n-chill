@extends('adminlte::page')

@include('partials.sidebar-admin')

@section('title', 'Kelola Galeri - Play N Chill')

@section('content_header')
    <div class="px-2">
        <h1 class="text-dark fw-bold" style="font-size: 1.8rem;">Kelola Galeri</h1>
        <p class="text-muted mb-2">Manajemen galeri website</p>
    </div>
@stop

@section('content')
<div class="container-fluid px-2">
    
    {{-- ALERT NOTIFIKASI (Awalnya Disembunyikan) --}}
    <div id="alertSuccess" class="alert alert-success d-none mb-3" style="border-radius: 0; background-color: #28a745; color: white; border: none;">
        <span id="alertMessage">Status berhasil diubah!</span>
    </div>

    {{-- 1. BAGIAN TOOLBAR (Pencarian, Filter, Tombol Tambah) --}}
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 8px;">
        <div class="card-body py-3">
            <div class="row align-items-center g-3">
                
                {{-- Input Pencarian --}}
                <div class="col-12 col-md-4">
                    <div class="input-group" style="border: 1px solid #d1d5db; border-radius: 6px; background-color: #ffffff; overflow: hidden;">
                        <span class="input-group-text border-0 bg-transparent text-muted" style="padding-right: 8px;">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" class="form-control border-0 bg-transparent ps-0 shadow-none" placeholder="Cari nama banner..." style="color: #4b5563;">
                    </div>
                </div>

                {{-- Dropdown Kategori --}}
                <div class="col-12 col-md-4 d-flex align-items-center">
                    <label class="mb-0 me-3 text-muted fw-normal" style="white-space: nowrap;">Kategori</label>
                    <select class="form-select text-muted shadow-none" style="background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 6px; cursor: pointer; width: 100%;">
                        <option>Semua</option>
                        <option>VIP Room</option>
                        <option>Mini Karaoke</option>
                    </select>
                </div>

                {{-- Tombol Filter, Reset, dan Tambah Foto --}}
                <div class="col-12 col-md-4 d-flex justify-content-between align-items-center">
                    <div>
                        <button class="btn btn-primary btn-sm px-3 me-1 py-2" style="border-radius: 6px; font-weight: 500;">
                            <i class="fas fa-filter me-1"></i> Filter
                        </button>
                        <button class="btn btn-secondary btn-sm px-3 py-2 text-white" style="background-color: #6c757d; border-radius: 6px; border: none; font-weight: 500;">
                            <i class="fas fa-sync-alt me-1"></i> Reset
                        </button>
                    </div>
                    
                    <a href="{{ url('/admin/galeri/create') }}" class="btn btn-purple px-4 py-2" style="border-radius: 6px;">
                        <i class="fas fa-plus me-1"></i> Tambah Foto
                    </a>
                </div>

            </div>
        </div>
    </div>

    {{-- 2. BAGIAN TABEL DATA --}}
    <div class="card border-0 shadow-sm" style="border-radius: 8px;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-dark fw-bold" style="font-size: 0.85rem;">
                        <tr>
                            <th class="text-center py-3 border-0" style="width: 5%;">#</th>
                            <th class="border-0">Preview</th>
                            <th class="border-0">Judul Foto</th>
                            <th class="border-0">Sub Kategori</th>
                            <th class="border-0">Tanggal Upload</th>
                            <th class="border-0" style="width: 25%;">Deskripsi</th>
                            <th class="border-0">Status</th>
                            <th class="border-0">Aksi</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 0.85rem; color: #4b5563;">
                        
                        {{-- Data Dummy 1 --}}
                        <tr>
                            <td class="text-center">1</td>
                            <td>
                                <img src="{{ asset('images/gaming.jpg') }}" alt="Preview" class="preview-img shadow-sm">
                            </td>
                            <td class="text-dark">Pro Gaming VIP</td>
                            <td><span class="badge bg-secondary">VIP Room</span></td>
                            <td>29/05/2026 20:30</td>
                            <td class="text-muted">Bernyanyi bersama keluarga maupun teman dalam ruangan ...</td>
                            <td>
                                <span id="status-row-1" class="badge bg-success px-2 py-1" style="border-radius: 4px;">Aktif</span>
                            </td>
                            <td>
                                {{-- PERBAIKAN: Mengganti class gap-1 dengan style gap presisi --}}
                                <div class="d-flex" style="gap: 6px;">
                                    <a href="{{ url('/admin/galeri/edit') }}" class="btn btn-info btn-sm text-white">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <button class="btn btn-warning btn-sm text-white" onclick="toggleStatus(this, 'row-1')">
                                        <i class="fas fa-ban"></i> Nonaktifkan
                                    </button>
                                    <button class="btn btn-danger btn-sm text-white">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>

                        {{-- Data Dummy 2 --}}
                        <tr>
                            <td class="text-center">2</td>
                            <td>
                                <img src="{{ asset('images/karaoke.jpg') }}" alt="Preview" class="preview-img shadow-sm" onerror="this.src='{{ asset('images/gaming.jpg') }}'">
                            </td>
                            <td class="text-dark">Mini Karaoke</td>
                            <td><span class="badge bg-secondary">Mini karaoke</span></td>
                            <td>29/05/2026 20:30</td>
                            <td class="text-muted">Rasakan pengalaman menonton film dengan layar besar, audio berkualitas.</td>
                            <td>
                                <span id="status-row-2" class="badge bg-success px-2 py-1" style="border-radius: 4px;">Aktif</span>
                            </td>
                            <td>
                                {{-- PERBAIKAN: Mengganti class gap-1 dengan style gap presisi --}}
                                <div class="d-flex" style="gap: 6px;">
                                    <a href="{{ url('/admin/galeri/edit') }}" class="btn btn-info btn-sm text-white">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <button class="btn btn-warning btn-sm text-white" onclick="toggleStatus(this, 'row-2')">
                                        <i class="fas fa-ban"></i> Nonaktifkan
                                    </button>
                                    <button class="btn btn-danger btn-sm text-white">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>

                        {{-- Data Dummy 3 (Kondisi Awal: Nonaktif) --}}
                        <tr>
                            <td class="text-center">3</td>
                            <td>
                                <img src="{{ asset('images/cinema.jpg') }}" alt="Preview" class="preview-img shadow-sm" onerror="this.src='{{ asset('images/gaming.jpg') }}'">
                            </td>
                            <td class="text-dark">Private Dual Station</td>
                            <td><span class="badge bg-secondary">VVIP ROOM</span></td>
                            <td>29/05/2026 20:30</td>
                            <td class="text-muted">Rasakan pengalaman menonton film dengan layar besar, audio berkualitas.</td>
                            <td>
                                <span id="status-row-3" class="badge bg-secondary px-2 py-1" style="border-radius: 4px;">Nonaktif</span>
                            </td>
                            <td>
                                {{-- PERBAIKAN: Mengganti class gap-1 dengan style gap presisi --}}
                                <div class="d-flex" style="gap: 6px;">
                                    <a href="{{ url('/admin/galeri/edit') }}" class="btn btn-info btn-sm text-white">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <button class="btn btn-success btn-sm text-white" onclick="toggleStatus(this, 'row-3')">
                                        <i class="fas fa-check-circle"></i> Aktifkan
                                    </button>
                                    <button class="btn btn-danger btn-sm text-white">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>

                    </tbody>
                </table>
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
    .preview-img {
        width: 80px;
        height: 45px;
        object-fit: cover;
        border-radius: 4px;
    }
    .table td {
        border-bottom: 1px solid #f3f4f6;
    }
    
    .btn-sm {
        font-size: 0.75rem;
        padding: 0.35rem 0.6rem;
    }
</style>
@endpush

@push('js')
<script>

    function toggleStatus(btnElement, rowId) {
        const statusBadge = document.getElementById('status-' + rowId);
        const alertBox = document.getElementById('alertSuccess');
        const alertMsg = document.getElementById('alertMessage');
        let isAktif = statusBadge.innerText.trim().toLowerCase() === 'aktif';
        
        if (isAktif) {

            statusBadge.className = 'badge bg-secondary px-2 py-1';
            statusBadge.innerText = 'Nonaktif';
            btnElement.className = 'btn btn-success btn-sm text-white';
            btnElement.innerHTML = '<i class="fas fa-check-circle"></i> Aktifkan';
            alertMsg.innerText = 'Foto galeri berhasil dinonaktifkan!';
        } else {
            statusBadge.className = 'badge bg-success px-2 py-1';
            statusBadge.innerText = 'Aktif';
            btnElement.className = 'btn btn-warning btn-sm text-white';
            btnElement.innerHTML = '<i class="fas fa-ban"></i> Nonaktifkan';
            alertMsg.innerText = 'Foto galeri berhasil diaktifkan!';
        }
        alertBox.classList.remove('d-none');
        setTimeout(() => {
            alertBox.classList.add('d-none');
        }, 3000);
    }
</script>
@endpush