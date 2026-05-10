<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User; 

class SABerandaController extends Controller
{
    public function index()
    {
        $totalPelanggan = User::where('role', 'pelanggan')->count();

        return view('superadmin.beranda-sa.index', compact('totalPelanggan'));
    }
}