<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Galeri;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    private const UPLOAD_DIR = 'images/banner';
    private const PER_PAGE = 10;

    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status'); // 'aktif' | 'nonaktif' | null

        $banners = Galeri::where('kategori', 'banner')
            ->when($search, function ($query) use ($search) {
                $query->where('judul_foto', 'like', '%' . $search . '%');
            })
            ->when($status === 'aktif', function ($query) {
                $query->where('is_active', 1);
            })
            ->when($status === 'nonaktif', function ($query) {
                $query->where('is_active', 0);
            })
            ->orderBy('id_galeri', 'DESC')
            ->paginate(self::PER_PAGE)
            ->withQueryString();

        // Request AJAX (filter/pagination) -> kirim partial HTML saja
        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.banners.partials.table-banner', compact('banners'))->render(),
            ]);
        }

        return view('admin.banners.index', compact('banners'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul_foto' => 'required|string|max:50',
            'file_foto'  => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $namaFile = $this->simpanFile($request->file('file_foto'));

        Galeri::create([
            'judul_foto' => $validated['judul_foto'],
            'kategori'   => 'banner',
            'file_foto'  => self::UPLOAD_DIR . '/' . $namaFile,
            // FIX: form kirim is_active = '' saat tambah baru, filled() menghindari itu kesimpan sbg string kosong
            'is_active'  => $request->filled('is_active') ? $request->input('is_active') : 1,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Banner berhasil ditambahkan.',
        ]);
    }

    public function update(Request $request, $id)
    {
        $banner = Galeri::where('kategori', 'banner')->findOrFail($id);

        $validated = $request->validate([
            'judul_foto' => 'required|string|max:50',
            'file_foto'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active'  => 'required|in:0,1',
        ]);

        $dataUpdate = [
            'judul_foto' => $validated['judul_foto'],
            'is_active'  => $validated['is_active'],
        ];

        if ($request->hasFile('file_foto')) {
            $this->hapusFile($banner->file_foto);
            $namaFile = $this->simpanFile($request->file('file_foto'));
            $dataUpdate['file_foto'] = self::UPLOAD_DIR . '/' . $namaFile;
        }

        $banner->update($dataUpdate);

        return response()->json([
            'status'  => 'success',
            'message' => 'Banner berhasil diperbarui.',
        ]);
    }

    public function destroy($id)
    {
        $banner = Galeri::where('kategori', 'banner')->findOrFail($id);

        $this->hapusFile($banner->file_foto);
        $banner->delete();

        return redirect()
            ->route('admin.banners.index')
            ->with('success', 'Banner berhasil dihapus.');
    }

    private function simpanFile($file): string
    {
        $namaFile = 'banner_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path(self::UPLOAD_DIR), $namaFile);

        return $namaFile;
    }

    private function hapusFile(?string $relativePath): void
    {
        if (!$relativePath) {
            return;
        }

        $path = public_path($relativePath);

        if (file_exists($path)) {
            unlink($path);
        }
    }
}