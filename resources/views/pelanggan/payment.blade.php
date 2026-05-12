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

    {{-- Countdown Timer --}}
    @php
        $expiredAt = \Carbon\Carbon::parse($transaksi->created_at)->addMinutes(30);
        $sisaDetik = max(0, now()->diffInSeconds($expiredAt, false));
    @endphp

    @if($sisaDetik > 0)
    <div class="container mt-3">
        <div class="alert text-center fw-bold" 
            style="background:rgba(255,165,0,0.2);border:1px solid orange;color:white;border-radius:12px;">
            ⏳ Selesaikan pembayaran dalam: 
            <span id="countdown" style="color:var(--yellow);font-size:1.2rem;">
                {{ gmdate('i:s', $sisaDetik) }}
            </span>
        </div>
    </div>
    @else
    <div class="container mt-3">
        <div class="alert text-center fw-bold"
            style="background:rgba(255,0,0,0.2);border:1px solid red;color:white;border-radius:12px;">
            ❌ Waktu pembayaran telah habis. Booking ini sudah dibatalkan otomatis.
        </div>
    </div>
    @endif

    <div class="container pb-5">
        <h2 class="payment-title text-white">Informasi Pembayaran</h2>

        @php
            $booking = session('booking_data', []);
            $user    = auth()->user();  
        @endphp

        <div class="row justify-content-center">
            <div class="col-lg-7">

                {{-- ── 1. RINGKASAN BOOKING ── --}}
                @php $ph = $transaksi->penetapanHarga; @endphp

        {{-- Ringkasan Pesanan --}}
        <div class="payment-card">
            <span class="section-badge">Ringkasan Pesanan</span>

            <div class="info-row">
                <span class="info-label">Kode Sewa</span>
                <span class="info-value"><code>{{ $transaksi->kode_sewa }}</code></span>
            </div>
            <div class="info-row">
                <span class="info-label">Ruangan</span>
                <span class="info-value">{{ $ph->ruangan->nama_ruangan ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Paket</span>
                <span class="info-value">{{ $ph->paket->nama_paket ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Tanggal</span>
                <span class="info-value">
                    {{ \Carbon\Carbon::parse($transaksi->waktu_mulai)->translatedFormat('d F Y') }}
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">Jam Main</span>
                <span class="info-value">
                    {{ \Carbon\Carbon::parse($transaksi->waktu_mulai)->format('H:i') }}
                    — {{ \Carbon\Carbon::parse($transaksi->waktu_selesai)->format('H:i') }} WIB
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">Metode Bayar</span>
                <span class="info-value text-warning">
                    {{ $transaksi->opsi_pembayaran === 'full' ? 'Full Payment' : 'DP' }}
                </span>
            </div>

            <div class="border-top border-white-50 my-3"></div>

            <div class="info-row">
                <span class="info-label">Nama Pemesan</span>
                <span class="info-value">{{ auth()->user()->name }}</span>
            </div>

            @if($transaksi->opsi_pembayaran === 'dp')
            <div class="info-row">
                <span class="info-label">Jumlah DP</span>
                <span class="info-value">Rp {{ number_format($transaksi->jumlah_dp, 0, ',', '.') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Sisa Bayar</span>
                <span class="info-value text-warning">Rp {{ number_format($transaksi->sisa_bayar, 0, ',', '.') }}</span>
            </div>
            @endif

            <div class="info-row border-0 pt-4">
                <span class="h5 m-0 fw-bold">Total Pembayaran</span>
                <span class="total-highlight">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
            </div>
        </div>

                {{-- ── 2. INFORMASI TRANSFER ── --}}
                <div class="payment-card">
                    <span class="section-badge">Metode Transfer</span>

                    {{-- QRIS --}}
                    <div class="qris-box">
                        <div class="qris-img mx-auto" >
                            <img src="{{ asset('images/qris.jpeg') }}" class="img-fluid" alt="QRIS" style="max-width: 200px;">
                        </div>
                        <p class="m-0 fw-black text-dark">Play N Chill Madiun</p>
                    </div>
                </div>

                {{-- ── 3. TOMBOL AKSI ── --}}
                <div class="mt-4">
                    <a href="{{ route('booking.status') }}" 
                        class="btn-confirm d-block text-center text-decoration-none mb-3">
                        Lihat Status Booking <span class="ms-2"></span>
                    </a>
                    <a href="{{ url('/') }}" class="btn-status-link">
                        Kembali ke Beranda 
                    </a>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        let sisaDetik = {{ $sisaDetik }};
        
        if (sisaDetik > 0) {
            const interval = setInterval(() => {
                sisaDetik--;
                
                if (sisaDetik <= 0) {
                    clearInterval(interval);
                    // Reload halaman biar status terupdate
                    location.reload();
                    return;
                }

                const menit = Math.floor(sisaDetik / 60).toString().padStart(2, '0');
                const detik = Math.floor(sisaDetik % 60).toString().padStart(2, '0'); // ← tambah Math.floor()
                const el     = document.getElementById('countdown');
                if (el) el.textContent = menit + ':' + detik;

                // Warna merah kalau tinggal 5 menit
                if (sisaDetik <= 300 && el) {
                    el.style.color = 'red';
                }
            }, 1000);
    }
</script>
</body>
</html>