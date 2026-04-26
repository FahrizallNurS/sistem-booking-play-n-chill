<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/logo_dumb.png') }}">
    <title>Status Pesanan - Play N Chill</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/status.css') }}">
</head>

<body class="pb-5">

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

<div class="container pb-5">

    <div class="text-center mb-5">
        <h2 class="display-5 text-white" style="font-family: 'Fredoka One';">
            PESANAN SAYA 📋
        </h2>
        <p class="text-white-50">Daftar semua booking kamu di Play N Chill</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success text-center">{{ session('success') }}</div>
    @endif

    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">

            @forelse($bookings as $booking)
                @php
                    $ph = $booking->penetapanHarga;

                    $badgeSewa = match($booking->status_sewa) {
                        'dikonfirmasi' => ['color' => 'text-success', 'icon' => '✅', 'label' => 'Dikonfirmasi'],
                        'dibatalkan'   => ['color' => 'text-danger',  'icon' => '❌', 'label' => 'Dibatalkan'],
                        'selesai'      => ['color' => 'text-primary', 'icon' => '🏁', 'label' => 'Selesai'],
                        default        => ['color' => 'text-warning', 'icon' => '⏳', 'label' => 'Menunggu Konfirmasi'],
                    };

                    $badgeBayar = match($booking->status_pembayaran) {
                        'lunas'    => ['color' => 'text-success', 'icon' => '💰', 'label' => 'Lunas'],
                        'dp'       => ['color' => 'text-info',    'icon' => '💳', 'label' => 'DP'],
                        default    => ['color' => 'text-warning', 'icon' => '⏳', 'label' => 'Menunggu Pembayaran'],
                    };
                @endphp

                <div class="glass-card mb-4">

                    {{-- Kode Sewa + Status --}}
                    <div class="text-center py-4">
                        <span class="section-badge">Kode Sewa</span>
                        <h1 class="booking-id-text text-white mt-3">{{ $booking->kode_sewa }}</h1>

                        <div class="d-flex justify-content-center gap-3 mt-2 flex-wrap">
                            {{-- Status Sewa --}}
                            <div class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-4 shadow-sm"
                                style="background: rgba(255,255,255,0.1)">
                                <span class="fw-bold {{ $badgeSewa['color'] }}">
                                    {{ $badgeSewa['icon'] }} {{ $badgeSewa['label'] }}
                                </span>
                            </div>

                            {{-- Status Bayar --}}
                            <div class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-4 shadow-sm"
                                style="background: rgba(255,255,255,0.1)">
                                <span class="fw-bold {{ $badgeBayar['color'] }}">
                                    {{ $badgeBayar['icon'] }} {{ $badgeBayar['label'] }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Detail --}}
                    <div class="p-4 pt-0">
                        <div class="row g-3">

                            <div class="col-md-6">
                                <div class="detail-item shadow-sm">
                                    <div class="detail-icon">🛋️</div>
                                    <div>
                                        <p class="small fw-bold text-uppercase mb-1">Ruangan</p>
                                        <p class="fw-bold h6 mb-0">{{ $ph->ruangan->nama_ruangan ?? '-' }}</p>
                                        <p class="small text-white-50 mb-0">{{ $ph->ruangan->kategori ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="detail-item shadow-sm">
                                    <div class="detail-icon">🎮</div>
                                    <div>
                                        <p class="small fw-bold text-uppercase mb-1">Paket</p>
                                        <p class="fw-bold h6 mb-0">{{ $ph->paket->nama_paket ?? '-' }}</p>
                                        <p class="small text-white-50 mb-0">{{ $ph->durasi_jam ?? '-' }} Jam</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="detail-item shadow-sm">
                                    <div class="detail-icon">📅</div>
                                    <div>
                                        <p class="small fw-bold text-uppercase mb-1">Waktu Mulai</p>
                                        <p class="fw-bold h6 mb-0">
                                            {{ \Carbon\Carbon::parse($booking->waktu_mulai)->format('d/m/Y') }}
                                        </p>
                                        <p class="small text-primary fw-bold mb-0">
                                            {{ \Carbon\Carbon::parse($booking->waktu_mulai)->format('H:i') }}
                                            —
                                            {{ \Carbon\Carbon::parse($booking->waktu_selesai)->format('H:i') }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="detail-item shadow-sm">
                                    <div class="detail-icon">💵</div>
                                    <div>
                                        <p class="small fw-bold text-uppercase mb-1">Pembayaran</p>
                                        <p class="fw-bold h6 mb-0">
                                            Rp {{ number_format($booking->total_harga, 0, ',', '.') }}
                                        </p>
                                        @if($booking->opsi_pembayaran === 'dp' && $booking->sisa_bayar > 0)
                                            <p class="small text-warning mb-0">
                                                Sisa: Rp {{ number_format($booking->sisa_bayar, 0, ',', '.') }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                        </div>

                        {{-- Catatan --}}
                        @if($booking->catatan_pembayaran)
                            <div class="mt-3 p-3 rounded-3" style="background: rgba(255,255,255,0.08)">
                                <p class="small fw-bold text-uppercase text-white-50 mb-1">Catatan Admin</p>
                                <p class="text-white mb-0">{{ $booking->catatan_pembayaran }}</p>
                            </div>
                        @endif

                        {{-- WA Button — hanya kalau masih menunggu --}}
                        @if($booking->status_sewa === 'ditahan')
                            <a href="https://wa.me/628123456789?text=Halo admin, saya konfirmasi booking {{ $booking->kode_sewa }}"
                                class="btn-wa-confirm mt-4 d-block text-center">
                                KONFIRMASI PEMBAYARAN
                                <i class="fa-brands fa-whatsapp fs-5 ms-2 text-success"></i>
                            </a>
                        @endif
                    </div>

                </div>

            @empty
                <div class="glass-card text-center py-5">
                    <p class="text-white fs-5">Kamu belum punya booking.</p>
                    <a href="{{ url('/booking') }}" class="btn btn-warning mt-2">Booking Sekarang</a>
                </div>
            @endforelse

            <a href="{{ url('/') }}"
                class="text-white text-center d-block opacity-75 fw-bold text-decoration-none mt-3">
                ← Kembali ke Beranda
            </a>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>