<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MsPaket extends Model
{
    protected $table = 'ms_paket';
    protected $primaryKey = 'id_paket';
    protected $fillable = [
        'nama_paket', 'deskripsi_paket', 'maksimal_orang', 'is_active',
        'ms_sub_kategori_paket_id_sub_kategori_paket'
    ];

    public function penetapanHarga()
    {
        return $this->hasMany(PenetapanHarga::class, 'id_paket', 'id_paket');
    }

    public function subKategori()
    {
        return $this->belongsTo(MsSubKategoriPaket::class, 'ms_sub_kategori_paket_id_sub_kategori_paket', 'id_sub_kategori_paket');
    }
}
