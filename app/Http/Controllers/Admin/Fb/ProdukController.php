<?php

namespace App\Http\Controllers\Admin\Fb;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MsProduk;
use App\Models\MsSubKategoriProduk;
use Illuminate\Support\Facades\File;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $query = MsProduk::with('subKategori')->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_produk', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        if ($request->filled('kategori') && $request->kategori !== 'Semua Produk') {
            $kategori = $request->kategori;
            $query->whereHas('subKategori', function($q) use ($kategori) {
                $q->where('kategori_produk', $kategori);
            });
        }

        $produks = $query->paginate(10);
        $kategoriList = MsSubKategoriProduk::select('kategori_produk')->distinct()->pluck('kategori_produk');

        return view('admin.fb.produk.index', compact('produks', 'kategoriList'));
    }

    public function create()
    {
        $kategoriList = MsSubKategoriProduk::where('is_active', 1)->select('kategori_produk')->distinct()->pluck('kategori_produk');
        $subKategoriList = MsSubKategoriProduk::where('is_active', 1)->select('sub_kategori_produk')->distinct()->pluck('sub_kategori_produk');
        
        return view('admin.fb.produk.tambah-produk', compact('kategoriList', 'subKategoriList'));
    }

    public function store(Request $request)
    {   

        $request->merge([
            'harga_beli' => str_replace('.', '', $request->harga_beli),
            'harga_jual' => str_replace('.', '', $request->harga_jual),
        ]);

        $request->validate([
            'nama_produk'         => 'required|string|max:30',
            'kategori_produk'     => 'required|string',
            'sub_kategori_produk' => 'required|string',
            'harga_beli'          => 'required|numeric|min:0',
            'harga_jual'          => 'required|numeric|min:0',
            'sku'                 => 'required|string|max:10|unique:ms_produk,sku',
            'stock'               => 'required|integer|min:0',
            'foto'                => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'sku.unique' => 'SKU ini sudah digunakan. Silakan gunakan kode lain.',
            'foto.max'   => 'Ukuran foto maksimal adalah 2MB.',
        ]);

        $subKategori = MsSubKategoriProduk::firstOrCreate(
            [
                'kategori_produk'     => $request->kategori_produk,
                'sub_kategori_produk' => $request->sub_kategori_produk,
            ],
            ['is_active' => 1]
        );

        $fotoName = null;
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $fotoName = time() . '_' . $foto->getClientOriginalName();
            $foto->move(public_path('uploads/fb'), $fotoName);
        }

        MsProduk::create([
            'nama_produk' => $request->nama_produk,
            'ms_sub_kategori_produk_id_sub_kategori_produk' => $subKategori->id_sub_kategori_produk,
            'harga_beli'  => $request->harga_beli,
            'harga_jual'  => $request->harga_jual,
            'sku'         => $request->sku,
            'stock'       => $request->stock,
            'foto'        => $fotoName,
            'is_active'   => 1,
        ]);

        return redirect()->route('admin.fb.produk.index')->with('success', 'Produk F&B berhasil ditambahkan!');
    }

    public function toggleStatus($id)
    {
        $produk = MsProduk::findOrFail($id);
        $produk->is_active = $produk->is_active ? 0 : 1;
        $produk->save();

        $status = $produk->is_active ? 'diaktifkan' : 'dinonaktifkan';
        
        return redirect()->back()->with('success', "Status produk berhasil $status.");
    }

    public function edit($id)
    {
        $produk = MsProduk::with('subKategori')->findOrFail($id);
        $kategoriList = MsSubKategoriProduk::where('is_active', 1)->select('kategori_produk')->distinct()->pluck('kategori_produk');
        
        return view('admin.fb.produk.edit-produk', compact('produk', 'kategoriList'));
    }

    public function update(Request $request, $id)
    {
        $request->merge([
            'harga_beli' => str_replace('.', '', $request->harga_beli),
            'harga_jual' => str_replace('.', '', $request->harga_jual),
        ]);

        $request->validate([
            'nama_produk'         => 'required|string|max:30',
            'kategori_produk'     => 'required|string',
            'sub_kategori_produk' => 'required|string',
            'harga_beli'          => 'required|numeric|min:0',
            'harga_jual'          => 'required|numeric|min:0',
            'sku'                 => 'required|string|max:10|unique:ms_produk,sku,'.$id.',id_produk',
            'stock'               => 'required|integer|min:0',
            'foto'                => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'sku.unique' => 'SKU ini sudah digunakan. Silakan gunakan kode lain.',
            'foto.max'   => 'Ukuran foto maksimal adalah 2MB.',
        ]);

        $produk = MsProduk::findOrFail($id);
        $subKategori = MsSubKategoriProduk::firstOrCreate(

            [
                'kategori_produk'     => $request->kategori_produk,
                'sub_kategori_produk' => $request->sub_kategori_produk,
            ],
            ['is_active' => 1]
        );

        if ($request->hasFile('foto')) {
            if ($produk->foto && file_exists(public_path('uploads/fb/' . $produk->foto))) {
                unlink(public_path('uploads/fb/' . $produk->foto));
            }

            $foto = $request->file('foto');
            $fotoName = time() . '_' . $foto->getClientOriginalName();
            $foto->move(public_path('uploads/fb'), $fotoName);
            $produk->foto = $fotoName; 
        }

        $produk->nama_produk = $request->nama_produk;
        $produk->ms_sub_kategori_produk_id_sub_kategori_produk = $subKategori->id_sub_kategori_produk;
        $produk->harga_beli  = $request->harga_beli;
        $produk->harga_jual  = $request->harga_jual;
        $produk->sku         = $request->sku;
        $produk->stock       = $request->stock;
        
        $produk->save();

        return redirect()->route('admin.fb.produk.index')->with('success', 'Data Produk F&B berhasil diperbarui!');
    }
    
}