<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Tambahkan ini jika nama tabel di database kamu bukan 'users' (tapi biasanya defaultnya 'users')
    protected $table = 'users'; 

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',     // Tambahkan dari Pengguna.php
        'no_hp',
        'status',    // Tambahkan dari Pengguna.php
        'role',
        'google_id',
        'alamat',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed', // Ini keren, password otomatis di-hash!
        ];
    }
}