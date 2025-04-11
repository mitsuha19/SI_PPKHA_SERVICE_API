<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Artikel extends Model
{
    use HasFactory;

  protected $table = 'artikels';

  // Mass assignable attributes
  protected $fillable = [ 
    'judul_artikel',
    'deskripsi_artikel',
    'sumber_artikel', 
    'gambar',
  ];

  protected $casts = [
    'gambar' => 'array', // Cast lampiran column as array
  ];
}
