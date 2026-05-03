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
        body {
            background-color: #442c94; /* Warna ungu tua sesuai screenshot */
            color: white;
            font-family: 'Nunito', sans-serif;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            position: relative; /* Wajib ditambahkan */
            min-height: 100vh;
        }

        /* Tambahkan blok ini untuk background transparan */
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
            opacity: 0.7; /* Atur tingkat transparansi (0.1 - 0.3 disarankan) */
            z-index: -1; /* Supaya berada di belakang semua elemen */
        }

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
            color: #A855F7; /* Ungu terang untuk kata 'Level' */
        }

        .description-text {
            color: #CBD5E1;
            max-width: 750px;
            margin: 0 auto;
            line-height: 1.8;
            font-size: 1.1rem;
        }

        /* Card Glassmorphism */
        /* Card dengan Background Gambar */
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
            justify-content: flex-end; /* Teks di bawah */
            min-height: 350px; /* Sesuaikan tinggi kartu */
            transition: all 0.3s ease;
            z-index: 1;
        }

        .custom-card::before {
        content: "";
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: linear-gradient(180deg, rgba(36, 20, 68, 0.2) 0%, rgba(36, 20, 68, 0.9) 80%);
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

        /* Banner Bawah (CTA) */
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

        /* bagian tim kami */
        .team-section {
            margin-top: 80px;
            margin-bottom: 40px;
        }

        .team-member-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 30px 15px;
            transition: 0.3s;
            height: 100%;
        }

        .team-member-card:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: #FFD700;
        }

        .avatar-placeholder {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #FFD700, #FFA500);
            color: #241444;
            font-size: 1.8rem;
            font-weight: 800;
            display: flex;
            object-fit: cover;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin: 0 auto 15px;
        }

        .member-role {
            color: #FFD700;
            font-size: 0.8rem;
            text-transform: uppercase;
            font-weight: 600;
        }
        .avatar-img {
            width: 130px;  /* Ukuran diperbesar */
            height: 130px; /* Samakan agar tetap bulat sempurna */
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 20px; /* Jarak ke nama ditambah sedikit */
            border: 4px solid #FFD700; /* Garis emas dipertebal */
            box-shadow: 0 8px 20px rgba(0,0,0,0.4);
            transition: 0.3s ease;
        }

        /* Tambahkan efek zoom sedikit saat kartu di-hover */
        .team-member-card:hover .avatar-img {
            transform: scale(1.05);
        }
    </style>
</head>
<body>

{{-- Navbar --}}
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container-fluid px-4">
            <a class="navbar-brand p-0" href="{{ url('/') }}">
                <img src="{{ asset('images/logo_dumb.png') }}" alt="Play N Chill" height="48">
            </a>
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navMain">
                <ul class="navbar-nav align-items-center gap-1">
                    <li class="nav-item"><a class="nav-link" href="{{ url('/') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/booking') }}">Booking</a></li>
                    <li class="nav-item"><a class="nav-link nav-btn-active" href="{{ url('/tentang-kami') }}">Tentang Kami</a></li>
                    <li class="nav-item ms-2">
                        @guest
                            <a class="nav-link nav-btn-active" href="{{ url('/login') }}" style="background-color: var(--orange) !important;">Login</a>
                        @endguest
                        @auth
                            <div class="dropdown">
                                <div class="nav-avatar" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <svg viewBox="0 0 24 24"><path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/></svg>
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

