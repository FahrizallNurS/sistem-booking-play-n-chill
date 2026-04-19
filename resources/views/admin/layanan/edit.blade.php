@extends('adminlte::page')

@section('title', 'Edit Ruangan')

@section('content_header')
    <h1>Edit Ruangan</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.layanan.update', $ruangan->id_ruangan) }}" method="POST">
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

            <a href="{{ route('admin.layanan.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
</div>
@stop