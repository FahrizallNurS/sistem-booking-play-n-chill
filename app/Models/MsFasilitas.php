<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MsFasilitas extends Model
{
    protected $table = 'ms_fasilitas';
    protected $primaryKey = 'id_fasilitas';
    protected $fillable = ['nama_fasilitas'];
}