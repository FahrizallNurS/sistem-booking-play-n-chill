@extends('adminlte::page')

@section('title', 'Tambah Paket')

@section('content_header')
    <h1>Tambah Paket</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.paket.store') }}" method="POST">
            @csrf

            {{-- Info Paket --}}
            <div class="form-group">
                <label>Nama Paket</label>
                <input type="text" name="nama_paket" class="form-control" required maxlength="50"
                    placeholder="contoh: Gaming Private Room">
            </div>
            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="deskripsi_paket" class="form-control" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label>Maksimal Orang</label>
                <input type="number" name="maksimal_orang" class="form-control" min="1"
                    placeholder="Kosongkan jika tidak dibatasi">
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="is_active" class="form-control">
                    <option value="1">Aktif</option>
                    <option value="0">Nonaktif</option>
                </select>
            </div>

            <hr>
            <h5>Assign ke Ruangan</h5>

            {{-- Kategori --}}
            <div class="form-group">
                <label>Kategori Ruangan</label>
                <select id="kategori_select" class="form-control">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($kategoris as $kategori)
                        <option value="{{ $kategori->id_kategori }}">{{ $kategori->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Ruangan (diisi via AJAX) --}}
            <div class="form-group">
                <label>Nama Ruangan</label>
                <select name="ms_ruangan_id_ruangan" id="ruangan_select" class="form-control" required>
                    <option value="">-- Pilih Kategori dulu --</option>
                </select>
            </div>

            {{-- Tipe Hari --}}
            <div class="form-group">
                <label>Tipe Hari</label>
                <select name="tipe_hari" id="tipe_hari" class="form-control" required>
                    <option value="">-- Pilih Hari --</option>
                    <option value="weekday">Weekday (Senin - Jumat)</option>
                    <option value="weekend">Weekend (Sabtu - Minggu)</option>
                    <option value="holiday">Holiday</option>
                </select>
                <small id="label_hari" class="text-muted"></small>
            </div>

            <hr>
            <h5>Fasilitas Paket</h5>

            <div id="fasilitas_container">
                <div class="fasilitas-row d-flex align-items-center mb-2">
                    <input type="text" name="fasilitas[]" class="form-control mr-2"
                        placeholder="contoh: AC, Snack, PS5">

                    <button type="button" class="btn btn-danger btn-hapus-fasilitas">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <button type="button" id="btn_tambah_fasilitas" class="btn btn-secondary btn-sm mb-3">
                <i class="fas fa-plus"></i> Tambah Fasilitas
            </button>

            <hr>
            <h5>Pricing (Durasi & Harga)</h5>

            <div id="pricing_container">
                <div class="pricing-row d-flex align-items-center mb-2">
                    <input type="number" name="durasi_menit[]" class="form-control mr-2"
                        placeholder="Durasi (menit)" min="30" required>

                    <input type="number" name="harga[]" class="form-control mr-2"
                        placeholder="Harga" min="0" required>

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
                <a href="{{ route('admin.paket.index') }}" class="btn btn-secondary mr-2">
                    Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    Simpan
                </button>
            </div>

            </div>
            </div>


        </form>


</div>
@stop

@section('js')
<script>
    // AJAX load ruangan by kategori
    document.getElementById('kategori_select').addEventListener('change', function() {
        const idKategori = this.value;
        const ruanganSelect = document.getElementById('ruangan_select');

        ruanganSelect.innerHTML = '<option value="">Loading...</option>';

        if (!idKategori) {
            ruanganSelect.innerHTML = '<option value="">-- Pilih Kategori dulu --</option>';
            return;
        }

        fetch(`/admin/kategori/${idKategori}/ruangan`)
        .then(res => res.text())
        .then(text => {
            const clean = text.replace(/^[^[{]*/, ''); // hapus karakter aneh di depan
            const data = JSON.parse(clean);
            ruanganSelect.innerHTML = '<option value="">-- Pilih Ruangan --</option>';
            data.forEach(r => {
                ruanganSelect.innerHTML += `<option value="${r.id_ruangan}">${r.nama_ruangan}</option>`;
            });
        });
    });

    // Label hari otomatis
    document.getElementById('tipe_hari').addEventListener('change', function() {
        const label = document.getElementById('label_hari');
        if (this.value === 'weekday') label.textContent = 'Berlaku Senin - Jumat';
        else if (this.value === 'weekend') label.textContent = 'Berlaku Sabtu - Minggu';
        else if (this.value === 'holiday') label.textContent = 'Berlaku Hari Libur Nasional';
        else label.textContent = '';
    });

    const fasilitasTemplate = `
    <input type="text" name="fasilitas[]" class="form-control mr-2"
        placeholder="contoh: AC, Snack, PS5">

    <button type="button" class="btn btn-danger btn-hapus-fasilitas">
        <i class="fas fa-times"></i>
    </button>
    `;

    document.getElementById('btn_tambah_fasilitas').addEventListener('click', function() {
        const div = document.createElement('div');
        div.className = 'fasilitas-row d-flex mb-2';
        div.innerHTML = fasilitasTemplate;
        document.getElementById('fasilitas_container').appendChild(div);
    });

    // Hapus fasilitas
    document.getElementById('fasilitas_container').addEventListener('click', function(e) {
        if (e.target.closest('.btn-hapus-fasilitas')) {
            const rows = document.querySelectorAll('.fasilitas-row');
            if (rows.length > 1) {
                e.target.closest('.fasilitas-row').remove();
            }
        }
    });

    const pricingTemplate = `
    <input type="number" name="durasi_menit[]" class="form-control mr-2"
        placeholder="Durasi (menit)" min="30" required>

    <input type="number" name="harga[]" class="form-control mr-2"
        placeholder="Harga" min="0" required>

    <button type="button" class="btn btn-danger btn-hapus-pricing">
        <i class="fas fa-times"></i>
    </button>
    `;

    // tambah pricing
    document.getElementById('btn_tambah_pricing').addEventListener('click', function() {
        const div = document.createElement('div');
        div.className = 'pricing-row d-flex mb-2';
        div.innerHTML = pricingTemplate;
        document.getElementById('pricing_container').appendChild(div);
    });

    // hapus pricing
    document.getElementById('pricing_container').addEventListener('click', function(e) {
        if (e.target.closest('.btn-hapus-pricing')) {
            const rows = document.querySelectorAll('.pricing-row');
            if (rows.length > 1) {
                e.target.closest('.pricing-row').remove();
            }
        }
    });

    
    
</script>


@stop