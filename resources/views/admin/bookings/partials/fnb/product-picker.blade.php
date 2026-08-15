<div class="fnb-panel-col">
    <h6 class="text-muted mb-3" style="font-size: 14px;">Pilih Menu</h6>

    <div class="fnb-category-tabs d-flex flex-wrap mb-3" style="gap: 8px;">
        <button type="button" class="fnb-tab-btn active" data-kategori="semua">Semua</button>
        @foreach($kategoriFnb as $kategori)
            <button type="button" class="fnb-tab-btn" data-kategori="{{ $kategori }}">{{ $kategori }}</button>
        @endforeach
    </div>

    <div class="fnb-product-grid" id="fnb-product-grid">
        @forelse($produks as $produk)
            <div class="fnb-product-card"
                data-kategori="{{ $produk->subKategori->sub_kategori_produk ?? 'Lainnya' }}"
                data-id="{{ $produk->id_produk }}"
                data-nama="{{ $produk->nama_produk }}"
                data-harga="{{ $produk->harga_jual }}"
                title="Klik untuk tambah ke keranjang">
                <div class="fnb-product-img">
                    @if($produk->foto)
                        <img src="{{ asset('uploads/fb/' . $produk->foto) }}" alt="{{ $produk->nama_produk }}">
                    @else
                        <div class="fnb-product-img-placeholder"><i class="fas fa-utensils"></i></div>
                    @endif
                </div>
                <div class="fnb-product-name">{{ $produk->nama_produk }}</div>
                <div class="fnb-product-harga">Rp {{ number_format($produk->harga_jual, 0, ',', '.') }}</div>
            </div>
        @empty
            <p class="text-muted small col-12">Belum ada produk F&amp;B yang aktif.</p>
        @endforelse
    </div>
</div>