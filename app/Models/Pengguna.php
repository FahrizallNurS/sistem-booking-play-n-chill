<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; // Penting untuk Auth::login
use Illuminate\Notifications\Notifiable;

class Pengguna extends Authenticatable
{
    use Notifiable;

    protected $table = 'users'; 

    // Berdasarkan gambar DB kamu, primary key-nya adalah 'id'
    protected $primaryKey = 'id'; 

    protected $fillable = [
        'name',      // Sesuai kolom di DB
        'email',
        'password',
        'phone',     // Sesuai kolom di DB
        'no_hp',
        'status',
        'role',
        'google_id',
        'alamat'
    ];

    public $timestamps = true; 
}