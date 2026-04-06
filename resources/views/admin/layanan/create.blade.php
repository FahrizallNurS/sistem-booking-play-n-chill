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
                    <input type="text" name="nama_ruangan" class="form-control" required maxlength="30">
                </div>
                <div class="form-group">
                    <label>Kategori</label>
                    <select name="ms_kategori_id_kategori" class="form-control" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategoris as $kategori)
                            <option value="{{ $kategori->id_kategori }}">{{ $kategori->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="description" class="form-control" rows="3"></textarea>
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