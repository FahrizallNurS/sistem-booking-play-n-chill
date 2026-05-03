@extends('adminlte::page')

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
                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalTambahGame">
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
                        <th>Device</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Dummy data --}}
                    <tr>
                        <td>1</td>
                        <td>
                            <img src="https://via.placeholder.com/60x60" alt="game" class="img-thumbnail" width="60">
                        </td>
                        <td>FIFA 24</td>
                        <td><span class="badge badge-info">PS4</span></td>
                        <td>
                            <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modalEditGame">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Hapus game ini?')">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>
                            <img src="https://via.placeholder.com/60x60" alt="game" class="img-thumbnail" width="60">
                        </td>
                        <td>GTA V</td>
                        <td><span class="badge badge-success">PS5</span></td>
                        <td>
                            <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modalEditGame">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Hapus game ini?')">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal Tambah Game --}}
    <div class="modal fade" id="modalTambahGame" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Game</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form action="#" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nama Game</label>
                            <input type="text" name="nama_game" class="form-control" required maxlength="60"
                                placeholder="contoh: FIFA 24">
                        </div>
                        <div class="form-group">
                            <label>Device</label>
                            <select name="device_game" class="form-control" required>
                                <option value="">-- Pilih Device --</option>
                                <option value="PS3">PS3</option>
                                <option value="PS4">PS4</option>
                                <option value="PS5">PS5</option>
                                <option value="Nintendo">Nintendo</option>
                                <option value="PC">PC</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Gambar Game</label>
                            <input type="file" name="gambar_game" class="form-control-file" accept="image/*">
                            <small class="text-muted">Format: JPG, PNG. Maks 2MB</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Edit Game --}}
    <div class="modal fade" id="modalEditGame" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Game</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form action="#" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nama Game</label>
                            <input type="text" name="nama_game" class="form-control" required maxlength="60"
                                value="FIFA 24">
                        </div>
                        <div class="form-group">
                            <label>Device</label>
                            <select name="device_game" class="form-control" required>
                                <option value="">-- Pilih Device --</option>
                                <option value="PS3">PS3</option>
                                <option value="PS4" selected>PS4</option>
                                <option value="PS5">PS5</option>
                                <option value="Nintendo">Nintendo</option>
                                <option value="PC">PC</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Gambar Sekarang</label>
                            <br>
                            <img src="https://via.placeholder.com/100x100" alt="game" class="img-thumbnail mb-2" width="100">
                        </div>
                        <div class="form-group">
                            <label>Ganti Gambar <small class="text-muted">(opsional)</small></label>
                            <input type="file" name="gambar_game" class="form-control-file" accept="image/*">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@stop