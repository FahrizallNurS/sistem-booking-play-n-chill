<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin - Data Pengguna</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .bg-sidebar-dark { background-color: #4a37b1; }
        .bg-sidebar-light { background-color: #b5acee; }
        .bg-purple-main { background-color: #7b6ad4; }
        .bg-lime-custom { background-color: #d1ff00; }
        .text-active { color: #4a37b1 !important; }
        
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="flex h-screen font-sans overflow-hidden">

    <div class="w-56 flex flex-col h-full shadow-xl">
        <div class="bg-sidebar-dark h-32 flex items-center justify-center">
            <h2 class="text-2xl font-normal text-white italic tracking-tighter">Super Admin</h2>
        </div>
        <div class="bg-sidebar-light h-4"></div>
        <nav class="flex flex-col">
            <a href="#" class="bg-lime-custom py-5 px-6 text-sm font-normal text-active uppercase">Data Pengguna</a>
            <div class="bg-sidebar-light h-4"></div>
            <a href="#" class="bg-purple-main py-5 px-6 text-sm font-normal text-white uppercase">Laporan</a>
        </nav>
        <div class="flex-1 bg-sidebar-light opacity-60"></div>
    </div>

    <div class="flex-1 flex flex-col">
        <header class="bg-purple-main h-24 flex items-center px-10 gap-8">
            <div class="bg-white rounded-full w-14 h-14 flex items-center justify-center shadow-lg">
                <i class="fa-solid fa-user text-3xl text-gray-400"></i>
            </div>
            <div class="flex items-center bg-white rounded-full overflow-hidden w-[450px] h-11">
                <input type="text" class="px-6 py-2 w-full outline-none text-gray-700 font-normal" placeholder="Search...">
                <button class="bg-lime-custom h-full px-6 text-active font-normal text-xs flex items-center gap-2">
                    search <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </div>
        </header>

        <main class="flex-1 p-12 bg-white overflow-y-auto no-scrollbar">
            <h1 class="text-5xl font-normal mb-12 text-gray-900">Data Pengguna</h1>

            <div class="flex justify-end mb-6">
                <button onclick="openModal()" class="bg-sidebar-dark hover:brightness-110 text-white text-[11px] font-normal py-2.5 px-5 rounded-lg shadow-md flex items-center gap-2 transition">
                    <i class="fa-solid fa-plus"></i> Tambah Pengguna
                </button>
            </div>

            <div class="rounded-3xl overflow-hidden shadow-2xl border border-gray-100">
                <table class="w-full text-center border-collapse">
                    <thead>
                        <tr class="bg-sidebar-dark text-white text-lg">
                            <th class="py-5 font-normal border-r border-indigo-400">Id.</th>
                            <th class="py-5 font-normal border-r border-indigo-400">User Name</th>
                            <th class="py-5 font-normal border-r border-indigo-400">Email</th>
                            <th class="py-5 font-normal border-r border-indigo-400">Nomor Telp.</th>
                            <th class="py-5 font-normal border-r border-indigo-400">Role</th>
                            <th class="py-5 font-normal">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="bg-lime-custom text-active font-normal text-base">
                            <td class="py-4 border-r border-white">1.</td>
                            <td class="py-4 border-r border-white">ppp</td>
                            <td class="py-4 border-r border-white">ppp@gmail.com</td>
                            <td class="py-4 border-r border-white">08123456789</td>
                            <td class="py-4 border-r border-white">Pelanggan</td>
                            <td class="py-4 flex justify-center gap-3">
                                <button class="bg-indigo-300 p-2 rounded-full text-white"><i class="fa-solid fa-pencil text-xs"></i></button>
                                <button class="bg-orange-400 p-2 rounded-full text-white"><i class="fa-solid fa-eraser text-xs"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <div id="modalTambah" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-2xl w-[600px] overflow-hidden">
            <div class="bg-[#e45d35] p-4">
                <h2 class="text-white text-lg font-normal">Detail Tambah Pengguna</h2>
            </div>
            
            <div class="p-8">
                <div class="grid grid-cols-2 gap-x-12 gap-y-6">
                    <div>
                        <label class="block text-gray-800 font-normal mb-1">Username</label>
                        <input type="text" class="w-full border border-gray-300 rounded px-3 py-1.5 focus:outline-none focus:border-indigo-500 font-normal" placeholder="Gojo Satoru">
                        
                        <label class="block text-gray-800 font-normal mt-4 mb-1">No. Telepon</label>
                        <input type="text" class="w-full border border-gray-300 rounded px-3 py-1.5 focus:outline-none focus:border-indigo-500 font-normal" placeholder="0857...">
                    </div>
                    <div>
                        <label class="block text-gray-800 font-normal mb-1">Role</label>
                        <select class="w-full border border-gray-300 rounded px-3 py-1.5 focus:outline-none focus:border-indigo-500 font-normal">
                            <option>Super User</option>
                            <option>Admin</option>
                        </select>

                        <label class="block text-gray-800 font-normal mt-4 mb-1">Email</label>
                        <input type="email" class="w-full border border-gray-300 rounded px-3 py-1.5 focus:outline-none focus:border-indigo-500 font-normal" placeholder="example@mail.com">
                    </div>
                </div>

                <div class="flex justify-between mt-12 px-4">
                    <button class="bg-[#2ecc71] text-white px-10 py-2.5 rounded-xl font-normal hover:brightness-105 transition">
                        Konfirmasi Tambah
                    </button>
                    <button onclick="closeModal()" class="bg-[#e45d35] text-white px-10 py-2.5 rounded-xl font-normal hover:brightness-105 transition">
                        Batalkan Tambah
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Fungsi untuk Membuka Modal
        function openModal() {
            const modal = document.getElementById('modalTambah');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        // Fungsi untuk Menutup Modal
        function closeModal() {
            const modal = document.getElementById('modalTambah');
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }

        // Menutup modal jika area luar kotak putih diklik
        window.onclick = function(event) {
            const modal = document.getElementById('modalTambah');
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
</body>
</html>