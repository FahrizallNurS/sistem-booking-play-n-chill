<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TrTransaksi;
use App\Models\User;
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

        // Query transaksi (SEMUA TRANSAKSI untuk superadmin)
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

        // Summary - DITAMBAH Total Admin & Pelanggan
        $totalBooking    = $transaksis->count();
        $totalSelesai    = $transaksis->where('status_sewa', 'selesai')->count();
        $totalDibatalkan = $transaksis->where('status_sewa', 'dibatalkan')->count();
        $totalPendapatan = $transaksis->whereIn('status_sewa', ['selesai'])->sum('total_harga');
        
        // TAMBAHAN UNTUK SUPERADMIN
        $totalPelanggan  = User::where('role', 'pelanggan')->count();
        $totalAdmin      = User::whereIn('role', ['admin', 'superadmin'])->count();

        return view('superadmin.laporan.index', compact(
            'transaksis',
            'totalBooking',
            'totalSelesai',
            'totalDibatalkan',
            'totalPendapatan',
            'totalPelanggan',
            'totalAdmin',
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

        // Query transaksi (SEMUA TRANSAKSI)
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
        $totalPendapatan = $transaksis->whereIn('status_sewa', ['selesai'])->sum('total_harga');
        
        // TAMBAHAN UNTUK SUPERADMIN
        $totalPelanggan  = User::where('role', 'pelanggan')->count();
        $totalAdmin      = User::whereIn('role', ['admin', 'superadmin'])->count();

        // Data untuk PDF
        $data = [
            'transaksis'       => $transaksis,
            'totalBooking'     => $totalBooking,
            'totalSelesai'     => $totalSelesai,
            'totalDibatalkan'  => $totalDibatalkan,
            'totalPendapatan'  => $totalPendapatan,
            'totalPelanggan'   => $totalPelanggan,
            'totalAdmin'       => $totalAdmin,
            'periode'          => $periode,
            'start'            => $start,
            'end'              => $end,
            'tanggalCetak'     => now()->translatedFormat('d F Y H:i'),
            'admin'            => auth()->user()->nama_pengguna,
        ];

        // Generate PDF
        $pdf = Pdf::loadView('superadmin.laporan.pdf', $data)
                  ->setPaper('a4', 'portrait');

        // Nama file
        $filename = 'Laporan_Superadmin_' . $start->format('Y-m-d') . '.pdf';

        // Download PDF
        return $pdf->download($filename);
    }
}