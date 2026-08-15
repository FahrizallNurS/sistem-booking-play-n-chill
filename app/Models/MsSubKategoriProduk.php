<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MsSubKategoriProduk extends Model
{
    use HasFactory;

    protected $table = 'ms_sub_kategori_produk';
    protected $primaryKey = 'id_sub_kategori_produk';

    protected $fillable = [
        'kategori_produk',
        'sub_kategori_produk',
        'is_active'
    ];

    public function produks()
    {
        return $this->hasMany(MsProduk::class, 'ms_sub_kategori_produk_id_sub_kategori_produk', 'id_sub_kategori_produk');
    }
}