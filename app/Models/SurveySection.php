<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveySection extends Model
{
    use HasFactory;
    protected $fillable = ['survey_sections_name'];
    public function surveys()
    {
        return $this->hasMany(Survey::class, 'survey_sections_id');
    }
}