<div class="container">
    <div class="hero-section">
        <div class="badge-location">Play N Chill • Manguharjo</div>
        <h1 class="main-title">Istirahat Total di <span class="text-purple-light">Level</span> Maksimal</h1>
        <p class="description-text">
            Di Play N Chill, kami percaya waktu luang itu berharga. Kami hadir bukan cuma sebagai tempat main, 
            tapi sebagai tempat kamu bersembunyi sejenak dari rutinitas dan benar-benar menikmati waktu untuk dirimu sendiri.
        </p>
    </div>

    <div class="row justify-content-center mt-5">
        <div class="col-md-11">
            <div class="custom-card">
                <h3 class="fw-bold mb-4"><span class="orange-line"></span>Lahir dari Rasa Bosan</h3>
                <p class="description-text" style="margin-left: 0; text-align: left; max-width: 100%;">
                    Play N Chill didirikan di Manguharjo karena kami bosan dengan tempat nongkrong yang biasa. 
                    Kami ingin tempat di mana privasi benar-benar dihargai, di mana perangkat game-nya selalu terbaru 
                    (PS5 sampai PS6), dan di mana kenyamanannya seperti di rumah. Di sini, kamu adalah raja atas waktumu sendiri.
                </p>
            </div>
        </div>
    </div>

    <div class="text-center mt-5 pt-5">
        <h2 class="section-title">Kenapa Harus Kami?</h2>
        <p class="sub-title">Pengalaman gaming premium yang berbeda</p>
        
        <div class="row g-4">
        <div class="col-md-3">
            <div class="custom-card" style="background-image: url('{{ asset('images/cinema.jpg') }}');">
                <div class="card-icon-box"><i class="fas fa-lock"></i></div>
                <h5 class="fw-bold">Privasi Mutlak</h5>
                <p class="small opacity-75">Nikmati ruangan privat pilihanmu (Reguler, VIP, VVIP) tanpa gangguan siapapun.</p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="custom-card" style="background-image: url('{{ asset('images/gaming.jpg') }}');">
                <div class="card-icon-box"><i class="fas fa-gamepad"></i></div>
                <h5 class="fw-bold">Gaming Terkini</h5>
                <p class="small opacity-75">Rasakan performa konsol next-gen terbaik dengan layar 4K dan audio jernih.</p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="custom-card" style="background-image: url('{{ asset('images/cinema.jpg') }}');">
                <div class="card-icon-box"><i class="fas fa-tv"></i></div>
                <h5 class="fw-bold">Ergonomis & Ambient</h5>
                <p class="small opacity-75">Dari kursi premium hingga pencahayaan yang pas, didesain untuk membuatmu rileks.</p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="custom-card" style="background-image: url('{{ asset('images/karaoke.jpg') }}');">
                <div class="card-icon-box"><i class="fas fa-calendar-alt"></i></div>
                <h5 class="fw-bold">Smart Booking</h5>
                <p class="small opacity-75">Pilih waktu dan ruangan favoritmu dengan mudah lewat sistem digital kami.</p>
            </div>
        </div>
    </div>

        <div class="team-section text-center">
        <h2 class="section-title">Tim Kami</h2>
        <p class="sub-title">Orang-orang di balik pengalaman chill-mu</p>

        <div class="row g-4 justify-content-center">
            <div class="col-6 col-md-3">
                <div class="team-member-card">
                    <img src="{{ asset('images/logo_dumb.png') }}" alt="Nama Member" class="avatar-img">
                    <h6 class="fw-bold mb-1">Nama Member 1</h6>
                    <p class="member-role mb-0">Founder & CEO</p>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="team-member-card">
                    <img src="{{ asset('images/logo_dumb.png') }}" alt="Nama Member" class="avatar-img">
                    <h6 class="fw-bold mb-1">Nama Member 2</h6>
                    <p class="member-role mb-0">Operations Manager</p>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="team-member-card">
                    <img src="{{ asset('images/logo_dumb.png') }}" alt="Nama Member" class="avatar-img">
                    <h6 class="fw-bold mb-1">Nama Member 3</h6>
                    <p class="member-role mb-0">Customer Experience</p>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="team-member-card">
                    <img src="{{ asset('images/logo_dumb.png') }}" alt="Nama Member" class="avatar-img">
                    <h6 class="fw-bold mb-1">Nama Member 4</h6>
                    <p class="member-role mb-0">Technical Support</p>
                </div>
            </div>
        </div>
    </div>

    <div class="cta-banner">
        <h2 class="fw-bold mb-3">Siap untuk recharge energimu?</h2>
        <p class="fs-5">Pilih ruangan favoritmu sekarang dan rasakan pengalaman Chill yang sesungguhnya hanya di Play N Chill.</p>
        <a href="{{ url('/booking') }}" class="btn btn-booking">Booking Sekarang <i class="fas fa-arrow-right ms-2"></i></a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>