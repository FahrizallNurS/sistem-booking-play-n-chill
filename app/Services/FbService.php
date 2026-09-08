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

                $uangDiterima = null;
                $kembalian    = null;

                if ($data['metode_pembayaran'] === 'TUNAI') {
                    $uangDiterima = (int) ($data['uang_diterima'] ?? 0);

                    if (empty($data['uang_diterima']) || $uangDiterima < $total) {
                        throw ValidationException::withMessages([
                            'uang_diterima' => 'Uang diterima kurang dari total tagihan.',
                        ]);
                    }

                    $kembalian = $uangDiterima - $total;
                }

                $pos = TrPos::create([
                    'id_transaksi'      => null, // F&B mandiri, gak nempel booking
                    'id_pengguna'       => $user->id_pengguna,
                    'id_admin'          => $data['id_admin'] ?? null,   // <-- baru
                    'total_pos'         => $total,
                    'sumber_pesanan'    => 'Kasir',
                    'status_pesanan'    => 'Menunggu',
                    'status_pembayaran' => 'lunas',
                    'metode_pembayaran' => $data['metode_pembayaran'],
                    'catatan'           => $data['catatan'] ?? null,
                    'nomor_nota'        => $this->generateNomorNota(),
                    'uang_diterima'     => $uangDiterima,
                    'kembalian'         => $kembalian,
                    'struk_created_at'  => now(), // PDF digenerate & disimpan di request ini juga
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

            $this->generateStrukPos($pos, $user, $detailRows, $total, $data['metode_pembayaran'], $data['dicetak_oleh'], $uangDiterima, $kembalian);

            return $pos->fresh();
        });
    }

     private function generateStrukPos(
        TrPos $pos,
        User $user,
        array $detailRows,
        int $total,
        string $metodePembayaran,
        string $dicetakOleh,
        ?int $uangDiterima = null,
        ?int $kembalian = null
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
            'uangDiterima'     => $uangDiterima,
            'kembalian'        => $kembalian,
            'pengaturan'       => $pengaturan,
            'kodeSewa'         => $pos->kode_pos, 
            'nomorNota'        => $pos->nomor_nota ?: $pos->kode_pos,
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