<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/logo_dumb.png') }}">
    <title>Pilih Durasi - Play N Chill</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-purple-700 text-white min-h-screen"
      x-data="{
          tempTime: '',
          confirmedTime: '',
          paymentMethod: 'Full Payment',
          tanggal: '{{ now()->format('Y-m-d') }}',
          harga: '{{ number_format($room['harga'] ?? 0, 0, ',', '.') }}'
      }">

{{-- ═══ NAVBAR ═══ --}}
<nav class="bg-white sticky top-0 z-50 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 flex items-center justify-between h-16">

        <a href="{{ url('/') }}">
            <img src="{{ asset('images/logo_dumb.png') }}" alt="Play N Chill" class="h-10">
        </a>

        <div class="flex items-center gap-2">
            <a href="{{ url('/') }}" class="text-gray-700 font-bold text-sm px-3 py-1 hover:text-purple-700">Home</a>
            <a href="{{ url('/booking') }}" class="bg-purple-700 text-white font-bold text-sm px-5 py-2 rounded-full">Booking</a>
            <a href="{{ url('/gallery') }}" class="text-gray-700 font-bold text-sm px-3 py-1 hover:text-purple-700">Gallery</a>

            @auth
                <div class="relative" x-data="{ open: false }">
                    <div @click="open = !open"
                         class="w-9 h-9 rounded-full bg-purple-100 flex items-center justify-center cursor-pointer">
                        <svg class="w-5 h-5 fill-purple-700" viewBox="0 0 24 24">
                            <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/>
                        </svg>
                    </div>
                    <div x-show="open" @click.away="open = false"
                         class="absolute right-0 mt-2 w-44 bg-white rounded-xl shadow-lg py-2 z-50">
                        <span class="block px-4 py-1 text-xs text-gray-400 font-bold">{{ auth()->user()->name }}</span>
                        <a href="{{ url('/profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Profil Saya</a>
                        <hr class="my-1">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="w-full text-left px-4 py-2 text-sm text-red-500 hover:bg-gray-50">
                                Keluar (Logout)
                            </button>
                        </form>
                    </div>
                </div>
            @endauth
        </div>
    </div>
</nav>

