@extends('superadmin.layouts.app')

@section('title', 'Dashboard Superadmin')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Dashboard Superadmin</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            {{-- Box Total Pelanggan --}}
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        {{-- Mengambil data dinamis dari Controller --}}
                        <h3>{{ $totalPelanggan ?? '0' }}</h3>
                        <p>Total Pelanggan</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <a href="{{ route('superadmin.dashboard') }}" class="small-box-footer">
                        Kelola User <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>44</h3>
                        <p>Laporan Baru</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    {{-- Pastikan rute 'superadmin.laporan' sudah terdaftar di web.php --}}
                    <a href="#" class="small-box-footer">
                        Tinjau Laporan <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>53</h3>
                        <p>Booking Berhasil</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="small-box-footer" style="height: 30px"></div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>99<sup style="font-size: 20px">%</sup></h3>
                        <p>Server Uptime</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-server"></i>
                    </div>
                    <div class="small-box-footer" style="height: 30px"></div>
                </div>
            </div>
        </div>

        {{-- Tabel Aktivitas Terbaru --}}
        <div class="row">
            <div class="col-md-12">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Aktivitas Pendaftaran Terbaru</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-hover table-bordered text-nowrap">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama Pengguna</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Contoh data statis, nanti bisa dilooping pakai @foreach --}}
                                <tr>
                                    <td>1</td>
                                    <td>Ahmad Budi</td>
                                    <td>ahmad@example.com</td>
                                    <td><span class="badge badge-info">Pelanggan</span></td>
                                    <td><span class="badge badge-success">Aktif</span></td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Siti Aminah</td>
                                    <td>siti@example.com</td>
                                    <td><span class="badge badge-info">Pelanggan</span></td>
                                    <td><span class="badge badge-success">Aktif</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer text-right">
                        <a href="{{ route('superadmin.dashboard') }}" class="btn btn-primary btn-sm">Lihat Semua Pengguna</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .small-box .icon i { font-size: 70px; }
        .card-title { font-weight: bold; }
    </style>
@stop