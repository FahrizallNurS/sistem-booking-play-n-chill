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
    // Daftar semua ruangan
    public function index()
    {
        $ruangans = MsRuangan::with('kategori')->latest()->get();
        return view('admin.layanan.index', compact('ruangans'));
    }

    // Form tambah ruangan
    public function create()
    {
        $kategoris = MsKategori::all();
        return view('admin.layanan.create', compact('kategoris'));
    }

    // Simpan ruangan baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_ruangan' => 'required|string|max:30',
            'ms_kategori_id_kategori' => 'required|exists:ms_kategori,id_kategori',
            'description' => 'nullable|string',
        ]);

        MsRuangan::create($request->all());
        return redirect()->route('admin.layanan.index')->with('success', 'Ruangan berhasil ditambahkan!');
    }

    // Detail ruangan + pricing
    public function show($id)
    {
        $ruangan = MsRuangan::with(['kategori', 'pricings.paket'])->findOrFail($id);
        $pakets = MsPaket::where('is_active', 1)->get();
        return view('admin.layanan.show', compact('ruangan', 'pakets'));
    }

    // Form edit ruangan
    public function edit($id)
    {
        $ruangan = MsRuangan::findOrFail($id);
        $kategoris = MsKategori::all();
        return view('admin.layanan.edit', compact('ruangan', 'kategoris'));
    }

    // Update ruangan
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_ruangan' => 'required|string|max:30',
            'ms_kategori_id_kategori' => 'required|exists:ms_kategori,id_kategori',
            'description' => 'nullable|string',
        ]);

        $ruangan = MsRuangan::findOrFail($id);
        $ruangan->update($request->all());
        return redirect()->route('admin.layanan.index')->with('success', 'Ruangan berhasil diupdate!');
    }

    // Hapus ruangan
    public function destroy($id)
    {
        MsRuangan::findOrFail($id)->delete();
        return redirect()->route('admin.layanan.index')->with('success', 'Ruangan berhasil dihapus!');
    }

    // Tambah pricing ke ruangan
    public function storePricing(Request $request, $id)
    {
        $request->validate([
            'ms_paket_id_paket' => 'required|exists:ms_paket,id_paket',
            'tipe_pricing' => 'required|in:weekday,weekend,holiday',
            'hari_type' => 'required|in:weekday,weekend,holiday',
            'durasi_menit' => 'required|integer|min:30',
            'harga' => 'required|numeric|min:0',
        ]);

        MsPricing::create([
            'ms_ruangan_id_ruangan' => $id,
            'ms_paket_id_paket' => $request->ms_paket_id_paket,
            'tipe_pricing' => $request->tipe_pricing,
            'hari_type' => $request->hari_type,
            'durasi_menit' => $request->durasi_menit,
            'harga' => $request->harga,
        ]);

        return redirect()->route('admin.layanan.show', $id)->with('success', 'Pricing berhasil ditambahkan!');
    }

    // Hapus pricing
    public function destroyPricing($id)
    {
        MsPricing::findOrFail($id)->delete();
        return back()->with('success', 'Pricing berhasil dihapus!');
    }
}