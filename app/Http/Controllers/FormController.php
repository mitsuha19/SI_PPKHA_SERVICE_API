<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\Option;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FormController extends Controller
{
    public function store(Request $request)
    {
        Log::info("Data Form Diterima: ", $request->all()); // Debugging

        // Cek apakah next_section ID valid
        foreach ($request->questions as $question) {
            foreach ($question['options'] as $option) {
                if (!is_null($option['next_section']) && !is_numeric($option['next_section'])) {
                    Log::error("Next Section ID tidak valid: " . json_encode($option));
                    return response()->json(["error" => "Invalid next_section ID"], 400);
                }
            }
        }

        // Coba simpan ke database
        try {
            $form = Form::create([
                'title' => $request->title,
                'description' => $request->description
            ]);

            foreach ($request->questions as $questionData) {
                $question = $form->questions()->create([
                    'question_text' => $questionData['text'],
                    'question_type' => $questionData['type']
                ]);


                foreach ($questionData['options'] as $optionData) {
                    $question->options()->create([
                        'option_text' => $optionData['text'], // Sesuai dengan nama di database
                        'next_section_id' => $optionData['next_section'] ?? null
                    ]);
                }
            }

            return response()->json(["success" => true, "form_id" => $form->id], 201);
        } catch (\Exception $e) {
            Log::error("Error saat menyimpan form: " . $e->getMessage());
            return response()->json(["error" => "Gagal menyimpan form"], 500);
        }
    }




    public function index()
    {
        return response()->json(Form::with('questions.options')->get());
    }
}
