<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypeQuestionSeeder extends Seeder
{
  public function run()
  {
    $types = [
      ['type_question_name' => 'Jawaban Singkat'],
      ['type_question_name' => 'Paragraf'],
      ['type_question_name' => 'Pilihan Ganda'],
      ['type_question_name' => 'Kotak Centang'],
      ['type_question_name' => 'Dropdown'],
      ['type_question_name' => 'Skala Linier'],
      ['type_question_name' => 'Lokasi'],
      ['type_question_name' => 'Date'],
    ];

    foreach ($types as $type) {
      DB::table('type_questions')->insert([
        'type_question_name' => $type['type_question_name'],
        'created_at' => now(),
        'updated_at' => now(),
      ]);
    }
  }
}
