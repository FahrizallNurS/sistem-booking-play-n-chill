<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProdukFnbController extends Controller
{
    /**
     * Menampilkan halaman Analisis Produk F&B (superadmin).
     *
     * NOTE SEMENTARA:
     * View superadmin/produk-fnb/index.blade.php saat ini masih generate
     * dummy data sendiri lewat blok @php di dalamnya (chart 30 hari & 5 baris
     * tabel produk), supaya frontend bisa dicek dulu sebelum data asli siap.
     *
     * Begitu query ke database sudah siap, langkah migrasinya:
     * 1. Hapus SELURUH blok @php dummy data di index.blade.php.
     * 2. Bentuk $chartLabels, $chartDatasets, $tableData di sini dengan
     *    struktur array PERSIS SAMA seperti yang dummy (lihat contoh
     *    kerangka di bawah), supaya tidak perlu ubah apapun di sisi view.
     * 3. Kirim ke view lewat compact(...).
     */
    public function index(Request $request)
    {
        // Filter yang dikirim dari <x-filter-card> (belum dipakai, masih placeholder
        // sampai query data asli dibuat):
        // $periode       = $request->input('periode', 'harian');
        // $rentangTanggal = $request->input('rentang_tanggal');
        // $kategori      = $request->input('kategori');
        // $subKategori   = $request->input('sub_kategori');

        // --- KERANGKA UNTUK NANTI (masih dinonaktifkan / dicomment) ---
        //
        // $kategoriFnb = collect(config('category-colors.produk_fnb_kategori'))
        //     ->map(fn ($item, $key) => $item)
        //     ->toArray();
        //
        // $chartLabels = ...; // tanggal sesuai $rentangTanggal, dari query agregat penjualan
        // $chartDatasets = [
        //     ['label' => $kategoriFnb['makanan_ringan']['label'], 'data' => [...], 'color' => $kategoriFnb['makanan_ringan']['color']],
        //     ... dst
        // ];
        //
        // $tableData = ProdukFnb::query()
        //     ->when($kategori, fn ($q) => $q->where('kategori', $kategori))
        //     ->when($subKategori, fn ($q) => $q->where('sub_kategori', $subKategori))
        //     ->get()
        //     ->map(fn ($produk) => [
        //         'foto'         => $produk->foto ? asset('storage/' . $produk->foto) : null,
        //         'nama'         => $produk->nama,
        //         'kategori'     => $produk->kategori,
        //         'sub_kategori' => $produk->sub_kategori,
        //         'harga_beli'   => $produk->harga_beli,
        //         'harga_jual'   => $produk->harga_jual,
        //         'sku'          => $produk->sku,
        //         'stock'        => $produk->stock,
        //         'status'       => $produk->status ? 'Aktif' : 'Nonaktif',
        //     ])
        //     ->toArray();
        //
        // return view('superadmin.produk-fnb.index', compact('chartLabels', 'chartDatasets', 'tableData'));

        // Untuk sekarang, cukup render view -- dummy data sudah di-generate di dalam view itu sendiri.
        return view('superadmin.produk-fnb.index');
    }
}
