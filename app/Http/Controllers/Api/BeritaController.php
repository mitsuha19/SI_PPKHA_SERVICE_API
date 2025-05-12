<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Http\Resources\ApiResource;
use Illuminate\Support\Facades\Storage;


class BeritaController extends Controller
{
    public function index()
    {
        $berita = Berita::latest()->get();

        return new ApiResource(true, 'List Data Berita', $berita);
    }

    public function show($id)
    {
        $berita = Berita::find($id);

        return new ApiResource(true, 'Detail Data berita!', $berita);
    }

    public function getGambar($id, $filename)
    {
        $berita = Berita::findOrFail($id);
        $gambar = json_decode($berita->gambar, true) ?? [];

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


    public function store(Request $request)
    {
        $claims = $request->attributes->get('jwt_claims');
        $userId = $claims['sub'];
        $role   = $claims['role'];

        if ($role !== 'admin') {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $validator = Validator::make($request->all(), [
            'judul_berita' => 'required|string',
            'deskripsi_berita' => 'required|string',
            'gambar.*' => 'nullable|file|mimes:jpg,jpeg,png',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $gambarPaths = [];

        if ($request->hasFile('gambar')) {
            foreach ($request->file('gambar') as $gambar) {
                $filename = uniqid() . '_' . $gambar->getClientOriginalName();
                $path = $gambar->move(public_path('gambar_berita'), $filename);
                $gambarPaths[] = 'gambar_berita/' . $filename;
            }
        }


        $berita = Berita::create([
            'judul_berita' => $request->judul_berita,
            'deskripsi_berita' => $request->deskripsi_berita,
            'gambar' => count($gambarPaths) > 0 ? json_encode($gambarPaths) : null,
        ]);

        return new ApiResource(true, 'Data Berhasil Disimpan', $berita);
    }




    public function update(Request $request, $id)
    {
        $claims = $request->attributes->get('jwt_claims');
        $role = $claims['role'];

        if ($role !== 'admin') {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $berita = Berita::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'judul_berita' => 'required|string',
            'deskripsi_berita' => 'required|string',
            'gambar.*' => 'nullable|file|mimes:jpg,jpeg,png',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $berita->judul_berita = $request->judul_berita;
        $berita->deskripsi_berita = $request->deskripsi_berita;

        if ($request->hasFile('gambar')) {
            $gambarPaths = [];

            foreach ($request->file('gambar') as $gambar) {
                $filename = uniqid() . '_' . $gambar->getClientOriginalName();
                $gambar->move(public_path('gambar_berita'), $filename);
                $gambarPaths[] = 'gambar_berita/' . $filename;
            }

            $gambarLama = json_decode($berita->gambar, true) ?? [];
            $berita->gambar = json_encode(array_merge($gambarLama, $gambarPaths));
        }

        $berita->save();

        return new ApiResource(true, 'Data berhasil diperbarui', $berita);
    }



    public function destroy(Request $request, $id)
    {
        $claims = $request->attributes->get('jwt_claims');
        $role = $claims['role'];  // role dari token

        if ($role !== 'admin') {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $berita = Berita::find($id);

        if (!$berita) {
            return response()->json([
                'success' => false,
                'message' => 'Berita tidak ditemukan'
            ], 404);
        }

        $berita->delete();

        return new ApiResource(true, 'Data  berhasil dihapus', null);
    }
}
