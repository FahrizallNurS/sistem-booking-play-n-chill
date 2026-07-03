<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/logo_dumb.png') }}">
    <title>Galeri - Play N Chill</title> 
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
                    <a class="nav-link" href="{{ url('/menu-fb') }}">Menu F&B</a>
                </li>

                <li class="nav-item">
                    {{-- Perhatikan class nav-btn-active pindah ke sini --}}
                    <a class="nav-link nav-btn-active" href="{{ url('/galeri') }}">Galeri</a>
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

{{-- Tambahan CSS khusus Halaman Galeri --}}
<style>
    /* Filter Buttons */
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

    /* Gallery Cards */
    .gallery-card {
        position: relative;
        border-radius: 16px;
        overflow: hidden;
        height: 380px;
        cursor: pointer;
        transition: transform 0.3s ease;
        box-shadow: 0 10px 20px rgba(0,0,0,0.2);
    }
    .gallery-card:hover {
        transform: translateY(-8px);
    }
    .gallery-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    .gallery-card:hover .gallery-img {
        transform: scale(1.05);
    }
    .gallery-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(to top, rgba(30, 10, 70, 1) 0%, rgba(30, 10, 70, 0.6) 50%, transparent 100%);
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        padding: 24px;
        color: white;
    }
    .gallery-badge {
        background-color: transparent;
        color: #ffd700;
        border: 1px solid #ffd700;
        font-size: 0.65rem;
        padding: 4px 10px;
        border-radius: 4px;
        display: inline-block;
        margin-bottom: 12px;
        font-weight: 700;
        letter-spacing: 1px;
        width: fit-content;
    }

    /* CTA Section */
    .cta-gallery {
      background: linear-gradient(135deg, #2E1f6e 0%, #4a33a5 100%);
      border-radius: 20px;
      padding: 60px 20px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
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
    }
    .btn-kuning:hover {
        transform: scale(1.05);
        background-color: #ffea00;
    }
</style>

{{-- ═══ AREA KONTEN UTAMA ═══ --}}
<div class="container my-5" style="min-height: 50vh;">
    <div class="text-center text-white mb-5" data-aos="fade-up">
        {{-- Bungkus span dengan div agar dipaksa turun baris --}}
        <div class="mb-3">
            <span class="badge rounded-pill text-secondary px-4 py-2" style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.1); letter-spacing: 2px; color: #a0a0a0 !important;">GALERI</span>
        </div>
        
        <h1 class="mb-4 font-modak" style="font-size: 2.5rem; letter-spacing: 1px; line-height: 1.2;">Lihat Suasana Terbaik <br class="d-md-none"><span style="color: #ffd700; letter-spacing: 2px;">Play N Chill</span></h1>
        
        <p class="mx-auto" style="max-width: 600px; color: #bca0e5; line-height: 1.6;">
            Jelajahi berbagai ruangan premium Play N Chill dan rasakan sendiri pengalaman gaming, karaoke, hingga private cinema yang nyaman, modern, dan eksklusif.
        </p>
    </div>

    <div class="d-flex justify-content-center flex-wrap mb-5 gap-3" data-aos="fade-up" data-aos-delay="100">
        <button class="filter-btn active">Semua</button>
        <button class="filter-btn">Galeri Reguler</button>
        <button class="filter-btn">Private Gaming</button>
        <button class="filter-btn">Private Nonton</button>
        <button class="filter-btn">Private Karaoke</button>
    </div>

    <div class="row g-4 mb-5 pb-4">
        <div class="col-12 col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
            <div class="gallery-card">
                <img src="{{ asset('images/gaming.jpg') }}" alt="Area Bermain Reguler" class="gallery-img">
                <div class="gallery-overlay">
                    <span class="gallery-badge">REGULER</span>
                    <h5 class="fw-bold mb-2" style="font-size: 1.25rem;">Area Bermain Reguler</h5>
                    <p class="mb-0" style="font-size: 0.85rem; color: #d8b8ff; line-height: 1.5;">Area gaming yang nyaman untuk bermain bersama teman dengan suasana santai dan nyaman.</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
            <div class="gallery-card">
                <img src="{{ asset('images/gaming.jpg') }}" alt="Private Gaming Room" class="gallery-img">
                <div class="gallery-overlay">
                    <span class="gallery-badge">PRIVATE</span>
                    <h5 class="fw-bold mb-2" style="font-size: 1.25rem;">Private Gaming Room</h5>
                    <p class="mb-0" style="font-size: 0.85rem; color: #d8b8ff; line-height: 1.5;">Nikmati pengalaman bermain PS5 secara private dengan ruangan eksklusif dan fasilitas premium.</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
            <div class="gallery-card">
                <img src="{{ asset('images/cinema.jpg') }}" alt="Private Cinema Room" class="gallery-img">
                <div class="gallery-overlay">
                    <span class="gallery-badge">CINEMA</span>
                    <h5 class="fw-bold mb-2" style="font-size: 1.25rem;">Private Cinema Room</h5>
                    <p class="mb-0" style="font-size: 0.85rem; color: #d8b8ff; line-height: 1.5;">Rasakan pengalaman menonton film dengan layar besar, audio berkualitas, dan suasana bioskop pribadi.</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="500">
            <div class="gallery-card">
                <img src="{{ asset('images/karaoke.jpg') }}" alt="Karaoke Room" class="gallery-img">
                <div class="gallery-overlay">
                    <span class="gallery-badge">KARAOKE</span>
                    <h5 class="fw-bold mb-2" style="font-size: 1.25rem;">Karaoke Room</h5>
                    <p class="mb-0" style="font-size: 0.85rem; color: #d8b8ff; line-height: 1.5;">Bernyanyi bersama keluarga maupun teman dalam ruangan karaoke private yang nyaman dan modern.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="cta-gallery text-center text-white mt-4" data-aos="zoom-in" data-aos-delay="200">
        <h2 class="fw-bold mb-3">Siap Merasakan Pengalaman Premium?</h2>
        <p class="mb-5 mx-auto" style="max-width: 550px; color: #d8b8ff;">
            Booking sekarang dan nikmati ruang gaming, private cinema, maupun karaoke terbaik hanya di Play N Chill.
        </p>
        <a href="{{ url('/booking') }}" class="btn btn-kuning">BOOKING SEKARANG</a>
    </div>
</div>

{{-- ═══ FOOTER ═══ --}}
<footer>
    <div class="container">
        <div class="row g-4 pb-2">
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="f-logo font-modak">Play N Chill</div>
                <p class="f-tagline">Nikmati pengalaman tak terlupakan bersama teman dan keluarga.</p>
            </div>
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