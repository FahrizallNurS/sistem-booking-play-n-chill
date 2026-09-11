@extends('adminlte::page')

@include('partials.sidebar-admin')

@section('title', 'Tambah Foto Galeri - Play N Chill')

@section('content_header')
    <div class="px-2">
        <h1 class="text-dark fw-bold" style="font-size: 1.8rem;">Kelola Galeri</h1>
        <p class="text-muted mb-2">Manajemen Galeri We
            bsite.</p>
        <hr class="mt-0 mb-3" style="border-color: #d1d5db;">
    </div>
@stop

@section('content')
<div class="container-fluid px-2 pb-5">
    
    <div class="card border-0 shadow-sm" style="border-radius: 8px; overflow: hidden;">
        <div class="card-header text-center py-3 border-bottom" style="background-color: #faf5f9; border-color: #e5e7eb !important;">
            <h5 class="mb-0 fw-bold text-dark" style="font-size: 1.1rem;">Tambah Foto Galeri</h5>
        </div>
        
        <div class="card-body p-4 p-md-5">
            {{-- Form mengarah ke route store --}}
            <form action="{{ route('admin.galeri.store') }}" method="POST" enctype="multipart/form-data">
                
                {{-- Keamanan bawaan Laravel --}}
                @csrf 
                
                <div class="row g-5">
                    
                    {{-- KOLOM KIRI --}}
                    <div class="col-12 col-lg-6 d-flex flex-column">
                        
                        {{-- Input Judul --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold" style="font-size: 0.95rem; color: #4b5563;">Judul Foto <span class="text-danger">*</span></label>
                            <input type="text" name="judul_foto" value="{{ old('judul_foto') }}" class="form-control custom-input shadow-none @error('judul_foto') is-invalid @enderror" placeholder="Contoh : Gaming Private Room" required>
                            @error('judul_foto')
                                <small class="text-danger mt-1">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Input Kategori (Custom Dropdown) --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold" style="font-size: 0.95rem; color: #4b5563;">Kategori Foto <span class="text-danger">*</span></label>
                            
                            <div class="position-relative custom-dropdown-container">
                                {{-- Input tersembunyi untuk backend --}}
                                <input type="hidden" name="kategori" id="kategoriInput" value="{{ old('kategori') }}" required>
                                
                                {{-- Kotak yang terlihat --}}
                                <div class="form-control custom-input shadow-none d-flex justify-content-between align-items-center @error('kategori') is-invalid @enderror" id="kategoriSelectBox" onclick="toggleDropdown()" style="cursor: pointer; background-color: #ffffff; min-height: 46px;">
                                    <span id="kategoriSelectedText" style="color: #374151;">{{ old('kategori') ?? 'Pilih Kategori...' }}</span>
                                    <i class="fas fa-chevron-down text-muted" style="font-size: 0.8rem;"></i>
                                </div>
                                
                                {{-- Daftar Pilihan --}}
                                <div class="shadow-sm" id="kategoriOptions" style="display: none; position: absolute; width: 100%; z-index: 1000; border: 1px solid #d1d5db; border-top: none; border-radius: 0 0 6px 6px; background-color: #f9fafb; overflow: hidden;">
    
                                    <div class="custom-option text-center py-3" onclick="selectOption('Reguler')" style="border-bottom: 1px solid #e5e7eb; cursor: pointer; color: #374151; transition: 0.2s;">
                                        Reguler
                                    </div>
                                    <div class="custom-option text-center py-3" onclick="selectOption('Private - Gaming')" style="border-bottom: 1px solid #e5e7eb; cursor: pointer; color: #374151; transition: 0.2s;">
                                        Private - Gaming
                                    </div>
                                    <div class="custom-option text-center py-3" onclick="selectOption('Private - Nonton')" style="border-bottom: 1px solid #e5e7eb; cursor: pointer; color: #374151; transition: 0.2s;">
                                        Private - Nonton
                                    </div>
                                    <div class="custom-option text-center py-3" onclick="selectOption('Private - Karaoke')" style="cursor: pointer; color: #374151; transition: 0.2s;">
                                        Private - Karaoke
                                    </div>

                                </div>
                            </div>
                            @error('kategori')
                                <small class="text-danger mt-1">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Input Deskripsi --}}
                        <div class="mb-4 flex-grow-1 d-flex flex-column">
                            <label class="form-label fw-bold" style="font-size: 0.95rem; color: #4b5563;">Deskripsi</label>
                            <textarea name="deskripsi_foto" class="form-control custom-input shadow-none flex-grow-1 @error('deskripsi_foto') is-invalid @enderror" placeholder="Contoh : Layanan paling asik untuk menemani me time kamu">{{ old('deskripsi_foto') }}</textarea>
                            @error('deskripsi_foto')
                                <small class="text-danger mt-1">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Tombol Batal --}}
                        <a href="{{ route('admin.galeri.index') }}" class="btn w-100 py-2 fw-bold mt-auto" style="background-color: #d1d5db; color: #ffffff; border-radius: 6px;">
                            Batal
                        </a>
                    </div>

                   {{-- KOLOM KANAN: Upload Area & Tombol Simpan --}}
                    <div class="col-12 col-lg-6 d-flex flex-column">
                        <div class="mb-4">
                            <label class="form-label fw-bold" style="font-size: 0.95rem; color: #4b5563;">Foto Ruangan <span class="text-danger">*</span></label>
                            
                            {{-- Area Upload Modern (Rasio 4:3) --}}
                            <div class="upload-area position-relative overflow-hidden @error('file_foto') border-danger @enderror" id="uploadContainer" onclick="document.getElementById('fileUpload').click()">
                                
                                {{-- Konten Default --}}
                                <div id="defaultUploadContent" class="text-center d-flex flex-column align-items-center justify-content-center h-100 p-4">
                                    <div class="icon-circle mb-3">
                                        <i class="fas fa-cloud-upload-alt" style="font-size: 2.5rem; color: #0084ff;"></i>
                                    </div>
                                    <h6 class="fw-bold mb-1" style="color: #374151;">Pilih atau Drop Foto di Sini</h6>
                                    <span class="text-muted mb-3" style="font-size: 0.85rem;">
                                        Wajib menggunakan rasio gambar <strong>4:3</strong> (Landscape)
                                    </span>
                                    <span class="badge" style="background-color: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; padding: 6px 12px; font-weight: 500;">
                                        Max: 2MB | JPG, JPEG, PNG
                                    </span>
                                </div>
                                
                                {{-- Preview Image --}}
                                <img id="imagePreview" src="" alt="Preview Foto" style="display: none; width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0;">

                                {{-- Input File Fisik (Tersembunyi) --}}
                                <input type="file" name="file_foto" id="fileUpload" class="d-none" accept="image/jpeg, image/png, image/jpg" onchange="previewImage(this)" required>
                            </div>
                            @error('file_foto')
                                <small class="text-danger mt-2 d-block text-center">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Tombol Simpan --}}
                        <button type="submit" class="btn btn-blue w-100 py-2 fw-bold text-white mt-auto" style="border-radius: 6px; min-height: 46px;">
                            Simpan Foto Galeri
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
    .custom-input::placeholder {
        color: #9ca3af;
    }
    
    .custom-option:hover {
        background-color: #f3f4f6; 
    }
    
    .upload-area {
        border: 2px dashed #cbd5e1; 
        border-radius: 12px;
        background-color: #f8fafc;
        cursor: pointer;
        transition: all 0.3s ease-in-out;
        width: 100%;
        aspect-ratio: 4 / 3; 
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .upload-area:hover {
        border-color: #0084ff;
        background-color: #f0f9ff;
    }

    .icon-circle {
        background-color: #e0f2fe;
        width: 70px;
        height: 70px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s;
    }

    .upload-area:hover .icon-circle {
        transform: scale(1.1);
        background-color: #bae6fd;
    }
    .border-danger {
        border-color: #dc3545 !important;
    }

    .btn-blue {
        background-color: #0084ff;
        border: none;
        transition: 0.2s;
    }
    .btn-blue:hover {
        background-color: #006bce;
    }
</style>
@endpush

@push('js')
<script>
    function toggleDropdown() {
        const options = document.getElementById('kategoriOptions');
        const box = document.getElementById('kategoriSelectBox');
        
        if (options.style.display === 'none') {
            options.style.display = 'block';
            box.style.borderBottomLeftRadius = '0';
            box.style.borderBottomRightRadius = '0';
            box.style.borderColor = '#d1d5db';
        } else {
            options.style.display = 'none';
            box.style.borderBottomLeftRadius = '6px';
            box.style.borderBottomRightRadius = '6px';
        }
    }

    function selectOption(value) {
        document.getElementById('kategoriSelectedText').innerHTML = value;
        document.getElementById('kategoriSelectedText').style.color = '#374151';
        document.getElementById('kategoriInput').value = value;
        toggleDropdown();
    }

    // Menutup dropdown jika di klik di luar area
    document.addEventListener('click', function(event) {
        const container = document.querySelector('.custom-dropdown-container');
        if (container && !container.contains(event.target)) {
            document.getElementById('kategoriOptions').style.display = 'none';
            document.getElementById('kategoriSelectBox').style.borderBottomLeftRadius = '6px';
            document.getElementById('kategoriSelectBox').style.borderBottomRightRadius = '6px';
        }
    });

    // Preview File Gambar
    function previewImage(input) {
        const file = input.files[0];
        const preview = document.getElementById('imagePreview');
        const defaultContent = document.getElementById('defaultUploadContent');

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
                defaultContent.style.display = 'none';
            }
            reader.readAsDataURL(file);
        } else {
            preview.src = '';
            preview.style.display = 'none';
            defaultContent.style.display = 'flex';
        }
    }
</script>
@endpush