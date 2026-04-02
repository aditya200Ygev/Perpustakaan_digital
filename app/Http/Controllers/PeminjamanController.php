<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\Auth;

class PeminjamanController extends Controller
{
    public function index()
{
    $peminjaman = Peminjaman::with(['user', 'buku'])->latest()->get();

    return view('dashboard.petugas.peminjaman.index', compact('peminjaman'));
}
    public function create($id)
    {
        $buku = Buku::findOrFail($id);
        return view('pinjam.create', compact('buku'));
    }

  public function store(Request $request)
{
    $buku = Buku::findOrFail($request->buku_id);
    // AMBIL JUMLAH
    $jumlah = $request->jumlah;

    // CEK STOK
    if ($buku->stok < 1) {
        return back()->with('error', 'Stok buku habis');
    }

    // SIMPAN
    Peminjaman::create([
        'id_pinjam' => 'PJ' . time(),
        'user_id' => auth()->id(),
        'buku_id' => $buku->id,
           'jumlah' => $jumlah, // ✅ WAJIB
        'tgl_pinjam' => now(),
        'tgl_kembali' => now()->addDays(3),
           'status' => 'diajukan' // 🔥 PENTING
    ]);

    return redirect()->back()->with('success', 'Pengajuan berhasil, menunggu persetujuan');


    // KURANGI STOK
    $buku->stok -= $jumlah;
    $buku->save();

   return redirect()->route('pinjam.confirm', $buku->id)
    ->with('success', 'Berhasil dipinjam');
}
    public function showPinjam($id)
{
    $buku = Buku::with('kategori')->findOrFail($id);

    return view('pinjam.store_view', compact('buku'));
}
   public function approve($id)
    {
        $pinjam = Peminjaman::findOrFail($id);
        $buku = Buku::findOrFail($pinjam->buku_id);

        if ($buku->stok < $pinjam->jumlah) {
            return back()->with('error', 'Stok tidak cukup untuk disetujui');
        }

        $pinjam->status = 'dipinjam';
        $pinjam->save();

        $buku->stok -= $pinjam->jumlah;
        $buku->save();

        return back()->with('success', 'Peminjaman disetujui');
    }

    public function kembalikan($id)
    {
        $pinjam = Peminjaman::findOrFail($id);
        $buku = Buku::findOrFail($pinjam->buku_id);

        $buku->stok += $pinjam->jumlah;
        $buku->save();

        $pinjam->status = 'dikembalikan';
        $pinjam->save();

        return back()->with('success', 'Buku berhasil dikembalikan');
    }

}
