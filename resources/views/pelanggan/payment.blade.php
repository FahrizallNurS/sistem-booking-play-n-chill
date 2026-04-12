<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/logo_dumb.png') }}">
    <title>Informasi Pembayaran - Play N Chill</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/payment.css') }}">

    <style>
        .modal-qris {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.85); z-index: 9999;
            display: flex; align-items: center; justify-content: center;
        }
        .qris-card { background: white; padding: 25px; border-radius: 30px; text-align: center; max-width: 350px; }
        .btn-bank { border: 2px solid #ddd; transition: all 0.3s; color: white !important; }
        .active-bca { border-color: #005dab !important; background: rgba(0, 93, 171, 0.1); }
        .active-bni { border-color: #E55300 !important; background: rgba(229, 83, 0, 0.1); }
    </style>
</head>
<body x-data="{ showQR: false, method: 'BCA' }">

    {{-- MODAL QRIS OVERLAY --}}
    <div x-show="showQR" x-transition class="modal-qris" @click="showQR = false">
        <div class="qris-card" @click.stop>
            <h4 class="fw-black text-dark mb-3">SCAN QRIS</h4>
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=PlayNChillMadiun" class="img-fluid rounded-4 shadow mb-3">
            <p class="small text-muted mb-4">Silahkan scan melalui m-Banking atau E-wallet pilihan Anda.</p>
            <button @click="showQR = false" class="btn btn-danger w-100 rounded-pill fw-bold">Tutup</button>
        </div>
    </div>

    <nav class="navbar navbar-expand-lg sticky-top bg-white shadow-sm mb-5">
        <div class="container-fluid px-4">
            <a class="navbar-brand p-0" href="{{ url('/') }}">
                <img src="{{ asset('images/logo_dumb.png') }}" alt="Play N Chill" height="48">
            </a>
            <div class="ms-auto d-flex align-items-center gap-3">
                <a href="{{ url('/') }}" class="nav-link fw-bold text-dark d-none d-md-block">Home</a>
                <a href="{{ url('/booking') }}" class="nav-link nav-btn-active">Booking</a>
                <a href="{{ url('/gallery') }}" class="nav-link fw-bold text-dark d-none d-md-block">Gallery</a>
            </div>
        </div>
    </nav>

    <div class="container pb-5">
        <h2 class="payment-title text-white">Informasi Pembayaran</h2>

        @php
            $booking = session('booking_data', []);
            $user    = auth()->user();
        @endphp

        <div class="row justify-content-center">
            <div class="col-lg-7">

                {{-- ── 1. RINGKASAN BOOKING (DINAMIS) ── --}}
                <div class="payment-card">
                    <span class="section-badge">Ringkasan Pesanan</span>
                    
                    <div class="info-row">
                        <span class="info-label">Ruangan</span>
                        <span class="info-value">{{ $booking['ruangan'] ?? 'Standard Room' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Paket</span>
                        <span class="info-value">{{ $booking['paket_nama'] ?? 'Paket Pilihan' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Tanggal</span>
                        <span class="info-value">
                            {{ isset($booking['tanggal']) ? \Carbon\Carbon::parse($booking['tanggal'])->translatedFormat('d F Y') : '-' }}
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Jam</span>
                        <span class="info-value">{{ $booking['waktu_mulai'] ?? '-' }} - {{ $booking['waktu_selesai'] ?? '-' }} WIB</span>
                    </div>

                    <div class="border-top border-white-50 my-3"></div>

                    <div class="info-row">
                        <span class="info-label">Pemesan</span>
                        <span class="info-value text-capitalize">{{ $user->name ?? 'Tamu' }}</span>
                    </div>

                    <div class="info-row border-0 pt-4">
                        <span class="h5 m-0 fw-bold">Total Pembayaran</span>
                        <span class="total-highlight">Rp {{ number_format($booking['total_harga'] ?? 0, 0, ',', '.') }}</span>
                    </div>
                </div>

                {{-- ── 2. INFORMASI TRANSFER ── --}}
                <div class="payment-card">
                    <span class="section-badge">Pilih Metode Transfer</span>
                    
                    <div class="d-flex gap-2 my-4">
                        <button type="button" @click="method = 'BCA'" :class="method === 'BCA' ? 'active-bca shadow' : ''" class="btn btn-bank flex-fill py-3 rounded-4">
                            <span class="fw-bold">BCA</span>
                        </button>
                        <button type="button" @click="method = 'BNI'" :class="method === 'BNI' ? 'active-bni shadow' : ''" class="btn btn-bank flex-fill py-3 rounded-4">
                            <span class="fw-bold">BNI</span>
                        </button>
                        <button type="button" @click="showQR = true" class="btn btn-bank flex-fill py-3 rounded-4 bg-success border-0">
                            <span class="fw-bold text-white">QRIS</span>
                        </button>
                    </div>
                    
                    {{-- Detail Rekening BCA --}}
                    <div x-show="method === 'BCA'" x-transition class="bank-box mb-2">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/5c/Bank_Central_Asia.svg/1200px-Bank_Central_Asia.svg.png" width="80">
                        <div class="ms-3">
                            <p class="m-0 fw-bold text-uppercase small text-muted">BCA (Transfer)</p>
                            <h4 class="m-0 fw-black text-primary">1112233705</h4>
                            <p class="m-0 fw-bold small">A/N Play n Chill</p>
                        </div>
                    </div>

                    {{-- Detail Rekening BNI --}}
                    <div x-show="method === 'BNI'" x-transition class="bank-box mb-2" style="border-left-color: #E55300;">
                        <img src="https://upload.wikimedia.org/wikipedia/id/thumb/5/55/BNI_logo.svg/1200px-BNI_logo.svg.png" width="80">
                        <div class="ms-3">
                            <p class="m-0 fw-bold text-uppercase small text-muted">BNI (Transfer)</p>
                            <h4 class="m-0 fw-black" style="color: #E55300;">0987654321</h4>
                            <p class="m-0 fw-bold small">A/N Play n Chill</p>
                        </div>
                    </div>
                </div>

                {{-- ── 3. TOMBOL AKSI ── --}}
                <form action="{{ route('booking.store') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-confirm w-100 mb-3 shadow-lg">
                        Konfirmasi Pembayaran <span class="ms-2">✓</span>
                    </button>
                </form>

                <a href="{{ url('/status-booking') }}" class="btn-status-link text-center d-block">
                    Lihat Status Booking ➜
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>