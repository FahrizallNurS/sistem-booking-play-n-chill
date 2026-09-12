<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/logo_dumb.png') }}">
    <title>Penawaran F&B - Play N Chill</title> 
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Modak&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <style>

       body {
        background-color: var(--purple-dark);
        position: relative;
        min-height: 100vh;
        margin: 0;
        padding-top: 85px; 
        overflow-x: clip; 
        width: 100%;
    }

    body::before {
        content: "";
        position: fixed; 
        top: 0; 
        left: 0; 
        right: 0; 
        bottom: 0; 
        background-image: url('{{ asset("images/bg-segitiga.png") }}');
        background-repeat: no-repeat; 
        background-size: cover;
        background-position: center; 
        opacity: 0.8; 
        z-index: -1;
    }

        .hero-title {
            font-size: 3.5rem;
            letter-spacing: 1px;
            line-height: 1.2;
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 2rem; 
                line-height: 1.1; 
            }
        }

        @media (max-width: 480px) {
            .hero-title {
                font-size: 1.65rem; 
                line-height: 1.1; 
            }
        }

        .fb-card {
            background-color: #2b0054;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
            transition: transform 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
            border: 1px solid rgba(255,255,255,0.05);
        }

        .fb-card:hover {
            transform: translateY(-8px);
        }

        .fb-img-wrapper {
            position: relative;
            aspect-ratio: 4 / 3;
            overflow: hidden;
        }

        .fb-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            transition: transform 0.5s ease;
        }

        .fb-card:hover .fb-img {
            transform: scale(1.05);
        }

        .fb-price {
            position: absolute;
            top: 12px;
            right: 12px;
            background-color: #ffd700;
            color: #2b0054;
            font-weight: 800;
            font-size: 0.8rem;
            padding: 4px 12px;
            border-radius: 20px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        .fb-body {
            padding: 20px;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }

        .fb-badge {
            background-color: rgba(255, 215, 0, 0.1);
            color: #ffd700;
            font-size: 0.65rem;
            padding: 4px 10px;
            border-radius: 4px;
            display: inline-block;
            margin-bottom: 12px;
            font-weight: 700;
            letter-spacing: 1px;
            width: fit-content;
        }

        .fb-title {
            color: white;
            font-weight: bold;
            font-size: 1.15rem;
            margin-bottom: 8px;
        }

        .fb-desc {
            color: #bca0e5;
            font-size: 0.85rem;
            line-height: 1.5;
            margin-bottom: 20px;
            flex-grow: 1;
        }

        .fb-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: auto;
        }

        .fb-status {
            color: #8a73ba;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .fb-add-btn {
            background-color: #ffd700;
            color: #2b0054;
            border: none;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 1.2rem;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.2s;
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }

        .fb-add-btn:hover {
            transform: scale(1.1);
            background-color: #ffea00;
        }

        .filter-btn {
            background-color: #3a2377; 
            color: #d8b8ff;
            border: 1px solid rgba(255, 255, 255, 0.15); 
            border-radius: 50px;
            padding: 8px 24px; 
            font-weight: 600; 
            font-size: 0.9rem; 
            transition: all 0.3s ease;
        }

        .filter-btn:hover, .filter-btn.active { 
            background-color: #ffd700; 
            color: #2b0054; 
            border-color: #ffd700; 
        }

        .floating-cart-pill {
            position: fixed; 
            bottom: 30px; 
            right: 30px; 
            z-index: 1040;
            background-color: #40288c; 
            border: 1px solid rgba(255, 255, 255, 0.15); 
            border-radius: 50px; 
            padding: 12px 24px 12px 18px; 
            display: flex; 
            align-items: center; 
            gap: 14px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.3); 
            cursor: pointer;
            transition: transform 0.3s ease, background-color 0.3s ease;
            font-family: 'Nunito', sans-serif;
        }

        .floating-cart-pill:hover { 
            transform: translateY(-5px) scale(1.03); 
            background-color: #4a2e9e; 
        }

        .cart-icon-wrapper { 
            position: relative; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
        }

        .cart-icon-wrapper svg { 
            width: 32px; 
            height: 32px; 
            color: #ffd700; 
        }

        .cart-badge-new {
            position: absolute; 
            top: -4px; 
            right: -8px; 
            background-color: #ffd700; 
            color: #2b0054;
            font-weight: 800; 
            font-size: 0.75rem; 
            width: 22px; 
            height: 22px; 
            border-radius: 50%;
            display: flex; 
            justify-content: center; 
            align-items: center; 
            border: 2px solid #40288c;
        }

        .cart-text-wrapper { 
            display: flex; 
            flex-direction: column; 
            justify-content: center; 
        }

        .cart-item-count { 
            color: #ffffff; 
            font-weight: 700; 
            font-size: 0.95rem; 
            line-height: 1.2; 
        }

        .cart-total-price { 
            color: #ffd700; 
            font-weight: 800; 
            font-size: 1rem; 
            line-height: 1.2; 
        }

        .custom-bottom-sheet {
            background-color: #2b1b54 !important; 
            border-top-left-radius: 24px; 
            border-top-right-radius: 24px;
            border-top: 1px solid rgba(255, 255, 255, 0.1); 
            height: auto !important; 
            max-height: 60vh; 
            font-family: 'Nunito', sans-serif;
        }

        .custom-bottom-sheet .offcanvas-header { 
            padding: 24px 24px 16px 24px; 
        }

        .custom-bottom-sheet .offcanvas-body { 
            padding: 0 24px 24px 24px; 
        }

        .btn-close-custom { 
            filter: invert(1) grayscale(100%) brightness(200%); 
            opacity: 0.8; 
        }

        .btn-qty-control {
            background-color: rgba(255, 255, 255, 0.1); 
            color: #ffffff; 
            border: none; 
            width: 28px; 
            height: 28px;
            border-radius: 6px; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            font-weight: bold; 
            transition: 0.2s;
        }

        .btn-qty-control:hover { 
            background-color: rgba(255, 255, 255, 0.2); 
        }

        .btn-checkout-orange {
            background-color: #ff7a00; 
            color: #ffffff; 
            font-weight: 800; 
            border-radius: 50px;
            padding: 14px; 
            border: none; 
            font-size: 1.05rem; 
            transition: transform 0.2s;
        }

        .btn-checkout-orange:hover { 
            background-color: #e06b00; 
            transform: scale(1.02); 
            color: #ffffff; 
        }

        .divider-custom { 
            border-color: rgba(255, 255, 255, 0.1); 
            margin: 20px 0; 
        }

        @media (max-width: 576px) {
            .floating-cart-pill { 
                bottom: 25px; 
                right: 20px; 
                padding: 10px 20px 10px 14px; 
                gap: 12px; 
            }
            .cart-icon-wrapper svg { 
                width: 28px; 
                height: 28px; 
            }
            .cart-item-count { 
                font-size: 0.85rem; 
            }
            .cart-total-price { 
                font-size: 0.9rem; 
            }
            .cart-badge-new { 
                width: 20px; 
                height: 20px; 
                font-size: 0.7rem; 
            }
        }

        .floating-skip-pill {
            position: fixed; 
            bottom: 30px; 
            right: 30px; 
            z-index: 1040;
            background-color: #40288c; 
            color: #ffffff; 
            border: 1px solid rgba(255, 255, 255, 0.15); 
            border-radius: 50px; 
            padding: 12px 24px; 
            font-weight: 700;
            font-size: 1rem;
            box-shadow: 0 10px 25px rgba(0,0,0,0.3); 
            cursor: pointer;
            transition: transform 0.3s ease, background-color 0.3s ease;
            font-family: 'Nunito', sans-serif;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .floating-skip-pill svg {
            color: #ffd700;
            transition: transform 0.3s ease;
        }
        
        .floating-skip-pill:hover { 
            transform: translateY(-5px) scale(1.03); 
            background-color: #4a2e9e; 
            color: #ffffff;
        }
        
        .floating-skip-pill:hover svg {
            transform: translateX(4px); 
        }

        .d-none-custom { 
            display: none !important; 
        }

        .btn-delete-item {
            color: #ff6b6b;
            background: none;
            border: none;
            padding: 0;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 4px;
            cursor: pointer;
            transition: color 0.2s;
            margin-top: 8px;
        }
        
        .btn-delete-item:hover {
            color: #ff4c4c;
        }

        .navbar.fixed-top {
            position: fixed !important; 
            top: 0 !important; 
            width: 100% !important;
            z-index: 1030 !important; 
            background: rgb(255, 255, 255);
            backdrop-filter: blur(10px); 
        }

    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg fixed-top">
    <div class="container-fluid px-4">
        <a class="navbar-brand p-0" href="{{ url('/') }}">
            <img src="{{ asset('images/logo_dumb.png') }}" alt="Play N Chill" height="48">
        </a>
        
        {{-- INI DIA YANG HILANG: Tombol Hamburger Menu untuk tampilan HP --}}
        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse justify-content-end" id="navMain">
            <ul class="navbar-nav align-items-center gap-1">
                <li class="nav-item"><a class="nav-link" href="{{ url('/') }}">Home</a></li>
                <li class="nav-item"><a class="nav-link nav-btn-active" href="{{ url('/booking') }}">Booking</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('/menu-fb') }}">Menu F&B</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('/galeri') }}">Galeri</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('/tentang-kami') }}">Tentang Kami</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-2 mt-md-5 mb-5 pb-5">
    
    <div class="text-center" data-aos="fade-down">
        <h1 class="font-modak mb-3 hero-title" style="color: #ffd700;">
            Menu<br class="d-block d-sm-none"> Favoritmu
        </h1>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger mx-auto mt-3" style="border-radius: 10px; font-size: 0.9rem; max-width: 600px;">
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="mb-4 mt-5" data-aos="fade-up">
        <h3 class="text-white fw-bold mb-1" style="font-family: 'Nunito', sans-serif; font-size: 1.15rem;">
            Rekomendasi Food & Beverage
        </h3>
        <div style="width: 60px; height: 3px; background-color: #ffd700; border-radius: 2px;"></div>
    </div>

    <div class="row g-4 mb-5">
        @foreach($produks->take(2) as $item)
        <div class="col-12 col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
            <div class="fb-card">
                <div class="fb-img-wrapper">
                    @if($item->foto)
                        <img src="{{ asset('uploads/fb/' . $item->foto) }}" alt="{{ $item->nama_produk }}" class="fb-img" onerror="this.src='{{ asset('images/gaming.jpg') }}'">
                    @else
                        <div class="fb-img d-flex align-items-center justify-content-center" style="background-color: #f3f4f6;">
                            <i class="fas fa-utensils text-muted" style="font-size: 2rem;"></i>
                        </div>
                    @endif
                    <span class="fb-price">Rp {{ number_format($item->harga_jual, 0, ',', '.') }}</span>
                </div>
                <div class="fb-body">
                    <span class="fb-badge">BEST SELLER</span>
                    <h5 class="fb-title">{{ $item->nama_produk }}</h5>
                    <div class="fb-footer">
                        <span class="fb-status">{{ $item->stock > 0 ? 'In Stock' : 'Out Stock' }}</span>
                        @if($item->stock > 0)
                            <button class="fb-add-btn add-to-cart-btn" 
                                data-id="{{ $item->id_produk }}" 
                                data-name="{{ $item->nama_produk }}" 
                                data-price="{{ $item->harga_jual }}" 
                                data-img="{{ $item->foto ? asset('uploads/fb/' . $item->foto) : asset('images/gaming.jpg') }}">+</button>
                        @else
                            <button class="fb-add-btn bg-secondary text-white" disabled style="cursor: not-allowed;">-</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mb-4 mt-5" data-aos="fade-up">
        <h3 class="text-white fw-bold mb-1" style="font-family: 'Nunito', sans-serif; font-size: 1.15rem;">
            Menu Lainnya
        </h3>
        <div style="width: 60px; height: 3px; background-color: #ffd700; border-radius: 2px;"></div>
    </div>

    <div class="d-flex justify-content-center flex-wrap mb-5 gap-2 gap-md-3" data-aos="fade-up" data-aos-delay="100">
        <button class="filter-btn active" data-filter="semua">Semua</button>
        @php
            $kategoriUnik = $produks->pluck('subKategori.kategori_produk')->filter()->unique();
        @endphp
        
        @foreach($kategoriUnik as $kat)
            <button class="filter-btn" data-filter="{{ $kat }}">{{ $kat }}</button>
        @endforeach
    </div>

    <div class="row g-4" id="dynamic-product-list">
        @forelse($produks->skip(2) as $produk)
            <div class="col-12 col-md-6 col-lg-3 product-item" data-kategori="{{ $produk->subKategori->kategori_produk ?? 'Lainnya' }}" data-aos="fade-up" data-aos-delay="100">
                <div class="fb-card">
                    <div class="fb-img-wrapper">
                        @if($produk->foto)
                            <img src="{{ asset('uploads/fb/' . $produk->foto) }}" alt="{{ $produk->nama_produk }}" class="fb-img" onerror="this.src='{{ asset('images/gaming.jpg') }}'">
                        @else
                            <div class="fb-img d-flex align-items-center justify-content-center" style="background-color: #f3f4f6;">
                                <i class="fas fa-utensils text-muted" style="font-size: 2rem;"></i>
                            </div>
                        @endif
                        <span class="fb-price">Rp {{ number_format($produk->harga_jual, 0, ',', '.') }}</span>
                    </div>
                    <div class="fb-body">
                        <span class="fb-badge">{{ strtoupper($produk->subKategori->kategori_produk ?? 'LAINNYA') }}</span>
                        <h5 class="fb-title">{{ $produk->nama_produk }}</h5>
                        <div class="fb-footer">
                            <span class="fb-status">{{ $produk->stock > 0 ? 'In Stock' : 'Out Stock' }}</span>
                            @if($produk->stock > 0)
                                <button class="fb-add-btn add-to-cart-btn" 
                                    data-id="{{ $produk->id_produk }}" 
                                    data-name="{{ $produk->nama_produk }}" 
                                    data-price="{{ $produk->harga_jual }}" 
                                    data-img="{{ $produk->foto ? asset('uploads/fb/' . $produk->foto) : asset('images/gaming.jpg') }}">+</button>
                            @else
                                <button class="fb-add-btn bg-secondary text-white" disabled style="cursor: not-allowed;">-</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center text-white py-5">
                <p>Mohon maaf, menu F&B sedang kosong saat ini.</p>
            </div>
        @endforelse
    </div>

