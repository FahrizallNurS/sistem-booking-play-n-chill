@extends('adminlte::page')
@include('partials.sidebar-admin')
@section('title', 'Kelola Game')

@section('content_header')
    <h1>Kelola Game</h1>
@stop

@section('content')

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Game</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalTambah">
                    <i class="fas fa-plus"></i> Tambah Game
                </button>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Gambar</th>
                        <th>Nama Game</th>
                        <th>Perangkat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($permainans as $i => $permainan)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>
                            @if($permainan->gambar)
                                <img src="{{ asset('storage/' . $permainan->gambar) }}"
                                    alt="{{ $permainan->nama_permainan }}"
                                    class="img-thumbnail" width="60" height="60"
                                    style="object-fit:cover;">
                            @else
                                <img src="https://via.placeholder.com/60x60?text=No+Image"
                                    alt="no image" class="img-thumbnail" width="60">
                            @endif
                        </td>
                        <td>{{ $permainan->nama_permainan }}</td>
                        <td>
                            @php
                                $device = $permainan->ruangans->first()?->perangkat ?? '-';
                            @endphp
                            <span class="badge badge-info">{{ $device }}</span>
                        </td>
                        <td>
                            <button type="button" class="btn btn-warning btn-sm btn-edit"
                                data-id="{{ $permainan->id_permainan }}"
                                data-nama="{{ $permainan->nama_permainan }}"
                                data-device="{{ $device }}"
                                data-gambar="{{ $permainan->gambar ? asset('storage/' . $permainan->gambar) : '' }}"
                                data-toggle="modal" data-target="#modalEdit">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <form action="{{ route('admin.game.destroy', $permainan->id_permainan) }}"
                                method="POST" class="d-inline"
                                onsubmit="return confirm('Hapus game {{ $permainan->nama_permainan }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">Belum ada data game.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="modalTambah" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.game.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Game</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="form-group">
                            <label>Nama Game <span class="text-danger">*</span></label>
                            <input type="text" name="nama_permainan"
                                class="form-control @error('nama_permainan') is-invalid @enderror"
                                value="{{ old('nama_permainan') }}" 
                                maxlength="30" 
                                placeholder="Contoh: God of War Ragnarök"
                                required>
                            @error('nama_permainan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Maksimal 30 karakter</small>
                        </div>

                        <div class="form-group">
                            <label>Perangkat <span class="text-danger">*</span></label>
                            <select name="device" class="form-control @error('device') is-invalid @enderror" required>
                                <option value="">-- Pilih Perangkat --</option>
                                <option value="PS3" {{ old('device') == 'PS3' ? 'selected' : '' }}>PS3</option>
                                <option value="PS4" {{ old('device') == 'PS4' ? 'selected' : '' }}>PS4</option>
                                <option value="PS5" {{ old('device') == 'PS5' ? 'selected' : '' }}>PS5</option>
                            </select>
                            @error('device')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Gambar Cover Game</label>
                            <input type="file" name="gambar" 
                                class="form-control-file @error('gambar') is-invalid @enderror"
                                accept="image/jpg,image/jpeg,image/png,image/webp"
                                onchange="previewImage(event, 'preview-tambah')">
                            @error('gambar')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Format: JPG, JPEG, PNG, WEBP. Maksimal 2MB</small>
                            
                            {{-- Preview Image --}}
                            <div class="mt-2">
                                <img id="preview-tambah" src="" alt="Preview" 
                                    class="img-thumbnail" 
                                    style="display:none; max-width: 200px; max-height: 200px; object-fit: cover;">
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

  
    <div class="modal fade" id="modalEdit" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formEdit" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Game</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="form-group">
                            <label>Nama Game <span class="text-danger">*</span></label>
                            <input type="text" name="nama_permainan" id="edit_nama_permainan"
                                class="form-control" 
                                maxlength="30" 
                                required>
                            <small class="text-muted">Maksimal 30 karakter</small>
                        </div>

                        <div class="form-group">
                            <label>Perangkat <span class="text-danger">*</span></label>
                            <select name="device" id="edit_device" class="form-control" required>
                                <option value="">-- Pilih Perangkat --</option>
                                <option value="PS3">PS3</option>
                                <option value="PS4">PS4</option>
                                <option value="PS5">PS5</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Gambar Cover Game</label>
                            <input type="file" name="gambar" 
                                class="form-control-file"
                                accept="image/jpg,image/jpeg,image/png,image/webp"
                                onchange="previewImage(event, 'preview-edit')">
                            <small class="text-muted d-block">
                                Format: JPG, JPEG, PNG, WEBP. Maksimal 2MB<br>
                                <span class="text-info">Kosongkan jika tidak ingin mengubah gambar</span>
                            </small>
                            
                            {{-- Current & Preview Image --}}
                            <div class="mt-2">
                                <img id="preview-edit" src="" alt="Preview" 
                                    class="img-thumbnail" 
                                    style="max-width: 200px; max-height: 200px; object-fit: cover;">
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save"></i> Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@stop

@section('js')
<script>
  
    function previewImage(event, previewId) {
        const file = event.target.files[0];
        const preview = document.getElementById(previewId);
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(file);
        } else {
            preview.src = '';
            preview.style.display = 'none';
        }
    }

  
    document.querySelectorAll('.btn-edit').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const nama = this.dataset.nama;
            const device = this.dataset.device;
            const gambar = this.dataset.gambar;

            // Set action form ke route update
            document.getElementById('formEdit').action = '/admin/game/' + id;

            // Isi field
            document.getElementById('edit_nama_permainan').value = nama;
            document.getElementById('edit_device').value = device !== '-' ? device : '';

            // Tampilkan gambar existing jika ada
            const previewEdit = document.getElementById('preview-edit');
            if (gambar) {
                previewEdit.src = gambar;
                previewEdit.style.display = 'block';
            } else {
                previewEdit.src = 'https://via.placeholder.com/200x200?text=No+Image';
                previewEdit.style.display = 'block';
            }
        });
    });

    $('#modalTambah').on('hidden.bs.modal', function () {
        $(this).find('form')[0].reset();
        document.getElementById('preview-tambah').style.display = 'none';
    });

    $('#modalEdit').on('hidden.bs.modal', function () {
        $(this).find('input[type="file"]').val('');
    });
</script>
@stop