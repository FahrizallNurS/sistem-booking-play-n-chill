<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/logo_dumb.png') }}">
    <title>Status Pesanan - Play N Chill</title>

    {{-- CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/status.css') }}">

    {{-- Alpine (LOGIC LU) --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="pb-5"
      x-data="{
        bookingCode: '{{ $bookingCode ?? 'AIXOV' }}',
        status: '{{ $status ?? 'pending' }}',
        tanggal: '{{ $tanggal ?? now()->format('Y-m-d') }}',
        waktu: '{{ $waktu ?? '20.00 - 22.00' }}',
        room: '{{ $room['nama'] ?? 'VIP Room 1' }}'
      }">

{{-- NAVBAR --}}
<nav class="navbar navbar-expand-lg sticky-top bg-white shadow-sm mb-5">
    <div class="container-fluid px-4">
        <a class="navbar-brand p-0" href="{{ url('/') }}">
            <img src="{{ asset('images/logo_dumb.png') }}" height="48">
        </a>
    </div>
</nav>

<div class="container pb-5">

    {{-- TITLE --}}
    <div class="text-center mb-5">
        <h2 class="display-5 text-white" style="font-family: 'Fredoka One';">
            PESANAN BERHASIL ✅
        </h2>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">

            {{-- BOOKING CODE --}}
            <div class="glass-card mb-4 text-center">
                <span class="section-badge">Kode Booking</span>

                <div class="py-5">
                    <h1 class="booking-id-text text-white" x-text="bookingCode"></h1>

                    {{-- STATUS DINAMIS --}}
                    <div class="status-box-pending d-inline-flex align-items-center gap-2 px-4 py-2 rounded-4 shadow-lg">
                        <span class="fw-bold text-uppercase h5 m-0"
                              :class="status === 'pending' ? 'text-danger' : 'text-success'">

                            <span x-text="status === 'pending' ? 'Menunggu Pembayaran' : 'Sudah Dibayar'"></span>
                        </span>

                        <span class="h4 m-0"
                              x-text="status === 'pending' ? '⏳' : '✅'"></span>
                    </div>
                </div>
            </div>

            {{-- CATATAN --}}
            <div class="glass-card mb-4">
                <span class="section-badge">Catatan Admin</span>
                <div class="p-4 pt-2">
                    <p class="h5 fw-bold text-white">
                        Kirim bukti pembayaran + kode booking ke WhatsApp admin dalam 30 menit.
                    </p>
                </div>
            </div>

            {{-- DETAIL --}}
            <div class="glass-card mb-4">
                <span class="section-badge">Detail Booking</span>

                <div class="p-4 mt-2">
                    <div class="row g-3">

                        <div class="col-md-6">
                            <div class="detail-item shadow-sm">
                                <div class="detail-icon">📅</div>
                                <div>
                                    <p class="small fw-bold text-uppercase">Waktu</p>
                                    <p class="fw-bold h5" x-text="tanggal"></p>
                                    <p class="text-primary fw-bold small" x-text="waktu"></p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="detail-item shadow-sm">
                                <div class="detail-icon">🛋️</div>
                                <div>
                                    <p class="small fw-bold text-uppercase">Ruangan</p>
                                    <p class="fw-bold h5 text-uppercase" x-text="room"></p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- WA BUTTON --}}
            <a :href="'https://wa.me/628123456789?text=Halo admin, saya konfirmasi booking ' + bookingCode"
               class="btn-wa-confirm mb-4">
                KONFIRMASI DI SINI
                <i class="fa-brands fa-whatsapp fs-2 ms-2 text-success"></i>
            </a>

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