<?php

namespace App\Services;

use App\Models\TrPos;
use App\Models\TrPosDetail;
use App\Models\MsProduk;
use App\Models\MsPengaturan;
use App\Models\User;
use App\Services\Concerns\GeneratesStrukPdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class FbService
{
    use GeneratesStrukPdf;

    /**
     * Buat pesanan F&B mandiri (tanpa booking), langsung final (potong stock +
     * generate PDF) dalam satu kali jalan -- beda dari flow booking yang
     * punya 2 tahap (create lalu finalisasi terpisah), karena F&B mandiri
     * cuma punya SATU entry point, jadi gak butuh idempotency guard di server.
     * Guard double-submit cukup di sisi client (disable tombol saat proses).
     *
     * @param  array $data  ['nama_pelanggan', 'no_telp'?, 'catatan'?,
     *                       'metode_pembayaran', 'items' => [['id_produk','jumlah'],...],
     *                       'dicetak_oleh']
     * @throws ValidationException jika item tidak valid / stock tidak cukup
     */
    public function createPos(array $data): TrPos
    {
        return DB::transaction(function () use ($data) {

            $noTelp = trim($data['no_telp'] ?? '');

            if ($noTelp !== '') {
                $user = User::firstOrCreate(
                    ['no_hp' => $noTelp],
                    [
                        'nama_pengguna' => $data['nama_pelanggan'],
                        'role'          => 'pelanggan',
                    ]
                );
            } else {
                $user = User::create([
                    'nama_pengguna' => $data['nama_pelanggan'],
                    'no_hp'         => null,
                    'role'          => 'pelanggan',
                ]);
            }

            // Harga & stock TIDAK dipercaya dari client, selalu diambil ulang
            // dari ms_produk -- pola sama persis dengan finalisasiStruk() di
            // BookingService.
            $detailRows = [];
            $total = 0;

            foreach ($data['items'] as $item) {
                $jumlah = (int) ($item['jumlah'] ?? 0);
                if ($jumlah <= 0) {
                    continue;
                }

                $produk = MsProduk::where('id_produk', $item['id_produk'])
                    ->lockForUpdate()
                    ->first();

                if (!$produk) {
                    throw ValidationException::withMessages([
                        'items' => 'Produk F&B tidak ditemukan.',
                    ]);
                }

                if ($produk->stock < $jumlah) {
                    throw ValidationException::withMessages([
                        'items' => "Stock '{$produk->nama_produk}' tidak cukup (sisa {$produk->stock}).",
                    ]);
                }

                $hargaSatuan = (int) $produk->harga_jual;
                $subtotal    = $hargaSatuan * $jumlah;
                $total      += $subtotal;

                $detailRows[] = [
                    'produk'       => $produk,
                    'jumlah'       => $jumlah,
                    'harga_satuan' => $hargaSatuan,
                    'subtotal'     => $subtotal,
                ];
            }

            if (empty($detailRows)) {
                throw ValidationException::withMessages([
                    'items' => 'Tidak ada item valid pada pesanan.',
                ]);
            }

            $pos = TrPos::create([
                'id_transaksi'      => null, // F&B mandiri, gak nempel booking
                'id_pengguna'       => $user->id_pengguna,
                'total_pos'         => $total,
                'sumber_pesanan'    => 'Kasir',
                'status_pesanan'    => 'Menunggu',
                'status_pembayaran' => 'lunas',
                'metode_pembayaran' => $data['metode_pembayaran'],
                'catatan'           => $data['catatan'] ?? null,
            ]);

            foreach ($detailRows as $row) {
                TrPosDetail::create([
                    'id_pos'       => $pos->id_pos,
                    'id_produk'    => $row['produk']->id_produk,
                    'jumlah'       => $row['jumlah'],
                    'harga_satuan' => $row['harga_satuan'],
                    'subtotal'     => $row['subtotal'],
                ]);

                $row['produk']->decrement('stock', $row['jumlah']);
            }

            $this->generateStrukPos($pos, $user, $detailRows, $total, $data['metode_pembayaran'], $data['dicetak_oleh']);

            return $pos->fresh();
        });
    }

    private function generateStrukPos(
        TrPos $pos,
        User $user,
        array $detailRows,
        int $total,
        string $metodePembayaran,
        string $dicetakOleh
    ): void {
        $pengaturan = MsPengaturan::current();

        $items = array_map(function ($row) {
            return [
                'nama'     => $row['produk']->nama_produk,
                'sub'      => null,
                'qty'      => $row['jumlah'],
                'harga'    => $row['harga_satuan'],
                'subtotal' => $row['subtotal'],
            ];
        }, $detailRows);

        $data = [
            'jumlahDp'         => 0, // F&B mandiri gak ada konsep DP
            'totalTagihan'     => $total,
            'totalBayar'       => $total,
            'pengaturan'       => $pengaturan,
            'kodeSewa'         => $pos->kode_pos, // view struk generic, field-nya dipakai bareng utk kode_pos
            'wifiSsid'         => $pengaturan->wifi_ssid,
            'wifiPassword'     => $pengaturan->wifi_password,
            'waktu'            => now()->format('d/m/Y H:i'),
            'kasir'            => $dicetakOleh,
            'customer'         => $user->nama_pengguna,
            'items'            => $items,
            'subTotal'         => $total,
            'metodePembayaran' => $metodePembayaran,
            'catatan'          => $pos->catatan,
            'waktuPembayaran'  => now()->format('d/m/Y H:i'),
            'dicetakOleh'      => $dicetakOleh,
        ];

        $this->simpanStrukPdf('admin.bookings.struk-pdf', $data, $pos->kode_pos);
    }
}