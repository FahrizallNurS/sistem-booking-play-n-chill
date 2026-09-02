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
            text-align: justify;
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

        .orange-line {
            width: 50px;
            height: 4px;
            background-color: #FFD700;
            display: inline-block;
            margin-right: 15px;
            vertical-align: middle;
            border-radius: 2px;
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

        /* CTA */
        .cta-banner {
            background: var(--purple);
            border-radius: 35px;
            padding: 70px 40px;
            text-align: center;
            margin-top: 100px;
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

            .description-text {
                font-size: 0.95rem;
            }

            .features-scroll-wrap .custom-card {
                flex: 0 0 75vw;
                min-height: 260px;
            }

            .cta-banner {
                padding: 40px 20px;
                margin-top: 50px;
                margin-bottom: 50px;
                border-radius: 20px;
            }

            .btn-booking {
                padding: 12px 28px;
                font-size: 0.9rem;
            }
        }
    </style>
</head>

<body>

<!-- Navbar -->
 @include('partials.navbar')

<div class="container">

    <div class="hero-section">
        <div class="badge-location">Play N Chill</div>

        <h1 class="main-title">
            Istirahat Total di
            <span class="text-purple-light">Level</span>
            Maksimal
        </h1>

        <p class="description-text">
            Di Play N Chill, kami percaya waktu luang itu berharga.
            Kami hadir bukan cuma sebagai tempat main,
            tapi sebagai tempat kamu bersembunyi sejenak dari rutinitas
            dan benar-benar menikmati waktu untuk dirimu sendiri.
        </p>
    </div>

    <div class="row justify-content-center mt-5">
        <div class="col-md-11">
            <div class="custom-card">
                <h3 class="fw-bold mb-4">
                    <span class="orange-line"></span>
                    Lahir dari Rasa Bosan
                </h3>

                <p class="description-text"
                   style="margin-left: 0; text-align: left; max-width: 100%;">
                    Play N Chill hadir di Madiun sebagai tempat hiburan all-in-one
                    yang berbeda dari yang lain.
                    Kami bukan sekadar tempat main biasa,
                    kami adalah ruang di mana kamu bisa benar-benar melepas penat
                    dan menikmati waktu terbaikmu.
                </p>
            </div>
        </div>
    </div>

    <div class="text-center mt-5 pt-5">

        <h2 class="section-title">Kenapa Harus Kami?</h2>
        <p class="sub-title">Pengalaman gaming premium yang berbeda</p>

        <div class="features-scroll-wrap">

            <div class="custom-card"
                 style="background-image: url('{{ asset('images/privateroom.jpg') }}');">
                <div class="card-icon-box">
                    <i class="fas fa-lock"></i>
                </div>

                <h5 class="fw-bold">Privasi Mutlak</h5>

                <p class="small opacity-75">
                    Semua ruangan kami dirancang agar kamu bisa menikmati waktu
                    tanpa gangguan siapapun.
                </p>
            </div>

            <div class="custom-card"
                 style="background-image: url('{{ asset('images/gaming.jpg') }}');">
                <div class="card-icon-box">
                    <i class="fas fa-gamepad"></i>
                </div>

                <h5 class="fw-bold">Gaming Terkini</h5>

                <p class="small opacity-75">
                    Rasakan sensasi bermain di konsol PS3 hingga PS5 terbaru
                    dengan layar 4K dan audio maksimal.
                </p>
            </div>

            <div class="custom-card"
                 style="background-image: url('{{ asset('images/cinema.jpg') }}');">
                <div class="card-icon-box">
                    <i class="fas fa-tv"></i>
                </div>

                <h5 class="fw-bold">Private Bioskop</h5>

                <p class="small opacity-75">
                    Nonton film favorit dalam ruangan privat
                    dengan layar besar dan suara cinema.
                </p>
            </div>

            <div class="custom-card"
                 style="background-image: url('{{ asset('images/karaoke.jpg') }}');">
                <div class="card-icon-box">
                    <i class="fas fa-microphone"></i>
                </div>

                <h5 class="fw-bold">Karaoke Seru</h5>

                <p class="small opacity-75">
                    Ajak teman atau keluarga bernyanyi bareng
                    di ruang karaoke privat kami.
                </p>
            </div>

        </div>
    </div>

    <div class="cta-banner">
        <h2 class="fw-bold mb-3">
            Siap untuk recharge energimu?
        </h2>

        <p class="fs-5">
            Pilih ruangan favoritmu sekarang dan rasakan pengalaman Chill
            yang sesungguhnya hanya di Play N Chill.
        </p>

        <a href="{{ url('/booking') }}" class="btn btn-booking">
            Booking Sekarang
            <i class="fas fa-arrow-right ms-2"></i>
        </a>
    </div>

</div>

<!-- Footer -->
 @include('partials.footer')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>