@extends('adminlte::page')
@include('partials.sidebar-admin')
@section('title', 'Kelola Layanan')

@section('content_header')
    <h1>Kelola Layanan / Ruangan</h1>
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
            <h3 class="card-title">Daftar Ruangan</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalTambah">
                    <i class="fas fa-plus"></i> Tambah Ruangan
                </button>
            </div>
        </div>
        <div class="card-body">

        <div class="mb-2">
            <a href="?sort=kategori_asc" class="btn btn-sm btn-primary">Kategori ↑</a>
            <a href="?sort=kategori_desc" class="btn btn-sm btn-secondary">Kategori ↓</a>
        </div>

            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Ruangan</th>
                        <th>Kategori</th>
                        <th>Perangkat</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ruangans as $index => $ruangan)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $ruangan->nama_ruangan }}</td>
                            <td><span class="badge badge-info">{{ $ruangan->kategori }}</span></td>
                            <td>{{ $ruangan->perangkat ?? '-' }}</td>
                            <td>
                                @if($ruangan->is_active)
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-danger">Nonaktif</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.layanan.show', $ruangan->id_ruangan) }}"
                                    class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                                <button type="button" class="btn btn-warning btn-sm btn-edit"
                                    data-id="{{ $ruangan->id_ruangan }}"
                                    data-nama="{{ $ruangan->nama_ruangan }}"
                                    data-kategori="{{ $ruangan->kategori }}"
                                    data-perangkat="{{ $ruangan->perangkat }}"
                                    data-active="{{ $ruangan->is_active }}"
                                    data-toggle="modal" data-target="#modalEdit">
                                    <i class="fas fa-edit"></i> Edit
                                </button>   
                                <form action="{{ route('admin.layanan.toggle-aktif', $ruangan->id_ruangan) }}"
                                    method="POST" style="display:inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-{{ $ruangan->is_active ? 'warning' : 'success' }} btn-sm"
                                        onclick="return confirm('{{ $ruangan->is_active ? 'Nonaktifkan' : 'Aktifkan' }} ruangan ini?')">
                                        <i class="fas fa-{{ $ruangan->is_active ? 'ban' : 'check' }}"></i>
                                        {{ $ruangan->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>

                                    @if($ruangan->penetapanHarga->flatMap->transaksis->isNotEmpty())
                                        {{-- Punya transaksi: hard block --}}
                                        <button type="button" class="btn btn-danger btn-sm"
                                            data-toggle="modal"
                                            data-target="#modalBlokHapus{{ $ruangan->id_ruangan }}">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>

                                        <div class="modal fade" id="modalBlokHapus{{ $ruangan->id_ruangan }}" tabindex="-1" role="dialog">
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
                                                        <p>Ruangan yang memiliki riwayat transaksi tidak dapat dihapus untuk menjaga data laporan keuangan.</p>
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

                                    @else
                                        <button type="button" class="btn btn-danger btn-sm"
                                            data-toggle="modal"
                                            data-target="#modalHapus{{ $ruangan->id_ruangan }}">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                        <div class="modal fade" id="modalHapus{{ $ruangan->id_ruangan }}" tabindex="-1" role="dialog">
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
                                                            Ruangan ini akan <strong>dihapus permanen</strong>
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
                                    @endif     
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada data ruangan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ═══ MODAL TAMBAH RUANGAN ═══ --}}
    <div class="modal fade" id="modalTambah" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.layanan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Ruangan</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="form-group">
                            <label>Nama Ruangan</label>
                            <input type="text" name="nama_ruangan"
                                class="form-control @error('nama_ruangan') is-invalid @enderror"
                                value="{{ old('nama_ruangan') }}" maxlength="20" required>
                            @error('nama_ruangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Kategori</label>
                            <select name="kategori" class="form-control @error('kategori') is-invalid @enderror" required>
                                <option value="">-- Pilih Kategori --</option>
                                <option value="REGULAR" {{ old('kategori') == 'REGULAR' ? 'selected' : '' }}>REGULAR</option>
                                <option value="PRIVATE-ROOM" {{ old('kategori') == 'PRIVATE-ROOM' ? 'selected' : '' }}>PRIVATE ROOM</option>
                            </select>
                            @error('kategori')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Perangkat</label>
                            <select name="perangkat" class="form-control">
                                <option value="">-- Pilih Perangkat --</option>
                                <option value="PS3" {{ old('perangkat') == 'PS3' ? 'selected' : '' }}>PS3</option>
                                <option value="PS4" {{ old('perangkat') == 'PS4' ? 'selected' : '' }}>PS4</option>
                                <option value="PS5" {{ old('perangkat') == 'PS5' ? 'selected' : '' }}>PS5</option>
                                <option value="Nintendo Switch" {{ old('perangkat') == 'Nintendo Switch' ? 'selected' : '' }}>Nintendo Switch</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Status</label>
                            <select name="is_active" class="form-control" required>
                                <option value="1" {{ old('is_active') == '1' ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Foto Ruangan</label>
                            <input type="file" name="galeri" class="form-control-file"
                                accept="image/jpg,image/jpeg,image/png,image/webp">
                            <small class="text-muted">Format: JPG, JPEG, PNG, WEBP. Maks 2MB</small>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ═══ MODAL EDIT RUANGAN ═══ --}}
    <div class="modal fade" id="modalEdit" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formEdit" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Ruangan</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="form-group">
                            <label>Nama Ruangan</label>
                            <input type="text" name="nama_ruangan" id="edit_nama_ruangan"
                                class="form-control" maxlength="20" required>
                        </div>

                        <div class="form-group">
                            <label>Kategori</label>
                            <select name="kategori" id="edit_kategori" class="form-control" required>
                                <option value="REGULAR">REGULAR</option>
                                <option value="PRIVATE-ROOM">PRIVATE ROOM</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Perangkat</label>
                            <select name="perangkat" id="edit_perangkat" class="form-control">
                                <option value="">-- Pilih Perangkat --</option>
                                <option value="PS3">PS3</option>
                                <option value="PS4">PS4</option>
                                <option value="PS5">PS5</option>
                                <option value="Nintendo Switch">Nintendo Switch</option>
                            </select>
                        </div>  

                        <div class="form-group">
                            <label>Status</label>
                            <select name="is_active" id="edit_is_active" class="form-control" required>
                                <option value="1">Aktif</option>
                                <option value="0">Nonaktif</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Foto Ruangan <small class="text-muted">(kosongkan jika tidak ingin mengubah foto)</small></label>
                            <input type="file" name="galeri" class="form-control-file"
                                accept="image/jpg,image/jpeg,image/png,image/webp">
                            <small class="text-muted">Format: JPG, JPEG, PNG, WEBP. Maks 2MB</small>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@stop

@section('js')
<script>
    // Isi data ke modal edit saat tombol edit diklik
    document.querySelectorAll('.btn-edit').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const id       = this.dataset.id;
            const nama     = this.dataset.nama;
            const kategori = this.dataset.kategori;
            const perangkat = this.dataset.perangkat;
            const active   = this.dataset.active;

            // Set action form ke route update
            document.getElementById('formEdit').action = '/admin/layanan/' + id;

            // Isi field
            document.getElementById('edit_nama_ruangan').value = nama;
            document.getElementById('edit_kategori').value     = kategori;
            document.getElementById('edit_perangkat').value    = perangkat !== 'null' ? perangkat : '';
            document.getElementById('edit_is_active').value    = active;
        });
    });
</script>
@stop