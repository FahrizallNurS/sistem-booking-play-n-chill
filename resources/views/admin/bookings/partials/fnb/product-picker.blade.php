<style>
    /* CSS Khusus untuk Produk Habis (Tetap Aman) */
    .stok-habis {
        filter: grayscale(100%);
        opacity: 0.6;
        pointer-events: none;
        cursor: not-allowed;
    }
    .badge-habis {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: #dc3545;
        color: white;
        padding: 4px 10px;
        font-size: 0.8rem;
        font-weight: 900;
        border-radius: 4px;
        z-index: 10;
        box-shadow: 0 4px 6px rgba(0,0,0,0.3);
        letter-spacing: 1px;
    }

    /* JURUS PAKSA TINGGI KOTAK MENU */
    .fnb-product-grid {
        /* KITA PAKSA TINGGINYA JADI 650 PIXEL */
        height: 650px !important; 
        
        overflow-y: auto !important; 
        padding-bottom: 30px !important; 
        padding-right: 10px; 
    }

    /* Mempercantik tampilan Scrollbar */
    .fnb-product-grid::-webkit-scrollbar {
        width: 6px;
    }
    .fnb-product-grid::-webkit-scrollbar-thumb {
        background-color: rgba(0,0,0,0.2);
        border-radius: 10px;
    }
</style>

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
            {{-- Menggunakan $produk->stock --}}
            <div class="fnb-product-card {{ $produk->stock <= 0 ? 'stok-habis' : '' }}"
                data-kategori="{{ $produk->subKategori->sub_kategori_produk ?? 'Lainnya' }}"
                data-id="{{ $produk->id_produk }}"
                data-nama="{{ $produk->nama_produk }}"
                data-harga="{{ $produk->harga_jual }}"
                title="{{ $produk->stock <= 0 ? 'Stok Habis' : 'Klik untuk tambah ke keranjang' }}">
                
                <div class="fnb-product-img" style="position: relative;">
                    @if($produk->foto)
                        <img src="{{ asset('uploads/fb/' . $produk->foto) }}" alt="{{ $produk->nama_produk }}">
                    @else
                        <div class="fnb-product-img-placeholder"><i class="fas fa-utensils"></i></div>
                    @endif

                    {{-- Munculkan pita HABIS jika stock 0 --}}
                    @if($produk->stock <= 0)
                        <div class="badge-habis">HABIS</div>
                    @endif
                </div>
                
                <div class="fnb-product-name">{{ $produk->nama_produk }}</div>
                <div class="fnb-product-harga">Rp {{ number_format($produk->harga_jual, 0, ',', '.') }}</div>
                
                {{-- Indikator sisa stock --}}
                <div class="mt-1">
                    @if($produk->stock > 0)
                        <span class="text-success" style="font-size: 11px; font-weight: bold;">Sisa Stok: {{ $produk->stock }}</span>
                    @else
                        <span class="text-danger" style="font-size: 11px; font-weight: bold;">Sisa Stok: 0</span>
                    @endif
                </div>

            </div>
        @empty
            <p class="text-muted small col-12">Belum ada produk F&amp;B yang aktif.</p>
        @endforelse
    </div>
</div>