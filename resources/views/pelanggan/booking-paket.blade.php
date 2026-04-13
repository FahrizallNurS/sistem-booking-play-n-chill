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

{{-- NAVBAR --}}
<nav class="navbar navbar-expand-lg sticky-top bg-white shadow-sm">
    <div class="container-fluid px-4">
        <a class="navbar-brand p-0" href="{{ url('/') }}">
            <img src="{{ asset('images/logo_dumb.png') }}" alt="Play N Chill" height="48">
        </a>
        <div class="ms-auto d-flex align-items-center gap-3">
            <a href="{{ url('/') }}" class="nav-link fw-bold text-dark">Home</a>
            <a href="{{ url('/booking') }}" class="nav-link nav-btn-active">Booking</a>
            <a href="{{ url('/gallery') }}" class="nav-link fw-bold text-dark">Gallery</a>
            @auth
            <div class="nav-avatar"><svg viewBox="0 0 24 24" width="22"><path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z" fill="#472CA1"/></svg></div>
            @endauth
        </div>
    </div>
</nav>

{{-- HERO --}}
<div class="paket-hero">
    <h1>Booking Sekarang</h1>
    <p>Reservasi paket favoritmu jadi lebih praktis, ikuti langkah mudahnya dan pesan sekarang</p>
</div>

<section class="paket-section section-ps">
    <h2 class="section-title">{{ $room->kategori->nama_kategori ?? 'Paket' }}</h2>

    <div class="paket-grid">
        @forelse($pricings as $paketId => $items)

            @php
                $first = $items->first();
                $paket = $first->paket;
            @endphp

            <div class="paket-card">
                {{-- NAMA --}}
                <div class="paket-card-name">
                    {{ $paket->nama_paket }}
                </div>

                {{-- SUB --}}
                <p class="paket-card-sub">
                    {{ $room->nama_ruangan ?? '' }}
                </p>

                {{-- HARGA --}}
                <div class="paket-card-price">
                    Rp {{ number_format($first->harga, 0, ',', '.') }}
                </div>

                {{-- META --}}
                <div class="paket-card-meta">
                    <span>🕐 {{ $first->durasi_menit }} menit</span>
                    <span>👤 Max {{ $paket->maksimal_orang }} Orang</span>
                </div>

                {{-- FASILITAS = DESKRIPSI --}}
                <ul class="paket-features">
                    @forelse($paket->fasilitas as $f)
                        <li>{{ $f->nama_fasilitas }}</li>
                    @empty
                        <li class="text-muted">Tidak ada fasilitas</li>
                    @endforelse
                </ul>

                {{-- BUTTON --}}
                <a href="{{ url('/booking/form?room='.$roomId.'&tipe='.$tipe.'&paket='.$paket->id_paket) }}"
                   class="btn-pilih-paket">
                    Pilih Paket
                </a>
            </div>

        @empty
            <p class="text-center text-muted">Belum ada paket tersedia.</p>
        @endforelse
    </div>
</section>

<div style="background-color: #472CA1; padding: 40px 0; text-align: center;">
    <a href="{{ url('/booking') }}" style="color: white; text-decoration: none; font-weight: bold;">← Kembali Pilih Ruangan</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>