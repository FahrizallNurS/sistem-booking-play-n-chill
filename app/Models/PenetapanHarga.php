<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PenetapanHarga extends Model
{
    protected $table = 'penetapan_harga';
    protected $primaryKey = 'id_penetapan_harga';
    protected $fillable = [
        'id_ruangan', 
        'id_paket', 
        'harga', 
        'durasi_jam', 
        'tipe_hari',
        'sku',
    ];

    public function ruangan()
    {
        return $this->belongsTo(MsRuangan::class, 'id_ruangan', 'id_ruangan');
    }

    public function paket()
    {
        return $this->belongsTo(MsPaket::class, 'id_paket', 'id_paket');
    }

    public function transaksis()
    {
        return $this->hasMany(TrTransaksi::class, 'id_penetapan_harga', 'id_penetapan_harga');
    }

    public function scopeCurrentPrices($query)
    {
        return $query->whereIn('id_penetapan_harga', function($subquery) {
            $subquery->select(DB::raw('MAX(id_penetapan_harga)'))
                ->from('penetapan_harga')
                ->groupBy('id_ruangan', 'id_paket', 'tipe_hari', 'durasi_jam');
        });
    }

    public function scopeHistoricalPrices($query)
    {
        return $query->whereNotIn('id_penetapan_harga', function($subquery) {
            $subquery->select(DB::raw('MAX(id_penetapan_harga)'))
                ->from('penetapan_harga')
                ->groupBy('id_ruangan', 'id_paket', 'tipe_hari', 'durasi_jam');
        });
    }
    
    public static function findExisting($idRuangan, $idPaket, $tipeHari, $durasiJam)
    {
        return self::where('id_ruangan', $idRuangan)
            ->where('id_paket', $idPaket)
            ->where('tipe_hari', $tipeHari)
            ->where('durasi_jam', $durasiJam)
            ->orderBy('id_penetapan_harga', 'desc')
            ->first();
    }

        public static function tipeHariFromDate($tanggal)
    {
        $carbon = $tanggal instanceof \Carbon\Carbon
            ? $tanggal
            : \Carbon\Carbon::parse($tanggal);

        // dayOfWeekIso: 1 = Senin ... 7 = Minggu
        return in_array($carbon->dayOfWeekIso, [1, 2, 3, 4])
            ? 'harian'
            : 'akhir_pekan';
    }
}