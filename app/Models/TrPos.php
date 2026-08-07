<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrPos extends Model
{
    use HasFactory;

    // Arahkan ke nama tabel yang benar
    protected $table = 'tr_pos';

    // Beritahu Laravel bahwa Primary Key-nya adalah id_pos, bukan id
    protected $primaryKey = 'id_pos';

    // Izinkan semua kolom untuk diisi (Mass Assignment)
    protected $guarded = [];
    protected $appends = ['kode_pos'];

    public function getKodePosAttribute(): string
    {
        return 'POS-' . str_pad((string) $this->id_pos, 3, '0', STR_PAD_LEFT);
    }
}