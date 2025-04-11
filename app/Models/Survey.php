<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    use HasFactory;

    protected $fillable = [
        'survey_sections_id',
        'question_title',
        'type_question_id',
        'is_required'
    ];

    public function surveySection()
    {
        return $this->belongsTo(SurveySection::class, 'survey_sections_id');
    }

    public function typeQuestion()
    {
        return $this->belongsTo(TypeQuestion::class, 'type_question_id');
    }

    public function surveyOptions()
    {
        return $this->hasMany(SurveyOption::class, 'survey_id');
    }
}