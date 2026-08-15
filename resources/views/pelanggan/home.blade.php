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
        /* PERBAIKAN: Spasi pada var(--purple-dark) sudah dirapatkan */
        background-color: var(--purple-dark); 
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

    /* Karakter Wrapper */
    .character-wrapper {
        position: absolute;
        bottom: 0;
        left: 20px; /* Jarak dari kiri layar */
        width: 350px; /* Ukuran desktop */
        height: auto;
        transition: all 0.3s ease;
        z-index: 10;
    }

    /* Gambar karakter di dalamnya mengikuti lebar wrapper */
    .character-wrapper .foreground-char {
        width: 100%;
        height: auto;
        position: relative;
        display: block;
    }

    /* Responsivitas untuk Tablet & HP */
    @media (max-width: 768px) {
        .cta-section {
            display: flex;
            flex-direction: column; /* Menyusun konten dari atas ke bawah */
            align-items: center;
            padding-bottom: 0; /* Menghilangkan padding bawah agar karakter nempel dasar */
        }

        .character-wrapper {
            position: relative !important; /* Tidak lagi melayang di atas tombol */
            bottom: 0;
            left: 0 !important;
            margin: 20px auto 0; /* Beri jarak di bawah tombol */
            width: 220px; /* Ukuran karakter di HP */
            order: 2; /* Memastikan karakter muncul setelah teks/tombol */
        }
        
        .cta-content {
            order: 1;
            margin-left: 0 !important; /* Hilangkan margin kiri agar teks rata tengah */
            width: 100%;
        }

        .item-interaktif {
            width: 55px !important;
            top: -20px !important; 
            left: 56px !important; /* Koordinat mahkota di kepala */
        }
    }

    .navbar.fixed-top {
        position: fixed !important;
        top: 0 !important;
        width: 100% !important;
        z-index: 1030 !important; /* Nilai tinggi agar di atas semua elemen */
        background: rgb(255, 255, 255); /* Beri warna agar tidak transparan total */
        backdrop-filter: blur(10px); /* Efek blur estetik */
    }

    /* ═══ HERO BANNER ═══ */
    .banner-frame {
        position: relative;
        overflow: hidden; /* Biar backdrop blur & inner card ke-clip rapi */
    }

    /* Backdrop: salinan gambar yang sama, di-zoom + blur */
    .banner-backdrop {
        display: none; /* default nonaktif (mobile) */
        position: absolute;
        inset: 0;
        background-size: cover;
        background-position: center;
        filter: blur(28px) brightness(0.55);
        transform: scale(1.15); /* Biar tepi hasil blur tidak kelihatan pudar */
        z-index: 1;
    }

    .wadah-banner-owner {
        width: 100%;
        background-size: cover;
        background-position: center center;
        background-repeat: no-repeat;
        position: relative;
        z-index: 2; /* Selalu di atas backdrop */
    }

    /* Tampilan Desktop (Layar Lebar) */
    @media (min-width: 993px) {
        .wadah-banner-owner {
            height: 450px;
            max-width: 900px; 
        }
    }

    /* Tampilan Tablet */
    @media (max-width: 992px) and (min-width: 577px) {
        .wadah-banner-owner {
            height: 380px;
            max-width: 640px;
        }
    }

    /* Tampilan Mobile / HP */
    @media (max-width: 576px) {
        .wadah-banner-owner {
            height: 270px !important;
        }
    }

    /* Mulai dari tablet ke atas: aktifkan backdrop blur */
    @media (min-width: 577px) {
        .banner-backdrop {
            display: block;
        }

        .wadah-banner-owner {
            margin-left: auto;
            margin-right: auto;
        }
    }

    /* ═══ CSS VIDEO SLIDER (Tunggal & Elegan) ═══ */
    .video-slider-wrapper {
        position: relative;
        width: 100%;
        max-width: 850px; /* Lebar maksimal di desktop agar proporsional */
        margin: 0 auto;
    }

    .video-slider-container {
        display: flex;
        flex-wrap: nowrap;
        overflow-x: auto;
        gap: 1.5rem;
        padding-bottom: 1rem;
        scroll-snap-type: x mandatory; 
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none; 
    }
    
    .video-slider-container::-webkit-scrollbar {
        display: none;
    }

    .video-slider-item {
        /* Selalu tampil 100% (satu video per layar), baik di HP maupun Desktop */
        flex: 0 0 100%; 
        width: 100%;
        scroll-snap-align: center;
        scroll-snap-stop: always;
    }

    /* CSS Khusus Bullet Indicator */
    .slider-indicators {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
        margin-top: 15px;
    }

    .slider-dot {
        width: 10px;
        height: 10px;
        background-color: rgba(216, 184, 255, 0.3);
        border-radius: 50%;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .slider-dot.active {
        background-color: #ffd700;
        width: 30px; 
        border-radius: 10px;
    }

    /* Efek Hover Mewah Tombol Play */
    .btn-play-inline:hover .play-btn-circle {
        transform: scale(1.15);
        background-color: #ffea00 !important;
        box-shadow: 0 0 30px rgba(255, 215, 0, 0.8) !important;
    }
</style>

    <script>
        // Script diletakkan di bawah agar terbaca setelah HTML dimuat
        document.addEventListener('DOMContentLoaded', function() {
            const item = document.getElementById('interactiveItem');
            if (item) {
                item.addEventListener('click', function() {
                    item.classList.add('fall-animation');
                    
                    // Reset setelah 3 detik agar item muncul kembali
                    setTimeout(() => {
                        item.classList.remove('fall-animation');
                    }, 3000);
                });
            }
        });
    </script>

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

                <li class="nav-item ms-2">

                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/menu-fb') }}">Menu F&B</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/galeri') }}">Galeri</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/tentang-kami') }}">Tentang Kami</a>
                </li>

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
<section class="hero p-0" style="background: transparent;">
    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">

        {{-- Indicator --}}
       <div class="carousel-indicators">
            @foreach ($banners as $index => $banner)
                <button type="button"
                        data-bs-target="#heroCarousel"
                        data-bs-slide-to="{{ $index }}"
                        class="{{ $index == 0 ? 'active' : '' }}">
                </button>
            @endforeach
        </div>

        {{-- Slides --}}
        <div class="carousel-inner">
            @forelse ($banners as $index => $banner)
                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}" data-bs-interval="4000">
                    <div class="container-fluid py-2 py-sm-4">
                        <div class="rounded-4 p-2 p-sm-3 banner-frame" style="background: rgb(25, 18, 52); box-shadow: 0 10px 30px rgba(0,0,0,0.3);">

                            {{-- Backdrop blur: salinan gambar yang sama, dizoom & diblur, ngisi ruang kosong kiri-kanan --}}
                            <div class="banner-backdrop" style="background-image: url('{{ asset($banner->file_foto) }}');"></div>

                            {{-- Card dalam: gambar tajam, di-center di atas backdrop --}}
                            <div class="rounded-4 wadah-banner-owner"
                                 style="background-image: url('{{ asset($banner->file_foto) }}');">
                            </div>

                        </div>
                    </div>
                </div>
            @empty
                {{-- Fallback jika admin belum upload banner sama sekali --}}
                <div class="carousel-item active">
                    <div class="container-fluid py-2 py-sm-4">
                        <div class="rounded-4 p-2 p-sm-3" style="background: rgb(25, 18, 52); box-shadow: 0 10px 30px rgba(0,0,0,0.3);">
                            <div class="rounded-4 wadah-banner-owner bg-secondary d-flex justify-content-center align-items-center text-white">
                                <h5>Belum ada banner promo</h5>
                            </div>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

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
    <a href="{{ url('/booking?tipe=vip') }}" class="text-decoration-none">
        <div class="r-card">
            <div class="r-img gaming" style="background-image: url('{{ asset('images/gaming.jpg') }}');">
                <!-- Ganti emoji dengan tag img -->
                <span class="r-badge">
                    <img src="{{ asset('gambar/ic_gem.png') }}" alt="Icon Gaming">
                </span>
            </div>
            <div class="r-foot">
                <h5>Gaming Room</h5>
                <p>PlayStation & Nintendo Switch terbaru dengan koleksi game lengkap</p>
            </div>
        </div>  
    </a>
    
    {{-- Karaoke Room --}}
    <a href="{{ url('/booking?tipe=vip') }}" class="text-decoration-none" data-aos="fade-up" data-aos-delay="200">
        <div class="r-card">
            <div class="r-img karaoke" style="background-image: url('{{ asset('images/karaoke.jpg') }}'); background-size: cover; background-position: center;">
                <span class="r-badge">
                    <img src="{{ asset('gambar/ic_mic.png') }}" alt="Icon Karaoke">
                </span>
            </div>
            <div class="r-foot">
                <h5>Karaoke Room</h5>
                <p>Ruang karaoke privat dengan sound system premium</p>
            </div>
        </div>
    </a>

    {{-- Private Bioskop --}}
    <a href="{{ url('/booking?tipe=vip') }}" class="text-decoration-none" data-aos="fade-up" data-aos-delay="300">
        <div class="r-card">
            <div class="r-img bioskop" style="background-image: url('{{ asset('images/cinema.jpg') }}'); background-size: cover; background-position: center;">
                <span class="r-badge">
                    <img src="{{ asset('gambar/ic_mov.png') }}" alt="Icon Bioskop">
                </span>
            </div>
            <div class="r-foot">
                <h5>Private Bioskop</h5>
                <p>Nikmati film favorit di layar lebar dengan kenyamanan maksimal</p>
            </div>
        </div>
    </a>
    </div>
