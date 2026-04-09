<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Kategori; // ✅ FIX: tambahkan ini
use Illuminate\Support\Facades\Storage;

class BukuController extends Controller
{
   public function index(Request $request) // Tambahkan Request $request
{
    // 1. Ambil semua kategori untuk dropdown filter
    $kategoris = Kategori::all();

    // 2. Mulai Query Buku
    $query = Buku::with('kategori');

    // 3. Logika Filter Kategori (Jika dipilih)
    if ($request->has('kategori_id') && $request->kategori_id != '') {
        $query->where('kategori_id', $request->kategori_id);
    }

    // 4. Logika Pencarian (Jika mengetik di kolom cari)
    if ($request->has('search') && $request->search != '') {
        $query->where(function($q) use ($request) {
            $q->where('judul', 'like', '%' . $request->search . '%')
              ->orWhere('penulis', 'like', '%' . $request->search . '%');
        });
    }

    // 5. Eksekusi dengan Paginate dan kirim SEMUA variabel
    $bukus = $query->latest()->paginate(10);

    // ✅ Tambahkan 'kategoris' di dalam compact()
    return view('dashboard.petugas.buku.index', compact('bukus', 'kategoris'));
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
