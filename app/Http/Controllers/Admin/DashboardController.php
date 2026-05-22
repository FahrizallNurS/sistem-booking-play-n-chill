<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TrTransaksi;
use App\Models\User;
use App\Models\MsRuangan;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPelanggan = User::where('role', 'pelanggan')->count();
        $totalBooking   = TrTransaksi::count();
        $totalRuangan   = MsRuangan::count();
        $pendapatanTotal = TrTransaksi::whereIn('status_sewa', ['dikonfirmasi', 'selesai'])->sum('total_harga');

        $bookingTerbaru = TrTransaksi::with(['pengguna', 'penetapanHarga.ruangan', 'penetapanHarga.paket'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalPelanggan',
            'totalBooking',
            'totalRuangan',
            'pendapatanTotal',
            'bookingTerbaru'
        ));
    }
}