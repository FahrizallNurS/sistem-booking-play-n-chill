@extends('superadmin.layouts.app')
@section('title', 'Tambah User')

@section('content_header', 'Tambah User')

@section('content')
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Error!</strong> {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Perhatian!</strong> Ada kesalahan pada form:
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

<div class="card">
    <div class="card-body">
        <form action="{{ route('superadmin.users.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label>Nama</label>
                <input type="text" name="nama_pengguna" class="form-control @error('nama_pengguna') is-invalid @enderror"
                    value="{{ old('nama_pengguna') }}" required>
                @error('nama_pengguna') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email') }}" required>
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password"
                    class="form-control @error('password') is-invalid @enderror" required>
                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Role</label>
                <select name="role" class="form-control @error('role') is-invalid @enderror" required>
                    <option value="">-- Pilih Role --</option>
                    <option value="pelanggan" {{ old('role') == 'pelanggan' ? 'selected' : '' }}>Pelanggan</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="superadmin" {{ old('role') == 'superadmin' ? 'selected' : '' }}>Superadmin</option>
                </select>
                @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>No. HP</label>
                <input type="text" name="no_hp" class="form-control @error('no_hp') is-invalid @enderror"
                    value="{{ old('no_hp') }}" maxlength="15">
                @error('no_hp') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>Alamat</label>
                <textarea name="alamat" 
                    class="form-control @error('alamat') is-invalid @enderror" 
                    rows="3" 
                    maxlength="255" 
                    id="alamatInput">{{ old('alamat') }}</textarea>
                @error('alamat') 
                    <div class="invalid-feedback">{{ $message }}</div> 
                @enderror
                <small class="form-text text-muted">
                    <span id="charCount">0</span>/255 karakter
                </small>
            </div>

            <a href="{{ route('superadmin.users.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const alamatInput = document.getElementById('alamatInput');
        const charCount = document.getElementById('charCount');
        
        if (alamatInput) {
            charCount.textContent = alamatInput.value.length;

            alamatInput.addEventListener('input', function() {
                charCount.textContent = this.value.length;

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
@endsection