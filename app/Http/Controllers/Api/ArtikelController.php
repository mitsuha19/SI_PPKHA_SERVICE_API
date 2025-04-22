<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Artikel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ApiResource;


class ArtikelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $artikels = Artikel::latest()->get();
        return new ApiResource(true, 'List Data Artikel', $artikels);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul_artikel' => 'required|string',
            'deskripsi_artikel' => 'required|string',
            'sumber_artikel' => 'nullable|url',
            'gambar.*' => 'nullable|file|mimes:jpg,jpeg,png',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $validatedData = $validator->validated();

        $gambarPaths = [];

        if ($request->hasFile('gambar')) {
            foreach ($request->file('gambar') as $file) {
                $filename = uniqid() . '_' . $file->getClientOriginalName();
                $file->move(public_path('gambar_artikel'), $filename);
                $gambarPaths[] = 'gambar_artikel/' . $filename;
            }
        }

        $artikel = Artikel::create([
            'judul_artikel' => $validatedData['judul_artikel'],
            'deskripsi_artikel' => $validatedData['deskripsi_artikel'],
            'sumber_artikel' => $validatedData['sumber_artikel'] ?? null,
            'gambar' => count($gambarPaths) > 0 ? json_encode($gambarPaths) : null,
        ]);

        return new ApiResource(true, 'Sukses menambahkan data', $artikel);
    }


    public function getGambar($id, $filename)
    {
        $artikels = Artikel::findOrFail($id);
        $gambar = json_decode($artikels->gambar, true) ?? [];

        foreach ($gambar as $file) {
            if ($file['nama_file'] === $filename) {
                $decoded = base64_decode($file['isi_base64']);

                return response($decoded, 200)
                    ->header('Content-Type', $file['tipe'])
                    ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
            }
        }

        return response()->json(['message' => 'File tidak ditemukan'], 404);
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $artikels = Artikel::find($id);
        if($artikels){
            return response()->json([
                'status' => true,
                'message' => 'Artikel ditemukan',
                'data' => $artikels
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Artikel tidak ditemukan'
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $artikel = Artikel::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'judul_artikel' => 'required|string',
            'deskripsi_artikel' => 'required|string',
            'sumber_artikel' => 'nullable|url',
            'gambar.*' => 'nullable|file|mimes:jpg,jpeg,png',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $artikel->judul_artikel = $request->judul_artikel;
        $artikel->deskripsi_artikel = $request->deskripsi_artikel;
        $artikel->sumber_artikel = $request->sumber_artikel;

        if ($request->hasFile('gambar')) {
            $gambarPaths = [];
            foreach ($request->file('gambar') as $file) {
                $filename = uniqid() . '_' . $file->getClientOriginalName();
                $file->move(public_path('gambar_artikel'), $filename);
                $gambarPaths[] = 'gambar_artikel/' . $filename;
            }

            // Tambahkan ke gambar lama jika ingin menyimpan riwayat
            $gambarLama = json_decode($artikel->gambar, true) ?? [];
            $artikel->gambar = json_encode(array_merge($gambarLama, $gambarPaths));
        }

        $artikel->save();

        return new ApiResource(true, 'Artikel berhasil diperbarui', $artikel);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $artikels = Artikel::find($id);

        if (!$artikels) {
            return response()->json([
                'success' => false,
                'message' => 'Artikel tidak ditemukan'
            ], 404);
        }

        $artikels->delete();

        return new ApiResource(true, 'Artikel  berhasil dihapus', null);
        }
}
