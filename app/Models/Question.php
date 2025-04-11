<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = ['section_id', 'question_body', 'type_question_id', 'is_required', 'question_order'];

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function typeQuestion()
    {
        return $this->belongsTo(TypeQuestion::class);
    }

    public function options()
    {
        return $this->hasMany(Option::class)->orderBy('option_order');
    }
}
