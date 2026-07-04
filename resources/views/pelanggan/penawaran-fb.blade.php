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
        }
        body::before {
            content: "";
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background-image: url('{{ asset("images/bg-segitiga.png") }}');
            background-repeat: no-repeat; background-size: cover;
            background-position: center; opacity: 0.8; z-index: -1;
        }
        .navbar.fixed-top {
            position: fixed !important; top: 0 !important; width: 100% !important;
            z-index: 1030 !important; background: rgb(255, 255, 255);
        }
        
        .menu-container {
            background-color: rgba(53, 34, 133, 0.6);
            border-radius: 20px;
            padding: 30px;
        }
        .cart-box {
            background-color: #2E1F6E;
            border: 1px solid rgba(255,255,255,0.05);
            border-radius: 20px;
            padding: 24px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            position: sticky;
            top: 100px; 
        }

        .btn-orange {
            background-color: #FF7A00;
            color: white;
            font-weight: 700;
            border-radius: 50px;
            padding: 12px 24px;
            border: none;
            transition: all 0.2s ease;
        }
        .btn-orange:hover {
            background-color: #e66e00;
            transform: scale(1.02);
            color: white;
        }

        /* Card Menu F&B */
        .fb-card {
            background-color: #40288c; border-radius: 16px; overflow: hidden;
            box-shadow: 0 10px 20px rgba(0,0,0,0.2); transition: transform 0.3s ease;
            height: 100%; display: flex; flex-direction: column;
            border: 1px solid rgba(255,255,255,0.05);
        }
        .fb-card:hover { transform: translateY(-5px); }
        .fb-img-wrapper { position: relative; height: 240px; overflow: hidden; }
        .fb-img { width: 100%; height: 100%; object-fit: cover; }
        .fb-price {
            position: absolute; top: 12px; right: 12px;
            background-color: #ffd700; color: #2b0054;
            font-weight: 800; font-size: 0.8rem; padding: 4px 12px; border-radius: 20px;
        }
        .fb-body { padding: 15px; display: flex; flex-direction: column; flex-grow: 1; }
        .fb-badge {
            background-color: rgba(255, 215, 0, 0.1); color: #ffd700;
            font-size: 0.6rem; padding: 4px 8px; border-radius: 4px;
            display: inline-block; margin-bottom: 8px; font-weight: 700; width: fit-content;
        }
        .fb-title { color: white; font-weight: bold; font-size: 1.1rem; line-height: 1.3; }
        .fb-add-btn {
            background-color: #ffd700; color: #2b0054; border: none;
            width: 32px; height: 32px; border-radius: 50%;
            display: flex; justify-content: center; align-items: center;
            font-size: 1.2rem; font-weight: bold; transition: transform 0.2s; margin-left: auto;
        }
        .fb-add-btn:hover { transform: scale(1.1); background-color: #ffea00; }

        /* Filter & Cart Item Style */
        .filter-btn {
            background-color: transparent; color: #d8b8ff;
            border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 50px;
            padding: 6px 16px; font-size: 0.85rem; transition: all 0.3s;
        }
        .filter-btn.active, .filter-btn:hover { background-color: #ffd700; color: #2b0054; border-color: #ffd700; }
        
        .cart-item-img { width: 40px; height: 40px; border-radius: 8px; object-fit: cover; }
        .qty-btn { background: rgba(255,255,255,0.1); border: none; color: white; width: 24px; height: 24px; border-radius: 4px; font-size: 0.8rem; }
    </style>
</head>
<body>

{{-- NAVBAR --}}
<nav class="navbar navbar-expand-lg fixed-top">
    <div class="container-fluid px-4">
        <a class="navbar-brand p-0" href="{{ url('/') }}">
            <img src="{{ asset('images/logo_dumb.png') }}" alt="Play N Chill" height="48">
        </a>
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

{{-- AREA KONTEN UTAMA --}}
<div class="container my-5 pb-5">
    <div class="row g-4">
        
        <div class="col-lg-7 col-xl-8">
            <div class="menu-container text-white" data-aos="fade-right">
                
                <h2 class="fw-bold mb-1">Lengkapi Pengalaman Bermainmu</h2>
                <h1 class="font-modak mb-3" style="color: #ffd700; font-size: 2.5rem; letter-spacing: 1px;">Menu Favoritmu</h1>
                <p style="color: #bca0e5; font-size: 0.95rem; max-width: 90%;">
                    Nikmati berbagai pilihan makanan ringan, hidangan utama, dan minuman segar yang disiapkan untuk menemani pengalaman bermain mu di Play N Chill.
                </p>

                <h5 class="fw-bold mt-5 mb-2">Rekomendasi Menu</h5>
                <p style="color: #bca0e5; font-size: 0.85rem;" class="mb-4">Menu favorit pelanggan yang sering dipesan bersamaan dengan booking.</p>
                
                <div class="row g-4 mb-5">
                    <div class="col-md-6">
                        <div class="fb-card">
                            <div class="fb-img-wrapper">
                                <img src="{{ asset('images/menu/french-fries.jpg') }}" alt="Menu" class="fb-img" onerror="this.src='{{ asset('images/gaming.jpg') }}'">
                                <span class="fb-price">Rp 35.000</span>
                            </div>
                            <div class="fb-body">
                                <span class="fb-badge">MAKANAN RINGAN</span>
                                <h5 class="fb-title">French Fries & Nuggets</h5>
                                <button class="fb-add-btn add-to-cart-btn" data-id="1" data-name="French Fries & Nuggets" data-price="35000" data-img="{{ asset('images/menu/french-fries.jpg') }}">+</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="fb-card">
                            <div class="fb-img-wrapper">
                                <img src="{{ asset('images/menu/lychee-tea.jpg') }}" alt="Menu" class="fb-img" onerror="this.src='{{ asset('images/gaming.jpg') }}'">
                                <span class="fb-price">Rp 28.000</span>
                            </div>
                            <div class="fb-body">
                                <span class="fb-badge">MINUMAN</span>
                                <h5 class="fb-title">Lychee Tea & Matcha</h5>
                                <button class="fb-add-btn add-to-cart-btn" data-id="2" data-name="Lychee Tea & Matcha" data-price="28000" data-img="{{ asset('images/menu/lychee-tea.jpg') }}">+</button>
                            </div>
                        </div>
                    </div>
                </div>

                <h5 class="fw-bold mt-4 mb-4">Menu Lainnya</h5>
                <div class="d-flex flex-wrap gap-2 mb-4">
                    <button class="filter-btn active">Semua</button>
                    <button class="filter-btn">Makanan Ringan</button>
                    <button class="filter-btn">Makanan Berat</button>
                    <button class="filter-btn">Minuman</button>
                </div>

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="fb-card">
                            <div class="fb-img-wrapper">
                                <img src="{{ asset('images/menu/snack-spread.jpg') }}" alt="Menu" class="fb-img" onerror="this.src='{{ asset('images/gaming.jpg') }}'">
                                <span class="fb-price">Rp 65.000</span>
                            </div>
                            <div class="fb-body">
                                <span class="fb-badge">MAKANAN RINGAN</span>
                                <h5 class="fb-title">Premium Snack Spread</h5>
                                <button class="fb-add-btn add-to-cart-btn" data-id="3" data-name="Premium Snack Spread" data-price="65000" data-img="{{ asset('images/menu/snack-spread.jpg') }}">+</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="fb-card">
                            <div class="fb-img-wrapper">
                                <img src="{{ asset('images/menu/katsu.jpg') }}" alt="Menu" class="fb-img" onerror="this.src='{{ asset('images/gaming.jpg') }}'">
                                <span class="fb-price">Rp 48.000</span>
                            </div>
                            <div class="fb-body">
                                <span class="fb-badge">MAKANAN BERAT</span>
                                <h5 class="fb-title">Chicken Katsu Rice</h5>
                                <button class="fb-add-btn add-to-cart-btn" data-id="4" data-name="Chicken Katsu Rice" data-price="48000" data-img="{{ asset('images/menu/katsu.jpg') }}">+</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="col-lg-5 col-xl-4">
            <div class="cart-box text-white" data-aos="fade-left" data-aos-delay="200">
                <h5 class="fw-bold mb-4">Ringkasan Pesanan F&B</h5>

                {{-- KODE PENDETEKSI ERROR LARAVEL --}}
                    @if ($errors->any())
                    <div class="alert alert-danger" style="border-radius: 10px; font-size: 0.85rem;">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <div id="cartItemsArea" class="mb-4" style="min-height: 150px; display: flex; flex-direction: column; justify-content: center;">
                    <div class="text-center text-muted" id="emptyCartMessage">
                        <p style="font-size: 0.85rem; color: #bca0e5 !important;">Belum ada menu yang ditambahkan</p>
                    </div>
                </div>

                <div id="cartTotalsArea" style="display: none;">
                    <hr style="border-color: rgba(255,255,255,0.1);">
                    <div class="d-flex justify-content-between mb-2" style="font-size: 0.9rem; color: #bca0e5;">
                        <span>Subtotal F&B</span>
                        <span id="subtotalValue">Rp 0</span>
                    </div>
                    <div class="d-flex justify-content-between mb-4 fw-bold" style="font-size: 1.1rem;">
                        <span>Total Pembayaran</span>
                        <span id="totalValue">Rp 0</span>
                    </div>
                </div>

                <!-- Form Checkout yang Menggabungkan Data Booking & F&B -->
                <form action="{{ route('booking.store') }}" method="POST" id="checkoutForm">
                    @csrf
                    
                    {{-- Menangkap data booking dari URL (operan halaman sebelumnya) --}}
                    <input type="hidden" name="id_penetapan_harga" value="{{ request('id_penetapan_harga') }}">
                    <input type="hidden" name="tanggal" value="{{ request('tanggal') }}">
                    <input type="hidden" name="waktu_mulai" value="{{ request('waktu_mulai') }}">
                    <input type="hidden" name="opsi_pembayaran" value="{{ request('opsi_pembayaran') }}">
                    <input type="hidden" name="jumlah_dp" value="{{ request('jumlah_dp') }}">
                    <input type="hidden" name="no_hp" value="{{ request('no_hp') }}">
                    
                    {{-- Wadah rahasia untuk menampung data keranjang makanan (JSON) --}}
                    <input type="hidden" name="keranjang_fb" id="cartDataInput" value="[]">

                    <button type="submit" id="checkoutBtn" class="btn btn-orange w-100 mt-2">
                        Nanti Saja & Lanjut Pembayaran
                    </button>
                </form>

            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<script>
    AOS.init({ duration: 800, once: true });
    let cart = [];
    const formatRupiah = (angka) => {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(angka);
    };

    function updateCartUI() {
        const cartItemsArea = document.getElementById('cartItemsArea');
        const emptyMessage = document.getElementById('emptyCartMessage');
        const totalsArea = document.getElementById('cartTotalsArea');
        const checkoutBtn = document.getElementById('checkoutBtn');
        const subtotalValue = document.getElementById('subtotalValue');
        const totalValue = document.getElementById('totalValue');

        if (cart.length === 0) {
            cartItemsArea.innerHTML = '';
            cartItemsArea.appendChild(emptyMessage);
            emptyMessage.style.display = 'block';
            totalsArea.style.display = 'none';
            cartItemsArea.style.justifyContent = 'center';
            checkoutBtn.innerHTML = 'Nanti Saja & Lanjut Pembayaran'; 
        } 
     
        else {
            emptyMessage.style.display = 'none';
            cartItemsArea.style.justifyContent = 'flex-start';
            cartItemsArea.innerHTML = ''; 
            
            let total = 0;

            cart.forEach(item => {
                total += item.price * item.qty;
                const imgSrc = item.img ? item.img : '{{ asset("images/gaming.jpg") }}';
                const itemHTML = `
                    <div class="d-flex align-items-center mb-3">
                        <img src="${imgSrc}" class="cart-item-img me-3" onerror="this.src='{{ asset('images/gaming.jpg') }}'">
                        <div class="flex-grow-1">
                            <h6 class="mb-1" style="font-size: 0.9rem;">${item.name}</h6>
                            <div class="d-flex align-items-center gap-2">
                                <button class="qty-btn" onclick="changeQty(${item.id}, -1)">-</button>
                                <span style="font-size: 0.85rem;">${item.qty}</span>
                                <button class="qty-btn" onclick="changeQty(${item.id}, 1)">+</button>
                            </div>
                        </div>
                        <div class="fw-bold" style="font-size: 0.9rem;">
                            ${formatRupiah(item.price * item.qty)}
                        </div>
                    </div>
                `;
                cartItemsArea.innerHTML += itemHTML;
            });

            totalsArea.style.display = 'block';
            subtotalValue.innerHTML = formatRupiah(total);
            totalValue.innerHTML = formatRupiah(total);
            checkoutBtn.innerHTML = 'Lanjut Pembayaran';
        }
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

    document.querySelectorAll('.add-to-cart-btn').forEach(button => {
        button.addEventListener('click', function() {
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

</script>

</body>
</html>