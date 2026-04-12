<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SABerandaController extends Controller
{
    public function index()
    {
        // Pastikan file view-nya ada di resources/views/superadmin/beranda.blade.php
        return view('superadmin.beranda');
    }
}
