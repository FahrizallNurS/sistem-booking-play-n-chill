<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/logo_dumb.png') }}">
    <title>Play N Chill</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }


        :root {
            --purple: #472CA1;
            --purple-btn: #734FFF;
            --purple-dark: #472CA1;
            --yellow: #D7E319; 
            --orange: #E65A29;
            --bg-gradient-btn: linear-gradient(90deg, #a5c312 0%, #c15454 100%); 
        }

        body {
            font-family: 'Nunito', sans-serif;
            overflow-x: hidden;
            background: #472CA1;
        }

        /* ════════════════════════
        NAVBAR
        ════════════════════════ */
        .navbar {
            background: #fff;
            padding: 10px 0;
            box-shadow: 0 1px 10px rgba(0,0,0,.08);
        }

        /* Logo pill outline */
        .logo-pill {
            border: 2px solid var(--purple-dark);
            border-radius: 30px;
            padding: 4px 12px;
            display: inline-flex;
            flex-direction: column;
            align-items: center;
            line-height: 1.1;
            text-decoration: none;
        }

        .logo-pill-top {
            font-family: 'Fredoka One', cursive;
            font-size: .9rem;
            color: var(--purple);
            letter-spacing: .3px;
        }

        .logo-pill-bot {
            font-family: 'Fredoka One', cursive;
            font-size: .5rem;
            color: var(--purple);
            letter-spacing: 2px;
            margin-top: -1px;
        }

        /* "Home" purple pill */
        .nav-btn-active {
            background: var(--purple-dark) !important;
            color: #fff !important;
            border-radius: 20px !important;
            padding: 6px 22px !important;
            font-weight: 700;
            font-size: .88rem;
        }

        .navbar-nav .nav-link {
            font-weight: 700;
            color: #333;
            font-size: .9rem;
            padding: 6px 14px !important;
        }

        .navbar-nav .nav-link:hover { color: var(--purple); }

        /* Avatar */
        .nav-avatar {
            width: 38px; height: 38px;
            border-radius: 50%;
            background: #e5d5f7;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
        }

        .nav-avatar svg { width: 22px; height: 22px; fill: var(--purple); }

        /* ════════════════════════
           HERO
        ════════════════════════ */
        .hero {
            background: var(--purple-dark);
            padding: 68px 16px 92px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .hero-title {
            font-family: 'Fredoka One', cursive;
            font-size: clamp(3rem, 9vw, 5.5rem);
            color: #fff;
            display: inline-flex;
            align-items: center;
            gap: 14px;
            letter-spacing: 0px;
            line-height: 550px;
            
        }

        /* Orange boomerang/arrow icon */
        .hero-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: clamp(52px, 9vw, 76px);
            height: clamp(52px, 9vw, 76px);
            background: var(--orange);
            border-radius: 50%;
            position: relative;
            animation: wobble 3s ease-in-out infinite;
        }

        .hero-icon::before {
            content: 'N';
            font-size: clamp(1.2rem, 3vw, 1.8rem);
            color: #fff;
            line-height: 1;
        }

        @keyframes wobble {
            0%,100% { transform: rotate(-10deg) scale(1); }
            50%      { transform: rotate(10deg) scale(1.08); }
        }

        /* ════════════════════════
           WHITE FLOATING CARD
        ════════════════════════ */
        .white-card {
            background: #fff;
            margin: -32px 0px 0;
            padding: 44px 36px 52px;
            box-shadow: 0 8px 36px rgba(0,0,0,.09);
            position: relative;
            z-index: 5;
            margin-top: -60px; /* Lebih naik ke atas hero */
            border-radius: 20px 20px 0px 0px; /* Lebih bulat */
        }

        .white-card h2 {
            font-family: 'Fredoka One', cursive;
            color: var(--purple-dark);
            font-size: clamp(1.4rem, 3.8vw, 2.2rem);
            text-align: center;
            margin-bottom: 10px;
        }

        .white-card .sub-desc {
            color: #666;
            font-size: .91rem;
            text-align: center;
            max-width: 420px;
            margin: 0 auto 38px;
            line-height: 1.65;
        }

        /* ── Room Cards grid ── */
        .room-grid {
            display: grid;
            grid-template-columns: repeat(3,1fr);
            gap: 20px;
        }

        .r-card {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 3px 18px rgba(92,45,145,.13);
            transition: transform .25s, box-shadow .25s;
            background: var(--purple-dark); /* Background ungu sesuai gambar */
            border: none;
            border-radius: 20px;
            text-align: center;
            color: #fff;
        }

        .r-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 10px 28px rgba(92,45,145,.22);
        }

        /* image panel */
        .r-img {
            width: 100%;
            aspect-ratio: 1.15 / 1;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: flex-end;
            justify-content: center;
            background-size: cover !important;
            background-position: center !important;
            border-radius: 20px 20px 0 0;
        }

        .r-img.gaming  { background: linear-gradient(150deg,#1a004e 0%,#4a1090 50%,#1a004e 100%); }
        .r-img.karaoke { background: linear-gradient(150deg,#060c28 0%,#0e2d6e 50%,#060c28 100%); }
        .r-img.bioskop { background: linear-gradient(150deg,#080820 0%,#1a1860 50%,#080820 100%); }

        /* ambient glow blobs inside each card image */
        .r-img::before {
            content: '';
            position: absolute;
            width: 70%; height: 60%;
            border-radius: 50%;
            filter: blur(28px);
            bottom: 0; left: 15%;
            opacity: .65;
        }

        .r-img.gaming::before  { background: #9b44ff; }
        .r-img.karaoke::before { background: #4488ff; }
        .r-img.bioskop::before { background: #6633cc; }

        /* right-corner badge */
        .r-badge {
            position: absolute;
            top: 8px; right: 8px;
            font-size: 1.05rem;
            background: rgba(255,255,255,.18);
            backdrop-filter: blur(6px);
            border-radius: 7px;
            padding: 4px 7px;
            z-index: 2;
        }

        /* sofa emoji in image */
        .r-sofa {
            font-size: 2.2rem;
            position: absolute;
            bottom: 10px;
            z-index: 2;
            filter: drop-shadow(0 4px 10px rgba(0,0,0,.5));
        }

        /* card footer */
        .r-foot {
            background: var(--purple);
            color: #fff;
            padding: 13px 14px 15px;
            background: transparent; /* Hilangkan background solid di footer kartu */
            
        }

        .r-foot h5 {
            font-family: 'Fredoka One', cursive;
            margin-bottom: 4px;
            letter-spacing: .2px;
            font-size: 1.2rem;
            font-weight: 800;
        }

        .r-foot p {
            font-size: .79rem;
            opacity: .9;
            line-height: 1.5;
            margin: 0;
        }

        /* ════════════════════════
           ROOM TOUR (yellow-green)
        ════════════════════════ */
        .tour-section {
            background: var(--yellow);
            text-align: center;
            position: relative;
            background-size: 100% 100%;
            clip-path: none;
            padding: 32px 24px;
        }

        .tour-section h2 {
            font-family: 'Fredoka One', cursive;
            color: var(--purple);
            font-size: clamp(2rem, 5.5vw, 3.2rem);
            margin-bottom: 6px;
        }

        .tour-section .tour-sub {
            font-weight: 700;
            color: #222;
            font-size: .95rem;
            margin-bottom: 28px;
        }

        .vid-wrap {
            max-width: 620px;
            margin: 0 auto;
            overflow: hidden;
            box-shadow: 0 10px 44px rgba(0,0,0,.28);
            aspect-ratio: 16/9;
            position: relative;
            background: #05000f;
            cursor: pointer;
            border-radius: 10px;
            border: 10px solid #000;
        }

        /* Placeholder room image - warm purple/orange ambience */
        .vid-thumb {
            position: absolute; inset: 0;
            background: #000;
}

        .vid-overlay {
            position: absolute; inset: 0;
            background: rgba(0,0,0,.22);
            display: flex; align-items: center; justify-content: center;
            transition: background .2s;
        }

        .vid-wrap:hover .vid-overlay { background: rgba(0,0,0,.08); }

        .vid-play {
            width: 68px; height: 68px;
            border-radius: 50%;
            background: rgba(255,255,255,.92);
            display: flex; align-items: center; justify-content: center;
            transition: transform .2s;
        }

        .vid-wrap:hover .vid-play { transform: scale(1.1); }

        .vid-play svg { width: 28px; height: 28px; fill: var(--purple); margin-left: 4px; }

        .vid-wrap iframe {
            position: absolute; inset: 0;
            width: 100%; height: 100%;
            border: none;
        }

        /* ════════════════════════
           CTA
        ════════════════════════ */
        .cta-section {
            background: var(--purple-dark);
            padding: 80px 24px 90px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        /* darker blob decorations */
        .cta-blob {
            position: absolute;
            border-radius: 50%;
            background: rgba(0,0,0,.18);
            pointer-events: none;
        }

        .cta-blob-1 { width:340px; height:340px; bottom:-120px; right:-80px; }
        .cta-blob-2 { width:260px; height:260px; top:-80px; left:-60px; }
        .cta-blob-3 { width:180px; height:180px; bottom:20px; left:8%; }

        .cta-section h2 {
            font-family: 'Fredoka One', cursive;
            color: var(--yellow);
            font-size: clamp(2rem, 5.5vw, 3.6rem);
            margin-bottom: 16px;
            position: relative; z-index: 2;
        }

        .cta-section p {
            color: rgba(255,255,255,.83);
            font-size: .95rem;
            max-width: 400px;
            margin: 0 auto 40px;
            line-height: 1.7;
            position: relative; z-index: 2;
        }

        .btn-pesan {
            display: inline-block;
            color: #fff !important;
            font-size: 1.4rem;
            font-family: 'Nunito', sans-serif;
            font-weight: 800;
            padding: 15px 60px;
            border-radius: 50px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.2) ;
            border: none;
            text-decoration: none;
            cursor: pointer;
            transition: transform .2s, box-shadow .2s;
            box-shadow: 0 6px 22px rgba(212,230,0,.32);
            position: relative; z-index: 2;
            background: var(--bg-gradient-btn);
        }

        .btn-pesan:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(212,230,0,.45);
            color: #3a3a00;
        }

        /* ════════════════════════
           FOOTER
        ════════════════════════ */
        footer {
            background: #14083a;
            color: rgba(255,255,255,.75);
            padding: 50px 0 20px;
        }

        .f-logo {
            font-family: 'Fredoka One', cursive;
            font-size: 1.45rem;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .f-logo-circle {
            display: inline-flex;
            align-items: center; justify-content: center;
            width: 28px; height: 28px;
            background: var(--orange);
            border-radius: 50%;
            font-size: .8rem;
        }

        .f-tagline {
            font-size: .8rem;
            color: rgba(255,255,255,.5);
            line-height: 1.7;
            margin-top: 8px;
            max-width: 170px;
        }

        .f-head {
            font-family: 'Fredoka One', cursive;
            font-size: .98rem;
            color: #fff;
            margin-bottom: 13px;
            gap: 4px;
            margin-left: 48px;
            
        }

        .f-list {
            list-style: none; padding: 0; margin-left: 48px;
            display: flex; flex-direction: column; gap: 9px;
        }

        .f-list li {
            display: flex; align-items: flex-start;
            gap: 8px;
            font-size: .8rem;
            color: rgba(255,255,255,.62);
            line-height: 1.55;
            
        }

        .f-list li .fi { flex-shrink: 0; font-size: .88rem; }

        .soc-wrap { display: flex; gap: 9px; }

        .soc-btn {
            width: fit-content; height: 32px;
            padding: 10px;
            border-radius: 7px;
            background: rgba(255,255,255,.12);
            display: flex; align-items: center; justify-content: center;
            text-align: center
            font-size: .88rem;
            color: #fff;
            text-decoration: none;
            transition: background .2s;
        }

        .soc-btn:hover { background: var(--purple); color: #fff; }

        .f-divider { border-color: rgba(255,255,255,.1) !important; margin: 28px 0 14px; }
        .f-copy { font-size: .72rem; color: rgba(255,255,255,.32); text-align: center; }


        /* ════════════════════════
           RESPONSIVE
        ════════════════════════ */
        @media (max-width: 767px) {
            .white-card  { padding: 32px 18px 40px; }
            .room-grid   { grid-template-columns: 1fr; max-width: 300px; margin: 0 auto; }
            .tour-section {
                padding: 58px 16px 68px;
            }
            .cta-section { padding: 60px 16px 70px; }
        }

        @media (max-width: 480px) {
            .hero        { padding: 50px 16px 72px; }
            .cta-section { padding: 52px 14px 60px; }
        }
    </style>
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
                        {{-- Tampilan JIKA BELUM Login --}}
                        <a class="nav-link nav-btn-active" href="{{ url('/login') }}" style="background-color: var(--orange) !important;">
                            Login
                        </a>
                    @endguest

                    @auth
                        {{-- Tampilan JIKA SUDAH Login --}}
                        <div class="dropdown">
                            <div class="nav-avatar" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <svg viewBox="0 0 24 24">
                                    <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/>
                                </svg>
                            </div>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="{{ url('/profile') }}">Profil Saya</a></li>
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

{{-- ═══ HERO ═══ --}}
<section class="hero">
    <div class="hero-title">
        <span>Play</span>
        <span style="color: var(--orange);">N</span>
        <span>Chill</span>
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

        {{-- Karaoke Room --}}
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

        {{-- Private Bioskop --}}
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
                <div class="f-logo">
                    Play N Chill
                </div>
                <p class="f-tagline">Nikmati pengalaman tak terlupakan bersama teman-teman!</p>
            </div>

            {{-- Hubungi Kami --}}
            <div class="col-6 col-sm-3 col-lg-3">
                <div class="f-head">Hubungi Kami</div>
                <ul class="f-list">
                    <li><span class="fi">📞</span><span>+62 812-XXXX-XXXX</span></li>
                    <li><span class="fi">📧</span><span>hello@playchill.id</span></li>
                    <li><span class="fi">📍</span><span>Jl. Margobawero No.46, Mojorejo, Kec. Taman, Kota Madiun, Jawa Timur 63139n</span></li>
                </ul>
            </div>

            {{-- Ikuti Kami --}}
            <div class="col-6 col-sm-3 col-lg-3">
                <div class="f-head">Ikuti Kami</div>
                <div class="soc-wrap">
                    <ul class="f-list">
                    <li><span class="soc-btn" class="fi" href="#" title="YouTube">  ▶ Youtube  </span></li>
                    <li><span class="soc-btn" class="fi" href="#" title="TikTok">  ♫ TikTok  </span></li>
                    <li><span class="soc-btn" class="fi" href="https://share.google/fMbFjkoIuMs0P7Mfh" title="Instagram">  📸 Instagram  </span></li>
                    </ul>
                </div>
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
        // Ganti YOUR_VIDEO_ID dengan ID video YouTube Room Tour kamu
        const videoId = 'P3yd4BX9aaU';
        const wrap    = document.getElementById('vidWrap');
        wrap.onclick  = null;
        wrap.innerHTML = `<iframe src="https://www.youtube.com/embed/${videoId}?autoplay=1"
            allow="autoplay; encrypted-media" allowfullscreen></iframe>`;
    }
</script>
</body>
</html>