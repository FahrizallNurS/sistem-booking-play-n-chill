<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TrTransaksi;
use App\Models\TrPos;
use App\Models\User;
use Carbon\Carbon;
use Exception;

class SABerandaController extends Controller
{
    /**
     * Hitung rentang waktu Periode Sekarang (Current) dan Periode Sebelumnya (Previous)
     */
    private function getPeriodRanges(Request $request): array
    {
        $periode = $request->input('periode', 'bulanan');

        if ($periode === 'mingguan') {
            $minggu = $request->input('minggu', now()->format('Y-\WW'));
            if (str_contains($minggu, '-W')) {
                [$year, $week] = explode('-W', $minggu);
                $curStart = Carbon::now()->setISODate((int)$year, (int)$week)->startOfWeek();
                $curEnd   = Carbon::now()->setISODate((int)$year, (int)$week)->endOfWeek();
            } else {
                $curStart = Carbon::now()->startOfWeek();
                $curEnd   = Carbon::now()->endOfWeek();
            }
            // Pembanding: 1 minggu sebelumnya
            $prevStart = $curStart->copy()->subWeek();
            $prevEnd   = $curEnd->copy()->subWeek();

        } elseif ($periode === 'bulanan') {
            $bulan = $request->input('bulan', now()->format('Y-m'));
            try {
                $curStart = Carbon::parse($bulan . '-01')->startOfMonth();
            } catch (Exception $e) {
                $curStart = Carbon::now()->startOfMonth();
            }
            $curEnd = $curStart->copy()->endOfMonth();

            // Pembanding: 1 bulan sebelumnya
            $prevStart = $curStart->copy()->subMonth()->startOfMonth();
            $prevEnd   = $curStart->copy()->subMonth()->endOfMonth();

        } else {
            // Default: Harian / Date Range Input
            $tanggalInput = $request->input('tanggal') ?? $request->input('rentang_tanggal');

            if ($tanggalInput && str_contains($tanggalInput, ' - ')) {
                // Parse jika format input rentang "DD MMM YYYY - DD MMM YYYY" (DateRangePicker)
                $dates = explode(' - ', $tanggalInput);
                try {
                    $curStart = Carbon::parse(trim($dates[0]))->startOfDay();
                    $curEnd   = Carbon::parse(trim($dates[1]))->endOfDay();
                } catch (Exception $e) {
                    $curStart = Carbon::today()->setTime(6, 0, 0);
                    $curEnd   = Carbon::today()->setTime(23, 59, 59);
                }
            } elseif ($tanggalInput) {
                try {
                    $curStart = Carbon::parse($tanggalInput)->setTime(6, 0, 0);
                    $curEnd   = Carbon::parse($tanggalInput)->setTime(23, 59, 59);
                } catch (Exception $e) {
                    $curStart = Carbon::today()->setTime(6, 0, 0);
                    $curEnd   = Carbon::today()->setTime(23, 59, 59);
                }
            } else {
                $curStart = Carbon::today()->setTime(6, 0, 0);
                $curEnd   = Carbon::today()->setTime(23, 59, 59);
            }

            // Hitung durasi selisih hari untuk pembanding periode sebelumnya
            $diffInDays = max(1, (int)$curStart->diffInDays($curEnd) + 1);
            $prevStart  = $curStart->copy()->subDays($diffInDays);
            $prevEnd    = $curEnd->copy()->subDays($diffInDays);
        }

        return [$curStart, $curEnd, $prevStart, $prevEnd, $periode];
    }

