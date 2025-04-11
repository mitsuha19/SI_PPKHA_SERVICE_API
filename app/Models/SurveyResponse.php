<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurveyResponse extends Model
{
    protected $fillable = [
        'survey_sections_id',
        'survey_id',
        'response',
    ];

    protected $casts = [
        'response' => 'array', // Optional: Casts the response to an array if it’s JSON-encoded
    ];

    public function surveySection()
    {
        return $this->belongsTo(SurveySection::class, 'survey_sections_id');
    }

    public function survey()
    {
        return $this->belongsTo(Survey::class, 'survey_id');
    }
}
