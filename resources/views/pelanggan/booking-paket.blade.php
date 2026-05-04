<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/logo_dumb.png') }}">
    <title>Booking Sekarang - Play N Chill</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/booking-paket.css') }}">
</head>
<body>

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
                                        {{ auth()->user()->nama_pengguna }}
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

{{-- HERO --}}
<div class="paket-hero">
    <h1>Pilih Paket</h1>
    <p>Ruangan: <strong>{{ $room->nama_ruangan }}</strong> | Kategori: <strong>{{ $room->kategori }}</strong></p>
</div>

<section class="paket-section section-ps">
    <div class="container">
        @if($penetapanHarga->isEmpty())
            <div class="alert alert-warning text-center">
                Belum ada paket tersedia untuk ruangan ini.
            </div>
        @else
            <div class="paket-grid">
                @foreach($penetapanHarga as $paketId => $items)
                    @php
                        $paket = $items->first()->paket;
                        $kategoriClass = strtolower($room->kategori); // regular, vip, vvip
                    @endphp
                        <div class="paket-card paket-card-{{ $kategoriClass }}">
                 
                        {{-- Nama Paket --}}
                        <div class="paket-card-name">{{ $paket->nama_paket }}</div>

                        {{-- Sub --}}
                        <p class="paket-card-sub">{{ $room->nama_ruangan }}</p>

                        {{-- Harga per durasi --}}
                        @foreach($items as $ph)
                            <div class="paket-card-price">
                                Rp {{ number_format($ph->harga, 0, ',', '.') }}
                                <small class="text-muted" style="font-size:12px">
                                    / {{ $ph->durasi_jam }} jam
                                    ({{ $ph->tipe_hari }})
                                </small>
                            </div>
                        @endforeach

                        {{-- Meta --}}
                        <div class="paket-card-meta">
                            <span>👥 Max {{ $paket->maksimal_orang }} Orang</span>
                        </div>

                        {{-- Deskripsi sebagai list --}}
                        @if($paket->deskripsi_paket)
                            <ul class="paket-features">
                                @foreach(explode("\n", $paket->deskripsi_paket) as $item)
                                    @if(trim($item))
                                        <li>{{ trim($item) }}</li>
                                    @endif
                                @endforeach
                            </ul>
                        @endif

                        {{-- Button --}}
                        <a href="{{ url('/booking/form?room='.$roomId.'&tipe='.$tipe.'&paket='.$paket->id_paket) }}"
                           class="btn-pilih-paket">Pilih Paket</a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>

<div style="background-color: #472CA1; padding: 40px 0; text-align: center;">
    <a href="{{ url('/booking?tipe='.$tipe) }}" class="btn-back-ruangan">
        Kembali Pilih Ruangan
    </a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>