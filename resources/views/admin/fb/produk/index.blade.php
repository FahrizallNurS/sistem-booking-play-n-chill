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
    
    {{-- ALERT NOTIFIKASI (Awalnya Disembunyikan) --}}
    <div id="alertSuccess" class="alert alert-success d-none mb-3" style="border-radius: 0; background-color: #28a745; color: white; border: none;">
        <span id="alertMessage">Status produk berhasil diubah!</span>
    </div>

    {{-- 1. BAGIAN TOOLBAR (Pencarian, Filter, Tombol Tambah) --}}
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 8px;">
        <div class="card-body py-3">
            <div class="row align-items-center g-3">
                
                {{-- Input Pencarian --}}
                <div class="col-12 col-md-4 col-lg-3">
                    <div class="input-group" style="border: 1px solid #d1d5db; border-radius: 6px; background-color: #f9fafb; overflow: hidden;">
                        <span class="input-group-text border-0 bg-transparent text-muted" style="padding-right: 8px;">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" class="form-control border-0 bg-transparent ps-0 shadow-none" placeholder="Cari nama produk..." style="color: #4b5563; font-size: 0.9rem;">
                    </div>
                </div>

                {{-- Dropdown Kategori + Tombol Filter & Reset --}}
                <div class="col-12 col-md-8 col-lg-6 d-flex align-items-center flex-wrap" style="gap: 12px;">
                    <label class="mb-0 text-muted fw-normal" style="white-space: nowrap; font-size: 0.9rem;">Kategori:</label>
                    
                    {{-- Custom Dropdown Kategori (Sesuai Desain Stitch AI) --}}
                    <div class="position-relative custom-dropdown-container" style="min-width: 170px;">
                        <input type="hidden" name="kategori" id="kategoriInput" value="Semua Produk">
                        
                        <div class="form-control shadow-none d-flex justify-content-between align-items-center" id="kategoriSelectBox" onclick="toggleDropdown()" style="cursor: pointer; background-color: #f9fafb; min-height: 36px; border: 1px solid #d1d5db; border-radius: 6px; padding: 0.375rem 0.75rem;">
                            <span id="kategoriSelectedText" style="color: #4b5563; font-size: 0.9rem;">Semua Produk</span>
                            <i class="fas fa-chevron-down text-muted" style="font-size: 0.7rem;"></i>
                        </div>
                        
                        <div class="shadow-sm" id="kategoriOptions" style="position: absolute; width: 100%; z-index: 1000; border: 1px solid #d1d5db; border-top: none; border-radius: 0 0 6px 6px; background-color: #f9fafb; overflow: hidden;">
                            <div class="custom-option text-center py-2" onclick="selectOption('Semua Produk')" style="border-bottom: 1px solid #e5e7eb; cursor: pointer; color: #374151; transition: 0.2s; font-size: 0.9rem;">Semua Produk</div>
                            <div class="custom-option text-center py-2" onclick="selectOption('Makanan Ringan')" style="border-bottom: 1px solid #e5e7eb; cursor: pointer; color: #374151; transition: 0.2s; font-size: 0.9rem;">Makanan Ringan</div>
                            <div class="custom-option text-center py-2" onclick="selectOption('Makanan Berat')" style="border-bottom: 1px solid #e5e7eb; cursor: pointer; color: #374151; transition: 0.2s; font-size: 0.9rem;">Makanan Berat</div>
                            <div class="custom-option text-center py-2" onclick="selectOption('Minuman')" style="cursor: pointer; color: #374151; transition: 0.2s; font-size: 0.9rem;">Minuman</div>
                        </div>
                    </div>

                    {{-- Tombol Filter & Reset (Dibungkus div dengan gap presisi) --}}
                    <div class="d-flex" style="gap: 8px;">
                        <button class="btn btn-primary btn-sm px-3" style="border-radius: 6px; font-weight: 500; background-color: #007bff; border: none; padding-top: 0.4rem; padding-bottom: 0.4rem;">
                            <i class="fas fa-filter me-1"></i> Filter
                        </button>
                        <button class="btn btn-secondary btn-sm px-3 text-white" style="background-color: #6c757d; border-radius: 6px; border: none; font-weight: 500; padding-top: 0.4rem; padding-bottom: 0.4rem;">
                            <i class="fas fa-sync-alt me-1"></i> Reset
                        </button>
                    </div>
                </div>

                {{-- Tombol Tambah Produk --}}
                <div class="col-12 col-lg-3 text-lg-end mt-3 mt-lg-0">
                    <a href="{{ url('/admin/fb/produk/create') }}" class="btn btn-purple px-4 d-inline-block" style="border-radius: 6px; padding-top: 0.4rem; padding-bottom: 0.4rem; text-decoration: none;">
                        <i class="fas fa-plus me-1"></i> Tambah Produk
                    </a>
                </div>

            </div>
        </div>
    </div>

    {{-- 2. BAGIAN TABEL DATA --}}
    <div class="card border-0 shadow-sm" style="border-radius: 8px;">
        <div class="card-body p-0">
            {{-- DIV INI KUNCI AGAR TABEL BISA DIGESER (SCROLL) --}}
            <div class="table-responsive custom-scrollbar">
                {{-- Class table-nowrap memastikan teks tidak turun ke bawah --}}
                <table class="table table-hover align-middle mb-0 table-nowrap">
                    <thead class="bg-light text-dark fw-bold" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                        <tr>
                            <th class="text-center py-3 border-0" style="width: 50px;">NO</th>
                            <th class="border-0" style="width: 80px;">FOTO</th>
                            <th class="border-0">NAMA PRODUK</th>
                            <th class="border-0">KATEGORI PRODUK</th>
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
                        
                        {{-- Data 1 --}}
                        <tr>
                            <td class="text-center">1</td>
                            <td><img src="{{ asset('images/indomie.jpg') }}" alt="Foto" class="preview-img shadow-sm" onerror="this.onerror=null; this.src='{{ asset('images/logo_dumb.png') }}';"></td>
                            <td class="text-dark fw-bold">Indomie Goreng</td>
                            <td>Internal</td>
                            <td>Makanan Berat</td>
                            <td>Rp. 3.000,00</td>
                            <td>Rp. 7.000,00</td>
                            <td>PNC-211</td>
                            <td class="text-center">12</td>
                            <td class="text-center"><span id="status-row-1" class="badge bg-success px-2 py-1 rounded">Aktif</span></td>
                            <td>
                                <div class="d-flex justify-content-center" style="gap: 6px;">
                                    <a href="{{ url('/admin/fb/produk/edit') }}" class="btn btn-warning text-dark fw-bold btn-action text-decoration-none">
                                        <i class="fas fa-edit me-1"></i> Edit
                                    </a>
                                    <button class="btn btn-warning text-dark fw-bold btn-action" onclick="toggleStatus(this, 'row-1')"><i class="fas fa-ban me-1"></i> Nonaktifkan</button>
                                </div>
                            </td>
                        </tr>

                        {{-- Data 2 --}}
                        <tr>
                            <td class="text-center">2</td>
                            <td><img src="{{ asset('images/soda.jpg') }}" alt="Foto" class="preview-img shadow-sm" onerror="this.onerror=null; this.src='{{ asset('images/logo_dumb.png') }}';"></td>
                            <td class="text-dark fw-bold">Soda Gembira</td>
                            <td>Internal</td>
                            <td>Minuman</td>
                            <td>Rp. 8.000,00</td>
                            <td>Rp. 12.000,00</td>
                            <td>PNC-111</td>
                            <td class="text-center">6</td>
                            <td class="text-center"><span id="status-row-2" class="badge bg-danger px-2 py-1 rounded">Nonaktif</span></td>
                            <td>
                                <div class="d-flex justify-content-center" style="gap: 6px;">
                                   <a href="{{ url('/admin/fb/produk/edit') }}" class="btn btn-warning text-dark fw-bold btn-action text-decoration-none">
                                        <i class="fas fa-edit me-1"></i> Edit
                                    </a>
                                    <button class="btn btn-success text-white fw-bold btn-action" onclick="toggleStatus(this, 'row-2')"><i class="fas fa-check me-1"></i> Aktifkan</button>
                                </div>
                            </td>
                        </tr>

                        {{-- Data 3 --}}
                        <tr>
                            <td class="text-center">3</td>
                            <td><img src="{{ asset('images/seblak.jpg') }}" alt="Foto" class="preview-img shadow-sm" onerror="this.onerror=null; this.src='{{ asset('images/logo_dumb.png') }}';"></td>
                            <td class="text-dark fw-bold">Seblak</td>
                            <td>Seblak</td>
                            <td>Makanan</td>
                            <td>Rp. 8.000,00</td>
                            <td>Rp. 12.000,00</td>
                            <td>PNC-311</td>
                            <td class="text-center">9</td>
                            <td class="text-center"><span id="status-row-3" class="badge bg-success px-2 py-1 rounded">Aktif</span></td>
                            <td>
                                <div class="d-flex justify-content-center" style="gap: 6px;">
                                    <a href="{{ url('/admin/fb/produk/edit') }}" class="btn btn-warning text-dark fw-bold btn-action text-decoration-none">
                                        <i class="fas fa-edit me-1"></i> Edit
                                    </a>
                                    <button class="btn btn-warning text-dark fw-bold btn-action" onclick="toggleStatus(this, 'row-3')"><i class="fas fa-ban me-1"></i> Nonaktifkan</button>
                                </div>
                            </td>
                        </tr>

                        {{-- Data 4 --}}
                        <tr>
                            <td class="text-center">4</td>
                            <td><img src="{{ asset('images/roti.jpg') }}" alt="Foto" class="preview-img shadow-sm" onerror="this.onerror=null; this.src='{{ asset('images/logo_dumb.png') }}';"></td>
                            <td class="text-dark fw-bold">Roti Aoka</td>
                            <td>Cowork</td>
                            <td>Makanan Ringan</td>
                            <td>Rp. 2.000,00</td>
                            <td>Rp. 2.500,00</td>
                            <td>PNC-212</td>
                            <td class="text-center">14</td>
                            <td class="text-center"><span id="status-row-4" class="badge bg-danger px-2 py-1 rounded">Nonaktif</span></td>
                            <td>
                                <div class="d-flex justify-content-center" style="gap: 6px;">
                                    <a href="{{ url('/admin/fb/produk/edit') }}" class="btn btn-warning text-dark fw-bold btn-action text-decoration-none">
                                        <i class="fas fa-edit me-1"></i> Edit
                                    </a>
                                    <button class="btn btn-success text-white fw-bold btn-action" onclick="toggleStatus(this, 'row-4')"><i class="fas fa-check me-1"></i> Aktifkan</button>
                                </div>
                            </td>
                        </tr>

                        {{-- Data 5 --}}
                        <tr>
                            <td class="text-center">5</td>
                            <td><img src="{{ asset('images/kopi.jpg') }}" alt="Foto" class="preview-img shadow-sm" onerror="this.onerror=null; this.src='{{ asset('images/logo_dumb.png') }}';"></td>
                            <td class="text-dark fw-bold">Top Kopi Gula Aren</td>
                            <td>Cowork</td>
                            <td>Minuman</td>
                            <td>Rp. 3.000,00</td>
                            <td>Rp. 5.000,00</td>
                            <td>PNC-112</td>
                            <td class="text-center">24</td>
                            <td class="text-center"><span id="status-row-5" class="badge bg-success px-2 py-1 rounded">Aktif</span></td>
                            <td>
                                <div class="d-flex justify-content-center" style="gap: 6px;">
                                    <a href="{{ url('/admin/fb/produk/edit') }}" class="btn btn-warning text-dark fw-bold btn-action text-decoration-none">
                                        <i class="fas fa-edit me-1"></i> Edit
                                    </a>
                                    <button class="btn btn-warning text-dark fw-bold btn-action" onclick="toggleStatus(this, 'row-5')"><i class="fas fa-ban me-1"></i> Nonaktifkan</button>
                                </div>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>

            {{-- 3. BAGIAN PAGINATION BAWAH --}}
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center p-3 border-top bg-light">
                <span class="text-muted" style="font-size: 0.85rem;">Menampilkan 1 hingga 5 dari 5 entri</span>
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
    .table-nowrap th, .table-nowrap td {
        white-space: nowrap;
        vertical-align: middle;
    }

    .custom-scrollbar::-webkit-scrollbar {
        height: 8px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1; 
        border-radius: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #d1d5db; 
        border-radius: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #9ca3af; 
    }

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
        width: 40px;
        height: 40px;
        object-fit: contain;
        background-color: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 4px;
        padding: 2px;
    }

    .btn-action {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        min-width: 90px;
    }
    
    .table td {
        border-bottom: 1px solid #f3f4f6;
    }

    .custom-option:hover {
        background-color: #f3f4f6; 
    }

    #kategoriOptions {
        visibility: hidden;
        opacity: 0;
        transform: translateY(-10px); 
        transition: opacity 0.2s ease, transform 0.2s ease, visibility 0.2s;
        pointer-events: none; 
    }
    #kategoriOptions.show-dropdown {
        visibility: visible;
        opacity: 1;
        transform: translateY(0); 
        pointer-events: auto;
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
            statusBadge.className = 'badge bg-danger px-2 py-1 rounded';
            statusBadge.innerText = 'Nonaktif';
            btnElement.className = 'btn btn-success text-white fw-bold btn-action';
            btnElement.innerHTML = '<i class="fas fa-check me-1"></i> Aktifkan';
            
            alertMsg.innerText = 'Status produk berhasil dinonaktifkan!';
        } else {
            statusBadge.className = 'badge bg-success px-2 py-1 rounded';
            statusBadge.innerText = 'Aktif';
            btnElement.className = 'btn btn-warning text-dark fw-bold btn-action';
            btnElement.innerHTML = '<i class="fas fa-ban me-1"></i> Nonaktifkan';
            
            alertMsg.innerText = 'Status produk berhasil diaktifkan!';
        }

        alertBox.classList.remove('d-none');
        setTimeout(() => {
            alertBox.classList.add('d-none');
        }, 3000);
    }

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