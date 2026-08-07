@extends('adminlte::page')

@include('partials.sidebar-admin')

@section('title', 'Edit Foto Galeri - Play N Chill')

@section('content_header')
    <div class="px-2">
        <h1 class="text-dark fw-bold" style="font-size: 1.8rem;">Kelola Galeri</h1>
        <p class="text-muted mb-2">Manajemen Galeri Website.</p>
        <hr class="mt-0 mb-3" style="border-color: #d1d5db;">
    </div>
@stop

@section('content')
<div class="container-fluid px-2 pb-5">
    
    <div class="card border-0 shadow-sm" style="border-radius: 8px; overflow: hidden;">
        <div class="card-header text-center py-3 border-bottom" style="background-color: #faf5f9; border-color: #e5e7eb !important;">
            <h5 class="mb-0 fw-bold text-dark" style="font-size: 1.1rem;">Edit Foto Galeri</h5>
        </div>
        
        <div class="card-body p-4 p-md-5">
            {{-- Form mengarah ke route update dengan parameter ID --}}
            <form action="{{ route('admin.galeri.update', $galeri->id_galeri) }}" method="POST" enctype="multipart/form-data">
                
                @csrf
                @method('PUT') {{-- Wajib untuk method update pada resource controller --}}

                <div class="row g-5">
                    
                    {{-- KOLOM KIRI --}}
                    <div class="col-12 col-lg-6 d-flex flex-column">
                        
                        {{-- Judul Foto --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold" style="font-size: 0.95rem; color: #4b5563;">Judul Foto <span class="text-danger">*</span></label>
                            <input type="text" name="judul_foto" class="form-control custom-input shadow-none @error('judul_foto') is-invalid @enderror" value="{{ old('judul_foto', $galeri->judul_foto) }}" required>
                            @error('judul_foto')
                                <small class="text-danger mt-1">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Kategori Foto --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold" style="font-size: 0.95rem; color: #4b5563;">Kategori Foto <span class="text-danger">*</span></label>
                            
                            <div class="position-relative custom-dropdown-container">
                                <input type="hidden" name="kategori" id="kategoriInput" value="{{ old('kategori', $galeri->kategori) }}" required>
                                
                                <div class="form-control custom-input shadow-none d-flex justify-content-between align-items-center @error('kategori') is-invalid @enderror" id="kategoriSelectBox" onclick="toggleDropdown()" style="cursor: pointer; background-color: #ffffff; min-height: 46px;">
                                    <span id="kategoriSelectedText" style="color: #374151;">{{ old('kategori', ucfirst($galeri->kategori)) }}</span>
                                    <i class="fas fa-chevron-down text-muted" style="font-size: 0.8rem;"></i>
                                </div>
                                
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

                        {{-- Deskripsi --}}
                        <div class="mb-4 flex-grow-1 d-flex flex-column">
                            <label class="form-label fw-bold" style="font-size: 0.95rem; color: #4b5563;">Deskripsi</label>
                            <textarea name="deskripsi_foto" class="form-control custom-input shadow-none flex-grow-1 @error('deskripsi_foto') is-invalid @enderror">{{ old('deskripsi_foto', $galeri->deskripsi_foto) }}</textarea>
                            @error('deskripsi_foto')
                                <small class="text-danger mt-1">{{ $message }}</small>
                            @enderror
                        </div>

                        <a href="{{ route('admin.galeri.index') }}" class="btn w-100 py-2 fw-bold mt-auto" style="background-color: #d1d5db; color: #ffffff; border-radius: 6px;">
                            Batal
                        </a>
                    </div>

                    {{-- KOLOM KANAN --}}
                    <div class="col-12 col-lg-6 d-flex flex-column">
                        <div class="mb-4 flex-grow-1 d-flex flex-column">
                            <label class="form-label fw-bold" style="font-size: 0.95rem; color: #4b5563;">Foto Ruangan</label>
                            <small class="text-muted mb-2"><i>(Biarkan kosong jika tidak ingin mengubah foto)</i></small>
                            
                            {{-- Area Upload --}}
                            <div class="upload-area flex-grow-1 d-flex flex-column align-items-center justify-content-center position-relative overflow-hidden @error('file_foto') border-danger @enderror" id="uploadContainer" onclick="document.getElementById('fileUpload').click()" style="border: none;">
                                
                                <div id="defaultUploadContent" class="text-center d-flex flex-column align-items-center" style="display: none !important;">
                                    <i class="fas fa-upload mb-3" style="font-size: 3rem; color: #9ca3af;"></i>
                                    <span class="fw-normal text-muted mb-1">Upload Foto Max 2MB</span>
                                    <small style="color: #9ca3af; font-size: 0.75rem;">Format: JPG, JPEG, PNG, WEBP</small>
                                </div>
                                
                                {{-- Menampilkan gambar lama dari database --}}
                                @if($galeri->file_foto)
                                    <img id="imagePreview" src="{{ asset('uploads/galeri/' . $galeri->file_foto) }}" alt="Preview Foto" style="display: block; width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0;" onerror="this.src='{{ asset('images/gaming.jpg') }}'">
                                @else
                                    <img id="imagePreview" src="{{ asset('images/gaming.jpg') }}" alt="Preview Foto" style="display: block; width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0;">
                                @endif

                                {{-- Input file tidak required saat Edit --}}
                                <input type="file" name="file_foto" id="fileUpload" class="d-none" accept="image/jpeg, image/png, image/webp" onchange="previewImage(this)">
                            </div>
                            @error('file_foto')
                                <small class="text-danger mt-2 text-center">{{ $message }}</small>
                            @enderror
                        </div>

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
    .custom-option:hover {
        background-color: #f3f4f6; 
    }
    .upload-area {
        border: 1px solid #d1d5db;
        border-radius: 8px;
        background-color: #ffffff;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
        min-height: 250px; 
    }
    .upload-area:hover {
        opacity: 0.9;
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
        document.getElementById('kategoriInput').value = value;
        toggleDropdown();
    }

    document.addEventListener('click', function(event) {
        const container = document.querySelector('.custom-dropdown-container');
        if (container && !container.contains(event.target)) {
            document.getElementById('kategoriOptions').style.display = 'none';
            document.getElementById('kategoriSelectBox').style.borderBottomLeftRadius = '6px';
            document.getElementById('kategoriSelectBox').style.borderBottomRightRadius = '6px';
        }
    });

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
                container.style.border = 'none'; 
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush