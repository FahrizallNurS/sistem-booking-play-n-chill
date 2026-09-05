<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TrTransaksi;
use App\Models\TrPos;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    // ============================================================
    // Rentang tanggal berdasarkan periode (dipakai index & exportPdf,
    // diekstrak biar gak duplikat)
    // ============================================================
    private function getDateRange(Request $request): array
    {
        $periode = $request->input('periode', 'harian');

        switch ($periode) {
            case 'mingguan':
                $minggu = $request->input('minggu', now()->format('Y-\WW'));
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

        return [$start, $end, $periode];
    }

    // ============================================================
    // Ambil & susun semua data laporan (dipakai index & exportPdf)
    // ============================================================
    private function getReportData(Request $request): array
    {
        [$start, $end, $periode] = $this->getDateRange($request);

        $jenisTransaksi = $request->input('jenis_transaksi', 'semua'); // booking | fnb | semua
        $sumber         = $request->input('sumber', '');               // Kasir | Online

        // --- Booking: filter tanggal pakai waktu_mulai ---
        $bookingQuery = TrTransaksi::with(['pengguna', 'admin', 'penetapanHarga.ruangan', 'penetapanHarga.paket'])
            ->whereBetween('waktu_mulai', [$start, $end])
            // Ubah kolom ke huruf kecil (lowercase) lalu cocokkan dengan 'selesai'
            ->whereRaw('LOWER(status_sewa) = ?', ['selesai']); 

        if ($sumber) {
            $bookingQuery->where('sumber_booking', $sumber);
        }

        $bookingData = $bookingQuery->latest('waktu_mulai')->get()
            ->each(fn ($item) => $item->jenis_laporan = 'Booking');

        // --- F&B: filter tanggal pakai created_at (tr_pos tidak punya waktu_mulai) ---
        $fnbQuery = TrPos::with(['pengguna', 'admin', 'transaksi.penetapanHarga.ruangan', 'details.produk'])
            ->whereBetween('created_at', [$start, $end])
            // Ubah kolom ke huruf kecil (lowercase) lalu cocokkan dengan 'selesai'
            ->whereRaw('LOWER(status_pesanan) = ?', ['selesai']);

        if ($sumber) {
            $fnbQuery->where('sumber_pesanan', $sumber);
        }

        $fnbData = $fnbQuery->latest('created_at')->get()
            ->each(fn ($item) => $item->jenis_laporan = 'F&B');

        // --- Data untuk tabel: sesuai filter jenis_transaksi ---
        $transaksis = match ($jenisTransaksi) {
            'booking' => $bookingData,
            'fnb'     => $fnbData,
            default   => $bookingData->concat($fnbData)
                ->sortByDesc(fn ($t) => $t->jenis_laporan === 'Booking' ? $t->waktu_mulai : $t->created_at)
                ->values(),
        };

        // --- Summary per jenis (dihitung dari data mentah, bukan dari $transaksis,
        //     supaya gak ikut kepotong walau tabel lagi difilter satu jenis) ---
        $totalBooking      = $bookingData->count();
        $totalFnb          = $fnbData->count();
        $bookingSelesai    = $bookingData->where('status_sewa', 'selesai')->count();
        $fnbSelesai        = $fnbData->where('status_pesanan', 'Selesai')->count();
        $bookingDibatalkan = $bookingData->where('status_sewa', 'dibatalkan')->count();
        $fnbDibatalkan     = $fnbData->where('status_pesanan', 'Dibatalkan')->count();

        // Pendapatan booking: HANYA yang statusnya benar-benar 'selesai'
        $pendapatanBooking = $bookingData
            ->where('status_sewa', 'selesai')
            ->sum('total_harga');

        $pendapatanFnb = $fnbData
            ->whereIn('status_pembayaran', ['sudah-bayar', 'lunas'])
            ->sum('total_pos');

        $totalSelesai    = $bookingSelesai + $fnbSelesai;
        $totalDibatalkan = $bookingDibatalkan + $fnbDibatalkan;
        $totalPendapatan = $pendapatanBooking + $pendapatanFnb;

        return [
            'transaksis'        => $transaksis,
            'jenisTransaksi'    => $jenisTransaksi,
            'sumber'            => $sumber,
            'totalBooking'      => $totalBooking,
            'totalFnb'          => $totalFnb,
            'bookingSelesai'    => $bookingSelesai,
            'fnbSelesai'        => $fnbSelesai,
            'bookingDibatalkan' => $bookingDibatalkan,
            'fnbDibatalkan'     => $fnbDibatalkan,
            'pendapatanBooking' => $pendapatanBooking,
            'pendapatanFnb'     => $pendapatanFnb,
            'totalSelesai'      => $totalSelesai,
            'totalDibatalkan'   => $totalDibatalkan,
            'totalPendapatan'   => $totalPendapatan,
            'periode'           => $periode,
            'start'             => $start,
            'end'               => $end,
        ];
    }

    public function index(Request $request)
    {
        $data = $this->getReportData($request);
        return view('admin.laporan.index', $data);
    }

    public function exportPdf(Request $request)
    {
        $data = $this->getReportData($request);
        $data['tanggalCetak'] = now()->translatedFormat('d F Y H:i');
        $data['admin']        = auth()->user()->nama_pengguna;

        $pdf      = Pdf::loadView('admin.laporan.pdf', $data)->setPaper('a4', 'portrait');
        $filename = 'Laporan_Transaksi_' . $data['start']->format('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }
}