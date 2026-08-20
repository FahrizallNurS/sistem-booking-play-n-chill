<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\User; 
use App\Models\TrPos;
use App\Models\TrTransaksi;

class PenjualanKasirController extends Controller
{
    public function index(Request $request)
    {
        // =======================================================
        // 1. SETUP OPSI FILTER
        // =======================================================
        $periode = $request->input('periode', 'harian');
        $kasirFilter = $request->input('nama_kasir', 'semua');
        
        // Mengambil daftar kasir (role disamakan menjadi 'admin')
        $kasirDb = User::where('role', 'admin')->pluck('nama_pengguna', 'id_pengguna')->toArray();
        $kasirOptions = ['semua' => 'Semua Kasir'] + $kasirDb;

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
        // Query untuk F&B
        $queryPos = TrPos::whereBetween('created_at', [$queryStart, $queryEnd])
                         ->whereNotIn('status_pesanan', ['Dibatalkan']);
        
        if ($kasirFilter !== 'semua') {
            $queryPos->where('id_admin', $kasirFilter); 
        }
        $transaksiPos = $queryPos->get();
        
        // Query untuk Booking Ruangan/Paket
        $queryBooking = TrTransaksi::whereBetween('created_at', [$queryStart, $queryEnd])
                         ->whereNotIn('status_sewa', ['Batal', 'Dibatalkan']);
    
        if ($kasirFilter !== 'semua') {
            $queryBooking->where('id_admin', $kasirFilter);
        }
        $transaksiBooking = $queryBooking->get();

        // =======================================================
        // 4. DATA TABEL
        // =======================================================
        $tableData = [];
        
        // Role disamakan menjadi 'admin' agar sinkron dengan dropdown di atas
        $kasirs = ($kasirFilter !== 'semua') 
            ? User::where('id_pengguna', $kasirFilter)->get() 
            : User::where('role', 'admin')->get(); 

        foreach ($kasirs as $kasir) {
          $fnbKasir = $transaksiPos->where('id_admin', $kasir->id_pengguna)->sum('total_pos');
            $bookingKasir = $transaksiBooking->where('id_admin', $kasir->id_pengguna)->sum('total_harga');      
            $refundKasir = 0; // Jika nanti ada tabel refund, sesuaikan di sini
            
            $total = $bookingKasir + $fnbKasir - $refundKasir;
            
            $tableData[] = [
                'nama'    => $kasir->nama_pengguna,
                'booking' => $bookingKasir,
                'fnb'     => $fnbKasir,
                'refund'  => $refundKasir,
                'total'   => $total,
            ];
        }

        // =======================================================
        // 5. DATA GRAFIK (Duplikat sudah dihapus)
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

        $colors = ['#7c3aed', '#10b981', '#f59e0b', '#3b82f6', '#ef4444'];
        
        foreach ($kasirs as $index => $kasir) {
            $dataKasir = [];
            foreach ($iterables as $step) {
                if (!in_array($step['label'], $chartLabels)) {
                    $chartLabels[] = $step['label'];
                }

                $fnbSesi = $transaksiPos->filter(function($trx) use ($kasir, $step) {
                return $trx->id_admin == $kasir->id_pengguna && $step['filter'](Carbon::parse($trx->created_at));
                })->sum('total_pos');

                $bookingSesi = $transaksiBooking->filter(function($trx) use ($kasir, $step) {
                    return $trx->id_admin == $kasir->id_pengguna && $step['filter'](Carbon::parse($trx->created_at));
                })->sum('total_harga'); 

                $dataKasir[] = $fnbSesi + $bookingSesi; 
            }

            $chartDatasets[] = [
                'label' => $kasir->nama_pengguna,
                'data'  => $dataKasir,
                'color' => $colors[$index % count($colors)],
            ];
        }

        // =======================================================
        // 6. RESPONSE AJAX
        // =======================================================
        if ($request->ajax()) {
            $tableHtml = '';
            foreach ($tableData as $idx => $row) {
                $tableHtml .= '<tr>';
                $tableHtml .= '<td class="px-4 text-muted py-3" style="font-size: 13px;">'.($idx + 1).'</td>';
                $tableHtml .= '<td class="text-dark py-3" style="font-size: 13px;">'.$row['nama'].'</td>';
                $tableHtml .= '<td class="text-muted text-right py-3" style="font-size: 13px;">Rp '.number_format($row['booking'], 0, ',', '.').'</td>';
                $tableHtml .= '<td class="text-muted text-right py-3" style="font-size: 13px;">Rp '.number_format($row['fnb'], 0, ',', '.').'</td>';
                $tableHtml .= '<td class="text-muted text-right py-3" style="font-size: 13px;">Rp '.number_format($row['refund'], 0, ',', '.').'</td>';
                $tableHtml .= '<td class="text-dark font-weight-bold text-right py-3" style="font-size: 13px;">Rp '.number_format($row['total'], 0, ',', '.').'</td>';
                $tableHtml .= '</tr>';
            }

            return response()->json([
                'success'  => true,
                'labels'   => $chartLabels,
                'datasets' => $chartDatasets,
                'html'     => $tableHtml,
                'total'    => count($tableData)
            ]);
        }

        return view('superadmin.penjualan-kasir.index', compact(
            'chartLabels', 'chartDatasets', 'tableData', 'kasirOptions'
        ));
    }
}