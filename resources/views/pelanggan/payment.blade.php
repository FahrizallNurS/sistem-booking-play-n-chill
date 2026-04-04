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
            background-image: 
                linear-gradient(135deg, #A8E014 15%, transparent 15%),
                linear-gradient(225deg, #A8E014 10%, transparent 10%);
            background-size: 100% 100%;
            background-attachment: fixed;
        }
        .glass-card {
            background: rgba(85, 60, 200, 0.97);
            border: 1px solid rgba(255, 255, 255, 0.15);
        }
    </style>
</head>
<body class="min-h-screen text-white pb-12">

    <!-- Header Full Width -->
    <nav class="bg-white w-full shadow-md mb-8">
        <div class="w-full px-6 py-3 flex justify-between items-center">
            <!-- Logo Kiri -->
            <a href="/" class="flex items-center gap-2 hover:opacity-80 transition-opacity">
                <div class="w-12 h-12 bg-[#4B32A8] rounded-xl flex items-center justify-center font-black text-white text-[10px] text-center p-1 uppercase leading-none shadow-md">
                    Play N Chill<br> 
                </div>
            </a>

            <!-- Menu Kanan (Bisa Dipencet Semua) -->
            <div class="flex items-center gap-4 md:gap-8 text-sm font-bold text-gray-800">
                <a href="/home" class="hover:text-indigo-600 transition-colors">Home</a>
                <a href="/booking" class="bg-[#9B87F5] text-white px-6 py-2 rounded-full shadow-md hover:bg-indigo-600 transition-all">
                    Booking
                </a>
                <a href="/gallery" class="hover:text-indigo-600 transition-colors">Gallery</a>
                
                <!-- Profil Bisa Dipencet -->
                <a href="/profile" class="group">
                    <div class="w-12 h-12 bg-[#E86B32] rounded-full flex items-center justify-center border-2 border-white overflow-hidden shadow-lg group-hover:scale-110 transition-transform">
                        <img src="https://ui-avatars.com/api/?name=Gojo+Satoru&background=E86B32&color=fff" alt="Profile" class="w-full h-full">
                    </div>
                </a>
            </div>
        </div>
    </nav>

    <!-- Container Utama (Dilebarkan ke max-w-2xl) -->
    <div class="max-w-2xl mx-auto px-6">
        
        <h2 class="text-4xl font-black text-center mb-10 uppercase tracking-tighter drop-shadow-lg">
            Informasi Pembayaran
        </h2>

        <!-- Area Konten Vertikal (Tumpuk Bawah) -->
        <div class="space-y-8">
            
            <!-- 1. Ringkasan Booking -->
            <div class="glass-card rounded-3xl overflow-hidden shadow-2xl">
                <div class="bg-[#E86B32] inline-block px-8 py-2 font-extrabold text-sm rounded-br-3xl uppercase tracking-wider shadow-md">
                    Ringkasan Booking
                </div>
                
                <div class="p-8 space-y-4">
                    <div class="flex justify-between border-b border-white/10 pb-3">
                        <span class="text-white/60 text-sm">Paket</span>
                        <span class="font-bold text-base">Gaming Private Room</span>
                    </div>
                    <div class="flex justify-between border-b border-white/10 pb-3">
                        <span class="text-white/60 text-sm">Durasi</span>
                        <span class="font-bold text-base">2 Jam</span>
                    </div>
                    <div class="flex justify-between border-b border-white/10 pb-3">
                        <span class="text-white/60 text-sm">Tanggal</span>
                        <span class="font-bold text-base">01-02-2005</span>
                    </div>
                    <div class="flex justify-between border-b border-white/10 pb-3">
                        <span class="text-white/60 text-sm">Jam Mulai</span>
                        <span class="font-bold text-base">11.00 WIB</span>
                    </div>
                    <div class="flex justify-between border-b border-white/10 pb-3">
                        <span class="text-white/60 text-sm">Nama</span>
                        <span class="font-bold text-base">Gojo Satoru</span>
                    </div>
                    <div class="flex justify-between border-b border-white/10 pb-3">
                        <span class="text-white/60 text-sm">Email</span>
                        <span class="font-bold text-base lowercase">gojosatoru@gmail.com</span>
                    </div>
                    <div class="flex justify-between border-b border-white/10 pb-3">
                        <span class="text-white/60 text-sm">No. HP</span>
                        <span class="font-bold text-base">085646789021</span>
                    </div>

                    <div class="flex justify-between pt-6 items-center">
                        <span class="font-black text-lg uppercase italic text-white/90 tracking-tighter">Total Pembayaran</span>
                        <span class="font-black text-3xl text-white drop-shadow-md">Rp. 192.000</span>
                    </div>
                </div>
            </div>

            <!-- 2. Informasi Transfer -->
            <div class="glass-card rounded-3xl overflow-hidden shadow-2xl">
                <div class="bg-[#E86B32] inline-block px-8 py-2 font-extrabold text-sm rounded-br-3xl uppercase tracking-wider shadow-md">
                    Informasi Transfer
                </div>

                <div class="p-8 space-y-6">
                    <!-- Bank BCA -->
                    <div class="bg-white rounded-2xl p-6 flex items-center gap-6 shadow-xl">
                        <div class="w-24 flex-shrink-0">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/5c/Bank_Central_Asia.svg/1200px-Bank_Central_Asia.svg.png" alt="Logo BCA" class="w-full">
                        </div>
                        <div class="text-gray-900 border-l-2 pl-6 border-gray-100 flex-grow">
                            <p class="font-black text-lg uppercase leading-tight tracking-tight">Bank Central Asia</p>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">A/N Play n Chill</p>
                            <p class="text-indigo-800 font-extrabold underline decoration-2 tracking-widest text-xl mt-1">1112233705</p>
                        </div>
                    </div>

                    <!-- QRIS -->
                    <div class="bg-white rounded-2xl overflow-hidden shadow-xl">
                        <div class="bg-[#E86B32] inline-block px-5 py-1 font-bold text-[10px] text-white rounded-br-2xl uppercase">
                            Qris
                        </div>
                        <div class="p-6 flex items-center gap-8">
                            <div class="w-36 h-36 flex-shrink-0 bg-white p-2 border-2 border-dashed border-gray-200 rounded-2xl">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=PlayNChillMadiun" alt="QRIS" class="w-full h-full object-contain">
                            </div>
                            <div class="text-gray-900 space-y-2">
                                <p class="font-black text-base uppercase leading-tight">Play N Chill Madiun</p>
                                <div class="space-y-1">
                                    <p class="text-xs font-bold text-gray-600 uppercase">NMID: <span class="text-gray-900 font-black">ID123456789</span></p>
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-tighter">TID: 002345001</p>
                                </div>
                                <p class="text-[11px] text-gray-400 italic mt-3 font-medium">*Silakan scan menggunakan mobile banking atau e-wallet</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tombol Status Booking (Bisa Dipencet) -->
            <a href="/status-booking" class="block w-full bg-white text-[#4B32A8] font-black py-5 rounded-3xl shadow-[0_8px_0_rgb(210,210,210)] 
            active:translate-y-1.5 active:shadow-[0_3px_0_rgb(210,210,210)] transition-all flex items-center justify-center gap-4 uppercase tracking-tighter hover:bg-gray-50 text-xl group">
                Lihat Status Booking 
                <span class="text-[#E86B32] text-3xl group-hover:translate-x-2 transition-transform">➜</span>
            </a>

        </div>
    </div>

</body>
</html>