@extends('adminlte::page')
@include('partials.sidebar-admin')
@section('title', 'Edit Paket')

@section('content_header')
    <h1>Edit Paket</h1>
@stop

@section('content')

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

{{-- ============================================================ --}}
{{-- CARD 1: INFO PAKET --}}
{{-- ============================================================ --}}
<div class="card">
    <div class="card-header bg-light">
        <h5 class="mb-0"><i class="fas fa-box"></i> Info Paket</h5>
    </div>
    <div class="card-body">

        @if($errors->any() && !$errors->has('harga') && !$errors->has('sku') && !$errors->has('sku_conflict'))
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.paket.update', $paket->id_paket) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Sub Kategori Paket</label>
                <select name="id_sub_kategori_paket" id="sub_kategori_select" class="form-control">
                    <option value="">-- Pilih Sub Kategori --</option>
                    @foreach($subKategoris as $sk)
                        <option value="{{ $sk->id_sub_kategori_paket }}"
                            {{ old('id_sub_kategori_paket', $paket->ms_sub_kategori_paket_id_sub_kategori_paket) == $sk->id_sub_kategori_paket ? 'selected' : '' }}>
                            {{ $sk->nama_sub_kategori }}
                        </option>
                    @endforeach
                    <option value="__baru__" {{ old('sub_kategori_baru') ? 'selected' : '' }}>+ Tambah Sub Kategori Baru</option>
                </select>

                <input type="text" name="sub_kategori_baru" id="sub_kategori_baru"
                    class="form-control mt-2"
                    placeholder="Nama sub kategori baru, contoh: PS4 Reguler"
                    value="{{ old('sub_kategori_baru') }}"
                    style="{{ old('sub_kategori_baru') ? '' : 'display:none' }}">
            </div>

            <div class="form-group">
                <label>Nama Paket</label>
                <input type="text" name="nama_paket" class="form-control @error('nama_paket') is-invalid @enderror"
                    required maxlength="40"
                    value="{{ old('nama_paket', $paket->nama_paket) }}">
                @error('nama_paket')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Deskripsi <small class="text-muted"></small></label>
                <textarea name="deskripsi_paket" class="form-control" rows="4">{{ old('deskripsi_paket', $paket->deskripsi_paket) }}</textarea>
            </div>

            <div class="form-group">
                <label>Maksimal Orang</label>
                <input type="number" name="maksimal_orang" class="form-control" min="1"
                    value="{{ old('maksimal_orang', $paket->maksimal_orang) }}" required>
            </div>

            <div class="form-group">
                <label>Status</label>
                <select name="is_active" class="form-control">
                    <option value="1" {{ old('is_active', $paket->is_active) == 1 ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ old('is_active', $paket->is_active) == 0 ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            <div class="d-flex justify-content-end">
                <a href="{{ route('admin.paket.index') }}" class="btn btn-secondary mr-2">Batal</a>
                <button type="submit" class="btn btn-primary">Update Info Paket</button>
            </div>
        </form>
    </div>
</div>

{{-- ============================================================ --}}
{{-- CARD 2: TABEL PENETAPAN HARGA AKTIF (PENGGANTI CARD 3 LAMA) --}}
{{-- ============================================================ --}}
<div class="card mt-3">
    <div class="card-header bg-info">
    <h3 class="card-title mt-1">📋 Penetapan Harga yang Berlaku Saat Ini</h3>
    <div class="card-tools">
            <button type="button" class="btn btn-light btn-sm font-weight-bold" data-toggle="modal" data-target="#modalTambahPenetapan">
                <i class="fas fa-plus text-primary"></i> Tambah Harga
            </button>
        </div>
    </div>
    
    <div class="card-body">
        
        @if($errors->has('kombinasi'))
            <div class="alert alert-danger">{{ $errors->first('kombinasi') }}</div>
        @endif

        @if($paket->penetapanHarga->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-sm table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th width="5%">#</th>
                            <th>Ruangan</th>
                            <th>Tipe Hari</th>
                            <th>Durasi</th>
                            <th>Harga</th>
                            <th>SKU</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($paket->penetapanHarga as $index => $ph)
                            @php $sudahDipakai = $ph->transaksis()->exists(); @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <span class="badge badge-primary">
                                        {{ $ph->ruangan->nama_ruangan ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    @if($ph->tipe_hari == 'harian')
                                        <span class="badge badge-success">Harian</span>
                                    @elseif($ph->tipe_hari == 'akhir_pekan')
                                        <span class="badge badge-warning">Akhir Pekan</span>
                                    @else
                                        <span class="badge badge-danger">Liburan</span>
                                    @endif
                                </td>
                                <td>{{ $ph->durasi_jam }} jam</td>
                                <td><strong>Rp {{ number_format($ph->harga, 0, ',', '.') }}</strong></td>
                                <td>{{ $ph->sku ?? '-' }}</td>
                                <td>
                                    {{-- Tombol Edit sekarang selalu sama, gak peduli udah dipakai transaksi atau belum --}}
                                    <button type="button" class="btn btn-info btn-sm btn-edit-inline"
                                        data-id="{{ $ph->id_penetapan_harga }}"
                                        data-ruangan="{{ $ph->ruangan->nama_ruangan ?? '-' }}"
                                        data-tipe-hari="{{ $ph->tipe_hari }}"
                                        data-durasi="{{ $ph->durasi_jam }}"
                                        data-harga="{{ $ph->harga }}"
                                        data-sku="{{ $ph->sku }}">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>

                                    @if($sudahDipakai)
                                        <button class="btn btn-secondary btn-sm" disabled title="Tidak bisa dihapus (ada transaksi)">
                                            <i class="fas fa-lock"></i>
                                        </button>
                                    @else
                                        <form action="{{ route('admin.paket.penetapan.destroy', $ph->id_penetapan_harga) }}"
                                            method="POST" class="d-inline form-hapus-penetapan">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-warning mb-0">
                <i class="fas fa-exclamation-triangle"></i>
                Belum ada penetapan harga untuk paket ini. Silakan klik "Tambah Harga".
            </div>
        @endif

{{-- ============================================================ --}}
{{-- MODAL: TAMBAH PENETAPAN HARGA BARU --}}
{{-- ============================================================ --}}
<div class="modal fade" id="modalTambahPenetapan" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.paket.penetapan.storeModal', $paket->id_paket) }}" method="POST">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Tambah Harga Baru</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Ruangan</label>
                        <select name="id_ruangan" class="form-control" required>
                            <option value="">-- Pilih Ruangan --</option>
                            @foreach($ruangans as $r)
                                <option value="{{ $r->id_ruangan }}" {{ old('id_ruangan') == $r->id_ruangan ? 'selected' : '' }}>
                                    {{ $r->nama_ruangan }} ({{ $r->kategori }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Tipe Hari</label>
                        <select name="tipe_hari" class="form-control" required>
                            <option value="">-- Pilih Hari --</option>
                            <option value="harian" {{ old('tipe_hari') === 'harian' ? 'selected' : '' }}>Harian (Senin - Kamis)</option>
                            <option value="akhir_pekan" {{ old('tipe_hari') === 'akhir_pekan' ? 'selected' : '' }}>Akhir Pekan (Jumat - Minggu)</option>
                            <option value="liburan" {{ old('tipe_hari') === 'liburan' ? 'selected' : '' }}>Liburan (Senin - Minggu, sepanjang periode aktif)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Durasi (Jam)</label>
                        <input type="number" name="durasi_jam" class="form-control" min="1" value="{{ old('durasi_jam') }}" required>
                    </div>

                    <div class="form-group">
                        <label>Harga (Rp)</label>
                        <input type="text" inputmode="numeric" name="harga" class="form-control input-format-rupiah" value="{{ old('harga') }}" required>
                    </div>

                    <div class="form-group mb-0">
                        <label>SKU</label>
                        <input type="text" name="sku" maxlength="10" class="form-control @error('sku') is-invalid @enderror" value="{{ old('sku') }}" required>
                        @error('sku')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
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


{{-- ============================================================ --}}
{{-- MODAL: EDIT PENETAPAN HARGA (Harga saja, Ruangan/Tipe Hari/SKU read-only) --}}
{{-- ============================================================ --}}
<div class="modal fade" id="modalEditPenetapan" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="formEditPenetapan" method="POST">
                @csrf
                @method('PATCH')

                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title">Edit Harga</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-light border">
                        <strong>Ruangan:</strong> <span id="modalEditRuangan"></span><br>
                        <strong>Tipe Hari:</strong> <span id="modalEditTipeHariLabel"></span><br>
                        <strong>Durasi:</strong> <span id="modalEditDurasi"></span> jam
                    </div>

                    <div class="form-group">
                        <label>Harga Baru (Rp)</label>
                        <input type="text" inputmode="numeric" name="harga" id="modalEditHarga"
                            class="form-control @error('harga') is-invalid @enderror" required>
                        @error('harga')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-0">
                        <label>SKU (Tidak dapat diubah)</label>
                        <input type="text" id="modalEditSku" class="form-control bg-light" readonly>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-info">Update Harga</button>
                </div>
            </form>
        </div>
    </div>
</div>

@stop

@section('css')
<style>
    .fade-out-row {
        transition: opacity 0.35s ease, transform 0.35s ease;
        opacity: 0;
        transform: translateX(16px);
    }
</style>
@stop

    @section('js')
<script>
    const subKategoriSelect = document.getElementById('sub_kategori_select');
    const subKategoriBaruInput = document.getElementById('sub_kategori_baru');

    function toggleSubKategoriBaru() {
        if (subKategoriSelect.value === '__baru__') {
            subKategoriBaruInput.style.display = 'block';
            subKategoriSelect.name = '';
        } else {
            subKategoriBaruInput.style.display = 'none';
            subKategoriBaruInput.value = '';
            subKategoriSelect.name = 'id_sub_kategori_paket';
        }
    }

    subKategoriSelect.addEventListener('change', toggleSubKategoriBaru);

    const TIPE_HARI_LABEL = { harian: 'Harian', akhir_pekan: 'Akhir Pekan', liburan: 'Liburan' };

    // Format Rupiah untuk Modal
    document.querySelectorAll('#modalEditHarga, .input-format-rupiah').forEach(input => {
        input.addEventListener('input', function (e) {
            this.value = this.value.replace(/[^\d.]/g, '');
        });
    });

    // Populate data ke Modal Edit
    document.querySelectorAll('.btn-edit-inline').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;
            document.getElementById('formEditPenetapan').action = `/admin/paket/penetapan/${id}`;
            document.getElementById('modalEditRuangan').textContent = this.dataset.ruangan;
            document.getElementById('modalEditTipeHariLabel').textContent = TIPE_HARI_LABEL[this.dataset.tipeHari] || this.dataset.tipeHari;
            document.getElementById('modalEditDurasi').textContent = this.dataset.durasi;
            document.getElementById('modalEditHarga').value = this.dataset.harga;
            document.getElementById('modalEditSku').value = this.dataset.sku;
            $('#modalEditPenetapan').modal('show');
        });
    });

    // Fade Out saat Hapus
    document.querySelectorAll('.form-hapus-penetapan').forEach(form => {
        form.addEventListener('submit', function (e) {
            if (!confirm('Hapus penetapan harga ini?')) {
                e.preventDefault();
                return;
            }
            e.preventDefault();
            const row = this.closest('tr');
            row.classList.add('fade-out-row');
            setTimeout(() => this.submit(), 350);
        });
    });

    // Repopulasi saat load awal atau error validasi
    window.addEventListener('DOMContentLoaded', function () {
        toggleSubKategoriBaru();
        
        // Buka modal tambah jika ada error dari sana
        @if($errors->has('sku') || $errors->has('kombinasi'))
            $('#modalTambahPenetapan').modal('show');
        @endif
    });
</script>
@stop