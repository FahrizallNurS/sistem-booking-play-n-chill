<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MsRuangan extends Model
{
    protected $table = 'ms_ruangan';
    protected $primaryKey = 'id_ruangan';
    protected $fillable = [
        'nama_ruangan', 'kategori', 'deskripsi',
        'perangkat', 'is_active', 'galeri'
    ];

    public function penetapanHarga()
    {
        return $this->hasMany(PenetapanHarga::class, 'id_ruangan', 'id_ruangan');
    }

    public function permainans()
    {
        return $this->belongsToMany(
            MsPermainan::class,
            'ms_ruangan_ms_permainan',
            'id_ruangan',
            'id_permainan'
        );
    }
}