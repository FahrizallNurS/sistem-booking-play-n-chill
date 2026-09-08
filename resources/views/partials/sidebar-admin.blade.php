@php
// =========================================================
// 1. HITUNG DATA YANG MEMBUTUHKAN TINDAKAN (PENDING)
// =========================================================
// Booking yang belum dikonfirmasi (status ditahan)
$pendingBookingCount = \App\Models\TrTransaksi::where('status_sewa', 'ditahan')->count();

// Pesanan F&B yang belum selesai (status Menunggu)
$pendingFnbCount = \App\Models\TrPos::where('status_pesanan', 'Menunggu')->count();


// =========================================================
// 2. SIAPKAN MENU KELOLA BOOKING PINTAR
// =========================================================
$menuKelolaBooking = [
    'text' => 'Kelola Booking',
    'url'  => 'admin/booking',
    'icon' => 'fas fa-fw fa-calendar-check',
];

if ($pendingBookingCount > 0) {
    $menuKelolaBooking['label']       = $pendingBookingCount;
    $menuKelolaBooking['label_color'] = 'danger badge-fnb-pending'; // Pakai animasi yang sama
}


// =========================================================
// 3. SIAPKAN MENU F&B PINTAR (PARENT & SUBMENU)
// =========================================================
$menuKelolaFnb = [
    'text' => 'Kelola Transaksi F&B',
    'url'  => 'admin/fb/transaksi',
];

$menuKasirPos = [
    'text'    => 'Kasir POS',
    'icon'    => 'fas fa-fw fa-utensils',
    'active'  => ['admin/fb*'],
];

if ($pendingFnbCount > 0) {
    // Pasang badge di submenu
    $menuKelolaFnb['label']       = $pendingFnbCount;
    $menuKelolaFnb['label_color'] = 'danger badge-fnb-pending';
    
    // Pasang badge di menu induk (Kasir POS)
    $menuKasirPos['label']        = $pendingFnbCount;
    $menuKasirPos['label_color']  = 'danger badge-fnb-pending';
}

$menuKasirPos['submenu'] = [
    [
        'text' => 'Data Produk',
        'url'  => 'admin/fb/produk',
    ],
    $menuKelolaFnb,
];


// =========================================================
// 4. GABUNGKAN KE KONFIGURASI ADMIN LTE
// =========================================================
config(['adminlte.menu' => array_merge(
    config('adminlte.menu', []),
    [
        ['header' => 'MENU UTAMA'],
        ['text' => 'Dashboard', 'url' => 'admin/dashboard', 'icon' => 'fas fa-fw fa-tachometer-alt', 'active' => ['admin/dashboard']],
        ['text' => 'Data Pelanggan', 'url' => 'admin/pelanggan', 'icon' => 'fas fa-fw fa-users'],
        
        $menuKelolaBooking, // <--- Menu Kelola Booking Pintar masuk di sini
        
        ['text' => 'Layanan & Ruangan', 'url' => 'admin/layanan', 'icon' => 'fas fa-fw fa-concierge-bell'],
        ['text' => 'Paket', 'url' => 'admin/paket', 'icon' => 'fas fa-fw fa-box'],
        
        ['header' => 'MANAJEMEN F&B'],
        $menuKasirPos, // <--- Menu Kasir POS Pintar masuk di sini
        
        ['header' => 'KONTEN'],
        ['text' => 'Katalog Game', 'url' => 'admin/game', 'icon' => 'fas fa-fw fa-gamepad'],
        ['text' => 'Banner', 'url' => 'admin/banner', 'icon' => 'far fa-fw fa-flag'],
        ['text' => 'Video', 'url' => 'admin/video', 'icon' => 'fas fa-fw fa-video'],
        ['text' => 'Galeri', 'url' => 'admin/galeri', 'icon' => 'far fa-fw fa-image', 'active' => ['admin/galeri*']],
        
        ['header' => 'LAPORAN'],
        ['text' => 'Riwayat Transaksi', 'url' => 'admin/laporan', 'icon' => 'fas fa-fw fa-file-alt'],
        
        ['header' => 'PENGATURAN SISTEM'],
        ['text' => 'Pengaturan', 'url' => 'admin/pengaturan', 'icon' => 'fas fa-fw fa-cogs', 'active' => ['admin/pengaturan']],
        
        ['header' => 'AKUN'],
        ['text' => 'Profil', 'url' => 'admin/profil', 'icon' => 'fas fa-fw fa-user-cog'],
        ['text' => 'Keluar', 'url' => '#', 'icon' => 'fas fa-fw fa-sign-out-alt', 'id' => 'logout-link'],
    ]
)]);
@endphp

@push('css')
<style>
    /* Animasi loncat lembut (Soft Bounce) yang elegan */
    @keyframes soft-bounce {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-3px);
        }
    }

    .badge-fnb-pending {
        animation: soft-bounce 2s infinite ease-in-out;
        box-shadow: 0 0 8px rgba(220, 53, 69, 0.6); /* Efek cahaya (glow) merah */
        border-radius: 50px !important;
        padding: 4px 8px !important;
        font-weight: bold !important;
        display: inline-block;
    }
</style>
@endpush

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const logoutLink = document.getElementById('logout-link');
        if (logoutLink) {
            logoutLink.addEventListener('click', function(e) {
                e.preventDefault();

                if (typeof konfirmasiAksi === 'function') {
                    konfirmasiAksi({
                        title: 'Keluar dari akun?',
                        text: 'Anda perlu login kembali untuk mengakses dashboard.',
                        icon: 'question',
                        confirmText: 'Ya, keluar',
                    }).then(function (result) {
                        if (result.isConfirmed) {
                            document.getElementById('logout-form').submit();
                        }
                    });
                } else if (confirm('Yakin ingin logout?')) {
                    document.getElementById('logout-form').submit();
                }
            });
        }
    });
</script>

<script>
    function fetchNotifikasi() {
        // Ambil data dari URL API yang baru kita buat
        fetch("{{ url('admin/api/notifikasi-sidebar') }}")
            .then(response => response.json())
            .then(data => {
                // Update otomatis angka di masing-masing menu
                updateBadgeNotif('Kelola Booking', data.booking);
                updateBadgeNotif('Kelola Transaksi F&B', data.fnb);
                updateBadgeNotif('Kasir POS', data.fnb);
            })
            .catch(error => console.error('Gagal mengambil data notifikasi:', error));
    }

    function updateBadgeNotif(menuText, count) {
        // Cari elemen menu berdasarkan teks namanya
        let links = document.querySelectorAll('.nav-link');
        links.forEach(link => {
            let p = link.querySelector('p');
            if (p && p.textContent.includes(menuText)) {
                let badge = p.querySelector('.badge-fnb-pending');

                if (count > 0) {
                    // Jika ada antrean dan badgenya belum ada, ciptakan badgenya!
                    if (!badge) {
                        badge = document.createElement('span');
                        badge.className = 'right badge bg-danger badge-fnb-pending';
                        p.appendChild(badge);
                    }
                    // Update angkanya secara real-time
                    badge.textContent = count;
                } else {
                    // Jika 0 (sudah diselesaikan semua), hapus badgenya
                    if (badge) {
                        badge.remove();
                    }
                }
            }
        });
    }

    // 1. Jalankan langsung saat halaman pertama kali dibuka
    fetchNotifikasi();

    // 2. Lakukan pengecekan berulang secara diam-diam setiap 10 detik (10000 ms)
    setInterval(fetchNotifikasi, 10000);
</script>
@endpush