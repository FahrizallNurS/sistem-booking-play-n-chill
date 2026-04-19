<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrTransaksi extends Model
{
    protected $table = 'tr_transaksi';
    protected $primaryKey = 'id_transaksi';
    protected $fillable = [
        'id_penetapan_harga', 'id_pengguna', 'kode_sewa',
        'waktu_mulai', 'waktu_selesai', 'total_harga',
        'opsi_pembayaran', 'jumlah_dp', 'status_sewa',
        'status_pembayaran', 'catatan_pembayaran', 'sisa_bayar'
    ];

    public function penetapanHarga()
    {
        return $this->belongsTo(PenetapanHarga::class, 'id_penetapan_harga', 'id_penetapan_harga');
    }

    public function pengguna()
    {
        return $this->belongsTo(User::class, 'id_pengguna', 'id');
    }

    // Helper — akses ruangan via penetapan_harga
    public function getRuanganAttribute()
    {
        return $this->penetapanHarga?->ruangan;
    }

    // Helper — akses paket via penetapan_harga
    public function getPaketAttribute()
    {
        return $this->penetapanHarga?->paket;
    }
}