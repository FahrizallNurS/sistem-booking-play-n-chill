<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User; // <-- 1. WAJIB TAMBAHKAN INI

class SABerandaController extends Controller
{
    public function index()
    {
        // 2. Ambil jumlah user yang rolenya adalah 'pelanggan'
        // Jika di database kamu nama rolenya 'pelanggan', gunakan 'pelanggan'
        $totalPelanggan = User::where('role', 'pelanggan')->count();

        // 3. Kirim variabel $totalPelanggan ke file view
        // Pastikan nama file kamu adalah resources/views/superadmin/dashboard.blade.php
        return view('superadmin.beranda-sa.index', compact('totalPelanggan'));
    }
}