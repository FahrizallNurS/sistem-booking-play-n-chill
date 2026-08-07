<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

class MetodePembayaranController extends Controller
{
    public function index(Request $request)
    {
        // 1. DUMMY DATA GRAFIK (QRIS vs Tunai)
        $chartLabels = [];
        $dataQris = []; 
        $dataTunai = [];
        
        // Simulasi pembuatan data selama 30 hari
        for ($i = 0; $i < 30; $i++) {
            $chartLabels[] = Carbon::create(2026, 6, $i + 1)->translatedFormat('d M');
            $dataQris[] = rand(100000, 500000);  // Ungu
            $dataTunai[] = rand(50000, 450000);  // Abu-abu
        }

        $chartDatasets = [
            ['label' => 'QRIS', 'data' => $dataQris, 'color' => '#6f42c1'], // Ungu
            ['label' => 'Tunai', 'data' => $dataTunai, 'color' => '#d1d5db'], // Abu-abu / Silver
        ];

        // 2. DUMMY DATA TABEL
        // Mengoreksi sedikit penamaan kolom (typo dari desain mock-up) agar secara data lebih masuk akal
        $tableData = [
            ['metode' => 'QRIS', 'jml_trx' => 289, 'total_pendapatan' => 9000000, 'persentase' => '55%', 'rata_rata' => 31141],
            ['metode' => 'TUNAI', 'jml_trx' => 136, 'total_pendapatan' => 9250000, 'persentase' => '45%', 'rata_rata' => 68014],
        ];

        // 3. DATA KARTU RINGKASAN (SUMMARY CARDS)
        $summary = [
            'total_trx' => 425,
            'total_revenue' => 18250000,
            'most_used_name' => 'Tunai',
            'most_used_tx' => 245,
            'highest_rev_name' => 'QRIS',
            'highest_rev_amount' => 6250000,
        ];

        return view('superadmin.metode-pembayaran.index', compact(
            'chartLabels',
            'chartDatasets',
            'tableData',
            'summary'
        ));
    }
}