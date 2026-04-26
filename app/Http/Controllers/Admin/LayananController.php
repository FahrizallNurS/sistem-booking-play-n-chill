<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MsRuangan;
use App\Models\MsPaket;
use App\Models\PenetapanHarga;
use Illuminate\Http\Request;

class LayananController extends Controller
{
    public function index()
    {
        $ruangans = MsRuangan::latest()->get();
        return view('admin.layanan.index', compact('ruangans'));
    }

    public function create()
    {
        return view('admin.layanan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_ruangan' => 'required|string|max:20|unique:ms_ruangan,nama_ruangan',
            'kategori'     => 'required|in:REGULAR,VIP,VVIP',
            'deskripsi'    => 'nullable|string|max:60',
            'perangkat'    => 'nullable|string|max:10',
            'is_active'    => 'required|in:0,1',
        ]);

        MsRuangan::create([
            'nama_ruangan' => $request->nama_ruangan,
            'kategori'     => $request->kategori,
            'deskripsi'    => $request->deskripsi,
            'perangkat'    => $request->perangkat,
            'is_active'    => $request->is_active,
        ]);

        return redirect()->route('admin.layanan.index')
            ->with('success', 'Ruangan berhasil ditambahkan!');
    }

    public function show($id)
    {
        $ruangan = MsRuangan::with(['penetapanHarga.paket'])->findOrFail($id);
        $pakets  = MsPaket::where('is_active', 1)->get();
        return view('admin.layanan.show', compact('ruangan', 'pakets'));
    }

    public function edit($id)
    {
        $ruangan = MsRuangan::findOrFail($id);
        return view('admin.layanan.edit', compact('ruangan'));
    }

   public function update(Request $request, $id)
{
    $request->validate([
        'nama_ruangan' => 'required|string|max:20|unique:ms_ruangan,nama_ruangan,' . $id . ',id_ruangan',
        'kategori'     => 'required|in:REGULAR,VIP,VVIP',
        'deskripsi'    => 'nullable|string|max:60',
        'perangkat'    => 'nullable|string|max:10',
        'is_active'    => 'required|in:0,1',
        'galeri'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    $ruangan = MsRuangan::findOrFail($id);

    $data = [
        'nama_ruangan' => $request->nama_ruangan,
        'kategori'     => $request->kategori,
        'deskripsi'    => $request->deskripsi,
        'perangkat'    => $request->perangkat,
        'is_active'    => $request->is_active,
    ];

    if ($request->hasFile('galeri')) {
        // Hapus foto lama
        if ($ruangan->galeri) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($ruangan->galeri);
        }
        $data['galeri'] = $request->file('galeri')->store('ruangan', 'public');
    }

    $ruangan->update($data);

    return redirect()->route('admin.layanan.index')
        ->with('success', 'Ruangan berhasil diupdate!');
}

    public function destroy($id)
    {
        $ruangan = MsRuangan::findOrFail($id);

        if ($ruangan->penetapanHarga()->exists()) {
            return back()->with('error', 'Ruangan tidak bisa dihapus karena masih memiliki penetapan harga.');
        }

        $ruangan->delete();
        return redirect()->route('admin.layanan.index')
            ->with('success', 'Ruangan berhasil dihapus!');
    }

    // Penetapan Harga
    public function storePenetapanHarga(Request $request, $id)
    {
        $request->validate([
            'id_paket'   => 'required|exists:ms_paket,id_paket',
            'tipe_hari'  => 'required|in:harian,akhir_pekan,liburan',
            'durasi_jam' => 'required|integer|min:1',
            'harga'      => 'required|integer|min:0',
        ]);

        // Cek duplikat
        $exists = PenetapanHarga::where('id_ruangan', $id)
            ->where('id_paket', $request->id_paket)
            ->where('tipe_hari', $request->tipe_hari)
            ->where('durasi_jam', $request->durasi_jam)
            ->exists();

        if ($exists) {
            return back()->withErrors([
                'durasi_jam' => 'Penetapan harga dengan kombinasi ini sudah ada.',
            ]);
        }

        PenetapanHarga::create([
            'id_ruangan' => $id,
            'id_paket'   => $request->id_paket,
            'tipe_hari'  => $request->tipe_hari,
            'durasi_jam' => $request->durasi_jam,
            'harga'      => $request->harga,
        ]);

        return redirect()->route('admin.layanan.show', $id)
            ->with('success', 'Penetapan harga berhasil ditambahkan!');
    }

    public function destroyPenetapanHarga($id)
    {
        PenetapanHarga::findOrFail($id)->delete();
        return back()->with('success', 'Penetapan harga berhasil dihapus!');
    }

    // AJAX: ambil ruangan by kategori
    public function getRuanganByKategori($kategori)
    {
        $ruangans = MsRuangan::where('kategori', $kategori)
            ->where('is_active', 1)
            ->get(['id_ruangan', 'nama_ruangan']);
        return response()->json($ruangans);
    }
}