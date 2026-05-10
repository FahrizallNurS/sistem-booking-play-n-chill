@extends('adminlte::page')
@include('partials.sidebar-admin')
@section('title', 'Edit Game')

@section('content_header')
    <h1>Edit Game</h1>
@stop

@section('content')

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Form Edit Game</h3>
            <div class="card-tools">
                <a href="{{ route('admin.game.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
        <div class="card-body">

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.game.update', $permainan->id_permainan) }}"
                method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')

                <div class="form-group">
                    <label>Nama Game <span class="text-danger">*</span></label>
                    <input type="text" name="nama_permainan" class="form-control"
                        value="{{ old('nama_permainan', $permainan->nama_permainan) }}"
                        maxlength="30" required>
                </div>

                <div class="form-group">
                    <label>Gambar Game</label>

                    {{-- Gambar saat ini --}}
                    @if($permainan->gambar)
                        <div class="mb-2">
                            <p class="text-muted small mb-1">Gambar saat ini:</p>
                            <img src="{{ asset('storage/' . $permainan->gambar) }}"
                                alt="{{ $permainan->nama_permainan }}"
                                style="width:120px;height:120px;object-fit:cover;border-radius:8px;border:2px solid #dee2e6;">
                        </div>
                    @endif

                    <div class="custom-file">
                        <input type="file" name="gambar" class="custom-file-input" id="gambarInput"
                            accept="image/jpg,image/jpeg,image/png,image/webp"
                            onchange="previewGambar(this)">
                        <label class="custom-file-label" for="gambarInput">
                            {{ $permainan->gambar ? 'Ganti gambar...' : 'Pilih gambar...' }}
                        </label>
                    </div>
                    <small class="text-muted">Kosongkan jika tidak ingin mengganti gambar.</small>

                    <div id="previewContainer" class="mt-2" style="display:none">
                        <p class="text-muted small mb-1">Preview gambar baru:</p>
                        <img id="previewGambar" src="" alt="Preview"
                            style="width:120px;height:120px;object-fit:cover;border-radius:8px;border:2px solid #dee2e6;">
                    </div>
                </div>

                <div class="form-group">
                    <label>Device <span class="text-danger">*</span></label>
                    <select name="device" class="form-control" required>
                        <option value="">-- Pilih Device --</option>
                        <option value="PS3" {{ old('device') === 'PS3' ? 'selected' : '' }}>PS3</option>
                        <option value="PS4" {{ old('device') === 'PS4' ? 'selected' : '' }}>PS4</option>
                        <option value="PS5" {{ old('device') === 'PS5' ? 'selected' : '' }}>PS5</option>
                    </select>
                    <small class="text-muted">Game akan otomatis diassign ke semua ruangan dengan device ini.</small>
                </div>

                <button type="submit" class="btn btn-warning">
                    <i class="fas fa-save"></i> Update
                </button>

            </form>
        </div>
    </div>

@stop

@section('js')
<script>
function previewGambar(input) {
    const label = input.nextElementSibling;
    const preview = document.getElementById('previewGambar');
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