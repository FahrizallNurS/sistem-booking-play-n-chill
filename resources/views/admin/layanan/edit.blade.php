@extends('adminlte::page')

@section('title', 'Edit Ruangan')

@section('content_header')
    <h1>Edit Ruangan</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.layanan.update', $ruangan->id_ruangan) }}" 
            method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Nama Ruangan</label>
                <input type="text" name="nama_ruangan"
                    class="form-control @error('nama_ruangan') is-invalid @enderror"
                    value="{{ old('nama_ruangan', $ruangan->nama_ruangan) }}"
                    required maxlength="20">
                @error('nama_ruangan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Kategori</label>
                <select name="kategori" class="form-control" required>
                    <option value="REGULAR" {{ $ruangan->kategori == 'REGULAR' ? 'selected' : '' }}>Regular</option>
                    <option value="VIP" {{ $ruangan->kategori == 'VIP' ? 'selected' : '' }}>VIP</option>
                    <option value="VVIP" {{ $ruangan->kategori == 'VVIP' ? 'selected' : '' }}>VVIP</option>
                </select>
            </div>

            <div class="form-group">
                <label>Perangkat</label>
                <input type="text" name="perangkat" class="form-control"
                    value="{{ old('perangkat', $ruangan->perangkat) }}" maxlength="10">
            </div>

            <div class="form-group">
                <label>Deskripsi</label>
                <input type="text" name="deskripsi" class="form-control"
                    value="{{ old('deskripsi', $ruangan->deskripsi) }}" maxlength="60">
            </div>

            <div class="form-group">
                <label>Status</label>
                <select name="is_active" class="form-control">
                    <option value="1" {{ $ruangan->is_active ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ !$ruangan->is_active ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            {{-- Upload Foto --}}
            <div class="form-group">
                <label>Foto Ruangan</label>

                @if($ruangan->galeri)
                    <div class="mb-2">
                        <p class="text-muted small mb-1">Foto saat ini:</p>
                        <img src="{{ asset('storage/' . $ruangan->galeri) }}"
                            alt="{{ $ruangan->nama_ruangan }}"
                            style="width:200px;height:130px;object-fit:cover;border-radius:8px;border:2px solid #dee2e6;">
                    </div>
                @endif

                <div class="custom-file">
                    <input type="file" name="galeri" class="custom-file-input" id="galeriInput"
                        accept="image/jpg,image/jpeg,image/png,image/webp"
                        onchange="previewFoto(this)">
                    <label class="custom-file-label" for="galeriInput">
                        {{ $ruangan->galeri ? 'Ganti foto...' : 'Pilih foto...' }}
                    </label>
                </div>
                <small class="text-muted">Format: JPG, PNG, WEBP. Maksimal 2MB. Kosongkan jika tidak ingin mengganti.</small>

                <div id="previewContainer" class="mt-2" style="display:none">
                    <p class="text-muted small mb-1">Preview foto baru:</p>
                    <img id="previewFoto" src="" alt="Preview"
                        style="width:200px;height:130px;object-fit:cover;border-radius:8px;border:2px solid #dee2e6;">
                </div>
            </div>

            <a href="{{ route('admin.layanan.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
</div>
@stop

@section('js')
<script>
function previewFoto(input) {
    const label = input.nextElementSibling;
    const preview = document.getElementById('previewFoto');
    const container = document.getElementById('previewContainer');

    if (input.files && input.files[0]) {
        label.textContent = input.files[0].name;
        const reader = new FileReader();
        reader.onload = e => {
            preview.src = e.target.result;
            container.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@stop