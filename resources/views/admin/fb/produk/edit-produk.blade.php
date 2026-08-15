@extends('adminlte::page')

@include('partials.sidebar-admin')

@section('title', 'Edit Produk F&B - Play N Chill')

@section('content_header')
    <div class="px-2">
        <h1 class="text-dark fw-bold" style="font-size: 1.8rem;">Kelola Produk F&B</h1>
        <p class="text-muted mb-2">Manajemen inventaris makanan dan minuman.</p>
        <hr class="mt-0 mb-3" style="border-color: #d1d5db;">
    </div>
@stop

@section('content')
<div class="container-fluid px-2 pb-5">
    
    <div class="card border-0 shadow-sm" style="border-radius: 8px; overflow: hidden;">
        <div class="card-header text-center py-3 border-bottom" style="background-color: #faf5f9; border-color: #e5e7eb !important;">
            <h5 class="mb-0 fw-bold text-dark" style="font-size: 1.1rem;">Edit Produk F&B</h5>
        </div>
        
        <div class="card-body p-4 p-md-5">

            {{-- Menampilkan pesan error validasi jika ada --}}
            @if ($errors->any())
                <div class="alert alert-danger mb-4" style="border-radius: 6px;">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Form Action diubah ke route update dengan parameter ID --}}
            <form action="{{ route('admin.fb.produk.update', $produk->id_produk) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT') {{-- Wajib untuk proses update data di Laravel --}}
                
                <div class="row g-5">
                    
                    {{-- KOLOM KIRI --}}
                    <div class="col-12 col-lg-6 d-flex flex-column">
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold" style="font-size: 0.95rem; color: #4b5563;">Nama Produk</label>
                            <input type="text" name="nama_produk" class="form-control custom-input shadow-none" value="{{ old('nama_produk', $produk->nama_produk) }}" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold" style="font-size: 0.95rem; color: #4b5563;">Harga Beli</label>
                            <input type="text" name="harga_beli" class="form-control custom-input shadow-none" value="{{ old('harga_beli', $produk->harga_beli) }}" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold" style="font-size: 0.95rem; color: #4b5563;">Harga Jual</label>
                            <input type="text" name="harga_jual" class="form-control custom-input shadow-none" value="{{ old('harga_jual', $produk->harga_jual) }}" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold" style="font-size: 0.95rem; color: #4b5563;">Stock</label>
                            <input type="number" name="stock" class="form-control custom-input shadow-none" value="{{ old('stock', $produk->stock) }}" min="0" required>
                        </div>

                        <div class="mb-5 flex-grow-1">
                            <label class="form-label fw-bold" style="font-size: 0.95rem; color: #4b5563;">SKU</label>
                            <input type="text" name="sku" class="form-control custom-input shadow-none" value="{{ old('sku', $produk->sku) }}" required>
                        </div>

                        {{-- Tombol Batal diarahkan kembali ke index --}}
                        <a href="{{ route('admin.fb.produk.index') }}" class="btn w-100 py-2 fw-bold mt-auto text-center text-decoration-none" style="background-color: #d1d5db; color: #ffffff; border-radius: 6px;">
                            Batal
                        </a>
                    </div>

                    {{-- KOLOM KANAN --}}
                    <div class="col-12 col-lg-6 d-flex flex-column">
                        
                        {{-- Area Upload --}}
                        <div class="mb-4 flex-grow-1 d-flex flex-column">
                            <label class="form-label fw-bold" style="font-size: 0.95rem; color: #4b5563;">Foto Produk</label>
                            <div class="upload-area flex-grow-1 d-flex flex-column align-items-center justify-content-center position-relative overflow-hidden" id="uploadContainer" onclick="document.getElementById('fileUpload').click()" style="border: 1px solid #e5e7eb;">
                                
                                <div id="defaultUploadContent" class="text-center d-flex flex-column align-items-center" style="display: none !important;">
                                    <i class="fas fa-upload mb-3" style="font-size: 3rem; color: #9ca3af;"></i>
                                    <span class="fw-normal text-muted mb-1">Upload Foto Max 2MB</span>
                                    <small style="color: #9ca3af; font-size: 0.75rem;">Format: JPG, JPEG, PNG, WEBP</small>
                                </div>
                                
                                {{-- Preview Foto di-load dari database --}}
                                <img id="imagePreview" src="{{ asset('uploads/fb/' . $produk->foto) }}" alt="Preview Foto" onerror="this.onerror=null; this.src='{{ asset('images/logo_dumb.png') }}';" style="display: block; width: 100%; height: 100%; object-fit: contain; position: absolute; top: 0; left: 0; background-color: #fff; padding: 10px;">
                                
                                {{-- Input file (Tidak wajib diisi saat edit) --}}
                                <input type="file" name="foto" id="fileUpload" class="d-none" accept="image/jpeg, image/png, image/webp" onchange="previewImage(this)">
                            </div>
                            <small class="text-muted mt-2 d-block" style="font-size: 0.8rem;">*Biarkan kosong jika tidak ingin mengubah foto.</small>
                        </div>

                        {{-- CUSTOM DROPDOWN 1: KATEGORI PRODUK --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold" style="font-size: 0.95rem; color: #4b5563;">Kategori Produk</label>
                            
                            <div class="position-relative custom-dropdown-container" id="containerKategoriUtama">
                                @php $currentKat = old('kategori_produk', $produk->subKategori->kategori_produk ?? ''); @endphp
                                <input type="hidden" name="kategori_produk" id="kategoriProdukInput" value="{{ $currentKat }}" required>
                                
                                <div class="form-control custom-input shadow-none d-flex justify-content-between align-items-center" id="kategoriProdukSelectBox" onclick="toggleKategoriProduk()" style="cursor: pointer; background-color: #ffffff; min-height: 48px;">
                                    <span id="kategoriProdukSelectedText" style="color: #374151;">{{ $currentKat ?: '-- Pilih Kategori --' }}</span>
                                    <i class="fas fa-chevron-right text-muted" style="font-size: 0.85rem;" id="kategoriIcon"></i>
                                </div>
                                
                                <div class="shadow-sm" id="kategoriProdukOptions" style="visibility: hidden; opacity: 0; transform: translateY(-10px); position: absolute; width: 100%; z-index: 1000; border: 1px solid #d1d5db; border-top: none; border-radius: 0 0 6px 6px; background-color: #ffffff; transition: opacity 0.2s ease, transform 0.2s ease, visibility 0.2s; pointer-events: none;">
                                    
                                    {{-- HEADER DROPDOWN: Tombol Tambah --}}
                                    <div id="addKategoriDefault" class="p-2 border-bottom d-flex justify-content-end align-items-center" style="background-color: #faf5f9;">
                                        <button type="button" class="btn btn-purple btn-sm px-3" onclick="showAddKategoriForm(event)" style="border-radius: 4px; font-weight: 500;">
                                            <i class="fas fa-plus me-1"></i> Tambah
                                        </button>
                                    </div>

                                    {{-- HEADER DROPDOWN: Form Input --}}
                                    <div id="addKategoriForm" class="p-2 border-bottom d-none justify-content-between align-items-center" style="background-color: #faf5f9; gap: 8px;">
                                        <input type="text" id="newKategoriInput" class="form-control custom-input shadow-none" placeholder="Contoh: Eksternal" style="padding: 6px 12px; font-size: 0.9rem; flex-grow: 1;" onclick="event.stopPropagation()" onkeydown="if(event.key === 'Enter') { event.preventDefault(); saveNewKategori(event); }">
                                        <button type="button" class="btn btn-blue btn-sm px-3" onclick="saveNewKategori(event)" style="border-radius: 4px; font-weight: 500; height: 33px;">
                                            Simpan
                                        </button>
                                    </div>

                                    {{-- Looping Data Kategori dari Controller --}}
                                    <div id="kategoriListWrapper">
                                        @foreach($kategoriList as $kat)
                                            <div class="custom-option py-2 px-3" onclick="selectKategoriProduk('{{ $kat }}')" style="border-bottom: 1px solid #e5e7eb; cursor: pointer; color: #374151; transition: 0.2s;">{{ $kat }}</div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- CUSTOM DROPDOWN 2: SUB KATEGORI PRODUK --}}
                        <div class="mb-5">
                            <label class="form-label fw-bold" style="font-size: 0.95rem; color: #4b5563;">Sub. Kategori Produk</label>
                            
                            <div class="position-relative custom-dropdown-container">
                                @php $currentSub = old('sub_kategori_produk', $produk->subKategori->sub_kategori_produk ?? ''); @endphp
                                <input type="hidden" name="sub_kategori_produk" id="subKategoriProdukInput" value="{{ $currentSub }}" required>
                                
                                <div class="form-control custom-input shadow-none d-flex justify-content-between align-items-center" id="subKategoriProdukSelectBox" onclick="toggleSubKategoriProduk()" style="cursor: pointer; background-color: #ffffff; min-height: 48px;">
                                    <span id="subKategoriProdukSelectedText" style="color: #374151;">{{ $currentSub ?: '-- Pilih Sub Kategori --' }}</span>
                                    <i class="fas fa-chevron-right text-muted" style="font-size: 0.85rem;" id="subKategoriIcon"></i>
                                </div>
                                
                                <div class="shadow-sm" id="subKategoriProdukOptions" style="visibility: hidden; opacity: 0; transform: translateY(-10px); position: absolute; width: 100%; z-index: 1000; border: 1px solid #d1d5db; border-top: none; border-radius: 0 0 6px 6px; background-color: #ffffff; transition: opacity 0.2s ease, transform 0.2s ease, visibility 0.2s; pointer-events: none;">
                                    <div class="custom-option py-2 px-3" onclick="selectSubKategoriProduk('Makanan ringan')" style="border-bottom: 1px solid #e5e7eb; cursor: pointer; color: #374151; transition: 0.2s;">Makanan ringan</div>
                                    <div class="custom-option py-2 px-3" onclick="selectSubKategoriProduk('Makanan berat')" style="border-bottom: 1px solid #e5e7eb; cursor: pointer; color: #374151; transition: 0.2s;">Makanan berat</div>
                                    <div class="custom-option py-2 px-3" onclick="selectSubKategoriProduk('Minuman')" style="cursor: pointer; color: #374151; transition: 0.2s;">Minuman</div>
                                </div>
                            </div>
                        </div>

                        {{-- Tombol Simpan --}}
                        <button type="submit" class="btn btn-blue w-100 py-2 fw-bold text-white mt-auto" style="border-radius: 6px;">
                            Simpan Perubahan
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>

