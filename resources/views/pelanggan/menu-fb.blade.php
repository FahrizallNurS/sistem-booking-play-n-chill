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
            padding-top: 85px; /* Sedikit dilebarkan agar konten tidak tertutup navbar fixed */
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
    </style>
</head>
<body>

{{-- ═══ NAVBAR ═══ --}}
<nav class="navbar navbar-expand-lg fixed-top">
    <div class="container-fluid px-4">
        <a class="navbar-brand p-0" href="{{ url('/') }}">
            <img src="{{ asset('images/logo_dumb.png') }}" alt="Play N Chill" height="48">
        </a>
        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navMain">
            <ul class="navbar-nav align-items-center gap-1">
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/') }}">Home</a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/booking') }}">Booking</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link nav-btn-active" href="{{ url('/menu-fb') }}">Menu F&B</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/galeri') }}">Galeri</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/tentang-kami') }}">Tentang Kami</a>
                </li>

                <li class="nav-item ms-2">
                    @guest
                        <a class="nav-link nav-btn-active" href="{{ url('/login') }}" style="background-color: var(--orange) !important;">
                            Login
                        </a>
                    @endguest
                    @auth
                        <div class="dropdown">
                            <div class="nav-avatar" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <svg viewBox="0 0 24 24">
                                    <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z" fill="var(--purple-dark)"/>
                                </svg>
                            </div>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="userDropdown">
                                <li><span class="dropdown-item-text fw-bold">{{ auth()->user()->name ?? 'User' }}</span></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">Keluar (Logout)</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @endauth
                </li>
            </ul>
        </div>
    </div>
</nav>

{{-- ═══ AREA KONTEN UTAMA (KITA CODING DI SINI NANTI) ═══ --}}
{{-- Tambahan CSS khusus Halaman Menu F&B --}}
<style>
    /* Filter Buttons (Mengikuti style Galeri) */
    .filter-btn {
        background-color: rgba(255, 255, 255, 0.05);
        color: #d8b8ff;
        border: 1px solid rgba(255, 255, 255, 0.1);
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
        background-color: #40288c; 
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
        height: 180px;
        overflow: hidden;
    }
    .fb-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
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
</style>

