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
    
    {{-- ALERT NOTIFIKASI BACKEND (Untuk Create, Update, Delete) --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-3" style="border-radius: 6px; background-color: #28a745; color: white; border: none;">
            {{ session('success') }}
            <button type="button" class="close text-white" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    {{-- ALERT NOTIFIKASI AJAX (Awalnya Disembunyikan) --}}
    <div id="alertSuccess" class="alert alert-success d-none mb-3" style="border-radius: 0; background-color: #28a745; color: white; border: none;">
        <span id="alertMessage">Status berhasil diubah!</span>
    </div>

    {{-- 1. BAGIAN TOOLBAR (Pencarian, Filter, Tombol Tambah) --}}
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 8px;">
        <div class="card-body py-3">
            {{-- Form GET untuk Pencarian & Filter --}}
            <form method="GET" action="{{ route('admin.galeri.index') }}">
                <div class="row align-items-center g-3">
                    
                    {{-- Input Pencarian --}}
                    <div class="col-12 col-md-4">
                        <div class="input-group" style="border: 1px solid #d1d5db; border-radius: 6px; background-color: #ffffff; overflow: hidden;">
                            <span class="input-group-text border-0 bg-transparent text-muted" style="padding-right: 8px;">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" name="search" value="{{ request('search') }}" class="form-control border-0 bg-transparent ps-0 shadow-none" placeholder="Cari judul foto..." style="color: #4b5563;">
                        </div>
                    </div>

                    {{-- Dropdown Kategori --}}
                    <div class="col-12 col-md-4 d-flex align-items-center">
                        <label class="mb-0 me-3 text-muted fw-normal" style="white-space: nowrap;">Kategori</label>
                        <select name="kategori" class="form-select text-muted shadow-none" style="background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 6px; cursor: pointer; width: 100%;">
                            <option value="">Semua</option>
                            <option value="Reguler" {{ request('kategori') == 'Reguler' ? 'selected' : '' }}>Reguler</option>
                            <option value="Private - Gaming" {{ request('kategori') == 'Private - Gaming' ? 'selected' : '' }}>Private - Gaming</option>
                            <option value="Private - Nonton" {{ request('kategori') == 'Private - Nonton' ? 'selected' : '' }}>Private - Nonton</option>
                            <option value="Private - Karaoke" {{ request('kategori') == 'Private - Karaoke' ? 'selected' : '' }}>Private - Karaoke</option>
                        </select>
                    </div>

                    {{-- Tombol Filter, Reset, dan Tambah Foto --}}
                    <div class="col-12 col-md-4 d-flex justify-content-between align-items-center">
                        <div>
                            <button type="submit" class="btn btn-primary btn-sm px-3 me-1 py-2" style="border-radius: 6px; font-weight: 500;">
                                <i class="fas fa-filter me-1"></i> Filter
                            </button>
                            <a href="{{ route('admin.galeri.index') }}" class="btn btn-secondary btn-sm px-3 py-2 text-white" style="background-color: #6c757d; border-radius: 6px; border: none; font-weight: 500;">
                                <i class="fas fa-sync-alt me-1"></i> Reset
                            </a>
                        </div>
                        
                        <a href="{{ route('admin.galeri.create') }}" class="btn btn-purple px-4 py-2" style="border-radius: 6px;">
                            <i class="fas fa-plus me-1"></i> Tambah Foto
                        </a>
                    </div>

                </div>
            </form>
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
                        
                        {{-- MENGGUNAKAN LOOPING DATA DARI BACKEND --}}
                        @forelse($galeris as $index => $galeri)
                            <tr>
                                <td class="text-center">{{ $galeris->firstItem() + $index }}</td>
                                
                                {{-- Menampilkan File Asli (Jika Kosong, Pakai Default) --}}
                                <td>
                                    @if($galeri->file_foto)
                                        <img src="{{ asset('uploads/galeri/' . $galeri->file_foto) }}"alt="Preview" class="preview-img shadow-sm" onerror="this.src='{{ asset('images/gaming.jpg') }}'">
                                    @else
                                        <img src="{{ asset('images/gaming.jpg') }}" alt="Default" class="preview-img shadow-sm">
                                    @endif
                                </td>
                                
                                <td class="text-dark">{{ $galeri->judul_foto }}</td>
                                
                                <td><span class="badge bg-secondary">{{ ucfirst($galeri->kategori) }}</span></td>
                                
                                <td>{{ \Carbon\Carbon::parse($galeri->created_at)->format('d/m/Y H:i') }}</td>
                                
                                <td class="text-muted">{{ Str::limit($galeri->deskripsi_foto, 80) }}</td>
                                
                                {{-- Menampilkan Status --}}
                                <td>
                                    @if($galeri->is_active)
                                        <span id="status-row-{{ $galeri->id_galeri }}" class="badge bg-success px-2 py-1" style="border-radius: 4px;">Aktif</span>
                                    @else
                                        <span id="status-row-{{ $galeri->id_galeri }}" class="badge bg-secondary px-2 py-1" style="border-radius: 4px;">Nonaktif</span>
                                    @endif
                                </td>
                                
                                {{-- Aksi CRUD --}}
                                <td>
                                    <div class="d-flex" style="gap: 6px;">
                                        {{-- Tombol Edit Mengarah ke Route Asli --}}
                                        <a href="{{ route('admin.galeri.edit', $galeri->id_galeri) }}" class="btn btn-info btn-sm text-white">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>

                                        {{-- Tombol Toggle Status (Pemicu AJAX) --}}
                                        @if($galeri->is_active)
                                            <button class="btn btn-warning btn-sm text-white btn-toggle-status" data-id="{{ $galeri->id_galeri }}">
                                                <i class="fas fa-ban"></i> Nonaktifkan
                                            </button>
                                        @else
                                            <button class="btn btn-success btn-sm text-white btn-toggle-status" data-id="{{ $galeri->id_galeri }}">
                                                <i class="fas fa-check-circle"></i> Aktifkan
                                            </button>
                                        @endif

                                        {{-- Tombol Hapus Menggunakan Form --}}
                                        <form id="formHapusGaleri{{ $galeri->id_galeri }}" action="{{ route('admin.galeri.destroy', $galeri->id_galeri) }}" method="POST" class="d-inline">
                                            @csrf 
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger btn-sm text-white btn-hapus-galeri" data-form-id="formHapusGaleri{{ $galeri->id_galeri }}" data-title="{{ $galeri->judul_foto }}">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">Belum ada foto di galeri.</td>
                            </tr>
                        @endforelse
                        
                    </tbody>
                </table>
            </div>

            {{-- Menambahkan Pagination Laravel di Bawah Tabel --}}
            @if($galeris->hasPages())
                <div class="card-footer bg-white border-top d-flex justify-content-between align-items-center" style="border-radius: 0 0 8px 8px;">
                    <span class="text-muted" style="font-size: 0.85rem;">
                        Menampilkan {{ $galeris->firstItem() }} sampai {{ $galeris->lastItem() }} dari {{ $galeris->total() }} foto
                    </span>
                    <div>
                        {{ $galeris->appends(request()->query())->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            @endif

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
    document.addEventListener('DOMContentLoaded', function () {
        
        // --- 1. AJAX UNTUK TOGGLE STATUS AKTIF/NONAKTIF ---
        document.querySelectorAll('.btn-toggle-status').forEach(function (btn) {
            btn.addEventListener('click', function () {
                let galeriId = this.dataset.id;
                
                // Panggil endpoint backend
                fetch(`/admin/galeri/${galeriId}/toggle-status`, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const statusBadge = document.getElementById('status-row-' + galeriId);
                        const alertBox = document.getElementById('alertSuccess');
                        const alertMsg = document.getElementById('alertMessage');
                        
                        // Manipulasi DOM setelah berhasil (Sesuai UI Tim-mu)
                        if (data.is_active == 1) {
                            statusBadge.className = 'badge bg-success px-2 py-1';
                            statusBadge.innerText = 'Aktif';
                            this.className = 'btn btn-warning btn-sm text-white btn-toggle-status';
                            this.innerHTML = '<i class="fas fa-ban"></i> Nonaktifkan';
                            alertMsg.innerText = 'Foto galeri berhasil diaktifkan!';
                        } else {
                            statusBadge.className = 'badge bg-secondary px-2 py-1';
                            statusBadge.innerText = 'Nonaktif';
                            this.className = 'btn btn-success btn-sm text-white btn-toggle-status';
                            this.innerHTML = '<i class="fas fa-check-circle"></i> Aktifkan';
                            alertMsg.innerText = 'Foto galeri berhasil dinonaktifkan!';
                        }
                        
                        alertBox.classList.remove('d-none');
                        setTimeout(() => {
                            alertBox.classList.add('d-none');
                        }, 3000);
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        });

        document.querySelectorAll('.btn-hapus-galeri').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.preventDefault(); // Mencegah klik nyasar
                
                // Ambil data dari atribut tombol
                var title = this.getAttribute('data-title') || 'foto ini';
                var formId = this.getAttribute('data-form-id');
                
                // Panggil fungsi sakti buatan timmu!
                if (typeof konfirmasiHapusSubmit === "function") {
                    konfirmasiHapusSubmit(formId, title);
                } else {
                    console.error("File confirm-helper.js belum terpanggil di halaman ini.");
                }
            });
        });
        
    });
</script>
@endpush