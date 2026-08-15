@extends('adminlte::page')
@include('partials.sidebar-superadmin')

@section('title', 'Edit User')

@section('content_header')
    <h1>Edit User</h1>
@stop

@section('content')

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <strong>Error!</strong> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <strong>Perhatian!</strong> Ada kesalahan pada form:
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
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
                            {{-- ✅ TAMBAHKAN error class --}}
                            <select name="role" class="form-control @error('role') is-invalid @enderror" required>
                                <option value="pelanggan" {{ old('role', $user->role) == 'pelanggan' ? 'selected' : '' }}>Pelanggan</option>
                                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="superadmin" {{ old('role', $user->role) == 'superadmin' ? 'selected' : '' }}>Superadmin</option>
                            </select>
                            @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group">
                            <label>No. HP</label>
                            {{-- ✅ TAMBAHKAN error class --}}
                            <input type="text" name="no_hp" class="form-control @error('no_hp') is-invalid @enderror"
                                value="{{ old('no_hp', $user->no_hp) }}" maxlength="15">
                            @error('no_hp') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group">
                            <label>Alamat</label>
                            <textarea name="alamat" 
                                class="form-control @error('alamat') is-invalid @enderror" 
                                rows="3" 
                                maxlength="255"
                                id="alamatInput">{{ old('alamat', $user->alamat) }}</textarea>
                            @error('alamat') 
                                <div class="invalid-feedback">{{ $message }}</div> 
                            @enderror
                            <small class="form-text text-muted">
                                <span id="charCount">0</span>/255 karakter
                            </small>
                        </div>

                        <a href="{{ route('superadmin.users.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>

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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const alamatInput = document.getElementById('alamatInput');
        const charCount = document.getElementById('charCount');
        
        if (alamatInput && charCount) {
            // Update counter saat halaman load
            charCount.textContent = alamatInput.value.length;
            
            // Update counter saat user mengetik
            alamatInput.addEventListener('input', function() {
                charCount.textContent = this.value.length;
                
                // Warning jika mendekati limit
                if (this.value.length > 240) {
                    charCount.classList.add('text-warning');
                } else {
                    charCount.classList.remove('text-warning');
                }
            });
        }
    });
</script>
@endpush

@stop