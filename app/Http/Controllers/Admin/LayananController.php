<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MsRuangan;
use App\Models\MsKategori;
use App\Models\MsPaket;
use App\Models\MsPricing;
use Illuminate\Http\Request;

class LayananController extends Controller
{
    public function index()
    {
        $ruangans = MsRuangan::with('kategori')->latest()->get();
        return view('admin.layanan.index', compact('ruangans'));
    }

    public function create()
    {
        $kategoris = MsKategori::all();
        return view('admin.layanan.create', compact('kategoris'));

        $exists = MsPricing::where('ms_ruangan_id_ruangan', $id)
        ->where('ms_paket_id_paket', $request->ms_paket_id_paket)
        ->where('hari_type', $request->hari_type)
        ->where('durasi_menit', $request->durasi_menit)
        ->exists();

        if ($exists) {
        return back()->withErrors([
            'durasi_menit' => 'Pricing dengan kombinasi ini sudah ada'
        ]);
}
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_ruangan'            => 'required|string|max:30',
            'ms_kategori_id_kategori' => 'required|exists:ms_kategori,id_kategori',
            'description'             => 'nullable|string',
            'is_active'               => 'required|in:0,1',
        ]);

        MsRuangan::create([
            'nama_ruangan'            => $request->nama_ruangan,
            'ms_kategori_id_kategori' => $request->ms_kategori_id_kategori,
            'description'             => $request->description,
            'is_active'               => $request->is_active,
        ]);

        return redirect()->route('admin.layanan.index')->with('success', 'Ruangan berhasil ditambahkan!');

        $exists = MsPricing::where('ms_ruangan_id_ruangan', $id)
        ->where('ms_paket_id_paket', $request->ms_paket_id_paket)
        ->where('hari_type', $request->hari_type)
        ->where('durasi_menit', $request->durasi_menit)
        ->exists();

    if ($exists) {
        return back()->withErrors([
            'durasi_menit' => 'Pricing dengan kombinasi ini sudah ada'
        ]);
    }
    }

    public function show($id)
    {
        $ruangan = MsRuangan::with(['kategori', 'pricings.paket'])->findOrFail($id);
        $pakets  = MsPaket::where('is_active', 1)->get();
        return view('admin.layanan.show', compact('ruangan', 'pakets'));
    }

    public function edit($id)
    {
        $ruangan = MsRuangan::with('pricings')->findOrFail($id);
        if ($ruangan->pricings()->exists()) {
        return back()->with('error', 'Ruangan tidak bisa dihapus karena masih memiliki pricing');
        }

        $ruangan->delete();

        $kategoris = MsKategori::all();
        return view('admin.layanan.edit', compact('ruangan', 'kategoris'));

        $exists = MsPricing::where('ms_ruangan_id_ruangan', $id)
            ->where('ms_paket_id_paket', $request->ms_paket_id_paket)
            ->where('hari_type', $request->hari_type)
            ->where('durasi_menit', $request->durasi_menit)
            ->exists();

        if ($exists) {
            return back()->withErrors([
                'durasi_menit' => 'Pricing dengan kombinasi ini sudah ada'
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_ruangan'            => 'required|string|max:30',
            'ms_kategori_id_kategori' => 'required|exists:ms_kategori,id_kategori',
            'description'             => 'nullable|string',
            'is_active'               => 'required|in:0,1',
        ]);

        MsRuangan::findOrFail($id)->update([
            'nama_ruangan'            => $request->nama_ruangan,
            'ms_kategori_id_kategori' => $request->ms_kategori_id_kategori,
            'description'             => $request->description,
            'is_active'               => $request->is_active,
        ]);

        return redirect()->route('admin.layanan.index')->with('success', 'Ruangan berhasil diupdate!');
    }

    public function destroy($id)
    {
        MsRuangan::findOrFail($id)->delete();
        return redirect()->route('admin.layanan.index')->with('success', 'Ruangan berhasil dihapus!');
    }

    public function storePricing(Request $request, $id)
    {
        $request->validate([
            'ms_paket_id_paket' => 'required|exists:ms_paket,id_paket',
            'tipe_pricing'      => 'required|in:weekday,weekend,holiday',
            'hari_type'         => 'required|in:weekday,weekend,holiday',
            'durasi_menit'      => 'required|integer|min:30',
            'harga'             => 'required|numeric|min:0',
        ]);

        MsPricing::create([
            'ms_ruangan_id_ruangan' => $id,
            'ms_paket_id_paket'     => $request->ms_paket_id_paket,
            'tipe_pricing'          => $request->tipe_pricing,
            'hari_type'             => $request->hari_type,
            'durasi_menit'          => $request->durasi_menit,
            'harga'                 => $request->harga,
        ]);

        return redirect()->route('admin.layanan.show', $id)->with('success', 'Pricing berhasil ditambahkan!');
    }

    public function destroyPricing($id)
    {
        MsPricing::findOrFail($id)->delete();
        return back()->with('success', 'Pricing berhasil dihapus!');
    }

    public function getRuanganByKategori($id)
    {
        return MsRuangan::where('ms_kategori_id_kategori', $id)
            ->where('is_active', 1)
            ->get(['id_ruangan', 'nama_ruangan']);
    }
}