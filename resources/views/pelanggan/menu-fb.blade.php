<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/logo_dumb.png') }}">
    <title>Menu F&B - Play N Chill</title> 
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
        }
        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('{{ asset("images/bg-segitiga.png") }}');
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
            opacity: 0.8;
            z-index: -1;
        }
        .navbar.fixed-top {
            position: fixed !important;
            top: 0 !important;
            width: 100% !important;
            z-index: 1030 !important;
            background: rgb(255, 255, 255);
            backdrop-filter: blur(10px);
        }

        .hero-title {
            font-size: 3.5rem;
            letter-spacing: 1px;
            line-height: 1.2;
        }

        .text-nowrap-custom {
            white-space: nowrap;
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 2rem; 
            }
        }
        
        @media (max-width: 480px) {
            .hero-title {
                font-size: 1.65rem; 
            }
        }
    </style>
</head>
<body>

@include('partials.navbar')

<style>
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
        aspect-ratio: 4 / 3; /* Menjaga bentuk kotak tidak terlalu pipih di HP */
        overflow: hidden;
    }
    .fb-img {
        width: 100%;
        height: 100%;
        object-fit: cover; /* Kembalikan ke cover agar gambar penuh menyentuh sudut */
        object-position: center; /* KUNCI RAHASIA: Memastikan crop otomatis selalu mengambil bagian paling tengah gambar */
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

    .cta-section {
        background: linear-gradient(135deg, #2E1F6E 0%, #4A33A5 100%);
        border-radius: 20px;
        padding: 60px 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        position: relative;
        overflow: hidden;
    }
    .btn-kuning {
        background-color: #ffd700;
        color: #2b0054;
        font-weight: 800;
        padding: 14px 36px;
        border-radius: 50px;
        border: none;
        letter-spacing: 0.5px;
        transition: transform 0.2s ease, background-color 0.2s ease;
        text-decoration: none;
        display: inline-block;
    }
    .btn-kuning:hover {
        transform: scale(1.05);
        background-color: #ffea00;
        color: #2b0054;
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
        .cart-item-count { font-size: 0.85rem; }
        .cart-total-price { font-size: 0.9rem; }
        .cart-badge-new {
            width: 20px;
            height: 20px;
            font-size: 0.7rem;
        }
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

    .payment-method-title {
        color: #ffffff;
        font-weight: 700;
        font-size: 1.05rem;
        margin-bottom: 12px;
        margin-top: 10px;
    }
    .payment-card {
        display: flex;
        align-items: center;
        background-color: rgba(255, 255, 255, 0.05);
        border: 1.5px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        padding: 12px 16px;
        margin-bottom: 12px;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .payment-card:hover {
        background-color: rgba(255, 255, 255, 0.08);
    }
    .payment-card.active {
        border-color: #ff7a00;
        background-color: rgba(255, 122, 0, 0.05);
    }
    .payment-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        justify-content: center;
        align-items: center;
        margin-right: 15px;
        color: #ff7a00;
        background-color: rgba(255, 122, 0, 0.1);
    }
    .payment-info {
        flex-grow: 1;
    }
    .payment-name {
        color: #ffffff;
        font-weight: 800;
        font-size: 0.95rem;
        margin-bottom: 2px;
    }
    .payment-desc {
        color: #bca0e5;
        font-size: 0.75rem;
    }
    .payment-radio {
        width: 22px;
        height: 22px;
        border-radius: 50%;
        border: 2px solid rgba(255, 255, 255, 0.2);
        position: relative;
        transition: all 0.2s ease;
    }
    .payment-card.active .payment-radio {
        border-color: #ff7a00;
    }
    .payment-card.active .payment-radio::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 12px;
        height: 12px;
        background-color: #ff7a00;
        border-radius: 50%;
    }
    .summary-text-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
    }

    /* Style Catatan */
    .custom-textarea {
        background-color: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #ffffff;
        border-radius: 10px;
        font-size: 0.9rem;
        resize: none; 
        transition: all 0.3s ease;
    }
    .custom-textarea:focus {
        background-color: rgba(255, 255, 255, 0.08);
        border-color: #ff7a00; 
        color: #ffffff;
        box-shadow: 0 0 0 0.2rem rgba(255, 122, 0, 0.25);
    }
    .custom-textarea::placeholder {
        color: #bca0e5;
        opacity: 0.7;
    }

</style>

<div class="container mt-2 mt-md-5 mb-5" style="min-height: 50vh;">
    
    <div class="text-center text-white mb-5" data-aos="fade-up">
        <div class="mb-3">
            <span class="badge rounded-pill text-secondary px-4 py-2" style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.1); letter-spacing: 2px; color: #a0a0a0 !important;">MENU F&B</span>
        </div>
        
        <h1 class="mb-4 font-modak hero-title">
            Lengkapi Momen Santai Mu dengan <br class="d-none d-md-block"> 
            <span class="text-nowrap-custom" style="color: #ffd700;">"Menu Favoritmu"</span>
        </h1>
        
        <p class="mx-auto" style="max-width: 600px; color: #ffffff; line-height: 1.6;">
            Nikmati berbagai pilihan makanan ringan, hidangan utama, dan minuman segar yang disiapkan untuk menemani pengalaman bermain mu di Play N Chill.
        </p>
    </div>

    {{-- ================= 1. BAGIAN REKOMENDASI (BEST SELLER) ================= --}}
    <div class="mb-4 mt-5" data-aos="fade-up">
        <h3 class="text-white fw-bold mb-1" style="font-family: 'Nunito', sans-serif; font-size: 1.4rem;">
            Rekomendasi Food & Beverage
        </h3>
        <div style="width: 60px; height: 3px; background-color: #ffd700; border-radius: 2px;"></div>
    </div>

    <div class="row g-4 mb-5 pb-2">
        {{-- Kita ambil 4 produk pertama sebagai rekomendasi --}}
        @foreach($produks->take(4) as $item)
        <div class="col-12 col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
            <div class="fb-card">
                <div class="fb-img-wrapper">
                    <img src="{{ asset('uploads/fb/' . $item->foto) }}" alt="{{ $item->nama_produk }}" class="fb-img" onerror="this.src='{{ asset('images/gaming.jpg') }}'">
                    <span class="fb-price">Rp {{ number_format($item->harga_jual, 0, ',', '.') }}</span>
                </div>
                <div class="fb-body">
                    <span class="fb-badge">RECOMMENDED</span>
                    <h5 class="fb-title">{{ $item->nama_produk }}</h5>
                    <div class="fb-footer">
                        <span class="fb-status">In Stock</span>
                        <button class="fb-add-btn add-to-cart-btn" data-id="{{ $item->id_produk }}" data-name="{{ $item->nama_produk }}" data-price="{{ $item->harga_jual }}" data-img="{{ asset('uploads/fb/' . $item->foto) }}">+</button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- ================= 2. BAGIAN MENU LAINNYA & FILTER ================= --}}
    <div class="mb-3" data-aos="fade-up">
        <h3 class="text-white fw-bold mb-1" style="font-family: 'Nunito', sans-serif; font-size: 1.4rem;">
            Menu Lainnya
        </h3>
        <div style="width: 60px; height: 3px; background-color: #ffd700; border-radius: 2px;"></div>
    </div>

    <div class="d-flex justify-content-center flex-wrap mb-5 gap-2 gap-md-3" data-aos="fade-up" data-aos-delay="100">
        <button class="filter-btn active" data-filter="Semua">Semua</button>
        <button class="filter-btn" data-filter="Makanan Ringan">Makanan Ringan</button>
        <button class="filter-btn" data-filter="Makanan Berat">Makanan Berat</button>
        <button class="filter-btn" data-filter="Minuman">Minuman</button>
    </div>

    <div class="row g-4 mb-5 pb-4" id="menuLainnyaContainer">
        {{-- Sisa produk (setelah 4 pertama) akan diloop di sini --}}
        @foreach($produks->skip(4) as $item)
        <div class="col-12 col-md-6 col-lg-3 menu-item-card" data-kategori="{{ $item->subKategori->kategori_produk ?? 'Lainnya' }}" data-aos="fade-up" data-aos-delay="100">
            <div class="fb-card">
                <div class="fb-img-wrapper">
                    <img src="{{ asset('uploads/fb/' . $item->foto) }}" alt="{{ $item->nama_produk }}" class="fb-img" onerror="this.src='{{ asset('images/gaming.jpg') }}'">
                    <span class="fb-price">Rp {{ number_format($item->harga_jual, 0, ',', '.') }}</span>
                </div>
                <div class="fb-body">
                    <span class="fb-badge">{{ strtoupper($item->subKategori->kategori_produk ?? 'MENU') }}</span>
                    <h5 class="fb-title">{{ $item->nama_produk }}</h5>
                    <div class="fb-footer">
                        <span class="fb-status">In Stock</span>
                        <button class="fb-add-btn add-to-cart-btn" data-id="{{ $item->id_produk }}" data-name="{{ $item->nama_produk }}" data-price="{{ $item->harga_jual }}" data-img="{{ asset('uploads/fb/' . $item->foto) }}">+</button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="cta-section text-center mt-4" data-aos="zoom-in" data-aos-delay="200">
        <h2 class="mb-3" style="font-family: 'Nunito', sans-serif !important; color: #ffffff !important; font-weight: 800 !important; font-size: 1.75rem !important;">
            Temani Setiap Permainan dengan Hidangan Terbaik
        </h2>
        
        <p class="mb-5 mx-auto" style="max-width: 550px; color: #d8b8ff !important;">
            Pilih makanan dan minuman favoritmu sebelum bermain agar pengalaman gaming semakin nyaman dan menyenangkan.
        </p>
        
        <a href="{{ url('/booking') }}" class="btn-kuning">BOOKING SEKARANG</a>
    </div>
