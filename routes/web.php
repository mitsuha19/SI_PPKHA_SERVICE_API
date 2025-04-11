<?php

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ArtikelController;
use App\Http\Controllers\LowonganController;
use App\Http\Controllers\KuesionerController;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\PerusahaanController;
use App\Http\Controllers\UserSurveyController;
use App\Http\Controllers\TracerStudyController;

/*
|--------------------------------------------------------------------------
| Public Routes (PPKHA)
|--------------------------------------------------------------------------
*/

// Home & other public pages

Route::get('/', [BerandaController::class, 'index'])->name('ppkha.beranda');

// Overwriting /berita route to show user-specific data:
Route::get('/berita', [BeritaController::class, 'showBeritaUser'])->name('ppkha.berita');
Route::get('/berita/detail/{id}', [BeritaController::class, 'showBeritaDetailUser'])->name('ppkha.detailBerita');

Route::get('/pengumuman', [PengumumanController::class, 'showPengumumanUser'])->name('ppkha.pengumuman');
Route::get('/pengumuman/detail', fn() => view('ppkha.detailPengumuman'));
Route::get('pengumuman/detail/{id}', [PengumumanController::class, 'showPengumumanDetailUser'])->name('ppkha.pengumumanDetail');

Route::get('/artikel', [ArtikelController::class, 'showArtikelUser'])->name('ppkha.artikel');
Route::get('/artikel/detail/{id}', [ArtikelController::class, 'showArtikelDetailUser'])->name('ppkha.detailArtikel');

Route::get('/daftar_perusahaan', [PerusahaanController::class, 'showPerusahaanUser'])->name('ppkha.daftarPerusahaan');
Route::get('/daftar_perusahaan/detail/{id}', [PerusahaanController::class, 'showPerusahaanDetailUser'])->name('ppkha.daftarPerusahaanDetail');

Route::get('/lowongan_pekerjaan', [LowonganController::class, 'showLowonganUser'])->name('ppkha.lowonganPekerjaan');
Route::get('/lowongan_pekerjaan/detail/{id}', [LowonganController::class, 'showLowonganDetailUser'])->name('ppkha.lowonganPekerjaanDetail');
Route::get('/lowongan_pekerjaan/detail', fn() => view('ppkha.detaillowongan'));

// tracer_study route accessible only to authenticated users with role "admin" or "alumni"
Route::get('/tracer_study', fn() => view('ppkha.tracer_study'))
    ->middleware(['auth', 'role:admin|alumni']);

Route::get('/user-survey', [UserSurveyController::class, 'showSurvey'])->name('survey.show');
Route::post('/user-survey/submit', [UserSurveyController::class, 'submit'])->name('survey.submit');
Route::get('/tentang', fn() => view('ppkha.tentang'));

/*
|--------------------------------------------------------------------------
| Admin Routes (Protected)
|--------------------------------------------------------------------------
*/

