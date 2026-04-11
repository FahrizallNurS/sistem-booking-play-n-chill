<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/logo_dumb.png') }}">
    <title>Pilih Durasi - Play N Chill</title>
    
    {{-- CSS Assets --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    
    {{-- Menggunakan CSS Terpisah yang sudah dibuat --}}
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/booking-form.css') }}">
    
    {{-- Alpine JS untuk logika Interaktif --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body x-data="{
    tempTime: '',
    confirmedTime: '',
    duration: 1,
    baseHarga: {{ $room['harga'] ?? 30000 }},
    paymentMethod: 'Full Payment',
    tanggal: '{{ now()->format('Y-m-d') }}',
    get totalHarga() {
        return (this.baseHarga * this.duration).toLocaleString('id-ID');
    }
}">

{{-- ═══ NAVBAR (SINKRON DENGAN HOME) ═══ --}}
<nav class="navbar navbar-expand-lg sticky-top bg-white shadow-sm">
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
                        <svg viewBox="0 0 24 24" width="22"><path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z" fill="var(--purple-dark)"/></svg>
                    </div>
                </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

{{-- ═══ CONTENT ═══ --}}
<div class="container py-5">
    <h1 class="page-title text-white">Pilih Durasi</h1>

    <div class="row justify-content-center">
        <div class="col-lg-7 col-md-9">
            <form action="{{ route('booking.payment.process') }}" method="POST">
                @csrf
                {{-- Tambahkan input hidden untuk total harga agar terbawa ke controller --}}
                <input type="hidden" name="harga" x-bind:value="baseHarga * duration">
                
                {{-- Input hidden lainnya tetap sama --}}
                <input type="hidden" name="room_id" value="{{ $room['id'] ?? '' }}">
                <input type="hidden" name="tipe" value="{{ $tipe ?? 'reguler' }}">
                <input type="hidden" name="paket" value="{{ request('paket') }}">
                <input type="hidden" name="kategori" value="{{ request('kategori') }}">
                <input type="hidden" name="tanggal" x-bind:value="tanggal">
                <input type="hidden" name="waktu" x-bind:value="confirmedTime">
                <input type="hidden" name="metode_pembayaran" x-bind:value="paymentMethod">

               
                <div class="booking-card text-center text-white">
                    <span class="section-badge">Ruangan & Paket Terpilih</span>
                    
                    <div class="d-flex flex-column align-items-center mt-2">
                        {{-- Nama Ruangan --}}
                        <h2 class="mb-1" style="font-family: 'Fredoka One'; color: var(--yellow);">
                            {{ $room['nama'] }}
                        </h2>
                        
                        {{-- Nama Paket (Misal: Paket Couple / Paket Seru) --}}
                        <div class="px-4 py-1 rounded-pill mb-3" style="background: rgba(255,255,255,0.1); border: 1px solid var(--purple-light);">
                            <span class="fw-black text-uppercase tracking-wider" style="font-size: 0.9rem;">
                                <i class="fa-solid fa-box-open me-2 text-warning"></i>{{ $paket }}
                            </span>
                        </div>

                        <p class="text-white-50 fw-bold mb-0">
                            Fasilitas: Playstation — {{ ucfirst($tipe) }} Edition
                        </p>
                        
                        <div class="price-final mt-2">
                            Rp <span x-text="totalHarga"></span>
                        </div>
                    </div>
                </div>

                {{-- ── PILIH TANGGAL ── --}}
                <div class="booking-card">
                    <span class="section-badge">Pilih Tanggal</span>
                    <input type="date" x-model="tanggal" min="{{ now()->format('Y-m-d') }}" 
                           class="form-control form-control-lg border-0 shadow-sm mt-2">
                </div>

                {{-- ── PILIH WAKTU ── --}}
                <div class="booking-card">
                    <span class="section-badge">Pilih Waktu Main</span>
                    <div class="time-grid">
                        @php
                            $times = ['10.00','10.30','11.00','11.30','12.00','12.30','13.00','13.30','14.00','14.30','15.00','15.30','16.00','16.30','17.00','17.30','18.00','18.30','19.00','19.30','20.00','20.30','21.00','21.30','22.00','22.30','23.00','23.30'];
                            $booked = []; 
                        @endphp

                        @foreach($times as $time)
                            <button type="button" 
                                    @click="tempTime = '{{ $time }}'"
                                    class="btn-time {{ in_array($time, $booked) ? 'time-full' : '' }}"
                                    :class="tempTime === '{{ $time }}' ? 'active' : ''"
                                    {{ in_array($time, $booked) ? 'disabled' : '' }}>
                                {{ $time }}
                            </button>
                        @endforeach
                    </div>
                    
                    <div class="d-flex justify-content-between mt-4 pt-3 border-top border-white-50">
                        <button type="button" @click="tempTime = ''; confirmedTime = ''" class="btn btn-light fw-bold">Batal</button>
                        <button type="button" @click="confirmedTime = tempTime" class="btn-submit-booking px-4 py-2">Pilih Jam</button>
                    </div>
                </div>

                {{-- ── PILIH DURASI MAIN ── --}}
                <div class="booking-card">
                    <span class="section-badge">Pilih Durasi (Jam)</span>
                    <div class="duration-grid">
                        @for ($h = 1; $h <= 6; $h++)
                            <button type="button" 
                                    @click="duration = {{ $h }}"
                                    class="btn-duration"
                                    :class="duration === {{ $h }} ? 'active' : ''">
                                {{ $h }} Jam
                            </button>
                        @endfor
                    </div>
                    <input type="hidden" name="durasi" x-bind:value="duration">
                </div>

                {{-- ── OPSI PEMBAYARAN ── --}}
                <div class="booking-card">
                    <span class="section-badge">Opsi Pembayaran</span>
                    <div class="row g-2 mt-1">
                        <div class="col-6">
                            <button type="button" @click="paymentMethod = 'DP'" 
                                    class="payment-btn" :class="paymentMethod === 'DP' ? 'active' : ''">Down Payment</button>
                        </div>
                        <div class="col-6">
                            <button type="button" @click="paymentMethod = 'Full'" 
                                    class="payment-btn" :class="paymentMethod === 'Full' ? 'active' : ''">Full Payment</button>
                        </div>
                    </div>
                </div>

                {{-- ── RINGKASAN ── --}}
                <div class="booking-card border-warning">
                    <span class="section-badge">Ringkasan Pesanan</span>
                    <div class="total-box mt-3">
                        <div class="total-row">
                            <span class="text-white-50">Tanggal:</span>
                            <span class="fw-bold" x-text="tanggal"></span>
                        </div>
                        <div class="total-row">
                            <span class="text-white-50">Durasi:</span>
                            <span class="fw-bold" x-text="duration + ' Jam'"></span>
                        </div>
                        <div class="total-row border-0">
                            <span class="h4 m-0 text-white">Total Tagihan:</span>
                            <span class="price-final m-0">Rp <span x-text="totalHarga"></span></span>
                        </div>
                    </div>

                    <div class="row mt-4 g-3">
                        <div class="col-6">
                            <a href="{{ url('/booking') }}" class="btn btn-outline-light w-100 py-3 fw-bold rounded-pill">Kembali</a>
                        </div>
                        <div class="col-6">
                            <button type="submit" :disabled="!confirmedTime" 
                                    class="btn-submit-booking w-100 py-3 rounded-pill">LANJUTKAN</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>