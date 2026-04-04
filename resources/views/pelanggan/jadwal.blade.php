<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Play N Chill</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-purple-700 text-white" x-data="{ 
    tempTime: '',       // Menampung jam yang diklik (warna berubah)
    confirmedTime: '',  // Menampung jam setelah tombol 'Pilih' diklik
    paymentMethod: 'Full Payment',
    tanggal: '2026-07-12',
    harga: '45.000'
}">

<div class="max-w-3xl mx-auto py-10 space-y-6 px-4">

    <h1 class="text-center text-3xl font-bold uppercase tracking-wider">Pilih Durasi</h1>

    <form action="/booking" method="POST">
        @csrf
        <input type="hidden" name="tanggal" :value="tanggal">
        <input type="hidden" name="waktu" :value="confirmedTime">
        <input type="hidden" name="metode_pembayaran" :value="paymentMethod">

        <div class="space-y-6">
    <div class="bg-purple-600 p-8 rounded-xl shadow-lg border border-purple-500 flex flex-col items-center text-center">
        <span class="bg-orange-500 px-4 py-1 rounded-full text-xs font-black uppercase tracking-widest shadow-md">
            Paket Terpilih
        </span>
        
        <h2 class="text-3xl mt-4 font-bold tracking-tight text-white">
            Paket Couple
        </h2>
        
        <div class="mt-2 flex items-center gap-3">
            <div class="h-[1px] w-8 bg-purple-400"></div>
            <p class="text-purple-200 font-medium uppercase text-sm tracking-tighter">
                VIP PlayStation 4
            </p>
            <div class="h-[1px] w-8 bg-purple-400"></div>
        </div>
    </div>
</div>
            <div class="bg-purple-600 p-5 rounded-xl shadow-lg border border-purple-500">
                <span class="bg-orange-500 px-3 py-1 rounded text-sm font-bold uppercase">Pilih Tanggal</span>
                <input type="date" x-model="tanggal" class="w-full mt-4 p-3 rounded text-black focus:ring-4 focus:ring-orange-500 outline-none">
            </div>

            <div class="bg-purple-600 p-5 rounded-xl shadow-lg border border-purple-500">
                <span class="bg-orange-500 px-3 py-1 rounded text-sm font-bold uppercase">Pilih Waktu</span>

                <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-2 mt-4">
                    @php
                        $times = ['10.00','10.30','11.00','11.30','12.00','12.30','13.00','13.30','14.00','14.30','15.00','15.30','16.00','16.30','17.00','17.30','18.00','18.30','20.30','21.00','21.30','22.00','22.30','23.00','23.30'];
                    @endphp

                    @foreach($times as $time)
                        <button type="button" 
                            @click="tempTime = '{{ $time }}'"
                            :class="tempTime === '{{ $time }}' ? 'bg-white text-orange-600 scale-95 shadow-inner ring-2 ring-orange-300' : 'bg-orange-500 text-white'"
                            class="py-2 rounded font-bold transition-all duration-200 hover:bg-orange-400">
                            {{ $time }}
                        </button>
                    @endforeach

                    <span class="bg-lime-300 text-black text-center py-2 rounded font-bold opacity-80 cursor-not-allowed italic">Penuh</span>
                </div>

                <div class="flex justify-between mt-6 border-t border-purple-500 pt-4">
                    <button type="button" @click="tempTime = ''; confirmedTime = ''" class="bg-white text-purple-700 px-6 py-2 rounded font-bold hover:bg-gray-200 transition">Batal</button>
                    <button type="button" 
                        @click="confirmedTime = tempTime"
                        class="bg-orange-500 px-6 py-2 rounded font-bold shadow-lg hover:bg-orange-600 transition">
                        Pilih
                    </button>
                </div>
            </div>

            <div class="bg-purple-600 p-5 rounded-xl shadow-lg border border-purple-500">
                <span class="bg-orange-500 px-3 py-1 rounded text-sm font-bold uppercase">Opsi Pembayaran</span>
                <div class="flex gap-4 mt-4">
                    <button type="button" 
                        @click="paymentMethod = 'Down Payment / DP'"
                        :class="paymentMethod === 'Down Payment / DP' ? 'bg-white text-purple-700 ring-2 ring-orange-500' : 'bg-gray-300 text-black'"
                        class="px-6 py-3 rounded-lg font-bold flex-1 transition-all">
                        Down Payment / DP
                    </button>
                    <button type="button" 
                        @click="paymentMethod = 'Full Payment'"
                        :class="paymentMethod === 'Full Payment' ? 'bg-orange-500 text-white ring-2 ring-white' : 'bg-gray-300 text-black'"
                        class="px-6 py-3 rounded-lg font-bold flex-1 transition-all">
                        Full Payment
                    </button>
                </div>
            </div>

            <div class="bg-purple-600 p-5 rounded-xl shadow-xl border-2 border-orange-500/30">
                <span class="bg-orange-500 px-3 py-1 rounded text-sm font-bold uppercase italic">Total Pesanan</span>

                <div class="bg-purple-500/50 p-6 rounded-lg mt-4 space-y-3">
                    <div class="flex justify-between border-b border-purple-400 pb-2">
                        <span class="text-purple-200 font-medium">Durasi:</span>
                        <span class="font-bold">2 Jam</span>
                    </div>
                    
                    <div class="flex justify-between border-b border-purple-400 pb-2">
                        <span class="text-purple-200 font-medium">Jam Main:</span>
                        <span class="font-bold text-orange-400" x-text="confirmedTime || '- Belum Pilih -'"></span>
                    </div>

                    <div class="flex justify-between border-b border-purple-400 pb-2">
                        <span class="text-purple-200 font-medium">Metode Pembayaran:</span>
                        <span class="font-bold" x-text="paymentMethod"></span>
                    </div>
                    
                    <div class="flex justify-between items-center pt-2">
                        <span class="text-xl">Total:</span>
                        <span class="text-2xl font-black text-orange-400">Rp <span x-text="harga"></span></span>
                    </div>
                </div>

                <div class="flex justify-between mt-6 gap-4">
                    <a href="/" class="bg-white/20 text-white px-8 py-3 rounded-xl font-bold hover:bg-white/30 transition text-center flex-1">
                        Kembali
                    </a>
                    <button type="submit" 
                        :disabled="!confirmedTime"
                        class="bg-orange-500 text-white px-8 py-3 rounded-xl font-bold shadow-lg hover:bg-orange-600 transition flex-1 disabled:opacity-50 disabled:cursor-not-allowed">
                        Lanjutkan
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

</body>
</html>