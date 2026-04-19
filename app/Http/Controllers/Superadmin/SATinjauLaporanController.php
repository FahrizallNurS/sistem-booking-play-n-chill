<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SATinjauLaporanController extends Controller
{
    public function index()
    {
        return view('superadmin.tinjau-laporan.index');
    }
}