Route::group([
    'prefix' => 'admin',
    'middleware' => ['auth', 'role:admin']
], function () {

    Route::get('/beranda', [BerandaController::class, 'showBerandaAdmin'])->name('beranda.edit');
    Route::put('/beranda', [BerandaController::class, 'update'])->name('beranda.update');

    // Dashboard
    Route::get('/', [ReportController::class, 'showTracerStudyStats'])->name('admin.dashboard');

    // Berita routes
    Route::prefix('berita')->group(function () {
        Route::get('/', [BeritaController::class, 'index2'])->name('admin.berita.berita');
        Route::post('/', [BeritaController::class, 'store'])->name('admin.berita.store');
        Route::get('/tambah', [BeritaController::class, 'index3'])->name('admin.berita.add');
        Route::get('/detail/{id}', [BeritaController::class, 'show1'])->name('admin.berita.beritaDetail');
        Route::get('/{id}/edit', [BeritaController::class, 'index4'])->name('admin.berita.beritaEdit');
        Route::put('/{id}', [BeritaController::class, 'update'])->name('berita.update');
        Route::delete('/{id}', [BeritaController::class, 'destroy'])->name('admin.berita.destroy');
    });

    // Pengumuman routes
    Route::prefix('pengumuman')->name('admin.pengumuman.')->group(function () {
        Route::get('/', [PengumumanController::class, 'index'])->name('index');
        Route::get('/create', [PengumumanController::class, 'create'])->name('create');
        Route::post('/store', [PengumumanController::class, 'store'])->name('store');
        Route::get('/{id}', [PengumumanController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [PengumumanController::class, 'edit'])->name('edit');
        Route::put('/{id}', [PengumumanController::class, 'update'])->name('update');
        Route::delete('/{id}', [PengumumanController::class, 'destroy'])->name('destroy');
    });

    // Artikel routes
    Route::prefix('artikel')->name('admin.artikel.')->group(function () {
        Route::get('/', [ArtikelController::class, 'showArtikelAdmin'])->name('artikel');
        Route::get('/create', [ArtikelController::class, 'createArtikel'])->name('create');
        Route::post('/', [ArtikelController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [ArtikelController::class, 'showArtikelEditAdmin'])->name('artikelEdit');
        Route::put('/{id}', [ArtikelController::class, 'update'])->name('update');
        Route::delete('/{id}', [ArtikelController::class, 'destroy'])->name('destroy');
        Route::get('/{id}', [ArtikelController::class, 'show'])->name('show');
    });

    // Additional Artikel pages
    Route::get('/artikel/add', fn() => view('admin.artikel.artikelAdd'));
    Route::get('/artikel/detail', fn() => view('admin.artikel.artikelDetail'));
    Route::get('/artikel/edit', fn() => view('admin.artikel.artikelEdit'));

    // daftar perusahaan
    Route::prefix('daftar-perusahaan')->group(function () {
        Route::get('/', [PerusahaanController::class, 'index2'])->name('admin.daftarPerusahaan.daftarPerusahaan');
        Route::post('/', [PerusahaanController::class, 'store'])->name('admin.perusahaan.store');
        Route::get('/detail/{id}', [PerusahaanController::class, 'show1'])->name('admin.daftarPerusahaan.daftarPerusahaanDetail');
        Route::get('/{id}/edit', [PerusahaanController::class, 'index4'])->name('admin.perusahaan.edit');
        Route::put('/{id}', [PerusahaanController::class, 'update'])->name('perusahaan.update');
        Route::delete('/{id}', [PerusahaanController::class, 'destroy'])->name('admin.daftarPerusahaan.destroy');
    });


    //lowongan pekerjaan
    Route::prefix('lowongan-pekerjaan')->group(function () {
        Route::get('/', [LowonganController::class, 'index2'])->name('admin.lowonganPekerjaan.lowonganPekerjaan');
        Route::post('/', [LowonganController::class, 'store'])->name('admin.lowonganPekerjaan.store');
        Route::get('/add', [LowonganController::class, 'index3'])->name('admin.lowonganPekerjaan.add');
        Route::get('/detail/{id}', [LowonganController::class, 'show1'])->name('admin.lowonganPekerjaan.lowonganPekerjaanDetail');
        Route::get('/{id}/edit', [LowonganController::class, 'index4'])->name('admin.lowonganPekerjaan.edit');
        Route::put('/{id}', [LowonganController::class, 'update'])->name('lowongan.update');
        Route::delete('/{id}', [LowonganController::class, 'destroy'])->name('admin.lowonganPekerjaan.destroy');
    });

    // Tracer Study routes
    Route::get('/tracer-study', [TracerStudyController::class, 'mainTracerStudy'])->name('admin.tracerStudy.tracerStudy');
    Route::get('/tracer-study/create', [TracerStudyController::class, 'createTracerStudy']);
    Route::post('/tracer-study/create', [TracerStudyController::class, 'store'])->name('admin.forms.store');
    Route::delete('/tracer-study/delete/{id}', [TracerStudyController::class, 'destroy'])->name('tracer-study.destroy');
    Route::get('/tracer-study/edit/{id}', [TracerStudyController::class, 'edit'])->name('admin.forms.edit');
    Route::put('/tracer-study/edit/{id}', [TracerStudyController::class, 'update'])->name('admin.forms.update');
    Route::resource('forms', TracerStudyController::class);
    Route::get('forms/{formId}/sections', [TracerStudyController::class, 'getSections']);
    Route::get('forms/{formId}/sections/{sectionId}/available', [TracerStudyController::class, 'getAvailableSections']);

    // User Survey
    Route::prefix('/user-survey')->name('admin.surveys.')->group(function () {
        Route::get('/', [UserSurveyController::class, 'mainSurvey'])->name('survey');
        Route::get('/create', [UserSurveyController::class, 'createSurvey'])->name('create');
        Route::post('/store', [UserSurveyController::class, 'store'])->name('store');
        Route::post('/update-all', [UserSurveyController::class, 'updateAll'])->name('update.all');
        Route::delete('/destroy/{id}', [UserSurveyController::class, 'destroy'])->name('destroy');
    });

    // Download CSV
    Route::get('/admin/unduh-data/csv/{formId}', [ReportController::class, 'unduhTracerStudyCSV'])
        ->name('admin.unduh.csv');
    Route::get('/admin/unduh-survey/csv/{surveySectionId}', [ReportController::class, 'unduhUserSurveyCSV'])
        ->name('admin.unduh.survey.csv');
});

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
Route::view('/register', 'auth.register')->name('register');
Route::view('/login', 'auth.login')->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Other Routes
|--------------------------------------------------------------------------
*/

// Form Kuisioner routes
Route::post('/forms', [FormController::class, 'store'])->name('forms.store');
Route::get('/forms', [FormController::class, 'index'])->name('forms.index');

// Test Kuisioner for User
Route::get('/kuesioner', [KuesionerController::class, 'show'])->name('kuesioner.show')
    ->middleware(['auth', 'role:admin|alumni']);
Route::post('/kuesioner/next/{sectionId}', [KuesionerController::class, 'nextSection'])->name('kuesioner.next');
Route::get('/kuesioner/previous/{sectionId}', [KuesionerController::class, 'previousSection'])->name('kuesioner.previous');
Route::get('/kuesioner/submit', [KuesionerController::class, 'submit'])->name('kuesioner.submit');

// Route Kuesioner
Route::get('/report-show', [ReportController::class, 'view'])->name('report-show');
Route::get('/report', [ReportController::class, 'viewCards'])->name('report');
// Route::get('/report-2021', function () {
//     return redirect()->route('report')->with('error', 'Report belum tersedia');
// })->name('report.2021');
// Route::get('/report-2022', function () {
//     return redirect()->route('report')->with('error', 'Report belum tersedia');
// })->name('report.2022');
// Route::get('/get-prodi', [ReportController::class, 'getProdi'])->name('report.get-prodi');
// Route::get('/get-tahun-angkatan', [ReportController::class, 'getAngkatan'])->name('report.get-tahun-angkatan');
// Route::get('/get-report', [ReportController::class, 'getReport'])->name('report.get-report');

// Proxy routes for external API data
Route::get('/proxy/provinces', function () {
    try {
        $response = Http::withOptions(["verify" => false])
            ->get('https://emsifa.github.io/api-wilayah-indonesia/api/provinces.json');

        if ($response->failed()) {
            return response()->json(['error' => 'Gagal mengambil data dari API eksternal'], 500);
        }

        return response($response->body())->header('Content-Type', 'application/json');
    } catch (\Exception $e) {
        return response()->json(['error' => 'Server Error: ' . $e->getMessage()], 500);
    }
});

Route::get('/proxy/regencies/{provinceId}', function ($provinceId) {
    try {
        $response = Http::withOptions(["verify" => false])
            ->get("https://emsifa.github.io/api-wilayah-indonesia/api/regencies/$provinceId.json");

        if ($response->failed()) {
            return response()->json(['error' => 'Gagal mengambil data dari API eksternal'], 500);
        }

        return response($response->body())->header('Content-Type', 'application/json');
    } catch (\Exception $e) {
        return response()->json(['error' => 'Server Error: ' . $e->getMessage()], 500);
    }
});
