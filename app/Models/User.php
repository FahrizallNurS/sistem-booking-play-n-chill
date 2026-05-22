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
    public $incrementing = true;

    protected $fillable = [
        'nama_pengguna',
        'email',
        'password',
        'no_hp',
        'google_id',
        'alamat',
        'role',
        'status',
        'email_verified_at',
        'remember_token',
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
    public function getKey()
    {
        return $this->id_pengguna;
    }

    public function getNameAttribute()
    {
        return $this->attributes['nama_pengguna'] ?? 'Admin';
    }

      public function isAdmin()
    {
        return $this->role === 'admin';
    }

     public function isSuperAdmin()
    {
        return $this->role === 'superadmin';
    }

     public function isPelanggan()
    {
        return $this->role === 'pelanggan';
    }

        public function isActive()
    {
        return (int) $this->status === 1;
    }

    public function adminlte_desc()
    {
        return ucfirst($this->role);
    }

      public function adminlte_profile_url()
    {
        return url('admin/profil');
    }

    public function adminlte_image()
    {
        return null; 
    }

}