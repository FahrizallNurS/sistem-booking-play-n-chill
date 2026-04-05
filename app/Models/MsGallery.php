<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MsGallery extends Model
{
    protected $table = 'ms_gallery';
    protected $primaryKey = 'id_gallery';
    protected $fillable = ['url_image', 'ms_ruangan_id_ruangan'];

    public function ruangan()
    {
        return $this->belongsTo(MsRuangan::class, 'ms_ruangan_id_ruangan', 'id_ruangan');
    }
}