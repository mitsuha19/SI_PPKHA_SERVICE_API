<?php

use App\Http\Controllers\Api\PengumumanController;
use App\Http\Controllers\Api\BeritaController;
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

Route::apiResource('/pengumuman', PengumumanController::class);
Route::apiResource('/berita', BeritaController::class);
Route::get('/gambar/{id}/{filename}', [BeritaController::class, 'getGambar']);
Route::get('/lampiran/{id}/{filename}', [PengumumanController::class, 'getLampiran']);
