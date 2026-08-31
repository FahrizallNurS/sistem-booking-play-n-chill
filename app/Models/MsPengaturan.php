<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MsPengaturan extends Model
{
    protected $table = 'ms_pengaturan';
    protected $primaryKey = 'id_pengaturan';
    public $timestamps = true;

    protected $fillable = [
        'wifi_ssid',
        'wifi_password',
        'nama_toko',
        'alamat_toko',
        'slogan_header',
        'logo_struk',
        'ig',
        'wa',
        'tiktok',
    ];

    public static function current(): self
    {
        return self::firstOrCreate([], [
            'wifi_ssid'     => '-',
            'wifi_password' => '-',
            'nama_toko'     => 'Play n Chill Madiun',
            'alamat_toko'   => 'Jl. Margobawero No.46 Kota Madiun',
            'slogan_header' => 'Play, Chill, Repeat!',
            'logo_struk'    => null,
            'ig'            => '-',
            'wa'            => '-',
            'tiktok'        => '-',
        ]);
    }
}