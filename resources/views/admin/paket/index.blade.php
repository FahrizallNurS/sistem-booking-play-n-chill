@extends('adminlte::page')
@include('partials.sidebar-admin')
@section('title', 'Kelola Paket')

@section('content_header')
    <h1>Kelola Paket</h1>
@stop

@section('content')

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Paket</h3>
            <div class="card-tools">
                <a href="{{ route('admin.paket.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Tambah Paket
                </a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Paket</th>
                        <th>Deskripsi</th>
                        <th>Maks. Orang</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pakets as $index => $paket)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $paket->nama_paket }}</td>
                            <td>{{ $paket->deskripsi_paket ?? '-' }}</td>
                            <td>{{ $paket->maksimal_orang ?? '-' }}</td>
                            <td>
                                @if($paket->is_active)
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-danger">Nonaktif</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.paket.edit', $paket->id_paket) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('admin.paket.destroy', $paket->id_paket) }}" method="POST" style="display:inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus paket ini?')">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada data paket</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@stop