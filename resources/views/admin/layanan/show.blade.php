@extends('adminlte::page')

@section('title', 'Detail Ruangan')

@section('content_header')
    <h1>Detail Ruangan: {{ $ruangan->nama_ruangan }}</h1>
@stop

@section('content')

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Info Ruangan --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Informasi Ruangan</h3>
            <div class="card-tools">
                <a href="{{ route('admin.layanan.edit', $ruangan->id_ruangan) }}"
                    class="btn btn-warning btn-sm">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a href="{{ route('admin.layanan.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-borderless">
                <tr><th width="150">Nama Ruangan</th><td>{{ $ruangan->nama_ruangan }}</td></tr>
                <tr><th>Kategori</th><td><span class="badge badge-info">{{ $ruangan->kategori }}</span></td></tr>
                <tr><th>Perangkat</th><td>{{ $ruangan->perangkat ?? '-' }}</td></tr>
                <tr><th>Deskripsi</th><td>{{ $ruangan->deskripsi ?? '-' }}</td></tr>
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

    {{-- Form Tambah Penetapan Harga --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Penetapan Harga</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.layanan.penetapan.store', $ruangan->id_ruangan) }}"
                method="POST" class="mb-4">
                @csrf
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Paket</label>
                            <select name="id_paket" class="form-control" required>
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
                            <select name="tipe_hari" class="form-control" required>
                                <option value="harian">Harian</option>
                                <option value="akhir_pekan">Akhir Pekan</option>
                                <option value="liburan">Liburan</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Durasi (jam)</label>
                            <input type="number" name="durasi_jam" class="form-control"
                                min="1" required placeholder="contoh: 1">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Harga (Rp)</label>
                            <input type="number" name="harga" class="form-control"
                                min="0" required>
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

            {{-- Tabel Penetapan Harga --}}
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
                    @forelse($ruangan->penetapanHarga as $index => $ph)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $ph->paket->nama_paket ?? '-' }}</td>
                            <td><span class="badge badge-info">{{ $ph->tipe_hari }}</span></td>
                            <td>{{ $ph->durasi_jam }} jam</td>
                            <td>Rp {{ number_format($ph->harga, 0, ',', '.') }}</td>
                            <td>
                                <form action="{{ route('admin.layanan.penetapan.destroy', $ph->id_penetapan_harga) }}"
                                    method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Hapus penetapan harga ini?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada penetapan harga</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@stop