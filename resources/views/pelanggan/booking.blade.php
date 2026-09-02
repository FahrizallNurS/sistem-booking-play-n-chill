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

<!-- Navbar -->
 @include('partials.navbar')

{{-- ═══ BOOKING PAGE ═══ --}}
<div class="booking-page">

    {{-- Judul --}}
    <h1 class="booking-title">Booking</h1>

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