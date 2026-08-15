<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Galeri;
use Illuminate\Http\Request;

class GaleriController extends Controller
{
    public function index(Request $request)
    {
        $query = Galeri::where('kategori', '!=', 'banner');

        if ($request->has('search') && $request->search != '') {
            $query->where('judul_foto', 'like', '%' . $request->search . '%');
        }

        if ($request->has('kategori') && $request->kategori != '') {
            $query->where('kategori', $request->kategori);
        }

        // Urutkan berdasarkan yang terbaru dan pakai pagination
        $galeris = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.galeri.index', compact('galeris'));
    }

    public function create()
    {
        return view('admin.galeri.tambah-foto');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul_foto'     => 'required|string|max:50',
            'deskripsi_foto' => 'nullable|string|max:255',
            'kategori'       => 'required|string',
            'file_foto'      => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active'      => 'nullable|boolean'
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        if ($request->hasFile('file_foto')) {
            $file = $request->file('file_foto');
            $filename = 'galeri_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/galeri'), $filename);
            $data['file_foto'] = $filename;
        }

        Galeri::create($data);

        return redirect()->route('admin.galeri.index')
            ->with('success', 'Foto baru berhasil ditambahkan ke galeri.');
    }

    public function edit($id)
    {
        $galeri = Galeri::findOrFail($id);
        return view('admin.galeri.edit-foto', compact('galeri'));
    }

    public function update(Request $request, $id)
    {
        $galeri = Galeri::findOrFail($id);

        $request->validate([
            'judul_foto'     => 'required|string|max:50',
            'deskripsi_foto' => 'nullable|string|max:255',
            'kategori'       => 'required|string',
            'file_foto'      => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active'      => 'nullable|boolean'
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        if ($request->hasFile('file_foto')) {
            $oldPath = public_path('uploads/galeri/' . $galeri->file_foto);
            if ($galeri->file_foto && file_exists($oldPath)) {
                unlink($oldPath);
            }

            $file = $request->file('file_foto');
            $filename = 'galeri_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/galeri'), $filename);
            $data['file_foto'] = $filename;
        }

        $galeri->update($data);

        return redirect()->route('admin.galeri.index')
            ->with('success', 'Data galeri berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $galeri = Galeri::findOrFail($id);
        $oldPath = public_path('uploads/galeri/' . $galeri->file_foto);
        if ($galeri->file_foto && file_exists($oldPath)) {
            unlink($oldPath);
        }

        $galeri->delete();

        return redirect()->route('admin.galeri.index')
            ->with('success', 'Foto berhasil dihapus dari galeri.');
    }

    public function toggleStatus($id)
    {
        $galeri = Galeri::findOrFail($id);
        $galeri->is_active = $galeri->is_active == 1 ? 0 : 1;
        $galeri->save();

        return response()->json(['success' => true, 'is_active' => $galeri->is_active]);
    }
}