</div>

{{-- ═══ VIDEO KESERUAN (DINAMIS DARI DATABASE) ═══ --}}
<section class="tour-section" style="overflow: hidden; padding: 60px 0;">
    <h2 data-aos="fade-down" class="font-modak text-center" style="color: #ffd700; margin-bottom: 10px; font-size: 2.5rem;">Keseruan Kami</h2>
    <p class="tour-sub text-center" data-aos="fade-down" data-aos-delay="100" style="color: #d8b8ff; margin-bottom: 40px; padding: 0 15px;">
        Tonton cuplikan aktivitas seru di Play N Chill
    </p>

    <div class="container-fluid px-4 px-md-5">
        <div class="video-slider-wrapper">
            <div class="video-slider-container" id="videoSliderContainer">
                @forelse($videos as $index => $video)
                    @php
                        // Logika untuk mengubah link YouTube biasa menjadi link Embed yang bisa diputar di web
                        $url = $video->{'link-video'};
                        $embedUrl = $url; 
                        if (preg_match('/[\?\&]v=([^\?\&]+)/', $url, $matches)) {
                            $embedUrl = 'https://www.youtube.com/embed/' . $matches[1];
                        } elseif (preg_match('/youtu\.be\/([^\?\&]+)/', $url, $matches)) {
                            $embedUrl = 'https://www.youtube.com/embed/' . $matches[1];
                        }
                    @endphp

                    <div class="video-slider-item" data-aos="fade-up" data-aos-delay="100">
                        <div class="video-card position-relative rounded-4 overflow-hidden" style="aspect-ratio: 16 / 9; box-shadow: 0 10px 30px rgba(0,0,0,0.4); background-color: #1e0a46;">
                            
                            {{-- THUMBNAIL & OVERLAY CUSTOM --}}
                            <div class="video-cover w-100 h-100 position-absolute top-0 start-0" style="z-index: 2; transition: opacity 0.5s ease;">
                                <img src="{{ asset('uploads/videos/' . $video->thumbnail) }}" alt="Thumbnail Video" class="w-100 h-100" style="object-fit: cover;">
                                
                                <div class="video-overlay position-absolute top-0 start-0 w-100 h-100 d-flex flex-column align-items-center justify-content-center" style="background: rgba(30, 10, 70, 0.4);">
                                    
                                    {{-- Tombol 1: Putar Langsung di Web --}}
                                    <button type="button" class="btn-play-inline border-0 p-0 mb-3" onclick="playVideoInline('videoFrame_{{ $index }}', '{{ $embedUrl }}')" style="background: transparent;">
                                        <div class="play-btn-circle d-flex align-items-center justify-content-center" style="width: 75px; height: 75px; background-color: #ffd700; border-radius: 50%; box-shadow: 0 0 20px rgba(255, 215, 0, 0.5); transition: all 0.3s ease;">
                                            <svg viewBox="0 0 24 24" style="width: 38px; height: 38px; fill: #2b0054; margin-left: 5px;">
                                                <path d="M8 5v14l11-7z"/>
                                            </svg>
                                        </div>
                                    </button>

                                    {{-- Tombol 2: Redirect ke YouTube --}}
                                    <a href="{{ $video->{'link-video'} }}" target="_blank" class="badge rounded-pill text-dark text-decoration-none px-3 py-2" style="background-color: #ffd700; font-weight: 700; font-size: 0.8rem; box-shadow: 0 4px 10px rgba(0,0,0,0.3); transition: transform 0.2s;">
                                        Lihat di YouTube &rarr;
                                    </a>
                                </div>
                            </div>

                            {{-- WADAH IFRAME YOUTUBE (Akan diisi otomatis oleh Javascript saat tombol putar diklik) --}}
                            <div class="w-100 h-100 position-absolute top-0 start-0 bg-dark" id="videoFrame_{{ $index }}" style="z-index: 1;">
                            </div>

                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-4 w-100">
                        <p class="mb-0" style="color: #bca0e5; font-size: 1.1rem;">Belum ada video keseruan yang dibagikan.</p>
                    </div>
                @endforelse
            </div>

            {{-- BULLET INDICATORS UNTUK SLIDER --}}
            @if($videos->count() > 0)
            <div class="slider-indicators" id="videoIndicators">
                @foreach($videos as $index => $video)
                    <div class="slider-dot {{ $index == 0 ? 'active' : '' }}"></div>
                @endforeach
            </div>
            @endif

        </div>
    </div>
