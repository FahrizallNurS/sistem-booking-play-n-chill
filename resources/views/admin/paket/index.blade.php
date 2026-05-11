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
                                    <span class="badge badge-secondary">Nonaktif</span>  {{-- ganti dari badge-danger --}}
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.paket.edit', $paket->id_paket) }}" 
                                class="btn btn-info btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                
                                {{-- Tombol Toggle Status --}}
                                @if($paket->is_active)
                                    {{-- Paket Aktif - Tampilkan tombol Nonaktifkan --}}
                                    <form action="{{ route('admin.paket.toggle-aktif', $paket->id_paket) }}" 
                                        method="POST" 
                                        style="display:inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                class="btn btn-warning btn-sm" 
                                                onclick="return confirm('Nonaktifkan paket {{ $paket->nama_paket }}?')">
                                            <i class="fas fa-ban"></i> Nonaktifkan
                                        </button>
                                    </form>
                                @else
                                    {{-- Paket Nonaktif - Tampilkan tombol Aktifkan --}}
                                    <form action="{{ route('admin.paket.toggle-aktif', $paket->id_paket) }}" 
                                        method="POST" 
                                        style="display:inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                class="btn btn-success btn-sm" 
                                                onclick="return confirm('Aktifkan kembali paket {{ $paket->nama_paket }}?')">
                                            <i class="fas fa-check-circle"></i> Aktifkan
                                        </button>
                                    </form>
                                @endif
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