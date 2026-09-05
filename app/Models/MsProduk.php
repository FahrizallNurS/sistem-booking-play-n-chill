<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MsProduk extends Model
{
    use HasFactory;

    protected $table = 'ms_produk';
    protected $primaryKey = 'id_produk';

    protected $fillable = [
        'ms_sub_kategori_produk_id_sub_kategori_produk',
        'nama_produk',
        'foto',
        'harga_jual',
        'harga_beli',
        'sku',
        'stock',
        'is_active'
    ];

    // Relasi ke Sub Kategori (mengambil Kategori & Sub Kategori)
    public function subKategori()
    {
        return $this->belongsTo(MsSubKategoriProduk::class, 'ms_sub_kategori_produk_id_sub_kategori_produk', 'id_sub_kategori_produk');
    }

    // Relasi ke detail transaksi POS untuk menghitung Best Seller
    public function detailTransaksi()
    {
        return $this->hasMany(TrPosDetail::class, 'id_produk', 'id_produk');
    }
}