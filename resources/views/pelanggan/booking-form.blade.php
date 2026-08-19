<!DOCTYPE html>
        <html lang="id">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="icon" type="image/x-icon" href="{{ asset('images/logo_dumb.png') }}">
            <title>Pilih Durasi - Play N Chill</title>

            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
            <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
            <link rel="stylesheet" href="{{ asset('css/style.css') }}">
            <link rel="stylesheet" href="{{ asset('css/booking-form.css') }}">
            <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

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

        <div class="container py-5">
            <h1 class="page-title text-white">Pilih Durasi</h1>

            <div class="row justify-content-center">
                <div class="col-lg-7 col-md-9">

                    <form action="{{ url('/booking/penawaran-fb') }}" method="GET">

                        <input type="hidden" name="id_penetapan_harga" :value="selectedPricing ? selectedPricing.id_penetapan_harga : ''">
                        <input type="hidden" name="tanggal" :value="tanggal">
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
                            <input type="date"
                                x-model="tanggal"
                                @change="updateJamTerpakai()"
                                min="{{ now()->format('Y-m-d') }}"
                                class="form-control mt-2">
                        </div>

                        {{-- WAKTU --}}
                        <div class="booking-card">
                            <span class="section-badge">Waktu</span>
                            <div class="text-white-50 small mb-2 mt-1" x-show="tanggal">
                                <i class="fas fa-clock"></i>
                                <span x-text="(() => {
                                    const [y, m, d] = tanggal.split('-').map(Number);
                                    const h = new Date(y, m - 1, d).getDay();
                                    if (h === 0 || h === 6) return 'Jam operasional: 10.00 – 00.00';
                                    if (h === 5) return 'Jam operasional: 13.00 – 00.00';
                                    return 'Jam operasional: 14.00 – 22.00';
                                })()"></span>
                            </div>

                            @php
                                $times = ['10.00','10.30','11.00','11.30','12.00','12.30','13.00','13.30',
                                '14.00','14.30','15.00','15.30','16.00','16.30','17.00','17.30',
                                '18.00','18.30','19.00','19.30','20.00','20.30','21.00','21.30',
                                '22.00','22.30','23.00','23.30'];
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
                                    :max="selectedPricing ? selectedPricing.harga : ''">
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
                                    <a href="{{ url('/booking/penawaran-fb') }}" class="btn-action btn-secondary-action w-100">Kembali</a>
                                </div>
                                <div class="col-6">
                                    <button type="submit"
                                        :disabled="!confirmedTime || !selectedPricing"
                                        class="btn-action btn-primary-action w-100">
                                        LANJUTKAN
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
                tanggal: "{{ now()->format('Y-m-d') }}",
                jamTerpakai: @json($jamTerpakai ?? []),
                roomId: {{ $room->id_ruangan }},

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
                    const [jamSlot, menitSlot] = normalized.split(':').map(Number);
                    const slotMenit = jamSlot * 60 + menitSlot;
                    const sekarangMenit = now.getHours() * 60 + now.getMinutes();
                    if (slotMenit <= sekarangMenit) return true;
                }

                const hari = tanggalDate.getDay();
                const normalized = slot.replace('.', ':');
                const [jam, menit] = normalized.split(':').map(Number);
                const slotMenit = jam * 60 + menit;

                return false;
            },

                async updateJamTerpakai() {
                    this.confirmedTime = '';
                    try {
                        const res = await fetch('/booking/jam-terpakai?room=' + this.roomId + '&tanggal=' + this.tanggal);
                        const data = await res.json();
                        this.jamTerpakai = data.terpakai;
                    } catch(e) {
                        console.error('Gagal fetch jam terpakai', e);
                    }
                }
            }
        }
        </script>
        </body>
        </html>