</section>
    

</section>

<section class="game-section">
    <div class="container-fluid px-4">
        <h2 class="teks-outline font-modak" data-aos="fade-right">Koleksi Game Kami</h2>

       {{-- Navigasi Filter --}}
<div class="game-filter" data-aos="fade-left">
    <button class="filter-game-btn active" onclick="filterGames('all', this)">Semua</button>
    @foreach($perangkats as $perangkat)
        <button class="filter-game-btn" onclick="filterGames('{{ strtolower($perangkat) }}', this)">
            {{ $perangkat }}
        </button>
    @endforeach
</div>


<div class="game-grid" id="gameGrid">
    @forelse($permainans as $index => $permainan)
        @php
            // Ambil semua perangkat dari ruangan yang di-assign ke game ini
            $platforms = $permainan->ruangans
                ->pluck('perangkat')
                ->filter()
                ->unique()
                ->map(fn($p) => strtolower($p))
                ->values()
                ->toArray();
            
            $platformStr = implode(' ', $platforms);
        @endphp

        <div class="game-card"
            data-platform="{{ $platformStr }}"
            data-aos="fade-up"
            data-aos-delay="{{ $index * 50 }}">

            @if($permainan->gambar)
                <img src="{{ asset('storage/' . $permainan->gambar) }}"
                    alt="{{ $permainan->nama_permainan }}"
                    onerror="this.src='{{ asset('images/gallery/room_sample.jpg') }}'">
            @else
                <div style="width:100%;height:200px;background:#2d1b69;display:flex;align-items:center;justify-content:center;border-radius:12px;">
                    <span style="font-size:3rem">🎮</span>
                </div>
            @endif

            <h5>{{ $permainan->nama_permainan }}</h5>
        </div>
    @empty
        <p class="text-white text-center w-100">Belum ada game tersedia.</p>
    @endforelse
