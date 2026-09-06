<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/logo_dumb.png') }}">
    <title>Pembayaran F&B - Play N Chill</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800&family=Modak&display=swap" rel="stylesheet">
    
    <style>
        body {
            background-color: #3f3192;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            font-family: 'Nunito', sans-serif;
            padding: 20px;
        }
        .payment-modal {
            background: linear-gradient(180deg, #5746b8 0%, #4a3ba5 100%);
            /* Border biru telah dihapus sesuai permintaan */
            border-radius: 12px;
            padding: 40px 30px;
            width: 100%;
            max-width: 600px;
            color: #ffffff;
            box-shadow: 0 15px 40px rgba(0,0,0,0.3);
            position: relative;
        }
        .title-modak {
            font-family: 'Modak', cursive;
            font-size: 2.2rem;
            letter-spacing: 1px;
            margin-bottom: 5px;
            text-align: center;
        }
        .subtitle {
            text-align: center;
            font-size: 0.95rem;
            color: #d1c4e9;
            margin-bottom: 25px;
            line-height: 1.4;
        }
        .divider-line {
            height: 1px;
            background-color: rgba(255, 255, 255, 0.2);
            margin: 0 auto 20px auto;
            width: 90%;
        }
        .order-number {
            text-align: center;
            font-size: 0.9rem;
            margin-bottom: 15px;
        }
        .total-payment {
            text-align: center;
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 10px;
        }
        .total-amount {
            color: #ffd700;
        }
        .logo-text {
            font-family: 'Modak', cursive;
            font-size: 1.8rem;
            text-align: center;
            margin-bottom: 20px;
        }
        .qr-container {
            background-color: #ffffff;
            border-radius: 12px;
            padding: 30px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px auto;
            width: 85%;
        }
        .qr-box {
            background-color: #111;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
        }
        .qr-box img {
            width: 240px;  /* Ukuran diperbesar */
            height: 240px; /* Ukuran diperbesar */
            object-fit: contain; /* Memastikan QR code utuh tidak terpotong */
            border-radius: 8px;
        }
        .qris-text {
            color: #111;
            font-weight: 800;
            font-size: 1rem;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .info-box {
            font-size: 0.85rem;
            color: #d1c4e9;
            line-height: 1.5;
            margin-bottom: 30px;
            padding: 0 10px;
        }
        .btn-group-custom {
            display: flex;
            gap: 15px;
            padding: 0 10px;
        }
        .btn-batal {
            flex: 1;
            background-color: #ffffff;
            color: #333333;
            font-weight: 800;
            border: none;
            padding: 14px 10px;
            border-radius: 50px;
            font-size: 0.95rem;
            transition: 0.2s;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
        }
        .btn-batal:hover {
            background-color: #f0f0f0;
        }
        .btn-bayar {
            flex: 1;
            background-color: #ff7a00;
            color: #ffffff;
            font-weight: 800;
            border: none;
            padding: 14px 10px;
            border-radius: 50px;
            font-size: 0.95rem;
            transition: 0.2s;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
        }
        .btn-bayar:hover {
            background-color: #e06b00;
        }

        /* PERBAIKAN UNTUK LAYAR HP (MOBILE RESPONSIVE) */
        @media (max-width: 576px) {
            body {
                padding: 12px; /* Mengurangi gap di luar kotak modal */
            }
            .payment-modal {
                padding: 30px 15px; /* Memperlebar ruang di dalam modal */
            }
            .qr-container {
                width: 100%; /* Kotak QR dipenuhkan */
                padding: 20px;
            }
            .btn-group-custom {
                gap: 8px; /* Jarak antar tombol diperkecil sedikit */
                padding: 0;
            }
            .btn-batal, .btn-bayar {
                font-size: 0.8rem; /* Mengecilkan teks agar muat 1 baris */
                padding: 12px 8px;
                white-space: nowrap; /* Mencegah teks turun ke baris baru */
            }
            .title-modak {
                font-size: 1.8rem;
            }
            .total-payment {
                font-size: 1.25rem;
            }
            .logo-text {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>

<div class="payment-modal">
    
    @if($metode === 'QRIS')
        {{-- TAMPILAN JIKA PILIH QRIS --}}
        <div class="title-modak">Pembayaran QRIS</div>
        <div class="subtitle">Silahkan selesaikan pembayaran untuk menyelesaikan pesanan<br>makanan dan minuman Anda.</div>
        
        <div class="divider-line"></div>
        
        <div class="order-number">
            Nomor Pesanan: <strong>FNBPNC-{{ str_pad($pos->id_pos, 3, '0', STR_PAD_LEFT) }}</strong>
        </div>
        
        <div class="total-payment">
            Total Pembayaran: <span class="total-amount">Rp. {{ number_format($pos->total_pos, 0, ',', '.') }}</span>
        </div>
        
        <div class="logo-text">
            <span>Play</span> <span style="color: #ffd700;">N Chill</span>
        </div>

         @php
            // Hitung sisa waktu dari created_at + 15 menit vs waktu sekarang
            $waktuKadaluarsa = \Carbon\Carbon::parse($pos->created_at)->addMinutes(15);
            $sisaDetik = now()->diffInSeconds($waktuKadaluarsa, false);
        @endphp

        <div class="text-center mb-4" id="timerContainer">
            <div style="font-size: 0.9rem; color: #d1c4e9; margin-bottom: 4px;">Sisa Waktu Pembayaran:</div>
            <div id="countdown-timer" style="font-size: 2.2rem; font-weight: 800; color: #ffd700; font-family: 'Fredoka One', cursive; letter-spacing: 2px;">
                --:--
            </div>
        </div>

        <div class="qr-container shadow">
            <div class="qr-box">
                <img src="{{ asset('images/qris_play_n_chill_hd.png') }}" alt="QR Code QRIS">
            </div>
            <div class="qris-text">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M0 .5A.5.5 0 0 1 .5 0h3a.5.5 0 0 1 0 1H1v2.5a.5.5 0 0 1-1 0v-3Zm12 0a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0V1h-2.5a.5.5 0 0 1-.5-.5ZM.5 12a.5.5 0 0 1 .5.5V15h2.5a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5v-3a.5.5 0 0 1 .5-.5Zm15 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1 0-1H15v-2.5a.5.5 0 0 1 .5-.5ZM4 4h1v1H4V4Z"/>
                    <path d="M7 2H2v5h5V2ZM3 3h3v3H3V3Zm2 8H4v1h1v-1Z"/>
                    <path d="M7 9H2v5h5V9Zm-4 1h3v3H3v-3Zm8-6h1v1h-1V4Z"/>
                    <path d="M9 2h5v5H9V2Zm1 1v3h3V3h-3ZM8 8v2h1v1H8v1h2v-2h1v2h1v-1h2v-1h-3V8H8Zm2 2H9V9h1v1Zm4 2h-1v1h-2v1h3v-2Zm-4 2v-1H8v1h2Z"/>
                </svg>
                QRIS
            </div>
        </div>
    @else
        {{-- TAMPILAN JIKA PILIH TUNAI --}}
        <div class="title-modak">Pembayaran Tunai</div>
        <div class="subtitle">Silahkan selesaikan pembayaran di meja kasir untuk<br>menyelesaikan pesanan Anda.</div>
        
        <div class="divider-line"></div>
        
        <div class="order-number">
            Nomor Pesanan: <strong>FNBPNC-{{ str_pad($pos->id_pos, 3, '0', STR_PAD_LEFT) }}</strong>
        </div>
        
        <div class="total-payment mb-5">
            Total Pembayaran: <span class="total-amount">Rp. {{ number_format($pos->total_pos, 0, ',', '.') }}</span>
        </div>
        
        <div class="logo-text mb-5">
            <span>Play</span> <span style="color: #ffd700;">N Chill</span>
        </div>
    @endif
<div class="info-box">
        <strong>Informasi Penting:</strong><br>
        @if($metode === 'QRIS')
            Setelah melakukan pembayaran, silakan klik tombol <strong>"SAYA SUDAH BAYAR"</strong> di bawah ini untuk mengirimkan tangkapan layar <i>(screenshot)</i> bukti transfer ke WhatsApp Admin Kasir. Pesanan Anda akan langsung diproses ke dapur setelah bukti diverifikasi.
        @else
            Silakan tunjukkan nomor pesanan ini dan lakukan pembayaran tunai langsung di meja kasir. Pesanan Anda akan langsung diproses ke dapur setelah pembayaran diselesaikan.
        @endif
    </div>

    {{-- KODE TOMBOL YANG BARU DITEMPEL DI SINI --}}
    <div class="btn-group-custom">
        <a href="{{ url('/profile') }}" class="btn-batal text-decoration-none">CEK STATUS</a>
        <a href="{{ route('fb.payment.confirm', $pos->id_pos) }}" target="_blank" class="btn-bayar text-decoration-none">SAYA SUDAH BAYAR</a>
    </div>

</div> 

<script>
    document.addEventListener("DOMContentLoaded", function() {
       let sisaDetik = {{ (isset($sisaDetik) && $sisaDetik > 0) ? $sisaDetik : 0 }};
        const timerElement = document.getElementById('countdown-timer');
        const btnBayar = document.querySelector('.btn-bayar');
        const infoBox = document.querySelector('.info-box');
        const qrContainer = document.getElementById('qrContainer');

        function updateDisplay() {
            if (sisaDetik <= 0) {
                timerElement.innerHTML = "00:00";
                timerElement.style.color = "#ff4c4c"; 

                btnBayar.style.pointerEvents = "none";
                btnBayar.style.backgroundColor = "#6c757d";
                btnBayar.style.color = "#d1d5db";
                btnBayar.innerHTML = "WAKTU HABIS";
                btnBayar.removeAttribute('href');

                if(qrContainer) qrContainer.style.opacity = "0.3";
                infoBox.innerHTML = "<strong style='color:#ff4c4c; font-size:1rem;'>Pembayaran Kadaluarsa</strong><br>Waktu pembayaran Anda (15 menit) telah habis. Pesanan ini otomatis dibatalkan oleh sistem. Silakan buat pesanan baru.";
                return;
            }

            let menit = Math.floor(sisaDetik / 60);
            let detik = Math.floor(sisaDetik % 60);
            let strMenit = menit < 10 ? "0" + menit : menit;
            let strDetik = detik < 10 ? "0" + detik : detik;
            
            timerElement.innerHTML = strMenit + ":" + strDetik;

            if (sisaDetik <= 180) {
                timerElement.style.color = "#ff4c4c";
            }
            
            sisaDetik--;
        }

        updateDisplay();
        if (sisaDetik > 0) {
            setInterval(updateDisplay, 1000);
        }
    });
</script>

</body>
</html>