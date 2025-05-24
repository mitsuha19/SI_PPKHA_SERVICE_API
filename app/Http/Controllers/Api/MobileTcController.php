<?php

namespace App\Http\Controllers\api;

use App\Models\MobileTc;
use Illuminate\Http\Request;
use App\Http\Resources\ApiResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class MobileTcController extends Controller
{
    public function index()
    {
        $items = MobileTc::latest()->get();
        return new ApiResource(true, 'List Data Mobile TC', $items);
    }

    public function store(Request $request)
    {
        // cek role dari JWT
        $claims = $request->attributes->get('jwt_claims');
        $role   = $claims['role'] ?? null;
        if ($role !== 'admin') {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        // validasi input
        $validator = Validator::make($request->all(), [
            'judul'     => 'required|string',
            'deskripsi' => 'required|string',
            'link_url'  => 'nullable|url',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // buat record
        $item = MobileTc::create([
            'judul'      => $request->judul,
            'deskripsi'  => $request->deskripsi,
            'link_url'   => $request->link_url,
        ]);

        return new ApiResource(true, 'Data Mobile TC Berhasil ditambahkan', $item);
    }

    public function show($id)
    {
        $item = MobileTc::find($id);
        if (! $item) {
            return response()->json([
                'success' => false,
                'message' => 'Data Mobile TC tidak ditemukan'
            ], 404);
        }
        return new ApiResource(true, 'Detail Data Mobile TC', $item);
    }

    public function update(Request $request, $id)
    {
        // cek role admin
        $claims = $request->attributes->get('jwt_claims');
        $role   = $claims['role'] ?? null;
        if ($role !== 'admin') {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $item = MobileTc::findOrFail($id);

        // validasi input
        $validator = Validator::make($request->all(), [
            'judul'     => 'required|string',
            'deskripsi' => 'required|string',
            'link_url'  => 'nullable|url',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // assign dan simpan
        $item->judul     = $request->judul;
        $item->deskripsi = $request->deskripsi;
        $item->link_url  = $request->link_url;
        $item->save();

        return new ApiResource(true, 'Data Mobile TC berhasil diperbarui', $item);
    }

    public function destroy(Request $request, $id)
    {
        // cek role admin
        $claims = $request->attributes->get('jwt_claims');
        $role   = $claims['role'] ?? null;
        if ($role !== 'admin') {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $item = MobileTc::find($id);
        if (! $item) {
            return response()->json([
                'success' => false,
                'message' => 'Data Mobile TC tidak ditemukan'
            ], 404);
        }

        $item->delete();

        return new ApiResource(true, 'Data Mobile TC berhasil dihapus', null);
    }
}
