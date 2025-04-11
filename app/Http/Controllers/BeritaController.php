<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Berita;
use Illuminate\Support\Facades\Storage;

class BeritaController extends Controller
{
    public function index2(Request $request)
{
    $search = $request->input('search');

    $berita = Berita::when($search, function ($query) use ($search) {
        return $query->where('judul_berita', 'like', "%{$search}%");
    })->orderBy('created_at', 'desc')->paginate(2);

    return view('admin.berita.berita', compact('berita', 'search'));
}



    public function showBeritaUser(Request $request){
        $search = $request->input('search');

    $berita = Berita::when($search, function ($query) use ($search) {
        return $query->where('judul_berita', 'like', "%{$search}%");
    })->orderBy('created_at', 'desc')->paginate(10);
    
        return view('ppkha.berita', compact('berita','search'));
    }

    public function showBeritaDetailUser($id){
        $berita = Berita::where('id', $id)->first();
        return view('ppkha.detailBerita', compact('berita'));
    }

    public function index3()
    {
        return view('admin.berita.beritaAdd');
    }

    public function index4($id)
    {
        $berita = Berita::findOrFail($id);// Ambil data berita berdasarkan ID
    return view('admin.berita.beritaEdit', compact('berita'));
    }

    public function show1($id)
    {
        $berita = Berita::where('id', $id)->first();
        return view('admin.berita.beritaDetail', compact('berita'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'judul_berita' => 'required|string',
            'deskripsi_berita' => 'required|string',
            'gambar.*' => 'nullable|file|mimes:jpg,jpeg,png',
        ]);

        $gambarPaths = [];
        if ($request->hasFile('gambar')) {
            foreach ($request->file('gambar') as $file) {
                $gambarPaths[] = $file->store('gambar', 'public');
            }
        }

        Berita::create([
            'judul_berita' => $validatedData['judul_berita'],
            'deskripsi_berita' => $validatedData['deskripsi_berita'],
            'gambar' => $gambarPaths,
        ]);

        return redirect()->route('admin.berita.berita')->with('success', 'Berita berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
  {
      $validatedData = $request->validate([
          'judul_berita' => 'required|string',
          'deskripsi_berita' => 'required|string',
          'gambar.*' => 'nullable|file|mimes:jpg,jpeg,png,gif',
      ]);
  
      $berita = Berita::findOrFail($id);
  
      // Hapus gambar lama jika dicentang
      if ($request->has('hapus_gambar')) {
          foreach ($request->hapus_gambar as $file) {
              if (Storage::disk('public')->exists($file)) {
                  Storage::disk('public')->delete($file);
              }
          }
  
          // Filter gambar yang tidak dihapus
          $berita->gambar = array_diff($berita->gambar ?? [], $request->hapus_gambar);
      }
  
      // Tambah gambar baru
      $gambarPaths = $berita->gambar ?? [];
      if ($request->hasFile('gambar')) {
          foreach ($request->file('gambar') as $file) {
              $gambarPaths[] = $file->store('gambar', 'public');
          }
      }
  
      // Update berita
      $berita->update([
          'judul_berita' => $validatedData['judul_berita'],
          'deskripsi_berita' => $validatedData['deskripsi_berita'],
          'gambar' => $gambarPaths,
      ]);
  
      return redirect()->route('admin.berita.berita')->with('success', 'Berita berhasil diupdate!');
  }

  public function destroy($id)
  {
      try {
          $berita = Berita::findOrFail($id);
          $berita->delete();
  
          return response()->json(['success' => true]);
      } catch (\Exception $e) {
          return response()->json(['success' => false, 'message' => 'Gagal menghapus berita.'], 500);
      }
  }

}
