<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TrTransaksi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $periode       = $request->input('periode', 'harian');
        $statusBooking = $request->input('status_booking', '');
        $statusBayar   = $request->input('status_bayar', '');
        $jenisBayar    = $request->input('jenis_bayar', '');

        // Tentukan range tanggal berdasarkan periode
        switch ($periode) {
            case 'mingguan':
                $minggu = $request->input('minggu', now()->format('Y-W'));
                [$year, $week] = explode('-W', $minggu);
                $start = Carbon::now()->setISODate($year, $week)->startOfWeek();
                $end   = Carbon::now()->setISODate($year, $week)->endOfWeek();
                break;

            case 'bulanan':
                $bulan = $request->input('bulan', now()->format('Y-m'));
                $start = Carbon::parse($bulan . '-01')->startOfMonth();
                $end   = Carbon::parse($bulan . '-01')->endOfMonth();
                break;

            default: // harian
                $tanggal = $request->input('tanggal', now()->format('Y-m-d'));
                $start   = Carbon::parse($tanggal)->startOfDay();
                $end     = Carbon::parse($tanggal)->endOfDay();
                break;
        }

        $query = TrTransaksi::with(['pengguna', 'penetapanHarga.ruangan', 'penetapanHarga.paket'])
            ->whereBetween('waktu_mulai', [$start, $end]);

        if ($statusBooking) {
            $query->where('status_sewa', $statusBooking);
        }

        if ($statusBayar) {
            $query->where('status_pembayaran', $statusBayar);
        }

        if ($jenisBayar) {
            $query->where('opsi_pembayaran', $jenisBayar);
        }

        $transaksis = $query->latest('waktu_mulai')->get();

        // Summary
        $totalBooking    = $transaksis->count();
        $totalSelesai    = $transaksis->where('status_sewa', 'selesai')->count();
        $totalDibatalkan = $transaksis->where('status_sewa', 'dibatalkan')->count();
        $totalPendapatan = $transaksis->whereIn('status_sewa', ['selesai'])
                            ->sum('total_harga');

        return view('admin.laporan.index', compact(
            'transaksis',
            'totalBooking',
            'totalSelesai',
            'totalDibatalkan',
            'totalPendapatan',
            'periode',
            'start',
            'end'
        ));
    }

    public function exportPdf(Request $request)
    {
        $periode       = $request->input('periode', 'harian');
        $statusBooking = $request->input('status_booking', '');
        $statusBayar   = $request->input('status_bayar', '');
        $jenisBayar    = $request->input('jenis_bayar', '');

        // Tentukan range tanggal berdasarkan periode (SAMA seperti method index)
        switch ($periode) {
            case 'mingguan':
                $minggu = $request->input('minggu', now()->format('Y-W'));
                [$year, $week] = explode('-W', $minggu);
                $start = Carbon::now()->setISODate($year, $week)->startOfWeek();
                $end   = Carbon::now()->setISODate($year, $week)->endOfWeek();
                break;

            case 'bulanan':
                $bulan = $request->input('bulan', now()->format('Y-m'));
                $start = Carbon::parse($bulan . '-01')->startOfMonth();
                $end   = Carbon::parse($bulan . '-01')->endOfMonth();
                break;

            default: // harian
                $tanggal = $request->input('tanggal', now()->format('Y-m-d'));
                $start   = Carbon::parse($tanggal)->startOfDay();
                $end     = Carbon::parse($tanggal)->endOfDay();
                break;
        }

        $query = TrTransaksi::with(['pengguna', 'penetapanHarga.ruangan', 'penetapanHarga.paket'])
            ->whereBetween('waktu_mulai', [$start, $end]);

        if ($statusBooking) {
            $query->where('status_sewa', $statusBooking);
        }

        if ($statusBayar) {
            $query->where('status_pembayaran', $statusBayar);
        }

        if ($jenisBayar) {
            $query->where('opsi_pembayaran', $jenisBayar);
        }

        $transaksis = $query->latest('waktu_mulai')->get();

        // Summary
        $totalBooking    = $transaksis->count();
        $totalSelesai    = $transaksis->where('status_sewa', 'selesai')->count();
        $totalDibatalkan = $transaksis->where('status_sewa', 'dibatalkan')->count();
        $totalPendapatan = $transaksis->whereIn('status_sewa', ['selesai'])
                            ->sum('total_harga');

        // Data untuk PDF
        $data = [
            'transaksis'       => $transaksis,
            'totalBooking'     => $totalBooking,
            'totalSelesai'     => $totalSelesai,
            'totalDibatalkan'  => $totalDibatalkan,
            'totalPendapatan'  => $totalPendapatan,
            'periode'          => $periode,
            'start'            => $start,
            'end'              => $end,
            'tanggalCetak'     => now()->translatedFormat('d F Y H:i'),
            'admin'            => auth()->user()->nama_pengguna,
        ];

        // Generate PDF
        $pdf = Pdf::loadView('admin.laporan.pdf', $data)
                  ->setPaper('a4', 'portrait');

        // Nama file
        $filename = 'Laporan_Booking_' . $start->format('Y-m-d') . '.pdf';

        // Download PDF
        return $pdf->download($filename);
    }
}