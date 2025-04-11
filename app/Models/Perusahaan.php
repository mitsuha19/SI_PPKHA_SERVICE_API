<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perusahaan extends Model
{
    use HasFactory;

    protected $table = 'perusahaan';
    protected $primaryKey = 'id';
    protected $fillable = [
        'namaPerusahaan', 
        'lokasiPerusahaan', 
        'websitePerusahaan', 
        'industriPerusahaan',
        'deskripsiPerusahaan', 
        'logo'
    ];

    public function lowongan() {
        return $this->hasMany(Lowongan::class, 'perusahaan_id', 'id');
    }
}

