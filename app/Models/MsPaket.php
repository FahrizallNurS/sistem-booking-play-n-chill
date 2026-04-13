<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MsPaket extends Model
{
    protected $table = 'ms_paket';
    protected $primaryKey = 'id_paket';
    protected $fillable = ['nama_paket', 'deskripsi_paket', 'maksimal_orang', 'is_active'];

    public function pricings()
    {
        return $this->hasMany(MsPricing::class, 'ms_paket_id_paket', 'id_paket');
    }

    public function fasilitas()
    {
        return $this->belongsToMany(
        MsFasilitas::class,
        'ms_paket_has_ms_fasilitas',
        'ms_paket_id_paket',
        'ms_fasilitas_id_fasilitas'
     );
    }
}
