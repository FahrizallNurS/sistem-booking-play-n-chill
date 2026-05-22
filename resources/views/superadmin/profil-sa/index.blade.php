@extends('superadmin.layouts.app')
@section('title', 'Profil Superadmin')

@section('content_header')
    <h1>Profil Saya</h1>
@stop

@section('content')

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">

        {{-- Edit Profil --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Profil</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('superadmin.profil.update') }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="form-group">
                            <label>Nama</label>
                            <input type="text" name="nama_pengguna"
                                class="form-control @error('nama_pengguna') is-invalid @enderror"
                                value="{{ old('nama_pengguna', $user->nama_pengguna) }}" required>
                            @error('nama_pengguna') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', $user->email) }}" required>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group">
                            <label>No. HP</label>
                            <input type="text" name="no_hp" class="form-control"
                                value="{{ old('no_hp', $user->no_hp) }}" maxlength="15">
                        </div>

                        <div class="form-group">
                            <label>Alamat</label>
                            <textarea name="alamat" class="form-control"
                                rows="3">{{ old('alamat', $user->alamat) }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-save"></i> Simpan Perubahan
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Ganti Password --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Ganti Password</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('superadmin.profil.password') }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="form-group">
                            <label>Password Lama</label>
                            <input type="password" name="password_lama"
                                class="form-control @error('password_lama') is-invalid @enderror" required>
                            @error('password_lama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Password Baru</label>
                            <input type="password" name="password_baru"
                                class="form-control @error('password_baru') is-invalid @enderror" required>
                            @error('password_baru')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Konfirmasi Password Baru</label>
                            <input type="password" name="password_baru_confirmation"
                                class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-warning btn-block">
                            <i class="fas fa-lock"></i> Ganti Password
                        </button>
                    </form>
                </div>
            </div>

            {{-- Info Akun --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Info Akun</h3>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="120">Role</th>
                            <td><span class="badge badge-danger">Superadmin</span></td>
                        </tr>
                        <tr>
                            <th>Terdaftar</th>
                            <td>{{ $user->created_at->format('d/m/Y') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

    </div>

@stop