<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Galeri extends Model {
  use HasFactory;

  protected $table = 't_galeri';
  protected $primaryKey = 'id_galeri';

  protected $fillable = [
    'judul_foto',
    'deskripsi_foto',
    'kategori',
    'file_foto',
    'is_active'
  ];

  public $timestamps = true;
}