</div>

<button type="button" id="floatingSkipBtn" class="floating-skip-pill" onclick="document.getElementById('skipForm').submit();" data-aos="zoom-in" data-aos-delay="400">
    Nanti Saja
    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"/>
    </svg>
</button>

<div id="floatingCartPill" class="floating-cart-pill d-none-custom" data-bs-toggle="offcanvas" data-bs-target="#cartBottomSheet" aria-controls="cartBottomSheet">
    <div class="cart-icon-wrapper">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
        </svg>
        <span class="cart-badge-new" id="floatingCartBadge">0</span>
    </div>
    <div class="cart-text-wrapper">
        <span class="cart-item-count" id="floatingCartCount">0 Items</span>
        <span class="cart-total-price" id="floatingCartTotal">Rp 0</span>
    </div>
</div>

<div class="offcanvas offcanvas-bottom custom-bottom-sheet shadow-lg" tabindex="-1" id="cartBottomSheet" aria-labelledby="cartBottomSheetLabel">
    
    <div class="offcanvas-header">
        <h5 class="offcanvas-title text-white fw-bold" id="cartBottomSheetLabel" style="font-size: 1.25rem;">Ringkasan Pesanan F&B</h5>
        <button type="button" class="btn-close btn-close-custom" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    
   <div class="offcanvas-body d-flex flex-column">
        
        <div id="cartItemsArea" class="flex-grow-1 overflow-auto" style="scrollbar-width: none;">
            <div class="text-center text-muted mt-4" id="emptyCartMessage">
                <p style="font-size: 0.9rem; color: #bca0e5 !important;">Belum ada menu yang ditambahkan</p>
            </div>
        </div>

        <div class="mt-auto pt-2" id="cartTotalsArea" style="display: none;">
            <hr class="divider-custom">
            <div class="d-flex justify-content-between mb-2">
                <span style="color: #bca0e5; font-size: 0.95rem;">Total Item</span>
                <span style="color: #ffffff; font-size: 0.95rem; font-weight: 700;" id="totalItemValue">0</span>
            </div>
            <div class="d-flex justify-content-between mb-4">
                <span class="text-white fw-bold" style="font-size: 1.15rem;">Subtotal F&B</span>
                <span class="text-white fw-bold" style="font-size: 1.15rem;" id="totalValue">Rp 0</span>
            </div>
            
            <form action="{{ route('booking.store') }}" method="POST">
                @csrf
                <input type="hidden" name="id_penetapan_harga" value="{{ request('id_penetapan_harga') }}">
                <input type="hidden" name="tanggal" value="{{ request('tanggal') }}">
                <input type="hidden" name="waktu_mulai" value="{{ request('waktu_mulai') }}">
                <input type="hidden" name="opsi_pembayaran" value="{{ request('opsi_pembayaran') }}">
                <input type="hidden" name="jumlah_dp" value="{{ request('jumlah_dp') }}">
                <input type="hidden" name="no_hp" value="{{ request('no_hp') }}">
                <input type="hidden" name="keranjang_fb" id="cartDataInput" value="[]">
                
                <button type="submit" class="btn w-100 btn-checkout-orange">
                    Lanjut Pembayaran
                </button>
            </form>
        </div>

        <div class="mt-auto pt-2" id="emptyCartAction" style="display: block;">
            <form id="skipForm" action="{{ route('booking.store') }}" method="POST">
                @csrf
                <input type="hidden" name="id_penetapan_harga" value="{{ request('id_penetapan_harga') }}">
                <input type="hidden" name="tanggal" value="{{ request('tanggal') }}">
                <input type="hidden" name="waktu_mulai" value="{{ request('waktu_mulai') }}">
                <input type="hidden" name="opsi_pembayaran" value="{{ request('opsi_pembayaran') }}">
                <input type="hidden" name="jumlah_dp" value="{{ request('jumlah_dp') }}">
                <input type="hidden" name="no_hp" value="{{ request('no_hp') }}">
                <input type="hidden" name="keranjang_fb" value="[]">
                
                <button type="submit" class="btn w-100 btn-checkout-orange">
                    Nanti Saja & Lanjut Pembayaran
                </button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<script>
    AOS.init({ duration: 800, once: true });

    // Cart di-persist ke sessionStorage supaya tidak hilang kalau ada
    // redirect back() akibat bentrok jadwal di step akhir (booking.store()).
    const CART_STORAGE_KEY = 'cart_fb_booking';
    let cart = JSON.parse(sessionStorage.getItem(CART_STORAGE_KEY) || '[]');

    const formatRupiah = (angka) => {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(angka);
    };

    function updateCartUI() {
        const pillBadge = document.getElementById('floatingCartBadge');
        const pillCount = document.getElementById('floatingCartCount');
        const pillTotal = document.getElementById('floatingCartTotal');

        const cartItemsArea = document.getElementById('cartItemsArea');
        const totalsArea = document.getElementById('cartTotalsArea');
        const emptyCartAction = document.getElementById('emptyCartAction');
        const cartDataInput = document.getElementById('cartDataInput');

        const totalItemValue = document.getElementById('totalItemValue');
        const totalValue = document.getElementById('totalValue');

        const floatingSkipBtn = document.getElementById('floatingSkipBtn');
        const floatingCartPill = document.getElementById('floatingCartPill');

        let totalQty = 0;
        let totalPrice = 0;

        if (cart.length === 0) {
            if (cartItemsArea) {
                cartItemsArea.innerHTML = `
                    <div class="text-center text-muted mt-4" id="emptyCartMessage">
                        <p style="font-size: 0.9rem; color: #bca0e5 !important;">Belum ada menu yang ditambahkan</p>
                    </div>
                `;
            }
            if (totalsArea) totalsArea.style.display = 'none';
            if (emptyCartAction) emptyCartAction.style.display = 'block';

            if (pillBadge) pillBadge.innerText = '0';
            if (pillCount) pillCount.innerText = '0 Items';
            if (pillTotal) pillTotal.innerText = 'Rp 0';
            if (cartDataInput) cartDataInput.value = '[]';

            if (floatingSkipBtn) floatingSkipBtn.style.display = 'flex';
            if (floatingCartPill) {
                floatingCartPill.classList.add('d-none-custom');
                floatingCartPill.style.display = 'none';
            }

        } else {
            if (totalsArea) totalsArea.style.display = 'block';
            if (emptyCartAction) emptyCartAction.style.display = 'none';

            let htmlContent = '';

            cart.forEach(item => {
                totalQty += item.qty;
                totalPrice += (item.price * item.qty);

                const imgSrc = item.img ? item.img : '{{ asset("images/gaming.jpg") }}';
                htmlContent += `
                    <div class="d-flex align-items-center mb-4">
                        <img src="${imgSrc}" alt="${item.name}" class="rounded" style="width: 56px; height: 56px; object-fit: cover;" onerror="this.src='{{ asset('images/gaming.jpg') }}'">

                        <div class="ms-3 flex-grow-1">
                            <div class="text-white fw-semibold mb-1" style="font-size: 0.95rem;">${item.name}</div>
                            <div class="d-flex align-items-center">
                                <button type="button" class="btn-qty-control" onclick="changeQty(${item.id}, -1)">-</button>
                                <span class="text-white mx-3 fw-bold" style="font-size: 0.95rem;">${item.qty}</span>
                                <button type="button" class="btn-qty-control" onclick="changeQty(${item.id}, 1)">+</button>
                            </div>
                        </div>

                        <div class="text-end d-flex flex-column align-items-end">
                            <div class="text-white fw-bold" style="font-size: 1rem;">
                                ${formatRupiah(item.price * item.qty)}
                            </div>
                            <button type="button" class="btn-delete-item" onclick="removeItem(${item.id})">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5Zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5Zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6Z"/>
                                    <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1ZM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118ZM2.5 3h11V2h-11v1Z"/>
                                </svg>
                                Hapus
                            </button>
                        </div>
                    </div>
                `;
            });

            if (cartItemsArea) cartItemsArea.innerHTML = htmlContent;
            if (totalItemValue) totalItemValue.innerText = totalQty;
            if (totalValue) totalValue.innerText = formatRupiah(totalPrice);

            if (pillBadge) pillBadge.innerText = totalQty;
            if (pillCount) pillCount.innerText = totalQty + ' Items';
            if (pillTotal) pillTotal.innerText = formatRupiah(totalPrice);

            if (cartDataInput) cartDataInput.value = JSON.stringify(cart);

            if (floatingSkipBtn) floatingSkipBtn.style.display = 'none';
            if (floatingCartPill) {
                floatingCartPill.classList.remove('d-none-custom');
                floatingCartPill.style.display = 'flex';
            }
        }

        // Simpan kondisi cart terbaru ke sessionStorage setiap kali UI di-update.
        // Diletakkan di sini (setelah blok if/else selesai total) supaya
        // tidak memutus struktur if/else itu sendiri.
        sessionStorage.setItem(CART_STORAGE_KEY, JSON.stringify(cart));
    }

    window.changeQty = function(id, change) {
        const itemIndex = cart.findIndex(i => i.id == id);
        if (itemIndex > -1) {
            cart[itemIndex].qty += change;
            if (cart[itemIndex].qty <= 0) {
                cart.splice(itemIndex, 1);
            }
            updateCartUI();
        }
    };

    window.removeItem = function(id) {
        cart = cart.filter(item => item.id != id);
        updateCartUI();
    };

    document.querySelectorAll('.add-to-cart-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            const price = parseInt(this.getAttribute('data-price'));
            const img = this.getAttribute('data-img');

            const existingItem = cart.find(item => item.id == id);

            if (existingItem) {
                existingItem.qty += 1;
            } else {
                cart.push({ id, name, price, img, qty: 1 });
            }

            updateCartUI();
        });
    });

    // Render ulang cart yang sudah tersimpan (dari sessionStorage)
    // begitu halaman dimuat — bukan cuma nunggu klik tombol "+".
    updateCartUI();

    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            const filter = this.getAttribute('data-filter');
            const items = document.querySelectorAll('.product-item');

            items.forEach(item => {
                if(filter === 'semua' || item.getAttribute('data-kategori') === filter) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
</script>

</body>
</html>