<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class MsSubKategoriPaket extends Model
{
    protected $table = 'ms_sub_kategori_paket';
    protected $primaryKey = 'id_sub_kategori_paket';
    protected $fillable = [
        'nama_sub_kategori', 'is_active'
    ];
 
    public function pakets()
    {
        return $this->hasMany(MsPaket::class, 'ms_sub_kategori_paket_id_sub_kategori_paket', 'id_sub_kategori_paket');
    }
}