<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/logo_dumb.png') }}">
    <title>Profil - Play N Chill</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">

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
            opacity: 0.7; /* Nilai 0.0 (hilang) sampai 1.0 (jelas) */
            
            z-index: -1; /* Memastikan background berada di belakang konten */
        }
    </style>
    
</head>
<body>

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

<div class="profile-page">
<div class="container py-4">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    {{-- ═══ HEADER PROFIL ═══ --}}
    <div class="profile-header-card mb-4">
        <div class="profile-header-left">
            <div class="profile-avatar-circle">
                <i class="fas fa-user"></i>
            </div>
            <div class="profile-header-info">
                <h2>{{ $user->nama_pengguna }}</h2>
                <p><i class="fas fa-envelope me-1"></i> {{ $user->email }}</p>
                @if($user->no_hp)
                    <p><i class="fas fa-phone me-1"></i> {{ $user->no_hp }}</p>
                @endif
                <p><i class="fas fa-calendar me-1"></i> Member sejak {{ \Carbon\Carbon::parse($user->created_at)->translatedFormat('F Y') }}</p>
            </div>
        </div>
        <div class="profile-header-right">
            <div class="profile-stat">
                <div class="profile-stat-value">{{ $totalJam }}</div>
                <div class="profile-stat-label">Jam Main</div>
            </div>
            <div class="profile-stat">
                <div class="profile-stat-value">{{ $riwayat->count() }}</div>
                <div class="profile-stat-label">Total Booking</div>
            </div>
            <button class="btn-edit-profile" onclick="toggleEditModal()">
                <i class="fas fa-edit me-1"></i> Edit Profil
            </button>
        </div>
    </div>

    @if($bookingAktif)
    @php
        $ph      = $bookingAktif->penetapanHarga;
        $mulai   = \Carbon\Carbon::parse($bookingAktif->waktu_mulai);
        $selesai = \Carbon\Carbon::parse($bookingAktif->waktu_selesai);
        $now     = now();
        $totalMenit    = $mulai->diffInMinutes($selesai);
        $jalanMenit    = $now->between($mulai, $selesai) ? $mulai->diffInMinutes($now) : ($now->gt($selesai) ? $totalMenit : 0);
        $persenJalan   = $totalMenit > 0 ? min(100, round($jalanMenit / $totalMenit * 100)) : 0;
        $sisaMenit     = max(0, $totalMenit - $jalanMenit);
        $sisaJam       = floor($sisaMenit / 60);
        $sisaMenitSisa = $sisaMenit % 60;
        $statusWaktu      = $now->lt($mulai) ? 'Belum Dimulai' : ($now->gt($selesai) ? 'Sudah Selesai' : 'Sedang Berjalan');
        $statusWaktuColor = $now->lt($mulai) ? '#6b7280' : ($now->gt($selesai) ? '#3b82f6' : '#22c55e');
    @endphp

    {{-- Timer --}}
    @if($bookingAktif->status_sewa === 'ditahan' && $sisaDetik > 0)
    <div class="alert text-center fw-bold mb-3"
        style="background:rgba(255,165,0,0.2);border:1px solid orange;color:white;border-radius:12px;">
        ⏳ Selesaikan pembayaran dalam:
        <span id="countdown" style="color:var(--yellow);font-size:1.2rem;">
            {{ gmdate('i:s', $sisaDetik) }}
        </span>
    </div>
    @elseif($bookingAktif->status_sewa === 'ditahan' && $sisaDetik <= 0)
    <div class="alert text-center fw-bold mb-3"
        style="background:rgba(255,0,0,0.2);border:1px solid red;color:white;border-radius:12px;">
        ❌ Waktu pembayaran habis. Booking dibatalkan otomatis.
    </div>
    @endif

    <div class="section-title mb-3">
        <i class="fas fa-clock me-2"></i> Booking Aktif
    </div>

    <div class="booking-aktif-card mb-4">
        <div class="booking-aktif-main">
            <div class="booking-aktif-info">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <h4 class="mb-0">{{ $ph->ruangan->nama_ruangan ?? '-' }}</h4>
                    <span class="badge-aktif">Aktif</span>
                </div>
                <p class="text-muted mb-3">Kode Booking: <code>{{ $bookingAktif->kode_sewa }}</code></p>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <div class="waktu-box">
                            <i class="fas fa-play-circle text-success me-2"></i>
                            <div>
                                <div class="waktu-label">Waktu Mulai</div>
                                <div class="waktu-value">{{ $mulai->translatedFormat('l, d F Y') }} pukul {{ $mulai->format('H:i') }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="waktu-box">
                            <i class="fas fa-stop-circle text-danger me-2"></i>
                            <div>
                                <div class="waktu-label">Waktu Selesai</div>
                                <div class="waktu-value">{{ $selesai->translatedFormat('l, d F Y') }} pukul {{ $selesai->format('H:i') }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Progress --}}
                <div class="progress-section mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="progress-label">Status Waktu:</span>
                        <span class="progress-status" style="color:{{ $statusWaktuColor }}">{{ $statusWaktu }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <div>
                            <div class="progress-sublabel">Waktu Berjalan</div>
                            <div class="progress-time text-success">{{ floor($jalanMenit/60) }}j {{ $jalanMenit%60 }}m</div>
                        </div>
                        <div class="text-end">
                            <div class="progress-sublabel">Waktu Tersisa</div>
                            <div class="progress-time text-warning">{{ $sisaJam }}j {{ $sisaMenitSisa }}m</div>
                        </div>
                    </div>
                    <div class="progress" style="height:8px;border-radius:10px;">
                        <div class="progress-bar bg-success" style="width:{{ $persenJalan }}%;border-radius:10px;"></div>
                    </div>
                    <div class="text-center mt-1" style="font-size:0.75rem;color:#888;">{{ $persenJalan }}% selesai</div>
                </div>

                {{-- Pembayaran --}}
                <div class="payment-info-grid">
                    <div class="payment-info-item">
                        <span>Total Harga:</span>
                        <strong>Rp {{ number_format($bookingAktif->total_harga, 0, ',', '.') }}</strong>
                    </div>
                    @if($bookingAktif->opsi_pembayaran === 'dp')
                    <div class="payment-info-item">
                        <span>DP Dibayar:</span>
                        <strong class="text-success">Rp {{ number_format($bookingAktif->jumlah_dp, 0, ',', '.') }}</strong>
                    </div>
                    <div class="payment-info-item">
                        <span>Sisa Pembayaran:</span>
                        <strong class="text-danger">Rp {{ number_format($bookingAktif->sisa_bayar, 0, ',', '.') }}</strong>
                    </div>
                    @endif
                    <div class="payment-info-item">
                        <span>Status Pembayaran:</span>
                        <strong>
                            @if($bookingAktif->status_pembayaran === 'lunas')
                                <span class="badge bg-success">Lunas</span>
                            @elseif($bookingAktif->status_pembayaran === 'dp')
                                <span class="badge bg-warning text-dark">DP Dibayar</span>
                            @else
                                <span class="badge bg-secondary">Menunggu</span>
                            @endif
                        </strong>
                    </div>
                </div>

                {{-- Catatan --}}
                @if($bookingAktif->catatan_pembayaran)
                <div class="catatan-box mt-2">
                    <i class="fas fa-info-circle me-1"></i> {{ $bookingAktif->catatan_pembayaran }}
                </div>
                @endif

                {{-- Tombol aksi --}}
                @if($bookingAktif->status_pembayaran !== 'lunas')
                <div class="d-flex gap-3 mt-3 flex-wrap">
                    <a href="https://wa.me/6285735329227?text=Halo admin, saya ingin konfirmasi pembayaran booking {{ $bookingAktif->kode_sewa }}"
                        class="btn-bayar">
                        <i class="fab fa-whatsapp me-2"></i> Konfirmasi Pembayaran
                    </a>
                    <a href="{{ route('booking.payment.show', $bookingAktif->id_transaksi) }}"
                        class="btn-bayar btn-bayar-payment">
                        <i class="fas fa-credit-card me-2"></i> Lihat Pembayaran
                    </a>
                </div>
                @endif
            </div>

            <div class="booking-aktif-gambar">
                @if($ph->ruangan->galeri)
                    <img src="{{ asset('storage/' . $ph->ruangan->galeri) }}" alt="{{ $ph->ruangan->nama_ruangan }}">
                @else
                    <div class="gambar-placeholder">
                        <i class="fas fa-gamepad"></i>
                        <span>{{ $ph->ruangan->nama_ruangan ?? 'Ruangan' }}</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @endif


    <div class="section-title mb-3">
        <i class="fas fa-history me-2"></i> Riwayat Booking
    </div>

    @forelse($riwayat as $booking)
    @php
        $ph = $booking->penetapanHarga;
    @endphp
    <div class="riwayat-card mb-3">
        <div class="riwayat-main">
            <div class="riwayat-info">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <h5 class="mb-0">{{ $ph->ruangan->nama_ruangan ?? '-' }}</h5>
                    @if($booking->status_sewa === 'selesai')
                        <span class="badge bg-primary">Selesai</span>
                    @else
                        <span class="badge bg-danger">Dibatalkan</span>
                    @endif
                </div>
                <p class="text-muted mb-2" style="font-size:0.85rem">Kode: {{ $booking->kode_sewa }}</p>

                <div class="riwayat-detail-grid">
                    <div><i class="fas fa-calendar me-1 text-muted"></i> {{ \Carbon\Carbon::parse($booking->waktu_mulai)->format('d/m/Y') }}</div>
                    <div><i class="fas fa-clock me-1 text-muted"></i> {{ \Carbon\Carbon::parse($booking->waktu_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->waktu_selesai)->format('H:i') }}</div>
                    <div><i class="fas fa-money-bill me-1 text-muted"></i> Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</div>
                    <div>
                        @if($booking->status_pembayaran === 'lunas')
                            <span class="badge bg-success">Lunas</span>
                        @elseif($booking->status_pembayaran === 'dp')
                            <span class="badge bg-warning text-dark">DP</span>
                        @else
                            <span class="badge bg-secondary">Belum Bayar</span>
                        @endif
                    </div>
                </div>
                @if($booking->status_sewa === 'dibatalkan' && $booking->catatan_pembayaran)
                <div style="margin-top:8px;background:#fff3cd;border:1px solid #ffc107;
                            border-radius:8px;padding:8px 12px;font-size:0.82rem;color:#856404;">
                    <i class="fas fa-exclamation-triangle me-1"></i>
                    <strong>Alasan:</strong> {{ $booking->catatan_pembayaran }}
                </div>
                @endif
            </div>

            <div class="riwayat-actions">
                <a href="{{ url('/booking/paket?room='.$ph->id_ruangan.'&tipe='.strtolower($ph->ruangan->kategori ?? 'reguler')) }}"
                    class="btn-booking-lagi">
                    <i class="fas fa-redo me-1"></i> Booking Lagi
                </a>
            </div>
        </div>
    </div>
    @empty
    <div class="empty-state">
        <i class="fas fa-calendar-times"></i>
        <p>Belum ada riwayat booking.</p>
        <a href="{{ url('/booking') }}" class="btn-booking-lagi">Booking Sekarang</a>
    </div>
    @endforelse

</div>
</div>

<div class="modal fade" id="modalEditProfil" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Edit Profil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama</label>
                        <input type="text" name="nama_pengguna" class="form-control"
                            value="{{ old('nama_pengguna', $user->nama_pengguna) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Email</label>
                        <input type="email" name="email" class="form-control"
                            value="{{ old('email', $user->email) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">No. HP</label>
                        <input type="tel" name="no_hp" class="form-control"
                            placeholder="Contoh: 08123456789"
                            value="{{ old('no_hp', $user->no_hp) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Alamat</label>
                        <input type="text" name="alamat" class="form-control"
                            placeholder="Alamat lengkap"
                            value="{{ old('alamat', $user->alamat) }}">
                    </div>

                    <hr>
                    <p class="text-muted small mb-3">
                        <i class="fas fa-lock me-1"></i>
                        Kosongkan bagian password jika tidak ingin menggantinya.
                    </p>

                    @if(!$user->password)
                        <div class="alert alert-info small py-2">
                            <i class="fas fa-google me-1"></i>
                            Akun terhubung via Google. Buat password untuk bisa login manual.
                        </div>
                    @else
                        <div class="mb-3">
                            <label class="form-label fw-bold">Password Lama</label>
                            <input type="password" name="current_password" class="form-control"
                                placeholder="Masukkan password lama">
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label fw-bold">Password Baru</label>
                        <input type="password" name="new_password" class="form-control"
                            placeholder="Minimal 8 karakter">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Konfirmasi Password Baru</label>
                        <input type="password" name="new_password_confirmation" class="form-control"
                            placeholder="Ulangi password baru">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary fw-bold">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function toggleEditModal() {
        var modal = new bootstrap.Modal(document.getElementById('modalEditProfil'));
        modal.show();
    }

    // Auto buka modal kalau ada error validasi
    @if($errors->any())
        document.addEventListener('DOMContentLoaded', function() {
            toggleEditModal();
        });
    @endif
</script>
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
            const el = document.getElementById('countdown');
            if (el) el.textContent = menit + ':' + detik;

            if (sisaDetik <= 300 && el) el.style.color = 'red';
        }, 1000);
    }
</script>
</body>
</html>