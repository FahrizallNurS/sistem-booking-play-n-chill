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
    </style>
</head>

<body>

<div x-data="{
    tempTime: '',
    confirmedTime: '',
    selectedPricing: null,
    paymentMethod: 'full',
    tanggal: '{{ now()->format('Y-m-d') }}',
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
        return this.jamTerpakai.includes(slot);
    },

    pilihWaktu() {
        if (this.tempTime !== '' && !this.isBlocked(this.tempTime)) {
            this.confirmedTime = this.tempTime;
        }
    },

    batalWaktu() {
        this.tempTime = '';
        this.confirmedTime = '';
    },

    async updateJamTerpakai() {
        this.tempTime = '';
        this.confirmedTime = '';
        try {
            const res = await fetch('/booking/jam-terpakai?room=' + this.roomId + '&tanggal=' + this.tanggal);
            const data = await res.json();
            this.jamTerpakai = data.terpakai;
        } catch(e) {
            console.error('Gagal fetch jam terpakai', e);
        }
    }
}">

{{-- NAVBAR --}}
<nav class="navbar navbar-expand-lg sticky-top bg-white shadow-sm">
    <div class="container-fluid px-4">
        <a class="navbar-brand p-0" href="{{ url('/') }}">
            <img src="{{ asset('images/logo_dumb.png') }}" height="48">
        </a>
    </div>
</nav>

<div class="container py-5">
    <h1 class="page-title text-white">Pilih Durasi</h1>

    <div class="row justify-content-center">
        <div class="col-lg-7 col-md-9">

            <form action="{{ route('booking.store') }}" method="POST">
                @csrf

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

                    @php
                        $times = ['10.00','10.30','11.00','11.30','12.00','12.30','13.00','13.30',
                                  '14.00','14.30','15.00','15.30','16.00','16.30','17.00','17.30',
                                  '18.00','18.30','19.00','19.30','20.00','20.30','21.00','21.30',
                                  '22.00','22.30','23.00','23.30'];
                    @endphp

                    <div class="time-grid">
                        @foreach($times as $time)
                        <button type="button"
                            @click="!isBlocked('{{ $time }}') && (tempTime = '{{ $time }}')"
                            class="btn-time"
                            :class="{
                                'active': tempTime === '{{ $time }}',
                                'blocked': isBlocked('{{ $time }}')
                            }"
                            :disabled="isBlocked('{{ $time }}')">
                            {{ $time }}
                        </button>
                        @endforeach
                    </div>
                    <div class="d-flex gap-3 mt-2 small text-white-50">
                        <span>⬜ Tersedia</span>
                        <span style="text-decoration:line-through">⬜ Penuh</span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <button type="button" @click="batalWaktu()" class="btn btn-light">Batal</button>

                        <span class="text-white-50 small" x-show="tempTime !== ''">
                            Dipilih: <strong x-text="tempTime"></strong>
                        </span>

                        <button type="button"
                            @click="pilihWaktu()"
                            :disabled="tempTime === '' || isBlocked(tempTime)"
                            class="btn-submit-booking">
                            Pilih
                        </button>
                    </div>

                    <div class="mt-2 text-center" x-show="confirmedTime !== ''">
                        <small class="text-success">
                            ✓ Waktu dikonfirmasi: <strong x-text="confirmedTime"></strong>
                        </small>
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

                    <div class="row mt-4">
                        <div class="col-6">
                            <a href="{{ url('/booking') }}" class="btn btn-outline-light w-100">Kembali</a>
                        </div>
                        <div class="col-6">
                            <button type="submit"
                                :disabled="!confirmedTime || !selectedPricing"
                                class="btn-submit-booking w-100">
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
</body>
</html>