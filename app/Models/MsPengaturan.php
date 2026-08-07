<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MsPengaturan extends Model
{
    protected $table = 'ms_pengaturan';
    protected $primaryKey = 'id_pengaturan';
    public $timestamps = true; // Pastikan ini true karena lu punya created_at & updated_at di skema awal

    // Tambahin kolom baru ke fillable biar bisa di-save lewat controller
    protected $fillable = [
        'wifi_ssid',
        'wifi_password',
        'nama_toko',
        'alamat_toko',
        'slogan_header',
        'logo_struk',
    ];

    /**
     * Ambil baris pengaturan (selalu 1 baris, dibuat kalau belum ada).
     * Dipakai di BookingService & halaman admin pengaturan supaya
     * tidak perlu tahu id-nya secara eksplisit.
     */
    public static function current(): self
    {
        // Kasih nilai default sekalian buat kolom baru
        return self::firstOrCreate([], [
            'wifi_ssid'     => '-',
            'wifi_password' => '-',
            'nama_toko'     => 'Play n Chill Madiun',
            'alamat_toko'   => 'Jl. Margobawero No.46 Kota Madiun',
            'slogan_header' => 'Play, Chill, Repeat!',
            'logo_struk'    => null, 
        ]);
    }
}