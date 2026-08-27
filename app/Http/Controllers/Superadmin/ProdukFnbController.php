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
            ->pluck('kategori_produk', 'kategori_produk')
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

        // 🔹 AMBIL SEMUA PRODUK UNTUK SEARCH BOX (TAMBAH PEMBANDING)
        $allProduks = MsProduk::where('is_active', 1)->get(['id_produk', 'nama_produk']);

        // =======================================================
        // 2. DATA TABEL PRODUK (TETAP MENGGUNAKAN KODEMU)
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
        // 3. LOGIKA DYNAMIC DATE
        // =======================================================
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

        $transaksiPos = TrPos::with('details')
            ->whereBetween('created_at', [$queryStart, $queryEnd])
            ->whereNotIn('status_pesanan', ['Dibatalkan']) 
            ->get();

        $chartLabels = [];
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
        }

        $suggestedMax = 0;
        $colors = ['#6f42c1', '#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#ec4899', '#8b5cf6'];

        // =======================================================
        // 4. AJAX: JIKA MENAMBAH 1 PEMBANDING BARU
        // =======================================================
        if ($request->ajax() && $request->has('add_produk_id')) {
            $produkId = $request->input('add_produk_id');
            $produk = MsProduk::find($produkId);
            $colorIndex = $request->input('color_index', 0);
            
            $data = [];
            foreach ($iterables as $step) {
                $transaksiFiltered = $transaksiPos->filter($step['filter']);
                $total = 0;
                foreach ($transaksiFiltered as $pos) {
                    foreach ($pos->details as $detail) {
                        if ($detail->id_produk == $produkId) {
                            $total += $detail->subtotal;
                        }
                    }
                }
                $data[] = $total;
                if ($total > $suggestedMax) $suggestedMax = $total;
            }

            return response()->json([
                'success' => true,
                'dataset' => [
                    'id_produk' => $produkId,
                    'label'     => $produk->nama_produk ?? 'Unknown',
                    'data'      => $data,
                    'color'     => $colors[$colorIndex % count($colors)]
                ],
                'suggestedMax' => $suggestedMax + ($suggestedMax * 0.1)
            ]);
        }

        // =======================================================
        // 5. RENDER CHART DATASET SAAT LOAD AWAL ATAU FILTER
        // =======================================================
        $chartDatasets = [];
        $produkIds = [];
        
        if ($request->has('produk_ids') && !empty($request->input('produk_ids'))) {
            $produkIds = explode(',', $request->input('produk_ids'));
        } else {
            // Default saat load awal: Tampilkan 2 produk F&B terlaris
            $topProduks = \App\Models\TrPosDetail::select('id_produk', \Illuminate\Support\Facades\DB::raw('SUM(subtotal) as total'))
                ->groupBy('id_produk')
                ->orderByDesc('total')
                ->limit(2)
                ->pluck('id_produk')
                ->toArray();
            $produkIds = $topProduks;
        }

        foreach ($produkIds as $index => $pid) {
            $produk = MsProduk::find($pid);
            if (!$produk) continue;

            $data = [];
            foreach ($iterables as $step) {
                $transaksiFiltered = $transaksiPos->filter($step['filter']);
                $total = 0;
                foreach ($transaksiFiltered as $pos) {
                    foreach ($pos->details as $detail) {
                        if ($detail->id_produk == $pid) {
                            $total += $detail->subtotal;
                        }
                    }
                }
                $data[] = $total;
                if ($total > $suggestedMax) $suggestedMax = $total;
            }

            $chartDatasets[] = [
                'id_produk' => $pid,
                'label'     => $produk->nama_produk,
                'data'      => $data,
                'color'     => $colors[$index % count($colors)]
            ];
        }

        $suggestedMax = $suggestedMax + ($suggestedMax * 0.1);

        // =======================================================
        // 6. RESPONSE AJAX FILTER UTAMA
        // =======================================================
        if ($request->ajax()) {
            $tableHtml = '';
            
            // Format ulang tabel agar sama persis dengan desain aslimu
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

            return response()->json([
                'success'      => true,
                'labels'       => $chartLabels,
                'datasets'     => $chartDatasets,
                'html'         => $tableHtml,
                'total'        => count($tableData),
                'suggestedMax' => $suggestedMax,
                'allProduks'   => $allProduks 
            ]);
        }

        return view('superadmin.produk-fnb.index', compact(
            'tableData', 
            'statusBadgeVariant', 
            'chartLabels', 
            'chartDatasets',
            'kategoriOptions',
            'subKategoriOptions',
            'allProduks',
            'suggestedMax'
        ));
    }
}