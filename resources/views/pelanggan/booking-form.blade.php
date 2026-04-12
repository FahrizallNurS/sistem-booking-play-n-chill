<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/logo_dumb.png') }}">
    <title>Pilih Durasi - Play N Chill</title>

    {{-- CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/booking-form.css') }}">

    {{-- Alpine --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body 
x-data="{
    tempTime: '',
    confirmedTime: '',
    selectedPricing: null,
    paymentMethod: 'Full Payment',
    tanggal: '{{ now()->format('Y-m-d') }}',

    get totalHarga() {
        return this.selectedPricing 
            ? Number(this.selectedPricing.harga).toLocaleString('id-ID') 
            : 0;
    }
}"
>

{{-- NAVBAR --}}
<nav class="navbar navbar-expand-lg sticky-top bg-white shadow-sm">
    <div class="container-fluid px-4">
        <a class="navbar-brand p-0" href="{{ url('/') }}">
            <img src="{{ asset('images/logo_dumb.png') }}" height="48">
        </a>
    </div>
</nav>

{{-- CONTENT --}}
<div class="container py-5">
    <h1 class="page-title text-white">Pilih Durasi</h1>

    <div class="row justify-content-center">
        <div class="col-lg-7 col-md-9">

            <form action="{{ route('booking.payment.process') }}" method="POST">
                @csrf

            <input type="hidden" name="pricing_id" 
            :value="selectedPricing ? selectedPricing.id_pricing : ''">

                {{-- CARD --}}
                <div class="booking-card text-center text-white">
                    <span class="section-badge">Ruangan & Paket</span>

                    <h2 style="font-family: 'Fredoka One'; color: var(--yellow);">
                        {{ $room['nama'] ?? '-' }}
                    </h2>

                    <p class="text-white-50">
                        {{ ucfirst($tipe ?? 'reguler') }}
                    </p>

                    <div class="price-final mt-2">
                        Rp <span x-text="totalHarga"></span>
                    </div>
                </div>

                {{-- TANGGAL --}}
                <div class="booking-card">
                    <span class="section-badge">Tanggal</span>
                    <input type="date"
                        x-model="tanggal"
                        min="{{ now()->format('Y-m-d') }}"
                        class="form-control mt-2">
                </div>

                {{-- WAKTU --}}
                <div class="booking-card">
                    <span class="section-badge">Waktu</span>

                    @php
                        $times = ['10.00','10.30','11.00','11.30','12.00','12.30','13.00','13.30','14.00','14.30','15.00','15.30','16.00','16.30','17.00','17.30','18.00','18.30','19.00','19.30','20.00','20.30','21.00','21.30','22.00','22.30','23.00','23.30'];
                    @endphp

                    <div class="time-grid">
                        @foreach($times as $time)
                        <button type="button"
                            @click="tempTime = '{{ $time }}'"
                            class="btn-time"
                            :class="tempTime === '{{ $time }}' ? 'active' : ''">
                            {{ $time }}
                        </button>
                        @endforeach
                    </div>

                    <div class="d-flex justify-content-between mt-3">
                        <button type="button"
                            @click="tempTime=''; confirmedTime=''"
                            class="btn btn-light">Batal</button>

                        <button type="button"
                            @click="confirmedTime = tempTime"
                            class="btn-submit-booking">Pilih</button>
                    </div>
                </div>

                {{-- DURASI --}}
                <div class="booking-card">
                    <span class="section-badge">Durasi</span>

                 <div class="duration-grid">
                        @foreach($pricings as $p)
                        <button type="button"
                            @click='selectedPricing = @json($p)'
                            class="btn-duration"
                            :class="selectedPricing && selectedPricing.id_pricing === {{ $p->id_pricing }} ? 'active' : ''">

                            {{ $p->durasi_menit / 60 }} Jam
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
                                @click="paymentMethod = 'DP'"
                                class="payment-btn"
                                :class="paymentMethod === 'DP' ? 'active' : ''">
                                DP
                            </button>
                        </div>
                        <div class="col-6">
                            <button type="button"
                                @click="paymentMethod = 'Full Payment'"
                                class="payment-btn"
                                :class="paymentMethod === 'Full Payment' ? 'active' : ''">
                                Full
                            </button>
                        </div>
                    </div>
                </div>

                {{-- SUMMARY --}}
                <div class="booking-card border-warning">
                    <span class="section-badge">Ringkasan</span>

                    <div class="total-box mt-3">
                        <div class="total-row">
                            <span>Tanggal:</span>
                            <span x-text="tanggal"></span>
                        </div>
                        <div class="total-row">
                            <span>Jam:</span>
                            <span x-text="confirmedTime || '-'"></span>
                        </div>
                        <div class="total-row">
                            <span>Durasi:</span>
                            <span x-text="selectedPricing ? (selectedPricing.durasi_menit / 60) + ' Jam' : '-'"></span>
                        </div>
                        <div class="total-row border-0">
                            <span>Total:</span>
                            <span>Rp <span x-text="totalHarga"></span></span>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>