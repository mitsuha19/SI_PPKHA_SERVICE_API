<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\BerandaController;
use App\Http\Controllers\Api\BeritaController;
use App\Http\Controllers\Api\ArtikelController;
use App\Http\Controllers\Api\LowonganController;
use App\Http\Controllers\Api\PengumumanController;
use App\Http\Controllers\Api\PerusahaanController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');

Route::get('/external-token', [AuthController::class, 'getExternalApiToken']);

// Route::apiResource('/pengumuman', PengumumanController::class);

Route::get('pengumuman', [PengumumanController::class, 'index']);
Route::get('pengumuman/{id}', [PengumumanController::class, 'show']);

Route::middleware('jwt.auth')->group(function () {
    Route::post('pengumuman', [PengumumanController::class, 'store']);
    Route::put('pengumuman/{id}', [PengumumanController::class, 'update']);
    Route::delete('pengumuman/{id}', [PengumumanController::class, 'destroy']);
});

//Route::apiResource('/berita', BeritaController::class);
Route::get('berita', [BeritaController::class, 'index']);
Route::get('berita/{id}', [BeritaController::class, 'show']);
Route::get('/gambar/{id}/{filename}', [BeritaController::class, 'getGambar']);
Route::get('/lampiran/{id}/{filename}', [PengumumanController::class, 'getLampiran']);

Route::middleware('jwt.auth')->group(function () {
    Route::post('berita', [BeritaController::class, 'store']);
    Route::put('berita/{id}', [BeritaController::class, 'update']);
    Route::delete('berita/{id}', [BeritaController::class, 'destroy']);
    Route::get('/gambar/{id}/{filename}', [BeritaController::class, 'getGambar']);

    Route::post('/lowongan', [LowonganController::class, 'store']);
    Route::put('/lowongan/{id}', [LowonganController::class, 'update']);
    Route::delete('/lowongan/{id}', [LowonganController::class, 'destroy']);
});

Route::get('/lowongan', [LowonganController::class, 'index']);
Route::get('/lowongan/{id}', [LowonganController::class, 'show']);
Route::get('/lowongan/{id}/logo', [LowonganController::class, 'getLogoPerusahaan']);

//Route::apiResource('/berita', BeritaController::class);
Route::get('artikel', [ArtikelController::class, 'index']);
Route::get('artikel/{id}', [ArtikelController::class, 'show']);
Route::get('/gambar/{id}/{filename}', [ArtikelController::class, 'getGambar']);

Route::middleware('jwt.auth')->group(function () {
    Route::post('artikel', [ArtikelController::class, 'store']);
    Route::put('artikel/{id}', [ArtikelController::class, 'update']);
    Route::delete('artikel/{id}', [ArtikelController::class, 'destroy']);
    Route::get('/gambar/{id}/{filename}', [ArtikelController::class, 'getGambar']);

    Route::post('/perusahaan', [PerusahaanController::class, 'store']);
    Route::put('/perusahaan/{id}', [PerusahaanController::class, 'update']);
    Route::delete('/perusahaan/{id}', [PerusahaanController::class, 'destroy']);
});

Route::get('/perusahaan', [PerusahaanController::class, 'index']);
Route::get('/perusahaan/{id}', [PerusahaanController::class, 'show']);
Route::get('/perusahaan/{id}/logo', [PerusahaanController::class, 'getLogo']);


Route::get('/beranda', [BerandaController::class, 'index']);

Route::middleware('jwt.auth')->group(function () {
    Route::put('/beranda', [BerandaController::class, 'update']);
});
