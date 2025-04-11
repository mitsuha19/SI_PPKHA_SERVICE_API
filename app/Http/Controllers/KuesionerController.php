<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\Answer;
use App\Models\Option;
use App\Models\Section;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KuesionerController extends Controller
{
    public function show()
    {

        $userId = Auth::id();

        $hasAnswered = Answer::where('user_id', $userId)->exists();

        if ($hasAnswered) {
            return view('ppkha.kuisionerSubmit');
        }

        $form = Form::orderBy('id')->first();

        if (!$form) {
            return view('ppkha.kuisioner', ['form' => null, 'firstSection' => null]);
        }

        $firstSection = Section::where('form_id', $form->id)->orderBy('section_order')->first();

        if (!$firstSection) {
            return view('ppkha.kuisioner', ['form' => $form, 'firstSection' => null]);
        }

        session(['answers' => [], 'section_history' => [$firstSection->id]]);

        return view('ppkha.kuisioner', compact('form', 'firstSection'));
    }

    public function nextSection(Request $request, $sectionId)
    {
        $nextSectionId = null;
        $currentSection = Section::findOrFail($sectionId);
        $form = Form::findOrFail($currentSection->form_id);

        $answers = session('answers', []);
        $sectionHistory = session('section_history', []);

        // Simpan jawaban dari section saat ini ke session
        $newAnswers = $request->input('answers', []);
        foreach ($newAnswers as $questionId => $answerValue) {
            if (is_array($answerValue)) {
                $answerValue = json_encode($answerValue);
            }
            if (!isset($answers[$questionId]) || $answers[$questionId] !== $answerValue) {
                $answers[$questionId] = $answerValue;

                // Jika ini pertanyaan pilihan ganda, bersihkan jawaban dari section yang tidak relevan
                $question = Question::find($questionId);
                if ($question && $question->type_question_id == 3) {
                    $this->cleanIrrelevantAnswers($questionId, $answerValue, $sectionHistory, $answers);
                }
            }
        }
        session(['answers' => $answers]);

        // Tentukan section berikutnya
        foreach ($newAnswers as $questionId => $answerValue) {
            $question = Question::find($questionId);
            if ($question && $question->type_question_id == 3) {
                $option = Option::where('question_id', $questionId)
                    ->where('id', $answerValue)
                    ->first();
                if ($option && $option->next_section_id) {
                    $nextSectionId = $option->next_section_id;
                    break;
                }
            }
        }

        if ($nextSectionId) {
            $nextSection = Section::find($nextSectionId);
        } else {
            $excludedSections = Option::whereNotNull('next_section_id')->pluck('next_section_id')->toArray();
            $nextSection = Section::where('form_id', $currentSection->form_id)
                ->where('section_order', '>', $currentSection->section_order)
                ->whereNotIn('id', $excludedSections)
                ->orderBy('section_order')
                ->first();
        }

        // Update riwayat section
        if ($nextSection && !in_array($nextSection->id, $sectionHistory)) {
            $sectionHistory[] = $nextSection->id;
            session(['section_history' => $sectionHistory]);
        }

        if ($nextSection) {
            return view('ppkha.kuisioner', [
                'form' => $form,
                'firstSection' => $nextSection,
                'previousSectionId' => $sectionId
            ]);
        }

        return $this->submit();
    }

    public function previousSection($sectionId)
    {
        $currentSection = Section::findOrFail($sectionId);
        $form = Form::findOrFail($currentSection->form_id);

        $sectionHistory = session('section_history', []);
        $currentIndex = array_search($sectionId, $sectionHistory);

        //      \Illuminate\Support\Facades\Log::info("Previous Section - Section ID: $sectionId, History: " . implode(', ', $sectionHistory) . ", Current Index: $currentIndex");

        if ($currentIndex > 0) {
            $previousSectionId = $sectionHistory[$currentIndex - 1];
            $previousSection = Section::find($previousSectionId);

            $previousToPreviousId = $currentIndex > 1 ? $sectionHistory[$currentIndex - 2] : null;

            return view('ppkha.kuisioner', [
                'form' => $form,
                'firstSection' => $previousSection,
                'previousSectionId' => $previousToPreviousId
            ]);
        }

        $firstSection = Section::where('form_id', $form->id)->orderBy('section_order')->first();
        return view('ppkha.kuisioner', compact('form', 'firstSection'));
    }

    public function submit()
    {
        $answers = session('answers', []);
        $sectionHistory = session('section_history', []);
        $userId = Auth::id();

        $validSectionIds = $this->getValidSectionIds($sectionHistory, $answers);
        $filteredAnswers = array_filter($answers, function ($questionId) use ($validSectionIds) {
            $question = Question::find($questionId);
            return $question && in_array($question->section_id, $validSectionIds);
        }, ARRAY_FILTER_USE_KEY);

        foreach ($filteredAnswers as $questionId => $answerValue) {
            $question = Question::find($questionId);
            if (!$question) continue;

            $attributes = [
                'question_id' => $questionId,
                'user_id' => $userId,
            ];

            if (in_array($question->type_question_id, [3, 4, 5])) {
                $option = Option::where('question_id', $questionId)
                    ->where('id', $answerValue)
                    ->first();
                if ($option) {
                    Answer::updateOrCreate(
                        $attributes,
                        ['answer_value' => $option->option_body]
                    );
                }
            } else {
                Answer::updateOrCreate(
                    $attributes,
                    ['answer_value' => $answerValue]
                );
            }
        }

        session()->forget(['answers', 'section_history']);

        return view('ppkha.kuisionerSubmit');
    }

    /**
     * Membersihkan jawaban dari section yang tidak relevan berdasarkan perubahan pilihan ganda.
     */
    private function cleanIrrelevantAnswers($questionId, $selectedOptionId, &$sectionHistory, &$answers)
    {
        $question = Question::find($questionId);
        $currentSectionId = $question->section_id;
        $currentSectionIndex = array_search($currentSectionId, $sectionHistory);

        if ($currentSectionIndex === false) return;

        // Ambil opsi yang dipilih
        $selectedOption = Option::find($selectedOptionId);
        $nextSectionId = $selectedOption ? $selectedOption->next_section_id : null;

        // Ambil semua opsi lain untuk pertanyaan ini
        $otherOptions = Option::where('question_id', $questionId)
            ->where('id', '!=', $selectedOptionId)
            ->whereNotNull('next_section_id')
            ->pluck('next_section_id')
            ->toArray();

        // Potong riwayat section setelah current section
        $sectionHistory = array_slice($sectionHistory, 0, $currentSectionIndex + 1);
        if ($nextSectionId && !in_array($nextSectionId, $sectionHistory)) {
            $sectionHistory[] = $nextSectionId;
        }
        session(['section_history' => $sectionHistory]);

        // Hapus jawaban dari section yang tidak lagi relevan
        $irrelevantSectionIds = array_diff($sectionHistory, [$currentSectionId, $nextSectionId]);
        foreach ($answers as $qId => $value) {
            $q = Question::find($qId);
            if ($q && in_array($q->section_id, $otherOptions)) {
                unset($answers[$qId]);
            }
        }
        session(['answers' => $answers]);
    }

    /**
     * Menentukan section yang valid berdasarkan alur pilihan pengguna.
     */
    private function getValidSectionIds($sectionHistory, $answers)
    {
        $validSectionIds = [$sectionHistory[0]]; // Mulai dari section pertama
        $currentSectionId = $sectionHistory[0];

        while (true) {
            $nextSectionId = null;
            $questions = Question::where('section_id', $currentSectionId)->get();

            foreach ($questions as $question) {
                if ($question->type_question_id == 3 && isset($answers[$question->id])) {
                    $option = Option::where('question_id', $question->id)
                        ->where('id', $answers[$question->id])
                        ->first();
                    if ($option && $option->next_section_id) {
                        $nextSectionId = $option->next_section_id;
                        break;
                    }
                }
            }

            if (!$nextSectionId) {
                $currentSection = Section::find($currentSectionId);
                $nextSection = Section::where('form_id', $currentSection->form_id)
                    ->where('section_order', '>', $currentSection->section_order)
                    ->whereIn('id', $sectionHistory)
                    ->orderBy('section_order')
                    ->first();
                $nextSectionId = $nextSection ? $nextSection->id : null;
            }

            if ($nextSectionId && in_array($nextSectionId, $sectionHistory)) {
                $validSectionIds[] = $nextSectionId;
                $currentSectionId = $nextSectionId;
            } else {
                break;
            }
        }

        return $validSectionIds;
    }
}
