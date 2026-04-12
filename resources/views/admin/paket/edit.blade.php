@extends('adminlte::page')

@section('title', 'Edit Paket')

@section('content_header')
    <h1>Edit Paket</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">

        {{-- FORM 1: Edit Paket --}}
        <form action="{{ route('admin.paket.update', $paket->id_paket) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Info Paket --}}
            <div class="form-group">
                <label>Nama Paket</label>
                <input type="text" name="nama_paket" class="form-control" required maxlength="50"
                    value="{{ $paket->nama_paket }}">
            </div>

            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="deskripsi_paket" class="form-control" rows="3">{{ $paket->deskripsi_paket }}</textarea>
            </div>

            <div class="form-group">
                <label>Maksimal Orang</label>
                <input type="number" name="maksimal_orang" class="form-control" min="1"
                    value="{{ $paket->maksimal_orang }}">
            </div>

            <div class="form-group">
                <label>Status</label>
                <select name="is_active" class="form-control">
                    <option value="1" {{ $paket->is_active ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ !$paket->is_active ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            <hr>

            {{-- FASILITAS --}}
            <h5>Fasilitas Paket</h5>
            <div id="fasilitas_container">
                @forelse($paket->fasilitas as $f)
                    <div class="fasilitas-row d-flex align-items-center mb-2">
                        <input type="text" name="fasilitas[]" class="form-control mr-2"
                            value="{{ $f->nama_fasilitas }}">
                        <button type="button" class="btn btn-danger btn-hapus-fasilitas">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @empty
                    <div class="fasilitas-row d-flex align-items-center mb-2">
                        <input type="text" name="fasilitas[]" class="form-control mr-2"
                            placeholder="contoh: AC, Snack, PS5">
                        <button type="button" class="btn btn-danger btn-hapus-fasilitas">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endforelse
            </div>

            <button type="button" id="btn_tambah_fasilitas" class="btn btn-secondary btn-sm mb-3">
                <i class="fas fa-plus"></i> Tambah Fasilitas
            </button>

            <hr>

            {{-- PRICING BARU --}}
            <h5>Tambah Pricing Baru</h5>

            <div class="form-group">
                <label>Kategori Ruangan</label>
                <select id="kategori_select" class="form-control">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($kategoris as $kategori)
                        <option value="{{ $kategori->id_kategori }}">{{ $kategori->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Pilih Ruangan</label>
                <div id="ruangan_container" class="border rounded p-2" style="min-height:50px">
                    <small class="text-muted">Pilih kategori dulu...</small>
                </div>
            </div>

            <div class="form-group">
                <label>Tipe Hari</label>
                <select name="tipe_hari" id="tipe_hari" class="form-control">
                    <option value="">-- Pilih Hari --</option>
                    <option value="weekday">Weekday</option>
                    <option value="weekend">Weekend</option>
                    <option value="holiday">Holiday</option>
                </select>
                <small id="label_hari" class="text-muted"></small>
            </div>

            <div id="pricing_container">
                <div class="pricing-row d-flex align-items-center mb-2">
                    <input type="number" name="durasi_menit[]" class="form-control mr-2"
                        placeholder="Durasi (menit)" min="30">
                    <input type="number" name="harga[]" class="form-control mr-2"
                        placeholder="Harga" min="0">
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
        {{-- END FORM 1 --}}

        <hr>

        {{-- TABEL PRICING --}}
        <h5>Pricing yang Sudah Ada</h5>

        @if($paket->pricings->count() > 0)
            <table class="table table-bordered table-sm mb-3">
                <thead>
                    <tr>
                        <th>Ruangan</th>
                        <th>Tipe Hari</th>
                        <th>Durasi</th>
                        <th>Harga</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($paket->pricings as $pricing)
                        <tr>
                            <td>{{ $pricing->ruangan->nama_ruangan ?? '-' }}</td>
                            <td>{{ $pricing->tipe_pricing }}</td>
                            <td>{{ $pricing->durasi_menit }}</td>
                            <td>Rp {{ number_format($pricing->harga, 0, ',', '.') }}</td>
                            <td>
                                <form action="{{ route('admin.layanan.pricing.destroy', $pricing->id_pricing) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Hapus pricing ini?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-muted">Belum ada pricing.</p>
        @endif

    </div>
</div>
@stop

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // AJAX ruangan
    const kategori = document.getElementById('kategori_select');
    if (kategori) {
        kategori.addEventListener('change', function () {
            const id = this.value;
            const container = document.getElementById('ruangan_container');

            if (!id) {
                container.innerHTML = '<small>Pilih kategori dulu...</small>';
                return;
            }

            container.innerHTML = 'Loading...';

            fetch(`/admin/kategori/${id}/ruangan`)
                .then(res => res.json())
                .then(data => {
                    container.innerHTML = '';
                    if (!data.length) {
                        container.innerHTML = 'Tidak ada ruangan';
                        return;
                    }

                    data.forEach(r => {
                        container.innerHTML += `
                            <div class="form-check">
                                <input type="checkbox" name="ms_ruangan_ids[]" value="${r.id_ruangan}">
                                ${r.nama_ruangan}
                            </div>
                        `;
                    });
                });
        });
    }

    // fasilitas
    document.getElementById('btn_tambah_fasilitas').onclick = () => {
        document.getElementById('fasilitas_container').insertAdjacentHTML('beforeend', `
            <div class="fasilitas-row d-flex mb-2">
                <input type="text" name="fasilitas[]" class="form-control mr-2">
                <button type="button" class="btn btn-danger btn-hapus-fasilitas">X</button>
            </div>
        `);
    };

    document.addEventListener('click', function(e){
        if (e.target.classList.contains('btn-hapus-fasilitas')) {
            e.target.closest('.fasilitas-row').remove();
        }
        if (e.target.classList.contains('btn-hapus-pricing')) {
            e.target.closest('.pricing-row').remove();
        }
    });

    // pricing
    document.getElementById('btn_tambah_pricing').onclick = () => {
        document.getElementById('pricing_container').insertAdjacentHTML('beforeend', `
            <div class="pricing-row d-flex mb-2">
                <input type="number" name="durasi_menit[]" class="form-control mr-2">
                <input type="number" name="harga[]" class="form-control mr-2">
                <button type="button" class="btn btn-danger btn-hapus-pricing">X</button>
            </div>
        `);
    };

});
</script>
@stop