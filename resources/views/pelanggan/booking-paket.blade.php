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

{{-- SECTION 1: PLAYSTATION (Latar Putih) --}}
<section class="paket-section section-ps">
    <h2 class="section-title">Playstation</h2>
    <div class="paket-grid">
        @php $ps_packages = ['Paket Couple', 'Paket Group', 'Paket Party']; @endphp
        @foreach($ps_packages as $p)
        <div class="paket-card">
            <div class="paket-card-name">{{ $p }}</div>
            <p class="paket-card-sub">VIP PlayStation 3 / 4 / 5</p>
            <div class="paket-card-price">Rp 45.000</div>
            <div class="paket-card-meta"><span>🕐 Durasi 1 jam</span> <span>👤 1-4 Orang</span></div>
            <ul class="paket-features">
                <li>Nintendo Switch</li><li>AC & WiFi</li><li>VIP/VVIP Room</li><li>4K TV Screen</li>
            </ul>
            <a href="{{ url('/booking/form?room='.$room['id'].'&tipe='.$tipe.'&paket='.urlencode($p).'&kategori=Playstation') }}" class="btn-pilih-paket">Pilih Paket</a>
        </div>
        @endforeach
    </div>
</section>

{{-- SECTION 2: KARAOKE (Latar Oranye) --}}
<section class="paket-section section-karaoke">
    <h2 class="section-title">Private Karaoke</h2>
    <div class="paket-grid">
        @php $karaoke_packages = ['Paket Couple', 'Paket Group', 'Paket Party']; @endphp
        @foreach($karaoke_packages as $k)
        <div class="paket-card">
            <div class="paket-card-name">{{ $k }}</div>
            <p class="paket-card-sub">VIP Room Premium <br> Senin - Minggu</p>
            <div class="paket-card-price">Rp 50.000</div>
            <div class="paket-card-meta"><span>🕐 Durasi per jam</span> <span>👤 Max 6 Orang</span></div>
            <ul class="paket-features">
                <li>VIP Room</li><li>Full AC</li><li>Sofa / Beanbag</li><li>Soundbar System</li><li>Wireless Mic</li>
            </ul>
            <a href="{{ url('/booking/form?room='.$room['id'].'&tipe='.$tipe.'&paket='.urlencode($k).'&kategori=Karaoke') }}" class="btn-pilih-paket">Pilih Paket</a>
        </div>
        @endforeach
    </div>
</section>

{{-- SECTION 3: BIOSKOP (Latar Hijau Lemon) --}}
<section class="paket-section section-bioskop">
    <h2 class="section-title">Bioskop</h2>
    <div class="paket-grid">
        @php $bioskop_packages = ['Paket Couple', 'Paket Group', 'Paket Party']; @endphp
        @foreach($bioskop_packages as $b)
        <div class="paket-card">
            <div class="paket-card-name">{{ $b }}</div>
            <p class="paket-card-sub">Private Cinema Experience <br> Senin - Minggu</p>
            <div class="paket-card-price">Rp 100.000</div>
            <div class="paket-card-meta"><span>🕐 Durasi per jam</span> <span>👤 Max 6 Orang</span></div>
            <ul class="paket-features">
                <li>VIP Cinema Room</li><li>Projector / 4K TV</li><li>Cozy Sofa</li><li>Premium Audio</li><li>Snack Friendly</li>
            </ul>
            <a href="{{ url('/booking/form?room='.$room['id'].'&tipe='.$tipe.'&paket='.urlencode($b).'&kategori=Bioskop') }}" class="btn-pilih-paket">Pilih Paket</a>
        </div>
        @endforeach
    </div>
</section>

<div style="background-color: #472CA1; padding: 40px 0; text-align: center;">
    <a href="{{ url('/booking') }}" style="color: white; text-decoration: none; font-weight: bold;">← Kembali Pilih Ruangan</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>