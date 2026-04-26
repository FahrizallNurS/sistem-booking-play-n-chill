<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Play N Chill</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-[#4c34a4] text-slate-800" x-data="{ 
    tempTime: '', 
    confirmedTime: '', 
    paymentMethod: 'Full Payment',
    tanggal: '{{ date('Y-m-d') }}',
    hargaPerJam: 45000,
    durasi: 1,
    paket_id: '1',

    get totalHarga() {
        let total = this.hargaPerJam * this.durasi;
        return new Intl.NumberFormat('id-ID').format(total);
    },

    get jamSelesai() {
        if(!this.confirmedTime) return '';
        let [jam, menit] = this.confirmedTime.split(':').map(Number);
        let jamBaru = (jam + parseInt(this.durasi)) % 24;
        return (jamBaru < 10 ? '0' : '') + jamBaru + ':' + (menit < 10 ? '0' : '') + menit;
    }
}">

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
                                        {{ auth()->user()->name }}
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

<div class="max-w-2xl mx-auto py-12 px-6">
    <div class="text-center mb-10">
        <h1 class="text-4xl font-extrabold text-white tracking-tight">Play <span class="text-orange-400">N</span> Chill</h1>
        <p class="text-purple-100 mt-2 font-medium">Konfigurasi Booking Anda</p>
    </div>

    <form action="{{ route('booking.checkout') }}" method="POST" class="space-y-6">
        @csrf
        <input type="hidden" name="tanggal" :value="tanggal">
        <input type="hidden" name="waktu_mulai" :value="confirmedTime">
        <input type="hidden" name="waktu_selesai" :value="jamSelesai">
        <input type="hidden" name="metode_pembayaran" :value="paymentMethod">
        <input type="hidden" name="paket_id" :value="paket_id">
        <input type="hidden" name="total_harga" :value="totalHarga.replace(/\./g, '')">

        <div class="bg-white p-6 rounded-2xl shadow-xl flex items-center justify-between border-b-4 border-orange-500">
            <div>
                <span class="text-orange-600 text-xs font-black uppercase tracking-widest">Paket Terpilih</span>
                <h2 class="text-2xl font-bold text-slate-900">Paket Couple</h2>
                <p class="text-slate-500 text-sm">VIP PlayStation 4 • Per 2 Jam</p>
            </div>
            <div class="text-right">
                <p class="text-3xl font-black text-orange-600">Rp <span x-text="totalHarga"></span></p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-xl">
            <label class="block text-sm font-black text-slate-700 uppercase mb-4 text-center">Berapa jam Anda ingin Booking?</label>
            <div class="flex justify-center gap-2">
                <template x-for="n in [1, 2, 3, 4, 5]">
                    <button type="button" @click="durasi = n"
                        :class="durasi == n ? 'bg-orange-500 text-white shadow-lg' : 'bg-slate-100 text-slate-500'"
                        class="w-12 h-12 rounded-lg font-black transition-all flex items-center justify-center border-b-2 border-slate-200">
                        <span x-text="n"></span>
                    </button>
                </template>
                <div class="flex items-center ml-2 font-bold text-slate-400 uppercase text-xs">Jam</div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-xl space-y-6">
            <div>
                <label class="block text-sm font-bold text-slate-700 uppercase mb-2">Pilih Tanggal</label>
                <input type="date" x-model="tanggal" min="{{ date('Y-m-d') }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-slate-900 focus:ring-2 focus:ring-orange-500 outline-none">
            </div>

            <div class="border-t border-slate-100 pt-4">
                <label class="block text-sm font-bold text-slate-700 uppercase mb-4">Pilih Jam Mulai</label>
                <div class="grid grid-cols-5 gap-2">
                    @php $times = ['10:00','11:00','12:00','13:00','14:00','15:00','16:00','17:00','18:00','19:00']; @endphp
                    @foreach($times as $time)
                        <button type="button" @click="tempTime = '{{ $time }}'"
                            :class="tempTime === '{{ $time }}' ? 'bg-orange-500 text-white' : 'bg-slate-50 text-slate-600 hover:bg-slate-100'"
                            class="py-2 rounded-lg font-bold text-xs border border-slate-200 transition-all">
                            {{ $time }}
                        </button>
                    @endforeach
                </div>
                
                <div class="mt-4 flex items-center justify-between bg-slate-50 p-3 rounded-xl border border-dashed border-slate-300">
                    <span class="text-xs font-bold text-slate-500 uppercase">Estimasi Waktu</span>
                    <span class="text-sm font-black text-orange-600" x-text="tempTime ? tempTime + ' s/d ' + jamSelesai : 'Pilih Jam...'"></span>
                </div>

                <div class="flex justify-end mt-4">
                    <button type="button" @click="confirmedTime = tempTime" 
                        class="bg-slate-800 text-white px-6 py-2 rounded-xl font-bold text-sm shadow-md hover:bg-slate-900 transition">
                        Konfirmasi Jam
                    </button>
                </div>
            </div>
        </div>

        <div class="bg-orange-500 p-8 rounded-3xl text-white shadow-2xl">
            <div class="space-y-3">
                <div class="flex justify-between border-b border-white/20 pb-2">
                    <span class="font-bold opacity-80 uppercase text-xs tracking-widest">Detail Sesi</span>
                    <span class="font-black" x-text="confirmedTime ? confirmedTime + ' - ' + jamSelesai : '-'"></span>
                </div>
                <div class="flex justify-between items-center pt-2">
                    <span class="text-lg font-bold uppercase">Total</span>
                    <span class="text-3xl font-black">Rp <span x-text="totalHarga"></span></span>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mt-8">
                <a href="{{ url('/booking') }}"class="bg-white/20 text-center py-4 rounded-2xl font-bold hover:bg-white/30 transition">
                    Batal
                </a>
                <button type="submit" :disabled="!confirmedTime"
                    :class="!confirmedTime ? 'bg-black/20 text-white/50 cursor-not-allowed' : 'bg-black text-white hover:scale-[1.02] shadow-xl'"
                    class="py-4 rounded-2xl font-black transition-all uppercase tracking-widest">
                    Lanjutkan
                </button>
            </div>
        </div>
    </form>
</div>
</body>
</html>