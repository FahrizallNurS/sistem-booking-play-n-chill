<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User; 
use App\Models\TrTransaksi;

class SABerandaController extends Controller
{
    public function index()
    {
        $totalPelanggan = User::where('role', 'pelanggan')->count();
        $totalBooking   = TrTransaksi::count();
        $bookingSelesai = TrTransaksi::where('status_sewa', 'selesai')
            ->count();
        $totalPendapatan = TrTransaksi::where('status_sewa', 'selesai')
            ->sum('total_harga');
        $userTerbaru = User::latest('created_at')
            ->take(5)
            ->get();

        return view('superadmin.beranda-sa.index', compact(
            'totalPelanggan',
            'totalBooking',
            'bookingSelesai',
            'totalPendapatan',
            'userTerbaru'
        ));
    }
}