@extends('adminlte::page')
@include('partials.sidebar-admin')
@section('title', 'Tambah Paket')

@section('content_header')
    <h1>Tambah Paket</h1>
@stop

@section('content')
            <div class="card">
                <div class="card-body">
                    @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        <form action="{{ route('admin.paket.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label>Sub Kategori Paket</label>
                <select name="id_sub_kategori_paket" id="sub_kategori_select" class="form-control @error('id_sub_kategori_paket') is-invalid @enderror">
                    <option value="">-- Pilih Sub Kategori --</option>
                    @foreach($subKategoris as $sk)
                        <option value="{{ $sk->id_sub_kategori_paket }}" {{ old('id_sub_kategori_paket') == $sk->id_sub_kategori_paket ? 'selected' : '' }}>
                            {{ $sk->nama_sub_kategori }}
                        </option>
                    @endforeach
                    <option value="__baru__" {{ old('sub_kategori_baru') ? 'selected' : '' }}>+ Tambah Sub Kategori Baru</option>
                </select>
                @error('id_sub_kategori_paket')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                <input type="text" name="sub_kategori_baru" id="sub_kategori_baru"
                    class="form-control mt-2 @error('sub_kategori_baru') is-invalid @enderror"
                    placeholder="Nama sub kategori baru, contoh: PS4 Reguler"
                    value="{{ old('sub_kategori_baru') }}"
                    style="{{ old('sub_kategori_baru') ? '' : 'display:none' }}">
                @error('sub_kategori_baru')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>


            <div class="form-group">
                <label>Nama Paket</label>
                <input type="text" name="nama_paket"
                    class="form-control @error('nama_paket') is-invalid @enderror"
                    value="{{ old('nama_paket') }}" required maxlength="40"
                    placeholder="contoh: Gaming Private Room">
                @error('nama_paket')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Deskripsi <small class="text-muted"></small></label>
                <textarea name="deskripsi_paket" class="form-control" rows="4"
                    placeholder="AC & WiFi&#10;PS5 Terbaru&#10;Sofa Nyaman">{{ old('deskripsi_paket') }}</textarea>
            </div>

            <div class="form-group">
                <label>Maksimal Orang</label>
                <input type="number" name="maksimal_orang" class="form-control"
                    value="{{ old('maksimal_orang') }}" min="1" required>
            </div>

            <div class="form-group">
                <label>Status</label>
                <select name="is_active" class="form-control">
                    <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            <hr>
            <h5>Penetapan Harga <small class="text-muted">(opsional, bisa diatur nanti)</small></h5>

            <div class="form-group">
                <label>Kategori Ruangan</label>
                <select id="kategori_select" class="form-control">
                    <option value="">-- Pilih Kategori --</option>
                    <option value="REGULAR" {{ old('kategori_dipilih') === 'REGULAR' ? 'selected' : '' }}>Regular</option>
                    <option value="PRIVATE-ROOM" {{ old('kategori_dipilih') === 'PRIVATE-ROOM' ? 'selected' : '' }}>Private Room</option>
                </select>
                {{-- Field bantu, bukan bagian dari validasi server — cuma dipakai
                     untuk merepopulasi kategori & daftar ruangan kalau validasi gagal. --}}
                <input type="hidden" name="kategori_dipilih" id="kategori_dipilih_hidden" value="{{ old('kategori_dipilih') }}">
            </div>

            <div class="form-group">
                <label>Pilih Ruangan <small class="text-muted">(bisa pilih lebih dari satu)</small></label>
                <div id="ruangan_container" class="border rounded p-2" style="min-height:50px">
                    <small class="text-muted">Pilih kategori dulu...</small>
                </div>
                @error('ruangan_ids')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Tipe Hari</label>
                <select name="tipe_hari" class="form-control">
                    <option value="">-- Pilih Hari --</option>
                    <option value="harian" {{ old('tipe_hari') === 'harian' ? 'selected' : '' }}>Harian (Senin - Kamis)</option>
                    <option value="akhir_pekan" {{ old('tipe_hari') === 'akhir_pekan' ? 'selected' : '' }}>Akhir Pekan (Jumat - Minggu)</option>
                    <option value="liburan" {{ old('tipe_hari') === 'liburan' ? 'selected' : '' }}>Liburan (Senin - Minggu, sepanjang periode aktif)</option>
                </select>
            </div>

            <div class="form-group">
                <label>Durasi (jam) <small class="text-muted">yang akan dibuka</small></label>
                <div id="pricing_container">
                    @php $oldDurasi = old('durasi_jam', ['']); @endphp
                    @foreach($oldDurasi as $d)
                    <div class="pricing-row d-flex align-items-center mb-2">
                        <input type="number" class="form-control durasi-input mr-2"
                            name="durasi_jam[]" placeholder="Durasi (jam)" min="1" style="max-width: 200px;"
                            value="{{ $d }}">
                        <button type="button" class="btn btn-danger btn-hapus-pricing">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    @endforeach
                </div>
                <button type="button" id="btn_tambah_pricing" class="btn btn-secondary btn-sm">
                    <i class="fas fa-plus"></i> Tambah Durasi
                </button>
            </div>

            <div class="form-group">
                <label>Harga & SKU per Ruangan <small class="text-muted">(isi sel yang ingin dibuka, boleh dikosongkan sebagian)</small></label>
                <div id="matrix_container">
                    <small class="text-muted">Pilih ruangan & isi durasi dulu untuk menampilkan tabel harga...</small>
                </div>
                @error('sku_conflict')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <hr>
            <a href="{{ route('admin.paket.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
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
    toggleSubKategoriBaru();

    // ============ ESCAPE HELPER (cegah XSS saat render innerHTML) ============
    function escapeHtml(str) {
        return String(str ?? '').replace(/[&<>"']/g, function (ch) {
            return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[ch];
        });
    }

    // ============ STATE MATRIKS HARGA & SKU ============
    // Diisi awal dari old('harga')/old('sku') supaya kalau validasi gagal,
    // admin tidak perlu isi ulang seluruh matrix dari nol.
    let matrixValues = {}; // { [durasi]: { [ruanganId]: { harga: '', sku: '' } } }

    (function seedMatrixFromOld() {
        const oldHarga = @json(old('harga', []));
        const oldSku = @json(old('sku', []));

        Object.keys(oldHarga).forEach(ruanganId => {
            Object.keys(oldHarga[ruanganId]).forEach(durasi => {
                if (!matrixValues[durasi]) matrixValues[durasi] = {};
                matrixValues[durasi][ruanganId] = {
                    harga: oldHarga[ruanganId][durasi] ?? '',
                    sku: (oldSku[ruanganId] && oldSku[ruanganId][durasi]) ?? '',
                };
            });
        });
    })();

    const oldRuanganIds = @json(old('ruangan_ids', [])).map(String);

    function getCheckedRuangans() {
        return Array.from(document.querySelectorAll('#ruangan_container input[type="checkbox"]:checked'))
            .map(cb => ({
                id: cb.value,
                nama: cb.closest('.form-check').querySelector('label').textContent.trim()
            }));
    }

    function getDurasiList() {
        const values = Array.from(document.querySelectorAll('.durasi-input'))
            .map(input => input.value)
            .filter(v => v !== '' && Number(v) > 0);
        return [...new Set(values)]; // durasi unik saja, hindari kolom ganda
    }

    function syncMatrixValuesFromDOM() {
        document.querySelectorAll('#matrix_container td[data-durasi][data-ruangan]').forEach(cell => {
            const durasi = cell.dataset.durasi;
            const ruanganId = cell.dataset.ruangan;
            const hargaInput = cell.querySelector('.matrix-harga');
            const skuInput = cell.querySelector('.matrix-sku');

            if (!matrixValues[durasi]) matrixValues[durasi] = {};
            matrixValues[durasi][ruanganId] = {
                harga: hargaInput ? hargaInput.value : '',
                sku: skuInput ? skuInput.value : '',
            };
        });
    }

    function renderMatrix() {
        const ruangans = getCheckedRuangans();
        const durasiList = getDurasiList();
        const container = document.getElementById('matrix_container');

        if (ruangans.length === 0 || durasiList.length === 0) {
            container.innerHTML = '<small class="text-muted">Pilih ruangan & isi durasi dulu untuk menampilkan tabel harga...</small>';
            return;
        }

        let html = '<div class="table-responsive"><table class="table table-bordered table-sm align-middle">';
        html += '<thead class="thead-light"><tr><th style="min-width:80px">Durasi</th>';
        ruangans.forEach(r => {
            html += `<th style="min-width:220px">${escapeHtml(r.nama)}</th>`;
        });
        html += '</tr></thead><tbody>';

        durasiList.forEach(durasi => {
            html += `<tr><td class="align-middle font-weight-bold">${escapeHtml(durasi)} jam</td>`;
            ruangans.forEach(r => {
                const existing = (matrixValues[durasi] && matrixValues[durasi][r.id]) || { harga: '', sku: '' };
                html += `
                    <td data-durasi="${durasi}" data-ruangan="${r.id}">
                        <input type="text" inputmode="numeric"
                            name="harga[${r.id}][${durasi}]"
                            class="form-control form-control-sm matrix-harga mb-1"
                            placeholder="Harga (Rp)" value="${escapeHtml(existing.harga)}">
                        <input type="text"
                            name="sku[${r.id}][${durasi}]"
                            class="form-control form-control-sm matrix-sku"
                            placeholder="SKU" maxlength="10" value="${escapeHtml(existing.sku)}">
                    </td>`;
            });
            html += '</tr>';
        });

        html += '</tbody></table></div>';
        container.innerHTML = html;
    }

    function refreshMatrix() {
        syncMatrixValuesFromDOM();
        renderMatrix();
    }

    // Ruangan dicentang/dilepas -> update kolom matriks
    document.getElementById('ruangan_container').addEventListener('change', function(e) {
        if (e.target.matches('input[type="checkbox"]')) refreshMatrix();
    });

    // Durasi ditambah/diketik -> update baris matriks
    document.getElementById('pricing_container').addEventListener('change', function(e) {
        if (e.target.matches('.durasi-input')) refreshMatrix();
    });

    // Batasi input harga cuma angka & titik
    document.getElementById('matrix_container').addEventListener('input', function(e) {
        if (e.target.matches('.matrix-harga')) {
            e.target.value = e.target.value.replace(/[^\d.]/g, '');
        }
    });

    // ============ KATEGORI -> LOAD RUANGAN ============
    document.getElementById('kategori_select').addEventListener('change', function() {
        const kategori = this.value;
        document.getElementById('kategori_dipilih_hidden').value = kategori;

        const container = document.getElementById('ruangan_container');
        container.innerHTML = '<small class="text-muted">Loading...</small>';

        if (!kategori) {
            container.innerHTML = '<small class="text-muted">Pilih kategori dulu...</small>';
            refreshMatrix();
            return;
        }

        fetch(`/admin/kategori/${kategori}/ruangan`)
            .then(res => res.text())
            .then(text => {
                const clean = text.replace(/^[^[{]*/, '');
                const data = JSON.parse(clean);
                container.innerHTML = '';
                if (data.length === 0) {
                    container.innerHTML = '<small class="text-muted">Tidak ada ruangan tersedia.</small>';
                    refreshMatrix();
                    return;
                }
                data.forEach(r => {
                    const checked = oldRuanganIds.includes(String(r.id_ruangan)) ? 'checked' : '';
                    container.innerHTML += `
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox"
                                name="ruangan_ids[]" value="${r.id_ruangan}" id="ruangan_${r.id_ruangan}" ${checked}>
                            <label class="form-check-label" for="ruangan_${r.id_ruangan}">
                                ${escapeHtml(r.nama_ruangan)}
                            </label>
                        </div>
                    `;
                });
                refreshMatrix();
            });
    });

    // ============ TAMBAH / HAPUS BARIS DURASI ============
    document.getElementById('btn_tambah_pricing').addEventListener('click', function() {
        const div = document.createElement('div');
        div.className = 'pricing-row d-flex align-items-center mb-2';
        div.innerHTML = `
            <input type="number" class="form-control durasi-input mr-2" name="durasi_jam[]" placeholder="Durasi (jam)" min="1" style="max-width: 200px;">
            <button type="button" class="btn btn-danger btn-hapus-pricing"><i class="fas fa-times"></i></button>
        `;
        document.getElementById('pricing_container').appendChild(div);
    });

    document.getElementById('pricing_container').addEventListener('click', function(e) {
        if (e.target.closest('.btn-hapus-pricing')) {
            const rows = document.querySelectorAll('.pricing-row');
            if (rows.length > 1) {
                e.target.closest('.pricing-row').remove();
                refreshMatrix();
            }
        }
    });

    // ============ REPOPULASI SAAT VALIDASI GAGAL ============
    // Kalau ada kategori tersimpan dari submit sebelumnya, trigger ulang
    // fetch ruangan-nya supaya checkbox & matrix ke-render dengan state lama.
    window.addEventListener('DOMContentLoaded', function () {
        if (subKategoriSelect.value) {
            toggleSubKategoriBaru();
        }
        if (document.getElementById('kategori_select').value) {
            document.getElementById('kategori_select').dispatchEvent(new Event('change'));
        }
    });
</script>
@stop