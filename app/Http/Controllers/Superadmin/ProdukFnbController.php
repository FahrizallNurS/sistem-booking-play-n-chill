<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\MsProduk;
use App\Models\TrPos;
use App\Models\MsSubKategoriProduk;
use Carbon\Carbon;

class ProdukFnbController extends Controller
{
    // Palet warna khusus grafik pembanding (Maks 7)
    private $colorPalette = ['#6f42c1', '#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#ec4899', '#8b5cf6'];

    private function getDateRange(Request $request): array
    {
        $periode = $request->input('periode', 'harian');

        if ($periode === 'mingguan') {
            $minggu = $request->input('minggu', now()->format('Y-\WW'));
            if (str_contains($minggu, '-W')) {
                [$year, $week] = explode('-W', $minggu);
                $start = Carbon::now()->setISODate((int)$year, (int)$week)->startOfWeek();
                $end   = Carbon::now()->setISODate((int)$year, (int)$week)->endOfWeek();
            } else {
                $start = Carbon::now()->startOfWeek();
                $end   = Carbon::now()->endOfWeek();
            }
        } elseif ($periode === 'bulanan') {
            $bulan = $request->input('bulan', now()->format('Y-m'));
            try {
                $start = Carbon::parse($bulan . '-01')->startOfMonth();
            } catch (\Exception $e) {
                $start = Carbon::now()->startOfMonth();
            }
            $end = $start->copy()->endOfMonth();
        } else {
            // Default: Harian / Date Range Input
            $tanggalInput = $request->input('tanggal') ?? $request->input('rentang_tanggal');

            if ($tanggalInput && str_contains($tanggalInput, ' - ')) {
                $dates = explode(' - ', $tanggalInput);
                try {
                    $start = Carbon::parse(trim($dates[0]))->startOfDay();
                    $end   = Carbon::parse(trim($dates[1]))->endOfDay();
                } catch (\Exception $e) {
                    $start = Carbon::today()->startOfDay();
                    $end   = Carbon::today()->endOfDay();
                }
            } elseif ($tanggalInput) {
                try {
                    $start = Carbon::parse($tanggalInput)->startOfDay();
                    $end   = Carbon::parse($tanggalInput)->endOfDay();
                } catch (\Exception $e) {
                    $start = Carbon::today()->startOfDay();
                    $end   = Carbon::today()->endOfDay();
                }
            } else {
                $start = Carbon::today()->startOfDay();
                $end   = Carbon::today()->endOfDay();
            }
        }

        return [$start, $end, $periode];
    }

    private function buildAxes($start, $end, $periode): array
    {
        $labels = [];
        $slots = [];

        if ($periode === 'harian') {
            // Format Jam (06:00 - 23:00 sesuai logic awalmu)
            for ($i = 6; $i <= 23; $i++) {
                $hour = str_pad($i, 2, '0', STR_PAD_LEFT);
                $labels[] = $hour . ':00';
                $slots[$hour] = 0;
            }
        } elseif ($periode === 'mingguan') {
            $labels = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
            for ($i = 1; $i <= 7; $i++) $slots[$i] = 0;
        } elseif ($periode === 'bulanan') {
            $daysInMonth = $start->daysInMonth;
            for ($i = 1; $i <= $daysInMonth; $i++) {
                $labels[] = str_pad($i, 2, '0', STR_PAD_LEFT);
                $slots[$i] = 0;
            }
        } else {
            // Rentang waktu dinamis
            $diff = $start->diffInDays($end);
            for ($i = 0; $i <= $diff; $i++) {
                $dt = $start->copy()->addDays($i);
                $labels[] = $dt->translatedFormat('d M');
                $slots[$dt->format('Y-m-d')] = 0;
            }
        }

        return ['labels' => $labels, 'slots' => $slots];
    }

    private function fetchDatasetForProduk($produkId, $start, $end, $periode, $colorIndex)
    {
        $produk = MsProduk::find($produkId);
        if (!$produk) return null;

        $axes = $this->buildAxes($start, $end, $periode);
        $slots = $axes['slots'];

        // Agregasi di level Database (Mencegah out of memory)
        $transactions = DB::table('tr_pos_detail')
            ->join('tr_pos', 'tr_pos_detail.id_pos', '=', 'tr_pos.id_pos')
            ->where('tr_pos.status_pesanan', '!=', 'Dibatalkan')
            ->where('tr_pos_detail.id_produk', $produkId)
            ->whereBetween('tr_pos.created_at', [$start, $end])
            ->select('tr_pos_detail.subtotal', 'tr_pos.created_at')
            ->get();

        foreach ($transactions as $trx) {
            $dt = Carbon::parse($trx->created_at);
            
            if ($periode === 'harian') {
                $key = $dt->format('H'); 
                // Abaikan jika transaksi terjadi di luar jam operasional (06 - 23)
                if (!array_key_exists($key, $slots)) continue;
            } elseif ($periode === 'mingguan') {
                $key = $dt->dayOfWeekIso;
            } elseif ($periode === 'bulanan') {
                $key = (int) $dt->format('j');
            } else {
                $key = $dt->format('Y-m-d');
            }

            if (array_key_exists($key, $slots)) {
                $slots[$key] += (float) $trx->subtotal;
            }
        }

        return [
            'id_produk' => $produkId,
            'label' => $produk->nama_produk,
            'data' => array_values($slots),
            'color' => $this->colorPalette[$colorIndex % count($this->colorPalette)]
        ];
    }

