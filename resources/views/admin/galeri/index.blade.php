@extends('adminlte::page')

@include('partials.sidebar-admin')

@section('title', 'Kelola Galeri - Play N Chill')

@section('content_header')
    <div class="px-2">
        <h1 class="text-dark fw-bold" style="font-size: 1.8rem;">Kelola Galeri</h1>
        <p class="text-muted mb-2">Manajemen galeri website</p>
    </div>
@stop

<style>
    .custom-option:hover {
        background-color: #f3f4f6; 
    }
</style>

@section('content')
<div class="container-fluid px-2 pb-4">
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-3 shadow-sm" style="border-radius: 8px; background-color: #10b981; color: white; border: none;">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="close text-white" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div id="alertSuccess" class="alert alert-success d-none mb-3 shadow-sm" style="border-radius: 8px; background-color: #10b981; color: white; border: none;">
        <i class="fas fa-check-circle me-2"></i> <span id="alertMessage">Status berhasil diubah!</span>
    </div>

   <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px; overflow: visible;"> {{-- FIX 1: Ubah overflow dari hidden menjadi visible --}}
        <div class="card-body p-4">
            <form method="GET" action="{{ route('admin.galeri.index') }}">
                <div class="row align-items-center g-3">
                    
                    {{-- Input Pencarian --}}
                    <div class="col-12 col-lg-4">
                        <div class="input-group transition-all" style="border: 1px solid #d1d5db; border-radius: 8px; background-color: #ffffff; overflow: hidden; height: 42px;">
                            <span class="input-group-text border-0 bg-transparent text-muted px-3">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" name="search" value="{{ request('search') }}" class="form-control border-0 bg-transparent ps-0 shadow-none h-100" placeholder="Cari judul foto..." style="color: #4b5563; font-size: 0.95rem;">
                        </div>
                    </div>

                    {{-- Dropdown Kategori --}}
                    <div class="col-12 col-md-6 col-lg-4 d-flex align-items-center mt-3 mt-lg-0">
                        <label class="mb-0 me-3 text-muted fw-bold" style="white-space: nowrap; font-size: 0.9rem;">Kategori:</label>
                        
                        <div class="position-relative custom-dropdown-container w-100">
                            <input type="hidden" name="kategori" id="kategoriInput" value="{{ request('kategori') }}">
                            
                            <div class="form-control shadow-none d-flex justify-content-between align-items-center transition-all" id="kategoriSelectBox" onclick="toggleDropdown()" style="cursor: pointer; background-color: #ffffff; height: 42px; border: 1px solid #d1d5db; border-radius: 8px; padding: 0 1rem;">
                                <span id="kategoriSelectedText" style="color: #4b5563; font-size: 0.95rem;">
                                    {{ request('kategori') ?: 'Semua' }}
                                </span>
                                <i class="fas fa-chevron-down text-muted" style="font-size: 0.75rem;"></i>
                            </div>
                            
                            {{-- Pilihan Dropdown --}}
                            <div class="shadow-sm" id="kategoriOptions" style="display: none; position: absolute; width: 100%; z-index: 1000; border: 1px solid #d1d5db; border-top: none; border-radius: 0 0 8px 8px; background-color: #ffffff; overflow: hidden;">
                                <div class="custom-option text-center py-2" onclick="selectOption('')" style="border-bottom: 1px solid #f3f4f6; cursor: pointer; color: #4b5563; transition: 0.2s; font-size: 0.95rem;">Semua</div>
                                <div class="custom-option text-center py-2" onclick="selectOption('Reguler')" style="border-bottom: 1px solid #f3f4f6; cursor: pointer; color: #4b5563; transition: 0.2s; font-size: 0.95rem;">Reguler</div>
                                <div class="custom-option text-center py-2" onclick="selectOption('Private - Gaming')" style="border-bottom: 1px solid #f3f4f6; cursor: pointer; color: #4b5563; transition: 0.2s; font-size: 0.95rem;">Private - Gaming</div>
                                <div class="custom-option text-center py-2" onclick="selectOption('Private - Nonton')" style="border-bottom: 1px solid #f3f4f6; cursor: pointer; color: #4b5563; transition: 0.2s; font-size: 0.95rem;">Private - Nonton</div>
                                <div class="custom-option text-center py-2" onclick="selectOption('Private - Karaoke')" style="cursor: pointer; color: #4b5563; transition: 0.2s; font-size: 0.95rem;">Private - Karaoke</div>
                            </div>
                        </div>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="col-12 col-md-6 col-lg-4 d-flex justify-content-lg-end justify-content-between align-items-center mt-3 mt-lg-0">
                        <div class="d-flex align-items-center">
                            {{-- Kita paksa pakai style margin-right agar pasti berjarak --}}
                            <button type="submit" class="btn btn-primary btn-action mr-2" style="margin-right: 10px;">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                            <a href="{{ route('admin.galeri.index') }}" class="btn btn-secondary btn-action text-white mr-3" style="background-color: #64748b; border: none; margin-right: 20px;">
                                <i class="fas fa-sync-alt"></i> Reset
                            </a>
                        </div>
                        
                        <a href="{{ route('admin.galeri.create') }}" class="btn btn-purple btn-action">
                            <i class="fas fa-plus"></i> Tambah Foto
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 custom-table">
                    <thead style="background-color: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                        <tr>
                            <th class="text-center py-3 border-0 text-muted fw-bold" style="width: 5%; font-size: 0.85rem; text-transform: uppercase;">#</th>
                            <th class="py-3 border-0 text-muted fw-bold" style="font-size: 0.85rem; text-transform: uppercase;">Preview</th>
                            <th class="py-3 border-0 text-muted fw-bold" style="font-size: 0.85rem; text-transform: uppercase;">Judul Foto</th>
                            <th class="py-3 border-0 text-muted fw-bold" style="font-size: 0.85rem; text-transform: uppercase;">Sub Kategori</th>
                            <th class="py-3 border-0 text-muted fw-bold text-nowrap" style="font-size: 0.85rem; text-transform: uppercase;">Tanggal Upload</th>
                            <th class="py-3 border-0 text-muted fw-bold" style="width: 20%; font-size: 0.85rem; text-transform: uppercase;">Deskripsi</th>
                            <th class="py-3 border-0 text-muted fw-bold text-nowrap" style="font-size: 0.85rem; text-transform: uppercase;">Status</th>
                            <th class="py-3 border-0 text-muted fw-bold text-nowrap" style="font-size: 0.85rem; text-transform: uppercase;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 0.9rem; color: #334155;">
                        
                        @forelse($galeris as $index => $galeri)
                            <tr>
                                <td class="text-center py-3">{{ $galeris->firstItem() + $index }}</td>
                                
                                <td class="py-3">
                                    @if($galeri->file_foto)
                                        <img src="{{ asset('uploads/galeri/' . $galeri->file_foto) }}" alt="Preview" class="preview-img shadow-sm" onerror="this.src='{{ asset('images/gaming.jpg') }}'">
                                    @else
                                        <img src="{{ asset('images/gaming.jpg') }}" alt="Default" class="preview-img shadow-sm">
                                    @endif
                                </td>
                                
                                <td class="fw-bold text-dark py-3">{{ $galeri->judul_foto }}</td>
                                
                                <td class="py-3">
                                    <span class="badge" style="background-color: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; font-weight: 600; padding: 6px 10px;">
                                        {{ ucfirst($galeri->kategori) }}
                                    </span>
                                </td>
                                
                                <td class="text-nowrap text-muted py-3">
                                    {{ \Carbon\Carbon::parse($galeri->created_at)->format('d/m/Y') }} <br>
                                    <small style="font-size: 0.75rem;">{{ \Carbon\Carbon::parse($galeri->created_at)->format('H:i') }} WIB</small>
                                </td>
                                
                                <td class="text-muted py-3" style="line-height: 1.4;">{{ Str::limit($galeri->deskripsi_foto, 70) }}</td>
                                
                                <td class="text-nowrap py-3">
                                    @if($galeri->is_active)
                                        <span id="status-row-{{ $galeri->id_galeri }}" class="badge bg-success" style="padding: 6px 10px; font-weight: 600; letter-spacing: 0.3px;">Aktif</span>
                                    @else
                                        <span id="status-row-{{ $galeri->id_galeri }}" class="badge bg-secondary" style="padding: 6px 10px; font-weight: 600; letter-spacing: 0.3px;">Nonaktif</span>
                                    @endif
                                </td>
                                
                                <td class="py-3">
                                    <div class="d-flex flex-wrap align-items-center" style="gap: 8px;">
                                        <a href="{{ route('admin.galeri.edit', $galeri->id_galeri) }}" class="btn btn-info btn-action text-white">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>

                                        @if($galeri->is_active)
                                            <button class="btn btn-warning btn-action text-white btn-toggle-status" data-id="{{ $galeri->id_galeri }}">
                                                <i class="fas fa-ban"></i> Nonaktifkan
                                            </button>
                                        @else
                                            <button class="btn btn-success btn-action text-white btn-toggle-status" data-id="{{ $galeri->id_galeri }}">
                                                <i class="fas fa-check-circle"></i> Aktifkan
                                            </button>
                                        @endif

                                        <form id="formHapusGaleri{{ $galeri->id_galeri }}" action="{{ route('admin.galeri.destroy', $galeri->id_galeri) }}" method="POST" class="d-inline m-0">
                                            @csrf 
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger btn-action text-white btn-hapus-galeri" data-form-id="formHapusGaleri{{ $galeri->id_galeri }}" data-title="{{ $galeri->judul_foto }}">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-5">
                                    <i class="fas fa-images mb-3" style="font-size: 2.5rem; color: #cbd5e1;"></i>
                                    <p class="mb-0">Belum ada foto di galeri.</p>
                                </td>
                            </tr>
                        @endforelse
                        
                    </tbody>
                </table>
            </div>

            @if($galeris->hasPages())
                <div class="card-footer bg-white border-top d-flex justify-content-between align-items-center py-3" style="border-radius: 0 0 12px 12px;">
                    <span class="text-muted fw-bold" style="font-size: 0.85rem;">
                        Menampilkan {{ $galeris->firstItem() }} - {{ $galeris->lastItem() }} dari total {{ $galeris->total() }} foto
                    </span>
                    <div class="pagination-custom">
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
        background-color: #7c3aed;
        color: #ffffff;
        border: none;
    }
    .btn-purple:hover {
        background-color: #6d28d9;
        color: #ffffff;
    }
    
    .btn-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        font-weight: 600;
        font-size: 0.8rem;
        padding: 6px 12px;
        border-radius: 6px;
        transition: all 0.2s ease;
        height: 32px;
        border: none;
    }
    .btn-action:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    
    .preview-img {
        width: 96px;
        height: 72px;
        object-fit: cover;
        border-radius: 6px;
        border: 1px solid #e2e8f0;
    }
    
    .custom-table td {
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }
    
    .input-group:focus-within, .custom-dropdown-container .form-control:focus-within {
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    .pagination-custom .pagination {
        margin-bottom: 0;
    }
</style>
@endpush

@push('js')
<script>
    function toggleDropdown() {
        const options = document.getElementById('kategoriOptions');
        const box = document.getElementById('kategoriSelectBox');
        
        if (options.style.display === 'none' || options.style.display === '') {
            options.style.display = 'block';
            box.style.borderBottomLeftRadius = '0';
            box.style.borderBottomRightRadius = '0';
        } else {
            options.style.display = 'none';
            box.style.borderBottomLeftRadius = '8px';
            box.style.borderBottomRightRadius = '8px';
        }
    }

    function selectOption(value) {
        let displayText = value === '' ? 'Semua' : value;
        
        document.getElementById('kategoriSelectedText').innerHTML = displayText;
        document.getElementById('kategoriInput').value = value;
        toggleDropdown();
    }

    document.addEventListener('click', function(event) {
        const container = document.querySelector('.custom-dropdown-container');
        if (container && !container.contains(event.target)) {
            const options = document.getElementById('kategoriOptions');
            const box = document.getElementById('kategoriSelectBox');
            if (options && box) {
                options.style.display = 'none';
                box.style.borderBottomLeftRadius = '8px';
                box.style.borderBottomRightRadius = '8px';
            }
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
        
        document.querySelectorAll('.btn-toggle-status').forEach(function (btn) {
            btn.addEventListener('click', function () {
                let galeriId = this.dataset.id;
                
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
                        
                        if (data.is_active == 1) {
                            statusBadge.className = 'badge bg-success';
                            statusBadge.innerText = 'Aktif';
                            this.className = 'btn btn-warning btn-action text-white btn-toggle-status';
                            this.innerHTML = '<i class="fas fa-ban"></i> Nonaktifkan';
                            alertMsg.innerText = 'Foto galeri berhasil diaktifkan!';
                        } else {
                            statusBadge.className = 'badge bg-secondary';
                            statusBadge.innerText = 'Nonaktif';
                            this.className = 'btn btn-success btn-action text-white btn-toggle-status';
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
                e.preventDefault(); 
                
                var title = this.getAttribute('data-title') || 'foto ini';
                var formId = this.getAttribute('data-form-id');
                
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