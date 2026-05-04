@extends('adminlte::page')

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
                    <option value="1">Aktif</option>
                    <option value="0">Nonaktif</option>
                </select>
            </div>

            <hr>
            <h5>Penetapan Harga <small class="text-muted">(opsional, bisa diatur nanti)</small></h5>

            <div class="form-group">
                <label>Kategori Ruangan</label>
                <select id="kategori_select" class="form-control">
                    <option value="">-- Pilih Kategori --</option>
                    <option value="REGULAR">Regular</option>
                    <option value="VIP">VIP</option>
                    <option value="VVIP">VVIP</option>
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
            <a href="{{ route('admin.paket.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>
@stop

@section('js')
<script>
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
                    container.innerHTML = '<small class="text-muted">Tidak ada ruangan tersedia.</small>';
                    return;
                }
                data.forEach(r => {
                    container.innerHTML += `
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox"
                                name="ruangan_ids[]" value="${r.id_ruangan}" id="ruangan_${r.id_ruangan}">
                            <label class="form-check-label" for="ruangan_${r.id_ruangan}">
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