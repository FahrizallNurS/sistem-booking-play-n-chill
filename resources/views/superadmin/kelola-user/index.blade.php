@extends('superadmin.layouts.app')
@include('partials.sidebar-superadmin')
@section('title', 'Kelola User')

@section('content_header')
    <h1>Kelola User</h1>
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
            <h3 class="card-title">Daftar User</h3>
            <div class="card-tools">
                <a href="{{ route('superadmin.users.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Tambah User
                </a>
            </div>
        </div>
        <div class="card-body">
            <form method="GET"
      action="{{ route('superadmin.users.index') }}"
      class="mb-3">

    <div class="row">

        {{-- Search --}}
        <div class="col-md-4">

            <input type="text"
                   name="search"
                   class="form-control"
                   placeholder="Cari nama atau email..."
                   value="{{ request('search') }}">

        </div>

        {{-- Filter Role --}}
        <div class="col-md-3">

            <select name="role" class="form-control">

                <option value="">
                    Semua Role
                </option>

                <option value="superadmin"
                    {{ request('role') == 'superadmin' ? 'selected' : '' }}>
                    Superadmin
                </option>

                <option value="admin"
                    {{ request('role') == 'admin' ? 'selected' : '' }}>
                    Admin
                </option>

                <option value="pelanggan"
                    {{ request('role') == 'pelanggan' ? 'selected' : '' }}>
                    Pelanggan
                </option>

            </select>

        </div>

        {{-- Tombol Search --}}
        <div class="col-md-2">

            <button type="submit"
                    class="btn btn-primary btn-block">

                <i class="fas fa-search"></i>
                Cari

            </button>

        </div>

        {{-- Reset --}}
        <div class="col-md-2">

            <a href="{{ route('superadmin.users.index') }}"
               class="btn btn-secondary btn-block">

                Reset

            </a>

        </div>

    </div>

    </form>
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>No. HP</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Terdaftar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($users as $index => $user)
                    <tr>
                        <td>
                            @if($user->status == 1)
                                <span class="badge badge-success">Aktif</span>
                            @else
                                <span class="badge badge-secondary">Nonaktif</span>
                            @endif
                        </td>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $user->nama_pengguna }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->no_hp ?? '-' }}</td>
                        <td>
                            @if($user->role === 'superadmin')
                                <span class="badge badge-danger">Superadmin</span>
                            @elseif($user->role === 'admin')
                                <span class="badge badge-warning">Admin</span>
                            @else
                                <span class="badge badge-info">Pelanggan</span>
                            @endif
                        </td>
                        <td>{{ $user->created_at->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('superadmin.users.edit', $user->id_pengguna) }}"
                                class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            @if($user->id_pengguna !== auth()->user()->id_pengguna)
                                <form action="{{ route('superadmin.users.destroy', $user->id_pengguna) }}"
                                    method="POST" style="display:inline">
                                    @csrf
                                    @method('DELETE')

                                    @if($user->status == 1)
                                        <button type="submit"
                                            class="btn btn-warning btn-sm"
                                            onclick="return confirm('Nonaktifkan user ini?')">

                                            <i class="fas fa-user-slash"></i> Nonaktifkan
                                        </button>
                                    @else
                                        <button type="submit"
                                            class="btn btn-success btn-sm"
                                            onclick="return confirm('Aktifkan user ini kembali?')">

                                            <i class="fas fa-user-check"></i> Aktifkan
                                        </button>
                                    @endif
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Belum ada data user</td>
                    </tr>
                @endforelse
            </tbody>
            </table>
        </div>
    </div>

@stop