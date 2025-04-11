<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lowongan extends Model
{
    use HasFactory;

    protected $table = 'lowongan';
    protected $primaryKey = 'id';

    protected $fillable = [
        'judulLowongan', 
        'jenisLowongan', 
        'tipeLowongan',
        'deskripsiLowongan', 
        'kualifikasi', 
        'benefit', 
        'keahlian',
        'batasMulai', 
        'batasAkhir', 
        'perusahaan_id'
    ];

    protected $casts = [
        'keahlian' => 'array',
    ];

    public function perusahaan() {
        return $this->belongsTo(Perusahaan::class, 'perusahaan_id', 'id');
    }
}
