<?php

namespace App\Http\Controllers;

use App\Models\Galeri;

class GalleryController extends Controller
{
    public function index()
    {
        $galeris = Galeri::where('is_active', 1)
            ->where('kategori', '!=', 'banner')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pelanggan.galeri', compact('galeris'));
    }
}