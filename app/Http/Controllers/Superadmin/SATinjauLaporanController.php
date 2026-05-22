<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\TrTransaksi;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class SATinjauLaporanController extends Controller
{
    private function getQueryData(Request $request)
    {
            $periode       = $request->input('periode', 'harian');
            $statusBooking = $request->input('status_booking', '');
            $statusBayar   = $request->input('status_bayar', '');
            $jenisBayar    = $request->input('jenis_bayar', '');

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
                default:
                    $tanggal = $request->input('tanggal', now()->format('Y-m-d'));
                    $start   = Carbon::parse($tanggal)->startOfDay();
                    $end     = Carbon::parse($tanggal)->endOfDay();
                    break;
            }

            $query = TrTransaksi::with(['pengguna', 'penetapanHarga.ruangan', 'penetapanHarga.paket'])
                ->whereBetween('waktu_mulai', [$start, $end]);

            if ($statusBooking) $query->where('status_sewa', $statusBooking);
            if ($statusBayar)   $query->where('status_pembayaran', $statusBayar);
            if ($jenisBayar)    $query->where('opsi_pembayaran', $jenisBayar);

            $transaksis      = $query->latest('waktu_mulai')->get();
            $totalBooking    = $transaksis->count();
            $totalSelesai    = $transaksis->where('status_sewa', 'selesai')->count();
            $totalDibatalkan = $transaksis->where('status_sewa', 'dibatalkan')->count();
            $totalPendapatan = $transaksis->where('status_sewa', 'selesai')->sum('total_harga');
            $totalPelanggan = User::where('role', 'pelanggan')->count();
            $totalAdmin = User::where('role', 'admin')->count();

            return compact(
            'transaksis', 'totalBooking', 'totalSelesai',
            'totalDibatalkan', 'totalPendapatan',
            'totalPelanggan', 'totalAdmin',
            'periode', 'start', 'end'
        );


    }

    public function index(Request $request)
    {
        $data = $this->getQueryData($request);
        return view('superadmin.laporan-sa.index', $data);
    }

    public function exportPdf(Request $request)
    {
        $data = $this->getQueryData($request);
        $data['tanggalCetak'] = now()->translatedFormat('d F Y H:i');
        $data['admin']        = auth()->user()->nama_pengguna;

        $pdf      = Pdf::loadView('admin.laporan.pdf', $data)->setPaper('a4', 'portrait');
        $filename = 'Laporan_Booking_' . $data['start']->format('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }
}