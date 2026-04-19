<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MsPaket extends Model
{
    protected $table = 'ms_paket';
    protected $primaryKey = 'id_paket';
    protected $fillable = [
        'nama_paket', 'deskripsi_paket', 'maksimal_orang', 'is_active'
    ];

    public function penetapanHarga()
    {
        return $this->hasMany(PenetapanHarga::class, 'id_paket', 'id_paket');
    }
}
