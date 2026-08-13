@extends('adminlte::page')
@include('partials.sidebar-admin')

@section('title', 'Restok Produk F&B')

@section('content_header')
    <h1>Restok Produk F&B</h1>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm" style="border-radius: 10px;">
        <div class="card-header bg-success text-white" style="border-top-left-radius: 10px; border-top-right-radius: 10px;">
            <h3 class="card-title"><i class="fas fa-box-open mr-2"></i> Daftar Stok Produk</h3>
            <div class="card-tools">
                <a href="{{ route('admin.fb.produk.index') }}" class="btn btn-sm btn-light text-success font-weight-bold">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
        
        <div class="card-body p-0">
            {{-- Area Filter Kategori Dinamis --}}
            <div class="p-3 border-bottom bg-light d-flex flex-wrap" style="gap: 8px;">
                <button class="btn btn-sm btn-dark filter-stok-btn active" data-kategori="Semua">Semua</button>
                @foreach($kategoriList as $kategori)
                    <button class="btn btn-sm btn-outline-dark filter-stok-btn" data-kategori="{{ $kategori }}">{{ $kategori }}</button>
                @endforeach
            </div>

            {{-- Area List Produk --}}
            <ul class="list-group list-group-flush" id="listStokProduk">
                @forelse($produks as $produk)
                    <li class="list-group-item d-flex align-items-center stok-item-row" data-kategori="{{ $produk->subKategori->kategori_produk ?? 'Lainnya' }}">
                        
                        {{-- Foto Produk --}}
                        <div class="mr-3 flex-shrink-0">
                            @if($produk->foto)
                                <img src="{{ asset('uploads/fb/' . $produk->foto) }}" alt="{{ $produk->nama_produk }}" class="rounded shadow-sm" style="width: 60px; height: 60px; object-fit: cover;">
                            @else
                                <div class="rounded shadow-sm d-flex align-items-center justify-content-center bg-secondary text-white" style="width: 60px; height: 60px;">
                                    <i class="fas fa-image"></i>
                                </div>
                            @endif
                        </div>

                        {{-- Info Produk --}}
                        <div class="flex-grow-1">
                            <h6 class="mb-1 font-weight-bold text-dark">{{ $produk->nama_produk }}</h6>
                            <div class="d-flex align-items-center" style="gap: 10px; font-size: 0.85rem;">
                                <span class="badge badge-info">{{ $produk->subKategori->kategori_produk ?? 'Lainnya' }}</span>
                                <span class="text-muted">Stok Saat Ini: <strong class="{{ $produk->stock == 0 ? 'text-danger' : 'text-success' }}">{{ $produk->stock }}</strong></span>
                            </div>
                        </div>

                        {{-- Form Input Tambah Stok --}}
                        <div class="ml-3">
                            <form action="{{ route('admin.fb.produk.simpan-stok', $produk->id_produk) }}" method="POST" class="d-flex align-items-center">
                                @csrf
                                <input type="number" name="tambahan_stok" class="form-control mr-2 text-center font-weight-bold" style="width: 90px; border: 2px solid #28a745;" placeholder="+ Stok" min="1" required>
                                <button type="submit" class="btn btn-success shadow-sm" title="Simpan Stok">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </form>
                        </div>
                        
                    </li>
                @empty
                    <div class="p-5 text-center text-muted">
                        <i class="fas fa-box-open mb-3" style="font-size: 3rem;"></i>
                        <h5>Belum ada produk yang didaftarkan.</h5>
                    </div>
                @endforelse
            </ul>
        </div>
    </div>
@stop

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const filterBtns = document.querySelectorAll('.filter-stok-btn');
        const stokRows = document.querySelectorAll('.stok-item-row');

        filterBtns.forEach(btn => {
            btn.addEventListener('click', function () {
                // Hapus warna aktif dari semua tombol
                filterBtns.forEach(b => {
                    b.classList.remove('btn-dark');
                    b.classList.add('btn-outline-dark');
                });
                // Beri warna aktif ke tombol yang diklik
                this.classList.remove('btn-outline-dark');
                this.classList.add('btn-dark');

                const filterValue = this.getAttribute('data-kategori');

                // Sembunyikan atau tampilkan baris sesuai kategori
                stokRows.forEach(row => {
                    if (filterValue === 'Semua' || row.getAttribute('data-kategori') === filterValue) {
                        row.style.setProperty('display', 'flex', 'important');
                    } else {
                        row.style.setProperty('display', 'none', 'important');
                    }
                });
            });
        });
    });
</script>
@stop