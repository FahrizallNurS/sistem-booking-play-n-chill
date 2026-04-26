@extends('adminlte::page')

@section('title', 'Kelola Gallery')

@section('content_header')
    <h1>Kelola Gallery</h1>
@stop

@section('content')

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Upload Foto Ruangan</h3>
        </div>
        <div class="card-body">

            {{-- Form Upload --}}
            <form action="#" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Kategori Ruangan</label>
                            <select id="kategori_select" class="form-control">
                                <option value="">-- Pilih Kategori --</option>
                                <option value="1">Reguler</option>
                                <option value="2">VIP</option>
                                <option value="3">VVIP</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Ruangan</label>
                            <select name="ms_ruangan_id_ruangan" id="ruangan_select" class="form-control" required>
                                <option value="">-- Pilih Kategori dulu --</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Pilih Foto</label>
                            <input type="file" name="foto[]" class="form-control-file" multiple accept="image/*" required>
                            <small class="text-muted">Bisa pilih lebih dari 1 foto</small>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-upload"></i> Upload
                </button>
            </form>

            <hr>

            {{-- Filter by Ruangan --}}
            <div class="row mb-3">
                <div class="col-md-4">
                    <select id="filter_kategori" class="form-control">
                        <option value="">-- Filter Kategori --</option>
                        <option value="1">Reguler</option>
                        <option value="2">VIP</option>
                        <option value="3">VVIP</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <select id="filter_ruangan" class="form-control">
                        <option value="">-- Filter Ruangan --</option>
                    </select>
                </div>
            </div>

            {{-- Grid Foto --}}
            <div class="row" id="gallery_grid">
                {{-- Dummy foto --}}
                <div class="col-md-3 mb-3">
                    <div class="card">
                        <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="foto ruangan">
                        <div class="card-body p-2">
                            <small class="text-muted">Reguler - 01</small>
                            <br>
                            <button class="btn btn-danger btn-sm mt-1" onclick="return confirm('Hapus foto ini?')">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card">
                        <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="foto ruangan">
                        <div class="card-body p-2">
                            <small class="text-muted">VIP - 01</small>
                            <br>
                            <button class="btn btn-danger btn-sm mt-1" onclick="return confirm('Hapus foto ini?')">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card">
                        <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="foto ruangan">
                        <div class="card-body p-2">
                            <small class="text-muted">VVIP - 01</small>
                            <br>
                            <button class="btn btn-danger btn-sm mt-1" onclick="return confirm('Hapus foto ini?')">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@stop

@section('js')
<script>
    // AJAX load ruangan by kategori (form upload)
    document.getElementById('kategori_select').addEventListener('change', function() {
        loadRuangan(this.value, 'ruangan_select');
    });

    // AJAX load ruangan by kategori (filter)
    document.getElementById('filter_kategori').addEventListener('change', function() {
        loadRuangan(this.value, 'filter_ruangan');
    });

    function loadRuangan(idKategori, targetId) {
        const select = document.getElementById(targetId);
        select.innerHTML = '<option value="">Loading...</option>';

        if (!idKategori) {
            select.innerHTML = '<option value="">-- Pilih Kategori dulu --</option>';
            return;
        }

        fetch(`/admin/kategori/${idKategori}/ruangan`)
            .then(res => res.json())
            .then(data => {
                select.innerHTML = '<option value="">-- Pilih Ruangan --</option>';
                data.forEach(r => {
                    select.innerHTML += `<option value="${r.id_ruangan}">${r.nama_ruangan}</option>`;
                });
            });
    }
</script>
@stop