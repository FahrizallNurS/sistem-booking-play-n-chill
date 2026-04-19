@extends('adminlte::page')

@section('title', 'Tambah Ruangan')

@section('content_header')
    <h1>Tambah Ruangan</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.layanan.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label>Nama Ruangan</label>
                <input type="text" name="nama_ruangan"
                    class="form-control @error('nama_ruangan') is-invalid @enderror"
                    value="{{ old('nama_ruangan') }}" required maxlength="20"
                    placeholder="contoh: REGULER-01">
                @error('nama_ruangan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Kategori</label>
                <select name="kategori" class="form-control @error('kategori') is-invalid @enderror" required>
                    <option value="">-- Pilih Kategori --</option>
                    <option value="REGULAR" {{ old('kategori') == 'REGULAR' ? 'selected' : '' }}>Regular</option>
                    <option value="VIP" {{ old('kategori') == 'VIP' ? 'selected' : '' }}>VIP</option>
                    <option value="VVIP" {{ old('kategori') == 'VVIP' ? 'selected' : '' }}>VVIP</option>
                </select>
                @error('kategori')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Perangkat</label>
                <input type="text" name="perangkat" class="form-control"
                    value="{{ old('perangkat') }}" maxlength="10"
                    placeholder="contoh: PS4, PS5">
            </div>

            <div class="form-group">
                <label>Deskripsi</label>
                <input type="text" name="deskripsi" class="form-control"
                    value="{{ old('deskripsi') }}" maxlength="60">
            </div>

            <div class="form-group">
                <label>Status</label>
                <select name="is_active" class="form-control">
                    <option value="1">Aktif</option>
                    <option value="0">Nonaktif</option>
                </select>
            </div>

            <a href="{{ route('admin.layanan.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>
@stop