<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MsKategori extends Model
{
    protected $table = 'ms_kategori';
    protected $primaryKey = 'id_kategori';
    protected $fillable = ['nama_kategori', 'deskripsi'];

    public function ruangans()
    {
        return $this->hasMany(MsRuangan::class, 'ms_kategori_id_kategori', 'id_kategori');
    }
}