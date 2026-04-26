<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MsRuangan;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->query('type', 'reguler');

        $kategoriMap = [
            'reguler' => 'REGULAR',
            'vip'     => 'VIP',
            'vvip'    => 'VVIP',
        ];

        $kategori = $kategoriMap[strtolower($type)] ?? 'REGULAR';

        // Ambil ruangan sesuai kategori, yang punya foto
        $ruangans = MsRuangan::where('kategori', $kategori)
            ->where('is_active', 1)
            ->get();

        // Hitung total ruangan per kategori untuk info
        $totalPerKategori = [
            'reguler' => MsRuangan::where('kategori', 'REGULAR')->where('is_active', 1)->count(),
            'vip'     => MsRuangan::where('kategori', 'VIP')->where('is_active', 1)->count(),
            'vvip'    => MsRuangan::where('kategori', 'VVIP')->where('is_active', 1)->count(),
        ];

        return view('pelanggan.gallery', compact('type', 'ruangans', 'totalPerKategori'));
    }
}