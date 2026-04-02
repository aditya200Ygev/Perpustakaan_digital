<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Kategori;

class BukuPublicController extends Controller
{
    public function index(Request $request)
    {
        $kategoris = Kategori::all();

        $bukus = Buku::with('kategori')
            ->when($request->kategori, function ($query) use ($request) {
                $query->where('kategori_id', $request->kategori);
            })
            ->latest()
            ->paginate(10);

        return view('books', compact('bukus', 'kategoris'));
    }

    // ✅ TAMBAHKAN INI (WAJIB)
    public function show($id)
    {
        $buku = Buku::with('kategori')->findOrFail($id);

        return view('books_detail', compact('buku'));
    }
}
