<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    use HasFactory;

    protected $fillable = ['judul_form', 'deskripsi_form'];

    public function sections()
    {
        return $this->hasMany(Section::class)->orderBy('section_order');
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? false, function ($query, $search) {
            return $query->where('judul_form', 'like', '%' . $search . '%')
                ->orWhere('judul_form', 'like', '%' . $search . '%');
        });
    }
}
