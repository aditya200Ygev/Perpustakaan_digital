<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Kategori;

class BukuPublicController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil semua kategori untuk menu filter
        $kategoris = Kategori::all();

        // 2. Ambil buku dengan filter kategori (untuk bagian GRID)
        $bukus = Buku::with('kategori')
            ->when($request->kategori, function ($query) use ($request) {
                return $query->where('kategori_id', $request->kategori);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // 3. Ambil 4 buku terbaru khusus untuk bagian POSTER/HERO
        $buku_terbaru = Buku::latest()->take(4)->get();

        // 4. KIRIM SEMUA VARIABEL KE VIEW (Jangan lupa tambahkan 'buku_terbaru')
        return view('books', compact('bukus', 'kategoris', 'buku_terbaru'));
    }

    public function show($id)
    {
        $buku = Buku::with('kategori')->findOrFail($id);
        return view('books_detail', compact('buku'));
    }
}
