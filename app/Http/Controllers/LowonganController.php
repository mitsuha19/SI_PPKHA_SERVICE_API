<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lowongan;
use App\Models\Perusahaan;
use Illuminate\Support\Facades\DB;

class LowonganController extends Controller
{
    public function index2(Request $request)
    {
        $search = $request->input('search');

    $lowongan = Lowongan::when($search, function ($query) use ($search) {
        return $query->where('judulLowongan', 'like', "%{$search}%");
    })->orderBy('created_at', 'desc')->paginate(2);

        return view('admin.lowonganPekerjaan.lowonganPekerjaan', compact('lowongan', 'search'));
    }

    public function showLowonganUser(Request $request)
    {
        $search = $request->input('search');

    $lowongan = Lowongan::when($search, function ($query) use ($search) {
        return $query->where('judulLowongan', 'like', "%{$search}%");
    })->orderBy('created_at', 'desc')->paginate(10);
        return view('ppkha.lowongan_pekerjaan', compact('lowongan', 'search'));
    }

    public function showLowonganDetailUser($id)
    {
        $lowongan = Lowongan::findOrFail($id);
        $perusahaan = Perusahaan::where('id', $id)->get();
        return view('ppkha.detaillowongan', compact('lowongan', 'perusahaan'));
    }

    public function index3()
    {
        $perusahaan = Perusahaan::all();
        return view('admin.lowonganPekerjaan.lowonganPekerjaanAdd', compact('perusahaan'));
    }

    public function index4($id)
    {
        $perusahaan = Perusahaan::all();
        $lowongan = Lowongan::findOrFail($id);
        return view('admin.lowonganPekerjaan.lowonganPekerjaanEdit', compact('perusahaan', 'lowongan'));
    }

    public function show1($id)
    {
        $lowongan = Lowongan::findOrFail($id);
        return view('admin.lowonganPekerjaan.lowonganPekerjaanDetail', compact('lowongan'));
    }

    public function store(Request $request)
    {
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
        ]);

        DB::transaction(function () use ($request, $validatedData) {
            if ($request->namaPerusahaan === 'Other') {
                $request->validate([
                    'namaPerusahaanBaru' => 'required|string|unique:perusahaan,namaPerusahaan',
                    'lokasiPerusahaan' => 'required|string',
                    'websitePerusahaan' => 'nullable|url',
                    'industriPerusahaan' => 'required|string',
                    'deskripsiPerusahaan' => 'required|string',
                    'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
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
                $perusahaan = Perusahaan::where('namaPerusahaan', $request->namaPerusahaan)->firstOrFail();
            }

            Lowongan::create([
                'judulLowongan' => $validatedData['judulLowongan'],
                'jenisLowongan' => $validatedData['jenisLowongan'],
                'tipeLowongan' => $validatedData['tipeLowongan'],
                'deskripsiLowongan' => $validatedData['deskripsiLowongan'],
                'kualifikasi' => $validatedData['kualifikasi'],
                'benefit' => $validatedData['benefit'],
                'keahlian' => implode(',', $validatedData['keahlian'] ?? []),
                'batasMulai' => $validatedData['batasMulai'],
                'batasAkhir' => $validatedData['batasAkhir'],
                'perusahaan_id' => $perusahaan->id,
            ]);
        });

        return redirect()->route('admin.lowonganPekerjaan.lowonganPekerjaan')->with('success', 'Lowongan berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
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
    
        $lowongan = Lowongan::findOrFail($id);
    
        // Simpan keahlian sebagai string jika ada, jika tidak, simpan null
        $keahlianString = $validatedData['keahlian'] ? implode(',', $validatedData['keahlian']) : null;
    
        // Update hanya data lowongan, tanpa menyentuh perusahaan
        $lowongan->update([
            'judulLowongan' => $validatedData['judulLowongan'],
            'jenisLowongan' => $validatedData['jenisLowongan'],
            'tipeLowongan' => $validatedData['tipeLowongan'],
            'deskripsiLowongan' => $validatedData['deskripsiLowongan'],
            'kualifikasi' => $validatedData['kualifikasi'],
            'benefit' => $validatedData['benefit'],
            'keahlian' => $keahlianString,
            'batasMulai' => $validatedData['batasMulai'],
            'batasAkhir' => $validatedData['batasAkhir'],
        ]);
    
        return redirect()->route('admin.lowonganPekerjaan.lowonganPekerjaan')
            ->with('success', 'Lowongan berhasil diperbarui');
    }
    
    


    public function destroy($id)
  {
      try {
          $lowongan = Lowongan::findOrFail($id);
          $lowongan->delete();
  
          return response()->json(['success' => true]);
      } catch (\Exception $e) {
          return response()->json(['success' => false, 'message' => 'Gagal menghapus berita.'], 500);
      }
  }
}
