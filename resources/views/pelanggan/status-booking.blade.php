<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Pesanan - Play N Chill</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #4B32A8;
            background-image: 
                linear-gradient(135deg, #A8E014 15%, transparent 15%),
                linear-gradient(225deg, #A8E014 10%, transparent 10%);
            background-size: 100% 100%;
            background-attachment: fixed;
            margin: 0;
        }
        .glass-card {
            background: rgba(85, 60, 200, 0.97);
            border: 1px solid rgba(255, 255, 255, 0.15);
        }
    </style>
</head>
<body class="min-h-screen text-white pb-12">

<!-- Header Full Width -->
<nav class="bg-white w-full shadow-md mb-10">
    <div class="w-full px-8 py-4 flex justify-between items-center">
        <!-- Logo -->
        <a href="/" class="flex items-center gap-3 hover:opacity-80 transition-opacity">
            <div class="w-12 h-12 bg-[#4B32A8] rounded-xl flex items-center justify-center font-black text-white text-[10px] text-center p-1 uppercase leading-none">
                Play N Chill<br>
            </div>
        </a>

        <!-- Menu Navigasi -->
        <div class="flex items-center gap-8 text-sm font-bold text-gray-800">
            <!-- Link Home -->
            <a href="{{ route('pelanggan.home') }}" class="hover:text-indigo-600 transition-colors">Home</a>
            
            <!-- Link Booking (Button Ungu Muda) -->
            <a href="#" class="bg-[#9B87F5]/30 text-[#4B32A8] px-6 py-2 rounded-full hover:bg-[#9B87F5]/50 transition-all active:scale-95">
                Booking
            </a>
            
            <!-- Link Gallery -->
            <a href="#" class="hover:text-indigo-600 transition-colors">Gallery</a>
            
            <!-- Tombol Profil dengan Icon User -->
            <a href="#" class="group relative">
                <div class="w-11 h-11 bg-[#E86B32] rounded-full flex items-center justify-center border-2 border-white overflow-hidden shadow-lg group-hover:bg-orange-600 transition-all active:scale-90">
                    <!-- Icon User Menggantikan "GS" -->
                    <i class="fa-solid fa-user text-white text-lg"></i>
                </div>
            </a>
        </div>
    </div>
</nav>

    <div class="max-w-3xl mx-auto px-6">
        
        <!-- Judul Utama -->
        <div class="flex items-center justify-center gap-3 mb-10">
            <h2 class="text-4xl font-black uppercase tracking-tighter drop-shadow-lg">Pesanan Berhasil</h2>
            <span class="text-4xl">✅</span>
        </div>

        <div class="space-y-8">
            
            <!-- Box Kode Booking -->
            <div class="glass-card rounded-3xl overflow-hidden shadow-2xl text-center relative">
                <div class="bg-[#E86B32] absolute top-0 left-0 px-8 py-2 font-extrabold text-sm rounded-br-3xl uppercase tracking-wider">
                    Kode Booking
                </div>
                
                <div class="py-16 px-8">
                    <h1 class="text-7xl font-black tracking-widest mb-6">AIXOV</h1>
                    
                    <!-- Status Label (Ini yang nanti kamu ganti logikanya) -->
                    <div class="inline-flex items-center gap-3 bg-white px-8 py-3 rounded-xl border-2 border-red-500 shadow-lg">
                        <span class="text-red-600 font-black text-xl uppercase tracking-tight">Menunggu Pembayaran</span>
                        <span class="text-2xl">⏳</span>
                    </div>
                </div>
            </div>

            <!-- Box Catatan -->
            <div class="glass-card rounded-3xl overflow-hidden shadow-2xl">
                <div class="bg-[#E86B32] inline-block px-8 py-2 font-extrabold text-sm rounded-br-3xl uppercase tracking-wider">
                    Catatan
                </div>
                <div class="p-8">
                    <p class="text-lg leading-relaxed font-medium">
                        Agar pesanan dapat diproses kamu bisa mengirim bukti pembayaran serta kode booking ke WhatsApp admin ya! Untuk batas konfirmasi dapat dilakukan dalam kurun waktu 30 menit.
                    </p>
                </div>
            </div>

            <!-- Box Booking Detail -->
            <div class="glass-card rounded-3xl overflow-hidden shadow-2xl pb-10 relative">
                <!-- Label (Sekarang menempel di pojok kiri atas) -->
                <div class="bg-[#E86B32] absolute top-0 left-0 px-8 py-2 font-extrabold text-sm rounded-br-3xl uppercase tracking-wider">
                    Booking Detail
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 px-10 mt-16">

                    <!-- Date & Time -->
                    <div class="bg-white rounded-2xl p-5 flex items-center gap-4 shadow-lg text-gray-800">
                        <div class="w-14 h-14 bg-indigo-100 rounded-xl flex items-center justify-center text-3xl">
                            📅
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Date & Time</p>
                            <p class="font-black text-lg">1 Februari, 2005</p>
                            <p class="text-xs font-bold text-indigo-500">11.00 - 13.00</p>
                        </div>
                    </div>

                    <!-- Room Info -->
                    <div class="bg-white rounded-2xl p-5 flex items-center gap-4 shadow-lg text-gray-800">
                        <div class="w-14 h-14 bg-orange-100 rounded-xl flex items-center justify-center text-3xl">
                            🛋️
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Room</p>
                            <p class="font-black text-lg uppercase">VIP Room 1</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tombol Konfirmasi -->
            <a href="https://wa.me/your_number" class="block w-full bg-white text-[#4B32A8] font-black py-6 rounded-3xl shadow-[0_8px_0_rgb(210,210,210)] active:translate-y-1.5 active:shadow-[0_3px_0_rgb(210,210,210)] transition-all flex items-center justify-center gap-4 uppercase text-2xl hover:bg-gray-50">
                Konfirmasi Disini
            </a>

        </div>
    </div>

</body>
</html>