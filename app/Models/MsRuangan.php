<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MsRuangan extends Model
{
    protected $table = 'ms_ruangan';
    protected $primaryKey = 'id_ruangan';
    protected $fillable = ['nama_ruangan', 'description', 'is_active', 'ms_kategori_id_kategori'];

    public function kategori()
    {
        return $this->belongsTo(MsKategori::class, 'ms_kategori_id_kategori', 'id_kategori');
    }   

    public function games()
    {
        return $this->belongsToMany(MsGame::class, 'ms_ruangan_has_ms_game', 'ms_ruangan_id_ruangan', 'ms_game_id_game');
    }

    public function galleries()
    {
        return $this->hasMany(MsGallery::class, 'ms_ruangan_id_ruangan', 'id_ruangan');
    }

    public function pricings()
{
    return $this->hasMany(MsPricing::class, 'ms_ruangan_id_ruangan', 'id_ruangan');
}
}