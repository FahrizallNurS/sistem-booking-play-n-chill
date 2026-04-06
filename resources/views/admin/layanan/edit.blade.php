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
                    <input type="text" name="nama_ruangan" class="form-control" value="{{ $ruangan->nama_ruangan }}" required maxlength="30">
                </div>
                <div class="form-group">
                    <label>Kategori</label>
                    <select name="ms_kategori_id_kategori" class="form-control" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategoris as $kategori)
                            <option value="{{ $kategori->id_kategori }}"
                                {{ $ruangan->ms_kategori_id_kategori == $kategori->id_kategori ? 'selected' : '' }}>
                                {{ $kategori->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="description" class="form-control" rows="3">{{ $ruangan->description }}</textarea>
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