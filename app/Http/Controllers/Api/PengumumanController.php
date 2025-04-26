<?php

namespace App\Http\Controllers\Api;

use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\PengumumanResource;

class PengumumanController extends Controller
{
    public function index()
    {
        $pengumuman = Pengumuman::latest()->get();

        return new ApiResource(true, "List Data Pengumuman", $pengumuman);
    }

    public function getLampiran($id, $filename)
    {
        $pengumuman = Pengumuman::findOrFail($id);
        $lampiran = json_decode($pengumuman->lampiran, true) ?? [];

        foreach ($lampiran as $file) {
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
        $userId = $claims['sub'];   // id user dari token
        $role   = $claims['role'];  // role dari token

        if ($role !== 'admin') {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $validator = Validator::make($request->all(), [
            'judul_pengumuman' => 'required|string',
            'deskripsi_pengumuman' => 'required|string',
            'lampiran.*' => 'file|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $lampiranPaths = [];

        if ($request->hasFile('lampiran')) {
            foreach ($request->file('lampiran') as $file) {
                $filename = uniqid() . '_' . $file->getClientOriginalName();
                $path = $file->move(public_path('lampiran_pengumuman'), $filename); // ⬅️ langsung ke public/
                $lampiranPaths[] = 'lampiran_pengumuman/' . $filename;
            }
        }

        $pengumuman = Pengumuman::create([
            'judul_pengumuman' => $request->judul_pengumuman,
            'deskripsi_pengumuman' => $request->deskripsi_pengumuman,
            'lampiran' => count($lampiranPaths) > 0 ? json_encode($lampiranPaths) : null,
        ]);

        return new ApiResource(true, "Data Pengumuman Berhasil ditambahkan", $pengumuman);
    }



    public function show($id)
    {
        //find post by ID
        $pengumuman = Pengumuman::find($id);

        //return single post as a resource
        return new ApiResource(true, 'Detail Data penguman!', $pengumuman);
    }

    public function update(Request $request, $id)
    {
        $claims = $request->attributes->get('jwt_claims');
        $role = $claims['role'];

        if ($role !== 'admin') {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $pengumuman = Pengumuman::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'judul_pengumuman' => 'required|string',
            'deskripsi_pengumuman' => 'required|string',
            'lampiran.*' => 'file|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $pengumuman->judul_pengumuman = $request->judul_pengumuman;
        $pengumuman->deskripsi_pengumuman = $request->deskripsi_pengumuman;

        // Tambahkan file baru ke lampiran lama tanpa menyimpan ke storage
        if ($request->hasFile('lampiran')) {
            $lampiranPaths = [];
            foreach ($request->file('lampiran') as $file) {
                $filename = uniqid() . '_' . $file->getClientOriginalName();
                $path = $file->move(public_path('lampiran_pengumuman'), $filename);
                $lampiranPaths[] = 'lampiran_pengumuman/' . $filename;
            }
            $pengumuman->lampiran = json_encode($lampiranPaths);
        }


        // Simpan perubahan pengumuman
        $pengumuman->save();

        // Return response sukses
        return new ApiResource(true, 'Data Pengumuman berhasil diperbarui', $pengumuman);
    }

    public function destroy(Request $request, $id)
    {
        $claims = $request->attributes->get('jwt_claims');
        $role = $claims['role'];  // role dari token

        if ($role !== 'admin') {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $pengumuman = Pengumuman::find($id);

        if (!$pengumuman) {
            return response()->json([
                'success' => false,
                'message' => 'Data Pengumuman tidak ditemukan',
            ], 404);
        }
        $pengumuman->delete();

        return new ApiResource(true, 'Data Pengumuman berhasil dihapus', null);
    }
}
