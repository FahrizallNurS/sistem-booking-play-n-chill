@extends('adminlte::page')

@section('title', 'Tambah Booking Manual')

@section('content_header')
    <h1>Tambah Booking Manual</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <form action="#" method="POST">
            @csrf

            <h5 class="mb-3">Informasi Pelanggan</h5>
            <div class="form-group">
                <label>Nama Pelanggan</label>
                <input type="text" name="nama_pelanggan" class="form-control" required
                    placeholder="Nama walk-in customer">
            </div>
            <div class="form-group">
                <label>No. HP</label>
                <input type="text" name="no_hp" class="form-control"
                    placeholder="08xxxxxxxxxx">
            </div>

            <hr>
            <h5 class="mb-3">Detail Booking</h5>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Kategori Ruangan</label>
                        <select id="kategori_select" class="form-control">
                            <option value="">-- Pilih Kategori --</option>
                            {{-- nanti diisi dari database --}}
                            <option value="1">Reguler</option>
                            <option value="2">VIP</option>
                            <option value="3">VVIP</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Ruangan</label>
                        <select name="ms_id_ruangan" id="ruangan_select" class="form-control" required>
                            <option value="">-- Pilih Kategori dulu --</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Paket</label>
                        <select name="ms_id_paket" id="paket_select" class="form-control" required>
                            <option value="">-- Pilih Ruangan dulu --</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Opsi Pembayaran</label>
                        <select name="opsi_pembayaran" class="form-control" required>
                            <option value="full">Full Payment</option>
                            <option value="dp">Down Payment (DP)</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Tanggal Booking</label>
                        <input type="date" name="tanggal_booking" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Waktu Mulai</label>
                        <input type="time" name="waktu_mulai" class="form-control" required>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Catatan Pembayaran</label>
                <textarea name="catatan_pembayaran" class="form-control" rows="2"
                    placeholder="Opsional"></textarea>
            </div>

            <a href="{{ route('admin.booking.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan Booking</button>
        </form>
    </div>
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
            .then(res => res.json())
            .then(data => {
                ruanganSelect.innerHTML = '<option value="">-- Pilih Ruangan --</option>';
                data.forEach(r => {
                    ruanganSelect.innerHTML += `<option value="${r.id_ruangan}">${r.nama_ruangan}</option>`;
                });
            });
    });
</script>
@stop