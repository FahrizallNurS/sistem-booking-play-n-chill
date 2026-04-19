@extends('adminlte::page')

@section('title', 'Kelola Layanan')

@section('content_header')
    <h1>Kelola Layanan / Ruangan</h1>
@stop

@section('content')

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Ruangan</h3>
            <div class="card-tools">
                <a href="{{ route('admin.layanan.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Tambah Ruangan
                </a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Ruangan</th>
                        <th>Kategori</th>
                        <th>Perangkat</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ruangans as $index => $ruangan)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $ruangan->nama_ruangan }}</td>
                            <td><span class="badge badge-info">{{ $ruangan->kategori }}</span></td>
                            <td>{{ $ruangan->perangkat ?? '-' }}</td>
                            <td>
                                @if($ruangan->is_active)
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-danger">Nonaktif</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.layanan.show', $ruangan->id_ruangan) }}"
                                    class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                                <a href="{{ route('admin.layanan.edit', $ruangan->id_ruangan) }}"
                                    class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('admin.layanan.destroy', $ruangan->id_ruangan) }}"
                                    method="POST" style="display:inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Yakin hapus ruangan ini?')">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada data ruangan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@stop