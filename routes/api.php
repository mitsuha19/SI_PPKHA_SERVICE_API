<?php

use App\Http\Controllers\Api\ArtikelController;
use App\Http\Controllers\Api\PengumumanController;
use App\Http\Controllers\Api\BeritaController;
use App\Http\Controllers\Api\PerusahaanController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');

Route::get('/external-token', [AuthController::class, 'getExternalApiToken']);

// Route::apiResource('/pengumuman', PengumumanController::class);

Route::get('pengumuman',   [PengumumanController::class, 'index']);
Route::get('pengumuman/{id}', [PengumumanController::class, 'show']);

Route::middleware('jwt.auth')->group(function () {
    Route::post('pengumuman',    [PengumumanController::class, 'store']);
    Route::put('pengumuman/{id}', [PengumumanController::class, 'update']);
    Route::delete('pengumuman/{id}', [PengumumanController::class, 'destroy']);
});

//Route::apiResource('/berita', BeritaController::class);
Route::get('berita', [BeritaController::class, 'index']);
Route::get('berita/{id}', [BeritaController::class, 'show']);
Route::get('/gambar/{id}/{filename}', [BeritaController::class, 'getGambar']);
Route::get('/lampiran/{id}/{filename}', [PengumumanController::class, 'getLampiran']);

Route::middleware('jwt.auth')->group(function () {
    Route::post('berita',    [BeritaController::class, 'store']);
    Route::put('berita/{id}', [BeritaController::class, 'update']);
    Route::delete('berita/{id}', [BeritaController::class, 'destroy']);
    Route::get('/gambar/{id}/{filename}', [BeritaController::class, 'getGambar']);
});


//Route::apiResource('/berita', BeritaController::class);
Route::get('artikel', [ArtikelController::class, 'index']);
Route::get('artikel/{id}', [ArtikelController::class, 'show']);
Route::get('/gambar/{id}/{filename}', [ArtikelController::class, 'getGambar']);

Route::middleware('jwt.auth')->group(function () {
    Route::post('artikel',    [ArtikelController::class, 'store']);
    Route::put('artikel/{id}', [ArtikelController::class, 'update']);
    Route::delete('artikel/{id}', [ArtikelController::class, 'destroy']);
    Route::get('/gambar/{id}/{filename}', [ArtikelController::class, 'getGambar']);
});


Route::apiResource('/perusahaan', PerusahaanController::class);
