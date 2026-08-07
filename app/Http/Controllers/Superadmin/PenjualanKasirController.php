<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PenjualanKasirController extends Controller
{
    public function index(Request $request)
    {
        // Dummy data masih di-generate di dalam view (lihat blok @php di
        // superadmin/penjualan-kasir/index.blade.php). Nanti kalau query
        // asli sudah siap, hapus blok itu dan kirim $chartLabels,
        // $chartDatasets, $tableData dari sini via compact(...).
        return view('superadmin.penjualan-kasir.index');
    }
}