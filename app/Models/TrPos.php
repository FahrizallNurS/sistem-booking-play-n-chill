<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrPos extends Model
{
    use HasFactory;

    protected $table = 'tr_pos';
    protected $primaryKey = 'id_pos';
    protected $guarded = [];
    protected $appends = ['kode_pos'];

    protected $casts = [
        'struk_created_at' => 'datetime',
    ];

    public function getKodePosAttribute(): string
    {
        return 'FNBPNC-' . str_pad((string) $this->id_pos, 3, '0', STR_PAD_LEFT);
    }

    public function pengguna()
    {
        return $this->belongsTo(User::class, 'id_pengguna', 'id_pengguna');
    }

    public function transaksi()
    {
        return $this->belongsTo(TrTransaksi::class, 'id_transaksi', 'id_transaksi');
    }

    public function details()
    {
        return $this->hasMany(TrPosDetail::class, 'id_pos', 'id_pos');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'id_admin', 'id_pengguna');
    }
}