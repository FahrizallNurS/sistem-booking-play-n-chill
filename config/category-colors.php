<?php

/**
 * Sumber tunggal untuk label & warna kategori.
 * Dipakai oleh:
 *  - resources/views/superadmin/beranda-sa/index.blade.php
 *  - resources/views/superadmin/analitik/analisis-pendapatan.blade.php
 *
 * Catatan: 'color' di sini mengikuti konvensi nama warna project
 * (dipakai sebagai suffix class "bg-{color}" atau langsung sebagai hex
 * untuk Chart.js), BUKAN class lengkap seperti "bg-purple".
 */

return [

    // Card "Analisis Pendapatan" di beranda-sa/index
    'analisis_pendapatan' => [
        'booking' => ['label' => 'Pendapatan Booking', 'color' => 'purple'],
        'fnb'     => ['label' => 'Penjualan F&B', 'color' => 'purple-light'],
    ],

    // Card "Produk Layanan / Sewa" di beranda-sa/index
    'produk_layanan' => [
        'vip'     => ['label' => 'VIP Room', 'color' => 'info'],
        'regular' => ['label' => 'Regular Area', 'color' => 'info-light'],
    ],

    // Card "Produk F&B" di beranda-sa/index
    'produk_fnb' => [
        'best_seller' => ['label' => 'Iced Cafe Latte', 'color' => 'success'],
        'runner_up'   => ['label' => 'French Fries', 'color' => 'success-light'],
    ],

    // Card "Metode Pembayaran" di beranda-sa/index
    'metode_pembayaran' => [
        'qris' => ['label' => 'QRIS', 'color' => 'warning'],
        'cash' => ['label' => 'Cash', 'color' => 'warning-light'],
    ],

    // Card "Laporan Transaksi" di beranda-sa/index
    'laporan_transaksi' => [
        'selesai'    => ['label' => 'Selesai', 'color' => 'danger'],
        'dibatalkan' => ['label' => 'Dibatalkan', 'color' => 'danger-medium'],
        'refund'     => ['label' => 'Refund', 'color' => 'danger-light'],
    ],

    // Grafik & tabel kategori ruangan di analitik/analisis-pendapatan
    // (hex dipakai langsung oleh Chart.js lewat <x-chart>)
    'kategori_ruangan' => [
        'regular'        => ['label' => 'Regular', 'color' => '#f97316'],
        'private_room'   => ['label' => 'Private Room', 'color' => '#8b5cf6'],
        'coworking'      => ['label' => 'Coworking', 'color' => '#ec4899'],
        'internal_event' => ['label' => 'Internal Event', 'color' => '#10b981'],
        'pas_bebas'      => ['label' => 'Pas Bebas', 'color' => '#3b82f6'],
    ],

];