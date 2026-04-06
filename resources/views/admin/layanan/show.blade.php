@extends('adminlte::page')

@section('title', 'Detail Ruangan')

@section('content_header')
    <h1>Detail Ruangan: {{ $ruangan->nama_ruangan }}</h1>
@stop

@section('content')

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Info Ruangan --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Informasi Ruangan</h3>
            <div class="card-tools">
                <a href="{{ route('admin.layanan.edit', $ruangan->id_ruangan) }}" class="btn btn-warning btn-sm">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a href="{{ route('admin.layanan.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
        <div class="card-body">
            <table class="table">
                <tr><th width="150">Nama Ruangan</th><td>{{ $ruangan->nama_ruangan }}</td></tr>
                <tr><th>Kategori</th><td>{{ $ruangan->kategori->nama_kategori ?? '-' }}</td></tr>
                <tr><th>Deskripsi</th><td>{{ $ruangan->description ?? '-' }}</td></tr>
                <tr><th>Status</th><td>
                    @if($ruangan->is_active)
                        <span class="badge badge-success">Aktif</span>
                    @else
                        <span class="badge badge-danger">Nonaktif</span>
                    @endif
                </td></tr>
            </table>
        </div>
    </div>

    {{-- Pricing --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Paket & Harga</h3>
        </div>
        <div class="card-body">

            {{-- Form Tambah Pricing --}}
            <form action="{{ route('admin.layanan.pricing.store', $ruangan->id_ruangan) }}" method="POST" class="mb-4">
                @csrf
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Paket</label>
                            <select name="ms_paket_id_paket" class="form-control" required>
                                <option value="">-- Pilih Paket --</option>
                                @foreach($pakets as $paket)
                                    <option value="{{ $paket->id_paket }}">{{ $paket->nama_paket }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Tipe Hari</label>
                            <select name="tipe_pricing" class="form-control" required>
                                <option value="weekday">Weekday</option>
                                <option value="weekend">Weekend</option>
                                <option value="holiday">Holiday</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Hari Type</label>
                            <select name="hari_type" class="form-control" required>
                                <option value="weekday">Weekday</option>
                                <option value="weekend">Weekend</option>
                                <option value="holiday">Holiday</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Durasi (menit)</label>
                            <input type="number" name="durasi_menit" class="form-control" min="30" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Harga (Rp)</label>
                            <input type="number" name="harga" class="form-control" min="0" required>
                        </div>
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            {{-- Tabel Pricing --}}
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Paket</th>
                        <th>Tipe Hari</th>
                        <th>Durasi</th>
                        <th>Harga</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ruangan->pricings as $index => $pricing)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $pricing->paket->nama_paket ?? '-' }}</td>
                            <td><span class="badge badge-info">{{ $pricing->tipe_pricing }}</span></td>
                            <td>{{ $pricing->durasi_menit }} menit</td>
                            <td>Rp {{ number_format($pricing->harga, 0, ',', '.') }}</td>
                            <td>
                                <form action="{{ route('admin.layanan.pricing.destroy', $pricing->id_pricing) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Hapus pricing ini?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada paket harga</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@stop