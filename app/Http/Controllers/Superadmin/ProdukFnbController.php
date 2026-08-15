<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MsProduk;
use App\Models\TrPos;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class ProdukFnbController extends Controller
{
    public function index(Request $request)
    {
        $produks = MsProduk::with('subKategori')->orderBy('nama_produk')->get();

        $tableData = $produks->map(function ($produk) {
            return [
                'foto'         => $produk->foto ? asset('uploads/fb/' . $produk->foto) : null,
                'nama'         => $produk->nama_produk,
                'kategori'     => $produk->subKategori->kategori_produk ?? 'Lainnya',
                'sub_kategori' => $produk->subKategori->sub_kategori_produk ?? '-',
                'harga_beli'   => $produk->harga_beli,
                'harga_jual'   => $produk->harga_jual,
                'sku'          => $produk->sku,
                'stock'        => $produk->stock,
                'status'       => $produk->is_active ? 'Aktif' : 'Nonaktif',
            ];
        })->toArray();

        $statusBadgeVariant = [
            'Aktif'    => 'success',
            'Nonaktif' => 'danger',
        ];

        $kategoriFnb = [
            'makanan_ringan' => ['label' => 'Makanan ringan', 'color' => '#2ecc71'],
            'minuman'        => ['label' => 'Minuman',        'color' => '#f39c12'],
            'makanan'        => ['label' => 'Makanan',        'color' => '#3498db'],
            'snack'          => ['label' => 'Snack',          'color' => '#8e44ad'],
        ];

        $periode = $request->input('periode', 'harian'); 
        $rentang = $request->input('rentang_tanggal');

        if ($rentang && strpos($rentang, ' - ') !== false) {
            $dates = explode(' - ', $rentang);
            $baseDateStart = Carbon::parse($dates[0]);
            $baseDateEnd   = Carbon::parse($dates[1]);
        } else {
            $baseDateStart = Carbon::now();
            $baseDateEnd   = Carbon::now();
        }

        if ($periode === 'harian') {
            $queryStart = $baseDateStart->copy()->startOfDay();
            $queryEnd   = $baseDateStart->copy()->endOfDay();
        } elseif ($periode === 'mingguan') {
            $queryStart = $baseDateStart->copy()->startOfWeek();
            $queryEnd   = $baseDateStart->copy()->endOfWeek();
        } else {
            $queryStart = $baseDateStart->copy()->startOfMonth();
            $queryEnd   = $baseDateStart->copy()->endOfMonth();
        }
 
        $transaksiPos = TrPos::with('details.produk.subKategori')
            ->whereBetween('created_at', [$queryStart, $queryEnd])
            ->whereNotIn('status_pesanan', ['Dibatalkan']) 
            ->get();

        $chartLabels = [];
        $dataMakananRingan = [];
        $dataMinuman = [];
        $dataMakanan = [];
        $dataSnack = [];
        $iterables = [];

        if ($periode === 'harian') {
            for ($i = 6; $i <= 23; $i++) {
                $iterables[] = [
                    'label' => str_pad($i, 2, '0', STR_PAD_LEFT) . ':00',
                    'filter' => function($pos) use ($queryStart, $i) {
                        $d = Carbon::parse($pos->created_at);
                        return $d->isSameDay($queryStart) && $d->hour === $i;
                    }
                ];
            }
        } elseif ($periode === 'mingguan') {
            $periodRange = CarbonPeriod::create($queryStart, '1 day', $queryEnd);
            foreach ($periodRange as $date) {
                $iterables[] = [
                    'label' => $date->locale('id')->translatedFormat('l'),
                    'filter' => function($pos) use ($date) {
                        return Carbon::parse($pos->created_at)->isSameDay($date);
                    }
                ];
            }
        } else {
            $periodRange = CarbonPeriod::create($queryStart, '1 day', $queryEnd);
            foreach ($periodRange as $date) {
                $iterables[] = [
                    'label' => $date->format('d'),
                    'filter' => function($pos) use ($date) {
                        return Carbon::parse($pos->created_at)->isSameDay($date);
                    }
                ];
            }
        }

        foreach ($iterables as $step) {
            $chartLabels[] = $step['label']; 
            
            $transaksiFiltered = $transaksiPos->filter($step['filter']);

            $totalMakananRingan = 0;
            $totalMinuman = 0;
            $totalMakanan = 0;
            $totalSnack = 0;

            foreach ($transaksiFiltered as $pos) {
                foreach ($pos->details as $detail) {
                    $subKategori = strtolower($detail->produk->subKategori->sub_kategori_produk ?? '');
                    
                    if (str_contains($subKategori, 'makanan ringan')) {
                        $totalMakananRingan += $detail->subtotal;
                    } elseif (str_contains($subKategori, 'minuman')) {
                        $totalMinuman += $detail->subtotal;
                    } elseif (str_contains($subKategori, 'makanan') && !str_contains($subKategori, 'ringan')) {
                        $totalMakanan += $detail->subtotal;
                    } elseif (str_contains($subKategori, 'snack') || str_contains($subKategori, 'seblak')) {
                        $totalSnack += $detail->subtotal;
                    } else {
                        $totalMakananRingan += $detail->subtotal;
                    }
                }
            }

            $dataMakananRingan[] = $totalMakananRingan;
            $dataMinuman[] = $totalMinuman;
            $dataMakanan[] = $totalMakanan;
            $dataSnack[] = $totalSnack;
        }

        $chartDatasets = [
            ['label' => $kategoriFnb['makanan_ringan']['label'], 'data' => $dataMakananRingan, 'color' => $kategoriFnb['makanan_ringan']['color']],
            ['label' => $kategoriFnb['minuman']['label'],        'data' => $dataMinuman,       'color' => $kategoriFnb['minuman']['color']],
            ['label' => $kategoriFnb['makanan']['label'],        'data' => $dataMakanan,       'color' => $kategoriFnb['makanan']['color']],
            ['label' => $kategoriFnb['snack']['label'],          'data' => $dataSnack,         'color' => $kategoriFnb['snack']['color']],
        ];

        return view('superadmin.produk-fnb.index', compact(
            'tableData', 
            'statusBadgeVariant', 
            'chartLabels', 
            'chartDatasets'
        ));
    }
}