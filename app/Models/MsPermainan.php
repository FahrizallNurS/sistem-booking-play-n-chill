<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MsPermainan extends Model
{
    protected $table = 'ms_permainan';
    protected $primaryKey = 'id_permainan';
    public $timestamps = false;
    protected $fillable = ['nama_permainan', 'gambar'];

    public function ruangans()
    {
        return $this->belongsToMany(
            MsRuangan::class,
            'ms_ruangan_ms_permainan',
            'id_permainan',
            'id_ruangan'
        );
    }
}