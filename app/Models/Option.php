<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;

    protected $fillable = ['question_id', 'option_body', 'next_section_id', 'option_order', 'label_angka'];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function nextSection()
    {
        return $this->belongsTo(Section::class, 'next_section_id');
    }
}
