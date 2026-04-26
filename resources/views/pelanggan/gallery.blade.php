<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/logo_dumb.png') }}">
    <title>Gallery Room - Play N Chill</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/gallery.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
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
                    <li class="nav-item"><a class="nav-link nav-btn-active" href="{{ url('/gallery') }}">Gallery</a></li>
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

        <div class="gallery-container">
            {{-- Dekorasi Segitiga Kuning --}}

            <h1>Gallery Room</h1>
            <p>Jelajahi ruangan dengan fasilitas premium sesuai kebutuhan Anda</p>

            {{-- Navigasi Filter --}}
            <div class="filter-nav">
                <a href="{{ url('/gallery?type=reguler') }}" class="filter-link {{ request('type') == 'reguler' || !request('type') ? 'active' : '' }}">Reguler</a>
                <a href="{{ url('/gallery?type=vip') }}" class="filter-link {{ request('type') == 'vip' ? 'active' : '' }}">VIP</a>
                <a href="{{ url('/gallery?type=vvip') }}" class="filter-link {{ request('type') == 'vvip' ? 'active' : '' }}">VVIP</a>
            </div>

            <div class="gallery-box">
        @if($ruangans->isEmpty())
            <div class="text-center py-5">
                <p style="color: rgba(255,255,255,0.5); font-size: 1.1rem;">
                    Belum ada ruangan tersedia untuk kategori ini.
                </p>
            </div>
        @else
            <div class="gallery-grid">
                @foreach($ruangans as $ruangan)
                    <div class="gallery-item-wrapper">
                        @if($ruangan->galeri)
                            <img src="{{ asset('storage/' . $ruangan->galeri) }}"
                                class="gallery-item"
                                alt="{{ $ruangan->nama_ruangan }}"
                                onerror="this.src='{{ asset('images/gallery/room_sample.jpg') }}'">
                        @else
                            <div class="gallery-placeholder">
                                <span>🎮</span>
                                <p>{{ $ruangan->nama_ruangan }}</p>
                            </div>
                        @endif

                        {{-- Overlay info ruangan --}}
                        <div class="gallery-overlay">
                            <h5>{{ $ruangan->nama_ruangan }}</h5>
                            <p>{{ $ruangan->perangkat ?? '-' }}</p>
                            <p class="small">{{ $ruangan->deskripsi ?? '' }}</p>
                            @auth
                                <a href="{{ url('/booking?tipe='.strtolower($type)) }}" 
                                    class="btn-gallery-booking">Booking</a>
                            @else
                                <a href="{{ url('/login') }}" 
                                    class="btn-gallery-booking">Login dulu</a>
                            @endauth
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>