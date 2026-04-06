<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informasi Pembayaran - Play N Chill</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #4B32A8;
        }
        .glass-card {
            background: rgba(85, 60, 200, 0.97);
            border: 1px solid rgba(255, 255, 255, 0.15);
        }
    </style>
</head>
<body class="min-h-screen text-white pb-12">

    {{-- ═══ NAVBAR ═══ --}}
    <nav class="bg-white w-full shadow-md mb-8">
        <div class="w-full px-6 py-3 flex justify-between items-center">

            <a href="{{ url('/') }}">
                <img src="{{ asset('images/logo_dumb.png') }}" alt="Play N Chill" class="h-10">
            </a>

            <div class="flex items-center gap-4 md:gap-8 text-sm font-bold text-gray-800">
                <a href="{{ url('/') }}" class="hover:text-indigo-600 transition-colors">Home</a>
                <a href="{{ url('/booking') }}" class="bg-[#9B87F5] text-white px-6 py-2 rounded-full shadow-md hover:bg-indigo-600 transition-all">
                    Booking
                </a>
                <a href="{{ url('/gallery') }}" class="hover:text-indigo-600 transition-colors">Gallery</a>

                @auth
                    <div class="relative" x-data="{ open: false }" xmlns:x-data="http://www.w3.org/1999/xhtml">
                        <a href="{{ url('/profile') }}"
                           class="w-11 h-11 bg-orange-500 rounded-full flex items-center justify-center border-2 border-white overflow-hidden shadow-lg hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 fill-white" viewBox="0 0 24 24">
                                <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/>
                            </svg>
                        </a>
                    </div>
                @endauth

            </div>
        </div>
    </nav>

    {{-- ═══ CONTENT ═══ --}}
    <div class="max-w-2xl mx-auto px-6">

        <h2 class="text-4xl font-black text-center mb-10 uppercase tracking-tighter drop-shadow-lg">
            Informasi Pembayaran
        </h2>

        @php
            // Ambil data dari session booking
            $booking = session('booking_data', []);
            $user    = auth()->user();
        @endphp

        <div class="space-y-8">

            {{-- ── 1. RINGKASAN BOOKING ── --}}
            <div class="glass-card rounded-3xl overflow-hidden shadow-2xl">
                <div class="bg-[#E86B32] inline-block px-8 py-2 font-extrabold text-sm rounded-br-3xl uppercase tracking-wider shadow-md">
                    Ringkasan Booking
                </div>

                <div class="p-8 space-y-4">
                    <div class="flex justify-between border-b border-white/10 pb-3">
                        <span class="text-white/60 text-sm">Ruangan</span>
                        <span class="font-bold text-base">{{ $booking['room_id'] ?? '-' }} — {{ ucfirst($booking['tipe'] ?? '-') }}</span>
                    </div>
                    <div class="flex justify-between border-b border-white/10 pb-3">
                        <span class="text-white/60 text-sm">Paket</span>
                        <span class="font-bold text-base">
                            {{ ucfirst($booking['paket'] ?? '-') }} — {{ ucfirst($booking['kategori'] ?? '-') }}
                        </span>
                    </div>
                    <div class="flex justify-between border-b border-white/10 pb-3">
                        <span class="text-white/60 text-sm">Tanggal</span>
                        <span class="font-bold text-base">
                            {{ isset($booking['tanggal'])
                                ? \Carbon\Carbon::parse($booking['tanggal'])->translatedFormat('d F Y')
                                : '-' }}
                        </span>
                    </div>
                    <div class="flex justify-between border-b border-white/10 pb-3">
                        <span class="text-white/60 text-sm">Jam Mulai</span>
                        <span class="font-bold text-base">{{ $booking['waktu'] ?? '-' }} WIB</span>
                    </div>
                    <div class="flex justify-between border-b border-white/10 pb-3">
                        <span class="text-white/60 text-sm">Metode Pembayaran</span>
                        <span class="font-bold text-base">{{ $booking['metode_pembayaran'] ?? '-' }}</span>
                    </div>

                    {{-- Divider --}}
                    <div class="border-t border-white/10 pt-4"></div>

                    <div class="flex justify-between border-b border-white/10 pb-3">
                        <span class="text-white/60 text-sm">Nama</span>
                        <span class="font-bold text-base">{{ $user->name ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between border-b border-white/10 pb-3">
                        <span class="text-white/60 text-sm">Email</span>
                        <span class="font-bold text-base lowercase">{{ $user->email ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between border-b border-white/10 pb-3">
                        <span class="text-white/60 text-sm">No. HP</span>
                        <span class="font-bold text-base">{{ $user->no_hp ?? '-' }}</span>
                    </div>

                    <div class="flex justify-between pt-6 items-center">
                        <span class="font-black text-lg uppercase italic text-white/90 tracking-tighter">
                            Total Pembayaran
                        </span>
                        <span class="font-black text-3xl text-white drop-shadow-md">
                            Rp {{ number_format($booking['harga'] ?? 0, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- ── 2. INFORMASI TRANSFER ── --}}
            <div class="glass-card rounded-3xl overflow-hidden shadow-2xl">
                <div class="bg-[#E86B32] inline-block px-8 py-2 font-extrabold text-sm rounded-br-3xl uppercase tracking-wider shadow-md">
                    Informasi Transfer
                </div>

                <div class="p-8 space-y-6">

                    {{-- Bank BCA --}}
                    <div class="bg-white rounded-2xl p-6 flex items-center gap-6 shadow-xl">
                        <div class="w-24 flex-shrink-0">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/5c/Bank_Central_Asia.svg/1200px-Bank_Central_Asia.svg.png"
                                 alt="Logo BCA" class="w-full">
                        </div>
                        <div class="text-gray-900 border-l-2 pl-6 border-gray-100 flex-grow">
                            <p class="font-black text-lg uppercase leading-tight tracking-tight">Bank Central Asia</p>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">A/N Play n Chill</p>
                            <p class="text-indigo-800 font-extrabold underline decoration-2 tracking-widest text-xl mt-1">
                                1112233705
                            </p>
                        </div>
                    </div>

                    {{-- QRIS --}}
                    <div class="bg-white rounded-2xl overflow-hidden shadow-xl">
                        <div class="bg-[#E86B32] inline-block px-5 py-1 font-bold text-[10px] text-white rounded-br-2xl uppercase">
                            QRIS
                        </div>
                        <div class="p-6 flex items-center gap-8">
                            <div class="w-36 h-36 flex-shrink-0 bg-white p-2 border-2 border-dashed border-gray-200 rounded-2xl">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=PlayNChillMadiun"
                                     alt="QRIS" class="w-full h-full object-contain">
                            </div>
                            <div class="text-gray-900 space-y-2">
                                <p class="font-black text-base uppercase leading-tight">Play N Chill Madiun</p>
                                <div class="space-y-1">
                                    <p class="text-xs font-bold text-gray-600 uppercase">
                                        NMID: <span class="text-gray-900 font-black">ID123456789</span>
                                    </p>
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-tighter">TID: 002345001</p>
                                </div>
                                <p class="text-[11px] text-gray-400 italic mt-3 font-medium">
                                    *Silakan scan menggunakan mobile banking atau e-wallet
                                </p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- ── 3. TOMBOL KONFIRMASI ── --}}
            {{-- Simpan booking ke database --}}
            <form action="{{ route('booking.store') }}" method="POST">
                @csrf
                <input type="hidden" name="room_id"           value="{{ $booking['room_id'] ?? '' }}">
                <input type="hidden" name="tipe"              value="{{ $booking['tipe'] ?? '' }}">
                <input type="hidden" name="paket"             value="{{ $booking['paket'] ?? '' }}">
                <input type="hidden" name="kategori"          value="{{ $booking['kategori'] ?? '' }}">
                <input type="hidden" name="tanggal"           value="{{ $booking['tanggal'] ?? '' }}">
                <input type="hidden" name="waktu"             value="{{ $booking['waktu'] ?? '' }}">
                <input type="hidden" name="metode_pembayaran" value="{{ $booking['metode_pembayaran'] ?? '' }}">
                <input type="hidden" name="confirm"           value="1">

                <button type="submit"
                        class="w-full bg-orange-500 text-white font-black py-5 rounded-3xl shadow-lg
                               hover:bg-orange-600 active:translate-y-1 transition-all
                               flex items-center justify-center gap-4 uppercase tracking-tighter text-xl">
                    Konfirmasi Pembayaran
                    <span class="text-3xl">✓</span>
                </button>
            </form>

            {{-- Lihat Status Booking --}}
            <a href="{{ url('/status-booking') }}"
               class="block w-full bg-white text-[#4B32A8] font-black py-5 rounded-3xl shadow-lg
                      active:translate-y-1 transition-all flex items-center justify-center gap-4
                      uppercase tracking-tighter hover:bg-gray-50 text-xl group">
                Lihat Status Booking
                <span class="text-[#E86B32] text-3xl group-hover:translate-x-2 transition-transform">➜</span>
            </a>

        </div>
    </div>

</body>
</html>