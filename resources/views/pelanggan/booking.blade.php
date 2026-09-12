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
            background-color: var(--purple-dark);
            position: relative;
            min-height: 100vh;
            margin: 0;
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
            opacity: 0.9;
            z-index: -1;
        }

        
    </style>
</head>
<body>

@include('partials.navbar')

<div class="booking-page">

    <h1 class="booking-title">Booking Ruangan</h1>

    {{-- ═══ Kontrol Tanggal & Kategori ═══ --}}
    <div class="booking-control-card">

        <div class="control-label" style="color: #ffffff;">Mau Booking Buat Kapan?</div>

        <div class="date-row">
            <div class="date-quick-group">
                @foreach($quickDates as $qd)
                <button type="button"
                        class="date-quick-btn js-quick-date {{ $tanggal === $qd['value'] ? 'active' : '' }}"
                        data-value="{{ $qd['value'] }}">
                    <span class="date-quick-label">{{ $qd['label'] }}</span>
                    <span class="date-quick-date">{{ $qd['tanggal_display'] }}</span>
                    <span class="date-quick-day">{{ $qd['nama_hari'] }}</span>
                </button>
                @endforeach
            </div>

            <div class="date-custom-wrap">
                <div class="control-label" style="color: #ffffff;">Atau Tanggal Lain</div>
                <input type="date"
                       id="tanggalCustom"
                       class="date-custom-input"
                       min="{{ now()->format('Y-m-d') }}"
                       value="{{ $tanggal }}">
            </div>
        </div>

        <div class="kategori-row">
            <div class="control-label" style="color: #ffffff;">Kategori Ruangan:</div>
            <div class="kategori-tabs">
                <a href="{{ url('/booking?tipe=reguler&tanggal='.$tanggal) }}"
                   class="kategori-tab tab-reguler js-tab-link {{ request('tipe', 'reguler') == 'reguler' ? 'active' : '' }}"
                   data-tipe="reguler">
                    Reguler
                </a>
                <a href="{{ url('/booking?tipe=private-room&tanggal='.$tanggal) }}"
                   class="kategori-tab tab-reguler js-tab-link {{ request('tipe') == 'private-room' ? 'active' : '' }}"
                   data-tipe="private-room">
                    Private VIP
                </a>
            </div>
        </div>

    </div>

    {{-- Room Container --}}
    <div class="room-container">
        <h2 class="room-container-title">Tentukan Nomor Ruangan Favoritmu!</h2>

        <div class="room-grid-booking">

            @foreach($rooms as $room)
            <div class="room-card-booking">

                @if($room->galeri)
                    <div class="room-foto">
                        <img src="{{ asset($room->galeri) }}" alt="{{ $room->nama_ruangan }}">
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

                <div class="room-name">{{ $room->nama_ruangan }}</div>
                <div class="room-device">{{ $room->perangkat ?? '-' }}</div>

                <a href="{{ url('/booking/paket?room='.$room->id_ruangan.'&tipe='.$tipe.'&tanggal='.$tanggal) }}"
                   class="btn-booking js-btn-booking"
                   data-base-href="{{ url('/booking/paket?room='.$room->id_ruangan.'&tipe='.$tipe) }}">
                    Booking
                </a>
            </div>
            @endforeach

        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
(function () {
    const quickBtns   = document.querySelectorAll('.js-quick-date');
    const customInput = document.getElementById('tanggalCustom');
    const bookingBtns = document.querySelectorAll('.js-btn-booking');
    const tabLinks    = document.querySelectorAll('.js-tab-link');

    function setActiveQuick(value) {
        let matched = false;
        quickBtns.forEach(function (btn) {
            if (btn.dataset.value === value) {
                btn.classList.add('active');
                matched = true;
            } else {
                btn.classList.remove('active');
            }
        });
        customInput.classList.toggle('active', !matched);
    }

    function applyTanggal(value) {
        bookingBtns.forEach(function (btn) {
            btn.href = btn.getAttribute('data-base-href') + '&tanggal=' + value;
        });

        tabLinks.forEach(function (tab) {
            const url = new URL(tab.href, window.location.origin);
            url.searchParams.set('tanggal', value);
            tab.href = url.toString();
        });

        customInput.value = value;
        setActiveQuick(value);
    }

    quickBtns.forEach(function (btn) {
        btn.addEventListener('click', function () {
            applyTanggal(btn.dataset.value);
        });
    });

    customInput.addEventListener('change', function () {
        if (customInput.value) {
            applyTanggal(customInput.value);
        }
    });

    // Set initial state sesuai $tanggal dari server
    setActiveQuick(customInput.value);
})();
</script>
</body>
</html>