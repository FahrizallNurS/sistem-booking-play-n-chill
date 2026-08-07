{{--
    Komponen badge status transaksi.
    Dipakai di tabel Laporan Transaksi maupun di dalam modal detail (Booking & F&B),
    supaya warna & label status konsisten di semua tempat — cukup diubah di satu file ini.

    Penggunaan:
        <x-status-badge :status="$t->status_sewa" />
--}}
@php
    $key = strtolower(trim($status ?? ''));

    $map = [
        'selesai'    => ['bg' => '#2ecc71', 'label' => 'Selesai'],
        'dibatalkan' => ['bg' => '#dc3545', 'label' => 'Dibatalkan'],
        'refund'     => ['bg' => '#e08e0b', 'label' => 'Refund'],
    ];

    $style = $map[$key] ?? ['bg' => '#6c757d', 'label' => $status ? ucfirst($status) : '-'];
@endphp
<span
    class="badge text-white"
    style="background-color: {{ $style['bg'] }}; padding: 6px 14px; border-radius: 4px; font-weight: 500;"
>
    {{ $style['label'] }}
</span>