</div>
@stop

@push('css')
<style>
    .custom-input {
        border: 1px solid #d1d5db;
        border-radius: 6px;
        padding: 12px 15px;
        font-size: 0.95rem;
        color: #374151;
    }
    .custom-input:focus {
        border-color: #0084ff;
    }
    
    .upload-area {
        border: 1px solid #d1d5db;
        border-radius: 8px;
        background-color: #ffffff;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
        min-height: 220px; 
    }
    .upload-area:hover {
        border-color: #0084ff;
        background-color: #f8faff;
    }
    .upload-area:hover i {
        color: #0084ff !important;
    }

    .btn-blue {
        background-color: #0084ff;
        border: none;
        transition: 0.2s;
    }
    .btn-blue:hover {
        background-color: #006bce;
    }
    
    .btn-purple {
        background-color: #5b21b6;
        color: #ffffff;
        border: none;
        transition: 0.2s;
    }
    .btn-purple:hover {
        background-color: #4c1d95;
        color: #ffffff;
    }

    #kategoriProdukOptions.show-dropdown,
    #subKategoriProdukOptions.show-dropdown {
        visibility: visible !important;
        opacity: 1 !important;
        transform: translateY(0) !important;
        pointer-events: auto !important;
    }
    .custom-option:hover {
        background-color: #f3f4f6;
    }
