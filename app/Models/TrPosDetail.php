<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrPosDetail extends Model
{
    use HasFactory;

    protected $table = 'tr_pos_detail';
    protected $primaryKey = 'id_pos_detail';
    protected $guarded = [];

    // --- MATIKAN TIMESTAMP HANYA UNTUK TABEL DETAIL INI ---
    public $timestamps = false; 
}