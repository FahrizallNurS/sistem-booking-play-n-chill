@php
config(['adminlte.menu' => array_merge(
    config('adminlte.menu', []),
    [
        ['header' => 'MENU UTAMA'],
        ['text' => 'Dashboard', 'url' => 'admin/dashboard', 'icon' => 'fas fa-fw fa-tachometer-alt', 'active' => ['admin/dashboard']],
        ['header' => 'MANAJEMEN'],
        ['text' => 'Data Pelanggan', 'url' => 'admin/pelanggan', 'icon' => 'fas fa-fw fa-users'],
        ['text' => 'Booking', 'url' => 'admin/booking', 'icon' => 'fas fa-fw fa-calendar-check'],
        ['text' => 'Layanan', 'url' => 'admin/layanan', 'icon' => 'fas fa-fw fa-concierge-bell'],
        ['text' => 'Paket', 'url' => 'admin/paket', 'icon' => 'fas fa-fw fa-box'],
        ['header' => 'KONTEN'],
        ['text' => 'Upload Game', 'url' => 'admin/game', 'icon' => 'fas fa-fw fa-gamepad'],
        ['header' => 'LAPORAN'],
        ['text' => 'Laporan', 'url' => 'admin/laporan', 'icon' => 'fas fa-fw fa-file-alt'],
        ['header' => 'AKUN'],
        ['text' => 'Profil', 'url' => 'admin/profil', 'icon' => 'fas fa-fw fa-user-cog'],
        ['text' => 'Keluar', 'url' => '#', 'icon' => 'fas fa-fw fa-sign-out-alt', 'id' => 'logout-link'],
    ]
)]);
@endphp

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const logoutLink = document.getElementById('logout-link');
        if (logoutLink) {
            logoutLink.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Konfirmasi dengan confirm bawaan
                if (confirm('Apakah Anda yakin ingin keluar?')) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route('logout') }}';
                    
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    form.appendChild(csrfToken);
                    
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    });
</script>
@endpush