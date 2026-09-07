<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\MsPaket;
use App\Models\PenetapanHarga;
use App\Models\MsSubKategoriPaket;
use Carbon\Carbon;

class ProdukLayananController extends Controller
{
    // Palet warna khusus grafik pembanding (Maks 5)
    private $colorPalette = ['#10b981', '#f97316', '#0ea5e9', '#8b5cf6', '#ec4899'];

    private function getDateRange(Request $request): array
    {
        $periode = $request->input('periode', 'bulanan');

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
                // Parse jika format input rentang "DD MMM YYYY - DD MMM YYYY" (DateRangePicker)
                $dates = explode(' - ', $tanggalInput);
                try {
                    $start = Carbon::parse(trim($dates[0]))->startOfDay();
                    $end   = Carbon::parse(trim($dates[1]))->endOfDay();
                } catch (\Exception $e) {
                    $start = Carbon::today()->setTime(6, 0, 0);
                    $end   = Carbon::today()->setTime(23, 59, 59);
                }
            } elseif ($tanggalInput) {
                try {
                    $start = Carbon::parse($tanggalInput)->setTime(6, 0, 0);
                    $end   = Carbon::parse($tanggalInput)->setTime(23, 59, 59);
                } catch (\Exception $e) {
                    $start = Carbon::today()->setTime(6, 0, 0);
                    $end   = Carbon::today()->setTime(23, 59, 59);
                }
            } else {
                $start = Carbon::today()->setTime(6, 0, 0);
                $end   = Carbon::today()->setTime(23, 59, 59);
            }
        }

        return [$start, $end, $periode];
    }

    private function buildAxes($start, $end, $periode): array
    {
        $labels = [];
        $slots = [];

        if ($periode === 'harian') {
            // Format Jam (00:00 - 23:00)
            for ($i = 0; $i < 24; $i++) {
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
            // Rentang waktu dinamis (kustom)
            $diff = $start->diffInDays($end);
            for ($i = 0; $i <= $diff; $i++) {
                $dt = $start->copy()->addDays($i);
                $labels[] = $dt->translatedFormat('d M');
                $slots[$dt->format('Y-m-d')] = 0;
            }
        }

        return ['labels' => $labels, 'slots' => $slots];
    }

    /**
     * Fetch Dataset 1 Baris Grafik berdasarkan ID Paket
     */
    private function fetchDatasetForPaket($paketId, $start, $end, $periode, $colorIndex)
    {
        $paket = MsPaket::find($paketId);
        if (!$paket) return null;

        $axes = $this->buildAxes($start, $end, $periode);
        $slots = $axes['slots'];

        $transactions = DB::table('tr_transaksi')
            ->join('penetapan_harga', 'tr_transaksi.id_penetapan_harga', '=', 'penetapan_harga.id_penetapan_harga')
            ->where('tr_transaksi.status_sewa', 'selesai')
            ->where('penetapan_harga.id_paket', $paketId)
            ->whereBetween('tr_transaksi.waktu_mulai', [$start, $end])
            ->select('tr_transaksi.total_harga', 'tr_transaksi.waktu_mulai')
            ->get();

        foreach ($transactions as $trx) {
            $dt = Carbon::parse($trx->waktu_mulai);

            if ($periode === 'harian') {
                $key = $dt->format('H'); // Ambil jam (00 - 23)
            } elseif ($periode === 'mingguan') {
                $key = $dt->dayOfWeekIso;
            } elseif ($periode === 'bulanan') {
                $key = (int) $dt->format('j');
            } else {
                $key = $dt->format('Y-m-d');
            }

            if (array_key_exists($key, $slots)) {
                $slots[$key] += (float) $trx->total_harga;
            }
        }

        return [
            'id_paket' => $paketId,
            'label' => $paket->nama_paket,
            'data' => array_values($slots),
            'color' => $this->colorPalette[$colorIndex % count($this->colorPalette)]
        ];
    }

    /**
     * Warna progress bar kontribusi %, disamakan dengan palet fixed
     * Regular/Private Room yang dipakai di AnalisisPendapatanController,
     * supaya kategori ruangan yang sama konsisten warnanya lintas halaman.
     * Di luar 2 kategori itu (kalau ada), fallback ke ungu default.
     */
    private function progressBarColor(string $kategori): string
    {
        $fixedColors = config('category-colors.kategori_ruangan');

        if ($kategori === 'REGULAR' && $fixedColors) {
            return $fixedColors['regular']['color'];
        }
        if ($kategori === 'PRIVATE-ROOM' && $fixedColors) {
            return $fixedColors['private_room']['color'];
        }

        return '#6f42c1';
    }

    /**
     * Baris tabel: 1 baris = 1 penetapan_harga (paket + ruangan + durasi + SKU),
     * dilengkapi Total Pendapatan & Jml Transaksi hasil agregat tr_transaksi
     * pada rentang waktu $start-$end (mengikuti filter periode/tanggal aktif).
     *
     * Kontribusi % dihitung terhadap grand total dari SELURUH baris yang lolos
     * filter kategori/sub_kategori/periode saat ini (bukan grand total absolut
     * semua data) -- beda dengan AnalisisPendapatanController.
     *
     * Baris tanpa transaksi pada periode terpilih TETAP ditampilkan (Rp0, 0%).
     */
    private function getTableRows(Request $request, $start, $end)
    {
        $revenueSub = DB::table('tr_transaksi')
            ->where('status_sewa', 'selesai')
            ->whereBetween('waktu_mulai', [$start, $end])
            ->select(
                'id_penetapan_harga',
                DB::raw('SUM(total_harga) as total'),
                DB::raw('COUNT(*) as trx')
            )
            ->groupBy('id_penetapan_harga');

        $query = PenetapanHarga::query()
            ->join('ms_paket', 'penetapan_harga.id_paket', '=', 'ms_paket.id_paket')
            ->join('ms_ruangan', 'penetapan_harga.id_ruangan', '=', 'ms_ruangan.id_ruangan')
            ->join('ms_sub_kategori_paket', 'ms_paket.ms_sub_kategori_paket_id_sub_kategori_paket', '=', 'ms_sub_kategori_paket.id_sub_kategori_paket')
            ->leftJoinSub($revenueSub, 'agg', function ($join) {
                $join->on('penetapan_harga.id_penetapan_harga', '=', 'agg.id_penetapan_harga');
            })
            ->select(
                'ms_paket.nama_paket as paket',
                'ms_ruangan.kategori as kategori',
                'ms_sub_kategori_paket.nama_sub_kategori as sub',
                'penetapan_harga.harga as harga',
                'penetapan_harga.durasi_jam as jam',
                'penetapan_harga.sku as sku',
                DB::raw('COALESCE(agg.total, 0) as total_pendapatan'),
                DB::raw('COALESCE(agg.trx, 0) as jml_transaksi')
            );

        if ($request->filled('kategori') && $request->kategori !== 'semua') {
            $query->where('ms_ruangan.kategori', $request->kategori);
        }
        if ($request->filled('sub_kategori') && $request->sub_kategori !== 'semua') {
            $query->where('ms_sub_kategori_paket.id_sub_kategori_paket', $request->sub_kategori);
        }

        $rows = $query->get();

        // Grand total relatif ke filter aktif (kategori + sub_kategori + periode) saat ini.
        $grandTotal = $rows->sum('total_pendapatan');

        return $rows->map(function ($row) use ($grandTotal) {
            $total = (float) $row->total_pendapatan;
            $trx = (int) $row->jml_transaksi;

            return [
                'paket' => $row->paket,
                'kategori' => $row->kategori,
                'sub' => $row->sub,
                'harga' => $row->harga,
                'jam' => $row->jam,
                'sku' => $row->sku,
                'total' => $total,
                'trx' => $trx,
                'persen' => $grandTotal > 0 ? round(($total / $grandTotal) * 100, 1) : 0,
                'rata' => $trx > 0 ? round($total / $trx) : 0,
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

    private function tableRowHtml(int $no, array $row): string
    {
        $barColor = $this->progressBarColor($row['kategori']);

        return '<tr>
            <td class="px-4 text-muted py-3" style="font-size: 13px;">' . $no . '</td>
            <td class="text-dark py-3 font-weight-bold" style="font-size: 13px;">' . e($row['paket']) . '</td>
            <td class="text-muted py-3" style="font-size: 13px;">' . e($row['kategori']) . '</td>
            <td class="text-muted py-3" style="font-size: 13px;">' . e($row['sub']) . '</td>
            <td class="text-muted py-3" style="font-size: 13px;">Rp ' . number_format($row['harga'], 0, ',', '.') . '</td>
            <td class="text-muted py-3 text-center" style="font-size: 13px;">' . e($row['jam']) . '</td>
            <td class="text-muted py-3" style="font-size: 13px;">' . e($row['sku']) . '</td>
            <td class="text-right font-weight-bold py-3" style="font-size: 13px;">Rp ' . number_format($row['total'], 0, ',', '.') . '</td>
            <td class="text-center py-3" style="width: 150px;">
                <div class="progress" style="height:6px;">
                    <div class="progress-bar" style="width:' . $row['persen'] . '%; background-color:' . $barColor . ';"></div>
                </div>
                <span class="font-weight-bold" style="font-size: 12px;">' . $row['persen'] . '%</span>
            </td>
            <td class="text-right text-muted py-3" style="font-size: 13px;">Rp ' . number_format($row['rata'], 0, ',', '.') . '</td>
        </tr>';
    }

    /**
     * Mengambil daftar paket yang TERSISA setelah filter kategori diterapkan.
     */
    private function getAvailablePakets(Request $request): array
    {
        $query = PenetapanHarga::query()
            ->join('ms_paket', 'penetapan_harga.id_paket', '=', 'ms_paket.id_paket')
            ->join('ms_ruangan', 'penetapan_harga.id_ruangan', '=', 'ms_ruangan.id_ruangan')
            ->join('ms_sub_kategori_paket', 'ms_paket.ms_sub_kategori_paket_id_sub_kategori_paket', '=', 'ms_sub_kategori_paket.id_sub_kategori_paket')
            ->where('ms_paket.is_active', 1)
            ->select('ms_paket.id_paket', 'ms_paket.nama_paket')
            ->distinct();

        if ($request->filled('kategori') && $request->kategori !== 'semua') {
            $query->where('ms_ruangan.kategori', $request->kategori);
        }
        if ($request->filled('sub_kategori') && $request->sub_kategori !== 'semua') {
            $query->where('ms_sub_kategori_paket.id_sub_kategori_paket', $request->sub_kategori);
        }

        return $query->get()->toArray();
    }

    public function index(Request $request)
    {
        [$start, $end, $periode] = $this->getDateRange($request);
        $suggestedMax = $this->getSuggestedMax($periode);

        // Filter paket yang sah sesuai kondisi dropdown "Kategori"
        $allPakets = $this->getAvailablePakets($request);

        // 1. HANDLER AJAX: Pagination Tabel Tanpa Reload
        if ($request->ajax() && $request->has('page') && !$request->has('add_paket_id')) {
            $rows = $this->getTableRows($request, $start, $end);
            $tableData = $this->paginateRows($rows, $request);

            $html = '';
            $startNum = ($tableData->currentPage() - 1) * $tableData->perPage() + 1;
            foreach ($tableData as $index => $row) {
                $html .= $this->tableRowHtml($startNum + $index, $row);
            }

            return response()->json([
                'html' => $html,
                'pagination' => $tableData->links('pagination::bootstrap-4')->render(),
                'info' => "Menampilkan {$tableData->firstItem()} hingga {$tableData->lastItem()} dari {$tableData->total()} entri"
            ]);
        }

        // 2. HANDLER AJAX: Menambah Pembanding ke Grafik
        if ($request->ajax() && $request->has('add_paket_id')) {
            $availableIds = array_column($allPakets, 'id_paket');
            if (!in_array($request->add_paket_id, $availableIds)) {
                return response()->json(['success' => false, 'message' => 'Paket tidak tersedia untuk filter saat ini.']);
            }

            $dataset = $this->fetchDatasetForPaket($request->add_paket_id, $start, $end, $periode, $request->color_index ?? 0);
            return response()->json(['success' => true, 'dataset' => $dataset, 'suggestedMax' => $suggestedMax]);
        }

        // 3. INITIAL LOAD & SUBMIT FILTER (Data Dropdown)
        $kategoriOptions = ['semua' => 'Semua Kategori', 'REGULAR' => 'REGULAR', 'PRIVATE-ROOM' => 'PRIVATE-ROOM'];

        $subKategoriOptions = ['semua' => 'Semua Sub Kategori'];
        $subKategoris = MsSubKategoriPaket::where('is_active', 1)->get();
        foreach ($subKategoris as $sub) {
            $subKategoriOptions[$sub->id_sub_kategori_paket] = $sub->nama_sub_kategori;
        }

        // Paket yang tampil di grafik: dari state client (jika ada), atau Top-4 terlaris
        $paketIds = $this->resolvePaketIds($request, $start, $end, $allPakets);

        $axes = $this->buildAxes($start, $end, $periode);
        $chartLabels = $axes['labels'];
        $chartDatasets = [];

        foreach ($paketIds as $index => $paketId) {
            $dataset = $this->fetchDatasetForPaket($paketId, $start, $end, $periode, $index);
            if ($dataset) {
                $chartDatasets[] = $dataset;
            }
        }

        // 4. HANDLER AJAX: Jika user ganti rentang tanggal / submit form filter
        if ($request->ajax()) {
            // Render ulang tabel halaman 1 dan kirim daftar autocomplete paket terbaru
            $tableRequest = clone $request;
            $tableRequest->query->set('page', 1);
            $rows = $this->getTableRows($tableRequest, $start, $end);
            $tableData = $this->paginateRows($rows, $tableRequest);

            $tableHtml = '';
            foreach ($tableData as $index => $row) {
                $tableHtml .= $this->tableRowHtml($index + 1, $row);
            }

            return response()->json([
                'success' => true,
                'labels' => $chartLabels,
                'datasets' => $chartDatasets,
                'suggestedMax' => $suggestedMax,
                'allPakets' => $allPakets,
                'table' => [
                    'html' => $tableHtml,
                    'pagination' => $tableData->links('pagination::bootstrap-4')->render(),
                    'info' => "Menampilkan {$tableData->firstItem()} hingga {$tableData->lastItem()} dari {$tableData->total()} entri"
                ]
            ]);
        }

        $rows = $this->getTableRows($request, $start, $end);
        $tableData = $this->paginateRows($rows, $request);

        return view('superadmin.produk-layanan.index', compact(
            'chartLabels', 'chartDatasets', 'tableData', 'kategoriOptions', 'subKategoriOptions', 'allPakets', 'suggestedMax'
        ));
    }

    private function getSuggestedMax(string $periode): int
    {
        return $periode === 'harian' ? 400000 : 1000000;
    }

    private function resolvePaketIds(Request $request, $start, $end, array $availablePakets): array
    {
        $availableIds = array_column($availablePakets, 'id_paket');

        if ($request->has('paket_ids')) {
            $raw = $request->input('paket_ids', '');
            $ids = is_array($raw) ? $raw : explode(',', $raw);

            // Validasi id yang masuk, pastikan masih match dengan filter aktif
            $filtered = array_values(array_unique(array_filter(
                array_map('intval', $ids),
                fn($id) => in_array($id, $availableIds)
            )));

            if (!empty($filtered)) {
                return $filtered;
            }
        }

        // Kalau kosong (atau ga ada yg match), balikin default top-4 revenue DARI paket yang tersedia
        return DB::table('tr_transaksi')
            ->join('penetapan_harga', 'tr_transaksi.id_penetapan_harga', '=', 'penetapan_harga.id_penetapan_harga')
            ->where('tr_transaksi.status_sewa', 'selesai')
            ->whereBetween('tr_transaksi.waktu_mulai', [$start, $end])
            ->whereIn('penetapan_harga.id_paket', $availableIds)
            ->select('penetapan_harga.id_paket', DB::raw('SUM(tr_transaksi.total_harga) as total_revenue'))
            ->groupBy('penetapan_harga.id_paket')
            ->orderByDesc('total_revenue')
            ->limit(4)
            ->pluck('id_paket')
            ->toArray();
    }
}