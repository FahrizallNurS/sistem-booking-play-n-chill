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
                // Custom range multi-hari: granularitas per-tanggal di buildAxes(), jam operasional
                // tidak relevan di sini karena tiap transaksi tetap masuk ke tanggalnya sendiri.
                $dates = explode(' - ', $tanggalInput);
                try {
                    $start = Carbon::parse(trim($dates[0]))->startOfDay();
                    $end   = Carbon::parse(trim($dates[1]))->endOfDay();
                } catch (\Exception $e) {
                    $start = Carbon::today()->startOfDay();
                    $end   = Carbon::today()->endOfDay();
                }
            } elseif ($tanggalInput) {
                // Jam operasional 10:00 - 01:00 (lewat tengah malam), jadi end harus nyambung
                // ke jam 01:59:59 keesokan harinya supaya transaksi jam 00:00-01:00 ikut ke-fetch.
                try {
                    $start = Carbon::parse($tanggalInput)->startOfDay();
                    $end   = Carbon::parse($tanggalInput)->addDay()->setTime(1, 59, 59);
                } catch (\Exception $e) {
                    $start = Carbon::today()->startOfDay();
                    $end   = Carbon::tomorrow()->setTime(1, 59, 59);
                }
            } else {
                $start = Carbon::today()->startOfDay();
                $end   = Carbon::tomorrow()->setTime(1, 59, 59);
            }
        }

        return [$start, $end, $periode];
    }

    private function buildAxes($start, $end, $periode): array
    {
        $labels = [];
        $slots = [];

        if ($periode === 'harian') {
            // Jam operasional: 10:00 - 01:00 (lewat tengah malam)
            $hours = array_merge(range(10, 23), [0, 1]);
            foreach ($hours as $i) {
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
            ->where('tr_pos.status_pembayaran', 'lunas')
            ->where('tr_pos_detail.id_produk', $produkId)
            ->whereBetween('tr_pos.struk_created_at', [$start, $end])
            ->select('tr_pos_detail.subtotal', 'tr_pos.struk_created_at')
            ->get();

        foreach ($transactions as $trx) {
            $dt = Carbon::parse($trx->struk_created_at);

            if ($periode === 'harian') {
                $key = $dt->format('H');
                // Abaikan jika transaksi terjadi di luar jam operasional (10:00 - 01:00)
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
        // Ambil Top 4 Produk Terlaris berdasarkan periode yang dipilih
        return DB::table('tr_pos_detail')
            ->join('tr_pos', 'tr_pos_detail.id_pos', '=', 'tr_pos.id_pos')
            ->where('tr_pos.status_pembayaran', 'lunas')
            ->whereBetween('tr_pos.struk_created_at', [$start, $end])
            ->whereIn('tr_pos_detail.id_produk', $availableIds)
            ->select('tr_pos_detail.id_produk', DB::raw('SUM(tr_pos_detail.subtotal) as total_revenue'))
            ->groupBy('tr_pos_detail.id_produk')
            ->orderByDesc('total_revenue')
            ->limit(4)
            ->pluck('id_produk')
            ->toArray();
    }

    private function getTableRows(Request $request, $start, $end)
    {
        $revenueSub = DB::table('tr_pos_detail')
            ->join('tr_pos', 'tr_pos_detail.id_pos', '=', 'tr_pos.id_pos')
            ->where('tr_pos.status_pembayaran', 'lunas')
            ->whereBetween('tr_pos.struk_created_at', [$start, $end])
            ->select(
                'tr_pos_detail.id_produk',
                DB::raw('SUM(tr_pos_detail.subtotal) as total'),
                DB::raw('COUNT(DISTINCT tr_pos.id_pos) as trx')
            )
            ->groupBy('tr_pos_detail.id_produk');

        $query = MsProduk::query()
            ->with('subKategori')
            ->leftJoinSub($revenueSub, 'agg', function ($join) {
                $join->on('ms_produk.id_produk', '=', 'agg.id_produk');
            })
            ->select(
                'ms_produk.*',
                DB::raw('COALESCE(agg.total, 0) as total_pendapatan'),
                DB::raw('COALESCE(agg.trx, 0) as jml_transaksi')
            );

        $kategoriFilter = $request->input('kategori', 'semua');
        $subKategoriFilter = $request->input('sub_kategori', 'semua');

        if ($kategoriFilter !== 'semua') {
            $query->whereHas('subKategori', fn($q) => $q->where('kategori_produk', $kategoriFilter));
        }
        if ($subKategoriFilter !== 'semua') {
            $query->whereHas('subKategori', fn($q) => $q->where('sub_kategori_produk', $subKategoriFilter));
        }

        $produkList = $query->get();

        // Grand total relatif ke filter aktif (kategori + sub_kategori + periode) saat ini.
        $grandTotal = $produkList->sum('total_pendapatan');

        return $produkList->map(function ($produk) use ($grandTotal) {
            $total = (float) $produk->total_pendapatan;
            $trx = (int) $produk->jml_transaksi;

            return [
                'foto'         => $produk->foto ? asset('uploads/fb/' . $produk->foto) : null,
                'nama'         => $produk->nama_produk,
                'kategori'     => $produk->subKategori->kategori_produk ?? 'Lainnya',
                'sub_kategori' => $produk->subKategori->sub_kategori_produk ?? '-',
                'status'       => $produk->is_active ? 'Aktif' : 'Nonaktif',
                'stock'        => $produk->stock,
                'total'        => $total,
                'trx'          => $trx,
                'persen'       => $grandTotal > 0 ? round(($total / $grandTotal) * 100, 1) : 0,
                'rata'         => $trx > 0 ? round($total / $trx) : 0,
            ];
        })->sortByDesc('total')->values();
    }

    private function paginateRows($rows, Request $request)
    {
        $perPage = 10;
        $page = (int) $request->input('page', 1);
        $slice = $rows->slice(($page - 1) * $perPage, $perPage)->values();

        return new \Illuminate\Pagination\LengthAwarePaginator(
            $slice,
            $rows->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );
    }

    private function tableRowHtml(int $no, array $row, array $statusBadgeVariant): string
    {
        $fotoImg = !empty($row['foto'])
            ? '<img src="' . $row['foto'] . '" alt="' . e($row['nama']) . '" class="rounded" style="width: 44px; height: 44px; object-fit: cover; border: 1px solid #e5e7eb;" onerror="this.onerror=null; this.src=\'' . asset('images/logo_dumb.png') . '\';">'
            : '<div class="d-flex align-items-center justify-content-center rounded bg-light text-muted" style="width: 44px; height: 44px; border: 1px solid #e5e7eb;"><i class="fas fa-image"></i></div>';

        $variant = $statusBadgeVariant[$row['status']] ?? 'secondary';
        $badge = '<span class="badge badge-' . $variant . ' px-2 py-1" style="font-size: 11px; font-weight: 600; border-radius: 4px;">' . e($row['status']) . '</span>';

        // Konsisten dgn warna kategori F&B di AnalisisPendapatanController.
        $barColor = '#f97316';

        return '<tr>
            <td class="px-4 text-muted py-2" style="font-size: 13px;">' . $no . '</td>
            <td class="py-2">' . $fotoImg . '</td>
            <td class="text-dark font-weight-bold py-2" style="font-size: 13px;">' . e($row['nama']) . '</td>
            <td class="text-muted py-2" style="font-size: 13px;">' . e($row['kategori']) . '</td>
            <td class="text-muted py-2" style="font-size: 13px;">' . e($row['sub_kategori']) . '</td>
            <td class="text-center py-2">' . $badge . '</td>
            <td class="text-right font-weight-bold py-2" style="font-size: 13px;">Rp ' . number_format($row['total'], 0, ',', '.') . '</td>
            <td class="text-center py-2" style="width: 150px;">
                <div class="progress" style="height:6px;">
                    <div class="progress-bar" style="width:' . $row['persen'] . '%; background-color:' . $barColor . ';"></div>
                </div>
                <span class="font-weight-bold" style="font-size: 12px;">' . $row['persen'] . '%</span>
            </td>
            <td class="text-right text-muted py-2" style="font-size: 13px;">Rp ' . number_format($row['rata'], 0, ',', '.') . '</td>
            <td class="text-muted text-center py-2" style="font-size: 13px;">' . $row['stock'] . '</td>
        </tr>';
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

        $statusBadgeVariant = ['Aktif' => 'success', 'Nonaktif' => 'danger'];

        // 2. HANDLER AJAX: Pagination Tabel Tanpa Reload
        if ($request->ajax() && $request->has('page') && !$request->has('add_produk_id')) {
            $rows = $this->getTableRows($request, $start, $end);
            $tableData = $this->paginateRows($rows, $request);

            $html = '';
            $startNum = ($tableData->currentPage() - 1) * $tableData->perPage() + 1;
            foreach ($tableData as $index => $row) {
                $html .= $this->tableRowHtml($startNum + $index, $row, $statusBadgeVariant);
            }

            return response()->json([
                'html' => $html,
                'pagination' => $tableData->links('pagination::bootstrap-4')->render(),
                'info' => "Menampilkan {$tableData->firstItem()} hingga {$tableData->lastItem()} dari {$tableData->total()} entri"
            ]);
        }

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

        // 5. HANDLER AJAX: submit filter utama (ganti periode/tanggal/kategori/sub_kategori)
        if ($request->ajax()) {
            $tableRequest = clone $request;
            $tableRequest->query->set('page', 1);
            $rows = $this->getTableRows($tableRequest, $start, $end);
            $tableData = $this->paginateRows($rows, $tableRequest);

            $tableHtml = '';
            foreach ($tableData as $index => $row) {
                $tableHtml .= $this->tableRowHtml($index + 1, $row, $statusBadgeVariant);
            }

            return response()->json([
                'success'      => true,
                'labels'       => $chartLabels,
                'datasets'     => $chartDatasets,
                'suggestedMax' => $suggestedMax,
                'allProduks'   => $allProduks,
                'table' => [
                    'html' => $tableHtml,
                    'pagination' => $tableData->links('pagination::bootstrap-4')->render(),
                    'info' => "Menampilkan {$tableData->firstItem()} hingga {$tableData->lastItem()} dari {$tableData->total()} entri"
                ]
            ]);
        }

        $rows = $this->getTableRows($request, $start, $end);
        $tableData = $this->paginateRows($rows, $request);

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