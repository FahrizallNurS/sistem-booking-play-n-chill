<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/logo_dumb.png') }}">
    <title>Pilih Durasi - Play N Chill</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/booking-form.css') }}">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>[x-cloak] { display: none !important; }</style>
    <style>
        .btn-time.blocked {
            background: rgba(255,255,255,0.08) !important;
            color: rgba(255,255,255,0.25) !important;
            cursor: not-allowed !important;
            border-color: rgba(255,255,255,0.1) !important;
            text-decoration: line-through;
        }

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
            opacity: 0.7;  
            z-index: -1; 
        }
        
    </style>
</head>

<body>

<div x-data="bookingForm()">

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
                    
                    <li class="nav-item">
                    <a class="nav-link" href="{{ url('/galeri') }}">Menu F&B</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/galeri') }}">Galeri</a>
                </li>


                    <li class="nav-item ms-2">

                        @guest
                            <a class="nav-link nav-btn-active" href="{{ url('/login') }}"
                            style="background-color: var(--orange) !important;">
                                Login
                            </a>
                        @endguest

                        @auth
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

    <div class="container py-5">
        <h1 class="page-title text-white">Pilih Durasi</h1>

        <div class="row justify-content-center">
            <div class="col-lg-7 col-md-9">

                <form action="{{ url('/booking/penawaran-fb') }}" method="GET" novalidate @submit.prevent="handleSubmit($event)">

                    <input type="hidden" name="id_penetapan_harga" :value="selectedPricing ? selectedPricing.id_penetapan_harga : ''">
                    <input type="hidden" name="tanggal" value="{{ $tanggal }}">
                    <input type="hidden" name="waktu_mulai" :value="confirmedTime">
                    <input type="hidden" name="opsi_pembayaran" :value="paymentMethod">

                    {{-- INFO RUANGAN --}}
                    <div class="booking-card text-center text-white">
                        <span class="section-badge">Ruangan & Paket</span>
                        <h2 style="font-family:'Fredoka One';color:var(--yellow);">
                            {{ $room->nama_ruangan ?? '-' }}
                        </h2>
                        <p class="text-white-50">
                            {{ ucfirst(strtolower($room->kategori ?? '-')) }}
                            &mdash; {{ $paket->nama_paket ?? '-' }}
                        </p>
                        <div class="price-final mt-2">
                            Rp <span x-text="totalHarga">-</span>
                        </div>
                    </div>

                    {{-- TANGGAL --}}
                    <div class="booking-card">
                        <span class="section-badge">Tanggal</span>
                        <p class="text-white fw-bold mt-2 mb-0" style="font-size: 1.05rem;">
                            <i class="fas fa-calendar-alt me-2" style="color: var(--yellow);"></i>
                            {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y') }}
                        </p>
                    </div>

                    {{-- WAKTU --}}
                    <div class="booking-card">
                        <span class="section-badge">Waktu</span>
                        
                        {{-- Teks statis operasional --}}
                        <div class="text-white-50 small mb-2 mt-1" x-show="tanggal">
                            <i class="fas fa-clock"></i>
                            <span>Jam operasional: 10.00 – 01.00</span>
                        </div>

                        {{-- Tambahan slot waktu 00.00 dan 00.30 --}}
                        @php
                            $times = ['10.00','10.30','11.00','11.30','12.00','12.30','13.00','13.30',
                            '14.00','14.30','15.00','15.30','16.00','16.30','17.00','17.30',
                            '18.00','18.30','19.00','19.30','20.00','20.30','21.00','21.30',
                            '22.00','22.30','23.00','23.30','00.00'];
                        @endphp

                        <div class="time-grid">
                            @foreach($times as $time)
                            <button type="button"
                                @click="!isBlocked('{{ $time }}') && (confirmedTime = '{{ $time }}')"
                                class="btn-time"
                                :class="{
                                    'active': confirmedTime === '{{ $time }}',
                                    'blocked': isBlocked('{{ $time }}')
                                }"
                                :disabled="isBlocked('{{ $time }}')">
                                {{ $time }}
                            </button>
                            @endforeach
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="d-flex gap-3 small text-white-50">
                                <span>⬜ Tersedia</span>
                                <span style="text-decoration:line-through">⬜ Penuh</span>
                            </div>
                            
                            <div x-show="confirmedTime !== ''" class="text-end">
                                <small style="color: #ffcc00;">
                                    ✓ Waktu dipilih: <strong x-text="confirmedTime"></strong>
                                </small>
                            </div>
                        </div>
                    </div>

                    {{-- DURASI --}}
                    <div class="booking-card">
                        <span class="section-badge">Durasi & Harga</span>
                        <div class="duration-grid">
                            @foreach($penetapanHarga as $ph)
                            <button type="button"
                                @click='selectedPricing = {{ json_encode($ph) }}'
                                class="btn-duration"
                                :class="{ 'active': selectedPricing && selectedPricing.id_penetapan_harga === {{ $ph->id_penetapan_harga }} }">
                                {{ $ph->durasi_jam }} Jam
                                <small class="d-block">Rp {{ number_format($ph->harga, 0, ',', '.') }}</small>
                            </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- PAYMENT --}}
                    <div class="booking-card">
                        <span class="section-badge">Pembayaran</span>
                        <div class="row g-2">
                            <div class="col-6">
                                <button type="button"
                                    @click="paymentMethod = 'dp'"
                                    class="payment-btn w-100"
                                    :class="{ 'active': paymentMethod === 'dp' }">DP</button>
                            </div>
                            <div class="col-6">
                                <button type="button"
                                    @click="paymentMethod = 'full'"
                                    class="payment-btn w-100"
                                    :class="{ 'active': paymentMethod === 'full' }">Full Payment</button>
                            </div>
                        </div>

                        <div x-show="paymentMethod === 'dp'" class="mt-3" x-cloak>
                            <label class="text-white small mb-1">Jumlah DP (Rp)</label>
                            <input type="number" name="jumlah_dp" class="form-control"
                                placeholder="Masukkan jumlah DP" min="0"
                                x-model="dpAmount"
                                :class="{'is-invalid': isDpError}"
                                :max="selectedPricing ? selectedPricing.harga : ''">
                            
                            <div x-show="isDpError" class="text-danger mt-1 fw-bold" style="font-size: 0.85rem;" x-cloak>
                                <i class="fas fa-exclamation-triangle"></i> Jumlah DP tidak boleh melebihi total harga paket (Rp <span x-text="totalHarga"></span>)!
                            </div>
                        </div>
                    </div>

                    @if(!auth()->user()->no_hp)
                    <div class="booking-card">
                        <span class="section-badge">Nomor Telepon</span>
                        <p class="text-white-50 small mt-1 mb-2">
                            Nomor telepon diperlukan untuk konfirmasi booking.
                        </p>
                        <input type="tel" name="no_hp" class="form-control"
                            placeholder="Contoh: 08123456789"
                            maxlength="15"
                            value="{{ old('no_hp') }}"
                            required>
                        @error('no_hp')
                            <small class="text-danger mt-1 d-block">{{ $message }}</small>
                        @enderror
                    </div>
                    @endif

                    {{-- SUMMARY --}}
                    <div class="booking-card border-warning">
                        <span class="section-badge">Ringkasan</span>
                        <div class="total-box mt-3">
                            <div class="total-row">
                                <span>Ruangan:</span>
                                <span>{{ $room->nama_ruangan ?? '-' }}</span>
                            </div>
                            <div class="total-row">
                                <span>Paket:</span>
                                <span>{{ $paket->nama_paket ?? '-' }}</span>
                            </div>
                            <div class="total-row">
                                <span>Tanggal:</span>
                                <span x-text="tanggal"></span>
                            </div>
                            <div class="total-row">
                                <span>Jam Mulai:</span>
                                <span x-text="confirmedTime || '-'"></span>
                            </div>
                            <div class="total-row">
                                <span>Durasi:</span>
                                <span x-text="selectedPricing ? selectedPricing.durasi_jam + ' Jam' : '-'"></span>
                            </div>
                            <div class="total-row">
                                <span>Pembayaran:</span>
                                <span x-text="paymentMethod === 'full' ? 'Full Payment' : 'DP'"></span>
                            </div>
                            <div class="total-row border-0 fw-bold">
                                <span>Total:</span>
                                <span>Rp <span x-text="totalHarga">-</span></span>
                            </div>
                        </div>

                        <div class="row mt-4 g-3">
                            <div class="col-6">
                                <a href="{{ url('/booking/paket?room='.$room->id_ruangan.'&tipe='.$tipe.'&tanggal='.$tanggal) }}" class="btn-action btn-secondary-action w-100">Kembali</a>
                            </div>
                            <div class="col-6">
                                <button type="submit"
                                    :disabled="!confirmedTime || !selectedPricing || isDpError || isChecking"
                                    class="btn-action btn-primary-action w-100">
                                    <span x-show="!isChecking">LANJUTKAN</span>
                                    <span x-show="isChecking">MEMERIKSA...</span>
                                </button>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function bookingForm() {
    return {
        confirmedTime: '',
        selectedPricing: null,
        paymentMethod: 'full',
        tanggal: "{{ $tanggal }}",
        jamTerpakai: @json($jamTerpakai ?? []),
        roomId: {{ $room->id_ruangan }},

        dpAmount: '',
        isChecking: false,

        async handleSubmit(event) {
            if (!this.confirmedTime || !this.selectedPricing || this.isDpError) return;

            this.isChecking = true;

            const jamNormal = this.confirmedTime.replace('.', ':');
            const waktuMulaiFull = this.tanggal + ' ' + jamNormal;

            try {
                const params = new URLSearchParams({
                    ruangan: this.roomId,
                    waktu_mulai: waktuMulaiFull,
                    durasi_jam: this.selectedPricing.durasi_jam
                });

                const res = await fetch(`{{ url('/booking/cek-bentrok') }}?${params.toString()}`);
                const data = await res.json();

                if (!data.tersedia) {
                    await Swal.fire({
                        icon: 'warning',
                        title: 'Yah, Kalah Cepat!',
                        text: data.pesan,
                        confirmButtonColor: '#ff7a00',
                        confirmButtonText: 'Pilih Jam Lain'
                    });

                    // Refresh status jam supaya tombol yang baru penuh langsung ke-disable
                    await this.refreshJamTerpakai();
                    this.confirmedTime = '';
                    this.isChecking = false;
                    return;
                }

                event.target.submit();
            } catch (err) {
                this.isChecking = false;
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan',
                    text: 'Gagal memeriksa jadwal. Silakan coba lagi.'
                });
            }
        },

        async refreshJamTerpakai() {
            try {
                const params = new URLSearchParams({
                    room: this.roomId,
                    tanggal: this.tanggal
                });
                const res = await fetch(`{{ url('/booking/jam-terpakai') }}?${params.toString()}`);
                const data = await res.json();
                this.jamTerpakai = data.terpakai;
            } catch (err) {
                // diamkan, biarkan data lama tetap tampil
            }
        },

        get isDpError() {
            if (this.paymentMethod === 'dp' && this.selectedPricing) {
                return Number(this.dpAmount) > Number(this.selectedPricing.harga);
            }
            return false;
        },

        formatRupiah(angka) {
            return parseInt(angka, 10)
                .toString()
                .replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        },

        get totalHarga() {
            return this.selectedPricing
                ? this.formatRupiah(this.selectedPricing.harga)
                : '-';
        },

        isBlocked(slot) {
            if (this.jamTerpakai.includes(slot)) return true;
            if (!this.tanggal) return false;

            const now = new Date();
            const [y, m, d] = this.tanggal.split('-').map(Number);
            const tanggalDate = new Date(y, m - 1, d);
            const hariIni = new Date(now.getFullYear(), now.getMonth(), now.getDate());
            
            if (tanggalDate.getTime() === hariIni.getTime()) {
                const normalized = slot.replace('.', ':');
                let [jamSlot, menitSlot] = normalized.split(':').map(Number);
                
                // FIX: Logika jam melewati tengah malam (00:xx dan 01:xx)
                // Jika jam kurang dari jam buka (misal 10 pagi), anggap sebagai jam + 24 (hari berikutnya)
                if (jamSlot < 10) {
                    jamSlot += 24;
                }
                
                const slotMenit = jamSlot * 60 + menitSlot;
                
                // Sesuaikan juga jam saat ini (jika user booking saat larut malam)
                let jamSekarang = now.getHours();
                if (jamSekarang < 10) {
                    jamSekarang += 24;
                }
                
                const sekarangMenit = jamSekarang * 60 + now.getMinutes();
                
                if (slotMenit <= sekarangMenit) return true;
            }

            return false;
        }
    }
}
</script>
</body>
</html>