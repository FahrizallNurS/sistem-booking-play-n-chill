<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MsProduk;
use App\Models\TrPos;
use App\Models\MsSubKategoriProduk; 
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class ProdukFnbController extends Controller
{
    public function index(Request $request)
    {
        // =======================================================
        // 1. SETUP OPSI FILTER (DINAMIS & STATIS)
        // =======================================================
        $kategoriDb = MsSubKategoriProduk::whereNotNull('kategori_produk')
            ->distinct()
            ->pluck('kategori_produk', 'kategori_produk') // Memperbaiki nama kolom
            ->toArray();
        $kategoriOptions = ['semua' => 'Semua'] + $kategoriDb;

        $subKategoriOptions = [
            'semua'          => 'Semua',
            'Makanan Berat'  => 'Makanan Berat',
            'Makanan Ringan' => 'Makanan Ringan',
            'Minuman'        => 'Minuman',
        ];

        $kategoriFilter = $request->input('kategori', 'semua');
        $subKategoriFilter = $request->input('sub_kategori', 'semua');
        $periode = $request->input('periode', 'harian'); 

        // =======================================================
        // 2. DATA TABEL PRODUK
        // =======================================================
        $queryProduk = MsProduk::with('subKategori')->orderBy('nama_produk');

        if ($kategoriFilter !== 'semua') {
            $queryProduk->whereHas('subKategori', function($q) use ($kategoriFilter) {
                $q->where('kategori_produk', $kategoriFilter);
            });
        }
        if ($subKategoriFilter !== 'semua') {
            $queryProduk->whereHas('subKategori', function($q) use ($subKategoriFilter) {
                $q->where('sub_kategori_produk', $subKategoriFilter);
            });
        }

        $tableData = $queryProduk->get()->map(function ($produk) {
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

        $statusBadgeVariant = ['Aktif' => 'success', 'Nonaktif' => 'danger'];

        // =======================================================
        // 3. DATA GRAFIK (DENGAN LOGIKA DYNAMIC DATE)
        // =======================================================
        $kategoriFnb = [
            'makanan_berat'  => ['label' => 'Makanan Berat',  'color' => '#3498db'],
            'makanan_ringan' => ['label' => 'Makanan Ringan', 'color' => '#2ecc71'],
            'minuman'        => ['label' => 'Minuman',        'color' => '#f39c12'],
        ];

        if ($periode === 'harian') {
            $val = $request->input('tanggal', Carbon::now()->format('Y-m-d'));
            $baseDateStart = Carbon::parse($val);
            
            $queryStart = $baseDateStart->copy()->startOfDay();
            $queryEnd   = $baseDateStart->copy()->endOfDay();
            
        } elseif ($periode === 'mingguan') {
            $val = $request->input('minggu', Carbon::now()->format('Y-\WW')); 
            $baseDateStart = Carbon::now();
            
            if (preg_match('/^(\d{4})-W(\d{2})$/', $val, $matches)) {
                $baseDateStart->setISODate($matches[1], $matches[2]);
            }
            
            $queryStart = $baseDateStart->copy()->startOfWeek();
            $queryEnd   = $baseDateStart->copy()->endOfWeek();
            
        } else {
            $val = $request->input('bulan', Carbon::now()->format('Y-m'));
            $baseDateStart = Carbon::parse($val . '-01'); 
            
            $queryStart = $baseDateStart->copy()->startOfMonth();
            $queryEnd   = $baseDateStart->copy()->endOfMonth();
        }

        $transaksiPos = TrPos::with('details.produk.subKategori')
            ->whereBetween('created_at', [$queryStart, $queryEnd])
            ->whereNotIn('status_pesanan', ['Dibatalkan']) 
            ->get();

        $chartLabels = [];
        $dataMakananBerat = [];
        $dataMakananRingan = [];
        $dataMinuman = [];

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
                    'filter' => fn($pos) => Carbon::parse($pos->created_at)->isSameDay($date)
                ];
            }
        } else {
            $periodRange = CarbonPeriod::create($queryStart, '1 day', $queryEnd);
            foreach ($periodRange as $date) {
                $iterables[] = [
                    'label' => $date->format('d'),
                    'filter' => fn($pos) => Carbon::parse($pos->created_at)->isSameDay($date)
                ];
            }
        }

        foreach ($iterables as $step) {
            $chartLabels[] = $step['label'];
            $transaksiFiltered = $transaksiPos->filter($step['filter']);

            $totalMakananBerat = 0;
            $totalMakananRingan = 0;
            $totalMinuman = 0;

            foreach ($transaksiFiltered as $pos) {
                foreach ($pos->details as $detail) {
                    $kat    = $detail->produk->subKategori->kategori_produk ?? '';
                    $subKat = $detail->produk->subKategori->sub_kategori_produk ?? '';

                    if ($kategoriFilter !== 'semua' && $kat !== $kategoriFilter) continue;
                    if ($subKategoriFilter !== 'semua' && $subKat !== $subKategoriFilter) continue;
                    
                    $subKatLower = strtolower($subKat);
                    if (str_contains($subKatLower, 'makanan berat')) {
                        $totalMakananBerat += $detail->subtotal;
                    } elseif (str_contains($subKatLower, 'makanan ringan')) {
                        $totalMakananRingan += $detail->subtotal;
                    } elseif (str_contains($subKatLower, 'minuman')) {
                        $totalMinuman += $detail->subtotal;
                    } else {
                        $totalMakananRingan += $detail->subtotal;
                    }
                }
            }

            $dataMakananBerat[] = $totalMakananBerat;
            $dataMakananRingan[] = $totalMakananRingan;
            $dataMinuman[] = $totalMinuman;
        }

        $chartDatasets = [
            ['label' => $kategoriFnb['makanan_berat']['label'],  'data' => $dataMakananBerat,  'color' => $kategoriFnb['makanan_berat']['color']],
            ['label' => $kategoriFnb['makanan_ringan']['label'], 'data' => $dataMakananRingan, 'color' => $kategoriFnb['makanan_ringan']['color']],
            ['label' => $kategoriFnb['minuman']['label'],        'data' => $dataMinuman,       'color' => $kategoriFnb['minuman']['color']],
        ];

        if ($request->ajax()) {
            $tableHtml = '';
            
            // Render baris tabel menjadi HTML
            foreach ($tableData as $index => $row) {
                $fotoUrl = !empty($row['foto']) ? $row['foto'] : asset('images/logo_dumb.png');
                $fotoImg = !empty($row['foto']) 
                    ? '<img src="'.$fotoUrl.'" class="rounded" style="width: 44px; height: 44px; object-fit: cover; border: 1px solid #e5e7eb;">'
                    : '<div class="d-flex align-items-center justify-content-center rounded bg-light text-muted" style="width: 44px; height: 44px; border: 1px solid #e5e7eb;"><i class="fas fa-image"></i></div>';
                
                $variant = $statusBadgeVariant[$row['status']] ?? 'secondary';
                $badge = '<span class="badge badge-'.$variant.' px-2 py-1" style="font-size: 11px; font-weight: 600; border-radius: 4px;">'.$row['status'].'</span>';

                $tableHtml .= '<tr>';
                $tableHtml .= '<td class="px-4 text-muted py-2" style="font-size: 13px;">'.($index + 1).'</td>';
                $tableHtml .= '<td class="py-2">'.$fotoImg.'</td>';
                $tableHtml .= '<td class="text-dark font-weight-bold py-2" style="font-size: 13px;">'.$row['nama'].'</td>';
                $tableHtml .= '<td class="text-muted py-2" style="font-size: 13px;">'.$row['kategori'].'</td>';
                $tableHtml .= '<td class="text-muted py-2" style="font-size: 13px;">'.$row['sub_kategori'].'</td>';
                $tableHtml .= '<td class="text-muted text-right py-2" style="font-size: 13px;">Rp. '.number_format($row['harga_beli'], 2, ',', '.').'</td>';
                $tableHtml .= '<td class="text-muted text-right py-2" style="font-size: 13px;">Rp. '.number_format($row['harga_jual'], 2, ',', '.').'</td>';
                $tableHtml .= '<td class="text-muted py-2" style="font-size: 13px;">'.$row['sku'].'</td>';
                $tableHtml .= '<td class="text-muted text-center py-2" style="font-size: 13px;">'.$row['stock'].'</td>';
                $tableHtml .= '<td class="text-center py-2">'.$badge.'</td>';
                $tableHtml .= '</tr>';
            }

            // Kembalikan JSON ke frontend
            return response()->json([
                'success'  => true,
                'labels'   => $chartLabels,
                'datasets' => $chartDatasets,
                'html'     => $tableHtml,
                'total'    => count($tableData)
            ]);
        }

        return view('superadmin.produk-fnb.index', compact(
            'tableData', 
            'statusBadgeVariant', 
            'chartLabels', 
            'chartDatasets',
            'kategoriOptions',
            'subKategoriOptions'
        ));
    }
}