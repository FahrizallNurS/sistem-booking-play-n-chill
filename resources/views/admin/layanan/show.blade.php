@extends('adminlte::page')
@include('partials.sidebar-admin')
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

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Informasi Ruangan</h3>
            <div class="card-tools">
                <form action="{{ route('admin.layanan.toggle-aktif', $ruangan->id_ruangan) }}"
                    method="POST" style="display:inline">
                    @csrf @method('PATCH')
                    <button type="submit"
                        class="btn btn-{{ $ruangan->is_active ? 'warning' : 'success' }} btn-sm"
                        onclick="return confirm('{{ $ruangan->is_active ? 'Nonaktifkan' : 'Aktifkan' }} ruangan ini?')">
                        <i class="fas fa-{{ $ruangan->is_active ? 'ban' : 'check' }}"></i>
                        {{ $ruangan->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                    </button>
                </form>
                @php
                    $punya_transaksi = $ruangan->penetapanHarga->flatMap->transaksis->isNotEmpty();
                @endphp

                <button type="button" class="btn btn-danger btn-sm"
                    data-toggle="modal"
                    data-target="{{ $punya_transaksi ? '#modalBlokHapus' : '#modalHapus' }}">
                    <i class="fas fa-trash"></i> Hapus
                </button>

                <a href="{{ route('admin.layanan.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                {{-- Kiri: Info --}}
                <div class="col-md-7">
                    <table class="table table-borderless">
                        <tr><th width="150">Nama Ruangan</th><td>{{ $ruangan->nama_ruangan }}</td></tr>
                        <tr><th>Kategori</th><td><span class="badge badge-info">{{ $ruangan->kategori }}</span></td></tr>
                        <tr><th>Perangkat</th><td>{{ $ruangan->perangkat ?? '-' }}</td></tr>
                        <tr><th>Status</th><td>
                            @if($ruangan->is_active)
                                <span class="badge badge-success">Aktif</span>
                            @else
                                <span class="badge badge-danger">Nonaktif</span>
                            @endif
                        </td></tr>
                    </table>
                </div>

                <div class="col-md-5 text-center">
                    @if($ruangan->galeri)
                        <img src="{{ asset($ruangan->galeri) }}"
                            alt="{{ $ruangan->nama_ruangan }}"
                            style="width:100%;max-height:220px;object-fit:cover;border-radius:10px;border:1px solid #dee2e6;">
                    @else
                        <div class="d-flex flex-column align-items-center justify-content-center h-100 text-muted"
                            style="border:2px dashed #dee2e6;border-radius:10px;padding:40px 20px;">
                            <i class="fas fa-image fa-3x mb-2"></i>
                            <p class="mb-2">Belum ada foto</p>
                            <a href="{{ route('admin.layanan.edit', $ruangan->id_ruangan) }}"
                                class="btn btn-sm btn-warning">
                                <i class="fas fa-upload"></i> Upload Foto
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalBlokHapus" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title text-white">
                        <i class="fas fa-exclamation-triangle"></i> Ruangan Tidak Dapat Dihapus
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>
                        <strong>{{ $ruangan->nama_ruangan }}</strong> memiliki
                        riwayat transaksi booking yang tersimpan di sistem.
                    </p>
                    <p>Ruangan yang memiliki riwayat transaksi <strong>tidak dapat dihapus</strong>
                    untuk menjaga integritas data dan laporan keuangan.</p>
                    <div class="alert alert-info mb-0">
                        <i class="fas fa-lightbulb"></i>
                        Jika ingin menonaktifkan ruangan ini, gunakan tombol
                        <strong>"Nonaktifkan"</strong> agar ruangan tidak muncul
                        saat booking namun data transaksinya tetap aman.
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
    <div class="modal fade" id="modalHapus" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title">
                        <i class="fas fa-exclamation-circle"></i> Konfirmasi Hapus Ruangan
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger mb-0">
                        <i class="fas fa-exclamation-triangle"></i>
                        Ruangan ini akan dihapus permanen dan tidak dapat dipulihkan kembali.
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary px-4" data-dismiss="modal">
                        <i class="fas fa-times"></i> Batal
                    </button>
                    <form action="{{ route('admin.layanan.destroy', $ruangan->id_ruangan) }}"
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


@stop