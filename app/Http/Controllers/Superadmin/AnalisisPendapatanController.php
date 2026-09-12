<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\MsSubKategoriProduk;
use Carbon\Carbon;

class AnalisisPendapatanController extends Controller
{
    // Palet warna untuk kategori F&B (dinamis, max terpakai bergantung colorIndex).
    // Regular & Private Room TIDAK pakai palet ini, warnanya fixed dari config('category-colors').
    private $colorPalette = ['#8b5cf6', '#0ea5e9', '#eab308', '#ec4899', '#14b8a6', '#f97316'];

    /**
     * Jam operasional venue: 10:00 - 01:00 (lewat tengah malam).
     * Disamakan dengan ProdukFnbController::getDateRange, sebelumnya pakai 06:00-23:59.
     */
    private function getDateRange(Request $request): array
    {
        $periode = $request->input('periode', 'bulanan');

        if ($periode === 'mingguan') {
            $minggu = $request->input('minggu', now()->format('Y-\WW'));
            if (str_contains($minggu, '-W')) {
                [$year, $week] = explode('-W', $minggu);
                $start = Carbon::now()->setISODate((int) $year, (int) $week)->startOfWeek();
                $end   = Carbon::now()->setISODate((int) $year, (int) $week)->endOfWeek();
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
            $tanggalInput = $request->input('tanggal') ?? $request->input('rentang_tanggal');

            if ($tanggalInput && str_contains($tanggalInput, ' - ')) {
                // Custom range multi-hari: granularitas per-tanggal di buildAxes(),
                // jam operasional tidak relevan di sini.
                $dates = explode(' - ', $tanggalInput);
                try {
                    $start = Carbon::parse(trim($dates[0]))->startOfDay();
                    $end   = Carbon::parse(trim($dates[1]))->endOfDay();
                } catch (\Exception $e) {
                    $start = Carbon::today()->setTime(10, 0, 0);
                    $end   = Carbon::tomorrow()->setTime(1, 59, 59);
                }
            } elseif ($tanggalInput) {
                try {
                    $start = Carbon::parse($tanggalInput)->setTime(10, 0, 0);
                    $end   = Carbon::parse($tanggalInput)->addDay()->setTime(1, 59, 59);
                } catch (\Exception $e) {
                    $start = Carbon::today()->setTime(10, 0, 0);
                    $end   = Carbon::tomorrow()->setTime(1, 59, 59);
                }
            } else {
                $start = Carbon::today()->setTime(10, 0, 0);
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
            $diff = $start->diffInDays($end);
            for ($i = 0; $i <= $diff; $i++) {
                $dt = $start->copy()->addDays($i);
                $labels[] = $dt->translatedFormat('d M');
                $slots[$dt->format('Y-m-d')] = 0;
            }
        }

        return ['labels' => $labels, 'slots' => $slots];
    }

    private function getSuggestedMax(string $periode): int
    {
        return $periode === 'harian' ? 400000 : 1000000;
    }

    private function getKategoriMasterList(): array
    {
        $list = [
            ['key' => 'ruangan:REGULAR', 'label' => 'Regular', 'produk' => 'Booking'],
            ['key' => 'ruangan:PRIVATE-ROOM', 'label' => 'Private Room', 'produk' => 'Booking'],
        ];

        $kategoriProduk = MsSubKategoriProduk::where('is_active', 1)
            ->whereNotNull('kategori_produk')
            ->distinct()
            ->pluck('kategori_produk');

        foreach ($kategoriProduk as $kp) {
            $list[] = ['key' => 'produk:' . $kp, 'label' => $kp, 'produk' => 'F&B'];
        }

        return $list;
    }

    private function findKategoriMeta(string $key, array $masterList): ?array
    {
        foreach ($masterList as $item) {
            if ($item['key'] === $key) return $item;
        }
        return null;
    }

    private function resolveColor(string $kategoriKey, int $colorIndex): string
    {
        $fixedColors = config('category-colors.kategori_ruangan');

        if ($kategoriKey === 'ruangan:REGULAR' && $fixedColors) {
            return $fixedColors['regular']['color'];
        }
        if ($kategoriKey === 'ruangan:PRIVATE-ROOM' && $fixedColors) {
            return $fixedColors['private_room']['color'];
        }

        return $this->colorPalette[$colorIndex % count($this->colorPalette)];
    }

    private function fetchDatasetForKategori(string $kategoriKey, $start, $end, $periode, $colorIndex, array $masterList)
    {
        $meta = $this->findKategoriMeta($kategoriKey, $masterList);
        if (!$meta) return null;

        $axes = $this->buildAxes($start, $end, $periode);
        $slots = $axes['slots'];

        [$type, $value] = explode(':', $kategoriKey, 2);

        if ($type === 'ruangan') {
            $rows = DB::table('tr_transaksi')
                ->join('penetapan_harga', 'tr_transaksi.id_penetapan_harga', '=', 'penetapan_harga.id_penetapan_harga')
                ->join('ms_ruangan', 'penetapan_harga.id_ruangan', '=', 'ms_ruangan.id_ruangan')
                ->where('tr_transaksi.status_pembayaran', 'lunas')
                ->where('ms_ruangan.kategori', $value)
                ->whereBetween('tr_transaksi.struk_created_at', [$start, $end])
                ->select('tr_transaksi.total_harga as nominal', 'tr_transaksi.struk_created_at as waktu')
                ->get();
        } else {
            $rows = DB::table('tr_pos_detail')
                ->join('tr_pos', 'tr_pos_detail.id_pos', '=', 'tr_pos.id_pos')
                ->join('ms_produk', 'tr_pos_detail.id_produk', '=', 'ms_produk.id_produk')
                ->join('ms_sub_kategori_produk', 'ms_produk.ms_sub_kategori_produk_id_sub_kategori_produk', '=', 'ms_sub_kategori_produk.id_sub_kategori_produk')
                ->where('tr_pos.status_pembayaran', 'lunas')
                ->where('ms_sub_kategori_produk.kategori_produk', $value)
                ->whereBetween('tr_pos.struk_created_at', [$start, $end])
                ->select('tr_pos_detail.subtotal as nominal', 'tr_pos.struk_created_at as waktu')
                ->get();
        }

        foreach ($rows as $row) {
            $dt = Carbon::parse($row->waktu);

            if ($periode === 'harian') {
                $key = $dt->format('H');
            } elseif ($periode === 'mingguan') {
                $key = $dt->dayOfWeekIso;
            } elseif ($periode === 'bulanan') {
                $key = (int) $dt->format('j');
            } else {
                $key = $dt->format('Y-m-d');
            }

            if (array_key_exists($key, $slots)) {
                $slots[$key] += (float) $row->nominal;
            }
        }

        return [
            'kategori_key' => $kategoriKey,
            'label' => $meta['label'],
            'data' => array_values($slots),
            'color' => $this->resolveColor($kategoriKey, $colorIndex),
        ];
    }

    private function getAggregatePerKategori($start, $end): array
    {
        $result = [];

        $booking = DB::table('tr_transaksi')
            ->join('penetapan_harga', 'tr_transaksi.id_penetapan_harga', '=', 'penetapan_harga.id_penetapan_harga')
            ->join('ms_ruangan', 'penetapan_harga.id_ruangan', '=', 'ms_ruangan.id_ruangan')
            ->where('tr_transaksi.status_pembayaran', 'lunas')
            ->whereBetween('tr_transaksi.struk_created_at', [$start, $end])
            ->select('ms_ruangan.kategori as kategori', DB::raw('COUNT(*) as trx'), DB::raw('SUM(tr_transaksi.total_harga) as total'))
            ->groupBy('ms_ruangan.kategori')
            ->get();

        foreach ($booking as $row) {
            $result['ruangan:' . $row->kategori] = ['trx' => (int) $row->trx, 'total' => (float) $row->total];
        }

        $produk = DB::table('tr_pos_detail')
            ->join('tr_pos', 'tr_pos_detail.id_pos', '=', 'tr_pos.id_pos')
            ->join('ms_produk', 'tr_pos_detail.id_produk', '=', 'ms_produk.id_produk')
            ->join('ms_sub_kategori_produk', 'ms_produk.ms_sub_kategori_produk_id_sub_kategori_produk', '=', 'ms_sub_kategori_produk.id_sub_kategori_produk')
            ->where('tr_pos.status_pembayaran', 'lunas')
            ->whereBetween('tr_pos.struk_created_at', [$start, $end])
            ->select(
                'ms_sub_kategori_produk.kategori_produk as kategori',
                DB::raw('COUNT(DISTINCT tr_pos.id_pos) as trx'),
                DB::raw('SUM(tr_pos_detail.subtotal) as total')
            )
            ->groupBy('ms_sub_kategori_produk.kategori_produk')
            ->get();

        foreach ($produk as $row) {
            $result['produk:' . $row->kategori] = ['trx' => (int) $row->trx, 'total' => (float) $row->total];
        }

        return $result;
    }

    private function getAvailableKategoriList(Request $request, array $masterList, array $aggregate): array
    {
        $list = $masterList;

        if ($request->filled('kategori') && $request->kategori !== 'semua') {
            $list = array_values(array_filter($list, fn($m) => $m['label'] === $request->kategori));
        }

        return array_values(array_filter($list, function ($m) use ($aggregate) {
            return isset($aggregate[$m['key']]) && $aggregate[$m['key']]['total'] > 0;
        }));
    }

    private function resolveKategoriKeys(Request $request, array $availableList): array
    {
        $availableKeys = array_column($availableList, 'key');

        if ($request->has('kategori_keys')) {
            $raw = $request->input('kategori_keys', '');
            $keys = is_array($raw) ? $raw : explode(',', $raw);
            $filtered = array_values(array_unique(array_filter($keys, fn($k) => in_array($k, $availableKeys))));

            if (!empty($filtered)) {
                return $filtered;
            }
        }

        return array_slice($availableKeys, 0, 4);
    }

    private function getKategoriTableData(Request $request, array $masterList, array $aggregate)
    {
        $grandTotal = array_sum(array_column($aggregate, 'total'));

        $rows = collect($masterList)->map(function ($meta) use ($aggregate, $grandTotal) {
            $data = $aggregate[$meta['key']] ?? ['trx' => 0, 'total' => 0];
            $total = $data['total'];
            $trx = $data['trx'];

            return [
                'kategori' => $meta['label'],
                'produk' => $meta['produk'],
                'trx' => $trx,
                'total' => $total,
                'persen' => $grandTotal > 0 ? round(($total / $grandTotal) * 100, 1) : 0,
                'rata' => $trx > 0 ? round($total / $trx) : 0,
            ];
        });

        if ($request->filled('kategori') && $request->kategori !== 'semua') {
            $rows = $rows->filter(fn($r) => $r['kategori'] === $request->kategori)->values();
        }

        $rows = $rows->sortByDesc('total')->values();

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
        $barColor = $row['produk'] === 'Booking' ? '#6f42c1' : '#f97316';

        return '<tr>
            <td class="px-4 text-muted">' . $no . '</td>
            <td class="font-weight-bold text-purple">' . e($row['kategori']) . '</td>
            <td class="text-muted">' . e($row['produk']) . '</td>
            <td class="text-center">' . $row['trx'] . '</td>
            <td class="text-right font-weight-bold">Rp ' . number_format($row['total'], 0, ',', '.') . '</td>
            <td class="text-center" style="width: 150px;">
                <div class="progress" style="height:6px;">
                    <div class="progress-bar" style="width:' . $row['persen'] . '%; background-color:' . $barColor . ';"></div>
                </div>
                <span class="font-weight-bold" style="font-size: 12px;">' . $row['persen'] . '%</span>
            </td>
            <td class="text-right text-muted">Rp ' . number_format($row['rata'], 0, ',', '.') . '</td>
        </tr>';
    }

    public function pendapatan(Request $request)
    {
        [$start, $end, $periode] = $this->getDateRange($request);
        $suggestedMax = $this->getSuggestedMax($periode);
        $masterList = $this->getKategoriMasterList();

        $aggregate = $this->getAggregatePerKategori($start, $end);
        uasort($aggregate, fn($a, $b) => $b['total'] <=> $a['total']);

        $availableList = $this->getAvailableKategoriList($request, $masterList, $aggregate);
        $order = array_flip(array_keys($aggregate));
        usort($availableList, fn($a, $b) => ($order[$a['key']] ?? PHP_INT_MAX) <=> ($order[$b['key']] ?? PHP_INT_MAX));

        if ($request->ajax() && $request->has('page') && !$request->has('add_kategori_key')) {
            $tableData = $this->getKategoriTableData($request, $masterList, $aggregate);

            $html = '';
            $startNum = ($tableData->currentPage() - 1) * $tableData->perPage() + 1;
            foreach ($tableData as $index => $row) {
                $html .= $this->tableRowHtml($startNum + $index, $row);
            }

            return response()->json([
                'html' => $html,
                'pagination' => $tableData->links('pagination::bootstrap-4')->render(),
                'info' => "Menampilkan {$tableData->firstItem()} hingga {$tableData->lastItem()} dari {$tableData->total()} entri",
            ]);
        }

        if ($request->ajax() && $request->has('add_kategori_key')) {
            $availableKeys = array_column($availableList, 'key');
            if (!in_array($request->add_kategori_key, $availableKeys)) {
                return response()->json(['success' => false, 'message' => 'Kategori tidak tersedia untuk filter saat ini.']);
            }

            $colorIndex = (int) $request->input('color_index', 0);
            $dataset = $this->fetchDatasetForKategori($request->add_kategori_key, $start, $end, $periode, $colorIndex, $masterList);
            return response()->json(['success' => true, 'dataset' => $dataset, 'suggestedMax' => $suggestedMax]);
        }

        $kategoriOptions = ['semua' => 'Semua Kategori'];
        foreach ($masterList as $meta) {
            $kategoriOptions[$meta['label']] = $meta['label'];
        }

        $allKategori = collect($availableList)->map(fn($m) => [
            'kategori_key' => $m['key'],
            'label' => $m['label'],
        ])->values();

        $kategoriKeys = $this->resolveKategoriKeys($request, $availableList);

        $axes = $this->buildAxes($start, $end, $periode);
        $chartLabels = $axes['labels'];
        $chartDatasets = [];

        foreach ($kategoriKeys as $index => $key) {
            $dataset = $this->fetchDatasetForKategori($key, $start, $end, $periode, $index, $masterList);
            if ($dataset) $chartDatasets[] = $dataset;
        }

        if ($request->ajax()) {
            $tableRequest = clone $request;
            $tableRequest->query->set('page', 1);
            $tableData = $this->getKategoriTableData($tableRequest, $masterList, $aggregate);

            $tableHtml = '';
            foreach ($tableData as $index => $row) {
                $tableHtml .= $this->tableRowHtml($index + 1, $row);
            }

            return response()->json([
                'success' => true,
                'labels' => $chartLabels,
                'datasets' => $chartDatasets,
                'suggestedMax' => $suggestedMax,
                'allKategori' => $allKategori,
                'table' => [
                    'html' => $tableHtml,
                    'pagination' => $tableData->links('pagination::bootstrap-4')->render(),
                    'info' => "Menampilkan {$tableData->firstItem()} hingga {$tableData->lastItem()} dari {$tableData->total()} entri",
                ],
            ]);
        }

        $tableData = $this->getKategoriTableData($request, $masterList, $aggregate);

        return view('superadmin.analitik.analisis-pendapatan', compact(
            'chartLabels', 'chartDatasets', 'tableData', 'kategoriOptions', 'allKategori', 'suggestedMax'
        ));
    }
}