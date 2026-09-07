<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/logo_dumb.png') }}">
    <title>Tentang Kami - Play N Chill</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <style>
        html {
            overflow-x: hidden;
        }

        body {
            background-color: #442c94;
            color: white;
            font-family: 'Nunito', sans-serif;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            position: relative;
            min-height: 100vh;
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
            opacity: 0.7;
            z-index: -1;
        }

        /* HERO */
        .hero-section {
            padding: 100px 0 50px;
            text-align: center;
        }

        .badge-location {
            border: 1px solid #FFD700;
            color: #FFD700;
            padding: 6px 22px;
            border-radius: 50px;
            font-size: 0.85rem;
            display: inline-block;
            margin-bottom: 25px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .main-title {
            font-size: clamp(2.5rem, 5vw, 4rem);
            font-weight: 800;
            margin-bottom: 20px;
        }

        .text-purple-light {
            color: #A855F7;
        }

        .description-text {
            color: #CBD5E1;
            max-width: 750px;
            margin: 0 auto;
            text-align: center;
            line-height: 1.8;
            font-size: 1.1rem;
        }

        /* CARDS */
        .custom-card {
            background-size: cover;
            background-position: center;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            padding: 40px;
            height: 100%;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            min-height: 350px;
            transition: all 0.3s ease;
            z-index: 1;
        }

        .custom-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(
                180deg,
                rgba(36, 20, 68, 0.2) 0%,
                rgba(36, 20, 68, 0.9) 80%
            );
            z-index: -1;
        }

        .custom-card:hover {
            transform: translateY(-10px);
            border-color: #FFD700;
        }

        .card-icon-box {
            border: 1px solid #FFD700;
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            margin-bottom: 20px;
            color: #FFD700;
        }

        /* FEATURES SCROLL */
        .features-scroll-wrap {
            display: flex;
            gap: 16px;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            -webkit-overflow-scrolling: touch;
            padding-bottom: 12px;
        }

        .features-scroll-wrap::-webkit-scrollbar {
            height: 4px;
        }

        .features-scroll-wrap::-webkit-scrollbar-track {
            background: rgba(255,255,255,0.05);
            border-radius: 10px;
        }

        .features-scroll-wrap::-webkit-scrollbar-thumb {
            background: #FFD700;
            border-radius: 10px;
        }

        .features-scroll-wrap .custom-card {
            flex: 0 0 calc(25% - 12px);
            min-width: 200px;
            scroll-snap-align: start;
            height: auto;
            min-height: 350px;
        }

        /* AUDIENCE CATEGORY */
        .audience-title {
            font-family: 'Fredoka One', cursive;
            color: #FFD700;
            letter-spacing: 1px;
            font-size: 1.8rem;
            margin-bottom: 15px;
        }
        
        .audience-img {
            border-radius: 16px;
            border: 3px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }
        
        .audience-card:hover .audience-img {
            border-color: #A855F7;
            transform: scale(1.03);
        }

        /* CTA */
        .cta-banner {
            background: var(--purple);
            border-radius: 35px;
            padding: 70px 40px;
            text-align: center;
            margin-top: 60px;
            margin-bottom: 100px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.4);
        }

        .btn-booking {
            background-color: #FFD700;
            color: #241444 !important;
            font-weight: 700;
            padding: 14px 40px;
            border-radius: 50px;
            border: none;
            text-decoration: none;
            display: inline-block;
            margin-top: 30px;
            transition: 0.3s ease;
            text-transform: uppercase;
        }

        .btn-booking:hover {
            background-color: white;
            transform: scale(1.05);
            box-shadow: 0 0 20px rgba(255, 215, 0, 0.4);
        }

        h2.section-title {
            font-weight: 700;
            margin-bottom: 10px;
        }

        .sub-title {
            color: #94A3B8;
            margin-bottom: 50px;
        }

        @media (max-width: 767px) {
            .hero-section {
                padding: 70px 16px 30px;
            }

            .main-title {
                font-size: clamp(1.8rem, 7vw, 2.5rem);
            }

            .features-scroll-wrap .custom-card {
                flex: 0 0 80vw;
                min-height: 260px;
            }

            .cta-banner {
                padding: 40px 20px;
                border-radius: 20px;
            }
        }
    </style>
