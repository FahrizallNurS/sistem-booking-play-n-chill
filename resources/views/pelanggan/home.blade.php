<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/logo_dumb.png') }}">
    <title>Play N Chill</title> 
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
            background-color: var(--purple-dark); /* Warna dasar tetap di body */
            position: relative;
            min-height: 100vh;
            margin: 0;
            padding-top: 65px;
        }

        body::before {
            content: "";
            position: fixed; /* Agar background tetap diam saat scroll */
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            
            /* Pengaturan gambar background */
            background-image: url('{{ asset("images/bg-segitiga.png") }}');
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;

            /* ATUR TRANSPARANSI DI SINI */
            opacity: 0.8; /* Nilai 0.0 (hilang) sampai 1.0 (jelas) */
            
            z-index: -1; /* Memastikan background berada di belakang konten */
        }

        .navbar.fixed-top {
        position: fixed !important;
        top: 0 !important;
        width: 100% !important;
        z-index: 1030 !important; /* Nilai tinggi agar di atas semua elemen */
        background: rgba(255, 255, 255, 0.836); /* Beri warna agar tidak transparan total */
        backdrop-filter: blur(10px); /* Efek blur estetik */
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

        <button class="navbar-toggler border-0 shadow-none" type="button"
                data-bs-toggle="collapse" data-bs-target="#navMain">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="navMain">
            <ul class="navbar-nav align-items-center gap-1">
                <li class="nav-item">
                    <a class="nav-link nav-btn-active" href="{{ url('/') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/booking') }}">Booking</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/tentang-kami') }}">Tentang Kami</a>
                </li>
                <li class="nav-item ms-2">

                    @guest
                        {{-- Belum login: tampilkan tombol Login --}}
                        <a class="nav-link nav-btn-active" href="{{ url('/login') }}"
                        style="background-color: var(--orange) !important;">
                            Login
                        </a>
                    @endguest

                    @auth
                        {{-- Sudah login: tampilkan avatar + dropdown --}}
                        <div class="dropdown">
                            <div class="nav-avatar" id="userDropdown"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <svg viewBox="0 0 24 24">
                                    <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4
                                            7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6
                                            1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"
                                        fill="var(--purple-dark)"/>
                                </svg>
                            </div>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0"
                                aria-labelledby="userDropdown">
                                <li>
                                    <span class="dropdown-item-text fw-bold">
                                        {{ auth()->user()->name }}
                                    </span>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="{{ url('/profile') }}">
                                        Profil Saya
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            Keluar (Logout)
                                        </button>
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

{{-- ═══ HERO / CAROUSEL ═══ --}}
{{-- ═══ HERO / CAROUSEL ═══ --}}
<section class="hero p-0" style="background: transparent;">
    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">

        {{-- Indicator --}}
        <div class="carousel-indicators">
            @for ($i = 0; $i < 4; $i++) {{-- Sesuaikan angka 5 dengan jumlah fotomu --}}
                <button type="button"
                        data-bs-target="#heroCarousel"
                        data-bs-slide-to="{{ $i }}"
                        class="{{ $i == 0 ? 'active' : '' }}">
                </button>
            @endfor
        </div>

        {{-- Slides --}}
        <div class="carousel-inner">

            @for ($i = 1; $i <= 4; $i++) {{-- Sesuaikan angka 5 dengan jumlah fotomu --}}
                <div class="carousel-item {{ $i == 1 ? 'active' : '' }}" data-bs-interval="4000">

                    <div class="container-fluid py-4">
                       
                        
                            @if ($i == 4)
                                {{-- 🌟 SLIDE 1: Desain Lengkap (Ada Teks, Tombol, dan Gradasi) 🌟 --}}
                                {{-- KOTAK LUAR (Dark Blue) - Dipertahankan di semua slide agar ukuran tinggi tetap stabil --}}
                                <div class="rounded-4 p-3" style="background: rgb(14, 23, 0); box-shadow: 0 10px 30px rgba(0,0,0,0.2);">

                                <div class="rounded-4 px-5 py-5 d-flex flex-column flex-lg-row align-items-center justify-content-between"
                                     style="
                                     min-height:420px;
                                     background:
                                     linear-gradient(rgba(60,0,110,.65),rgba(20,0,60,.65)),
                                     url('{{ asset("images/banner/slide{$i}.jpg") }}');
                                     background-size:cover;
                                     background-position:center;
                                     ">

                                    {{-- Bagian Kiri --}}
                                    <div class="text-white">
                                        <p class="text-uppercase mb-3" style="letter-spacing:3px;color:#d8b8ff;">
                                            Premium Gaming
                                        </p>
                                        <h1 class="fw-bold display-2 mb-3">
                                            PlaynChill
                                        </h1>
                                        <p class="fs-4 mb-4" style="max-width: 600px;">
                                            Rental PlayStation dengan suasana nyaman dan harga terjangkau
                                        </p>
                                        <div class="d-flex gap-3 flex-wrap">
                                            <a href="{{ url('/booking') }}" class="btn btn-lg text-white px-4 fw-bold" style="background:#a020f0; border-radius: 10px;">
                                                Book Now
                                            </a>
                                            <a href="{{ url('/booking') }}" class="btn btn-lg text-white px-4 fw-bold" style="border:2px solid #c06cff; border-radius: 10px;">
                                                Lihat Paket
                                            </a>
                                        </div>
                                    </div>

                                    {{-- Bagian Kanan --}}
                                    <div class="text-white mt-5 mt-lg-0 d-none d-md-block">
                                        <div style="font-size:170px; opacity:.15; transform: rotate(15deg);">🎮</div>
                                    </div>

                                </div>
                            </div> 
                            @else
                            <div class="rounded-4 p-3" style="box-shadow: 0 10px 30px rgba(0, 0, 0, 0.085);">
                                {{-- 🖼️ SLIDE 2 DAN SETERUSNYA: Gambar Polos (Tanpa Teks & Tombol) 🖼️ --}}
                                <div class="rounded-4"
                                     style="
                                     min-height:420px;
                                     background: url('{{ asset("images/banner/slide{$i}.jpg") }}');
                                     background-size:cover;
                                     background-position:center;
                                     ">
                                </div>
                            </div>    
                            @endif
                        
                    </div>

                </div>
            @endfor

        </div>

        {{-- Arrow Navigation --}}
        <button class="carousel-control-prev" type="button"
                data-bs-target="#heroCarousel" data-bs-slide="prev" style="width: 5%;">
            <span class="carousel-control-prev-icon"></span>
        </button>

        <button class="carousel-control-next" type="button"
                data-bs-target="#heroCarousel" data-bs-slide="next" style="width: 5%;">
            <span class="carousel-control-next-icon"></span>
        </button>

    </div>
</section>

{{-- ═══ SEMUA DALAM SATU TEMPAT ═══ --}}
<div class="white-card" data-aos="fade-up"> {{-- Animasi fade-up untuk judul --}}
    <h2>Semua ada dalam Satu Tempat</h2>
    <p class="sub-desc">
        Nikmati tiga jenis hiburan berbeda tanpa perlu pindah tempat. Hemat waktu, maksimalkan keseruan!
    </p>

   <div class="room-grid">

    {{-- Gaming Room --}}
    <a href="{{ url('/gallery?category=gaming') }}" class="text-decoration-none" data-aos="fade-up" data-aos-delay="100">
        <div class="r-card">
            <div class="r-img gaming" style="background-image: url('{{ asset('images/gaming.jpg') }}'); background-size: cover; background-position: center;">
                <span class="r-badge">🎮</span>
                <span class="r-sofa">🛋️</span>
            </div>
            <div class="r-foot">
                <h5>Gaming Room</h5>
                <p>PlayStation & Nintendo Switch terbaru dengan koleksi game lengkap</p>
            </div>
        </div>  
    </a>

    {{-- Karaoke Room --}}
    <a href="{{ url('/gallery?category=karaoke') }}" class="text-decoration-none" data-aos="fade-up" data-aos-delay="200">
        <div class="r-card">
            <div class="r-img karaoke" style="background-image: url('{{ asset('images/karaoke.jpg') }}'); background-size: cover; background-position: center;">
                <span class="r-badge">🎤</span>
                <span class="r-sofa">🛋️</span>
            </div>
            <div class="r-foot">
                <h5>Karaoke Room</h5>
                <p>Ruang karaoke privat dengan sound system premium</p>
            </div>
        </div>
    </a>

    {{-- Private Bioskop --}}
    <a href="{{ url('/gallery?category=bioskop') }}" class="text-decoration-none" data-aos="fade-up" data-aos-delay="300">
        <div class="r-card">
            <div class="r-img bioskop" style="background-image: url('{{ asset('images/cinema.jpg') }}'); background-size: cover; background-position: center;">
                <span class="r-badge">🎞️</span>
                <span class="r-sofa">🛋️</span>
            </div>
            <div class="r-foot">
                <h5>Private Bioskop</h5>
                <p>Nikmati film favorit di layar lebar dengan kenyamanan maksimal</p>
            </div>
        </div>
    </a>
    </div>
</div>

{{-- ═══ ROOM TOUR ═══ --}}
<section class="tour-section">
    <h2 data-aos="fade-down " class="font-modak">Room Tour</h2>
    <p class="tour-sub" data-aos="fade-down" data-aos-delay="100">Rasakan pengalaman seru di Play N Chill melalui video tour kami</p>

    <div class="vid-wrap" id="vidWrap" onclick="startVideo()" data-aos="zoom-in" data-aos-duration="1000">
        <div class="vid-thumb" 
            style="background-image: url('https://img.youtube.com/vi/P3yd4BX9aaU/maxresdefault.jpg'); 
                    background-size: cover; 
                    background-position: center; 
                    background-repeat: no-repeat;">
        </div>
        <div class="vid-overlay">
            <div class="vid-play">
                <svg viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
            </div>
        </div>
    </div>
</section>

{{-- ═══ DAFTAR GAME ═══ --}}
<section class="game-section">
    <div class="container-fluid px-4">
        <h2 class="teks-outline font-modak" data-aos="fade-right">Koleksi Game Kami</h2>

        {{-- Navigasi Filter --}}
        <div class="game-filter" data-aos="fade-left">
            <button class="filter-game-btn active" onclick="filterGames('all')">Semua</button>
            <button class="filter-game-btn" onclick="filterGames('ps3')">PS 3</button>
            <button class="filter-game-btn" onclick="filterGames('ps4')">PS 4</button>
            <button class="filter-game-btn" onclick="filterGames('ps5')">PS 5</button>
            <button class="filter-game-btn" onclick="filterGames('switch')">Switch</button>
        </div>

        {{-- Grid Game --}}
        <div class="game-grid" id="gameGrid">
            @php
                // Data Dummy Game (Nanti bisa dipindah ke Controller)
                $games = [
                    ['title' => 'GTA V', 'platform' => 'ps4', 'img' => 'gta5.jpg'],
                    ['title' => 'God of War Ragnarok', 'platform' => 'ps5', 'img' => 'gow.jpg'],
                    ['title' => 'Mario Kart 8', 'platform' => 'switch', 'img' => 'mario.jpg'],
                    ['title' => 'FIFA 23', 'platform' => 'ps5', 'img' => 'fifa23.jpg'],
                    ['title' => 'The Last of Us', 'platform' => 'ps3', 'img' => 'tlou.jpg'],
                    ['title' => 'Naruto Storm 4', 'platform' => 'ps4', 'img' => 'naruto.jpg'],
                ];
            @endphp

            @foreach ($games as $index => $game)
                <div class="game-card" data-platform="{{ $game['platform'] }}" data-aos="fade-up" data-aos-delay="{{ $index * 50 }}">
                    {{-- Pastikan kamu punya gambar di public/images/games/ --}}
                    <img src="{{ asset('images/games/' . $game['img']) }}" 
                         onerror="this.src='{{ asset('images/gallery/room_sample.jpg') }}'" alt="Game">
                    <span class="game-badge">{{ $game['platform'] }}</span>
                    <h5>{{ $game['title'] }}</h5>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══ CTA ═══ --}}
