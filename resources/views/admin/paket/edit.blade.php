@extends('adminlte::page')
@include('partials.sidebar-admin')
@section('title', 'Edit Paket')

@section('content_header')
    <h1>Edit Paket</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('admin.paket.update', $paket->id_paket) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Sub Kategori Paket</label>
                <select name="id_sub_kategori_paket" id="sub_kategori_select" class="form-control">
                    <option value="">-- Pilih Sub Kategori --</option>
                    @foreach($subKategoris as $sk)
                        <option value="{{ $sk->id_sub_kategori_paket }}" {{ $paket->ms_sub_kategori_paket_id_sub_kategori_paket == $sk->id_sub_kategori_paket ? 'selected' : '' }}>
                            {{ $sk->nama_sub_kategori }}
                        </option>
                    @endforeach
                    <option value="__baru__">+ Tambah Sub Kategori Baru</option>
                </select>

                <input type="text" name="sub_kategori_baru" id="sub_kategori_baru"
                    class="form-control mt-2"
                    placeholder="Nama sub kategori baru, contoh: PS4 Reguler"
                    style="display:none">
            </div>

            <div class="form-group">
                <label>Nama Paket</label>
                <input type="text" name="nama_paket" class="form-control" required maxlength="40"
                    value="{{ $paket->nama_paket }}">
            </div>

            <div class="form-group">
                <label>Deskripsi <small class="text-muted"></small></label>
                <textarea name="deskripsi_paket" class="form-control" rows="4">{{ $paket->deskripsi_paket }}</textarea>
            </div>

            <div class="form-group">
                <label>Maksimal Orang</label>
                <input type="number" name="maksimal_orang" class="form-control" min="1"
                    value="{{ $paket->maksimal_orang }}" required>
            </div>

            <div class="form-group">
                <label>Status</label>
                <select name="is_active" class="form-control">
                    <option value="1" {{ $paket->is_active ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ !$paket->is_active ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            <hr>
            <h5>Tambah Penetapan Harga Baru</h5>

            <div class="form-group">
                <label>Kategori Ruangan</label>
                <select id="kategori_select" class="form-control">
                    <option value="">-- Pilih Kategori --</option>
                    <option value="REGULAR">Regular</option>
                    <option value="PRIVATE-ROOM">Private Room</option>
                </select>
            </div>

            <div class="form-group">
                <label>Pilih Ruangan <small class="text-muted">(bisa pilih lebih dari satu)</small></label>
                <div id="ruangan_container" class="border rounded p-2" style="min-height:50px">
                    <small class="text-muted">Pilih kategori dulu...</small>
                </div>
            </div>

            <div class="form-group">
                <label>Tipe Hari</label>
                <select name="tipe_hari" class="form-control">
                    <option value="">-- Pilih Hari --</option>
                    <option value="harian">Harian (Senin - Jumat)</option>
                    <option value="akhir_pekan">Akhir Pekan (Sabtu - Minggu)</option>
                    <option value="liburan">Liburan</option>
                </select>
            </div>

            <div id="pricing_container">
                <div class="pricing-row d-flex align-items-center mb-2">
                    <input type="number" name="durasi_jam[]" class="form-control mr-2"
                        placeholder="Durasi (jam)" min="1">
                    <input type="number" name="harga[]" class="form-control mr-2"
                        placeholder="Harga (Rp)" min="0">
                    <button type="button" class="btn btn-danger btn-hapus-pricing">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <button type="button" id="btn_tambah_pricing" class="btn btn-secondary btn-sm mb-3">
                <i class="fas fa-plus"></i> Tambah Durasi
            </button>

            <hr>
            <div class="d-flex justify-content-end">
                <a href="{{ route('admin.paket.index') }}" class="btn btn-secondary mr-2">Batal</a>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>

        </form>
    </div>
</div>

<div class="card mt-3">
    <div class="card-header bg-info">
        <h5 class="mb-0">📋 Penetapan Harga yang Berlaku Saat Ini</h5>
    </div>
    <div class="card-body">
        @if($paket->penetapanHarga->count() > 0)
            <table class="table table-bordered table-sm table-hover">
                <thead class="thead-light">
                    <tr>
                        <th width="5%">#</th>
                        <th>Ruangan</th>
                        <th>Tipe Hari</th>
                        <th>Durasi</th>
                        <th>Harga</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($paket->penetapanHarga as $index => $ph)
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
                            <td>
                                @if($ph->transaksis()->exists())
                                    <button class="btn btn-secondary btn-sm" disabled title="Tidak bisa dihapus (ada transaksi)">
                                        <i class="fas fa-lock"></i> Terkunci
                                    </button>
                                @else
                                    <form action="{{ route('admin.paket.penetapan.destroy', $ph->id_penetapan_harga) }}"
                                        method="POST" style="display:inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Hapus penetapan harga ini?')">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div class="alert alert-info mt-3 mb-0">
                <i class="fas fa-info-circle"></i>
                <strong>Catatan:</strong> Harga yang ditampilkan adalah harga terbaru yang berlaku saat ini. 
                Riwayat perubahan harga tetap tersimpan untuk keperluan laporan transaksi.
            </div>
        @else
            <div class="alert alert-warning mb-0">
                <i class="fas fa-exclamation-triangle"></i>
                Belum ada penetapan harga untuk paket ini.
            </div>
        @endif
    </div>
</div>
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

    document.getElementById('kategori_select').addEventListener('change', function() {
        const kategori = this.value;
        const container = document.getElementById('ruangan_container');
        container.innerHTML = '<small class="text-muted">Loading...</small>';

        if (!kategori) {
            container.innerHTML = '<small class="text-muted">Pilih kategori dulu...</small>';
            return;
        }

        fetch(`/admin/kategori/${kategori}/ruangan`)
            .then(res => res.text())
            .then(text => {
                const clean = text.replace(/^[^[{]*/, '');
                const data = JSON.parse(clean);
                container.innerHTML = '';
                if (data.length === 0) {
                    container.innerHTML = '<small class="text-muted">Tidak ada ruangan.</small>';
                    return;
                }
                data.forEach(r => {
                    container.innerHTML += `
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox"
                                name="ruangan_ids[]" value="${r.id_ruangan}" id="r_${r.id_ruangan}">
                            <label class="form-check-label" for="r_${r.id_ruangan}">
                                ${r.nama_ruangan}
                            </label>
                        </div>
                    `;
                });
            });
    });

    const pricingTemplate = `
        <input type="number" name="durasi_jam[]" class="form-control mr-2" placeholder="Durasi (jam)" min="1">
        <input type="number" name="harga[]" class="form-control mr-2" placeholder="Harga (Rp)" min="0">
        <button type="button" class="btn btn-danger btn-hapus-pricing"><i class="fas fa-times"></i></button>
    `;

    document.getElementById('btn_tambah_pricing').addEventListener('click', function() {
        const div = document.createElement('div');
        div.className = 'pricing-row d-flex mb-2';
        div.innerHTML = pricingTemplate;
        document.getElementById('pricing_container').appendChild(div);
    });

    document.getElementById('pricing_container').addEventListener('click', function(e) {
        if (e.target.closest('.btn-hapus-pricing')) {
            const rows = document.querySelectorAll('.pricing-row');
            if (rows.length > 1) e.target.closest('.pricing-row').remove();
        }
    });
</script>
@stop