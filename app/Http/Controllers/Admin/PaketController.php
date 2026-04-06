<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MsPaket;
use App\Models\MsKategori;
use App\Models\MsRuangan;
use App\Models\MsFasilitas;
use App\Models\MsPricing;
use Illuminate\Http\Request;

class PaketController extends Controller
{
    public function index()
    {
        $pakets = MsPaket::latest()->get();
        return view('admin.paket.index', compact('pakets'));
    }

    public function create()
    {
        $kategoris = MsKategori::all();
        $fasilitas = MsFasilitas::all();
        return view('admin.paket.create', compact('kategoris', 'fasilitas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_paket'            => 'required|string|max:50',
            'deskripsi_paket'       => 'nullable|string',
            'maksimal_orang'        => 'nullable|integer|min:1',
            'is_active'             => 'required|in:0,1',
            'ms_ruangan_id_ruangan' => 'required|exists:ms_ruangan,id_ruangan',
            'tipe_hari'             => 'required|in:weekday,weekend,holiday',
            'harga'                 => 'required|numeric|min:0',
            'durasi_menit'          => 'required|integer|min:30',
            'fasilitas'             => 'nullable|array',
            'fasilitas.*'           => 'exists:ms_fasilitas,id_fasilitas',
        ]);

        // Simpan paket
        $paket = MsPaket::create([
            'nama_paket'      => $request->nama_paket,
            'deskripsi_paket' => $request->deskripsi_paket,
            'maksimal_orang'  => $request->maksimal_orang,
            'is_active'       => $request->is_active,
        ]);

        // Simpan pricing
        MsPricing::create([
            'ms_ruangan_id_ruangan' => $request->ms_ruangan_id_ruangan,
            'ms_paket_id_paket'     => $paket->id_paket,
            'tipe_pricing'          => $request->tipe_hari,
            'hari_type'             => $request->tipe_hari,
            'durasi_menit'          => $request->durasi_menit,
            'harga'                 => $request->harga,
        ]);

        // Simpan fasilitas ruangan
    if ($request->fasilitas) {
        $paket->fasilitas()->syncWithoutDetaching($request->fasilitas);
    }

        return redirect()->route('admin.paket.index')->with('success', 'Paket berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $paket     = MsPaket::with('pricings.ruangan')->findOrFail($id);
        $kategoris = MsKategori::all();
        $fasilitas = MsFasilitas::all();
        return view('admin.paket.edit', compact('paket', 'kategoris', 'fasilitas'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_paket'      => 'required|string|max:50',
            'deskripsi_paket' => 'nullable|string',
            'maksimal_orang'  => 'nullable|integer|min:1',
            'is_active'       => 'required|in:0,1',
        ]);

        MsPaket::findOrFail($id)->update($request->only([
            'nama_paket', 'deskripsi_paket', 'maksimal_orang', 'is_active'
        ]));

        return redirect()->route('admin.paket.index')->with('success', 'Paket berhasil diupdate!');
    }

    public function destroy($id)
    {
        MsPaket::findOrFail($id)->delete();
        return redirect()->route('admin.paket.index')->with('success', 'Paket berhasil dihapus!');
    }

    // AJAX: ambil ruangan berdasarkan kategori
    public function getRuanganByKategori($id_kategori)
    {
        $ruangans = MsRuangan::where('ms_kategori_id_kategori', $id_kategori)
            ->where('is_active', 1)
            ->get(['id_ruangan', 'nama_ruangan']);
        return response()->json($ruangans);
    }
}