<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MobileTc extends Model
{
    protected $table = 'mobileTc';

    // Jika kamu tidak pakai created_at/updated_at:
    // public $timestamps = false;

    // Kolom yang boleh di-mass assign
    protected $fillable = [
        'judul',
        'deskripsi',
        'link_url',
    ];
}
