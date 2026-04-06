<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TinjauLaporanController extends Controller
{
    public function index()
    {
        // Panggil file tinjau-laporan.blade.php
        return view('superadmin.tinjau-laporan'); 
    }
}