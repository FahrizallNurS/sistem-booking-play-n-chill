<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        // Logika untuk menangani filter kategori (reguler, vip, vvip)
        $type = $request->query('type', 'reguler');
        
        return view('pelanggan.gallery', compact('type'));
    }
}