<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenetapanHarga extends Model
{
    protected $table = 'penetapan_harga';
    protected $primaryKey = 'id_penetapan_harga';
    protected $fillable = [
        'id_ruangan', 'id_paket', 'harga', 'durasi_jam', 'tipe_hari'
    ];

    public function ruangan()
    {
        return $this->belongsTo(MsRuangan::class, 'id_ruangan', 'id_ruangan');
    }

    public function paket()
    {
        return $this->belongsTo(MsPaket::class, 'id_paket', 'id_paket');
    }

    public function transaksis()
    {
        return $this->hasMany(TrTransaksi::class, 'id_penetapan_harga', 'id_penetapan_harga');
    }
}