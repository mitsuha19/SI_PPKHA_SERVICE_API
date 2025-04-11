<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengumuman;
use App\Models\Artikel;
use App\Models\Berita;
use App\Models\Beranda;
use Illuminate\Support\Facades\DB;

class BerandaController extends Controller
{
    public function index()
    {
        
        $pengumuman = Pengumuman::latest()->take(3)->get();

        // Mengambil artikel terbaru
        $artikel = Artikel::latest()->take(3)->get();

        // Mengambil berita terbaru
        $berita = Berita::latest()->take(3)->get();

        $beranda = Beranda::first(); // Selalu ambil id = 1 karena cuma satu

        // Kirim data ke view
        return view('ppkha.beranda', compact('pengumuman', 'artikel', 'berita', 'beranda'));
    }

    public function showBerandaAdmin()
    {
        $beranda = Beranda::first(); // Selalu ambil id = 1 karena cuma satu
        return view('admin.berandaEdit', compact('beranda'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'deskripsi_beranda' => 'required|string',
        ]);

        $beranda = Beranda::first(); // Selalu id = 1
        $beranda->update([
            'deskripsi_beranda' => $request->deskripsi_beranda,
        ]);

        return redirect()->back()->with('success', 'Deskripsi beranda berhasil diperbarui.');
    }
}
