<?php

namespace App\Http\Controllers\Api;

use App\Models\Perusahaan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ApiResource;

class PerusahaanController extends Controller
{
  public function index()
  {
    // Ambil data perusahaan terbaru
    $perusahaan = Perusahaan::latest()->get();

    return new ApiResource(true, "List Data Perusahaan", $perusahaan);
  }

  public function store(Request $request)
  {
    // Validasi input
    $validator = Validator::make($request->all(), [
      'namaPerusahaan' => 'required|string',
      'lokasiPerusahaan' => 'required|string',
      'websitePerusahaan' => 'required|url',
      'industriPerusahaan' => 'required|string',
      'deskripsiPerusahaan' => 'nullable|string',
      'logo' => 'nullable|file|mimes:jpg,jpeg,png'
    ]);

    if ($validator->fails()) {
      return response()->json($validator->errors(), 422);
    }

    // Proses upload logo
    $logoPath = null;
    if ($request->hasFile('logo')) {
      $filename = uniqid() . '_' . $request->file('logo')->getClientOriginalName();
      $path = $request->file('logo')->move(public_path('logo_perusahaan'), $filename);
      $logoPath = 'logo_perusahaan/' . $filename;
    }

    // Simpan data perusahaan ke database
    $perusahaan = Perusahaan::create([
      'namaPerusahaan' => $request->namaPerusahaan,
      'lokasiPerusahaan' => $request->lokasiPerusahaan,
      'websitePerusahaan' => $request->websitePerusahaan,
      'industriPerusahaan' => $request->industriPerusahaan,
      'deskripsiPerusahaan' => $request->deskripsiPerusahaan,
      'logo' => $logoPath,
    ]);

    return new ApiResource(true, "Data Perusahaan Berhasil Ditambahkan", $perusahaan);
  }

  public function show($id)
  {
    // Ambil perusahaan berdasarkan ID
    $perusahaan = Perusahaan::find($id);

    if (!$perusahaan) {
      return response()->json([
        'success' => false,
        'message' => 'Data Perusahaan tidak ditemukan',
      ], 404);
    }

    return new ApiResource(true, 'Detail Data Perusahaan', $perusahaan);
  }

  public function update(Request $request, $id)
  {
    $perusahaan = Perusahaan::findOrFail($id);

    // Validasi input
    $validator = Validator::make($request->all(), [
      'namaPerusahaan' => 'required|string',
      'lokasiPerusahaan' => 'required|string',
      'websitePerusahaan' => 'required|url',
      'industriPerusahaan' => 'required|string',
      'deskripsiPerusahaan' => 'nullable|string',
      'logo' => 'nullable|file|mimes:jpg,jpeg,png'
    ]);

    if ($validator->fails()) {
      return response()->json($validator->errors(), 422);
    }

    // Update data perusahaan
    $perusahaan->namaPerusahaan = $request->namaPerusahaan;
    $perusahaan->lokasiPerusahaan = $request->lokasiPerusahaan;
    $perusahaan->websitePerusahaan = $request->websitePerusahaan;
    $perusahaan->industriPerusahaan = $request->industriPerusahaan;
    $perusahaan->deskripsiPerusahaan = $request->deskripsiPerusahaan;

    // Proses upload logo jika ada perubahan
    if ($request->hasFile('logo')) {
      $filename = uniqid() . '_' . $request->file('logo')->getClientOriginalName();
      $path = $request->file('logo')->move(public_path('logo_perusahaan'), $filename);
      $perusahaan->logo = 'logo_perusahaan/' . $filename;
    }

    // Simpan perubahan
    $perusahaan->save();

    return new ApiResource(true, 'Data Perusahaan Berhasil Diperbarui', $perusahaan);
  }

  public function destroy($id)
  {
    $perusahaan = Perusahaan::find($id);

    if (!$perusahaan) {
      return response()->json([
        'success' => false,
        'message' => 'Data Perusahaan tidak ditemukan',
      ], 404);
    }

    $perusahaan->delete();

    return new ApiResource(true, 'Data Perusahaan Berhasil Dihapus', null);
  }
}
