<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class PelangganController extends Controller
{
    public function index()
    {
        $pelanggans = User::where('role', 'pelanggan')->latest()->get();
        return view('admin.pelanggan.index', compact('pelanggans'));
    }
}