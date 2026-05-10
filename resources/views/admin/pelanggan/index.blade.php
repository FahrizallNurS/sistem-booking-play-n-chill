@extends('adminlte::page')
@include('partials.sidebar-admin')
@section('title', 'Data Pelanggan')

@section('content_header')
    <h1>Data Pelanggan</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Pelanggan</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>No. HP</th>
                        <th>Alamat</th>
                        <th>Terdaftar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pelanggans as $index => $pelanggan)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $pelanggan->nama_pengguna ?? '-' }}</td>
                            <td>{{ $pelanggan->email }}</td>
                            <td>{{ $pelanggan->no_hp ?? '-' }}</td>
                            <td>{{ $pelanggan->alamat ?? '-' }}</td>
                            <td>{{ $pelanggan->created_at->format('d/m/Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada data pelanggan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('css')
@stop

@section('js')
@stop