    private function getAvailableProduks(Request $request): array
    {
        $query = MsProduk::with('subKategori')->where('is_active', 1);

        $kategoriFilter = $request->input('kategori', 'semua');
        $subKategoriFilter = $request->input('sub_kategori', 'semua');

        if ($kategoriFilter !== 'semua') {
            $query->whereHas('subKategori', function($q) use ($kategoriFilter) {
                $q->where('kategori_produk', $kategoriFilter);
            });
        }
        if ($subKategoriFilter !== 'semua') {
            $query->whereHas('subKategori', function($q) use ($subKategoriFilter) {
                $q->where('sub_kategori_produk', $subKategoriFilter);
            });
        }

        return $query->select('id_produk', 'nama_produk')->get()->toArray();
    }

    private function resolveProdukIds(Request $request, $start, $end, array $availableProduks): array
    {
        $availableIds = array_column($availableProduks, 'id_produk');

        if ($request->has('produk_ids')) {
            $raw = $request->input('produk_ids', '');
            $ids = is_array($raw) ? $raw : explode(',', $raw);
            
            $filtered = array_values(array_unique(array_filter(
                array_map('intval', $ids),
                fn($id) => in_array($id, $availableIds)
            )));

            if (!empty($filtered)) {
                return $filtered;
            }
        }

        // Ambil Top 4 Produk Terlaris berdasarkan periode yang dipilih
        return DB::table('tr_pos_detail')
            ->join('tr_pos', 'tr_pos_detail.id_pos', '=', 'tr_pos.id_pos')
            ->where('tr_pos.status_pesanan', '!=', 'Dibatalkan')
            ->whereBetween('tr_pos.created_at', [$start, $end])
            ->whereIn('tr_pos_detail.id_produk', $availableIds)
            ->select('tr_pos_detail.id_produk', DB::raw('SUM(tr_pos_detail.subtotal) as total_revenue'))
            ->groupBy('tr_pos_detail.id_produk')
            ->orderByDesc('total_revenue')
            ->limit(4) 
            ->pluck('id_produk')
            ->toArray();
    }

    public function index(Request $request)
    {
        // 1. Setup Data Filter
        $kategoriDb = MsSubKategoriProduk::whereNotNull('kategori_produk')
            ->distinct()
            ->pluck('kategori_produk', 'kategori_produk')
            ->toArray();
        $kategoriOptions = ['semua' => 'Semua Kategori'] + $kategoriDb;

        $subKategoriOptions = [
            'semua'          => 'Semua Sub Kategori',
            'Makanan Berat'  => 'Makanan Berat',
            'Makanan Ringan' => 'Makanan Ringan',
            'Minuman'        => 'Minuman',
        ];

        [$start, $end, $periode] = $this->getDateRange($request);
        
        $allProduks = $this->getAvailableProduks($request);
        $suggestedMax = $periode === 'harian' ? 100000 : 500000;

        // 2. Kueri Tabel Data (Bawah)
        $queryProduk = MsProduk::with('subKategori')->orderBy('nama_produk');
        $kategoriFilter = $request->input('kategori', 'semua');
        $subKategoriFilter = $request->input('sub_kategori', 'semua');

        if ($kategoriFilter !== 'semua') {
            $queryProduk->whereHas('subKategori', fn($q) => $q->where('kategori_produk', $kategoriFilter));
        }
        if ($subKategoriFilter !== 'semua') {
            $queryProduk->whereHas('subKategori', fn($q) => $q->where('sub_kategori_produk', $subKategoriFilter));
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

        // 3. Handler AJAX Tambah 1 Pembanding
        if ($request->ajax() && $request->has('add_produk_id')) {
            $dataset = $this->fetchDatasetForProduk(
                $request->add_produk_id, $start, $end, $periode, $request->color_index ?? 0
            );
            return response()->json(['success' => true, 'dataset' => $dataset, 'suggestedMax' => $suggestedMax]);
        }

        // 4. Proses Grafik Initial / Submit Filter Utama
        $produkIds = $this->resolveProdukIds($request, $start, $end, $allProduks);
        
        $axes = $this->buildAxes($start, $end, $periode);
        $chartLabels = $axes['labels'];
        $chartDatasets = [];

        $currentMax = 0;
        foreach ($produkIds as $index => $pid) {
            $dataset = $this->fetchDatasetForProduk($pid, $start, $end, $periode, $index);
            if ($dataset) {
                $chartDatasets[] = $dataset;
                $maxVal = max($dataset['data']);
                if ($maxVal > $currentMax) $currentMax = $maxVal;
            }
        }
        
        // Sesuaikan max value Y Axis berdasarkan data real + 10%
        $suggestedMax = $currentMax > $suggestedMax ? $currentMax + ($currentMax * 0.1) : $suggestedMax;

        // 5. Kembalikan Response jika dipanggil via AJAX Submit Filter
        if ($request->ajax()) {
            $tableHtml = '';
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
                $tableHtml .= '<td class="text-muted text-right py-2" style="font-size: 13px;">Rp. '.number_format($row['harga_beli'], 0, ',', '.').'</td>';
                $tableHtml .= '<td class="text-muted text-right py-2" style="font-size: 13px;">Rp. '.number_format($row['harga_jual'], 0, ',', '.').'</td>';
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