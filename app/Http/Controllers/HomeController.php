<?php

namespace App\Http\Controllers;

use App\Models\MsPermainan;
use App\Models\MsRuangan;
use App\Models\Video; // 1. Pastikan Model Video sudah di-import di sini

class HomeController extends Controller
{
    public function index()
    {
        $permainans = MsPermainan::with('ruangans')->get();

        // Ambil semua perangkat unik dari ruangan yang aktif
        $perangkats = MsRuangan::where('is_active', 1)
            ->whereNotNull('perangkat')
            ->pluck('perangkat')
            ->unique()
            ->values();

        // 2. Kueri untuk mengambil data video
        $videos = Video::where('is_active', 1)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pelanggan.home', compact('permainans', 'perangkats', 'videos'));
    }
}