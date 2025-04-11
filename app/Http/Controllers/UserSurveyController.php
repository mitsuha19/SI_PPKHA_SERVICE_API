<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\SurveyOption;
use App\Models\TypeQuestion;
use Illuminate\Http\Request;
use App\Models\SurveySection;
use App\Models\SurveyResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Validation\ValidationException;
// use Illuminate\Support\Facades\Session;

class UserSurveyController extends Controller
{
    public function mainSurvey()
    {
        $sections = SurveySection::with(['surveys.surveyOptions'])->latest()->get();
        $typeQuestions = TypeQuestion::all();

        if ($sections->isEmpty()) {
            return view('admin.userSurvey.userSurvey', [
                'sections' => $sections,
                'output' => '0',
                'typeQuestions' => $typeQuestions,
                'showZero' => true // Flag to indicate we should display 0 in the UI
            ]);
        }

        return view('admin.userSurvey.userSurvey', [
            'sections' => $sections,
            'typeQuestions' => $typeQuestions,
            'showZero' => false // No need to display 0 when sections exist
        ]);
    }

    public function createSurvey()
    {
        $typeQuestions = TypeQuestion::all();
        return view('admin.userSurvey.addUserSurvey', compact('typeQuestions'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            // Log the incoming request data for debugging
            Log::info('Submitted form data:', $request->all());

            $request->validate([
                'sections' => 'required|array|min:1',
                'sections.*.survey_sections_name' => 'required|string|max:255',
                'sections.*.questions' => 'required|array|min:1',
                'sections.*.questions.*.question_title' => 'required|string|max:255',
                'sections.*.questions.*.type_question_id' => 'required|exists:type_questions,id',
                'sections.*.questions.*.is_required' => 'required|boolean',
                'sections.*.questions.*.options' => 'sometimes|array',
                'sections.*.questions.*.options.*.option_body' => 'required_if:sections.*.questions.*.type_question_id,3,4,5,6|string',
                'sections.*.questions.*.options.*.label_angka' => 'nullable|string',
            ], [
                'sections.*.survey_sections_name.required' => 'Nama section wajib diisi.',
                'sections.*.questions.*.question_title.required' => 'Judul pertanyaan wajib diisi.',
                'sections.*.questions.*.type_question_id.required' => 'Tipe pertanyaan wajib dipilih.',
                'sections.*.questions.*.is_required.required' => 'Status wajib diisi harus ditentukan.',
                'sections.*.questions.*.options.*.option_body.required_if' => 'Opsi jawaban wajib diisi untuk tipe pertanyaan ini.',
            ]);

            foreach ($request->sections as $sectionIndex => $sectionData) {
                $section = new SurveySection();
                $section->id = $this->getNextAvailableId('survey_sections');
                $section->survey_sections_name = $sectionData['survey_sections_name'];
                $section->save();

                foreach ($sectionData['questions'] as $questionIndex => $question) {
                    // Log each question for debugging
                    Log::info("Processing question [Section: {$sectionIndex}, Question: {$questionIndex}]", $question);

                    $survey = new Survey();
                    $survey->id = $this->getNextAvailableId('surveys');
                    $survey->survey_sections_id = $section->id;
                    $survey->question_title = $question['question_title'];
                    $survey->type_question_id = $question['type_question_id'];
                    // Use array_key_exists to avoid undefined key error
                    $survey->is_required = isset($question['is_required']) ? (bool) $question['is_required'] : false;
                    $survey->save();

                    if (in_array($question['type_question_id'], [3, 4, 5, 6]) && isset($question['options'])) {
                        foreach ($question['options'] as $optionData) {
                            $surveyOption = new SurveyOption();
                            $surveyOption->id = $this->getNextAvailableId('survey_options');
                            $surveyOption->survey_id = $survey->id;
                            $surveyOption->option_body = $optionData['option_body'] ?? null;
                            $surveyOption->label_angka = $optionData['label_angka'] ?? null;
                            $surveyOption->save();
                        }
                    }
                }
            }

            DB::commit();
            Alert::success('Success', 'Survey berhasil dibuat!');
            return redirect()->route('admin.surveys.survey');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in store method: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return back()->withErrors(['msg' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
        }
    }

    // public function edit($id)
    // {
    //     $surveySection = SurveySection::with(['surveys.surveyOptions'])->findOrFail($id);
    //     $typeQuestions = TypeQuestion::all();
    //     return view('admin.userSurvey.editUserSurvey', compact('surveySection', 'typeQuestions'));
    // }

    public function updateAll(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'sections' => 'required|array|min:1',
                'sections.*.id' => 'nullable|exists:survey_sections,id',
                'sections.*.survey_sections_name' => 'required|string|max:255',
                'sections.*.questions' => 'required|array|min:1',
                'sections.*.questions.*.id' => 'nullable|exists:surveys,id',
                'sections.*.questions.*.question_title' => 'required|string|max:255',
                'sections.*.questions.*.type_question_id' => 'required|exists:type_questions,id',
                'sections.*.questions.*.is_required' => 'required|boolean',
                'sections.*.questions.*.survey_options' => 'sometimes|array',
                'sections.*.questions.*.survey_options.*.id' => 'nullable|exists:survey_options,id',
                'sections.*.questions.*.survey_options.*.option_body' => 'required|string',
                'sections.*.questions.*.survey_options.*.label_angka' => 'nullable|string',
            ]);

            $existingSections = SurveySection::with('surveys.surveyOptions')->get()->keyBy('id');
            $sectionIds = array_column($request->sections, 'id');
            $toDelete = array_diff($existingSections->pluck('id')->toArray(), array_filter($sectionIds));
            SurveySection::whereIn('id', $toDelete)->delete();

            foreach ($request->sections as $index => $sectionData) {
                if (isset($sectionData['id']) && $existingSections->has($sectionData['id'])) {
                    $section = $existingSections[$sectionData['id']];
                    $section->update([
                        'survey_sections_name' => $sectionData['survey_sections_name'],
                    ]);
                } else {
                    $section = new SurveySection();
                    $section->id = $this->getNextAvailableId('survey_sections');
                    $section->survey_sections_name = $sectionData['survey_sections_name'];
                    $section->save();
                }

                $existingQuestionIds = $section->surveys->pluck('id')->toArray();
                $newQuestionIds = array_filter(array_column($sectionData['questions'], 'id'));
                $toDeleteQuestions = array_diff($existingQuestionIds, $newQuestionIds);
                Survey::whereIn('id', $toDeleteQuestions)->delete();

                foreach ($sectionData['questions'] as $questionData) {
                    if (isset($questionData['id'])) {
                        $survey = Survey::find($questionData['id']);
                        $survey->update([
                            'question_title' => $questionData['question_title'],
                            'type_question_id' => $questionData['type_question_id'],
                            'is_required' => $questionData['is_required'],
                        ]);
                    } else {
                        $survey = new Survey();
                        $survey->id = $this->getNextAvailableId('surveys');
                        $survey->survey_sections_id = $section->id;
                        $survey->question_title = $questionData['question_title'];
                        $survey->type_question_id = $questionData['type_question_id'];
                        $survey->is_required = $questionData['is_required'];
                        $survey->save();
                    }

                    if (in_array($questionData['type_question_id'], [3, 4, 5, 6])) {
                        $existingOptionIds = $survey->surveyOptions->pluck('id')->toArray();
                        $newOptionIds = isset($questionData['survey_options']) ? array_filter(array_column($questionData['survey_options'], 'id')) : [];
                        $toDeleteOptions = array_diff($existingOptionIds, $newOptionIds);
                        SurveyOption::whereIn('id', $toDeleteOptions)->delete();

                        if (isset($questionData['survey_options'])) {
                            foreach ($questionData['survey_options'] as $optionIndex => $optionData) {
                                if (isset($optionData['id'])) {
                                    $surveyOption = SurveyOption::find($optionData['id']);
                                    $surveyOption->update([
                                        'option_body' => $optionData['option_body'],
                                        'label_angka' => $optionData['label_angka'] ?? null,
                                    ]);
                                } else {
                                    $surveyOption = new SurveyOption();
                                    $surveyOption->id = $this->getNextAvailableId('survey_options');
                                    $surveyOption->survey_id = $survey->id;
                                    $surveyOption->option_body = $optionData['option_body'];
                                    $surveyOption->label_angka = $optionData['label_angka'] ?? null;
                                    $surveyOption->save();
                                }
                            }
                        }
                    } else {
                        $survey->surveyOptions()->delete();
                    }
                }
            }

            DB::commit();
            return redirect()->route('admin.surveys.survey')->with('success', 'Semua section berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['msg' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $surveySection = SurveySection::findOrFail($id);

            // Log the deletion attempt
            Log::info('Attempting to delete survey section with ID: ' . $id);

            // Delete the survey section (cascade will delete related surveys and survey_options)
            $surveySection->delete();

            DB::commit();

            return response()->json([
                'message' => 'Section berhasil dihapus. ID berikutnya akan diatur secara otomatis.',
                'status' => 'success'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in destroy method for ID ' . $id . ': ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    private function getNextAvailableId($tableName)
    {
        return DB::transaction(function () use ($tableName) {
            $maxId = DB::table($tableName)->max('id') ?: 0;
            $nextId = $maxId + 1;

            while (DB::table($tableName)->where('id', $nextId)->exists()) {
                $nextId++;
            }

            return $nextId;
        });
    }

    public function showSurvey()
    {
        $surveySections = SurveySection::with(['surveys.surveyOptions'])->get();

        if ($surveySections->isEmpty()) {
            return view('ppkha.user_survey', ['surveySections' => [], 'message' => 'Maaf tidak ada survei yang tersedia saat ini. Kembali lagi!']);
        }

        return view('ppkha.user_survey', compact('surveySections'));
    }

    public function submit(Request $request)
    {
        // Fetch all survey sections with their surveys to validate required fields
        $surveySections = SurveySection::with('surveys')->get();

        // Build dynamic validation rules based on 'is_required'
        $validationRules = ['answers' => 'required|array'];
        $validationMessages = ['answers.required' => 'Harap isi setidaknya satu jawaban.'];

        foreach ($surveySections as $sectionIndex => $section) {
            foreach ($section->surveys as $questionIndex => $question) {
                $fieldName = "answers.{$sectionIndex}.{$questionIndex}";
                if ($question->is_required) {
                    $validationRules[$fieldName] = 'required';
                    $validationMessages["{$fieldName}.required"] = "Jawaban untuk pertanyaan '{$question->question_title}' wajib diisi.";
                } else {
                    $validationRules[$fieldName] = 'nullable';
                }

                if ($question->type_question_id == 4) { // Checkboxes
                    $validationRules[$fieldName . '.*'] = 'string';
                }
            }
        }

        // Validate the incoming request
        $validated = $request->validate($validationRules, $validationMessages);

        // Check if the number of sections matches the submitted data
        if ($surveySections->count() !== count($request->input('answers', []))) {
            return response()->json([
                'error' => 'Jumlah section yang dikirim (' . count($request->input('answers', [])) . ') tidak sesuai dengan survei yang tersedia (' . $surveySections->count() . ').',
                'status' => 'error'
            ], 422);
        }

        DB::beginTransaction();
        try {
            // Log the incoming data for debugging
            Log::info('Submitted survey answers:', $request->all());

            // Process each section and question
            foreach ($surveySections as $sectionIndex => $section) {
                if (!isset($request->answers[$sectionIndex])) {
                    throw new \Exception("Data untuk section {$section->survey_sections_name} tidak ditemukan.");
                }

                foreach ($section->surveys as $questionIndex => $question) {
                    $answer = $request->input("answers.{$sectionIndex}.{$questionIndex}");

                    // Handle different response formats
                    if (is_array($answer)) {
                        $answer = !empty($answer) ? json_encode(array_filter($answer)) : '';
                    } elseif ($answer === '' || $answer === null) {
                        $answer = ''; // Default to empty string to satisfy NOT NULL constraint
                    }

                    // Validate required fields again (double-check)
                    if ($question->is_required && empty($answer)) {
                        throw new \Exception("Pertanyaan wajib diisi: {$question->question_title} (Section: {$section->survey_sections_name})");
                    }

                    // Create a new survey response
                    SurveyResponse::create([
                        'survey_sections_id' => $section->id,
                        'survey_id' => $question->id,
                        'response' => $answer,
                    ]);
                }
            }

            DB::commit();

            // Return success response for AJAX
            return response()->json([
                'message' => 'Terima kasih! Survei Anda telah dikirim.',
                'status' => 'success'
            ], 200);
        } catch (ValidationException $e) {
            DB::rollBack();
            Log::error('Validation error in submit method: ' . $e->getMessage(), ['errors' => $e->errors()]);
            return response()->json([
                'error' => 'Validasi gagal: ' . implode(', ', array_map('reset', $e->errors())),
                'status' => 'error'
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in submit method: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'error' => 'Terjadi kesalahan saat mengirim survei: ' . $e->getMessage(),
                'status' => 'error'
            ], 500);
        }
    }
}
