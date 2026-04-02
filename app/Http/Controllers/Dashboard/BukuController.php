<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Kategori; // ✅ FIX: tambahkan ini
use Illuminate\Support\Facades\Storage;

class BukuController extends Controller
{
    public function index()
    {
        // ✅ FIX: gunakan paginate, bukan get()
        $bukus = Buku::with('kategori')->latest()->paginate(10);

        return view('dashboard.petugas.buku.index', compact('bukus'));
    }

    public function create()
    {
        $kategoris = Kategori::all(); // opsional tapi bagus

        return view('dashboard.petugas.buku.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required',
            'penulis' => 'required',
            'penerbit' => 'required',
            'tahun_terbit' => 'required',
            'kategori_id' => 'required|exists:kategoris,id',
            'deskripsi' => 'nullable|string',
            'stok' => 'required|integer|min:0',
            'cover' => 'nullable|image'
        ]);

        $data = $request->all();

        if ($request->hasFile('cover')) {
            $data['cover'] = $request->file('cover')->store('buku', 'public');
        }

        Buku::create($data);

        return redirect()->route('petugas.buku')
            ->with('success', 'Buku berhasil ditambahkan');
    }

    public function edit($id)
    {
        $buku = Buku::findOrFail($id);
        $kategoris = Kategori::all(); // ✅ FIX: sebelumnya tidak dikirim ke view

        return view('dashboard.petugas.buku.edit', compact('buku', 'kategoris'));
    }

    public function update(Request $request, $id)
    {
        $buku = Buku::findOrFail($id);

        $request->validate([
            'judul' => 'required',
            'penulis' => 'required',
            'penerbit' => 'required',
            'tahun_terbit' => 'required',
            'kategori_id' => 'required|exists:kategoris,id',
            'deskripsi' => 'nullable|string',
            'stok' => 'required|integer|min:0',
            'cover' => 'nullable|image'
        ]);

        $data = $request->all();

        if ($request->hasFile('cover')) {

            if ($buku->cover) {
                Storage::delete('public/'.$buku->cover);
            }

            $data['cover'] = $request->file('cover')->store('buku', 'public');
        }

        $buku->update($data);

        return redirect()->route('petugas.buku')
            ->with('success', 'Buku berhasil diupdate');
    }

    public function destroy($id)
    {
        $buku = Buku::findOrFail($id);

        if ($buku->cover) {
            Storage::delete('public/'.$buku->cover);
        }

        $buku->delete();

        return back()->with('success', 'Buku berhasil dihapus');
    }
}
