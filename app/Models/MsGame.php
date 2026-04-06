<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MsGame extends Model
{
    protected $table = 'ms_game';
    protected $primaryKey = 'id_game';
    protected $fillable = ['nama_game', 'gambar_game', 'device_game'];
}
