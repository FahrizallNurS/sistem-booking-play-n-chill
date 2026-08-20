<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\TrTransaksi;
use App\Models\TrPos;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;

class SATinjauLaporanController extends Controller
{
    /**
     * Hitung rentang waktu berdasarkan periode (Harian/Mingguan/Bulanan).
     * Pola & default (bulanan) disamakan persis dengan SABerandaController::getPeriodRanges().
     */
    private function getPeriodRange(Request $request): array
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
            } catch (Exception $e) {
                $start = Carbon::now()->startOfMonth();
            }
            $end = $start->copy()->endOfMonth();
        } else {
            // Default: Harian
            $tanggal = $request->input('tanggal', now()->format('Y-m-d'));
            try {
                $start = Carbon::parse($tanggal)->startOfDay();
                $end   = Carbon::parse($tanggal)->endOfDay();
            } catch (Exception $e) {
                $start = Carbon::today()->startOfDay();
                $end   = Carbon::today()->endOfDay();
            }
        }

        return [$start, $end, $periode];
    }

    private function getQueryData(Request $request)
    {
        [$start, $end, $periode] = $this->getPeriodRange($request);

        $jenisTransaksi  = $request->input('jenis_transaksi', 'semua');
        $statusTransaksi = $request->input('status_transaksi', ''); // '' = Semua, 'dibatalkan' = Dibatalkan

        // ============================================================
        // 1. DATA MENTAH — cuma difilter rentang tanggal.
        //    Dipakai untuk SUMMARY CARDS, supaya angkanya tidak ikut
        //    terpotong walau tabel lagi difilter status/jenis transaksi.
        // ============================================================
        $bookingRaw = TrTransaksi::with(['pengguna', 'admin', 'penetapanHarga.ruangan', 'penetapanHarga.paket'])
            ->whereBetween('created_at', [$start, $end])
            ->latest('waktu_mulai')
            ->get()
            ->map(function ($item) {
                $item->jenis_laporan = 'Booking';
                // Kasir dari relasi admin (sebelumnya field ini tidak pernah di-set).
                $item->kasir = $item->admin->nama_pengguna ?? null;
                return $item;
            });

        $fnbRaw = TrPos::with(['pengguna', 'admin', 'transaksi.penetapanHarga.ruangan', 'details.produk'])
            ->whereBetween('created_at', [$start, $end])
            ->latest('created_at')
            ->get()
            ->map(function ($item) {
                $item->jenis_laporan = 'F&B';

                // --- Alias/virtual property, supaya blade yang sudah ada
                //     (table-transaksi, modal-fnb) bisa dipakai tanpa diubah ---
                $item->waktu_mulai    = $item->created_at;
                $item->kasir          = $item->admin->nama_pengguna ?? null;
                $item->kode_booking   = $item->transaksi->kode_sewa ?? '-';
                $item->ruangan        = $item->transaksi->penetapanHarga->ruangan->nama_ruangan ?? '-';
                $item->status_sewa    = strtolower($item->status_pesanan ?? '');
                $item->total_harga    = $item->total_pos;
                $item->sumber_booking = $item->sumber_pesanan;
                $item->items          = $item->details->map(function ($d) {
                    return (object) [
                        'produk'   => $d->produk->nama_produk ?? 'Produk dihapus',
                        'harga'    => $d->harga_satuan,
                        'jumlah'   => $d->jumlah,
                        'subtotal' => $d->subtotal,
                    ];
                });

                return $item;
            });

        // ============================================================
        // 2. DATA UNTUK TABEL — ikut filter status_transaksi & jenis_transaksi.
        // ============================================================
        // F&B "Selesai" dianggap valid kalau status_pesanan='Selesai' DAN status_pembayaran
        // sudah-bayar/lunas (defensif — 2 kolom ini independen di DB, bisa saja tidak sinkron).
        $isFnbSelesai = fn ($item) => $item->status_sewa === 'selesai'
            && in_array($item->status_pembayaran, ['sudah-bayar', 'lunas']);
        $isFnbDibatalkan = fn ($item) => $item->status_sewa === 'dibatalkan';

        if ($statusTransaksi === 'selesai') {
            $bookingFiltered = $bookingRaw->where('status_sewa', 'selesai')->values();
            $fnbFiltered     = $fnbRaw->filter($isFnbSelesai)->values();
        } elseif ($statusTransaksi === 'dibatalkan') {
            $bookingFiltered = $bookingRaw->where('status_sewa', 'dibatalkan')->values();
            $fnbFiltered     = $fnbRaw->filter($isFnbDibatalkan)->values();
        } else {
            // Semua = kombinasi Selesai + Dibatalkan (bukan tanpa filter status sama sekali).
            $bookingFiltered = $bookingRaw->whereIn('status_sewa', ['selesai', 'dibatalkan'])->values();
            $fnbFiltered     = $fnbRaw->filter(
                fn ($item) => $isFnbSelesai($item) || $isFnbDibatalkan($item)
            )->values();
        }

        $transaksis = match ($jenisTransaksi) {
            'booking' => $bookingFiltered,
            'fnb'     => $fnbFiltered,
            default   => $bookingFiltered->concat($fnbFiltered)->sortByDesc('waktu_mulai')->values(),
        };

        // ============================================================
        // 3. SUMMARY CARDS — selalu dari data MENTAH (bookingRaw/fnbRaw),
        //    supaya tidak kepengaruh filter status_transaksi/jenis_transaksi.
        // ============================================================
        $totalBooking      = $bookingRaw->count();
        $bookingSelesai    = $bookingRaw->where('status_sewa', 'selesai')->count();
        $bookingDibatalkan = $bookingRaw->where('status_sewa', 'dibatalkan')->count();
        $pendapatanBooking = (float) $bookingRaw->where('status_sewa', 'selesai')->sum('total_harga');

        $totalFnb      = $fnbRaw->count();
        $fnbSelesai    = $fnbRaw->filter(fn ($item) => $item->status_sewa === 'selesai')->count();
        $fnbDibatalkan = $fnbRaw->filter(fn ($item) => $item->status_sewa === 'dibatalkan')->count();
        $pendapatanFnb = (float) $fnbRaw->filter(
            fn ($item) => in_array($item->status_pembayaran, ['sudah-bayar', 'lunas'])
        )->sum('total_pos');

        $totalPelanggan = User::where('role', 'pelanggan')->count();
        $totalAdmin     = User::where('role', 'admin')->count();

        return compact(
            'transaksis',
            'totalBooking', 'bookingSelesai', 'bookingDibatalkan', 'pendapatanBooking',
            'totalFnb', 'fnbSelesai', 'fnbDibatalkan', 'pendapatanFnb',
            'totalPelanggan', 'totalAdmin',
            'periode', 'start', 'end', 'jenisTransaksi'
        );
    }

    private function paginateTransaksis($transaksis, Request $request)
    {
        $perPage = 10;
        $page    = (int) $request->input('page', 1);
        $slice   = $transaksis->slice(($page - 1) * $perPage, $perPage)->values();

        return new \Illuminate\Pagination\LengthAwarePaginator(
            $slice,
            $transaksis->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );
    }

    public function index(Request $request)
    {
        $data = $this->getQueryData($request);
        $data['transaksisPaged'] = $this->paginateTransaksis($data['transaksis'], $request);

        // Handler AJAX: klik link pagination tabel, tanpa reload halaman
        if ($request->ajax() && $request->has('page')) {
            $html = view('superadmin.laporan-sa.partials.table-rows', [
                'transaksis' => $data['transaksisPaged'],
            ])->render();

            $pagination = view('superadmin.laporan-sa.partials.pagination-links', [
                'transaksisPaged' => $data['transaksisPaged'],
            ])->render();

            return response()->json([
                'html'       => $html,
                'pagination' => $pagination,
                'info'       => "Menampilkan {$data['transaksisPaged']->firstItem()} hingga {$data['transaksisPaged']->lastItem()} dari {$data['transaksisPaged']->total()} entri",
            ]);
        }

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