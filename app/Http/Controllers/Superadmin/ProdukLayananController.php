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

    /**
     * Helper: Format Sumbu X Grafik
     */
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
     * Kueri untuk Tabel Data
     */
    private function getTableQuery(Request $request)
    {
        $query = PenetapanHarga::query()
            ->join('ms_paket', 'penetapan_harga.id_paket', '=', 'ms_paket.id_paket')
            ->join('ms_ruangan', 'penetapan_harga.id_ruangan', '=', 'ms_ruangan.id_ruangan')
            ->join('ms_sub_kategori_paket', 'ms_paket.ms_sub_kategori_paket_id_sub_kategori_paket', '=', 'ms_sub_kategori_paket.id_sub_kategori_paket')
            ->select(
                'ms_paket.nama_paket as paket',
                'ms_ruangan.kategori as kategori',
                'ms_sub_kategori_paket.nama_sub_kategori as sub',
                'penetapan_harga.harga as harga',
                'penetapan_harga.durasi_jam as jam',
                'penetapan_harga.sku as sku'
            );

        if ($request->filled('kategori') && $request->kategori !== 'semua') {
            $query->where('ms_ruangan.kategori', $request->kategori);
        }
        if ($request->filled('sub_kategori') && $request->sub_kategori !== 'semua') {
            $query->where('ms_sub_kategori_paket.id_sub_kategori_paket', $request->sub_kategori);
        }

        return $query;
    }

    public function index(Request $request)
    {
        [$start, $end, $periode] = $this->getDateRange($request);
        $suggestedMax = $this->getSuggestedMax($periode);

        // 1. HANDLER AJAX: Pagination Tabel Tanpa Reload
        if ($request->ajax() && $request->has('page')) {
            $tableData = $this->getTableQuery($request)->paginate(10);
            
            $html = '';
            $startNum = ($tableData->currentPage() - 1) * $tableData->perPage() + 1;
            foreach ($tableData as $index => $row) {
                $html .= '<tr>
                    <td class="px-4 text-muted py-3" style="font-size: 13px;">' . ($startNum + $index) . '</td>
                    <td class="text-dark py-3" style="font-size: 13px;">' . e($row->paket) . '</td>
                    <td class="text-muted py-3" style="font-size: 13px;">' . e($row->kategori) . '</td>
                    <td class="text-muted py-3" style="font-size: 13px;">' . e($row->sub) . '</td>
                    <td class="text-muted py-3" style="font-size: 13px;">Rp ' . number_format($row->harga, 0, ',', '.') . '</td>
                    <td class="text-muted py-3 text-center" style="font-size: 13px;">' . e($row->jam) . '</td>
                    <td class="text-muted py-3" style="font-size: 13px;">' . e($row->sku) . '</td>
                </tr>';
            }

            return response()->json([
                'html' => $html,
                'pagination' => $tableData->links('pagination::bootstrap-4')->render(),
                'info' => "Menampilkan {$tableData->firstItem()} hingga {$tableData->lastItem()} dari {$tableData->total()} entri"
            ]);
        }

        // 2. HANDLER AJAX: Menambah Pembanding ke Grafik
        if ($request->ajax() && $request->has('add_paket_id')) {
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

        // Data Paket untuk fitur Autocomplete (hanya diambil nama dan ID nya saja)
        $allPakets = MsPaket::where('is_active', 1)->select('id_paket', 'nama_paket')->get();

        // Cari Top 4 Paket terlaris berdasarkan rentang tanggal yang dipilih
        $topPackages = DB::table('tr_transaksi')
            ->join('penetapan_harga', 'tr_transaksi.id_penetapan_harga', '=', 'penetapan_harga.id_penetapan_harga')
            ->where('tr_transaksi.status_sewa', 'selesai')
            ->whereBetween('tr_transaksi.waktu_mulai', [$start, $end])
            ->select('penetapan_harga.id_paket', DB::raw('SUM(tr_transaksi.total_harga) as total_revenue'))
            ->groupBy('penetapan_harga.id_paket')
            ->orderByDesc('total_revenue')
            ->limit(4) // Sengaja 4 agar sisa 1 ruang kosong untuk test tambah pembanding
            ->pluck('id_paket')
            ->toArray();

        $axes = $this->buildAxes($start, $end, $periode);
        $chartLabels = $axes['labels'];
        $chartDatasets = [];
        
        foreach ($topPackages as $index => $paketId) {
            $chartDatasets[] = $this->fetchDatasetForPaket($paketId, $start, $end, $periode, $index);
        }

        // 4. HANDLER AJAX: Jika user ganti rentang tanggal / submit form filter
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'labels' => $chartLabels,
                'datasets' => $chartDatasets,
                'suggestedMax' => $suggestedMax,
            ]);
        }

        $tableData = $this->getTableQuery($request)->paginate(10);

        return view('superadmin.produk-layanan.index', compact(
            'chartLabels', 'chartDatasets', 'tableData', 'kategoriOptions', 'subKategoriOptions', 'allPakets', 'suggestedMax'
        ));
    }

    private function getSuggestedMax(string $periode): int
    {
        return $periode === 'harian' ? 400000 : 1000000;
    }
}