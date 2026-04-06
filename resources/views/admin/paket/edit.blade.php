@extends('adminlte::page')

@section('title', 'Edit Paket')

@section('content_header')
    <h1>Edit Paket</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.paket.update', $paket->id_paket) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label>Nama Paket</label>
                    <input type="text" name="nama_paket" class="form-control" required maxlength="50"
                        value="{{ $paket->nama_paket }}">
                </div>
                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi_paket" class="form-control" rows="3">{{ $paket->deskripsi_paket }}</textarea>
                </div>
                <div class="form-group">
                    <label>Maksimal Orang</label>
                    <input type="number" name="maksimal_orang" class="form-control" min="1"
                        value="{{ $paket->maksimal_orang }}">
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="is_active" class="form-control">
                        <option value="1" {{ $paket->is_active ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ !$paket->is_active ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
                <a href="{{ route('admin.paket.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>
@stop