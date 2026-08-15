@php
config(['adminlte.menu' => array_merge(
    config('adminlte.menu', []),
    [
        ['header' => 'MENU UTAMA'],
        ['text' => 'Dashboard', 'url' => 'admin/dashboard', 'icon' => 'fas fa-fw fa-tachometer-alt', 'active' => ['admin/dashboard']],
        ['text' => 'Data Pelanggan', 'url' => 'admin/pelanggan', 'icon' => 'fas fa-fw fa-users'],
        ['text' => 'Kelola Booking', 'url' => 'admin/booking', 'icon' => 'fas fa-fw fa-calendar-check'],
        ['text' => 'Layanan & Ruangan', 'url' => 'admin/layanan', 'icon' => 'fas fa-fw fa-concierge-bell'],
        ['text' => 'Paket', 'url' => 'admin/paket', 'icon' => 'fas fa-fw fa-box'],
        
        ['header' => 'MANAJEMEN F&B'],
        [
            'text' => 'Kasir POS',
            'icon' => 'fas fa-fw fa-utensils',
            'active' => ['admin/fb*'], 
            'submenu' => [
                [
                    'text' => 'Data Produk',
                    'url' => 'admin/fb/produk',
                ],
                [
                    'text' => 'Kelola Transaksi F&B',
                    'url' => 'admin/fb/transaksi',
                ],
            ]
        ],
        
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
@endphp@push('js')<script>
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
</script>@endpush