</div>
</section>

{{-- ═══ CTA ═══ --}}
<section class="cta-section" id="booking" data-aos="zoom-in-up" style="position: relative; overflow: hidden; min-height: 500px; display: flex; flex-direction: column;">
    
    <!-- Konten Teks -->
    <div class="cta-content" style="position: relative; z-index: 5;">
        <h2 class="teks-outline font-modak">Siap untuk nongkrong Seru?</h2>
        <p>Pesan ruangan Anda sekarang juga dan ciptakan kenangan yang tak terlupakan bersama teman dan keluarga.</p>
        <a href="{{ url('/booking') }}" class="btn-pesan">Pesan Sekarang!</a>
    </div>

    <!-- Wrapper Karakter -->
    <div class="character-wrapper">
        <img src="{{ asset('images/item.png') }}" 
             id="interactiveItem" 
             class="item-interaktif" 
             alt="Item Interaktif"
             style="width: 90px; position: absolute; top: -32px; left: 84px; cursor: pointer; z-index: 11; transform: scaleX(-1) rotate(4deg);">
        
        <img src="{{ asset('images/karakter1.png') }}" class="foreground-char" alt="Karakter Mascot">
    </div>
</section>

<footer>
    <div class="container">
        <div class="row gy-4 pb-2">

           <div class="col-12 col-sm-6 col-lg-3">
                <div class="f-brand-container">
                    <img src="{{ asset('logo/PNCLOGO.jpg') }}" alt="Logo Play N Chill" class="f-logo-round">                   
                    <div class="f-brand-text">
                        <h3 class="f-brand-title">Play N Chill</h3>
                        <p class="f-tagline mb-0">Nikmati pengalaman tak terlupakan bersama teman dan keluarga.</p>
                    </div>
                </div>
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

            <div class="col-6 col-sm-3 col-lg-3 pe-2">
                <div class="f-head">Hubungi Kami</div>
                <ul class="f-list">
                    <li><span class="fi"><img src="{{ asset('gambar/ic_tel.png') }}" alt="Phone"></span><span>+62 857-3532-9227</span></li>
                    <li><span class="fi"><img src="{{ asset('gambar/ic_email.png') }}" alt="Email"></span><span style="word-break: break-word;">playnchillmadiun@gmail.com</span></li>
                    <li>
                        <span class="fi"><img src="{{ asset('gambar/ic_lok.png') }}" alt="Location"></span>
                        <span style="line-height: 1.5;">Jl. Margobawero No.46,<br>Mojorejo, Kec. Taman,<br>Kota Madiun,<br>Jawa Timur 63139</span>
                    </li>
                </ul>
            </div>

            <div class="col-6 col-sm-3 col-lg-3 d-flex justify-content-end justify-content-sm-start">
                <div class="w-100" style="max-width: max-content;">
                    <div class="f-head">Ikuti Kami</div>
                    <ul class="f-list f-soc-list">
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
            </div>

        </div>

        <hr class="f-divider">
        <p class="f-copy">&copy; {{ date('Y') }} Play N Chill Madiun. All rights reserved.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function startVideo() {
        const videoId = '4iSpL_-hO8Y';
        const wrap    = document.getElementById('vidWrap');
        wrap.onclick  = null;
        wrap.innerHTML = `<iframe src="https://www.youtube.com/embed/${videoId}?autoplay=1"
            allow="autoplay; encrypted-media" allowfullscreen></iframe>`;
    }
