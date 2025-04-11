<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\Section;
use App\Models\Question;
use App\Models\TypeQuestion;
use App\Models\Option;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class TracerStudyController extends Controller
{
    public function mainTracerStudy()
    {
        $pagination = Form::paginate(5)->withQueryString();
        $forms = Form::latest()->filter(request(['search']))->paginate(5)->withQueryString();
        return view('admin.tracerStudy.tracerStudy', compact('forms', 'pagination'));
    }

    public function createTracerStudy()
    {
        $typeQuestions = TypeQuestion::all();
        return view('admin.tracerStudy.tambahPertanyaan', compact('typeQuestions'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            // Validasi input
            $request->validate([
                'judul_form' => 'required|string|max:255',
                'deskripsi_form' => 'nullable|string',
                'sections' => 'required|array',
            ]);

            // Buat form baru
            $form = Form::create([
                'judul_form' => $request->judul_form,
                'deskripsi_form' => $request->deskripsi_form,
            ]);

            // **Cek apakah Section 9999 sudah ada dalam form ini**
            $sendSection = Section::where('form_id', $form->id)->where('section_name', 'Kirim Formulir')->first();

            if (!$sendSection) {
                // **Buat section "Kirim Formulir" untuk form ini**
                $sendSection = Section::create([
                    'form_id' => $form->id, // Harus terhubung ke form yang baru dibuat
                    'section_name' => 'Kirim Formulir',
                    'section_order' => 999,
                ]);
            }

            // **Simpan ID section "Kirim Formulir" untuk digunakan di next_section_id**
            $submitSectionId = $sendSection->id;

            // **Penyimpanan section dan pertanyaan lainnya**
            $sectionIds = [];
            $questionIds = [];

            foreach ($request->sections as $sectionIndex => $sectionData) {
                // Buat section baru
                $section = Section::create([
                    'form_id' => $form->id,
                    'section_name' => $sectionData['section_name'],
                    'section_order' => $sectionIndex,
                ]);

                $sectionIds[$sectionIndex] = $section->id;

                if (isset($sectionData['questions']) && is_array($sectionData['questions'])) {
                    foreach ($sectionData['questions'] as $questionIndex => $questionData) {
                        // Buat pertanyaan baru
                        $question = Question::create([
                            'section_id' => $section->id,
                            'question_body' => $questionData['question_body'],
                            'type_question_id' => $questionData['type_question_id'],
                            'is_required' => isset($questionData['is_required']) ? 1 : 0,
                            'question_order' => $questionIndex,
                        ]);

                        $questionIds[$sectionIndex][$questionIndex] = $question->id;
                    }
                }
            }

            // **Penyimpanan opsi untuk setiap pertanyaan**
            foreach ($request->sections as $sectionIndex => $sectionData) {
                if (isset($sectionData['questions']) && is_array($sectionData['questions'])) {
                    foreach ($sectionData['questions'] as $questionIndex => $questionData) {
                        $question_id = $questionIds[$sectionIndex][$questionIndex];
                        $needsOptions = in_array($questionData['type_question_id'], [3, 4, 5, 6]);

                        if ($needsOptions && isset($questionData['options']) && is_array($questionData['options'])) {
                            foreach ($questionData['options'] as $optionIndex => $optionData) {
                                $labelAngka = $questionData['type_question_id'] == 6 && isset($optionData['label_angka'])
                                    ? $optionData['label_angka']
                                    : null;

                                // **Periksa apakah opsi mengarah ke "Kirim Formulir"**
                                $nextSectionId = null;
                                if (!empty($optionData['next_section_id']) && $optionData['next_section_id'] === 'submit') {
                                    $nextSectionId = $submitSectionId; // Gunakan ID yang sesuai dalam form ini
                                } elseif (!empty($optionData['next_section_id'])) {
                                    $nextSectionId = $sectionIds[(int)$optionData['next_section_id']] ?? null;
                                }

                                // Simpan opsi ke database
                                Option::create([
                                    'question_id' => $question_id,
                                    'option_body' => $optionData['option_body'],
                                    'next_section_id' => $nextSectionId,
                                    'option_order' => $optionIndex,
                                    'label_angka' => $labelAngka,
                                ]);
                            }
                        }
                    }
                }
            }

            DB::commit();
            Alert::success('Success', 'Form berhasil dibuat!');
            return redirect()->route('admin.tracerStudy.tracerStudy');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['msg' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
        }
    }



    public function edit($id)
    {
        $form = Form::with(['sections.questions.options', 'sections.questions.typeQuestion'])->findOrFail($id);
        $typeQuestions = TypeQuestion::all();

        $submitSection = Section::where('form_id', $form->id)
            ->where('section_name', 'Kirim Formulir')
            ->first();

        return view('admin.tracerStudy.editTracerStudy', compact('form', 'typeQuestions', 'submitSection'));
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            // Validasi input
            $request->validate([
                'judul_form' => 'required|string|max:255',
                'deskripsi_form' => 'nullable|string',
                'sections' => 'required|array',
            ]);

            $form = Form::with('sections.questions.options')->findOrFail($id);
            $form->update([
                'judul_form' => $request->judul_form,
                'deskripsi_form' => $request->deskripsi_form,
            ]);

            $submitSection = Section::where('form_id', $form->id)
                ->where('section_name', 'Kirim Formulir')
                ->first();
            if (!$submitSection) {
                $submitSection = Section::create([
                    'form_id' => $form->id,
                    'section_name' => 'Kirim Formulir',
                    'section_order' => 999,
                ]);
            }
            $submitSectionId = $submitSection->id;

            $existingSectionIds = $form->sections->pluck('id')->toArray();
            $sectionIdMap = [];

            // Proses section dan buat pemetaan indeks ke ID
            foreach ($request->sections as $sectionIndex => $sectionData) {
                $sectionId = $sectionData['id'] ?? null;
                if ($sectionId && in_array($sectionId, $existingSectionIds)) {
                    $section = Section::find($sectionId);
                    $section->update([
                        'section_name' => $sectionData['section_name'],
                        'section_order' => $sectionIndex,
                    ]);
                    $key = array_search($sectionId, $existingSectionIds);
                    if ($key !== false) {
                        unset($existingSectionIds[$key]);
                    }
                } else {
                    $section = Section::create([
                        'form_id' => $form->id,
                        'section_name' => $sectionData['section_name'],
                        'section_order' => $sectionIndex,
                    ]);
                }
                $sectionIdMap[(string)$sectionIndex] = $section->id; // Gunakan string sebagai kunci
            }

            // Debugging: Periksa $sectionIdMap
            // dd($sectionIdMap);

            // Proses pertanyaan dan opsi
            foreach ($request->sections as $sectionIndex => $sectionData) {
                if (isset($sectionData['questions']) && is_array($sectionData['questions'])) {
                    $section = Section::where('form_id', $form->id)
                        ->where('section_order', $sectionIndex)
                        ->first();
                    $existingQuestionIds = $section->questions->pluck('id')->toArray();

                    foreach ($sectionData['questions'] as $questionIndex => $questionData) {
                        $questionId = $questionData['id'] ?? null;
                        $question = $questionId ? Question::find($questionId) : new Question();
                        $question->section_id = $section->id;
                        $question->question_body = $questionData['question_body'];
                        $question->type_question_id = $questionData['type_question_id'];
                        $question->is_required = isset($questionData['is_required']) ? 1 : 0;
                        $question->question_order = $questionIndex;
                        $question->save();

                        if ($questionId) {
                            $key = array_search($questionId, $existingQuestionIds);
                            if ($key !== false) {
                                unset($existingQuestionIds[$key]);
                            }
                        }

                        if (isset($questionData['options']) && is_array($questionData['options'])) {
                            $existingOptionIds = $question->options->pluck('id')->toArray();
                            foreach ($questionData['options'] as $optionIndex => $optionData) {
                                $optionId = $optionData['id'] ?? null;
                                $labelAngka = $questionData['type_question_id'] == 6 && isset($optionData['label_angka'])
                                    ? $optionData['label_angka']
                                    : null;

                                $nextSectionId = null;
                                if ($questionData['type_question_id'] == 3 && isset($optionData['next_section_id'])) {
                                    $nextSectionValue = (string)$optionData['next_section_id']; // Pastikan string
                                    if ($nextSectionValue === '999') {
                                        $nextSectionId = $submitSectionId;
                                    } elseif (!empty($nextSectionValue) && array_key_exists($nextSectionValue, $sectionIdMap)) {
                                        $nextSectionId = $sectionIdMap[$nextSectionValue];
                                    }
                                }

                                $option = $optionId ? Option::find($optionId) : new Option();
                                $option->question_id = $question->id;
                                $option->option_body = $optionData['option_body'];
                                $option->next_section_id = $nextSectionId;
                                $option->option_order = $optionIndex;
                                $option->label_angka = $labelAngka;
                                $option->save();

                                if ($optionId) {
                                    $key = array_search($optionId, $existingOptionIds);
                                    if ($key !== false) {
                                        unset($existingOptionIds[$key]);
                                    }
                                }
                            }
                            Option::whereIn('id', $existingOptionIds)->delete();
                        }
                    }
                    Question::whereIn('id', $existingQuestionIds)->delete();
                }
            }

            Section::whereIn('id', $existingSectionIds)->delete();

            if ($request->has('deleted_options') && !empty($request->input('deleted_options'))) {
                $deletedOptionIds = array_filter(explode(',', $request->input('deleted_options')));
                Option::whereIn('id', $deletedOptionIds)->delete();
            }

            DB::commit();
            Alert::success('Success', 'Form berhasil diperbaharui!');
            return redirect()->route('admin.tracerStudy.tracerStudy');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['msg' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $form = Form::findOrFail($id);
            $form->delete();
            return response()->json(['message' => 'Form berhasil dihapus!'], 200);
        } catch (\Exception $e) {

            return response()->json(['error' => 'Terjadi kesalahan saat menghapus.'], 500);
        }
    }

    public function getSections($formId)
    {
        $sections = Section::where('form_id', $formId)->orderBy('section_order')->get();
        return response()->json($sections);
    }

    public function getAvailableSections($formId, $currentSectionId)
    {
        $currentSection = Section::findOrFail($currentSectionId);
        $sections = Section::where('form_id', $formId)
            ->where('section_order', '>', $currentSection->section_order)
            ->orderBy('section_order')
            ->get();

        return response()->json($sections);
    }
}
