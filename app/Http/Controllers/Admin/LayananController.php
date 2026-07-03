<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MsRuangan;
use App\Models\MsPaket;
use App\Models\PenetapanHarga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LayananController extends Controller
{
    public function index(Request $request)
    {
            $query = MsRuangan::query();

            // SORTING
            if ($request->sort == 'kategori_asc') {
                $query->orderBy('kategori', 'asc');
            } elseif ($request->sort == 'kategori_desc') {
                $query->orderBy('kategori', 'desc');
            } else {
                // default (kayak sekarang)
                $query->latest();
            }

            $ruangans = $query->with(['penetapanHarga.transaksis'])->get();

            return view('admin.layanan.index', compact('ruangans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_ruangan' => 'required|string|max:20|unique:ms_ruangan,nama_ruangan',
            'kategori'     => 'required|in:REGULAR,PRIVATE ROOM',
            'perangkat' => 'nullable|in:PS3,PS4,PS5,Nintendo Switch',
            'is_active'    => 'required|in:0,1',
            'galeri'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = [
            'nama_ruangan' => $request->nama_ruangan,
            'kategori'     => $request->kategori,
            'perangkat'    => $request->perangkat,
            'is_active'    => $request->is_active,
        ];

        if ($request->hasFile('galeri')) {
            $data['galeri'] = $request->file('galeri')->store('ruangan', 'public');
        }

        MsRuangan::create($data);

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
        return response()->json($ruangan);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_ruangan' => 'required|string|max:20|unique:ms_ruangan,nama_ruangan,' . $id . ',id_ruangan',
            'kategori'     => 'required|in:REGULAR,VIP,VVIP',
            'perangkat'    => 'nullable|in:PS3,PS4,PS5,Nintendo Switch',
            'is_active'    => 'required|in:0,1',
            'galeri'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $ruangan = MsRuangan::findOrFail($id);

        $data = [
            'nama_ruangan' => $request->nama_ruangan,
            'kategori'     => $request->kategori,
            'perangkat'    => $request->perangkat,
            'is_active'    => $request->is_active,
        ];

        if ($request->hasFile('galeri')) {
            if ($ruangan->galeri) {
                Storage::disk('public')->delete($ruangan->galeri);
            }
            $data['galeri'] = $request->file('galeri')->store('ruangan', 'public');
        }

        $ruangan->update($data);

        return redirect()->route('admin.layanan.index')
            ->with('success', 'Ruangan berhasil diupdate!');
    }

    public function toggleAktif($id)
    {
        $ruangan = MsRuangan::findOrFail($id);
        $ruangan->update(['is_active' => !$ruangan->is_active]);

        $status = $ruangan->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Ruangan berhasil {$status}!");
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

    public function destroy($id)
    {
        $ruangan = MsRuangan::findOrFail($id);

         $punya_transaksi = $ruangan->penetapanHarga()
        ->whereHas('transaksis')
        ->exists();

        if ($punya_transaksi) {
            return back()->with('error', 'Ruangan tidak dapat dihapus karena memiliki data transaksi.');
        }
        $ruangan->permainans()->detach();
        $ruangan->penetapanHarga()->delete();

         if ($ruangan->galeri) {
        Storage::disk('public')->delete($ruangan->galeri);
        }

        $ruangan->delete();
        return redirect()->route('admin.layanan.index')
            ->with('success', 'Ruangan berhasil dihapus!');
    }
}