</script>

<script>
function filterGames(platform, btn) {
    document.querySelectorAll('.filter-game-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    document.querySelectorAll('.game-card').forEach(card => {
        const cardPlatforms = card.getAttribute('data-platform') || '';
        if (platform === 'all' || cardPlatforms.includes(platform)) {
            card.style.display = 'block';
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

<script>
    // ==========================================
    // 1. FUNGSI MEMUTAR VIDEO LANGSUNG DI WEB
    // ==========================================
    function playVideoInline(frameId, embedUrl) {
        const frameContainer = document.getElementById(frameId);
        const coverOverlay = frameContainer.previousElementSibling;
        
        // HENTIKAN SLIDER OTOMATIS SAAT VIDEO DIPUTAR
        // Agar video tidak lari ke samping saat pengunjung sedang menonton
        stopVideoAutoSlide();
        const videoContainer = document.getElementById('videoSliderContainer');
        if (videoContainer) {
            videoContainer.removeEventListener('mouseleave', startVideoAutoSlide);
            videoContainer.removeEventListener('touchend', startVideoAutoSlide);
        }

        // Sembunyikan cover thumbnail buatan kita
        coverOverlay.style.opacity = '0';
        setTimeout(() => { coverOverlay.style.display = 'none'; }, 500);

        // Cek apakah URL sudah memiliki tanda tanya (?) untuk parameter
        let autoPlayUrl = embedUrl.includes('?') ? `${embedUrl}&autoplay=1` : `${embedUrl}?autoplay=1`;

        // Sisipkan Iframe YouTube dengan mode Autoplay yang diperbaiki
        frameContainer.innerHTML = `<iframe width="100%" height="100%" src="${autoPlayUrl}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen style="position:absolute; top:0; left:0; border-radius: 16px;"></iframe>`;
        
        // Naikkan layer iframe ke depan
        frameContainer.style.zIndex = '3';
    }

    // ==========================================
    // 2. LOGIKA AUTO-SLIDE & BULLET INDICATOR
    // ==========================================
    let videoAutoSlideInterval;
    const videoAutoSlideDelay = 4000; // Bergeser otomatis setiap 4 detik

    function startVideoAutoSlide() {
        stopVideoAutoSlide();
        videoAutoSlideInterval = setInterval(moveNextVideoSlide, videoAutoSlideDelay);
    }

    function stopVideoAutoSlide() {
        clearInterval(videoAutoSlideInterval);
    }

    function moveNextVideoSlide() {
        const videoContainer = document.getElementById('videoSliderContainer');
        const videoItems = document.querySelectorAll('.video-slider-item');
        if (!videoContainer || videoItems.length <= 1) return;

        let centerViewport = videoContainer.clientWidth / 2;
        let minDistance = Infinity;
        let currentIndex = -1;

        videoItems.forEach((item, index) => {
            let rect = item.getBoundingClientRect();
            let containerRect = videoContainer.getBoundingClientRect();
            let itemCenter = (rect.left - containerRect.left) + (rect.width / 2);
            let distance = Math.abs(centerViewport - itemCenter);

            if (distance < minDistance) {
                minDistance = distance;
                currentIndex = index;
            }
        });

        let nextIndex = currentIndex + 1;
        if (nextIndex >= videoItems.length) {
            nextIndex = 0;
        }

        let targetItem = videoItems[nextIndex];
        let scrollPos = targetItem.offsetLeft - videoContainer.offsetLeft;
        videoContainer.scrollTo({ left: scrollPos, behavior: 'smooth' });
    }

    document.addEventListener('DOMContentLoaded', function () {
        const videoContainer = document.getElementById('videoSliderContainer');
        const videoDots = document.querySelectorAll('#videoIndicators .slider-dot');
        const videoItems = document.querySelectorAll('.video-slider-item');

        if (videoContainer && videoDots.length > 0) {
            
            // Mulai Auto-Slide saat halaman dimuat
            startVideoAutoSlide();

            // Smart Pause: Berhenti bergeser saat di-hover/disentuh
            videoContainer.addEventListener('mouseenter', stopVideoAutoSlide);
            videoContainer.addEventListener('mouseleave', startVideoAutoSlide);
            videoContainer.addEventListener('touchstart', stopVideoAutoSlide, { passive: true });
            videoContainer.addEventListener('touchend', startVideoAutoSlide, { passive: true });

            // Sinkronisasi saat digeser manual
            videoContainer.addEventListener('scroll', () => {
                let centerViewport = videoContainer.clientWidth / 2;
                let minDistance = Infinity;
                let activeIndex = -1;

                videoItems.forEach((item, index) => {
                    let rect = item.getBoundingClientRect();
                    let containerRect = videoContainer.getBoundingClientRect();
                    let itemCenter = (rect.left - containerRect.left) + (rect.width / 2);
                    let distance = Math.abs(centerViewport - itemCenter);

                    if (distance < minDistance) {
                        minDistance = distance;
                        activeIndex = index;
                    }
                });

                // Nyalakan dot yang aktif
                videoDots.forEach(dot => dot.classList.remove('active'));
                if (activeIndex !== -1 && videoDots[activeIndex]) {
                    videoDots[activeIndex].classList.add('active');
                }
            });

            // Navigasi saat dot diklik
            videoDots.forEach((dot, index) => {
                dot.addEventListener('click', function() {
                    let targetItem = videoItems[index];
                    if (targetItem) {
                        let scrollPos = targetItem.offsetLeft - videoContainer.offsetLeft;
                        videoContainer.scrollTo({ left: scrollPos, behavior: 'smooth' });
                    }
                });
            });
        }
    });
</script>
</body>
</html>