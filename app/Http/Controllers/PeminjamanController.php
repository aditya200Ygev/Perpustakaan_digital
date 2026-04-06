<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PeminjamanController extends Controller
{
   public function index(Request $request)
{
    $query = Peminjaman::with(['user', 'buku']);

    // 🔍 SEARCH (nama user / judul buku)
    if ($request->search) {
        $query->where(function ($q) use ($request) {
            $q->whereHas('user', function ($u) use ($request) {
                $u->where('name', 'like', '%' . $request->search . '%');
            })
            ->orWhereHas('buku', function ($b) use ($request) {
                $b->where('judul', 'like', '%' . $request->search . '%');
            });
        });
    }

    // 📅 FILTER TANGGAL
    if ($request->from && $request->to) {
        $query->whereBetween('tgl_pinjam', [$request->from, $request->to]);
    }

    // 🔥 AMBIL DATA (JANGAN DITIMPA LAGI)
    $peminjaman = $query->latest()->get();

    // 🔥 AUTO DENDA
    foreach ($peminjaman as $item) {
        if (
            $item->status == 'dipinjam' &&
            Carbon::now()->gt(Carbon::parse($item->tgl_kembali))
        ) {
            $item->status = 'denda';
            $item->save();
        }
    }

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
            'jumlah' => $jumlah,
            'tgl_pinjam' => now(),
            'tgl_kembali' => now()->addDays(3),
            'status' => 'diajukan'
        ]);

        return back()->with('success', 'Pengajuan berhasil, menunggu persetujuan');
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

        // KURANGI STOK
        $buku->stok -= $pinjam->jumlah;
        $buku->save();

        return back()->with('success', 'Peminjaman disetujui');
    }

    public function kembalikan($id)
    {
        $pinjam = Peminjaman::findOrFail($id);
        $buku = Buku::findOrFail($pinjam->buku_id);

        // TAMBAH STOK
        $buku->stok += $pinjam->jumlah;
        $buku->save();

        $pinjam->status = 'dikembalikan';
        $pinjam->save();

        return back()->with('success', 'Buku berhasil dikembalikan');
    }

    public function tolak($id)
    {
        $pinjam = Peminjaman::findOrFail($id);

        $pinjam->status = 'ditolak';
        $pinjam->save();

        return back()->with('success', 'Pengajuan ditolak');
    }
}
