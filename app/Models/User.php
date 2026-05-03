<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $table      = 'users';
    protected $primaryKey = 'id_pengguna';

    protected $fillable = [
        'nama_pengguna',
        'email',
        'password',
        'no_hp',
        'status',
        'google_id',
        'alamat',
        'role',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // Relasi ke transaksi
    public function transaksis()
    {
        return $this->hasMany(TrTransaksi::class, 'id_pengguna', 'id_pengguna');
    }

        
    public function getAuthIdentifierName()
    {
        return 'id_pengguna';
    }

    public function getAuthIdentifier()
    {
        return $this->id_pengguna;      
    }

    public function getAuthPassword()
    {
        return $this->password;
    }
}