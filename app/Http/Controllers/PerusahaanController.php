<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Perusahaan;
use App\Models\Lowongan;
use Illuminate\Support\Facades\Storage;

class PerusahaanController extends Controller
{
    // Menampilkan semua perusahaan (Admin)
    public function index2(Request $request)
    {
        $search = $request->input('search');

        $perusahaan = Perusahaan::when($search, function ($query) use ($search) {
            return $query->where('namaPerusahaan', 'like', "%{$search}%");
        })->orderBy('created_at', 'desc')->paginate(2);

        return view('admin.daftarPerusahaan.daftarPerusahaan', compact('perusahaan', 'search'));
    }

    // Menampilkan semua perusahaan (User)
    public function showPerusahaanUser(Request $request)
    {
        $search = $request->input('search');

        $perusahaan = Perusahaan::when($search, function ($query) use ($search) {
            return $query->where('namaPerusahaan', 'like', "%{$search}%");
        })->orderBy('created_at', 'desc')->paginate(10); // Gunakan paginate agar selalu berupa Collection

        return view('ppkha.daftar_perusahaan', compact('perusahaan', 'search'));
    }

    // Menampilkan detail perusahaan untuk User
    public function showPerusahaanDetailUser($id)
    {
        $perusahaan = Perusahaan::findOrFail($id);
        $lowongan = Lowongan::where('perusahaan_id', $id)->get();

        return view('ppkha.detailperusahaan', compact('perusahaan', 'lowongan'));
    }

    // Menampilkan form edit perusahaan (Admin)
    public function index4($id)
    {
        $perusahaan = Perusahaan::findOrFail($id);
        $lowongan = Lowongan::where('perusahaan_id', $id)->get(); // Perbaikan di sini

        return view('admin.daftarPerusahaan.daftarPerusahaanEdit', compact('perusahaan', 'lowongan'));
    }

    // Menampilkan detail perusahaan (Admin)
    public function show1($id)
    {
        $perusahaan = Perusahaan::findOrFail($id);
        $lowongan = Lowongan::where('perusahaan_id', $id)->get();

        return view('admin.daftarPerusahaan.daftarPerusahaanDetail', compact('perusahaan', 'lowongan'));
    }

    // Menampilkan detail perusahaan dalam format JSON
    public function show($id)
    {
        $perusahaan = Perusahaan::find($id);

        if (!$perusahaan) {
            return response()->json(['message' => 'Perusahaan tidak ditemukan'], 404);
        }

        return response()->json($perusahaan);
    }

    // Mengupdate data perusahaan
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'namaPerusahaan' => 'required|string',
            'lokasiPerusahaan' => 'required|string',
            'websitePerusahaan' => 'nullable|url',
            'industriPerusahaan' => 'required|string',
            'deskripsiPerusahaan' => 'required|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $perusahaan = Perusahaan::findOrFail($id);

        // Jika ada file logo baru, simpan dan hapus logo lama
        if ($request->hasFile('logo')) {
            // Hapus logo lama jika ada
            if ($perusahaan->logo) {
                Storage::delete('public/' . $perusahaan->logo);
            }

            // Simpan logo baru
            $logoPath = $request->file('logo')->store('logos', 'public');
        } else {
            // Gunakan logo lama jika tidak ada file baru
            $logoPath = $perusahaan->logo;
        }

        // Update data perusahaan
        $perusahaan->update([
            'namaPerusahaan' => $validatedData['namaPerusahaan'],
            'lokasiPerusahaan' => $validatedData['lokasiPerusahaan'],
            'websitePerusahaan' => $validatedData['websitePerusahaan'],
            'industriPerusahaan' => $validatedData['industriPerusahaan'],
            'deskripsiPerusahaan' => $validatedData['deskripsiPerusahaan'],
            'logo' => $logoPath, // Gunakan logo yang sudah ada jika tidak diubah
        ]);

        return redirect()->route('admin.daftarPerusahaan.daftarPerusahaan')
            ->with('success', 'Perusahaan berhasil diperbarui');
    }

    // Menghapus perusahaan
    public function destroy($id)
    {
        try {
            $perusahaan = Perusahaan::findOrFail($id);
            $perusahaan->delete();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus perusahaan.'], 500);
        }
    }
}
