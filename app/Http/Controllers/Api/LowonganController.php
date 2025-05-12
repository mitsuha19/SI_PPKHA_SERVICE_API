<?php

namespace App\Http\Controllers\Api;

use App\Models\Lowongan;
use App\Models\Perusahaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\ApiResource;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;


class LowonganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lowongan = Lowongan::with('perusahaan')
            ->orderBy('created_at', 'desc')
            ->get();

        return new ApiResource(true, 'List of Lowongan', $lowongan);
    }

    public function store(Request $request)
    {
        $claims = $request->attributes->get('jwt_claims');
        $userId = $claims['sub'];   // id user dari token
        $role = $claims['role'];  // role dari token

        if ($role !== 'admin') {
            return response()->json(['error' => 'Forbidden'], 403);
        }


        $validatedData = $request->validate([
            'judulLowongan' => 'required|string',
            'jenisLowongan' => 'required|string',
            'tipeLowongan' => 'required|string',
            'deskripsiLowongan' => 'required|string',
            'kualifikasi' => 'required|string',
            'benefit' => 'required|string',
            'keahlian' => 'required|array',
            'batasMulai' => 'required|date',
            'batasAkhir' => 'required|date',
            'namaPerusahaan' => 'required|string',
            'namaPerusahaanBaru' => 'nullable|string|unique:perusahaan,namaPerusahaan',
            'lokasiPerusahaan' => 'nullable|string',
            'websitePerusahaan' => 'nullable|url',
            'industriPerusahaan' => 'nullable|string',
            'deskripsiPerusahaan' => 'nullable|string',
            'logo' => 'nullable|file|mimes:jpg,jpeg,png,svg|max:2048',
        ]);

        try {
            DB::beginTransaction();

            if ($validatedData['namaPerusahaan'] === 'Other') {
                $request->validate([
                    'namaPerusahaanBaru' => 'required|string|unique:perusahaan,namaPerusahaan',
                    'lokasiPerusahaan' => 'required|string',
                    'websitePerusahaan' => 'nullable|url',
                    'industriPerusahaan' => 'required|string',
                    'deskripsiPerusahaan' => 'required|string',
                    'logo' => 'nullable|file|mimes:jpg,jpeg,png,svg|max:2048'
                ]);

                $perusahaan = Perusahaan::create([
                    'namaPerusahaan' => $request->input('namaPerusahaanBaru'),
                    'lokasiPerusahaan' => $request->input('lokasiPerusahaan'),
                    'websitePerusahaan' => $request->input('websitePerusahaan'),
                    'industriPerusahaan' => $request->input('industriPerusahaan'),
                    'deskripsiPerusahaan' => $request->input('deskripsiPerusahaan'),
                    'logo' => $request->file('logo') ? $request->file('logo')->store('logos', 'public') : null,
                ]);
            } else {
                $perusahaan = Perusahaan::where('namaPerusahaan', $validatedData['namaPerusahaan'])->firstOrFail();
            }

            $lowongan = Lowongan::create([
                'judulLowongan' => $validatedData['judulLowongan'],
                'jenisLowongan' => $validatedData['jenisLowongan'],
                'tipeLowongan' => $validatedData['tipeLowongan'],
                'deskripsiLowongan' => $validatedData['deskripsiLowongan'],
                'kualifikasi' => $validatedData['kualifikasi'],
                'benefit' => $validatedData['benefit'],
                'keahlian' => implode(',', $validatedData['keahlian']),
                'batasMulai' => $validatedData['batasMulai'],
                'batasAkhir' => $validatedData['batasAkhir'],
                'perusahaan_id' => $perusahaan->id,
            ]);

            DB::commit();

            return new ApiResource(true, 'Lowongan berhasil dibuat', $lowongan->load('perusahaan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return new ApiResource(false, 'Terjadi kesalahan saat membuat lowongan', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $lowongan = Lowongan::with('perusahaan')->find($id);

        if (!$lowongan) {
            return response()->json([
                'success' => false,
                'message' => 'Lowongan not found'
            ], 404);
        }

        return new ApiResource(true, 'Lowongan detail', $lowongan);
    }

    public function update(Request $request, $id)
    {
        $claims = $request->attributes->get('jwt_claims');
        $role = $claims['role'] ?? null;

        if ($role !== 'admin') {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $validatedData = $request->validate([
            'judulLowongan' => 'required|string',
            'jenisLowongan' => 'required|string',
            'tipeLowongan' => 'required|string',
            'deskripsiLowongan' => 'required|string',
            'kualifikasi' => 'required|string',
            'benefit' => 'required|string',
            'keahlian' => 'nullable|array',
            'batasMulai' => 'required|date',
            'batasAkhir' => 'required|date',
        ]);

        try {
            DB::beginTransaction();

            $lowongan = Lowongan::findOrFail($id);

            $lowongan->update([
                'judulLowongan' => $validatedData['judulLowongan'],
                'jenisLowongan' => $validatedData['jenisLowongan'],
                'tipeLowongan' => $validatedData['tipeLowongan'],
                'deskripsiLowongan' => $validatedData['deskripsiLowongan'],
                'kualifikasi' => $validatedData['kualifikasi'],
                'benefit' => $validatedData['benefit'],
                'keahlian' => isset($validatedData['keahlian']) ? implode(',', $validatedData['keahlian']) : null,
                'batasMulai' => $validatedData['batasMulai'],
                'batasAkhir' => $validatedData['batasAkhir'],
            ]);

            DB::commit();

            return new ApiResource(true, 'Lowongan berhasil diperbarui', $lowongan);
        } catch (\Exception $e) {
            DB::rollBack();
            return new ApiResource(false, 'Terjadi kesalahan saat memperbarui lowongan', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $claims = request()->attributes->get('jwt_claims');
        $role = $claims['role'] ?? null;

        if ($role !== 'admin') {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        try {
            $lowongan = Lowongan::findOrFail($id);
            $lowongan->delete();

            return new ApiResource(true, 'Lowongan berhasil dihapus', null);
        } catch (\Exception $e) {
            return new ApiResource(false, 'Terjadi kesalahan saat menghapus lowongan', $e->getMessage());
        }
    }

    public function getLogoPerusahaan($id)
    {
        Log::info('Fetching logo for perusahaan_id: ' . $id);

        $perusahaan = Perusahaan::findOrFail($id);

        if (!$perusahaan || !$perusahaan->logo) {
            Log::error('Logo not found for perusahaan_id: ' . $id);
            return response()->json(['message' => 'Logo tidak ditemukan'], 404);
        }

        $logoPath = public_path($perusahaan->logo);
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
            ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
    }
}
