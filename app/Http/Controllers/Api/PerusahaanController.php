<?php

namespace App\Http\Controllers\Api;

use App\Models\Perusahaan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ApiResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PerusahaanController extends Controller
{
  public function index()
  {
    // Ambil data perusahaan terbaru
    $perusahaan = Perusahaan::latest()->get();

    return new ApiResource(true, "List Data Perusahaan", $perusahaan);
  }

  // public function store(Request $request)
  // {
  //   // Validasi input
  //   $validator = Validator::make($request->all(), [
  //     'namaPerusahaan' => 'required|string',
  //     'lokasiPerusahaan' => 'required|string',
  //     'websitePerusahaan' => 'required|url',
  //     'industriPerusahaan' => 'required|string',
  //     'deskripsiPerusahaan' => 'nullable|string',
  //     'logo' => 'nullable|file|mimes:jpg,jpeg,png'
  //   ]);

  //   if ($validator->fails()) {
  //     return response()->json($validator->errors(), 422);
  //   }

  //   // Proses upload logo
  //   $logoPath = null;
  //   if ($request->hasFile('logo')) {
  //     $filename = uniqid() . '_' . $request->file('logo')->getClientOriginalName();
  //     $path = $request->file('logo')->move(public_path('logo_perusahaan'), $filename);
  //     $logoPath = 'logo_perusahaan/' . $filename;
  //   }

  //   // Simpan data perusahaan ke database
  //   $perusahaan = Perusahaan::create([
  //     'namaPerusahaan' => $request->namaPerusahaan,
  //     'lokasiPerusahaan' => $request->lokasiPerusahaan,
  //     'websitePerusahaan' => $request->websitePerusahaan,
  //     'industriPerusahaan' => $request->industriPerusahaan,
  //     'deskripsiPerusahaan' => $request->deskripsiPerusahaan,
  //     'logo' => $logoPath,
  //   ]);

  //   return new ApiResource(true, "Data Perusahaan Berhasil Ditambahkan", $perusahaan);
  // }

  public function show($id)
  {
    $perusahaan = Perusahaan::with('lowongan')->find($id);
    if (!$perusahaan) {
      return response()->json(['success' => false, 'message' => 'Data Perusahaan tidak ditemukan'], 404);
    }
    return new ApiResource(true, 'Detail Data Perusahaan', $perusahaan);
  }

  public function update(Request $request, $id)
  {
    Log::info('Request files:', $request->allFiles());
    Log::info('Request data:', $request->all());
    $claims = $request->attributes->get('jwt_claims');
    $role = $claims['role'] ?? null;

    if ($role !== 'admin') {
      return response()->json(['error' => 'Forbidden'], 403);
    }

    $validatedData = $request->validate([
      'namaPerusahaan' => 'nullable|string|max:255',
      'lokasiPerusahaan' => 'nullable|string|max:255',
      'websitePerusahaan' => 'nullable|url',
      'industriPerusahaan' => 'nullable|string|max:255',
      'deskripsiPerusahaan' => 'nullable|string',
      'logo' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
    ]);

    try {
      DB::beginTransaction();

      $perusahaan = Perusahaan::findOrFail($id);

      // Kumpulkan data yang akan diupdate, bandingkan dengan nilai saat ini
      $updateData = [];
      $fields = ['namaPerusahaan', 'lokasiPerusahaan', 'websitePerusahaan', 'industriPerusahaan', 'deskripsiPerusahaan'];
      foreach ($fields as $field) {
        if (isset($validatedData[$field]) && $validatedData[$field] !== $perusahaan->$field) {
          $updateData[$field] = $validatedData[$field];
        }
      }

      if (!empty($updateData)) {
        $perusahaan->update($updateData);
      }

      $hasLogoUpdate = false;
      if ($request->hasFile('logo')) {
        $hasLogoUpdate = true; // Anggap sebagai perubahan sejak ada file yang dikirim
        $logo = $request->file('logo');

        // Log informasi file untuk debugging
        Log::info('Logo File Received:', [
          'name' => $logo->getClientOriginalName(),
          'size' => $logo->getSize(),
          'mime' => $logo->getMimeType(),
          'is_valid' => $logo->isValid(),
        ]);

        if (!$logo->isValid()) {
          throw new \Exception('File logo tidak valid.');
        }

        // Pastikan direktori ada dan dapat ditulis
        $storagePath = storage_path('app/public/logos');
        if (!file_exists($storagePath)) {
          mkdir($storagePath, 0755, true);
        }
        if (!is_writable($storagePath)) {
          throw new \Exception('Direktori penyimpanan tidak dapat ditulis: ' . $storagePath);
        }

        $filename = uniqid() . '_' . $logo->getClientOriginalName();
        $path = $logo->storeAs('logos', $filename, 'public');
        if (!$path) {
          throw new \Exception('Gagal menyimpan file logo.');
        }

        $perusahaan->logo = 'logos/' . $filename;
        $perusahaan->save();

        Log::info('Logo Saved:', ['path' => $path]);
      }

      if ($hasLogoUpdate || !empty($updateData)) {
        DB::commit();
      } else {
        DB::rollBack();
        throw new \Exception('Tidak ada perubahan untuk diperbarui.');
      }

      return new ApiResource(true, 'Data Perusahaan Berhasil Diperbarui', $perusahaan->fresh());
    } catch (\Illuminate\Validation\ValidationException $e) {
      DB::rollBack();
      Log::error('Validation Error: ' . $e->getMessage(), ['errors' => $e->errors()]);
      return new ApiResource(false, 'Validasi gagal: ' . implode(', ', $e->errors()), null);
    } catch (\Exception $e) {
      DB::rollBack();
      Log::error('Update Perusahaan Error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
      return new ApiResource(false, 'Terjadi kesalahan saat memperbarui perusahaan: ' . $e->getMessage(), null);
    }
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

  public function getLogo($id)
  {
    Log::info('Fetching logo for perusahaan_id: ' . $id);

    $perusahaan = Perusahaan::find($id);

    if (!$perusahaan || !$perusahaan->logo) {
      Log::error('Logo not found for perusahaan_id: ' . $id);
      return response()->json(['message' => 'Logo tidak ditemukan'], 404);
    }

    // Use storage_path since logos are stored with store('logos', 'public')
    $logoPath = storage_path('app/public/' . $perusahaan->logo);
    Log::info('Logo path: ' . $logoPath);

    if (!file_exists($logoPath)) {
      Log::error('Logo file does not exist at path: ' . $logoPath);
      return response()->json(['message' => 'File tidak ditemukan'], 404);
    }

    $mimeType = mime_content_type($logoPath);
    $content = file_get_contents($logoPath);
    $filename = basename($logoPath);

    Log::info('Successfully fetched logo for perusahaan_id: ' . $id);
    return response($content, 200)
      ->header('Content-Type', $mimeType)
      ->header('Content-Disposition', 'inline; filename="' . $filename . '"')
      ->header('Access-Control-Allow-Origin', '*');
  }
}
