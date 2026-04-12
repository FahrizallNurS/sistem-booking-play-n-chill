<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin - Play N Chill</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .sidebar-bg { background-color: #F1E9FF; }
        .pnc-purple { background-color: #5E35B1; }
        .pnc-accent { background-color: #D4FF00; }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 antialiased">

    <div class="flex min-h-screen">
        
        <!-- SIDEBAR (BAGIAN KIRI) -->
        <aside class="w-64 sidebar-bg border-r border-indigo-100 flex flex-col shadow-inner shrink-0">
            <!-- Logo Area -->
            <div class="h-20 pnc-purple flex items-center justify-center px-6 shadow-md rounded-br-2xl">
                <span class="text-white text-xl font-extrabold tracking-tighter uppercase">Super<span class="text-pnc-accent text-[#D4FF00]">Admin</span></span>
            </div>

            <!-- Menu Navigasi Sidebar -->
            <nav class="flex-1 pt-8 px-4 space-y-3">
                <p class="px-4 text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-4">Main Menu</p>
                
                <!-- Menu Aktif -->
                <a href="{{ route('superadmin.users') }}"
                    class="flex items-center gap-3.5 px-4 py-3 rounded-xl bg-white text-[#5E35B1] font-bold shadow-sm border border-indigo-50">
                    <i class="fa-solid fa-users text-lg"></i>
                    Data Pengguna
                </a>

                <a href="{{ route('superadmin.laporan') }}"
                    class="flex items-center gap-3.5 px-4 py-3 rounded-xl text-gray-600 font-semibold hover:bg-white hover:text-[#5E35B1] transition-all">
                    <i class="fa-solid fa-file-chart-column text-lg"></i>
                    Laporan
                </a>
            </nav>

            <!-- User Info di Bawah Sidebar -->
            <div class="p-6 border-t border-indigo-100">
                <div class="flex items-center gap-3 p-3 bg-white rounded-2xl shadow-sm border border-indigo-50">
                    <div class="w-10 h-10 bg-[#5E35B1] rounded-full flex items-center justify-center text-white font-bold">GS</div>
                    <div class="overflow-hidden">
                        <p class="font-bold text-xs text-[#5E35B1] truncate">Gojo Satoru</p>
                        <p class="text-[10px] text-gray-400 truncate">admin@pnc.com</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- BAGIAN KANAN (HEADER + KONTEN) -->
        <div class="flex-1 flex flex-col h-screen overflow-y-auto">
            
            <!-- NAVBAR / HEADER -->
            <header class="h-20 bg-white border-b border-gray-100 px-8 flex justify-between items-center sticky top-0 z-20">
                
                <!-- Search Bar -->
                <div class="flex-1 max-w-lg">
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                            <i class="fa-solid fa-magnifying-glass text-sm"></i>
                        </span>
                        <input type="text" placeholder="Cari data..." class="w-full bg-gray-50 border border-gray-200 py-2 pl-12 pr-6 rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-indigo-200">
                        <button class="absolute inset-y-0 right-0 bg-[#D4FF00] px-6 rounded-r-full font-bold text-gray-800 text-xs hover:bg-yellow-400 transition-all">
                            search
                        </button>
                    </div>
                </div>

                <!-- Profile Circle -->
                <div class="flex items-center gap-4 ml-8">
                    <div class="text-right hidden md:block">
                        <p class="text-xs font-bold text-[#5E35B1]">Super Admin</p>
                        <p class="text-[10px] text-gray-400">Online</p>
                    </div>
                    <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center border-2 border-indigo-100 shadow-sm overflow-hidden">
                        <i class="fa-solid fa-circle-user text-[#7E57C2] text-3xl"></i>
                    </div>
                </div>
            </header>

            <!-- ISI KONTEN UTAMA -->
            <main class="p-10">
                <div class="bg-white p-10 rounded-3xl shadow-lg border border-gray-100 min-h-[500px]">
                    <div class="flex justify-between items-start mb-10">
                        <div>
                            <h1 class="text-3xl font-extrabold text-[#5E35B1] tracking-tighter uppercase italic">Data <span class="text-gray-900 not-italic">Pengguna</span></h1>
                            <p class="text-gray-400 text-sm mt-1 font-medium">Kelola semua akun user Play N Chill di sini.</p>
                        </div>
                        <button class="bg-[#5E35B1] text-white px-6 py-3 rounded-2xl font-bold text-xs flex items-center gap-2 hover:bg-indigo-800 transition-all shadow-lg active:scale-95">
                            <i class="fa-solid fa-plus"></i> Tambah User
                        </button>
                    </div>

                    <!-- Placeholder area untuk Tabel nantinya -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                        <div class="p-6 bg-indigo-50 rounded-2xl border border-indigo-100">
                            <p class="text-[10px] font-bold text-indigo-400 uppercase tracking-widest">Total Akun</p>
                            <p class="text-3xl font-black text-[#5E35B1] mt-2">1,250</p>
                        </div>
                        <div class="p-6 bg-green-50 rounded-2xl border border-green-100">
                            <p class="text-[10px] font-bold text-green-400 uppercase tracking-widest">User Aktif</p>
                            <p class="text-3xl font-black text-green-600 mt-2">980</p>
                        </div>
                        <div class="p-6 bg-red-50 rounded-2xl border border-red-100">
                            <p class="text-[10px] font-bold text-red-400 uppercase tracking-widest">Terblokir</p>
                            <p class="text-3xl font-black text-red-600 mt-2">15</p>
                        </div>
                    </div>

                    <div class="border-2 border-dashed border-gray-100 rounded-3xl py-20 flex flex-col items-center justify-center text-gray-300">
                        <i class="fa-solid fa-table-list text-6xl mb-4"></i>
                        <p class="font-bold uppercase tracking-widest text-xs">Belum ada data untuk ditampilkan</p>
                    </div>
                </div>
            </main>

        </div>
    </div>

</body>
</html>