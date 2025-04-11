<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengumuman;
use Illuminate\Support\Facades\Storage;

class PengumumanController extends Controller
{
  public function index(Request $request)
  {
    $search = $request->input('search');

    $pengumuman = Pengumuman::when($search, function ($query) use ($search) {
        return $query->where('judul_pengumuman', 'like', "%{$search}%");
    })->orderBy('created_at', 'desc')->paginate(2);

    return view('admin.pengumuman.pengumuman', compact('pengumuman', 'search'));
  }

  public function showPengumumanUser(Request $request)
  {
    $search = $request->input('search');

    $pengumuman = Pengumuman::when($search, function ($query) use ($search) {
        return $query->where('judul_pengumuman', 'like', "%{$search}%");
    })->orderBy('created_at', 'desc')->paginate(10);
    return view('ppkha.pengumuman', compact('pengumuman','search'));
  }

  public function showPengumumanDetailUser($id)
  {
    $pengumuman = Pengumuman::findOrFail($id);
    return view('ppkha.detailPengumuman', compact('pengumuman'));
  }

  public function create()
  {
    return view('admin.pengumuman.pengumumanAdd');
  }

  public function show($id)
  {
    $pengumuman = Pengumuman::findOrFail($id);
    return view('admin.pengumuman.pengumumanDetail', compact('pengumuman'));
  }


  public function store(Request $request)
  {
    $request->validate([
      'judul_pengumuman' => 'required|string',
      'deskripsi_pengumuman' => 'required|string',
      'lampiran.*' => 'file|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx' // Validasi file
    ]);

    // Simpan lampiran
    $lampiranPaths = [];
    if ($request->hasFile('lampiran')) {
      foreach ($request->file('lampiran') as $file) {
        $path = $file->store('lampiran_pengumuman', 'public'); // Simpan ke storage/app/public/lampiran_pengumuman
        $lampiranPaths[] = $path;
      }
    }

    // Simpan data pengumuman ke database
    $pengumuman = Pengumuman::create([
      'judul_pengumuman' => $request->judul_pengumuman,
      'deskripsi_pengumuman' => $request->deskripsi_pengumuman,
      'lampiran' => count($lampiranPaths) > 0 ? json_encode($lampiranPaths) : null, // Simpan dalam format JSON
    ]);

    return redirect()->route('admin.pengumuman.index')->with('success', 'Pengumuman berhasil ditambahkan!');
  }

  public function edit($id)
  {
    $pengumuman = Pengumuman::findOrFail($id);
    return view('admin.pengumuman.pengumumanEdit', compact('pengumuman'));
  }

  public function update(Request $request, $id)
  {
    $pengumuman = Pengumuman::findOrFail($id);

    // Validasi input
    $request->validate([
      'judul_pengumuman' => 'required|string',
      'deskripsi_pengumuman' => 'required|string',
      'lampiran.*' => 'file|mimes:jpg,jpeg,png,pdf',
    ]);

    // Update data pengumuman
    $pengumuman->judul_pengumuman = $request->judul_pengumuman;
    $pengumuman->deskripsi_pengumuman = $request->deskripsi_pengumuman;

    // Hapus lampiran yang dipilih
    if ($request->has('hapus_lampiran')) {
      $lampiranTerbaru = json_decode($pengumuman->lampiran, true) ?? [];

      foreach ($request->hapus_lampiran as $file) {
        if ($file) { // Hanya hapus jika file tidak null
          Storage::delete($file);
          $lampiranTerbaru = array_filter($lampiranTerbaru, fn($item) => $item !== $file);
        }
      }

      // Simpan kembali daftar lampiran yang tersisa
      $pengumuman->lampiran = json_encode(array_values($lampiranTerbaru));
    }

    // Tambahkan lampiran baru
    if ($request->hasFile('lampiran')) {
      $lampiranBaru = [];
      foreach ($request->file('lampiran') as $file) {
        $path = $file->store('lampiran_pengumuman', 'public');
        $lampiranBaru[] = $path;
      }

      // Gabungkan dengan lampiran yang masih ada
      $lampiranLama = json_decode($pengumuman->lampiran, true) ?? [];
      $pengumuman->lampiran = json_encode(array_merge($lampiranLama, $lampiranBaru));
    }

    $pengumuman->save();

    return redirect()->route('admin.pengumuman.index')->with('success', 'Pengumuman berhasil diperbarui!');
  }


  public function destroy($id)
  {
    try {
      $pengumuman = Pengumuman::findOrFail($id);
      $lampiranPaths = json_decode($pengumuman->lampiran ?? '[]', true);

      foreach ($lampiranPaths as $file) {
        if (Storage::disk('public')->exists($file)) {
          Storage::disk('public')->delete($file);
        }
      }

      $pengumuman->delete();
      return response()->json(['success' => true]);
    } catch (\Exception $e) {
      return response()->json(['success' => false, 'message' => 'Gagal menghapus pengumuman.'], 500);
    }
  }
}

