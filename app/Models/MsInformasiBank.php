<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MsInformasiBank extends Model
{
    protected $table = 'ms_informasi_bank';
    protected $primaryKey = 'id_informasi_bank';
    protected $fillable = ['nama_bank', 'nomor_akun', 'nama_akun', 'logo_bank', 'kode_qr', 'catatan'];
}