</div>

{{-- FOOTER --}}
@include('partials.footer')

<div class="floating-cart-pill" data-aos="zoom-in" data-aos-delay="400" data-bs-toggle="offcanvas" data-bs-target="#cartBottomSheet" aria-controls="cartBottomSheet">
    
    {{-- Ikon Keranjang & Badge --}}
    <div class="cart-icon-wrapper">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
        </svg>
        <span class="cart-badge-new" id="floatingCartBadge">0</span>
    </div>
    
    {{-- Teks Bertumpuk (Items & Total Harga) --}}
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
        
        {{-- Area Daftar Pesanan Cart --}}
        <div id="cartItemsArea" class="flex-grow-1 overflow-auto" style="scrollbar-width: none;">
            <div class="text-center text-muted mt-4" id="emptyCartMessage">
                <p style="font-size: 0.9rem; color: #bca0e5 !important;">Belum ada menu yang ditambahkan</p>
            </div>
        </div>

        {{-- Area Rincian Harga & Tombol Checkout JIKA ADA BARANG --}}
        <div class="mt-auto pt-2" id="cartTotalsArea" style="display: none;">
            <hr class="divider-custom mt-0 mb-3">
            
            {{-- PILIH METODE PEMBAYARAN --}}
            <div class="payment-method-title">Pilih Metode Pembayaran</div>
            
            <div class="payment-card active" data-method="QRIS" onclick="selectPayment('QRIS')">
                <div class="payment-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M0 .5A.5.5 0 0 1 .5 0h3a.5.5 0 0 1 0 1H1v2.5a.5.5 0 0 1-1 0v-3Zm12 0a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0V1h-2.5a.5.5 0 0 1-.5-.5ZM.5 12a.5.5 0 0 1 .5.5V15h2.5a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5v-3a.5.5 0 0 1 .5-.5Zm15 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1 0-1H15v-2.5a.5.5 0 0 1 .5-.5ZM4 4h1v1H4V4Z"/>
                        <path d="M7 2H2v5h5V2ZM3 3h3v3H3V3Zm2 8H4v1h1v-1Z"/>
                        <path d="M7 9H2v5h5V9Zm-4 1h3v3H3v-3Zm8-6h1v1h-1V4Z"/>
                        <path d="M9 2h5v5H9V2Zm1 1v3h3V3h-3ZM8 8v2h1v1H8v1h2v-2h1v2h1v-1h2v-1h-3V8H8Zm2 2H9V9h1v1Zm4 2h-1v1h-2v1h3v-2Zm-4 2v-1H8v1h2Z"/>
                    </svg>
                </div>
                <div class="payment-info">
                    <div class="payment-name">QRIS</div>
                    <div class="payment-desc">Scan barcode dari HP</div>
                </div>
                <div class="payment-radio"></div>
            </div>

            <div class="payment-card" data-method="Tunai" onclick="selectPayment('Tunai')">
                <div class="payment-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M1 3a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1H1zm7 8a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/>
                        <path d="M0 5a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1V5zm3 0a2 2 0 0 1-2 2v4a2 2 0 0 1 2 2h10a2 2 0 0 1 2-2V7a2 2 0 0 1-2-2H3z"/>
                    </svg>
                </div>
                <div class="payment-info">
                    <div class="payment-name">TUNAI</div>
                    <div class="payment-desc">Bayar langsung di kasir</div>
                </div>
                <div class="payment-radio"></div>
            </div>
            
            <hr class="divider-custom mt-2 mb-3">

            {{-- RINGKASAN TOTAL --}}
            <div class="summary-text-row">
                <span style="color: #bca0e5; font-size: 0.95rem;">Total Item</span>
                <span style="color: #ffffff; font-size: 0.95rem; font-weight: 700;" id="totalItemValue">0</span>
            </div>
            <div class="summary-text-row">
                <span style="color: #bca0e5; font-size: 0.95rem;">Subtotal</span>
                <span style="color: #ffffff; font-size: 0.95rem; font-weight: 700;" id="subtotalValue">Rp 0</span>
            </div>
            
            <hr class="divider-custom my-3">

            <div class="d-flex justify-content-between mb-4">
                <span class="text-white fw-bold" style="font-size: 1.15rem;">Grand Total</span>
                <span class="fw-bold" style="font-size: 1.15rem; color: #ff7a00;" id="totalValue">Rp 0</span>
            </div>
            
            {{-- Form Checkout F&B --}}
            <form action="{{ url('/checkout-fb') }}" method="POST">
                @csrf
                <input type="hidden" name="keranjang_fb" id="cartDataInput" value="[]">
                <input type="hidden" name="metode_pembayaran" id="metodePembayaranInput" value="QRIS">

                {{-- Form Catatan --}}
                <div class="mb-3 text-start">
                    <label for="catatan" class="form-label text-white fw-bold mb-2" style="font-size: 0.95rem">
                        Catatan
                        <span style="color: #bca0e5; font-size: 0.8rem; font-weight: normal;">
                            (Opsional)
                        </span>
                    </label>
                    <textarea name="catatan" id="catatan" class="form-control custom-textarea px-3 py-2" rows="2" placeholder="Contoh: Antar kemana?, Atas nama siapa?"></textarea>
                </div>
                
                <button type="submit" class="btn w-100 btn-checkout-orange text-uppercase" style="letter-spacing: 1px;">
                    Lanjut Checkout
                </button>
            </form>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<script>
    // ================= INISIALISASI ANIMASI =================
    AOS.init({ duration: 800, once: false, offset: 100 });

    // ================= FUNGSI FILTER MENU =================
    document.querySelectorAll('.filter-btn').forEach(button => {
        button.addEventListener('click', function() {
            document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');

            const filterValue = this.getAttribute('data-filter');
            const menuCards = document.querySelectorAll('.menu-item-card');

            menuCards.forEach(card => {
                if (filterValue === 'Semua' || card.getAttribute('data-kategori') === filterValue) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });

    // ================= FUNGSI KERANJANG (CART) =================
    let cart = [];
    
    const formatRupiah = (angka) => {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(angka);
    };

    function updateCartUI() {
        const pillBadge = document.getElementById('floatingCartBadge');
        const pillCount = document.getElementById('floatingCartCount');
        const pillTotal = document.getElementById('floatingCartTotal');
        
        const cartItemsArea = document.getElementById('cartItemsArea');
        const totalsArea = document.getElementById('cartTotalsArea');
        const cartDataInput = document.getElementById('cartDataInput');
        
        const totalItemValue = document.getElementById('totalItemValue');
        const subtotalValue = document.getElementById('subtotalValue');
        const totalValue = document.getElementById('totalValue');

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
            
            if (pillBadge) pillBadge.innerText = '0';
            if (pillCount) pillCount.innerText = '0 Items';
            if (pillTotal) pillTotal.innerText = 'Rp 0';
            if (cartDataInput) cartDataInput.value = '[]';
            
        } else {
            // Tampilan jika keranjang ADA ISINYA
            if (totalsArea) totalsArea.style.display = 'block';
            
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
            if (subtotalValue) subtotalValue.innerText = formatRupiah(totalPrice);
            if (totalValue) totalValue.innerText = formatRupiah(totalPrice);
            
            if (pillBadge) pillBadge.innerText = totalQty;
            if (pillCount) pillCount.innerText = totalQty + ' Items';
            if (pillTotal) pillTotal.innerText = formatRupiah(totalPrice);
            
            if (cartDataInput) cartDataInput.value = JSON.stringify(cart); 
        }
    }

    // ================= FUNGSI TAMBAH/KURANG/HAPUS ITEM =================
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

    document.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('add-to-cart-btn')) {
            e.preventDefault(); 
            const button = e.target;
            const id = button.getAttribute('data-id');
            const name = button.getAttribute('data-name');
            const price = parseInt(button.getAttribute('data-price'));
            const img = button.getAttribute('data-img');

            const existingItem = cart.find(item => item.id == id);
            
            if (existingItem) {
                existingItem.qty += 1;
            } else {
                cart.push({ id, name, price, img, qty: 1 });
            }
            
            updateCartUI();

            const cartPill = document.querySelector('.floating-cart-pill');
            if (cartPill) {
                cartPill.style.transform = 'scale(1.1)';
                setTimeout(() => {
                    cartPill.style.transform = 'scale(1)';
                }, 200);
            }
        }
    });

    // ================= FUNGSI METODE PEMBAYARAN =================
    window.selectPayment = function(method) {
        document.querySelectorAll('.payment-card').forEach(card => {
            card.classList.remove('active');
        });

        const selectedCard = document.querySelector(`.payment-card[data-method="${method}"]`);
        if (selectedCard) {
            selectedCard.classList.add('active');
        }

        const methodInput = document.getElementById('metodePembayaranInput');
        if (methodInput) {
            methodInput.value = method;
        }
    };

    document.addEventListener("DOMContentLoaded", function() {
        updateCartUI();
    });

</script>

</body>
</html>