    /**
     * Membangun susunan label Sumbu X dan array data grafik (gabungan Booking + F&B)
     */
    private function buildChartData($curStart, $curEnd, $prevStart, $prevEnd, $periode): array
    {
        $labels = [];
        $currentSlots = [];
        $previousSlots = [];

        if ($periode === 'mingguan') {
            $labels = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
            for ($i = 1; $i <= 7; $i++) {
                $currentSlots[$i] = 0;
                $previousSlots[$i] = 0;
            }
            $suggestedMax = 1000000;

        } elseif ($periode === 'bulanan') {
            $daysInMonth = $curStart->daysInMonth;
            for ($i = 1; $i <= $daysInMonth; $i++) {
                $labels[] = str_pad($i, 2, '0', STR_PAD_LEFT);
                $currentSlots[$i] = 0;
                $previousSlots[$i] = 0;
            }
            $suggestedMax = 1000000;

        } else {
            // Harian: Slot jam 06:00 s/d 23:00 (18 titik jam)
            for ($h = 6; $h <= 23; $h++) {
                $labels[] = str_pad($h, 2, '0', STR_PAD_LEFT) . ':00';
                $currentSlots[$h] = 0;
                $previousSlots[$h] = 0;
            }
            $suggestedMax = 400000;
        }

        // Kueri Data Periode Sekarang
        $curBooking = $this->queryBookingData($curStart, $curEnd);
        $curPos     = $this->queryPosData($curStart, $curEnd);

        // Kueri Data Periode Sebelumnya
        $prevBooking = $this->queryBookingData($prevStart, $prevEnd);
        $prevPos     = $this->queryPosData($prevStart, $prevEnd);

        // Petakan transaksi ke dalam slot sumbu X
        $this->populateSlots($currentSlots, $curBooking, $curPos, $periode);
        $this->populateSlots($previousSlots, $prevBooking, $prevPos, $periode);

        return [
            'labels'       => $labels,
            'currentData'  => array_values($currentSlots),
            'previousData' => array_values($previousSlots),
            'suggestedMax' => $suggestedMax,
        ];
    }

    private function queryBookingData($start, $end)
    {
        return TrTransaksi::where('status_sewa', 'selesai')
            ->whereBetween('created_at', [$start, $end])
            ->get(['total_harga', 'created_at']);
    }

    private function queryPosData($start, $end)
    {
        return TrPos::whereIn('status_pembayaran', ['sudah-bayar', 'lunas'])
            ->whereBetween('created_at', [$start, $end])
            ->get(['total_pos', 'created_at']);
    }

    private function populateSlots(&$slots, $bookingData, $posData, $periode)
    {
        foreach ($bookingData as $row) {
            $dt  = Carbon::parse($row->created_at);
            $key = $this->getSlotKey($dt, $periode);
            if (array_key_exists($key, $slots)) {
                $slots[$key] += (float) $row->total_harga;
            }
        }

        foreach ($posData as $row) {
            $dt  = Carbon::parse($row->created_at);
            $key = $this->getSlotKey($dt, $periode);
            if (array_key_exists($key, $slots)) {
                $slots[$key] += (float) $row->total_pos;
            }
        }
    }

    private function getSlotKey(Carbon $dt, string $periode): int
    {
        if ($periode === 'mingguan') {
            return $dt->dayOfWeekIso; // 1 (Senin) .. 7 (Minggu)
        } elseif ($periode === 'bulanan') {
            return (int) $dt->format('j'); // 1 .. 31
        } else {
            return (int) $dt->format('G'); // 0 .. 23 (Jam tanpa leading zero)
        }
    }

    /**
     * Hitung persentase aman terhadap pembagian nol, dibulatkan ke integer.
     */
    private function safePercent(int|float $part, int|float $total): int
    {
        return $total > 0 ? (int) round($part / $total * 100) : 0;
    }

