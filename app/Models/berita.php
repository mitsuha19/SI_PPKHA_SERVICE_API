<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Berita extends Model
{
  use HasFactory;

  protected $table = 'berita';

  // Mass assignable attributes
  protected $fillable = [ 
    'judul_berita',
    'deskripsi_berita',
    'gambar',
  ];

  protected $casts = [
    'gambar' => 'array', // Cast lampiran column as array
  ];
}
