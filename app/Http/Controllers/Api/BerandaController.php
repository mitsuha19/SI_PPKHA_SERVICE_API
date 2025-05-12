<?php

namespace App\Http\Controllers\Api;

use App\Models\Beranda;
use Illuminate\Http\Request;
use App\Http\Resources\ApiResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class BerandaController extends Controller
{
    public function index()
    {
        $beranda = Beranda::first();

        if (!$beranda) {
            $beranda = Beranda::create([
                'deskripsi_beranda' => 'Selamat datang di halaman beranda kami.',
            ]);
        }
        return new ApiResource(true, 'Data Beranda', $beranda);
    }

    public function update(Request $request)
    {
        $claims = $request->attributes->get('jwt_claims');
        $role = $claims['role'];

        if ($role !== 'admin') {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $validator = Validator::make($request->all(), [
            'deskripsi_beranda' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $beranda = Beranda::first();
        $beranda->deskripsi_beranda = $request->deskripsi_beranda;
        $beranda->save();

        return new ApiResource(true, 'Deskripsi Beranda berhasil diperbarui', $beranda);
    }
}
