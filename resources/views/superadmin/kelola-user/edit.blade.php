@extends('superadmin.layouts.app')

@section('title', 'Edit User')

@section('content_header',)
    <h1>Edit User</h1>
@stop

@section('content')

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">

        {{-- Form Edit User --}}
        <div class="col-md-7">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Data User</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('superadmin.users.update', $user->id_pengguna) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="form-group">
                            <label>Nama</label>
                            <input type="text" name="nama_pengguna" class="form-control @error('nama_pengguna') is-invalid @enderror"
                                value="{{ old('nama_pengguna', $user->nama_pengguna) }}" required>
                            @error('nama_pengguna') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', $user->email) }}" required>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group">
                            <label>Role</label>
                            <select name="role" class="form-control" required>
                                <option value="pelanggan" {{ $user->role == 'pelanggan' ? 'selected' : '' }}>Pelanggan</option>
                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="superadmin" {{ $user->role == 'superadmin' ? 'selected' : '' }}>Superadmin</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>No. HP</label>
                            <input type="text" name="no_hp" class="form-control"
                                value="{{ old('no_hp', $user->no_hp) }}" maxlength="15">
                        </div>

                        <div class="form-group">
                            <label>Alamat</label>
                            <textarea name="alamat" class="form-control" rows="3">{{ old('alamat', $user->alamat) }}</textarea>
                        </div>

                        <a href="{{ route('superadmin.users.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Ganti Password --}}
        <div class="col-md-5">
            <div class="card">
                <div class="card-header bg-warning">
                    <h3 class="card-title">Ganti Password</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('superadmin.users.password', $user->id_pengguna) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="form-group">
                            <label>Password Baru</label>
                            <input type="password" name="password"
                                class="form-control @error('password') is-invalid @enderror" required>
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group">
                            <label>Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
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
                            <th>Role</th>
                            <td><span class="badge badge-primary">{{ ucfirst($user->role) }}</span></td>
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