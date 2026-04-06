<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengguna extends Model
{
    protected $table = 'ms_pengguna';

    protected $primaryKey = 'id_pengguna';

    protected $fillable = [
        'nama_pengguna',
        'email',
        'password',
        'no_hp',
        'status',
        'google_id'
    ];

    public $timestamps = false; // karena pakai created_at manual
}