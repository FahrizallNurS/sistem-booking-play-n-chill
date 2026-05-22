@extends('superadmin.layouts.app')

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

                {{-- Nomor --}}
                <td>{{ $index + 1 }}</td>

                {{-- Nama --}}
                <td>{{ $user->nama_pengguna }}</td>

                {{-- Email --}}
                <td>{{ $user->email }}</td>

                {{-- No HP --}}
                <td>{{ $user->no_hp ?? '-' }}</td>

                {{-- Role --}}
                <td>
                    @if($user->role === 'superadmin')
                        <span class="badge badge-danger">Superadmin</span>
                    @elseif($user->role === 'admin')
                        <span class="badge badge-warning">Admin</span>
                    @else
                        <span class="badge badge-info">Pelanggan</span>
                    @endif
                </td>

                {{-- Status --}}
                <td>
                    @if($user->status == 1)
                        <span class="badge badge-success">Aktif</span>
                    @else
                        <span class="badge badge-secondary">Nonaktif</span>
                    @endif
                </td>

                {{-- Terdaftar --}}
                <td>{{ $user->created_at->format('d/m/Y') }}</td>

                <td>
                    <a href="{{ route('superadmin.users.edit', $user->id_pengguna) }}"
                        class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i> Edit
                    </a>

                    @if($user->id_pengguna !== auth()->user()->id_pengguna)

                        {{-- Toggle Status --}}
                        <form action="{{ route('superadmin.users.toggle-status', $user->id_pengguna) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                class="btn btn-sm {{ $user->status == 1 ? 'btn-secondary' : 'btn-success' }}"
                                onclick="return confirm('{{ $user->status == 1 ? 'Nonaktifkan' : 'Aktifkan' }} user ini?')">
                                {{ $user->status == 1 ? 'Nonaktifkan' : 'Aktifkan' }}
                            </button>
                        </form>

                        {{-- Tombol Hapus — cek transaksi --}}
                        @if($user->transaksis()->exists())
                            {{-- Punya transaksi: tampilkan modal block --}}
                            <button type="button" class="btn btn-danger btn-sm"
                                data-toggle="modal"
                                data-target="#modalBlokHapus{{ $user->id_pengguna }}">
                                <i class="fas fa-trash"></i> Hapus
                            </button>

                            {{-- Modal Hard Block --}}
                            <div class="modal fade" id="modalBlokHapus{{ $user->id_pengguna }}" tabindex="-1" role="dialog">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger">
                                            <h5 class="modal-title text-white">
                                                <i class="fas fa-exclamation-triangle"></i> User Tidak Dapat Dihapus
                                            </h5>
                                            <button type="button" class="close text-white" data-dismiss="modal">
                                                <span>&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <p>
                                                <strong>{{ $user->nama_pengguna }}</strong> memiliki
                                                <strong>{{ $user->transaksis()->count() }} data transaksi</strong>
                                                yang tersimpan di sistem.
                                            </p>
                                            <p>User yang memiliki riwayat transaksi <strong>tidak dapat dihapus</strong>
                                            untuk menjaga integritas data dan laporan keuangan.</p>
                                            <div class="alert alert-info mb-0">
                                                <i class="fas fa-lightbulb"></i>
                                                Jika ingin mencabut akses user ini, gunakan tombol
                                                <strong>"Nonaktifkan"</strong> agar user tidak bisa login
                                                namun data transaksinya tetap aman.
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                <i class="fas fa-times"></i> Tutup
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        @else
                            {{-- Tidak punya transaksi: modal konfirmasi hapus permanen --}}
                            <button type="button" class="btn btn-danger btn-sm"
                                data-toggle="modal"
                                data-target="#modalHapus{{ $user->id_pengguna }}">
                                <i class="fas fa-trash"></i> Hapus
                            </button>

                            
                            <div class="modal fade" id="modalHapus{{ $user->id_pengguna }}" tabindex="-1" role="dialog">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-warning">
                                            <h5 class="modal-title">
                                                <i class="fas fa-exclamation-circle"></i> Konfirmasi Hapus User
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal">
                                                <span>&times;</span>
                                            </button>
                                        </div>

                                        <div class="modal-body">
                                            <div class="alert alert-danger mb-0">
                                                <i class="fas fa-exclamation-triangle"></i>
                                                Akun ini akan <strong>dihapus permanen</strong> dan
                                                <strong>tidak dapat dipulihkan kembali.</strong>
                                            </div>
                                        </div>

                                        <div class="modal-footer d-flex justify-content-between">
                                            <button type="button" class="btn btn-secondary px-4" data-dismiss="modal">
                                                <i class="fas fa-times"></i> Batal
                                            </button>
                                            <form action="{{ route('superadmin.users.destroy', $user->id_pengguna) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger px-4">
                                                    <i class="fas fa-trash"></i> Ya, Hapus Permanen
                                                </button>
                                            </form>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        @endif

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