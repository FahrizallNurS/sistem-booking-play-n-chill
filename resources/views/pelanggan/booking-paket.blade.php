<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/logo_dumb.png') }}">
    <title>Pilih Paket - Play N Chill</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/booking-paket.css') }}">
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
                    <a class="nav-link" href="{{ url('/gallery') }}">Gallery</a>
                </li>
                <li class="nav-item ms-2">
                    @guest
                        <a class="nav-link nav-btn-active" href="{{ url('/login') }}"
                           style="background-color: var(--orange) !important;">Login</a>
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
                                        {{ auth()->user()->name }}
                                    </span>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ url('/profile') }}">Profil Saya</a></li>
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

{{-- ═══ PAKET PAGE ═══ --}}
<div class="paket-page">

    {{-- Hero Judul --}}
    <div class="paket-hero">
        <h1>Booking Sekarang</h1>
        <p>Reservasi paket favoritmu jadi lebih praktis, ikuti langkah mudahnya dan pesan sekarang</p>
    </div>

    {{-- ════════════════════════
         SECTION: PLAYSTATION
    ════════════════════════ --}}
    <div class="paket-section ps">
        <h2 class="paket-section-title">Playstation</h2>

        <div class="paket-grid">

            {{-- Paket Couple --}}
            <div class="paket-card">
                <div class="paket-card-name">Paket Couple</div>
                <div class="paket-card-sub">VIP PlayStation3</div>
                <div class="paket-card-price">Rp 7.000</div>
                <div class="paket-card-meta">
                    <span>🕐 Durasi 1 jam</span>
                    <span>👤 1 - 2 Orang</span>
                </div>
                <hr class="paket-divider">
                <ul class="paket-features">
                    <li>Nintendo Switch</li>
                    <li>AC & WiFi</li>
                    <li>VIP/VVIP</li>
                    <li>4K TV</li>
                </ul>
                <a href="{{ url('/booking/form?paket=ps-couple&room='.$room['id'].'&tipe='.$tipe) }}"
                   class="btn-pilih-paket">Pilih Paket</a>
            </div>

            {{-- Paket Group (featured) --}}
            <div class="paket-card featured">
                <div class="paket-card-name">Paket Group</div>
                <div class="paket-card-sub">VIP PlayStation4</div>
                <div class="paket-card-price">Rp 45.000</div>
                <div class="paket-card-meta">
                    <span>🕐 Durasi 2 jam</span>
                    <span>👤 1 - 4 Orang</span>
                </div>
                <hr class="paket-divider">
                <ul class="paket-features">
                    <li>Nintendo Switch</li>
                    <li>AC & WiFi</li>
                    <li>VIP/VVIP</li>
                    <li>4K TV</li>
                </ul>
                <a href="{{ url('/booking/form?paket=ps-group&room='.$room['id'].'&tipe='.$tipe) }}"
                   class="btn-pilih-paket">Pilih Paket</a>
            </div>

            {{-- Paket Party --}}
            <div class="paket-card">
                <div class="paket-card-name">Paket Party</div>
                <div class="paket-card-sub">VIP PlayStation5</div>
                <div class="paket-card-price">Rp 125.000</div>
                <div class="paket-card-meta">
                    <span>🕐 Durasi 3 jam</span>
                    <span>👤 1 - 6 Orang</span>
                </div>
                <hr class="paket-divider">
                <ul class="paket-features">
                    <li>Nintendo Switch</li>
                    <li>AC & WiFi</li>
                    <li>VIP/VVIP</li>
                    <li>4K TV</li>
                </ul>
                <a href="{{ url('/booking/form?paket=ps-party&room='.$room['id'].'&tipe='.$tipe) }}"
                   class="btn-pilih-paket">Pilih Paket</a>
            </div>

        </div>
    </div>

    {{-- ════════════════════════
         SECTION: PRIVATE KARAOKE
    ════════════════════════ --}}
    <div class="paket-section karaoke">
        <h2 class="paket-section-title">Private Karaoke</h2>

        <div class="paket-grid">

            {{-- Paket Couple --}}
            <div class="paket-card">
                <div class="paket-card-name">Paket Couple</div>
                <div class="paket-card-sub">VIP PlayStation4<br>Senin - Kamis</div>
                <div class="paket-card-price">Rp 30.000</div>
                <div class="paket-card-meta">
                    <span>🕐 Durasi per jam</span>
                    <span>👤 1 - 2 Orang</span>
                </div>
                <hr class="paket-divider">
                <ul class="paket-features">
                    <li>VIP Room</li>
                    <li>AC</li>
                    <li>Sofa / Beanbag</li>
                    <li>4K TV</li>
                    <li>Soundbar</li>
                </ul>
                <a href="{{ url('/booking/form?paket=karaoke-couple&room='.$room['id'].'&tipe='.$tipe) }}"
                   class="btn-pilih-paket">Pilih Paket</a>
            </div>

            {{-- Paket Group (featured) --}}
            <div class="paket-card featured">
                <div class="paket-card-name">Paket Group</div>
                <div class="paket-card-sub">VIP PlayStation4<br>Senin - Kamis</div>
                <div class="paket-card-price">Rp 50.000</div>
                <div class="paket-card-meta">
                    <span>🕐 Durasi per jam</span>
                    <span>👤 1 - 4 Orang</span>
                </div>
                <hr class="paket-divider">
                <ul class="paket-features">
                    <li>VIP Room</li>
                    <li>AC</li>
                    <li>Sofa / Beanbag</li>
                    <li>4K TV</li>
                    <li>Soundbar</li>
                </ul>
                <a href="{{ url('/booking/form?paket=karaoke-group&room='.$room['id'].'&tipe='.$tipe) }}"
                   class="btn-pilih-paket">Pilih Paket</a>
            </div>

            {{-- Paket Party --}}
            <div class="paket-card">
                <div class="paket-card-name">Paket Party</div>
                <div class="paket-card-sub">VIP PlayStation4<br>Jumat - Minggu</div>
                <div class="paket-card-price">Rp 85.000</div>
                <div class="paket-card-meta">
                    <span>🕐 Durasi per jam</span>
                    <span>👤 1 - 6 Orang</span>
                </div>
                <hr class="paket-divider">
                <ul class="paket-features">
                    <li>VIP Room</li>
                    <li>AC</li>
                    <li>Sofa / Beanbag</li>
                    <li>4K TV</li>
                    <li>Soundbar</li>
                </ul>
                <a href="{{ url('/booking/form?paket=karaoke-party&room='.$room['id'].'&tipe='.$tipe) }}"
                   class="btn-pilih-paket">Pilih Paket</a>
            </div>

        </div>
    </div>

    {{-- ════════════════════════
         SECTION: BIOSKOP
    ════════════════════════ --}}
    <div class="paket-section bioskop">
        <h2 class="paket-section-title">Bioskop</h2>

        <div class="paket-grid">

            {{-- Paket Couple --}}
            <div class="paket-card">
                <div class="paket-card-name">Paket Couple</div>
                <div class="paket-card-sub">VIP PlayStation4<br>Senin - Kamis</div>
                <div class="paket-card-price">Rp 65.000</div>
                <div class="paket-card-meta">
                    <span>🕐 Durasi per jam</span>
                    <span>👤 1 - 2 Orang</span>
                </div>
                <hr class="paket-divider">
                <ul class="paket-features">
                    <li>VIP Room</li>
                    <li>AC</li>
                    <li>Sofa / Beanbag</li>
                    <li>4K TV</li>
                    <li>Set Karaoke</li>
                    <li>2 Mic</li>
                </ul>
                <a href="{{ url('/booking/form?paket=bioskop-couple&room='.$room['id'].'&tipe='.$tipe) }}"
                   class="btn-pilih-paket">Pilih Paket</a>
            </div>

            {{-- Paket Group (featured) --}}
            <div class="paket-card featured">
                <div class="paket-card-name">Paket Group</div>
                <div class="paket-card-sub">VIP PlayStation4<br>Senin - Kamis</div>
                <div class="paket-card-price">Rp 100.000</div>
                <div class="paket-card-meta">
                    <span>🕐 Durasi per jam</span>
                    <span>👤 1 - 4 Orang</span>
                </div>
                <hr class="paket-divider">
                <ul class="paket-features">
                    <li>VIP Room</li>
                    <li>AC</li>
                    <li>Sofa / Beanbag</li>
                    <li>4K TV</li>
                    <li>Set Karaoke</li>
                    <li>2 Mic</li>
                </ul>
                <a href="{{ url('/booking/form?paket=bioskop-group&room='.$room['id'].'&tipe='.$tipe) }}"
                   class="btn-pilih-paket">Pilih Paket</a>
            </div>

            {{-- Paket Party --}}
            <div class="paket-card">
                <div class="paket-card-name">Paket Party</div>
                <div class="paket-card-sub">VIP PlayStation4<br>Jumat - Minggu</div>
                <div class="paket-card-price">Rp 165.000</div>
                <div class="paket-card-meta">
                    <span>🕐 Durasi per jam</span>
                    <span>👤 1 - 6 Orang</span>
                </div>
                <hr class="paket-divider">
                <ul class="paket-features">
                    <li>VIP Room</li>
                    <li>AC</li>
                    <li>Sofa / Beanbag</li>
                    <li>4K TV</li>
                    <li>Set Karaoke</li>
                    <li>2 Mic</li>
                </ul>
                <a href="{{ url('/booking/form?paket=bioskop-party&room='.$room['id'].'&tipe='.$tipe) }}"
                   class="btn-pilih-paket">Pilih Paket</a>
            </div>

        </div>
    </div>

</div>{{-- end paket-page --}}

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>