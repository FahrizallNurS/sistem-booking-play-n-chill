<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;
    protected $table = 't_video';
    protected $primaryKey = 'id_video';
   protected $fillable = [
        'thumbnail',
        'link-video', 
        'is_active'
    ];
}