<section class="cta-section" id="booking" data-aos="zoom-in-up">
    <h2 class="teks-outline font-modak" >Siap untuk nongkrong Seru?</h2>
    <p>Pesan kamar Anda sekarang dan ciptakan kenangan tak terlupakan bersama teman dan keluarga.</p>
    <a href="{{ url('/booking') }}" class="btn-pesan">Pesan Sekarang!</a>
</section>

{{-- ═══ FOOTER ═══ --}}
<footer>
    <div class="container">
        <div class="row g-4 pb-2">

            {{-- Brand --}}
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="f-logo">Play N Chill</div>
                <p class="f-tagline">Nikmati pengalaman tak terlupakan bersama teman-teman!</p>
            </div>

            {{-- Hubungi Kami --}}
            <div class="col-6 col-sm-3 col-lg-3">
                <div class="f-head">Hubungi Kami</div>
                <ul class="f-list">
                    <li><span class="fi">📞</span><span>+62 857-3532-9227</span></li>
                    <li><span class="fi">📧</span><span>playnchillmadiun@gmail.com</span></li>
                    <li>
                        <span class="fi">📍</span>
                        <span>Jl. Margobawero No.46, Mojorejo, Kec. Taman, Kota Madiun, Jawa Timur 63139</span>
                    </li>
                </ul>
            </div>

            {{-- Ikuti Kami --}}
            <div class="col-6 col-sm-3 col-lg-3">
                <div class="f-head">Ikuti Kami</div>
                <ul class="f-list">
                    <li><a class="soc-btn" href="#" title="YouTube">▶ Youtube</a></li>
                    <li><a class="soc-btn" href="#" title="TikTok">♫ TikTok</a></li>
                    <li>
                        <a class="soc-btn" href="https://share.google/fMbFjkoIuMs0P7Mfh" title="Instagram">
                            📸 Instagram
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Jam Operasional --}}
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="f-head">Jam Operasional</div>
                <ul class="f-list">
                    <li>
                        <span class="fi">🕐</span>
                        <div>
                            <div>Senin – Kamis: 14.00 – 22.00</div>
                            <div>Jumat: 14.00 – 23.00</div>
                            <div>Sabtu – Minggu: 10.00 – 23.00</div>
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
<script>
    function startVideo() {
        const videoId = 'P3yd4BX9aaU';
        const wrap    = document.getElementById('vidWrap');
        wrap.onclick  = null;
        wrap.innerHTML = `<iframe src="https://www.youtube.com/embed/${videoId}?autoplay=1"
            allow="autoplay; encrypted-media" allowfullscreen></iframe>`;
    }
</script>

<script>
    function filterGames(platform) {
        // 1. Ubah status tombol aktif
        const buttons = document.querySelectorAll('.filter-game-btn');
        buttons.forEach(btn => btn.classList.remove('active'));
        event.target.classList.add('active');

        // 2. Filter kartu game
        const cards = document.querySelectorAll('.game-card');
        cards.forEach(card => {
            if (platform === 'all' || card.getAttribute('data-platform') === platform) {
                card.style.display = 'block';
                // Animasi muncul kembali
                card.style.opacity = '0';
                setTimeout(() => { card.style.opacity = '1'; }, 10);
            } else {
                card.style.display = 'none';
            }
        });
    }
</script>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    // Memulai (Inisialisasi) animasi AOS
    AOS.init({
        duration: 800, // Durasi animasi (800ms)
        once: false,   // false = animasi diputar lagi setiap kali discroll
        offset: 100,   // Berapa pixel jarak sebelum elemen terlihat
    });
</script>

</body>
</html>