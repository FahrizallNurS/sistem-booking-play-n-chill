<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TrTransaksi extends Model
{
    protected $table = 'tr_transaksi';
    protected $primaryKey = 'id_transaksi';

    // Prefix kode booking untuk cabang ini (statis, belum multi-cabang)
    const PREFIX_KODE_SEWA = 'PNC01';

    protected $fillable = [
    'id_penetapan_harga', 'id_pengguna', 'id_admin', 'kode_sewa',
    'waktu_mulai', 'waktu_selesai', 'total_harga',
    'opsi_pembayaran', 'metode_pembayaran', 'jumlah_dp', 'status_sewa',
    'status_pembayaran', 'catatan_pembayaran', 'sisa_bayar',
    'uang_diterima', 'kembalian',
    'sumber_booking', 'struk_created_at', 'nomor_nota',
    ];

    public static function generateKodeSewa(): string
    {
        do {
            $kode = self::PREFIX_KODE_SEWA . '-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4));
        } while (self::where('kode_sewa', $kode)->exists());

        return $kode;
    }

    public function penetapanHarga()
    {
        return $this->belongsTo(PenetapanHarga::class, 'id_penetapan_harga', 'id_penetapan_harga');
    }

    public function pengguna()
    {
        return $this->belongsTo(User::class, 'id_pengguna', 'id_pengguna');
    }

    public function getRuanganAttribute()
    {
        return $this->penetapanHarga?->ruangan;
    }

    public function getPaketAttribute()
    {
        return $this->penetapanHarga?->paket;
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'id_admin', 'id_pengguna');
    }
}