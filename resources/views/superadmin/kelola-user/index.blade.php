@extends('adminlte::page')

@section('title', 'Kelola Data User')

@section('content')
<div class="container-fluid pt-4">
    {{-- Tombol Tambah Pengguna --}}
    <div class="d-flex justify-content-end mb-3">
        <button class="btn btn-primary" style="border-radius: 10px; background-color: #6c5ce7; border: none; padding: 10px 20px;">
            <i class="fas fa-plus"></i> Tambah Pengguna
        </button>
    </div>

    {{-- Container Tabel Ungu --}}
    <div class="custom-card">
        <div class="table-responsive">
            <table class="table table-borderless custom-table">
                <thead>
                    <tr>
                        <th>Id.</th>
                        <th>User Name</th>
                        <th>Email</th>
                        <th>Nomor Telp.</th>
                        <th>Role</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Baris dengan Sorotan Kuning/Hijau --}}
                    <tr class="highlight-row">
                        <td>1.</td>
                        <td>PPP</td>
                        <td>p@gmail.com</td>
                        <td>08123456789</td>
                        <td>Pelanggan</td>
                        <td class="text-center">
                            {{-- Tombol Edit untuk memicu Modal --}}
                            <button class="btn-edit" data-toggle="modal" data-target="#modalEditUser">
                                <i class="fas fa-pencil-alt"></i>
                            </button>
                            {{-- Tombol Hapus --}}
                            <button class="btn-delete">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                    {{-- Baris Standar --}}
                    <tr>
                        <td>2.</td>
                        <td>Gojo Satoru</td>
                        <td>gojo@pnc.com</td>
                        <td>085731907665</td>
                        <td>Pelanggan</td>
                        <td class="text-center">
                            <button class="btn-edit" data-toggle="modal" data-target="#modalEditUser">
                                <i class="fas fa-pencil-alt"></i>
                            </button>
                            <button class="btn-delete">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Memanggil file Modal --}}
@include('superadmin.kelola-user.modal-edit')
@stop

@section('css')
<style>
    /* Mengatur background utama agar bersih */
    .content-wrapper { background-color: #f4f6f9 !important; }

    /* Container Ungu Bulat */
    .custom-card {
        background-color: #8c7ae6; 
        border-radius: 25px;
        padding: 15px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        overflow: hidden;
    }

    .custom-table { color: white !important; margin-bottom: 0; }
    .custom-table thead th { 
        border: none; 
        font-weight: 600; 
        font-size: 1.1rem;
        padding: 20px 15px;
    }

    /* Gaya Baris Sorotan (Kuning Terang) */
    .highlight-row { 
        background-color: #d1ff00 !important; 
        color: #2d3436 !important; 
        font-weight: bold; 
    }
    .highlight-row td { color: #2d3436 !important; }

    /* Baris Tabel Biasa */
    .custom-table tbody tr { border-bottom: 1px solid rgba(255,255,255,0.1); }
    .custom-table td { vertical-align: middle; padding: 15px; }

    /* Tombol Aksi Bulat */
    .btn-edit, .btn-delete {
        border: none;
        border-radius: 50%;
        width: 38px;
        height: 38px;
        margin: 0 5px;
        transition: all 0.3s ease;
    }
    .btn-edit { background-color: #a29bfe; color: #6c5ce7; }
    .btn-delete { background-color: #ff7675; color: white; }

    .btn-edit:hover { background-color: white; transform: scale(1.1); }
    .btn-delete:hover { background-color: #d63031; transform: scale(1.1); }
</style>
@stop