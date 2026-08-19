<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/logo_dumb.png') }}">
    <title>Booking - Play N Chill</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/booking.css') }}">

    <style>
        body {
            background-color: var(--purple-dark); /* Warna dasar tetap di body */
            position: relative;
            min-height: 100vh;
            margin: 0;
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
            opacity: 0.9; /* Nilai 0.0 (hilang) sampai 1.0 (jelas) */
            
            z-index: -1; /* Memastikan background berada di belakang konten */
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
                    <a class="nav-link" href="{{ url('/') }}">Home</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link nav-btn-active" href="{{ url('/booking') }}">Booking</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/menu-fb') }}">Menu F&B</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/galeri') }}">Galeri</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/tentang-kami') }}">Tentang Kami</a>
                </li>

                <li class="nav-item ms-2">
                    @guest
                        <a class="nav-link nav-btn-active" href="{{ url('/login') }}"
                           style="background-color: var(--orange) !important;">
                            Login
                        </a>
                    @endguest

                    @auth
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
                                        {{ auth()->user()->nama_pengguna }}
                                    </span>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="{{ url('/profile') }}">Profil Saya</a>
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

{{-- ═══ BOOKING PAGE ═══ --}}
<div class="booking-page">

    {{-- Judul --}}
    <h1 class="booking-title">Tentukan Ruangan</h1>

    {{-- Tab Filter --}}
    <div class="booking-tabs">
        <a href="{{ url('/booking?tipe=reguler') }}"
           class="tab-link {{ request('tipe', 'reguler') == 'reguler' ? 'active' : '' }}">
            Reguler
        </a>
        <a href="{{ url('/booking?tipe=private-room') }}"
           class="tab-link {{ request('tipe') == 'private-room' ? 'active' : '' }}">
            Private Room
        </a>
    </div>

    {{-- Room Container --}}
    <div class="room-container">
        <h2 class="room-container-title">Tentukan Nomor Ruangan Favoritmu!</h2>

        <div class="room-grid-booking">
            


            @foreach($rooms as $room)
        <div class="room-card-booking">


        {{-- Badge status --}}
        <span class="room-status {{ $room->tersedia ? 'tersedia' : 'penuh' }}">
            {{ $room->tersedia ? 'Tersedia' : 'Sedang Dipakai' }}
        </span>

        @if($room->galeri)
            <div class="room-foto">
                <img src="{{ asset($room->galeri) }}"
                    alt="{{ $room->nama_ruangan }}">
            </div>
        @else
            <div class="room-icon">
                <svg viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg">
                    <rect x="4"  y="28" width="12" height="16" rx="4"/>
                    <rect x="48" y="28" width="12" height="16" rx="4"/>
                    <rect x="10" y="20" width="44" height="14" rx="5"/>
                    <rect x="16" y="34" width="32" height="12" rx="4"/>
                    <rect x="12" y="44" width="6"  height="8"  rx="2"/>
                    <rect x="46" y="44" width="6"  height="8"  rx="2"/>
                </svg>
            </div>
        @endif

        {{-- Info ruangan --}}
        <div class="room-name">{{ $room->nama_ruangan }}</div>

        {{-- FIX: tampilkan perangkat dari database --}}
        <div class="room-device">{{ $room->perangkat ?? '-' }}</div>

        {{-- Tombol Booking --}}
        @if($room->tersedia)
            @auth
                <a href="{{ url('/booking/paket?room='.$room->id_ruangan.'&tipe='.$tipe) }}"
                class="btn-booking">Booking</a>
            @else
                <a href="{{ url('/login') }}" class="btn-booking">Login dulu</a>
            @endauth
        @else
            <button class="btn-booking disabled" disabled>Sedang Dipakai</button>
        @endif
        </div>
    @endforeach

        </div>{{-- end room-grid-booking --}}
    </div>{{-- end room-container --}}

</div>{{-- end booking-page --}}

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>