@php
config(['adminlte.menu' => array_merge(
    config('adminlte.menu', []),
    [
        ['header' => 'MENU UTAMA'],
        ['text' => 'Dashboard', 'url' => 'superadmin/dashboard', 'icon' => 'fas fa-fw fa-tachometer-alt', 'active' => ['superadmin/dashboard']],
        ['header' => 'MANAJEMEN'],
        ['text' => 'Kelola User', 'url' => 'superadmin/data-user', 'icon' => 'fas fa-fw fa-users-cog'],
        ['header' => 'LAPORAN'],
        ['text' => 'Tinjau Laporan', 'url' => 'superadmin/tinjau-laporan', 'icon' => 'fas fa-fw fa-file-alt'],
        ['header' => 'AKUN'],
        ['text' => 'Profil', 'url' => 'superadmin/profil', 'icon' => 'fas fa-fw fa-user-cog'],
        ['text' => 'Keluar', 'url' => 'logout', 'icon' => 'fas fa-fw fa-sign-out-alt'],
    ]
)]);
@endphp