{{-- ═══ AREA KONTEN UTAMA ═══ --}}
<div class="container my-5" style="min-height: 50vh;">
    
    <div class="text-center text-white mb-5" data-aos="fade-up">
        <div class="mb-3">
            <span class="badge rounded-pill text-secondary px-4 py-2" style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.1); letter-spacing: 2px; color: #a0a0a0 !important;">MENU F&B</span>
        </div>
        
        <h1 class="mb-4 font-modak" style="font-size: 3.5rem; letter-spacing: 1px; line-height: 1.2;">
            Lengkapi Momen Santai Mu dengan <br class="d-none d-md-block"> 
            <span style="color: #ffd700;">"Menu Favoritmu"</span>
        </h1>
        
        <p class="mx-auto" style="max-width: 600px; color: #bca0e5; line-height: 1.6;">
            Nikmati berbagai pilihan makanan ringan, hidangan utama, dan minuman segar yang disiapkan untuk menemani pengalaman bermain mu di Play N Chill.
        </p>
    </div>

    <div class="d-flex justify-content-center flex-wrap mb-5 gap-3" data-aos="fade-up" data-aos-delay="100">
        <button class="filter-btn active">Semua</button>
        <button class="filter-btn">Makanan Ringan</button>
        <button class="filter-btn">Makanan Berat</button>
        <button class="filter-btn">Minuman</button>
    </div>

    <div class="row g-4 mb-5 pb-4">
        <div class="col-12 col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
            <div class="fb-card">
                <div class="fb-img-wrapper">
                    <img src="{{ asset('images/menu/french-fries.jpg') }}" alt="French Fries & Nuggets" class="fb-img" onerror="this.src='{{ asset('images/gaming.jpg') }}'">
                    <span class="fb-price">Rp 35.000</span>
                </div>
                <div class="fb-body">
                    <span class="fb-badge">MAKANAN RINGAN</span>
                    <h5 class="fb-title">French Fries & Nuggets</h5>
                    <p class="fb-desc">Kombinasi kentang goreng renyah dan chicken nuggets</p>
                    <div class="fb-footer">
                        <span class="fb-status">In Stock</span>
                        <button class="fb-add-btn">+</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
            <div class="fb-card">
                <div class="fb-img-wrapper">
                    <img src="{{ asset('images/menu/katsu.jpg') }}" alt="Chicken Katsu Rice" class="fb-img" onerror="this.src='{{ asset('images/gaming.jpg') }}'">
                    <span class="fb-price">Rp 48.000</span>
                </div>
                <div class="fb-body">
                    <span class="fb-badge">MAKANAN BERAT</span>
                    <h5 class="fb-title">Chicken Katsu Rice</h5>
                    <p class="fb-desc">Nasi putih hangat dengan katsu ayam renyah, salad...</p>
                    <div class="fb-footer">
                        <span class="fb-status">In Stock</span>
                        <button class="fb-add-btn">+</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
            <div class="fb-card">
                <div class="fb-img-wrapper">
                    <img src="{{ asset('images/menu/lychee-tea.jpg') }}" alt="Lychee Tea & Matcha" class="fb-img" onerror="this.src='{{ asset('images/gaming.jpg') }}'">
                    <span class="fb-price">Rp 28.000</span>
                </div>
                <div class="fb-body">
                    <span class="fb-badge">MINUMAN</span>
                    <h5 class="fb-title">Lychee Tea & Matcha</h5>
                    <p class="fb-desc">Pilihan minuman segar atau creamy untuk melepas...</p>
                    <div class="fb-footer">
                        <span class="fb-status">Fresh Brew</span>
                        <button class="fb-add-btn">+</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="500">
            <div class="fb-card">
                <div class="fb-img-wrapper">
                    <img src="{{ asset('images/menu/snack-spread.jpg') }}" alt="Premium Snack Spread" class="fb-img" onerror="this.src='{{ asset('images/gaming.jpg') }}'">
                    <span class="fb-price">Rp 65.000</span>
                </div>
                <div class="fb-body">
                    <span class="fb-badge">MAKANAN RINGAN</span>
                    <h5 class="fb-title">Premium Snack Spread</h5>
                    <p class="fb-desc">Paket snack lengkap untuk mabar seru bersama teman-</p>
                    <div class="fb-footer">
                        <span class="fb-status">Popular</span>
                        <button class="fb-add-btn">+</button>
                    </div>
                </div>
            </div>
        </div>
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
<footer>
    <div class="container">
        <div class="row g-4 pb-2">

            {{-- Brand --}}
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="f-logo font-modak">Play N Chill</div>
                <p class="f-tagline">Nikmati pengalaman tak terlupakan bersama teman dan keluarga.</p>
            </div>

            {{-- Hubungi Kami --}}
            <div class="col-6 col-sm-3 col-lg-3">
                <div class="f-head">Hubungi Kami</div>
                <ul class="f-list">
                    <li><span class="fi"><img src="{{ asset('gambar/ic_tel.png') }}" alt="Phone"></span><span>+62 857-3532-9227</span></li>
                    <li><span class="fi"><img src="{{ asset('gambar/ic_email.png') }}" alt="Email"></span><span>playnchillmadiun@gmail.com</span></li>
                    <li>
                        <span class="fi"><img src="{{ asset('gambar/ic_lok.png') }}" alt="Location"></span>
                        <span>Jl. Margobawero No.46, Mojorejo, Kec. Taman, Kota Madiun, Jawa Timur 63139</span>
                    </li>
                </ul>
            </div>

            {{-- Ikuti Kami --}}
            <div class="col-6 col-sm-3 col-lg-3">
                <div class="f-head">Ikuti Kami</div>
                <ul class="f-list">
                    <li>
                        <a class="soc-btn" href="https://youtube.com/@playnchillmadiun?si=KVGMA9tC2ktJHAY0" title="YouTube">
                            <img src="{{ asset('gambar/ic_yt.png') }}" alt="YouTube"> YouTube
                        </a>
                    </li>
                    <li>
                        <a class="soc-btn" href="https://www.tiktok.com/@playnchill.madiun?_r=1&_t=ZS-96DA4Nfui1t" title="TikTok">
                            <img src="{{ asset('gambar/ic_tk.png') }}" alt="TikTok"> TikTok
                        </a>
                    </li>
                    <li>
                        <a class="soc-btn" href="https://share.google/fMbFjkoIuMs0P7Mfh" title="Instagram">
                            <img src="{{ asset('gambar/ic_ig.png') }}" alt="Instagram"> Instagram
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Jam Operasional --}}
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="f-head">Jam Operasional</div>
                <ul class="f-list">
                    <li>
                        <span class="fi"><img src="{{ asset('gambar/ic_jam.png') }}" alt="Jam"></span>
                        <div>
                            <div>Senin – Kamis: 14.00 – 22.00</div>
                            <div>Jumat: 13.00 – 00.00</div>
                            <div>Sabtu – Minggu: 10.00 – 00.00</div>
                        </div>
                    </li>
                </ul>
            </div>

        </div>

        <hr class="f-divider">
        <p class="f-copy">&copy; {{ date('Y') }} Play N Chill Madiun. All rights reserved.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({ duration: 800, once: false, offset: 100 });
</script>

</body>
</html>