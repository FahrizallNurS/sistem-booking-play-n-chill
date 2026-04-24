@extends('adminlte::page')

@section('title', 'Tambah Game')

@section('content_header')
    <h1>Tambah Game</h1>
@stop

@section('content')

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Form Tambah Game</h3>
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

            <form action="{{ route('admin.game.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label>Nama Game <span class="text-danger">*</span></label>
                    <input type="text" name="nama_permainan" class="form-control"
                        value="{{ old('nama_permainan') }}"
                        placeholder="Contoh: PS5, Xbox, Nintendo Switch"
                        maxlength="30" required>
                </div>

                <div class="form-group">
                    <label>Gambar Game</label>
                    <div class="custom-file">
                        <input type="file" name="gambar" class="custom-file-input" id="gambarInput"
                            accept="image/jpg,image/jpeg,image/png,image/webp"
                            onchange="previewGambar(this)">
                        <label class="custom-file-label" for="gambarInput">Pilih gambar...</label>
                    </div>
                    <small class="text-muted">Format: JPG, PNG, WEBP. Maksimal 2MB.</small>

                    <div id="previewContainer" class="mt-2" style="display:none">
                        <img id="previewGambar" src="" alt="Preview"
                            style="width:120px;height:120px;object-fit:cover;border-radius:8px;border:2px solid #dee2e6;">
                    </div>
                </div>

                <div class="form-group">
                    <label>Assign ke Ruangan</label>
                    <div class="row">
                        @foreach($ruangans as $ruangan)
                        <div class="col-md-4">
                            <div class="custom-control custom-checkbox mb-2">
                                <input type="checkbox" class="custom-control-input"
                                    id="ruangan_{{ $ruangan->id_ruangan }}"
                                    name="ruangan_ids[]"
                                    value="{{ $ruangan->id_ruangan }}"
                                    {{ in_array($ruangan->id_ruangan, old('ruangan_ids', [])) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="ruangan_{{ $ruangan->id_ruangan }}">
                                    {{ $ruangan->nama_ruangan }}
                                    <span class="badge badge-secondary">{{ $ruangan->kategori }}</span>
                                </label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan
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