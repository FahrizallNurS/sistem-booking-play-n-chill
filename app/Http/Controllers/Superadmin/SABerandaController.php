<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TrTransaksi;
use App\Models\TrPos;
use Carbon\Carbon;
use Exception;

class SABerandaController extends Controller
{
    /**
     * Hitung rentang waktu Periode Sekarang (Current) dan Periode Sebelumnya (Previous)
     */
    private function getPeriodRanges(Request $request): array
    {
        $periode = $request->input('periode', 'harian');

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

    public function index(Request $request)
    {
        [$curStart, $curEnd, $prevStart, $prevEnd, $periode] = $this->getPeriodRanges($request);

        // 1. Hitung Ringkasan Metrik
        $totalBookingCount = TrTransaksi::whereBetween('created_at', [$curStart, $curEnd])->count();
        $totalFnbCount     = TrPos::whereBetween('created_at', [$curStart, $curEnd])->count();

        $pendapatanBooking = TrTransaksi::where('status_sewa', 'selesai')
            ->whereBetween('created_at', [$curStart, $curEnd])
            ->sum('total_harga');

        $pendapatanFnb = TrPos::whereIn('status_pembayaran', ['sudah-bayar', 'lunas'])
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

        // 3. Respon AJAX jika dipanggil via Filter Form
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data'    => [
                    'metrics' => $metricsData,
                    'chart'   => $chartData,
                    'periode' => [
                        'start' => $curStart->translatedFormat('d M Y H:i'),
                        'end'   => $curEnd->translatedFormat('d M Y H:i'),
                    ]
                ],
                'message' => 'Data berhasil diperbarui'
            ]);
        }

        // 4. Render Web Biasa (Initial Load)
        return view('superadmin.beranda-sa.index', compact(
            'metricsData',
            'chartData',
            'curStart',
            'curEnd',
            'periode'
        ));
    }
}