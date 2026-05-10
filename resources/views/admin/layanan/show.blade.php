@extends('adminlte::page')
@include('partials.sidebar-admin')
@section('title', 'Detail Ruangan')

@section('content_header')
    <h1>Detail Ruangan: {{ $ruangan->nama_ruangan }}</h1>
@stop

@section('content')

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Info Ruangan --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Informasi Ruangan</h3>
            <div class="card-tools">
                <a href="{{ route('admin.layanan.edit', $ruangan->id_ruangan) }}"
                    class="btn btn-warning btn-sm">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a href="{{ route('admin.layanan.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                {{-- Kiri: Info --}}
                <div class="col-md-7">
                    <table class="table table-borderless">
                        <tr><th width="150">Nama Ruangan</th><td>{{ $ruangan->nama_ruangan }}</td></tr>
                        <tr><th>Kategori</th><td><span class="badge badge-info">{{ $ruangan->kategori }}</span></td></tr>
                        <tr><th>Perangkat</th><td>{{ $ruangan->perangkat ?? '-' }}</td></tr>
                        <tr><th>Status</th><td>
                            @if($ruangan->is_active)
                                <span class="badge badge-success">Aktif</span>
                            @else
                                <span class="badge badge-danger">Nonaktif</span>
                            @endif
                        </td></tr>
                    </table>
                </div>

                {{-- Kanan: Foto --}}
                <div class="col-md-5 text-center">
                    @if($ruangan->galeri)
                        <img src="{{ asset('storage/' . $ruangan->galeri) }}"
                            alt="{{ $ruangan->nama_ruangan }}"
                            style="width:100%;max-height:220px;object-fit:cover;border-radius:10px;border:1px solid #dee2e6;">
                    @else
                        <div class="d-flex flex-column align-items-center justify-content-center h-100 text-muted"
                            style="border:2px dashed #dee2e6;border-radius:10px;padding:40px 20px;">
                            <i class="fas fa-image fa-3x mb-2"></i>
                            <p class="mb-2">Belum ada foto</p>
                            <a href="{{ route('admin.layanan.edit', $ruangan->id_ruangan) }}"
                                class="btn btn-sm btn-warning">
                                <i class="fas fa-upload"></i> Upload Foto
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@stop