    /**
     * Bangun data 6 card analitik berdasarkan data transaksi asli pada rentang periode.
     * Setiap card mengembalikan array item ['label', 'value', 'percent', 'color'].
     */
    private function buildAnalysisCardsData($curStart, $curEnd, float $pendapatanBooking, float $pendapatanFnb): array
    {
        // ------------------------------------------------------------
        // 1. Analisis Pendapatan (Booking vs F&B) — basis nominal
        // ------------------------------------------------------------
        $totalPendapatan = $pendapatanBooking + $pendapatanFnb;
        $persenBooking    = $this->safePercent($pendapatanBooking, $totalPendapatan);
        $persenFnb        = $this->safePercent($pendapatanFnb, $totalPendapatan);

        $analisisPendapatan = [
            [
                'label'   => 'Booking',
                'value'   => 'Rp ' . number_format($pendapatanBooking, 0, ',', '.') . ' (' . $persenBooking . '%)',
                'percent' => $persenBooking,
                'color'   => 'primary',
            ],
            [
                'label'   => 'F&B',
                'value'   => 'Rp ' . number_format($pendapatanFnb, 0, ',', '.') . ' (' . $persenFnb . '%)',
                'percent' => $persenFnb,
                'color'   => 'success',
            ],
        ];

        // ------------------------------------------------------------
        // 2. Produk Layanan / Sewa (Private Room vs Regular) — basis jumlah transaksi
        // ------------------------------------------------------------
        $ruanganCounts = TrTransaksi::join('penetapan_harga', 'tr_transaksi.id_penetapan_harga', '=', 'penetapan_harga.id_penetapan_harga')
            ->join('ms_ruangan', 'penetapan_harga.id_ruangan', '=', 'ms_ruangan.id_ruangan')
            ->where('tr_transaksi.status_sewa', 'selesai')
            ->whereBetween('tr_transaksi.created_at', [$curStart, $curEnd])
            ->selectRaw('ms_ruangan.kategori as kategori, COUNT(*) as total')
            ->groupBy('ms_ruangan.kategori')
            ->pluck('total', 'kategori');

        $privateCount  = (int) ($ruanganCounts['PRIVATE-ROOM'] ?? 0);
        $regularCount  = (int) ($ruanganCounts['REGULAR'] ?? 0);
        $totalRuangan  = $privateCount + $regularCount;
        $persenPrivate = $this->safePercent($privateCount, $totalRuangan);
        $persenRegular = $this->safePercent($regularCount, $totalRuangan);

        $produkLayanan = [
            [
                'label'   => 'Private Room',
                'value'   => $persenPrivate . '%',
                'percent' => $persenPrivate,
                'color'   => 'info',
            ],
            [
                'label'   => 'Regular',
                'value'   => $persenRegular . '%',
                'percent' => $persenRegular,
                'color'   => 'secondary',
            ],
        ];

        // ------------------------------------------------------------
        // 3. Produk F&B (Makanan vs Minuman) — basis qty terjual (tr_pos_detail.jumlah)
        // ------------------------------------------------------------
        $fnbQty = TrPos::join('tr_pos_detail', 'tr_pos.id_pos', '=', 'tr_pos_detail.id_pos')
            ->join('ms_produk', 'tr_pos_detail.id_produk', '=', 'ms_produk.id_produk')
            ->join('ms_sub_kategori_produk', 'ms_produk.ms_sub_kategori_produk_id_sub_kategori_produk', '=', 'ms_sub_kategori_produk.id_sub_kategori_produk')
            ->whereIn('tr_pos.status_pembayaran', ['sudah-bayar', 'lunas'])
            ->whereBetween('tr_pos.created_at', [$curStart, $curEnd])
            ->selectRaw('ms_sub_kategori_produk.sub_kategori_produk as sub_kategori, SUM(tr_pos_detail.jumlah) as total_qty')
            ->groupBy('ms_sub_kategori_produk.sub_kategori_produk')
            ->pluck('total_qty', 'sub_kategori');

        $makananQty   = (int) ($fnbQty['Makanan ringan'] ?? 0) + (int) ($fnbQty['Makanan berat'] ?? 0);
        $minumanQty   = (int) ($fnbQty['Minuman'] ?? 0);
        $totalFnbQty  = $makananQty + $minumanQty;
        $persenMakanan = $this->safePercent($makananQty, $totalFnbQty);
        $persenMinuman = $this->safePercent($minumanQty, $totalFnbQty);

        $produkFnb = [
            [
                'label'   => 'Makanan',
                'value'   => $persenMakanan . '%',
                'percent' => $persenMakanan,
                'color'   => 'warning',
            ],
            [
                'label'   => 'Minuman',
                'value'   => $persenMinuman . '%',
                'percent' => $persenMinuman,
                'color'   => 'danger',
            ],
        ];

        // ------------------------------------------------------------
        // 4. Metode Pembayaran (QRIS vs Tunai) — gabungan tr_transaksi + tr_pos, basis jumlah transaksi
        // ------------------------------------------------------------
        $metodeBooking = TrTransaksi::where('status_sewa', 'selesai')
            ->whereBetween('created_at', [$curStart, $curEnd])
            ->selectRaw('metode_pembayaran, COUNT(*) as total')
            ->groupBy('metode_pembayaran')
            ->pluck('total', 'metode_pembayaran');

        $metodeFnb = TrPos::whereIn('status_pembayaran', ['sudah-bayar', 'lunas'])
            ->whereBetween('created_at', [$curStart, $curEnd])
            ->selectRaw('metode_pembayaran, COUNT(*) as total')
            ->groupBy('metode_pembayaran')
            ->pluck('total', 'metode_pembayaran');

        $qrisCount   = (int) ($metodeBooking['QRIS'] ?? 0) + (int) ($metodeFnb['QRIS'] ?? 0);
        $tunaiCount  = (int) ($metodeBooking['TUNAI'] ?? 0) + (int) ($metodeFnb['TUNAI'] ?? 0);
        $totalMetode = $qrisCount + $tunaiCount;
        $persenQris  = $this->safePercent($qrisCount, $totalMetode);
        $persenTunai = $this->safePercent($tunaiCount, $totalMetode);

        $metodePembayaran = [
            [
                'label'   => 'QRIS',
                'value'   => $persenQris . '%',
                'percent' => $persenQris,
                'color'   => 'purple',
            ],
            [
                'label'   => 'Cash/Tunai',
                'value'   => $persenTunai . '%',
                'percent' => $persenTunai,
                'color'   => 'teal',
            ],
        ];

        // ------------------------------------------------------------
        // 5. Performa Kasir — gabungan jumlah booking + pos yang ditangani tiap admin, top 3
        //    Tidak difilter status: yang dihitung adalah beban penanganan, bukan revenue.
        // ------------------------------------------------------------
        $kasirBooking = TrTransaksi::whereBetween('created_at', [$curStart, $curEnd])
            ->whereNotNull('id_admin')
            ->selectRaw('id_admin, COUNT(*) as total')
            ->groupBy('id_admin')
            ->pluck('total', 'id_admin');

        $kasirPos = TrPos::whereBetween('created_at', [$curStart, $curEnd])
            ->whereNotNull('id_admin')
            ->selectRaw('id_admin, COUNT(*) as total')
            ->groupBy('id_admin')
            ->pluck('total', 'id_admin');

        $kasirTotals = [];
        foreach ($kasirBooking as $idAdmin => $count) {
            $kasirTotals[$idAdmin] = ($kasirTotals[$idAdmin] ?? 0) + (int) $count;
        }
        foreach ($kasirPos as $idAdmin => $count) {
            $kasirTotals[$idAdmin] = ($kasirTotals[$idAdmin] ?? 0) + (int) $count;
        }

        arsort($kasirTotals);
        $topKasir      = array_slice($kasirTotals, 0, 3, true);
        $maxKasirCount = $topKasir ? max($topKasir) : 0;

        $kasirNames = $topKasir
            ? User::whereIn('id_pengguna', array_keys($topKasir))->pluck('nama_pengguna', 'id_pengguna')
            : collect();

        $performaKasir = [];
        foreach ($topKasir as $idAdmin => $count) {
            $performaKasir[] = [
                'label'   => $kasirNames[$idAdmin] ?? ('Admin #' . $idAdmin),
                'value'   => '(' . $count . ')',
                'percent' => $this->safePercent($count, $maxKasirCount),
                'color'   => 'danger',
            ];
        }

        // ------------------------------------------------------------
        // 6. Laporan Transaksi (Selesai vs Dibatalkan) — hanya dari tr_transaksi
        // ------------------------------------------------------------
        $statusCounts = TrTransaksi::whereBetween('created_at', [$curStart, $curEnd])
            ->whereIn('status_sewa', ['selesai', 'dibatalkan'])
            ->selectRaw('status_sewa, COUNT(*) as total')
            ->groupBy('status_sewa')
            ->pluck('total', 'status_sewa');

        $selesaiCount    = (int) ($statusCounts['selesai'] ?? 0);
        $dibatalkanCount = (int) ($statusCounts['dibatalkan'] ?? 0);
        $totalStatus     = $selesaiCount + $dibatalkanCount;
        $persenSelesai   = $this->safePercent($selesaiCount, $totalStatus);
        $persenDibatalkan = $this->safePercent($dibatalkanCount, $totalStatus);

        $laporanTransaksi = [
            [
                'label'   => 'Selesai',
                'value'   => $selesaiCount . ' (' . $persenSelesai . '%)',
                'percent' => $persenSelesai,
                'color'   => 'success',
            ],
            [
                'label'   => 'Dibatalkan',
                'value'   => $dibatalkanCount . ' (' . $persenDibatalkan . '%)',
                'percent' => $persenDibatalkan,
                'color'   => 'danger',
            ],
        ];

        return compact(
            'analisisPendapatan',
            'produkLayanan',
            'produkFnb',
            'metodePembayaran',
            'performaKasir',
            'laporanTransaksi'
        );
    }