</head>

<body>

<!-- Navbar -->
 @include('partials.navbar')

<div class="container">

    <!-- HERO SECTION -->
    <div class="hero-section">
        <div class="badge-location">Play N Chill</div>

        <h1 class="main-title">
            Hiburan <span class="text-purple-light">3-in-1</span> dalam Satu Lokasi
        </h1>

        <p class="description-text">
            Play N Chill adalah pusat hiburan generasi muda dengan konsep 3-in-1 experience. Kami menggabungkan serunya bermain PlayStation, karaoke, dan nyamannya nonton bareng ke dalam satu tempat yang sama!
        </p>
    </div>

    <!-- KATEGORI AUDIENS (COUPLE, GROUP, PARTY) -->
    <div class="row justify-content-center mt-4 text-center">
        <div class="col-12 mb-4">
            <h2 class="section-title">Solusi Melepas Penat</h2>
            <p class="sub-title" style="margin-bottom: 30px;">Ruang kami didesain khusus agar kamu bebas beraktivitas tanpa takut bosan.</p>
        </div>
        
        <!-- CARD COUPLE -->
        <div class="col-md-4 mb-4">
            <div class="audience-card">
                <h3 class="audience-title">Couple</h3>
                
                <!-- Jejeran 3 Foto -->
                <div class="d-flex justify-content-center gap-2 mb-3 px-2">
                    <img src="{{ asset('images/couple-1.png') }}" class="img-fluid rounded audience-img" style="width: 31%; aspect-ratio: 1/1; object-fit: cover;" alt="Couple 1">
                    <img src="{{ asset('images/couple-2.png') }}" class="img-fluid rounded audience-img" style="width: 31%; aspect-ratio: 1/1; object-fit: cover;" alt="Couple 2">
                    <img src="{{ asset('images/couple-3.png') }}" class="img-fluid rounded audience-img" style="width: 31%; aspect-ratio: 1/1; object-fit: cover;" alt="Couple 3">
                </div>

                <p class="text-light small px-2">Quality time berdua bersama pasangan dengan suasana private yang cozy, nyaman, dan romantis.</p>
            </div>
        </div>
        
        <!-- CARD GROUP -->
        <div class="col-md-4 mb-4">
            <div class="audience-card">
                <h3 class="audience-title">Group</h3>
                
                <!-- Jejeran 3 Foto -->
                <div class="d-flex justify-content-center gap-2 mb-3 px-2">
                    <img src="{{ asset('images/group-1.png') }}" class="img-fluid rounded audience-img" style="width: 31%; aspect-ratio: 1/1; object-fit: cover;" alt="Group 1">
                    <img src="{{ asset('images/group-2.png') }}" class="img-fluid rounded audience-img" style="width: 31%; aspect-ratio: 1/1; object-fit: cover;" alt="Group 2">
                    <img src="{{ asset('images/group-3.png') }}" class="img-fluid rounded audience-img" style="width: 31%; aspect-ratio: 1/1; object-fit: cover;" alt="Group 3">
                </div>

                <p class="text-light small px-2">Seru-seruan bareng bestie, mabar PlayStation atau nonton film bareng dalam satu ruangan nyaman.</p>
            </div>
        </div>
        
        <!-- CARD PARTY -->
        <div class="col-md-4 mb-4">
            <div class="audience-card">
                <h3 class="audience-title">Party</h3>
                
                <!-- Jejeran 3 Foto -->
                <div class="d-flex justify-content-center gap-2 mb-3 px-2">
                    <img src="{{ asset('images/party-1.png') }}" class="img-fluid rounded audience-img" style="width: 31%; aspect-ratio: 1/1; object-fit: cover;" alt="Party 1">
                    <img src="{{ asset('images/party-2.png') }}" class="img-fluid rounded audience-img" style="width: 31%; aspect-ratio: 1/1; object-fit: cover;" alt="Party 2">
                    <!-- <img src="{{ asset('images/party-3.png') }}" class="img-fluid rounded audience-img" style="width: 31%; aspect-ratio: 1/1; object-fit: cover;" alt="Party 3"> -->
                </div>

                <p class="text-light small px-2">Kumpul rame-rame bareng teman se-circle, rayakan momen spesial tanpa takut mengganggu orang lain.</p>
            </div>
        </div>
    </div>

    <!-- FASILITAS & LAYANAN KAMI -->
    <div class="text-center mt-5 pt-4">
        <h2 class="section-title">Fasilitas & Layanan Kami</h2>
        <p class="sub-title">Pengalaman hiburan lengkap untuk segala suasana</p>

        <div class="features-scroll-wrap">
            <div class="custom-card" style="background-image: url('{{ asset('images/Reguler-Area.png') }}');">
                <div class="card-icon-box"><i class="fas fa-couch"></i></div>
                <h5 class="fw-bold">Pilihan Ruang Nyaman</h5>
                <p class="small opacity-75">Tersedia Reguler Area dengan beanbag, kipas hingga VIP Room lengkap dengan AC, sofa, soundbar dan 4K TV.</p>
            </div>

            <div class="custom-card" style="background-image: url('{{ asset('images/Playstations.png') }}');">
                <div class="card-icon-box"><i class="fas fa-gamepad"></i></div>
                <h5 class="fw-bold">PlayStation Experience</h5>
                <p class="small opacity-75">Rasakan sensasi mabar dengan konsol terkini, dimanjakan oleh ketajaman layar 4K dan audio yang menggelegar.</p>
            </div>

            <div class="custom-card" style="background-image: url('{{ asset('images/Private-Movie.png') }}');">
                <div class="card-icon-box"><i class="fas fa-tv"></i></div>
                <h5 class="fw-bold">Private Movie/Netflix</h5>
                <p class="small opacity-75">Nonton film atau series favoritmu dalam suasana privat yang super cozy, layaknya bioskop pribadi.</p>
            </div>

            <div class="custom-card" style="background-image: url('{{ asset('images/Private-Karaoke.png') }}');">
                <div class="card-icon-box"><i class="fas fa-microphone"></i></div>
                <h5 class="fw-bold">Private Karaoke</h5>
                <p class="small opacity-75">Pilih lagu andalanmu dan nyanyi sepuasnya bareng teman atau keluarga tanpa khawatir mengganggu orang lain.</p>
            </div>
        </div>
    </div>

    <!-- AKTIVITAS KAMI -->
    <div class="text-center mt-5 pt-5">
        <h2 class="section-title">Aktivitas Kami</h2>
        <p class="sub-title mb-4">Informasi membership eksklusif dan turnamen rutin PlayStation kami.</p>
        
        <div class="row justify-content-center g-4">
            <div class="col-md-5">
                <!-- Ganti nama file gambar sesuai dengan screenshot Loyalty Card yang diberikan klien -->
                <img src="{{ asset('images/loyalty-card.png') }}" class="img-fluid rounded-4 shadow" style="border: 2px solid rgba(255, 255, 255, 0.1);" alt="Loyalty Card Membership">
            </div>
            <div class="col-md-5">
                <!-- Ganti nama file gambar sesuai dengan screenshot PlayStation Tournament yang diberikan klien -->
                <img src="{{ asset('images/ps-tournament.png') }}" class="img-fluid rounded-4 shadow" style="border: 2px solid rgba(255, 255, 255, 0.1);" alt="PlayStation Tournament">
            </div>
        </div>
    </div>

    <!-- CTA BANNER -->
    <div class="cta-banner">
        <h2 class="fw-bold mb-3">Siap untuk recharge energimu?</h2>
        <p class="fs-5">Pilih ruangan favoritmu sekarang dan rasakan pengalaman Chill yang sesungguhnya hanya di Play N Chill.</p>
        <a href="{{ url('/booking') }}" class="btn btn-booking">
            Booking Sekarang <i class="fas fa-arrow-right ms-2"></i>
        </a>
    </div>

</div>

<!-- Footer -->
 @include('partials.footer')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>