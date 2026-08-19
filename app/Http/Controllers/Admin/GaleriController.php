<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Galeri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class GaleriController extends Controller
{
    private const UPLOAD_DIR = 'uploads/galeri';
    private const PER_PAGE = 10;

    private const KATEGORI_VALID = [
        'Reguler',
        'Private - Gaming',
        'Private - Nonton',
        'Private - Karaoke',
    ];

    public function index(Request $request)
    {
        $search   = $request->input('search');
        $kategori = $request->input('kategori');

        $galeris = Galeri::where('kategori', '!=', 'banner')
            ->when($search, function ($query) use ($search) {
                $query->where('judul_foto', 'like', '%' . $search . '%');
            })
            ->when($kategori, function ($query) use ($kategori) {
                $query->where('kategori', $kategori);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(self::PER_PAGE)
            ->withQueryString();

        return view('admin.galeri.index', compact('galeris'));
    }

    public function create()
    {
        return view('admin.galeri.tambah-foto');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul_foto'     => 'required|string|max:100',
            'kategori'       => 'required|in:' . implode(',', self::KATEGORI_VALID),
            'deskripsi_foto' => 'nullable|string|max:500',
            'file_foto'      => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $validated['file_foto'] = $this->simpanFile($request->file('file_foto'));
        $validated['is_active'] = 1;

        Galeri::create($validated);

        return redirect()->route('admin.galeri.index')
            ->with('success', 'Foto galeri berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $galeri = Galeri::where('kategori', '!=', 'banner')->findOrFail($id);
        return view('admin.galeri.edit-foto', compact('galeri'));
    }

    public function update(Request $request, $id)
    {
        $galeri = Galeri::where('kategori', '!=', 'banner')->findOrFail($id);

        $validated = $request->validate([
            'judul_foto'     => 'required|string|max:100',
            'kategori'       => 'required|in:' . implode(',', self::KATEGORI_VALID),
            'deskripsi_foto' => 'nullable|string|max:500',
            'file_foto'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('file_foto')) {
            $this->hapusFile($galeri->file_foto);
            $validated['file_foto'] = $this->simpanFile($request->file('file_foto'));
        }

        $galeri->update($validated);

        return redirect()->route('admin.galeri.index')
            ->with('success', 'Foto galeri berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $galeri = Galeri::where('kategori', '!=', 'banner')->findOrFail($id);

        $this->hapusFile($galeri->file_foto);
        $galeri->delete();

        return redirect()->route('admin.galeri.index')
            ->with('success', 'Foto galeri berhasil dihapus!');
    }

    public function toggleStatus($id)
    {
        $galeri = Galeri::where('kategori', '!=', 'banner')->findOrFail($id);
        $galeri->is_active = !$galeri->is_active;
        $galeri->save();

        return response()->json([
            'success'   => true,
            'is_active' => (int) $galeri->is_active,
        ]);
    }

    /**
     * Simpan file ke public/uploads/galeri, kembalikan NAMA FILE doang
     * (foldernya tetap hardcode di blade, sesuai pola yang udah ada).
     */
    private function simpanFile($file): string
    {
        $filename = 'galeri_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path(self::UPLOAD_DIR), $filename);

        return $filename;
    }

    private function hapusFile(?string $filename): void
    {
        if (!$filename) {
            return;
        }

        $path = public_path(self::UPLOAD_DIR . '/' . $filename);
        if (File::exists($path)) {
            File::delete($path);
        }
    }
}