<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/logo_dumb.png') }}">
    <title>Play N Chill</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

{{-- ═══ NAVBAR ═══ --}}
<nav class="navbar navbar-expand-lg sticky-top">
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
                    <a class="nav-link" href="{{ url('/gallery') }}">Gallery</a>
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
<section class="hero p-0">
    <div id="heroCarousel" class="carousel slide carousel-fade"
        data-bs-ride="carousel" data-bs-touch="true">

        <div class="carousel-indicators">
            @for ($i = 0; $i < 10; $i++)
                <button type="button" data-bs-target="#heroCarousel"
                        data-bs-slide-to="{{ $i }}"
                        class="{{ $i == 0 ? 'active' : '' }}"
                        aria-current="{{ $i == 0 ? 'true' : 'false' }}">
                </button>
            @endfor
        </div>

        <div class="carousel-inner">
            @for ($i = 1; $i <= 10; $i++)
                <div class="carousel-item {{ $i == 1 ? 'active' : '' }}" data-bs-interval="3000">
                    @if ($i == 1)
                        {{-- Slide 1: Teks Play N Chill --}}
                        <div class="hero-slide-img" style="background-color: var(--purple);">
                            <div class="hero-overlay">
                                <div class="hero-title">
                                    <span>Play</span>
                                    <span style="color: var(--orange); margin: 0 15px;">N</span>
                                    <span>Chill</span>
                                </div>
                            </div>
                        </div>
                    @else
                        {{-- Slide 2-10: Foto --}}
                        <div class="hero-slide-img"
                            style="background-image: url('{{ asset("images/banner/slide{$i}.jpg") }}')"
                        </div>
                    @endif
                </div>
            @endfor
        </div>

        <button class="carousel-control-prev" type="button"
                data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button"
                data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</section>

{{-- ═══ SEMUA DALAM SATU TEMPAT ═══ --}}
<div class="white-card">
    <h2>Semua ada dalam Satu Tempat</h2>
    <p class="sub-desc">
        Nikmati tiga jenis hiburan berbeda tanpa perlu pindah tempat. Hemat waktu, maksimalkan keseruan!
    </p>

    <div class="room-grid">

        {{-- Gaming Room --}}
        <a href="{{ url('/gallery?category=gaming') }}" class="text-decoration-none">
            <div class="r-card">
                <div class="r-img gaming">
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
        <a href="{{ url('/gallery?category=karaoke') }}" class="text-decoration-none">
            <div class="r-card">
                <div class="r-img karaoke">
                    <span class="r-badge">🎤</span>
                    <span class="r-sofa">🛋️</span>
                </div>
                <div class="r-foot">
                    <h5>Karaokke Room</h5>
                    <p>Ruang karaoke privat dengan sound system premium</p>
                </div>
            </div>
        </a>

        {{-- Private Bioskop --}}
        <a href="{{ url('/gallery?category=bioskop') }}" class="text-decoration-none">
            <div class="r-card">
                <div class="r-img bioskop">
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
    <h2>Room Tour</h2>
    <p class="tour-sub">Rasakan pengalaman seru di Play N Chill melalui video tour kami</p>

    <div class="vid-wrap" id="vidWrap" onclick="startVideo()">
        <div class="vid-thumb"></div>
        <div class="vid-overlay">
            <div class="vid-play">
                <svg viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
            </div>
        </div>
    </div>
</section>

{{-- ═══ CTA ═══ --}}
<section class="cta-section" id="booking">
    <div class="cta-blob cta-blob-1"></div>
    <div class="cta-blob cta-blob-2"></div>
    <div class="cta-blob cta-blob-3"></div>
    <h2>Siap untuk nongkrong Seru?</h2>
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
                    <li><span class="fi">📞</span><span>+62 812-XXXX-XXXX</span></li>
                    <li><span class="fi">📧</span><span>hello@playchill.id</span></li>
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

</body>
</html>