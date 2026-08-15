<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ProdukLayananController extends Controller
{
    public function index(Request $request)
    {
        // 1. DATA GRAFIK (4 Kategori Layanan)
        $chartLabels = [];
        $dataPs5 = []; $dataBioskop = []; $dataKaraoke = []; $dataGamingPrivate = [];
        
        // Simulasi pembuatan data selama 30 hari
        for ($i = 0; $i < 30; $i++) {
            $chartLabels[] = Carbon::create(2026, 6, $i + 1)->translatedFormat('d M');
            $dataPs5[] = rand(2000000, 6000000);         // Hijau
            $dataBioskop[] = rand(1000000, 8000000);     // Oranye
            $dataKaraoke[] = rand(3000000, 7000000);     // Biru
            $dataGamingPrivate[] = rand(1000000, 9000000); // Ungu
        }

        $chartDatasets = [
            ['label' => 'PS5 VIP Room', 'data' => $dataPs5, 'color' => '#10b981'],
            ['label' => 'Mini Bioskop Standard', 'data' => $dataBioskop, 'color' => '#f97316'],
            ['label' => 'Mini Karaoke', 'data' => $dataKaraoke, 'color' => '#0ea5e9'],
            ['label' => 'Gaming Private', 'data' => $dataGamingPrivate, 'color' => '#8b5cf6'],
        ];

        // 2. DATA TABEL
        $tableData = [
            ['paket' => 'PS5 VIP Room', 'kategori' => 'VIP', 'sub' => 'PS5-VIP', 'harga' => 25000, 'jam' => 1, 'sku' => 'PKG-001'],
            ['paket' => 'Mini Bioskop Standard', 'kategori' => 'VVIP', 'sub' => 'CINEMA-VVIP', 'harga' => 50000, 'jam' => 2, 'sku' => 'PKG-002'],
            ['paket' => 'Mini Karaoke', 'kategori' => 'VVIP', 'sub' => 'KARAOKE-VVIP', 'harga' => 35000, 'jam' => 1, 'sku' => 'PKG-003'],
            ['paket' => 'PS-4 REGULAR', 'kategori' => 'REGULAR', 'sub' => 'PS4-REGULAR', 'harga' => 75000, 'jam' => 1, 'sku' => 'PKG-004'],
            ['paket' => 'Karaoke Family Suite', 'kategori' => 'VVIP', 'sub' => 'KARAOKE-VVIP', 'harga' => 120000, 'jam' => 1, 'sku' => 'PKG-005'],
        ];

        return view('superadmin.produk-layanan.index', compact(
            'chartLabels',
            'chartDatasets',
            'tableData'
        ));
    }
}