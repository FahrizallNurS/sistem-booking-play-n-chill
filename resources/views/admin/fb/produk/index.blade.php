@extends('adminlte::page')

@include('partials.sidebar-admin')

@section('title', 'Kelola Produk F&B - Play N Chill')

@section('content_header')
    <div class="px-2">
        <h1 class="text-dark fw-bold" style="font-size: 1.8rem;">Kelola Produk F&B</h1>
        <p class="text-muted mb-2">Manajemen inventaris makanan dan minuman.</p>
        <hr class="mt-0 mb-3" style="border-color: #d1d5db;">
    </div>
@stop

@section('content')
<div class="container-fluid px-2 pb-4">
    
    {{-- ALERT NOTIFIKASI BERHASIL --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert" style="border-radius: 6px; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb;">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- 1. BAGIAN TOOLBAR (Pencarian, Filter, Tombol Tambah) --}}
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 8px;">
        <div class="card-body py-3">
            {{-- Form Pencarian & Filter --}}
            <form action="{{ route('admin.fb.produk.index') }}" method="GET" id="filterForm">
                <div class="row align-items-center g-3">
                    
                    {{-- Input Pencarian --}}
                    <div class="col-12 col-md-4 col-lg-3">
                        <div class="input-group" style="border: 1px solid #d1d5db; border-radius: 6px; background-color: #f9fafb; overflow: hidden;">
                            <span class="input-group-text border-0 bg-transparent text-muted" style="padding-right: 8px;">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" name="search" value="{{ request('search') }}" class="form-control border-0 bg-transparent ps-0 shadow-none" placeholder="Cari nama atau SKU..." style="color: #4b5563; font-size: 0.9rem;">
                        </div>
                    </div>

                    {{-- Dropdown Kategori + Tombol Filter & Reset --}}
                    <div class="col-12 col-md-8 col-lg-6 d-flex align-items-center flex-wrap" style="gap: 12px;">
                        <label class="mb-0 text-muted fw-normal" style="white-space: nowrap; font-size: 0.9rem;">Kategori:</label>
                        
                        {{-- Custom Dropdown Kategori --}}
                        <div class="position-relative custom-dropdown-container" style="min-width: 170px;">
                            <input type="hidden" name="kategori" id="kategoriInput" value="{{ request('kategori', 'Semua Produk') }}">
                            
                            <div class="form-control shadow-none d-flex justify-content-between align-items-center" id="kategoriSelectBox" onclick="toggleDropdown()" style="cursor: pointer; background-color: #f9fafb; min-height: 36px; border: 1px solid #d1d5db; border-radius: 6px; padding: 0.375rem 0.75rem;">
                                <span id="kategoriSelectedText" style="color: #4b5563; font-size: 0.9rem;">{{ request('kategori', 'Semua Produk') }}</span>
                                <i class="fas fa-chevron-down text-muted" style="font-size: 0.7rem;"></i>
                            </div>
                            
                            <div class="shadow-sm" id="kategoriOptions" style="position: absolute; width: 100%; z-index: 1000; border: 1px solid #d1d5db; border-top: none; border-radius: 0 0 6px 6px; background-color: #f9fafb; overflow: hidden;">
                                <div class="custom-option text-center py-2" onclick="selectOption('Semua Produk')" style="border-bottom: 1px solid #e5e7eb; cursor: pointer; color: #374151; transition: 0.2s; font-size: 0.9rem;">Semua Produk</div>
                                @foreach($kategoriList as $kat)
                                    <div class="custom-option text-center py-2" onclick="selectOption('{{ $kat }}')" style="border-bottom: 1px solid #e5e7eb; cursor: pointer; color: #374151; transition: 0.2s; font-size: 0.9rem;">{{ $kat }}</div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Tombol Filter & Reset --}}
                        <div class="d-flex" style="gap: 8px;">
                            <button type="submit" class="btn btn-primary btn-sm px-3" style="border-radius: 6px; font-weight: 500; background-color: #007bff; border: none; padding-top: 0.4rem; padding-bottom: 0.4rem;">
                                <i class="fas fa-filter me-1"></i> Filter
                            </button>
                            <a href="{{ route('admin.fb.produk.index') }}" class="btn btn-secondary btn-sm px-3 text-white" style="background-color: #6c757d; border-radius: 6px; border: none; font-weight: 500; padding-top: 0.4rem; padding-bottom: 0.4rem; text-decoration: none;">
                                <i class="fas fa-sync-alt me-1"></i> Reset
                            </a>
                        </div>
                    </div>

                    {{-- Tombol Tambah Produk --}}
                    <div class="col-12 col-lg-3 text-lg-end mt-3 mt-lg-0 ms-auto">
                        <a href="{{ url('/admin/fb/produk/create') }}" class="btn btn-purple px-4 d-inline-block" style="border-radius: 6px; padding-top: 0.4rem; padding-bottom: 0.4rem; text-decoration: none;">
                            <i class="fas fa-plus me-1"></i> Tambah Produk
                        </a>
                    </div>

                </div>
            </form>
        </div>
    </div>

    {{-- 2. BAGIAN TABEL DATA --}}
    <div class="card border-0 shadow-sm" style="border-radius: 8px;">
        <div class="card-body p-0">
            <div class="table-responsive custom-scrollbar">
                <table class="table table-hover align-middle mb-0 table-nowrap">
                    <thead class="bg-light text-dark fw-bold" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                        <tr>
                            <th class="text-center py-3 border-0" style="width: 50px;">NO</th>
                            <th class="border-0" style="width: 80px;">FOTO</th>
                            <th class="border-0">NAMA PRODUK</th>
                            <th class="border-0">KATEGORI</th>
                            <th class="border-0">SUB KATEGORI</th>
                            <th class="border-0">HARGA BELI</th>
                            <th class="border-0">HARGA JUAL</th>
                            <th class="border-0">SKU</th>
                            <th class="border-0 text-center">STOCK</th>
                            <th class="border-0 text-center">STATUS</th>
                            <th class="border-0 text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 0.85rem; color: #4b5563;">
                        
                        @forelse($produks as $index => $produk)
                            <tr>
                                <td class="text-center">{{ $produks->firstItem() + $index }}</td>
                                <td>
                                    <img src="{{ asset('uploads/fb/' . $produk->foto) }}" alt="Foto" class="preview-img shadow-sm" onerror="this.onerror=null; this.src='{{ asset('images/logo_dumb.png') }}';">
                                </td>
                                <td class="text-dark fw-bold">{{ $produk->nama_produk }}</td>
                                <td>{{ $produk->subKategori->kategori_produk ?? '-' }}</td>
                                <td>{{ $produk->subKategori->sub_kategori_produk ?? '-' }}</td>
                                <td>Rp {{ number_format($produk->harga_beli, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($produk->harga_jual, 0, ',', '.') }}</td>
                                <td>{{ $produk->sku }}</td>
                                <td class="text-center fw-bold {{ $produk->stock <= 5 ? 'text-danger' : '' }}">{{ $produk->stock }}</td>
                                <td class="text-center">
                                    @if($produk->is_active)
                                        <span class="badge bg-success px-2 py-1 rounded">Aktif</span>
                                    @else
                                        <span class="badge bg-danger px-2 py-1 rounded">Nonaktif</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center" style="gap: 6px;">
                                        <a href="{{ url('/admin/fb/produk/edit/' . $produk->id_produk) }}" class="btn btn-info btn-sm text-white btn-action text-decoration-none" title="Edit">
                                            <i class="fas fa-edit me-1"></i> Edit
                                        </a>
                                        
                                        {{-- Form Toggle Status --}}
                                        <form action="{{ route('admin.fb.produk.toggle-status', $produk->id_produk) }}" method="POST" class="d-inline m-0 p-0">
                                            @csrf
                                            @method('PATCH')
                                            @if($produk->is_active)
                                                <button type="submit" class="btn btn-warning btn-sm text-dark btn-action" title="Nonaktifkan" style="font-weight: 500;">
                                                    <i class="fas fa-ban me-1"></i> Nonaktifkan
                                                </button>
                                            @else
                                                <button type="submit" class="btn btn-success btn-sm text-white btn-action" title="Aktifkan" style="font-weight: 500;">
                                                    <i class="fas fa-check me-1"></i> Aktifkan
                                                </button>
                                            @endif
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center py-4 text-muted">Belum ada data produk F&B yang ditemukan.</td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

            {{-- 3. BAGIAN PAGINATION BAWAH (Otomatis dari Laravel) --}}
            @if($produks->hasPages())
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center p-3 border-top bg-light">
                    <span class="text-muted" style="font-size: 0.85rem;">
                        Menampilkan {{ $produks->firstItem() }} hingga {{ $produks->lastItem() }} dari {{ $produks->total() }} entri
                    </span>
                    <nav class="mt-2 mt-md-0">
                        {{ $produks->appends(request()->query())->links('pagination::bootstrap-4') }}
                    </nav>
                </div>
            @endif
            
        </div>
    </div>

</div>
@stop

@push('css')
<style>
    .table-nowrap th, .table-nowrap td {
        white-space: nowrap;
        vertical-align: middle;
    }
    .custom-scrollbar::-webkit-scrollbar { height: 8px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #9ca3af; }
    .btn-purple { background-color: #5b21b6; color: #ffffff; font-weight: 500; border: none; transition: 0.2s; }
    .btn-purple:hover { background-color: #4c1d95; color: #ffffff; }
    .preview-img { width: 40px; height: 40px; object-fit: cover; background-color: #fff; border: 1px solid #e5e7eb; border-radius: 4px; padding: 2px; }
    .btn-action { font-size: 0.75rem; padding: 0.25rem 0.5rem; border-radius: 4px; min-width: 90px; }
    .table td { border-bottom: 1px solid #f3f4f6; }
    .custom-option:hover { background-color: #f3f4f6; }
    #kategoriOptions {
        visibility: hidden; opacity: 0; transform: translateY(-10px); 
        transition: opacity 0.2s ease, transform 0.2s ease, visibility 0.2s; pointer-events: none; 
    }
    #kategoriOptions.show-dropdown {
        visibility: visible; opacity: 1; transform: translateY(0); pointer-events: auto;
    }

    .pagination svg { display: none; }
</style>
@endpush

@push('js')
<script>

    setTimeout(function() {
        let alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            let bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 3000);

    function toggleDropdown() {
        const options = document.getElementById('kategoriOptions');
        const box = document.getElementById('kategoriSelectBox');
        options.classList.toggle('show-dropdown');
        
        if (options.classList.contains('show-dropdown')) {
            box.style.borderBottomLeftRadius = '0';
            box.style.borderBottomRightRadius = '0';
        } else {
            box.style.borderBottomLeftRadius = '6px';
            box.style.borderBottomRightRadius = '6px';
        }
    }

    function selectOption(value) {
        document.getElementById('kategoriSelectedText').innerHTML = value;
        document.getElementById('kategoriInput').value = value;
        toggleDropdown();
    }

    document.addEventListener('click', function(event) {
        const container = document.querySelector('.custom-dropdown-container');
        if (container && !container.contains(event.target)) {
            const options = document.getElementById('kategoriOptions');
            const box = document.getElementById('kategoriSelectBox');
            if(options && options.classList.contains('show-dropdown')) {
                options.classList.remove('show-dropdown');
                box.style.borderBottomLeftRadius = '6px';
                box.style.borderBottomRightRadius = '6px';
            }
        }
    });
</script>
@endpush