</style>
@endpush

@push('js')
<script>
    function previewImage(input) {
        const preview = document.getElementById('imagePreview');
        const defaultContent = document.getElementById('defaultUploadContent');
        const container = document.getElementById('uploadContainer');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
                defaultContent.style.setProperty('display', 'none', 'important');
                container.style.border = '1px solid #e5e7eb'; 
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function toggleKategoriProduk() {
        const options = document.getElementById('kategoriProdukOptions');
        const box = document.getElementById('kategoriProdukSelectBox');
        const icon = document.getElementById('kategoriIcon');
        
        options.classList.toggle('show-dropdown');
        
        if (options.classList.contains('show-dropdown')) {
            box.style.borderBottomLeftRadius = '0';
            box.style.borderBottomRightRadius = '0';
            icon.classList.remove('fa-chevron-right');
            icon.classList.add('fa-chevron-down');
        } else {
            box.style.borderBottomLeftRadius = '6px';
            box.style.borderBottomRightRadius = '6px';
            icon.classList.remove('fa-chevron-down');
            icon.classList.add('fa-chevron-right');
            resetAddKategoriForm();
        }
    }

    function selectKategoriProduk(value) {
        document.getElementById('kategoriProdukSelectedText').innerHTML = value;
        document.getElementById('kategoriProdukInput').value = value;
        toggleKategoriProduk();
    }

    function showAddKategoriForm(event) {
        event.stopPropagation(); 
        document.getElementById('addKategoriDefault').classList.remove('d-flex');
        document.getElementById('addKategoriDefault').classList.add('d-none');
        document.getElementById('addKategoriForm').classList.remove('d-none');
        document.getElementById('addKategoriForm').classList.add('d-flex');
        document.getElementById('newKategoriInput').focus();
    }

    function saveNewKategori(event) {
        event.stopPropagation(); 
        const inputField = document.getElementById('newKategoriInput');
        const newValue = inputField.value.trim();
        
        if (newValue !== "") {
            const listWrapper = document.getElementById('kategoriListWrapper');
            const newDiv = document.createElement('div');
            newDiv.className = 'custom-option py-2 px-3';
            newDiv.style.cssText = 'border-bottom: 1px solid #e5e7eb; cursor: pointer; color: #374151; transition: 0.2s;';
            newDiv.innerHTML = newValue;
            
            newDiv.onclick = function() {
                selectKategoriProduk(newValue);
            };
            
            listWrapper.insertBefore(newDiv, listWrapper.firstChild);
            selectKategoriProduk(newValue);
        }
        
        inputField.value = "";
        resetAddKategoriForm();
    }

    function resetAddKategoriForm() {
        document.getElementById('addKategoriDefault').classList.remove('d-none');
        document.getElementById('addKategoriDefault').classList.add('d-flex');
        document.getElementById('addKategoriForm').classList.remove('d-flex');
        document.getElementById('addKategoriForm').classList.add('d-none');
    }

    function toggleSubKategoriProduk() {
        const options = document.getElementById('subKategoriProdukOptions');
        const box = document.getElementById('subKategoriProdukSelectBox');
        const icon = document.getElementById('subKategoriIcon');
        
        options.classList.toggle('show-dropdown');
        
        if (options.classList.contains('show-dropdown')) {
            box.style.borderBottomLeftRadius = '0';
            box.style.borderBottomRightRadius = '0';
            icon.classList.remove('fa-chevron-right');
            icon.classList.add('fa-chevron-down');
        } else {
            box.style.borderBottomLeftRadius = '6px';
            box.style.borderBottomRightRadius = '6px';
            icon.classList.remove('fa-chevron-down');
            icon.classList.add('fa-chevron-right');
        }
    }

    function selectSubKategoriProduk(value) {
        document.getElementById('subKategoriProdukSelectedText').innerHTML = value;
        document.getElementById('subKategoriProdukInput').value = value;
        toggleSubKategoriProduk();
    }

    document.addEventListener('click', function(event) {
        if (event.target.closest('#addKategoriForm')) return; 

        const katContainer = document.getElementById('containerKategoriUtama');
        if (katContainer && !katContainer.contains(event.target)) {
            const options = document.getElementById('kategoriProdukOptions');
            const box = document.getElementById('kategoriProdukSelectBox');
            const icon = document.getElementById('kategoriIcon');
            
            if(options && options.classList.contains('show-dropdown')) {
                options.classList.remove('show-dropdown');
                box.style.borderBottomLeftRadius = '6px';
                box.style.borderBottomRightRadius = '6px';
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-right');
                resetAddKategoriForm();
            }
        }

        const subContainer = document.getElementById('subKategoriProdukSelectBox').parentNode;
        if (subContainer && !subContainer.contains(event.target)) {
            const optionsSub = document.getElementById('subKategoriProdukOptions');
            const boxSub = document.getElementById('subKategoriProdukSelectBox');
            const iconSub = document.getElementById('subKategoriIcon');
            
            if(optionsSub && optionsSub.classList.contains('show-dropdown')) {
                optionsSub.classList.remove('show-dropdown');
                boxSub.style.borderBottomLeftRadius = '6px';
                boxSub.style.borderBottomRightRadius = '6px';
                iconSub.classList.remove('fa-chevron-down');
                iconSub.classList.add('fa-chevron-right');
            }
        }
    });
</script>
@endpush