{{-- ═══ CONTENT ═══ --}}
<div class="max-w-3xl mx-auto py-10 space-y-6 px-4">

    <h1 class="text-center text-3xl font-bold uppercase tracking-wider">Pilih Durasi</h1>

    <form action="{{ route('booking.store') }}" method="POST">
        @csrf
        <input type="hidden" name="room_id"   value="{{ $room['id'] ?? '' }}">
        <input type="hidden" name="tipe"       value="{{ $tipe ?? 'reguler' }}">
        <input type="hidden" name="paket"      value="{{ request('paket') }}">
        <input type="hidden" name="kategori"   value="{{ request('kategori') }}">
        <input type="hidden" name="tanggal"    x-bind:value="tanggal">
        <input type="hidden" name="waktu"      x-bind:value="confirmedTime">
        <input type="hidden" name="metode_pembayaran" x-bind:value="paymentMethod">

        <div class="space-y-6">

            {{-- ── INFO PAKET TERPILIH ── --}}
            <div class="bg-purple-600 p-8 rounded-xl shadow-lg border border-purple-500 flex flex-col items-center text-center">
                <span class="bg-orange-500 px-4 py-1 rounded-full text-xs font-black uppercase tracking-widest shadow-md">
                    Paket Terpilih
                </span>
                <h2 class="text-3xl mt-4 font-bold tracking-tight text-white">
                    {{ $room['nama'] ?? 'Ruangan' }}
                </h2>
                <div class="mt-2 flex items-center gap-3">
                    <div class="h-px w-8 bg-purple-400"></div>
                    <p class="text-purple-200 font-medium uppercase text-sm tracking-tighter">
                        {{ ucfirst(request('kategori', 'Playstation')) }} — {{ ucfirst($tipe ?? 'reguler') }}
                    </p>
                    <div class="h-px w-8 bg-purple-400"></div>
                </div>
                <p class="mt-3 text-orange-300 font-black text-xl">
                    Rp <span x-text="harga"></span>
                </p>
            </div>

            {{-- ── PILIH TANGGAL ── --}}
            <div class="bg-purple-600 p-5 rounded-xl shadow-lg border border-purple-500">
                <span class="bg-orange-500 px-3 py-1 rounded text-sm font-bold uppercase">Pilih Tanggal</span>
                <input type="date"
                       x-model="tanggal"
                       min="{{ now()->format('Y-m-d') }}"
                       class="w-full mt-4 p-3 rounded text-black focus:ring-4 focus:ring-orange-500 outline-none">
            </div>

            {{-- ── PILIH WAKTU ── --}}
            <div class="bg-purple-600 p-5 rounded-xl shadow-lg border border-purple-500">
                <span class="bg-orange-500 px-3 py-1 rounded text-sm font-bold uppercase">Pilih Waktu</span>

                <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-2 mt-4">
                    @php
                        $times = [
                            '10.00','10.30','11.00','11.30','12.00','12.30',
                            '13.00','13.30','14.00','14.30','15.00','15.30',
                            '16.00','16.30','17.00','17.30','18.00','18.30',
                            '19.00','19.30','20.00','20.30','21.00','21.30',
                            '22.00','22.30','23.00','23.30',
                        ];
                        // Slot yang sudah terisi (nanti dari database)
                        $bookedTimes = [];
                    @endphp

                    @foreach($times as $time)
                        @if(in_array($time, $bookedTimes))
                            {{-- Slot penuh --}}
                            <span class="bg-lime-300 text-black text-center py-2 rounded font-bold opacity-80 cursor-not-allowed italic text-sm">
                                {{ $time }}
                            </span>
                        @else
                            {{-- Slot tersedia --}}
                            <button type="button"
                                @click="tempTime = '{{ $time }}'"
                                :class="tempTime === '{{ $time }}'
                                    ? 'bg-white text-orange-600 scale-95 shadow-inner ring-2 ring-orange-300'
                                    : 'bg-orange-500 text-white hover:bg-orange-400'"
                                class="py-2 rounded font-bold transition-all duration-200 text-sm">
                                {{ $time }}
                            </button>
                        @endif
                    @endforeach
                </div>

                <div class="flex justify-between mt-6 border-t border-purple-500 pt-4">
                    <button type="button"
                            @click="tempTime = ''; confirmedTime = ''"
                            class="bg-white text-purple-700 px-6 py-2 rounded font-bold hover:bg-gray-200 transition">
                        Batal
                    </button>
                    <button type="button"
                            @click="confirmedTime = tempTime"
                            class="bg-orange-500 px-6 py-2 rounded font-bold shadow-lg hover:bg-orange-600 transition">
                        Pilih
                    </button>
                </div>
            </div>

            {{-- ── OPSI PEMBAYARAN ── --}}
            <div class="bg-purple-600 p-5 rounded-xl shadow-lg border border-purple-500">
                <span class="bg-orange-500 px-3 py-1 rounded text-sm font-bold uppercase">Opsi Pembayaran</span>
                <div class="flex gap-4 mt-4">
                    <button type="button"
                        @click="paymentMethod = 'Down Payment / DP'"
                        :class="paymentMethod === 'Down Payment / DP'
                            ? 'bg-white text-purple-700 ring-2 ring-orange-500'
                            : 'bg-gray-300 text-black'"
                        class="px-6 py-3 rounded-lg font-bold flex-1 transition-all">
                        Down Payment / DP
                    </button>
                    <button type="button"
                        @click="paymentMethod = 'Full Payment'"
                        :class="paymentMethod === 'Full Payment'
                            ? 'bg-orange-500 text-white ring-2 ring-white'
                            : 'bg-gray-300 text-black'"
                        class="px-6 py-3 rounded-lg font-bold flex-1 transition-all">
                        Full Payment
                    </button>
                </div>
            </div>

            {{-- ── TOTAL PESANAN ── --}}
            <div class="bg-purple-600 p-5 rounded-xl shadow-xl border-2 border-orange-500/30">
                <span class="bg-orange-500 px-3 py-1 rounded text-sm font-bold uppercase italic">Total Pesanan</span>

                <div class="bg-purple-500/50 p-6 rounded-lg mt-4 space-y-3">
                    <div class="flex justify-between border-b border-purple-400 pb-2">
                        <span class="text-purple-200 font-medium">Ruangan:</span>
                        <span class="font-bold">{{ $room['nama'] ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between border-b border-purple-400 pb-2">
                        <span class="text-purple-200 font-medium">Tanggal:</span>
                        <span class="font-bold" x-text="tanggal"></span>
                    </div>
                    <div class="flex justify-between border-b border-purple-400 pb-2">
                        <span class="text-purple-200 font-medium">Jam Main:</span>
                        <span class="font-bold text-orange-400"
                              x-text="confirmedTime || '- Belum Pilih -'"></span>
                    </div>
                    <div class="flex justify-between border-b border-purple-400 pb-2">
                        <span class="text-purple-200 font-medium">Metode Pembayaran:</span>
                        <span class="font-bold" x-text="paymentMethod"></span>
                    </div>
                    <div class="flex justify-between items-center pt-2">
                        <span class="text-xl">Total:</span>
                        <span class="text-2xl font-black text-orange-400">
                            Rp <span x-text="harga"></span>
                        </span>
                    </div>
                </div>

                {{-- Validasi error --}}
                @if($errors->any())
                    <div class="mt-4 bg-red-500/30 border border-red-400 rounded-lg p-3">
                        <ul class="text-sm space-y-1">
                            @foreach($errors->all() as $error)
                                <li>• {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="flex justify-between mt-6 gap-4">
                    <a href="{{ url('/booking') }}"
                       class="bg-white/20 text-white px-8 py-3 rounded-xl font-bold hover:bg-white/30 transition text-center flex-1">
                        Kembali
                    </a>
                    <button type="submit"
                        :class="{ 'opacity-50 cursor-not-allowed pointer-events-none': !confirmedTime }"
                        class="bg-orange-500 text-white px-8 py-3 rounded-xl font-bold shadow-lg hover:bg-orange-600 transition flex-1">
                        Lanjutkan
                    </button>
                </div>
            </div>

        </div>
    </form>
</div>

</body>
</html>