    public function index(Request $request)
    {
        [$curStart, $curEnd, $prevStart, $prevEnd, $periode] = $this->getPeriodRanges($request);

        // 1. Hitung Ringkasan Metrik
        $totalBookingCount = TrTransaksi::whereBetween('created_at', [$curStart, $curEnd])->count();
        $totalFnbCount     = TrPos::whereBetween('created_at', [$curStart, $curEnd])->count();

        $pendapatanBooking = (float) TrTransaksi::where('status_sewa', 'selesai')
            ->whereBetween('created_at', [$curStart, $curEnd])
            ->sum('total_harga');

        $pendapatanFnb = (float) TrPos::whereIn('status_pembayaran', ['sudah-bayar', 'lunas'])
            ->whereBetween('created_at', [$curStart, $curEnd])
            ->sum('total_pos');

        $totalPendapatanVal  = $pendapatanBooking + $pendapatanFnb;
        $totalTransaksiCount = $totalBookingCount + $totalFnbCount;
        $rataRataVal         = $totalTransaksiCount > 0 ? round($totalPendapatanVal / $totalTransaksiCount) : 0;

        $metricsData = [
            'total_pendapatan' => 'Rp ' . number_format($totalPendapatanVal, 0, ',', '.'),
            'total_booking'    => number_format($totalBookingCount, 0, ',', '.'),
            'total_fnb'        => number_format($totalFnbCount, 0, ',', '.'),
            'rata_rata'        => 'Rp ' . number_format($rataRataVal, 0, ',', '.'),
        ];

        // 2. Olah Data Grafik Pembanding
        $chartData = $this->buildChartData($curStart, $curEnd, $prevStart, $prevEnd, $periode);

        // 3. Olah Data 6 Card Analitik (data asli, bukan dummy)
        $analysisCardsData = $this->buildAnalysisCardsData($curStart, $curEnd, $pendapatanBooking, $pendapatanFnb);

        // 4. Respon AJAX jika dipanggil via Filter Form
        if ($request->ajax()) {
            $cardsHtml = [];
            foreach ($analysisCardsData as $key => $items) {
                $cardsHtml[$key] = view('superadmin.beranda-sa.partials.analysis-items', ['items' => $items])->render();
            }

            return response()->json([
                'success' => true,
                'data'    => [
                    'metrics' => $metricsData,
                    'chart'   => $chartData,
                    'cards'   => $cardsHtml,
                    'periode' => [
                        'start' => $curStart->translatedFormat('d M Y H:i'),
                        'end'   => $curEnd->translatedFormat('d M Y H:i'),
                    ]
                ],
                'message' => 'Data berhasil diperbarui'
            ]);
        }

        // 5. Render Web Biasa (Initial Load)
        return view('superadmin.beranda-sa.index', compact(
            'metricsData',
            'chartData',
            'analysisCardsData',
            'curStart',
            'curEnd',
            'periode'
        ));
    }
}