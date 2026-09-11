@extends('adminlte::page')
@include('partials.sidebar-admin')
@section('title', 'Kelola Paket')

@section('content_header')
    <h1>Kelola Paket</h1>
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
            <h3 class="card-title">Daftar Paket</h3>
            <div class="card-tools">
                <a href="{{ route('admin.paket.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Tambah Paket
                </a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Paket</th>
                        <th>Sub Kategori</th>
                        <th>Deskripsi</th>
                        <th>Tipe Hari</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pakets as $index => $paket)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $paket->nama_paket }}</td>
                            <td>
                                @if($paket->subKategori)
                                    <span class="badge badge-secondary">{{ $paket->subKategori->nama_sub_kategori }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ $paket->deskripsi_paket ?? '-' }}</td>
                            <td>
                                @if($paket->penetapanHarga->isNotEmpty())
                                    @foreach($paket->penetapanHarga->pluck('tipe_hari')->unique() as $tipe)
                                        <span class="badge badge-info">{{ ucwords(str_replace('_', ' ', $tipe)) }}</span>
                                    @endforeach
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($paket->is_active)
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-secondary">Nonaktif</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.paket.edit', $paket->id_paket) }}" 
                                class="btn btn-info btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                
                                {{-- Tombol Toggle Status --}}
                                @if($paket->is_active)
                                    <form action="{{ route('admin.paket.toggle-aktif', $paket->id_paket) }}" 
                                        method="POST" 
                                        style="display:inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                class="btn btn-warning btn-sm" 
                                                onclick="return confirm('Nonaktifkan paket {{ $paket->nama_paket }}?')">
                                            <i class="fas fa-ban"></i> Nonaktifkan
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.paket.toggle-aktif', $paket->id_paket) }}" 
                                        method="POST" 
                                        style="display:inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                class="btn btn-success btn-sm" 
                                                onclick="return confirm('Aktifkan kembali paket {{ $paket->nama_paket }}?')">
                                            <i class="fas fa-check-circle"></i> Aktifkan
                                        </button>
                                    </form>
                                @endif

                                {{--
                                    FIX: $paket->punya_transaksi sekarang dihitung di controller
                                    berdasarkan SEMUA penetapan harga (current + historical),
                                    bukan cuma yang currentPrices(). Jadi modal yang muncul di
                                    sini sudah konsisten dengan pengecekan di destroy().
                                --}}
                                <button type="button" class="btn btn-danger btn-sm"
                                    data-toggle="modal"
                                    data-target="{{ $paket->punya_transaksi ? '#modalBlokHapus' . $paket->id_paket : '#modalHapus' . $paket->id_paket }}">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                                @if($paket->punya_transaksi)
                                    <div class="modal fade" id="modalBlokHapus{{ $paket->id_paket }}" tabindex="-1" role="dialog">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <p>
                                                        <strong>{{ $paket->nama_paket }}</strong> memiliki
                                                        riwayat transaksi booking yang tersimpan di sistem.
                                                    </p>
                                                    <p>Paket ini memiliki riwayat transaksi dan tidak dapat dihapus untuk menjaga data laporan keuangan.</p>
                                                    <div class="alert alert-info mb-0">
                                                        <i class="fas fa-lightbulb"></i>
                                                        Jika ingin menonaktifkan paket ini, gunakan tombol
                                                        <strong>"Nonaktifkan"</strong> agar paket tidak muncul
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
                                    @else
                                    <div class="modal fade" id="modalHapus{{ $paket->id_paket }}" tabindex="-1" role="dialog">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <div class="alert alert-danger mb-0">
                                                        <i class="fas fa-exclamation-triangle"></i>
                                                        Paket ini akan dihapus permanen, apakah anda ingin menghapusnya?
                                                    </div>
                                                </div>
                                                <div class="modal-footer d-flex justify-content-between">
                                                    <button type="button" class="btn btn-secondary px-4" data-dismiss="modal">
                                                        <i class="fas fa-times"></i> Batal
                                                    </button>
                                                    <form action="{{ route('admin.paket.destroy', $paket->id_paket) }}"
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
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Belum ada data paket</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@stop