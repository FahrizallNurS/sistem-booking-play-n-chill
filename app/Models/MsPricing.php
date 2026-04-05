<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MsPricing extends Model
{
    protected $table = 'ms_pricing';
    protected $primaryKey = 'id_pricing';
    protected $fillable = [
        'tipe_pricing', 'harga', 'hari_type', 'durasi_menit',
        'ms_ruangan_id_ruangan', 'ms_paket_id_paket', 'durasi_rjsm'
    ];

    public function ruangan()
    {
        return $this->belongsTo(MsRuangan::class, 'ms_ruangan_id_ruangan', 'id_ruangan');
    }

    public function paket()
    {
        return $this->belongsTo(MsPaket::class, 'ms_paket_id_paket', 'id_paket');
    }
}