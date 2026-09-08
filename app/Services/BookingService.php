<?php

namespace App\Services;

use App\Models\PenetapanHarga;
use App\Models\TrTransaksi;
use App\Models\TrPos;
use App\Models\TrPosDetail;
use App\Models\MsProduk;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Models\MsPengaturan;
use App\Services\Concerns\GeneratesStrukPdf;

class BookingService
{
    use GeneratesStrukPdf;

    public function createManualBooking(array $data): TrTransaksi
    {
        return DB::transaction(function () use ($data) {

            $ph = PenetapanHarga::where('id_penetapan_harga', $data['id_penetapan_harga'])
                ->where('id_ruangan', $data['id_ruangan'])
                ->where('id_paket', $data['id_paket'])
                ->currentPrices()
                ->first();

            if (!$ph) {
                throw ValidationException::withMessages([
                    'id_penetapan_harga' => 'Kombinasi ruangan, paket, dan harga tidak valid. Silakan pilih ulang.',
                ]);
            }

            $ph->loadMissing('paket');

            $waktuMulai   = Carbon::createFromFormat('Y-m-d\TH:i', $data['waktu_mulai']);
            $waktuSelesai = $waktuMulai->copy()->addHours($ph->durasi_jam);
            $noTelp = trim($data['no_telp'] ?? '');

            if ($noTelp !== '') {
                $user = User::firstOrCreate(
                    ['no_hp' => $noTelp],
                    [
                        'nama_pengguna' => $data['nama_pelanggan'],
                        'email'         => $data['email'] ?? null,
                        'role'          => 'pelanggan',
                    ]
                );
            } else {
                $user = User::create([
                    'nama_pengguna' => $data['nama_pelanggan'],
                    'email'         => $data['email'] ?? null,
                    'no_hp'         => null,
                    'role'          => 'pelanggan',
                ]);
            }

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
                throw ValidationException::withMessages([
                    'waktu_mulai' => 'Ruangan sudah dibooking pada jam tersebut. Silakan pilih jam lain.',
                ]);
            }

            $kode = TrTransaksi::generateKodeSewa();

           $transaksi = TrTransaksi::create([
                'id_penetapan_harga' => $ph->id_penetapan_harga,
                'id_pengguna'        => $user->id_pengguna,
                'id_admin'           => $data['id_admin'] ?? null,   // <-- baru
                'kode_sewa'          => $kode,
                'waktu_mulai'        => $waktuMulai,
                'waktu_selesai'      => $waktuSelesai,
                'total_harga'        => $ph->harga,
                'opsi_pembayaran'    => 'full',
                'metode_pembayaran'  => $data['metode_pembayaran'],
                'jumlah_dp'          => null,
                'status_sewa'        => 'ditahan',
                'status_pembayaran'  => 'menunggu',
                'sisa_bayar'         => 0,
                'sumber_booking'     => 'Kasir',
                'catatan_pembayaran' => $data['catatan'] ?? null,
            ]);

            $this->finalisasiStruk(
                $transaksi,
                $data['items'] ?? [],
                $data['metode_pembayaran'],
                $data['dicetak_oleh'] ?? 'Admin',
                $data['id_admin'] ?? null,
                $data['uang_diterima'] ?? null

            );

            return $transaksi->fresh();
        });
    }

    public function finalisasiStruk(
        TrTransaksi $transaksi,
        array $itemsFnb,
        string $metodePembayaran,
        string $dicetakOleh,
        ?int $idAdminPencetak = null,
        ?int $uangDiterima = null
    ): string {
        return DB::transaction(function () use ($transaksi, $itemsFnb, $metodePembayaran, $dicetakOleh, $idAdminPencetak, $uangDiterima) {

            $transaksi = TrTransaksi::where('id_transaksi', $transaksi->id_transaksi)
                ->lockForUpdate()
                ->firstOrFail();

            $pdfRelativePath = 'assets/struk/' . $transaksi->kode_sewa . '.pdf';

            if (!empty($transaksi->struk_created_at)) {
                return $pdfRelativePath;
            }

            $waktuCetak = now();

            $transaksi->loadMissing('penetapanHarga.ruangan', 'penetapanHarga.paket', 'pengguna');
            $ph   = $transaksi->penetapanHarga;
            $user = $transaksi->pengguna;

            $fnbDetailRows = [];
            $totalFnb = 0;

            $trPos = TrPos::where('id_transaksi', $transaksi->id_transaksi)
                ->lockForUpdate()
                ->first();

            if ($trPos) {
                // ==========================================================
                // Kasus: TrPos sudah ada sebelumnya (dibuat lewat modal F&B
                // terpisah / AdminFbController). Detail baris sudah tersimpan
                // di tr_pos_detail sejak awal, di sini kita cuma perlu
                // membaca ulang untuk keperluan struk & total.
                // ==========================================================
                $existingDetails = TrPosDetail::where('id_pos', $trPos->id_pos)->get();

                foreach ($existingDetails as $d) {
                    $produk = MsProduk::find($d->id_produk);

                    $fnbDetailRows[] = [
                        'produk'       => $produk,
                        'nama_produk'  => $produk->nama_produk ?? 'Produk Dihapus',
                        'jumlah'       => $d->jumlah,
                        'harga_satuan' => $d->harga_satuan,
                        'subtotal'     => $d->subtotal,
                    ];
                    $totalFnb += $d->subtotal;
                }

                $trPos->update([
                    'status_pembayaran' => 'lunas',
                    'metode_pembayaran' => $metodePembayaran,
                    'id_admin'          => $idAdminPencetak ?? $trPos->id_admin,
                    'struk_created_at'  => $waktuCetak,
                ]);

            } elseif (!empty($itemsFnb)) {
                // ==========================================================
                // Kasus: F&B dikirim bareng saat booking dibuat (belum ada
                // TrPos). Di sini kita HARUS membuat baris tr_pos_detail
                // satu per satu, dan memotong stok produk — sebelumnya kedua
                // hal ini tidak pernah terjadi (bug utama).
                // ==========================================================
                foreach ($itemsFnb as $item) {
                    $jumlah = (int) ($item['jumlah'] ?? 0);
                    if ($jumlah <= 0) {
                        continue;
                    }

                    $produk = MsProduk::where('id_produk', $item['id_produk'])
                        ->lockForUpdate()
                        ->first();

                    if (!$produk) {
                        throw ValidationException::withMessages([
                            'items' => "Produk F&B tidak ditemukan.",
                        ]);
                    }

                    if ($produk->stock < $jumlah) {
                        throw ValidationException::withMessages([
                            'items' => "Stock '{$produk->nama_produk}' tidak cukup (sisa {$produk->stock}).",
                        ]);
                    }

                    $hargaSatuan = (int) $produk->harga_jual;
                    $subtotal    = $hargaSatuan * $jumlah;
                    $totalFnb   += $subtotal;

                    $fnbDetailRows[] = [
                        'produk'       => $produk,
                        'nama_produk'  => $produk->nama_produk,
                        'jumlah'       => $jumlah,
                        'harga_satuan' => $hargaSatuan,
                        'subtotal'     => $subtotal,
                    ];
                }

                if (!empty($fnbDetailRows)) {
                    $trPos = TrPos::create([
                        'id_transaksi'      => $transaksi->id_transaksi,
                        'id_pengguna'       => $transaksi->id_pengguna,
                        'id_admin'          => $idAdminPencetak ?? $transaksi->id_admin,
                        'total_pos'         => $totalFnb,
                        'sumber_pesanan'    => $transaksi->sumber_booking ?? 'Kasir',
                        'status_pesanan'    => 'Menunggu',
                        'status_pembayaran' => 'lunas',
                        'metode_pembayaran' => $metodePembayaran,
                        'catatan'           => null,
                        'struk_created_at'  => $waktuCetak,
                    ]);

                    // --- FIX: simpan tiap baris ke tr_pos_detail & potong stok ---
                    foreach ($fnbDetailRows as $row) {
                        TrPosDetail::create([
                            'id_pos'       => $trPos->id_pos,
                            'id_produk'    => $row['produk']->id_produk,
                            'jumlah'       => $row['jumlah'],
                            'harga_satuan' => $row['harga_satuan'],
                            'subtotal'     => $row['subtotal'],
                        ]);

                        $row['produk']->decrement('stock', $row['jumlah']);
                    }
                }
            }


            // --- Finalisasi status booking ---
            $totalTagihan = (int) $transaksi->total_harga + $totalFnb;
            
            // FIX BUG UANG DITERIMA KURANG:
            // Cek berapa uang yang SUDAH dibayarkan sebelumnya untuk booking ruangan ini
            $sudahDibayarBooking = 0;
            if ($transaksi->status_pembayaran === 'lunas') {
                $sudahDibayarBooking = (int) $transaksi->total_harga;
            } elseif ($transaksi->status_pembayaran === 'dp') {
                $sudahDibayarBooking = (int) $transaksi->jumlah_dp;
            }

            // Sisa yang benar-benar harus dibayar di kasir saat ini
            $sisaYangDibayar = $totalTagihan - $sudahDibayarBooking;

            // Kembalian cuma relevan buat TUNAI.
            $kembalian = null;
            if ($metodePembayaran === 'TUNAI') {
                if ($uangDiterima === null || $uangDiterima < $sisaYangDibayar) {
                    throw ValidationException::withMessages([
                        'uang_diterima' => 'Uang diterima kurang dari total tagihan yang harus dibayar (Minimal Rp ' . number_format($sisaYangDibayar, 0, ',', '.') . ').',
                    ]);
                }
                $kembalian = $uangDiterima - $sisaYangDibayar;
            } else {
                $uangDiterima = null;
            }

            $nomorNota = $this->generateNomorNota();
            $transaksi->update([
                'status_sewa'       => 'dikonfirmasi',
                'status_pembayaran' => 'lunas',
                'sisa_bayar'        => 0,
                'metode_pembayaran' => $metodePembayaran,
                'struk_created_at'  => $waktuCetak,
                'nomor_nota'        => $nomorNota,
            ]);

            // --- Generate PDF struk (gabungan item ruangan + F&B) ---
            $this->generateStrukPdf(
                $transaksi,
                $ph,
                $user,
                $dicetakOleh,
                $fnbDetailRows,
                $totalTagihan,
                $metodePembayaran,
                $sudahDibayarBooking, // <--- UBAH BAGIAN INI (Sebelumnya $jumlahDp)
                $uangDiterima,
                $kembalian
            );

            return $pdfRelativePath;

        });
    }

        private function generateStrukPdf(
        TrTransaksi $transaksi,
        PenetapanHarga $ph,
        User $user,
        string $dicetakOleh,
        array $fnbDetailRows = [],
        ?int $totalTagihanOverride = null,
        ?string $metodePembayaranOverride = null,
        int $jumlahDp = 0,
        ?int $uangDiterima = null,
        ?int $kembalian = null
    ): void {
        $pengaturan = MsPengaturan::current();

        $teksTipeHari = match($ph->tipe_hari) {
            'harian' => 'Senin-Kamis',
            'akhir_pekan' => 'Jumat-Minggu',
            'liburan' => 'Hari Libur',
            default => $ph->tipe_hari
        };

        $items = [
            [
                'nama'     => $ph->durasi_jam . ' Jam - ' . $ph->paket->nama_paket,
                'sub'      => $teksTipeHari,
                'qty'      => 1,
                'harga'    => $ph->harga,
                'subtotal' => $ph->harga,
            ],
        ];

        foreach ($fnbDetailRows as $row) {
            $items[] = [
                'nama'     => $row['produk']->nama_produk,
                'sub'      => null,
                'qty'      => $row['jumlah'],
                'harga'    => $row['harga_satuan'],
                'subtotal' => $row['subtotal'],
            ];
        }

        $subTotal     = array_sum(array_column($items, 'subtotal'));
        $totalTagihan = $totalTagihanOverride ?? $subTotal;
        $metodeBayar  = $metodePembayaranOverride ?? $transaksi->metode_pembayaran;
        $sisaYangDibayar = $totalTagihan - $jumlahDp;

        $data = [
            'jumlahDp'         => $jumlahDp,
            'totalTagihan'     => $totalTagihan,
            'totalBayar'       => $sisaYangDibayar,
            'uangDiterima'     => $uangDiterima,
            'kembalian'        => $kembalian,
            'pengaturan'       => $pengaturan,
            'kodeSewa'         => $transaksi->kode_sewa,
            'nomorNota'        => $transaksi->nomor_nota ?: $transaksi->kode_sewa,
            'wifiSsid'         => $pengaturan->wifi_ssid,
            'wifiPassword'     => $pengaturan->wifi_password,
            'waktu'            => now()->format('d/m/Y H:i'),
            'kasir'            => $dicetakOleh,
            'customer'         => $user->nama_pengguna,
            'items'            => $items,
            'subTotal'         => $subTotal,
            'metodePembayaran' => $metodeBayar,
            'catatan'          => $transaksi->catatan_pembayaran,
            'waktuPembayaran'  => now()->format('d/m/Y H:i'),
            'dicetakOleh'      => $dicetakOleh,
        ];

        $this->simpanStrukPdf('admin.bookings.struk-pdf', $data, $transaksi->kode_sewa);
    }

    public function ubahJadwal(TrTransaksi $booking, string $waktuMulaiBaru, ?int $idAdmin = null): TrTransaksi
    {
        return DB::transaction(function () use ($booking, $waktuMulaiBaru, $idAdmin) {

            $booking = TrTransaksi::where('id_transaksi', $booking->id_transaksi)
                ->lockForUpdate()
                ->firstOrFail();

            if (!in_array($booking->status_sewa, ['ditahan', 'dikonfirmasi'])) {
                throw ValidationException::withMessages([
                    'waktu_mulai' => 'Jadwal hanya bisa diubah untuk booking berstatus ditahan atau dikonfirmasi.',
                ]);
            }

            $booking->loadMissing('penetapanHarga');
            $ph = $booking->penetapanHarga;

            $waktuMulaiBaruCarbon = Carbon::createFromFormat('Y-m-d\TH:i', $waktuMulaiBaru);
            $waktuSelesaiBaru     = $waktuMulaiBaruCarbon->copy()->addHours($ph->durasi_jam);

            $konflik = TrTransaksi::whereHas('penetapanHarga', function ($q) use ($ph) {
                    $q->where('id_ruangan', $ph->id_ruangan);
                })
                ->where('id_transaksi', '!=', $booking->id_transaksi)
                ->whereIn('status_sewa', ['ditahan', 'dikonfirmasi'])
                ->where(function ($query) use ($waktuMulaiBaruCarbon, $waktuSelesaiBaru) {
                    $query->where('waktu_mulai', '<', $waktuSelesaiBaru)
                        ->where('waktu_selesai', '>', $waktuMulaiBaruCarbon);
                })
                ->lockForUpdate()
                ->exists();

            if ($konflik) {
                throw ValidationException::withMessages([
                    'waktu_mulai' => 'Ruangan sudah dibooking pada jam tersebut. Silakan pilih jam lain.',
                ]);
            }

            $booking->update([
                'waktu_mulai'   => $waktuMulaiBaruCarbon,
                'waktu_selesai' => $waktuSelesaiBaru,
                'id_admin'      => $idAdmin ?? $booking->id_admin,
            ]);

            return $booking->fresh();
        });
    }
}