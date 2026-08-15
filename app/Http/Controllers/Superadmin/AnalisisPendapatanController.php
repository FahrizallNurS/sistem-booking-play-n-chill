<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AnalisisPendapatanController extends Controller
{
    public function pendapatan(Request $request)
    {
        // 1. DATA GRAFIK (5 Kategori)
        $chartLabels = [];
        $dataRegular = []; $dataPrivate = []; $dataCowork = []; $dataInternal = []; $dataBebas = [];
        
        // Simulasi pembuatan data selama 30 hari
        for ($i = 0; $i < 30; $i++) {
            $chartLabels[] = Carbon::create(2026, 6, $i + 1)->translatedFormat('d M');
            $dataRegular[] = rand(3000000, 6000000);
            $dataPrivate[] = rand(4000000, 8000000);
            $dataCowork[] = rand(1000000, 3000000);
            $dataInternal[] = rand(500000, 1500000);
            $dataBebas[] = rand(2000000, 5000000);
        }

        $chartDatasets = [
            ['label' => 'Regular', 'data' => $dataRegular, 'color' => '#f97316'],
            ['label' => 'Private Room', 'data' => $dataPrivate, 'color' => '#10b981'],
            ['label' => 'Cowork', 'data' => $dataCowork, 'color' => '#8b5cf6'],
            ['label' => 'Internal', 'data' => $dataInternal, 'color' => '#0ea5e9'],
            ['label' => 'Bebas', 'data' => $dataBebas, 'color' => '#eab308'],
        ];

        // 2. DATA TABEL
        $tableData = [
            ['kategori' => 'Regular', 'produk' => 'Layanan', 'trx' => 245, 'total' => 2850000, 'persen' => 22.8, 'rata' => 11632, 'color' => 'bg-purple'],
            ['kategori' => 'Private Room', 'produk' => 'Layanan', 'trx' => 128, 'total' => 3100000, 'persen' => 24.9, 'rata' => 24218, 'color' => 'bg-success'],
            ['kategori' => 'Cowork', 'produk' => 'Layanan', 'trx' => 84, 'total' => 2450000, 'persen' => 19.6, 'rata' => 29166, 'color' => 'bg-warning'],
        ];

        return view('superadmin.analitik.analisis-pendapatan', compact(
            'chartLabels',
            'chartDatasets',
            'tableData'
        ));
    }
}