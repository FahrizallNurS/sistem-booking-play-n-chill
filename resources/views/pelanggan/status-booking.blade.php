<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/logo_dumb.png') }}">
    <title>Status Pesanan - Play N Chill</title>
    
    {{-- CSS Assets --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    {{-- Hubungkan ke CSS Terpisah --}}
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/status.css') }}">

    <style>
        /* Override beberapa gaya untuk konsistensi warna */
        body {
            background-color: var(--purple-dark);
            background-image: none; /* Menghilangkan pola garis hijau lama agar lebih clean */
        }
        .status-box-pending {
            border: 3px solid #ff4d4d;
            background-color: white;
        }
    </style>
</head>
<body class="pb-5">

{{-- ═══ NAVBAR (SINKRON DENGAN HOME) ═══ --}}
<nav class="navbar navbar-expand-lg sticky-top bg-white shadow-sm mb-5">
    <div class="container-fluid px-4">
        <a class="navbar-brand p-0" href="{{ url('/') }}">
            <img src="{{ asset('images/logo_dumb.png') }}" alt="Play N Chill" height="48">
        </a>
        <div class="ms-auto d-flex align-items-center gap-1">
            <ul class="navbar-nav flex-row gap-3">
                <li class="nav-item d-none d-md-block"><a class="nav-link fw-bold text-dark" href="{{ url('/') }}">Home</a></li>
                <li class="nav-item"><a class="nav-link nav-btn-active" href="{{ url('/booking') }}">Booking</a></li>
                <li class="nav-item d-none d-md-block"><a class="nav-link fw-bold text-dark" href="{{ url('/gallery') }}">Gallery</a></li>
                
                @auth
                <li class="nav-item ms-2">
                    <div class="nav-avatar">
                        <svg viewBox="0 0 24 24" width="22">
                            <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z" fill="var(--purple-dark)"/>
                        </svg>
                    </div>
                </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<div class="container pb-5">
    
    <div class="text-center mb-5">
        <h2 class="display-5 text-white" style="font-family: 'Fredoka One'; letter-spacing: 2px;">
            PESANAN BERHASIL <span class="ms-2">✅</span>
        </h2>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            
            <div class="space-y-4">
                
                <div class="glass-card mb-4 text-center">
                    <span class="section-badge">Kode Booking</span>
                    
                    <div class="py-5">
                        <h1 class="booking-id-text text-white">AIXOV</h1>
                        
                        <div class="status-box-pending d-inline-flex align-items-center gap-2 px-4 py-2 rounded-4 shadow-lg">
                            <span class="text-danger fw-black text-uppercase h5 m-0">Menunggu Pembayaran</span>
                            <span class="h4 m-0">⏳</span>
                        </div>
                    </div>
                </div>

                <div class="glass-card mb-4">
                    <span class="section-badge">Catatan Admin</span>
                    <div class="p-4 pt-2">
                        <p class="h5 leading-relaxed fw-bold text-white">
                            Agar pesanan dapat diproses, silakan kirim bukti pembayaran serta kode booking ke WhatsApp admin! Batas konfirmasi adalah 30 menit.
                        </p>
                    </div>
                </div>

                <div class="glass-card mb-4">
                    <span class="section-badge">Detail Booking</span>
                    <div class="p-4 mt-2">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="detail-item shadow-sm">
                                    <div class="detail-icon">📅</div>
                                    <div>
                                        <p class="m-0 text-muted small fw-bold text-uppercase">Waktu Main</p>
                                        <p class="m-0 fw-black h5">11 April 2026</p>
                                        <p class="m-0 text-primary fw-bold small">20.00 - 22.00 WIB</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-item shadow-sm">
                                    <div class="detail-icon">🛋️</div>
                                    <div>
                                        <p class="m-0 text-muted small fw-bold text-uppercase">Ruangan</p>
                                        <p class="m-0 fw-black h5 text-uppercase">VIP Room 1</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <a href="https://wa.me/628123456789?text=Halo%20Admin,%20saya%20ingin%20konfirmasi%20booking%20AIXOV" 
                   class="btn-wa-confirm mb-4">
                    KONFIRMASI DI SINI <i class="fa-brands fa-whatsapp fs-2 ms-2 text-success"></i>
                </a>

                <a href="{{ url('/') }}" class="text-white text-center d-block opacity-75 fw-bold text-decoration-none mt-3">
                    <i class="fa-solid fa-arrow-left me-2"></i> Kembali ke Beranda
                </a>

            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>