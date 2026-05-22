<?php

namespace App\Http\Controllers;

use App\Models\MsPermainan;
use App\Models\MsRuangan;

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

        return view('pelanggan.home', compact('permainans', 'perangkats'));
    }
}