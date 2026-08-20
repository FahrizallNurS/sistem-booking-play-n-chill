<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\TrTransaksi;
use App\Models\TrPos;

class MetodePembayaranController extends Controller
{
    public function index(Request $request)
    {
        // =======================================================
        // 1. SETUP OPSI FILTER
        // =======================================================
        $periode = $request->input('periode', 'harian');
        $jenisFilter = strtolower($request->input('jenis', 'semua'));

        // =======================================================
        // 2. LOGIKA DYNAMIC DATE
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

        // =======================================================
        // 3. QUERY DATA TRANSAKSI
        // =======================================================
        $trxQuery = TrTransaksi::whereBetween('created_at', [$queryStart, $queryEnd])
            ->whereNotIn('status_sewa', ['Batal', 'Dibatalkan'])
            ->where('status_pembayaran', 'lunas'); 
            
        $posQuery = TrPos::whereBetween('created_at', [$queryStart, $queryEnd])
            ->whereNotIn('status_pesanan', ['Dibatalkan'])
            ->where('status_pembayaran', 'lunas'); 

        if ($jenisFilter !== 'semua') {
            $trxQuery->where('metode_pembayaran', strtoupper($jenisFilter));
            $posQuery->where('metode_pembayaran', strtoupper($jenisFilter));
        }

        $trxs = $trxQuery->get();
        $poses = $posQuery->get();

        // =======================================================
        // 4. HITUNG RINGKASAN & TABEL
        // =======================================================
        $methodStats = [
            'QRIS'  => ['count' => 0, 'revenue' => 0],
            'TUNAI' => ['count' => 0, 'revenue' => 0],
        ];

        foreach ($trxs as $trx) {
            $m = strtoupper($trx->metode_pembayaran ?? '');
            if (isset($methodStats[$m])) {
                $methodStats[$m]['count']++;
                $methodStats[$m]['revenue'] += $trx->total_harga;
            }
        }

        foreach ($poses as $pos) {
            $m = strtoupper($pos->metode_pembayaran ?? '');
            if (isset($methodStats[$m])) {
                $methodStats[$m]['count']++;
                $methodStats[$m]['revenue'] += $pos->total_pos;
            }
        }

        $totalTrx = 0;
        $totalRev = 0;
        $mostUsedName = '-';
        $mostUsedTx = 0;
        $highestRevName = '-';
        $highestRevAmount = 0;

        foreach ($methodStats as $m => $stat) {
            $totalTrx += $stat['count'];
            $totalRev += $stat['revenue'];
            
            if ($stat['count'] > $mostUsedTx) {
                $mostUsedTx = $stat['count'];
                $mostUsedName = $m;
            }
            if ($stat['revenue'] > $highestRevAmount) {
                $highestRevAmount = $stat['revenue'];
                $highestRevName = $m;
            }
        }

        $summary = [
            'total_trx'          => $totalTrx,
            'total_revenue'      => $totalRev,
            'most_used_name'     => $mostUsedName,
            'most_used_tx'       => $mostUsedTx,
            'highest_rev_name'   => $highestRevName,
            'highest_rev_amount' => $highestRevAmount,
        ];

        $tableData = [];
        foreach ($methodStats as $m => $stat) {
            if ($stat['count'] > 0 || $jenisFilter === 'semua') {
                $pct = $totalRev > 0 ? round(($stat['revenue'] / $totalRev) * 100, 1) : 0;
                $tableData[] = [
                    'metode'           => $m,
                    'jml_trx'          => $stat['count'],
                    'persentase'       => $pct . '%',
                    'total_pendapatan' => $stat['revenue'],
                ];
            }
        }

        // =======================================================
        // 5. DATA GRAFIK
        // =======================================================
        $chartLabels = [];
        $chartDatasets = [];
        $iterables = [];

        if ($periode === 'harian') {
            for ($i = 6; $i <= 23; $i++) {
                $iterables[] = [
                    'label' => str_pad($i, 2, '0', STR_PAD_LEFT) . ':00',
                    'filter' => fn($d) => $d->isSameDay($queryStart) && $d->hour === $i
                ];
            }
        } elseif ($periode === 'mingguan') {
            foreach (CarbonPeriod::create($queryStart, '1 day', $queryEnd) as $date) {
                $iterables[] = [
                    'label' => $date->locale('id')->translatedFormat('l'),
                    'filter' => fn($d) => $d->isSameDay($date)
                ];
            }
        } else {
            foreach (CarbonPeriod::create($queryStart, '1 day', $queryEnd) as $date) {
                $iterables[] = [
                    'label' => $date->format('d'),
                    'filter' => fn($d) => $d->isSameDay($date)
                ];
            }
        }

        $colors = ['QRIS' => '#0ea5e9', 'TUNAI' => '#22c55e'];
        $methodsToChart = ($jenisFilter !== 'semua') ? [strtoupper($jenisFilter)] : ['QRIS', 'TUNAI'];

        foreach ($methodsToChart as $m) {
            if (!isset($colors[$m])) continue; 

            $dataArr = [];
            foreach ($iterables as $step) {
                if (!in_array($step['label'], $chartLabels)) {
                    $chartLabels[] = $step['label'];
                }

              $sumTrx = $trxs->filter(function($t) use ($m, $step) {
                return strtoupper($t->metode_pembayaran ?? '') === $m && $step['filter'](Carbon::parse($t->created_at));
                })->sum('total_harga');

                $sumPos = $poses->filter(function($p) use ($m, $step) {
                    return strtoupper($p->metode_pembayaran ?? '') === $m && $step['filter'](Carbon::parse($p->created_at));
                })->sum('total_pos');

                $dataArr[] = $sumTrx + $sumPos;
            }

            $chartDatasets[] = [
                'label' => $m,
                'data'  => $dataArr,
                'color' => $colors[$m],
            ];
        }

        // =======================================================
        // 6. RESPONSE AJAX
        // =======================================================
        if ($request->ajax()) {
            $tableHtml = '';
            foreach ($tableData as $idx => $row) {
                $tableHtml .= '<tr>';
                $tableHtml .= '<td class="py-3 text-muted" style="font-size: 13px;">'.($idx + 1).'</td>';
                $tableHtml .= '<td class="py-3 text-dark font-weight-bold" style="font-size: 13px;">'.$row['metode'].'</td>';
                $tableHtml .= '<td class="py-3 text-muted" style="font-size: 13px;">'.$row['jml_trx'].'</td>';
                $tableHtml .= '<td class="py-3 text-muted" style="font-size: 13px;">'.$row['persentase'].'</td>';
                $tableHtml .= '<td class="py-3 text-dark font-weight-bold" style="font-size: 13px;">Rp '.number_format($row['total_pendapatan'], 0, ',', '.').'</td>';
                $tableHtml .= '</tr>';
            }

            return response()->json([
                'success'  => true,
                'labels'   => $chartLabels,
                'datasets' => $chartDatasets,
                'html'     => $tableHtml,
                'total'    => count($tableData),
                'summary'  => $summary
            ]);
        }

        return view('superadmin.metode-pembayaran.index', compact(
            'chartLabels', 'chartDatasets', 'tableData', 'summary'
        ));
    }
}