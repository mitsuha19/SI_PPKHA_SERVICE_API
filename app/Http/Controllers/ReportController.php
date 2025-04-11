<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fakultas;
use App\Models\Prodi;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Models\Answer;


class ReportController extends Controller
{
    public function showTracerStudyStats()
    {
        // 1. Identify the question used for "current status" or tracer study data
        $questionId = 1;  // adjust as needed

        // 2. Get all alumni user IDs (Spatie's role-based approach)
        //    e.g., "alumni" is the name of the role you assigned for alumni
        $alumniUserIds = User::role('alumni')->pluck('id');
        $totalAlumni   = $alumniUserIds->count();

        // 3. Group answers by prodi_id, counting distinct user_ids
        //    We only consider users in $alumniUserIds
        $answers = DB::table('answers')
            ->where('question_id', $questionId)
            ->whereIn('answers.user_id', $alumniUserIds)
            ->join('users', 'answers.user_id', '=', 'users.id')
            ->select('users.prodi_id', DB::raw('COUNT(DISTINCT answers.user_id) as total'))
            ->groupBy('users.prodi_id')
            ->get();

        // Build arrays for chart
        $labels = [];
        $data   = [];
        $answeredCount = 0;

        foreach ($answers as $row) {
            $prodiName = 'Unknown';
            if ($row->prodi_id) {
                // Lookup Prodi name
                $prodi = Prodi::find($row->prodi_id);
                $prodiName = $prodi ? $prodi->name : 'Unknown';
            }
            $labels[] = $prodiName;
            $data[]   = $row->total;
            $answeredCount += $row->total;  // Sum up how many alumni answered
        }

        // Data for the "Jumlah Mahasiswa Tiap Kategori" chart (grouped by prodi)
        $jumlah_mahasiswa_tiap_kategori = [
            'labels' => $labels,
            'data'   => $data,
        ];

        // 4. Mengisi vs Belum Mengisi
        $sudahMengisi = $answeredCount;           // total alumni who answered
        $belumMengisi = $totalAlumni - $answeredCount;

        // Data for "Perbandingan Pengisian Kuesioner"
        $perbandingan_pengisian_questioner = [
            'labels' => ['Mengisi', 'Belum Mengisi'],
            'data'   => [$sudahMengisi, $belumMengisi],
        ];

        // 5. Return view with the chart data and numeric stats
        return view('admin.dashboard', compact(
            'jumlah_mahasiswa_tiap_kategori',
            'perbandingan_pengisian_questioner',
            'totalAlumni',
            'sudahMengisi',
            'belumMengisi'
        ));
    }

    public function unduhTracerStudyCSV($formId)
    {
        // 1. Fetch all answers for questions in the given form.
        $answers = \App\Models\Answer::whereHas('question.section.form', function ($query) use ($formId) {
            $query->where('id', $formId);
        })
            ->with(['question', 'user.fakultas', 'user.prodi'])
            ->get();

        // 2. Get a unique list of question texts.
        $questions = $answers->map(function ($ans) {
            return $ans->question->question_body;
        })->filter()->unique()->values();

        // 3. Build CSV header: fixed columns + question headers.
        $header = array_merge(['Name', 'NIM', 'Fakultas', 'Prodi'], $questions->toArray());

        // 4. Create a unique filename.
        $filename = "tracer_study_{$formId} " . time() . ".csv";
        $handle = fopen($filename, 'w+');

        // Write a UTF-8 BOM to help Excel recognize the file encoding.
        fwrite($handle, "\xEF\xBB\xBF");

        // Write the header row first.
        fputcsv($handle, $header);

        // 5. Group answers by user_id so each user appears only once.
        $groupedData = $answers->groupBy('user_id');
        foreach ($groupedData as $userId => $responses) {
            if (!$userId) continue;
            $firstResponse = $responses->first();
            $user = $firstResponse->user;

            $row = [
                $user->name ?? '',
                $user->nim ?? '',
                $user->fakultas->name ?? '',
                $user->prodi->name ?? '',
            ];

            // For each question header, add the answer from this user.
            foreach ($questions as $questionText) {
                $answer = $responses->firstWhere('question.question_body', $questionText)?->answer_value ?? '';
                $row[] = $answer;
            }
            fputcsv($handle, $row);
        }

        fclose($handle);

        $headers = [
            'Content-Type' => 'text/csv',
        ];

        return response()->download($filename, 'tracer_study_response.csv', $headers)
            ->deleteFileAfterSend(true);
    }

    public function unduhUserSurveyCSV()
    {
        // Generate a unique filename
        $filename = "user_survey" . time() . ".csv";
        $handle = fopen($filename, 'w+');

        // Write a UTF-8 BOM for Excel compatibility (optional)
        fwrite($handle, "\xEF\xBB\xBF");

        // Define the CSV header row
        $header = ['Survey ID', 'Survey Section ID', 'Question', 'Response'];
        fputcsv($handle, $header);

        // Retrieve all SurveyResponse records with their related Survey and SurveySection
        $responses = \App\Models\SurveyResponse::with(['survey', 'surveySection'])->get();

        foreach ($responses as $response) {
            $surveyId = $response->survey_id;
            $surveySectionId = $response->survey_sections_id;
            // Use the survey's question_title for the question text
            $question = $response->survey->question_title ?? '';
            // If response is an array, join into a string; otherwise, cast to string.
            $respValue = is_array($response->response)
                ? implode(', ', $response->response)
                : (string)$response->response;

            $row = [$surveyId, $surveySectionId, $question, $respValue];
            fputcsv($handle, $row);
        }

        fclose($handle);

        $headers = ['Content-Type' => 'text/csv'];

        return response()->download($filename, 'user_survey_response.csv', $headers)
            ->deleteFileAfterSend(true);
    }
}
