<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
  use HasFactory;

  protected $fillable = ['form_id', 'section_name', 'section_order'];

  public function form()
  {
    return $this->belongsTo(Form::class);
  }

  public function questions()
  {
    return $this->hasMany(Question::class)->orderBy('question_order');
  }
}
