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
        body { background-color: #352285; }
        .payment-wrapper {
            background-color: #4C3BAF; 
            border-radius: 12px; 
            padding: 30px 20px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }

        @media (max-width: 576px) {
            .payment-wrapper {
                padding: 24px 16px; 
            }
            .qr-box img {
                width: 200px;
                height: 200px;
            }
        }

        .timer-bar {
            background-color: rgba(0, 0, 0, 0.15); border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px; padding: 15px; margin-bottom: 20px;
        }
        .badge-orange {
            background-color: #FF7A00; color: white; font-weight: 700;
            padding: 6px 16px; border-radius: 50px; font-size: 0.85rem; display: inline-block;
        }
        .data-row {
            display: flex; justify-content: space-between; padding: 12px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.15); font-size: 0.95rem;
        }
        .data-row:last-child { border-bottom: none; }
        .data-label { color: #d1c4e9; }
        .data-value { color: #ffffff; font-weight: 700; text-align: right; }
        .text-orange { color: #FF7A00 !important; }
        .qr-box {
            background-color: white; border-radius: 12px; padding: 20px; display: inline-block;
        }
        .btn-full-orange {
            background-color: #FF7A00; color: white; font-weight: 800;
            border-radius: 50px; padding: 14px; border: none; transition: 0.2s;
        }
        .btn-full-orange:hover { background-color: #e66e00; color: white; }
        .btn-full-white {
            background-color: white; color: #352285; font-weight: 800;
            border-radius: 50px; padding: 14px; border: none; transition: 0.2s;
        }
        .btn-full-white:hover { background-color: #f0f0f0; color: #352285; }

        .qr-box img {
            width: 240px;
            height: 240px;
            object-fit: contain;
            border-radius: 8px;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg sticky-top" style="background: white;">
    <div class="container-fluid px-4">
        <a class="navbar-brand p-0" href="{{ url('/') }}">
            <img src="{{ asset('images/logo_dumb.png') }}" alt="Play N Chill" height="48">
        </a>
        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navMain">
            <ul class="navbar-nav align-items-center gap-1">
                <li class="nav-item"><a class="nav-link" href="{{ url('/') }}">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('/booking') }}">Booking</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('/menu-fb') }}">Menu F&B</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('/galeri') }}">Galeri</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('/tentang-kami') }}">Tentang Kami</a></li>
                <li class="nav-item ms-2">
                    @auth
                        <div class="dropdown">
                            <div class="nav-avatar" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;">
                                <svg viewBox="0 0 24 24" width="32" height="32"><path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z" fill="var(--purple-dark)"/></svg>
                            </div>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="userDropdown">
                                <li><span class="dropdown-item-text fw-bold">{{ auth()->user()->nama_pengguna ?? auth()->user()->name }}</span></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ url('/profile') }}">Profil Saya</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">Keluar (Logout)</button>
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

    {{-- LOGIKA DINAMIS BACKEND UNTUK 3 SKENARIO PEMESANAN --}}
    @php
        $sisaDetik = 0;
        $waktuDibuat = null;
        $totalBooking = 0;
        $totalFb = 0;

        // SKENARIO 1 & 2: Ada Data Booking
        if(isset($transaksi) && $transaksi) {
            $waktuDibuat = $transaksi->created_at;
            $totalBooking = $transaksi->opsi_pembayaran === 'dp' ? $transaksi->jumlah_dp : $transaksi->total_harga;
        } 
        // SKENARIO 3: Hanya Pesan F&B Mandiri
        elseif(isset($pos) && $pos) {
            $waktuDibuat = $pos->created_at;
        }

        // Kalkulasi Total F&B dari array yang dilempar Controller
        if(isset($keranjangFb) && is_array($keranjangFb)) {
            foreach($keranjangFb as $item) {
                $totalFb += ($item['price'] * $item['qty']);
            }
        } else {
            $keranjangFb = []; // Pastikan berupa array kosong jika tidak ada F&B
        }

        $grandTotal = $totalBooking + $totalFb;

        // Hitung Timer Countdown
        if($waktuDibuat) {
            $expiredAt = \Carbon\Carbon::parse($waktuDibuat)->addMinutes(30);
            $sisaDetik = max(0, now()->diffInSeconds($expiredAt, false));
        }
    @endphp

    <div class="container py-4" style="max-width: 800px;">
        
        @if($sisaDetik > 0)
        <div class="timer-bar text-center text-white">
            <span>Selesaikan Pembayaran Dalam: <span id="countdown" class="fw-bold">{{ gmdate('i:s', $sisaDetik) }}</span></span>
        </div>
        @else
        <div class="timer-bar text-center text-white" style="background-color: rgba(255,0,0,0.2); border-color: red;">
            <span class="fw-bold">❌ Waktu pembayaran telah habis. Pesanan dibatalkan otomatis.</span>
        </div>
        @endif

        <h3 class="text-center text-white fw-bold mb-4">RINGKASAN PESANAN</h3>

        <div class="payment-wrapper text-white mb-4">
            
            {{-- ================= BAGIAN 1: RINCIAN BOOKING (Hanya Muncul Jika Ada Booking) ================= --}}
            @if(isset($transaksi) && $transaksi)
                <div class="badge-orange mb-3">Pesanan Booking</div>
                
                <div class="data-row">
                    <span class="data-label">Kode Sewa</span>
                    <span class="data-value" style="color: #ff4d4d;">{{ $transaksi->kode_sewa }}</span>
                </div>
                <div class="data-row">
                    <span class="data-label">Nama Pemesan</span>
                   <span class="data-value text-uppercase">{{ optional(auth()->user())->name ?? optional(auth()->user())->nama_pengguna ?? 'Pelanggan Umum' }}</span>
                </div>
                <div class="data-row">
                    <span class="data-label">Ruangan & Paket</span>
                    <span class="data-value">
                        {{ $transaksi->penetapanHarga->ruangan->nama_ruangan ?? 'Ruangan' }} - 
                        {{ $transaksi->penetapanHarga->paket->nama_paket ?? 'Paket' }}
                    </span>
                </div>
                <div class="data-row">
                    <span class="data-label">Waktu Main</span>
                    <span class="data-value">
                        {{ \Carbon\Carbon::parse($transaksi->waktu_mulai)->translatedFormat('d F Y') }} <br>
                        {{ \Carbon\Carbon::parse($transaksi->waktu_mulai)->format('H.i') }} — 
                        {{ \Carbon\Carbon::parse($transaksi->waktu_selesai)->format('H.i') }} WIB
                    </span>
                </div>
                
                @if($transaksi->opsi_pembayaran === 'dp')
                    <div class="data-row">
                        <span class="data-label text-orange fw-bold">Total DP Booking</span>
                        <span class="data-value text-orange">Rp. {{ number_format($transaksi->jumlah_dp, 0, ',', '.') }}</span>
                    </div>
                @else
                    <div class="data-row">
                        <span class="data-label text-orange fw-bold">Total Booking</span>
                        <span class="data-value text-orange">Rp. {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
                    </div>
                @endif
            @endif


            {{-- ================= BAGIAN 2: RINCIAN F&B (Hanya Muncul Jika Ada Keranjang F&B) ================= --}}
            @if(is_array($keranjangFb) && count($keranjangFb) > 0)
                <div class="badge-orange mt-4 mb-3">Ringkasan Pesanan F&B</div>
                
                {{-- Jika HANYA pesan F&B (Tidak ada booking), tampilkan nama pemesan di sini --}}
                @if(!isset($transaksi))
                    <div class="data-row">
                        <span class="data-label">Nama Pemesan</span>
                      <span class="data-value text-uppercase">{{ auth()->user()?->name ?? auth()->user()?->nama_pengguna ?? 'Pelanggan Umum' }}</span>
                    </div>
                @endif

                @foreach($keranjangFb as $item)
                    <div class="data-row border-0 py-1">
                        <span class="data-label" style="font-size: 0.9rem;">- {{ $item['name'] }} ({{ $item['qty'] }}x)</span>
                        <span class="data-value" style="font-size: 0.9rem;">Rp. {{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}</span>
                    </div>
                @endforeach
                
                <div class="data-row mt-2 border-top border-secondary">
                    <span class="data-label text-orange fw-bold">Total Pesanan F&B</span>
                    <span class="data-value text-orange">Rp. {{ number_format($totalFb, 0, ',', '.') }}</span>
                </div>
            @endif


            {{-- ================= BAGIAN 3: GRAND TOTAL & QRIS ================= --}}
            
            {{-- Total Pembayaran dipindah ke atas --}}
            <div class="data-row border-0 mt-4 pt-2">
                <span class="data-label text-white fw-bold" style="font-size: 1.25rem;">Total Pembayaran</span>
                <span class="data-value text-white fw-bold" style="font-size: 1.25rem;">
                    Rp. {{ number_format($grandTotal, 0, ',', '.') }}
                </span>
            </div>

            {{-- Metode Bayar dipindah ke bawah dengan ukuran font yang konsisten (0.95rem) --}}
            <div class="data-row border-0 mb-4 pb-2" style="font-size: 0.95rem;">
                <span class="data-label">Metode Bayar:</span>
                <span class="data-value text-orange">
                    @if(isset($transaksi) && $transaksi->opsi_pembayaran === 'dp')
                        DP + Full Payment F&B
                    @else
                        Full Payment
                    @endif
                </span>
            </div>

            <div class="text-center">
                <div class="qr-box shadow">
                    <img src="{{ asset('images/qris_play_n_chill_hd.png') }}" class="img-fluid" alt="QR Code QRIS">
                </div>
            </div>

        </div>

        </div>

        {{-- ================= TOMBOL AKSI BAWAH ================= --}}
        <div class="d-flex flex-column gap-3">
            <a href="{{ route('profile') }}" class="btn btn-full-orange text-center text-decoration-none d-block w-100">
                LIHAT STATUS PESANAN
            </a>
            <a href="{{ url('/') }}" class="btn btn-full-white text-center text-decoration-none d-block w-100">
                KEMBALI KE BERANDA
            </a>
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
                    location.reload();
                    return;
                }

                const menit = Math.floor(sisaDetik / 60).toString().padStart(2, '0');
                const detik = Math.floor(sisaDetik % 60).toString().padStart(2, '0');
                const el    = document.getElementById('countdown');
                if (el) el.textContent = menit + ':' + detik;

                if (sisaDetik <= 300 && el) {
                    el.style.color = '#ff4d4d';
                }
            }, 1000);
        }
    </script>
</body>
</html>