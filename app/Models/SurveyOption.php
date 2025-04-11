<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurveyOption extends Model
{
    protected $fillable = ['survey_id', 'option_body', 'label_angka'];

    public function survey()
    {
        return $this->belongsTo(Survey::class, 'survey_id');
    }
}
