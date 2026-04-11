@extends('adminlte::page')

@section('title', 'Profil Superadmin')

@section('content_header')
    <h1>Profil Superadmin</h1>
@stop

@section('content')

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        {{-- Edit Profil --}}
        <div class="col-md-6">
            <div class="card card-primary card-outline"> {{-- Tambah aksen garis biru biar beda --}}
                <div class="card-header">
                    <h3 class="card-title">Update Data Diri</h3>
                </div>
                <div class="card-body">
                    {{-- Ganti route-nya ke route superadmin yang kita buat tadi --}}
                    <form action="{{ route('superadmin.profil.index') }}" method="POST"> 
                        @csrf
                        {{-- Sementara arahkan ke index dulu atau ganti ke route update jika sudah ada controllernya --}}
                        
                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input type="text" name="name" class="form-control" value="{{ Auth::user()->name }}" required>
                        </div>

                        <div class="form-group">
                            <label>Email Superadmin</label>
                            <input type="email" name="email" class="form-control" value="{{ Auth::user()->email }}" required>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-save"></i> Simpan Profil SA
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Info Status Superadmin --}}
        <div class="col-md-6">
            <div class="card card-dark"> {{-- Warna gelap biar kerasa "Super" --}}
                <div class="card-header">
                    <h3 class="card-title">Hak Akses Sistem</h3>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <i class="fas fa-user-shield fa-5x text-secondary"></i>
                    </div>
                    <table class="table">
                        <tr>
                            <th>Status Akun</th>
                            <td><span class="badge badge-danger">SUPER ADMIN</span></td>
                        </tr>
                        <tr>
                            <th>Login Terakhir</th>
                            <td>{{ now()->format('d M Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop