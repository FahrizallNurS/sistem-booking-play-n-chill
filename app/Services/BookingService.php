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

    /**
     * Buat booking manual oleh kasir/admin.
     * Begitu tersimpan, langsung difinalisasi (dikonfirmasi + lunas + cetak struk),
     * lewat finalisasiStruk() supaya logic-nya sama persis dengan flow booking online.
     *
     * @param  array $data  boleh menyertakan 'items' => [['id_produk' => int, 'jumlah' => int], ...]
     *                      untuk pesanan F&B yang dibuat bersamaan dengan booking (opsional).
     * @throws ValidationException jika kombinasi tidak valid, ada bentrok jadwal,
     *                             atau stock salah satu item F&B tidak cukup
     *                             (dalam kasus ini seluruh booking ikut rollback, atomic).
     */
    public function createManualBooking(array $data): TrTransaksi
    {
        return DB::transaction(function () use ($data) {

            $ph = PenetapanHarga::where('id_penetapan_harga', $data['id_penetapan_harga'])
                ->where('id_ruangan', $data['id_ruangan'])
                ->where('id_paket', $data['id_paket'])
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

            do {
                $kode = 'PNC-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4));
            } while (TrTransaksi::where('kode_sewa', $kode)->exists());

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
                $data['dicetak_oleh'] ?? 'Admin'
            );

            return $transaksi->fresh();
        });
    }

    public function finalisasiStruk(
        TrTransaksi $transaksi,
        array $itemsFnb,
        string $metodePembayaran,
        string $dicetakOleh
    ): string {
        return DB::transaction(function () use ($transaksi, $itemsFnb, $metodePembayaran, $dicetakOleh) {

            $transaksi = TrTransaksi::where('id_transaksi', $transaksi->id_transaksi)
                ->lockForUpdate()
                ->firstOrFail();

            $pdfRelativePath = 'assets/struk/' . $transaksi->kode_sewa . '.pdf';

            // --- Idempotensi: sudah pernah dicetak, jangan ulangi proses ---
            if (!empty($transaksi->struk_created_at)) {
                return $pdfRelativePath;
            }

            $transaksi->loadMissing('penetapanHarga.ruangan', 'penetapanHarga.paket', 'pengguna');
            $ph   = $transaksi->penetapanHarga;
            $user = $transaksi->pengguna;

            // --- Item F&B: validasi stock & susun baris tr_pos + tr_pos_detail ---
            // Harga TIDAK dipercaya dari input, selalu diambil ulang dari ms_produk
            // supaya tidak bisa dimanipulasi dari sisi client.
            $fnbDetailRows = [];
            $totalFnb = 0;

            if (!empty($itemsFnb)) {
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
                        'jumlah'       => $jumlah,
                        'harga_satuan' => $hargaSatuan,
                        'subtotal'     => $subtotal,
                    ];
                }
            }

            $trPos = null;

            if (!empty($fnbDetailRows)) {
                $trPos = TrPos::create([
                    'id_transaksi'      => $transaksi->id_transaksi,
                    'id_pengguna'       => $transaksi->id_pengguna,
                    'id_admin'          => $transaksi->id_admin,   // <-- baru, warisan dari parent
                    'total_pos'         => $totalFnb,
                    'sumber_pesanan'    => $transaksi->sumber_booking ?? 'Kasir',
                    'status_pesanan'    => 'Menunggu',
                    'status_pembayaran' => 'lunas',
                    'metode_pembayaran' => $metodePembayaran,
                    'catatan'           => null,
                ]);

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

            // --- Finalisasi status booking ---
            $totalTagihan = (int) $transaksi->total_harga + $totalFnb;
            $jumlahDp = (int) ($transaksi->jumlah_dp ?? 0);
            $transaksi->update([
                'status_sewa'       => 'dikonfirmasi',
                'status_pembayaran' => 'lunas',
                'sisa_bayar'        => 0,
                'metode_pembayaran' => $metodePembayaran,
                'struk_created_at'  => now(),
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
                $jumlahDp
            );

            return $pdfRelativePath;
        });
    }

    /**
     * Susun data struk 58mm (khusus konteks booking: item ruangan + opsional
     * F&B), lalu delegasikan proses render+simpan filenya ke
     * GeneratesStrukPdf::simpanStrukPdf() supaya logic simpan-filenya tidak
     * terduplikasi dengan FbService.
     *
     * @param array $fnbDetailRows  hasil susunan dari finalisasiStruk(), boleh kosong
     */
    private function generateStrukPdf(
        TrTransaksi $transaksi,
        PenetapanHarga $ph,
        User $user,
        string $dicetakOleh,
        array $fnbDetailRows = [],
        ?int $totalTagihanOverride = null,
        ?string $metodePembayaranOverride = null,
        int $jumlahDp = 0
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
            'pengaturan'       => $pengaturan,
            'kodeSewa'         => $transaksi->kode_sewa,
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