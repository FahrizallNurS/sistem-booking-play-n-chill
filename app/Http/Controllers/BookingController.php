<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TrTransaksi;
use App\Models\MsRuangan;
use App\Models\MsPaket;
use App\Models\PenetapanHarga;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use App\Models\TrPos;
use App\Models\TrPosDetail;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $this->cancelExpiredBookings();
        $this->completeExpiredBookings();

        $tipe    = $request->input('tipe', 'reguler');
        $tanggal = $request->input('tanggal', now()->format('Y-m-d'));

        $kategoriMap = [
            'reguler'       => 'REGULAR',
            'regular'       => 'REGULAR',
            'private-room'  => 'PRIVATE-ROOM',
            'private_room'  => 'PRIVATE-ROOM',
        ];

        $kategori = $kategoriMap[strtolower($tipe)] ?? 'REGULAR';

        $rooms = MsRuangan::where('kategori', $kategori)
            ->where('is_active', 1)
            ->get();

        // 3 opsi tanggal cepat: hari ini, besok, lusa — dihitung ulang tiap request
        $quickDates = collect(range(0, 2))->map(function ($i) {
            $date = now()->addDays($i);
            return [
                'value'   => $date->format('Y-m-d'),
                'label'   => match ($i) {
                    0       => 'Hari Ini',
                    1       => 'Besok',
                    default => $date->translatedFormat('l'), // nama hari, mis. "Sabtu"
                },
                'tanggal_display' => $date->translatedFormat('d M'),
                'nama_hari'       => $date->translatedFormat('l'),
            ];
        });

        return view('pelanggan.booking', compact('rooms', 'tipe', 'tanggal', 'quickDates'));
    }

    public function paket(Request $request)
    {
        $roomId  = $request->input('room');
        $tipe    = $request->input('tipe', 'reguler');
        $tanggal = $request->input('tanggal');

        if (empty($tanggal)) {
            return redirect()->route('booking')
                ->with('error', 'Silakan pilih tanggal terlebih dahulu.');
        }

        $room     = MsRuangan::findOrFail($roomId);
        $tipeHari = PenetapanHarga::tipeHariFromDate($tanggal);

        $adaPaketSamaSekali = PenetapanHarga::where('id_ruangan', $roomId)
            ->whereHas('paket', function ($query) {
                $query->where('is_active', 1);
            })
            ->exists();

        $penetapanHarga = PenetapanHarga::with('paket.subKategori')
            ->where('id_ruangan', $roomId)
            ->where('tipe_hari', $tipeHari)
            ->whereHas('paket', function ($query) {
                $query->where('is_active', 1);
            })
            ->get()
            ->groupBy('id_paket');

        return view('pelanggan.booking-paket', compact(
            'roomId', 'tipe', 'room', 'penetapanHarga', 'tanggal', 'tipeHari', 'adaPaketSamaSekali'
        ));
    }

    public function form(Request $request)
    {
        $roomId  = $request->input('room');
        $tipe    = $request->input('tipe');
        $paketId = $request->input('paket');
        $tanggal = $request->input('tanggal');

        if (empty($tanggal)) {
            return redirect()->route('booking')
                ->with('error', 'Silakan pilih tanggal terlebih dahulu.');
        }

        $room  = MsRuangan::findOrFail($roomId);
        $paket = MsPaket::where('id_paket', $paketId)
            ->where('is_active', 1)
            ->firstOrFail();

        $penetapanHarga = PenetapanHarga::where('id_ruangan', $roomId)
            ->where('id_paket', $paketId)
            ->get();

        $jamTerpakai = $this->calculateOccupiedSlots($roomId, $tanggal);

        return view('pelanggan.booking-form', compact(
            'room', 'tipe', 'paket', 'penetapanHarga', 'jamTerpakai', 'tanggal'
        ));
    }

    public function getJamTerpakai(Request $request): JsonResponse
    {
        $roomId  = $request->query('room');
        $tanggal = $request->query('tanggal');
        $terpakai = $this->calculateOccupiedSlots($roomId, $tanggal);
        return response()->json(['terpakai' => $terpakai]);
    }

    private function calculateOccupiedSlots($roomId, $tanggal)
    {
        $allSlots = [
            '10.00','10.30','11.00','11.30','12.00','12.30','13.00','13.30',
            '14.00','14.30','15.00','15.30','16.00','16.30','17.00','17.30',
            '18.00','18.30','19.00','19.30','20.00','20.30','21.00','21.30',
            '22.00','22.30','23.00','23.30'
        ];

        $occupiedSlots = [];

        $bookings = TrTransaksi::whereHas('penetapanHarga', function ($q) use ($roomId) {
                $q->where('id_ruangan', $roomId);
            })
            ->whereDate('waktu_mulai', $tanggal)
            ->whereIn('status_sewa', ['ditahan', 'dikonfirmasi'])
            ->get(['waktu_mulai', 'waktu_selesai']);

        foreach ($bookings as $booking) {
            $start = Carbon::parse($booking->waktu_mulai);
            $end   = Carbon::parse($booking->waktu_selesai);

            foreach ($allSlots as $slot) {
                $currentSlot = Carbon::parse($tanggal . ' ' . str_replace('.', ':', $slot), 'Asia/Jakarta');
                $start = Carbon::parse($booking->waktu_mulai)->setTimezone('Asia/Jakarta');
                $end   = Carbon::parse($booking->waktu_selesai)->setTimezone('Asia/Jakarta');
                if ($currentSlot >= $start && $currentSlot < $end) {
                    $occupiedSlots[] = $slot;
                }
            }
        }

        return array_values(array_unique($occupiedSlots));
    }

    public function store(Request $request)
    {

        if (empty(Auth::user()->no_hp)) {
        $request->merge(['no_hp_required' => true]);
        }

        $request->validate([
        'id_penetapan_harga' => 'required|exists:penetapan_harga,id_penetapan_harga',
        'tanggal' => 'required|date|after_or_equal:today',
        'waktu_mulai' => ['required', 'regex:/^([01]?[0-9]|2[0-3])[.:][0-5][0-9]$/'],
        'opsi_pembayaran' => 'required|in:full,dp',

        'jumlah_dp' => [
            'required_if:opsi_pembayaran,dp',
            'nullable',
            function ($attribute, $value, $fail) use ($request) {

                if ($request->opsi_pembayaran === 'dp') {

                    $dp = (int) str_replace('.', '', $value);

                    $ph = PenetapanHarga::find($request->id_penetapan_harga);

                    if ($dp <= 0) {
                        $fail('Jumlah DP harus lebih dari 0.');
                    }

                    if ($ph && $dp >= $ph->harga) {
                        $fail('Jumlah DP tidak boleh melebihi atau sama dengan total harga.');
                    }
                }
            }
        ]
        ], [
            'no_hp.required' => 'Nomor telepon wajib diisi.',
            'no_hp.regex' => 'Format nomor telepon tidak valid.',
        ]);

        if (empty(Auth::user()->no_hp) && $request->filled('no_hp')) {
        Auth::user()->update([
            'no_hp' => $request->no_hp
        ]);
         }

        return DB::transaction(function () use ($request) {
            $ph = PenetapanHarga::findOrFail($request->id_penetapan_harga);

            $jamInput     = str_replace('.', ':', $request->waktu_mulai);
            $waktuMulai   = Carbon::parse($request->tanggal . ' ' . $jamInput);
            $waktuSelesai = $waktuMulai->copy()->addHours($ph->durasi_jam);

            $konflik = TrTransaksi::whereHas('penetapanHarga', function ($q) use ($ph) {
                    $q->where('id_ruangan', $ph->id_ruangan);
                })
                ->whereIn('status_sewa', ['ditahan', 'dikonfirmasi'])
                ->where(function ($query) use ($waktuMulai, $waktuSelesai) {
                    $query->where('waktu_mulai', '<', $waktuSelesai)
                          ->where('waktu_selesai', '>', $waktuMulai);
                })
                ->lockForUpdate()
                ->exists();

            if ($konflik) {
                return back()->withInput()->withErrors([
                    'waktu_mulai' => 'Waduh, jam segini ruangannya udah ada yang nempetin lads. Coba geser jamnya dikit!'
                ]);
            }

            $kode = TrTransaksi::generateKodeSewa();

            $jumlahDp  = null;
            $sisaBayar = 0;

            if ($request->opsi_pembayaran === 'dp') {
                $jumlahDp  = (int) str_replace('.', '', $request->jumlah_dp);
                $sisaBayar = $ph->harga - $jumlahDp;
            }

            $transaksi = TrTransaksi::create([
                'id_penetapan_harga' => $ph->id_penetapan_harga,
                'id_pengguna'        => Auth::user()->id_pengguna,
                'kode_sewa'          => $kode,
                'waktu_mulai'        => $waktuMulai,
                'waktu_selesai'      => $waktuSelesai,
                'total_harga'        => $ph->harga,
                'opsi_pembayaran'    => $request->opsi_pembayaran,
                'jumlah_dp'          => $jumlahDp,
                'status_sewa'        => 'ditahan',
                'status_pembayaran'  => $request->opsi_pembayaran === 'full' ? 'menunggu' : 'dp',
                'sisa_bayar'         => $sisaBayar,
                'sumber_booking'      => 'Online',
            ]);

            $keranjangFb = json_decode($request->keranjang_fb, true);
            
            if (is_array($keranjangFb) && count($keranjangFb) > 0) {
                $totalFb = 0;
                foreach ($keranjangFb as $item) {
                    $totalFb += ($item['price'] * $item['qty']);
                }

                $pos = TrPos::create([
                    'id_transaksi'   => $transaksi->id_transaksi,
                    'id_pengguna'    => Auth::user()->id_pengguna, 
                    'sumber_pesanan' => 'Online',                  
                    'total_pos'      => $totalFb,
                    'status_pesanan' => 'Menunggu', 
                ]);

               foreach ($keranjangFb as $item) {
                    TrPosDetail::create([
                        'id_pos'       => $pos->id_pos,
                        'id_produk'    => $item['id'],
                        'jumlah'       => $item['qty'],
                        'harga_satuan' => $item['price'],
                        'subtotal'     => $item['price'] * $item['qty'],
                    ]);

                    $produk = \App\Models\MsProduk::find($item['id']);
                    if ($produk) {
                        $produk->decrement('stock', $item['qty']);
                    }
                }
            }

            return redirect()->route('booking.payment.show', $transaksi->id_transaksi);
        });
    }

    public function status()
    {
        $this->cancelExpiredBookings();
        $this->completeExpiredBookings();
        $bookings = TrTransaksi::with(['penetapanHarga.ruangan', 'penetapanHarga.paket'])
            ->where('id_pengguna', Auth::user()->id_pengguna) 
            ->latest()
            ->get();

        return view('pelanggan.status-booking', compact('bookings'));
    }

    public function processToPayment(Request $request)
    {
        session(['booking_data' => $request->all()]);
        return redirect()->route('booking.payment.show');
    }

   public function showPayment($id)
    {
        $this->cancelExpiredBookings();
        $this->completeExpiredBookings();
        
        $transaksi = TrTransaksi::with(['penetapanHarga.ruangan', 'penetapanHarga.paket'])
            ->where('id_transaksi', $id)
            ->where('id_pengguna', Auth::user()->id_pengguna)
            ->firstOrFail();

        $pos = TrPos::where('id_transaksi', $transaksi->id_transaksi)->first();
        $keranjangFb = [];
        
        if ($pos) {
            $details = TrPosDetail::where('id_pos', $pos->id_pos)->get();
            foreach ($details as $d) {
                $produk = DB::table('ms_produk')->where('id_produk', $d->id_produk)->first();
                $keranjangFb[] = [
                    'name'  => $produk ? $produk->nama_produk : 'Produk',
                    'qty'   => $d->jumlah,
                    'price' => $d->harga_satuan
                ];
            }
        }
        return view('pelanggan.payment', compact('transaksi', 'keranjangFb'));
    }

    // --- FUNGSI BARU UNTUK SKENARIO HANYA PESAN F&B (MANDIRI) ---
    public function checkoutFb(Request $request)
    {
        // 🔹 TAMBAHKAN PENGECEKAN LOGIN DI SINI 🔹
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu untuk melanjutkan pesanan.');
        }

        $keranjangFb = json_decode($request->keranjang_fb, true);

        if (empty($keranjangFb)) {
            return redirect()->back();
        }

        $totalFb = 0;
        foreach ($keranjangFb as $item) {
            $totalFb += ($item['price'] * $item['qty']);
        }

        $pos = TrPos::create([
            'id_transaksi'   => null,
            'id_pengguna'    => Auth::user()->id_pengguna, // Karena sudah dicek di atas, ini pasti aman
            'total_pos'      => $totalFb,
            'status_pesanan' => 'Menunggu',
            'sumber_pesanan' => 'Online',
            'catatan'        => $request->catatan,
            'metode_pembayaran' => $request->metode_pembayaran,
        ]);

        foreach ($keranjangFb as $item) {
            TrPosDetail::create([
                'id_pos'       => $pos->id_pos,
                'id_produk'    => $item['id'],
                'jumlah'       => $item['qty'],
                'harga_satuan' => $item['price'],
                'subtotal'     => $item['price'] * $item['qty'],
            ]);

            // PENGURANGAN STOK OTOMATIS (yang baru saja kita tambahkan sebelumnya)
            $produk = \App\Models\MsProduk::find($item['id']);
            if ($produk) {
                $produk->decrement('stock', $item['qty']);
            }
        }
        
        session(['metode_pembayaran_fb' => $request->metode_pembayaran]);

        return redirect()->route('fb.payment.show', $pos->id_pos);
    }

    public function showPaymentFb($id)
    {
        $pos = TrPos::findOrFail($id);
        $details = TrPosDetail::where('id_pos', $id)->get();
        $this->cancelExpiredFbOrders();
        $keranjangFb = [];
        foreach ($details as $d) {
            $produk = DB::table('ms_produk')->where('id_produk', $d->id_produk)->first();
            $keranjangFb[] = [
                'name'  => $produk ? $produk->nama_produk : 'Produk',
                'qty'   => $d->jumlah,
                'price' => $d->harga_satuan
            ];
        }
        $metode = session('metode_pembayaran_fb', 'QRIS');

        return view('pelanggan.payment-fb', compact('pos', 'keranjangFb', 'metode'));
    }

  public function confirmPaymentFb($id)
    {
        $this->cancelExpiredFbOrders(); 

        $pos = TrPos::findOrFail($id);

        if ($pos->status_pesanan === 'Dibatalkan' || $pos->status_pembayaran === 'kadaluarsa') {
            return redirect()->route('profile')->with('error', 'Pembayaran gagal. Waktu pembayaran F&B Anda telah habis.');
        }

        $pos->update([
            'status_pembayaran' => 'sudah-bayar'
        ]);

        $noWa = "6285735329227"; 
        $noPesanan = "FNB" . TrTransaksi::PREFIX_KODE_SEWA . "-" . str_pad($pos->id_pos, 3, '0', STR_PAD_LEFT);
        $pesan = "Halo Admin Play N Chill, saya ingin konfirmasi pembayaran QRIS untuk F&B dengan Nomor Pesanan: *{$noPesanan}*.\n\nBerikut saya lampirkan bukti transfernya.";
        
        $waUrl = "https://wa.me/{$noWa}?text=" . urlencode($pesan);

        return redirect()->away($waUrl);
    }

    private function cancelExpiredBookings()
    {
        TrTransaksi::where('status_sewa', 'ditahan')
            ->where('sumber_booking', 'Online')
            ->where('created_at', '<', now()->subMinutes(30))
            ->update([
                'status_sewa'        => 'dibatalkan',
                'catatan_pembayaran' => 'Waktu pembayaran habis!',
            ]);
    }

    private function completeExpiredBookings()
    {
        TrTransaksi::where('status_sewa', 'dikonfirmasi')
            ->where('waktu_selesai', '<', now())
            ->update([
                'status_sewa' => 'selesai',
            ]);
    }

    private function cancelExpiredFbOrders()
    {
        TrPos::where('status_pesanan', 'Menunggu')
            ->where('created_at', '<', now()->subMinutes(15))
            ->update([
                'status_pesanan'    => 'Dibatalkan',
                'status_pembayaran' => 'kadaluarsa',
            ]);
    }

    
}