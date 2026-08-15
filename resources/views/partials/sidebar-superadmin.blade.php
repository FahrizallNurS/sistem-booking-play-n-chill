@php
config(['adminlte.menu' => array_merge(
    config('adminlte.menu', []),
    [
        ['header' => 'MENU UTAMA'],
        ['text' => 'Dashboard', 'url' => 'superadmin/dashboard', 'icon' => 'fas fa-fw fa-tachometer-alt', 'active' => ['superadmin/dashboard']],

        ['header' => 'MANAJEMEN'],
        ['text' => 'Kelola User', 'url' => 'superadmin/data-user', 'icon' => 'fas fa-fw fa-users-cog', 'active' => ['superadmin/data-user*']],

        ['header' => 'ANALITIK'],
        ['text' => 'Analisis Pendapatan', 'url' => 'superadmin/analisis-pendapatan'],
        ['text' => 'Produk Layanan', 'url' => 'superadmin/produk-layanan', 'icon' => 'fas fa-fw fa-concierge-bell'],
        ['text' => 'Produk F&B', 'url' => 'superadmin/produk-fnb', 'icon' => 'fas fa-fw fa-hamburger'],
        ['text' => 'Penjualan per Kasir', 'url' => 'superadmin/penjualan-kasir', 'icon' => 'fas fa-fw fa-cash-register'],
        ['text' => 'Metode Pembayaran', 'url' => 'superadmin/metode-pembayaran', 'icon' => 'fas fa-fw fa-credit-card'],

        ['header' => 'LAPORAN'],
        ['text' => 'Laporan Transaksi', 'url' => 'superadmin/tinjau-laporan', 'icon' => 'fas fa-fw fa-file-alt', 'active' => ['superadmin/tinjau-laporan*']],

        ['header' => 'AKUN'],
        ['text' => 'Profil', 'url' => 'superadmin/profil', 'icon' => 'fas fa-fw fa-user-cog', 'active' => ['superadmin/profil*']],
        ['text' => 'Keluar', 'url' => '#', 'icon' => 'fas fa-fw fa-sign-out-alt', 'id' => 'logout-link-sa'],
    ]
)]);
@endphp

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const logoutLink = document.getElementById('logout-link-sa');
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
@endpush