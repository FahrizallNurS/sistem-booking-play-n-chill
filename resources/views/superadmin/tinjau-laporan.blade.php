<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin - Laporan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .bg-sidebar-dark { background-color: #4a37b1; }
        .bg-sidebar-light { background-color: #b5acee; }
        .bg-purple-main { background-color: #7b6ad4; }
        .bg-lime-custom { background-color: #d1ff00; }
        .text-active { color: #4a37b1 !important; }
        .text-lime-bright { color: #d1ff00; }
        .text-orange-bright { color: #ff8c00; }
        
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="flex h-screen font-sans overflow-hidden">

    <!-- Sidebar -->
    <div class="w-56 flex flex-col h-full shadow-xl">
        <div class="bg-sidebar-dark h-32 flex items-center justify-center">
            <h2 class="text-2xl font-normal text-white italic tracking-tighter">Super Admin</h2>
        </div>
        <div class="bg-sidebar-light h-4"></div>
        <nav class="flex flex-col">
            <!-- Data Pengguna (Sekarang tidak aktif) -->
            <a href="{{ route('superadmin.datauser') }}"
            class="bg-sidebar-light py-5 px-6 text-sm font-normal text-white uppercase hover:brightness-110 transition">
                Data Pengguna
            </a>
            <div class="bg-sidebar-light h-4"></div>
            <!-- Laporan (Aktif - Warna Lime) -->
            <a href="{{ route('superadmin.laporan') }}"
            class="bg-lime-custom py-5 px-6 text-sm font-normal text-active uppercase">
                Laporan
            </a>
        </nav>
        <div class="flex-1 bg-sidebar-light opacity-60"></div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col">
        <!-- Header -->
        <header class="bg-purple-main h-24 flex items-center px-10 gap-8">
            <div class="bg-white rounded-full w-14 h-14 flex items-center justify-center shadow-lg">
                <i class="fa-solid fa-user text-3xl text-gray-400"></i>
            </div>
            <div class="flex items-center bg-white rounded-full overflow-hidden w-[450px] h-11">
                <input type="text" class="px-6 py-2 w-full outline-none text-gray-700 font-normal" placeholder="">
                <button class="bg-lime-custom h-full px-6 text-active font-normal text-xs flex items-center gap-2">
                    search <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </div>
        </header>

        <!-- Content Area -->
        <main class="flex-1 p-12 bg-white overflow-y-auto no-scrollbar">
            <h1 class="text-5xl font-normal mb-12 text-gray-900">Laporan</h1>

            <!-- Table Laporan -->
            <div class="rounded-[2.5rem] overflow-hidden shadow-2xl">
                <table class="w-full text-center border-collapse">
                    <thead>
                        <tr class="bg-sidebar-dark text-white text-xl">
                            <th class="py-6 font-normal border-r border-indigo-400">Id.</th>
                            <th class="py-6 font-normal border-r border-indigo-400">User Name</th>
                            <th class="py-6 font-normal border-r border-indigo-400">Kode Booking</th>
                            <th class="py-6 font-normal border-r border-indigo-400">Tanggal</th>
                            <th class="py-6 font-normal border-r border-indigo-400">Status</th>
                            <th class="py-6 font-normal">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-white">
                        <!-- Baris 1 -->
                        <tr class="bg-purple-main border-b border-indigo-400 font-normal">
                            <td class="py-4 border-r border-indigo-400">BK-01</td>
                            <td class="py-4 border-r border-indigo-400">Gojo Satoru</td>
                            <td class="py-4 border-r border-indigo-400">PSHT1922</td>
                            <td class="py-4 border-r border-indigo-400 text-xs">23/09/2026<br><span class="text-[10px]">14.00 WIB</span></td>
                            <td class="py-4 border-r border-indigo-400 text-lime-bright font-bold">Selesai</td>
                            <td class="py-4 flex justify-center gap-2">
                                <button class="bg-white/20 p-2 rounded-full hover:bg-white/40 transition"><i class="fa-solid fa-eye text-xs"></i></button>
                                <button class="bg-white/20 p-2 rounded-full hover:bg-white/40 transition"><i class="fa-solid fa-pencil text-xs"></i></button>
                            </td>
                        </tr>
                        <!-- Baris 2 -->
                        <tr class="bg-sidebar-light border-b border-indigo-300 font-normal">
                            <td class="py-4 border-r border-indigo-300">BK-01</td>
                            <td class="py-4 border-r border-indigo-300">Gojo Satoru</td>
                            <td class="py-4 border-r border-indigo-300">PSHT1922</td>
                            <td class="py-4 border-r border-indigo-300 text-xs">23/09/2026<br><span class="text-[10px]">14.00 WIB</span></td>
                            <td class="py-4 border-r border-indigo-300 text-lime-bright font-bold">Selesai</td>
                            <td class="py-4 flex justify-center gap-2">
                                <button class="bg-white/20 p-2 rounded-full hover:bg-white/40 transition"><i class="fa-solid fa-eye text-xs"></i></button>
                                <button class="bg-white/20 p-2 rounded-full hover:bg-white/40 transition"><i class="fa-solid fa-pencil text-xs"></i></button>
                            </td>
                        </tr>
                        <!-- Baris 3 (Cencle) -->
                        <tr class="bg-purple-main border-b border-indigo-400 font-normal">
                            <td class="py-4 border-r border-indigo-400">BK-01</td>
                            <td class="py-4 border-r border-indigo-400">Wong Asor</td>
                            <td class="py-4 border-r border-indigo-400">paket couple</td>
                            <td class="py-4 border-r border-indigo-400 text-xs">23/09/2026<br><span class="text-[10px]">14.00 WIB</span></td>
                            <td class="py-4 border-r border-indigo-400 text-orange-bright font-bold">Cencle</td>
                            <td class="py-4 flex justify-center gap-2">
                                <button class="bg-white/20 p-2 rounded-full hover:bg-white/40 transition"><i class="fa-solid fa-eye text-xs"></i></button>
                                <button class="bg-white/20 p-2 rounded-full hover:bg-white/40 transition"><i class="fa-solid fa-pencil text-xs"></i></button>
                            </td>
                        </tr>
                        <!-- Baris 4 -->
                        <tr class="bg-sidebar-light font-normal">
                            <td class="py-4 border-r border-indigo-300">BK-01</td>
                            <td class="py-4 border-r border-indigo-300">Gojo Satoru</td>
                            <td class="py-4 border-r border-indigo-300">PSHT1922</td>
                            <td class="py-4 border-r border-indigo-300 text-xs">23/09/2026<br><span class="text-[10px]">14.00 WIB</span></td>
                            <td class="py-4 border-r border-indigo-300 text-lime-bright font-bold">Selesai</td>
                            <td class="py-4 flex justify-center gap-2">
                                <button class="bg-white/20 p-2 rounded-full hover:bg-white/40 transition"><i class="fa-solid fa-eye text-xs"></i></button>
                                <button class="bg-white/20 p-2 rounded-full hover:bg-white/40 transition"><i class="fa-solid fa-pencil text-xs"></i></button>
                            </td>
                        </tr>
                        <!-- Area bawah tabel yang kosong sesuai gambar -->
                        <tr class="bg-sidebar-light h-64">
                            <td colspan="6"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

</body>
</html>