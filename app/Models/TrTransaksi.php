<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrTransaksi extends Model
{
    protected $table = 'tr_transaksi';
    protected $primaryKey = 'id_transaksi';
    protected $fillable = [
        'kode_booking', 'tanggal_booking', 'waktu_mulai', 'durasi_sewa',
        'total_harga', 'opsi_pembayaran', 'jumlah_dp', 'status_booking',
        'status_pembayaran', 'catatan_pembayaran', 'harga_saat_transaksi',
        'ms_id_pengguna', 'ms_id_ruangan', 'ms_id_paket'
    ];

    public function pengguna()
    {
        return $this->belongsTo(User::class, 'ms_id_pengguna', 'id');
    }

    public function ruangan()
    {
        return $this->belongsTo(MsRuangan::class, 'ms_id_ruangan', 'id_ruangan');
    }

    public function paket()
    {
        return $this->belongsTo(MsPaket::class, 'ms_id_paket', 'id_paket');
    }
}