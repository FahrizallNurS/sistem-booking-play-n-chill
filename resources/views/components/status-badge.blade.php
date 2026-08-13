@php
    $key = strtolower(trim($status ?? ''));

    $map = [
        // Status sewa / pesanan
        'selesai'      => ['bg' => '#2ecc71', 'label' => 'Selesai'],
        'dikonfirmasi' => ['bg' => '#0d6efd', 'label' => 'Dikonfirmasi'],
        'ditahan'      => ['bg' => '#f0ad4e', 'label' => 'Ditahan'],
        'dibatalkan'   => ['bg' => '#dc3545', 'label' => 'Dibatalkan'],

        // Status pembayaran (booking & F&B, teks beda tapi makna sama
        // sengaja disamakan warnanya biar konsisten)
        'menunggu'     => ['bg' => '#f0ad4e', 'label' => 'Menunggu'],
        'belum-bayar'  => ['bg' => '#f0ad4e', 'label' => 'Belum Bayar'],
        'dp'           => ['bg' => '#17a2b8', 'label' => 'DP'],
        'lunas'        => ['bg' => '#2ecc71', 'label' => 'Lunas'],
        'sudah-bayar'  => ['bg' => '#2ecc71', 'label' => 'Sudah Bayar'],
        'refund'       => ['bg' => '#e08e0b', 'label' => 'Refund'],
        'kadaluarsa'   => ['bg' => '#6c757d', 'label' => 'Kadaluarsa'],
    ];

    $style = $map[$key] ?? ['bg' => '#6c757d', 'label' => $status ? ucfirst($status) : '-'];
@endphp
<span
    class="badge text-white"
    style="background-color: {{ $style['bg'] }}; padding: 6px 14px; border-radius: 4px; font-weight: 500;"
>
    {{ $style['label'] }}
</span>