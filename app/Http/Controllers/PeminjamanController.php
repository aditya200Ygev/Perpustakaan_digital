<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\Keuangan;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    /**
     * TAMPILAN DATA PEMINJAMAN (PETUGAS)
     */
    public function index(Request $request)
    {
        // 1. Inisialisasi Query dengan Eager Loading
        $query = Peminjaman::with(['user', 'buku', 'user.anggota']);

        // 2. 🔍 SEARCH (nama user / judul buku)
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->whereHas('user', function ($u) use ($request) {
                    $u->where('name', 'like', '%' . $request->search . '%');
                })
                ->orWhereHas('buku', function ($b) use ($request) {
                    $b->where('judul', 'like', '%' . $request->search . '%');
                });
            });
        }

        // 3. 📅 FILTER TANGGAL
        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('tgl_pinjam', [$request->from, $request->to]);
        }

        // 4. 🔥 FILTER STATUS
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 5. 🔥 AUTO DENDA
        Peminjaman::where('status', 'dipinjam')
            ->where('tgl_kembali', '<', now())
            ->update(['status' => 'denda']);

        // 6. 🔥 AMBIL DATA DENGAN PAGINATION
        $peminjaman = $query->latest()->paginate(10);

        return view('dashboard.petugas.peminjaman.index', compact('peminjaman'));
    }

    /**
     * PROSES PENGAJUAN PINJAM (USER)
     */
    public function create($id)
    {
        $buku = Buku::findOrFail($id);
        return view('pinjam.create', compact('buku'));
    }

    public function showPinjam($id)
    {
        $buku = Buku::with('kategori')->findOrFail($id);
        return view('pinjam.store_view', compact('buku'));
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
            'id_pinjam'   => 'PJ' . time(),
            'user_id'     => auth()->id(),
            'buku_id'     => $buku->id,
            'jumlah'      => $jumlah,
            'tgl_pinjam'  => $request->tgl_pinjam ?? now(),
            'tgl_kembali' => Carbon::parse($request->tgl_pinjam ?? now())->addDays(3),
            'status'      => 'diajukan'
        ]);

        return back()->with('success', 'Pengajuan berhasil, menunggu persetujuan');
    }

    /**
     * MANAJEMEN STATUS OLEH PETUGAS (APPROVE/TOLAK)
     */
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

    public function tolak($id)
    {
        $pinjam = Peminjaman::findOrFail($id);
        $pinjam->status = 'ditolak';
        $pinjam->save();

        return back()->with('success', 'Pengajuan ditolak');
    }

    /**
     * PROSES PENGEMBALIAN
     */
    public function ajukanKembali($id)
    {
        $pinjam = Peminjaman::findOrFail($id);

        if ($pinjam->status != 'dipinjam' && $pinjam->status != 'denda') {
            return back()->with('error', 'Tidak bisa ajukan');
        }

        // 🔥 LANGSUNG JADI DIKEMBALIKAN (REQUEST KE PETUGAS)
        $pinjam->status = 'dikembalikan';
        $pinjam->save();

        return back()->with('success', 'Pengajuan pengembalian dikirim');
    }

    public function accKembali($id)
    {
        $pinjam = Peminjaman::findOrFail($id);
        $today = Carbon::now();
        $tglKembali = Carbon::parse($pinjam->tgl_kembali);

        if ($today->gt($tglKembali)) {
            $pinjam->status = 'denda';
            $pinjam->is_denda = true;
        } else {
            $pinjam->status = 'selesai';
            $pinjam->is_denda = false;
        }

        $pinjam->save();

        return back()->with('success', 'Pengembalian diproses');
    }

    // Fungsi lama untuk kembalikan manual (opsional jika masih dipakai)
    public function kembalikan($id)
    {
        $pinjam = Peminjaman::findOrFail($id);
        $buku = Buku::findOrFail($pinjam->buku_id);

        // TAMBAH STOK
        $buku->stok += $pinjam->jumlah;
        $buku->save();

        return back()->with('success', 'Pengembalian disetujui');
    }

    /**
     * MANAJEMEN DENDA
     */
    public function dataDenda()
    {
        $denda = Peminjaman::with(['user', 'buku'])
            ->where('status', 'denda')
            ->latest()
            ->get();

        return view('dashboard.petugas.denda.index', compact('denda'));
    }
public function keuangan(Request $request)
{
    // 1. Gunakan query builder yang konsisten
    $query = Keuangan::with(['peminjaman.user.anggota', 'peminjaman.buku'])
        ->whereHas('peminjaman', function ($q) {
            $q->where('is_paid', true);
        });

    // 2. 🔍 FILTER SEARCH (Pencarian Nama/Buku)
    // Gunakan when() agar kode lebih bersih
    $query->when($request->filled('search'), function ($q) use ($request) {
        $q->whereHas('peminjaman', function($pq) use ($request) {
            $pq->where(function($sq) use ($request) {
                $sq->whereHas('user', function($u) use ($request) {
                    $u->where('name', 'like', '%' . $request->search . '%');
                })
                ->orWhereHas('buku', function($b) use ($request) {
                    $b->where('judul', 'like', '%' . $request->search . '%');
                });
            });
        });
    });

    // 3. 🔍 FILTER TANGGAL (Penting: gunakan filled() agar string kosong tidak masuk)
    if ($request->filled('from') && $request->filled('to')) {
        $query->whereBetween('tanggal', [$request->from, $request->to]);
    }

    // 4. 🔥 EKSEKUSI DATA (Gunakan clone agar query total tidak terpengaruh order/limit)
    $data = (clone $query)->orderBy('tanggal', 'desc')->get();
    $total = (clone $query)->sum('total_denda');

    // 5. 🔥 DATA CHART (Sesuaikan filternya dengan filter di atas)
    $chart = Keuangan::selectRaw('DATE(tanggal) as tanggal, SUM(total_denda) as total')
        ->whereHas('peminjaman', function ($q) {
            $q->where('is_paid', true);
        })
        ->when($request->filled('from') && $request->filled('to'), function ($q) use ($request) {
            $q->whereBetween('tanggal', [$request->from, $request->to]);
        })
        ->groupBy('tanggal')
        ->orderBy('tanggal', 'asc')
        ->get();

    return view('dashboard.petugas.keuangan.index', compact('data', 'chart', 'total'));
}
    public function bayarDenda($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->is_paid = true; // ✅ tandai sudah bayar
        $peminjaman->status = 'denda';
        $peminjaman->save();

        return back();
    }

    public function accDenda($id)
    {
        $pinjam = Peminjaman::findOrFail($id);
        $buku = Buku::findOrFail($pinjam->buku_id);

        if ($pinjam->status != 'denda') {
            return back()->with('error', 'Data bukan denda');
        }

        if ($pinjam->is_paid) {
            return back()->with('error', 'Denda sudah dibayar sebelumnya!');
        }

        // 🔥 HITUNG DENDA LANGSUNG
        $tglKembali = Carbon::parse($pinjam->tgl_kembali);
        $today = Carbon::now();
        $hariTelat = $tglKembali->diffInDays($today);
        $totalDenda = $hariTelat * 5000;

        // Tambah stok buku
        $buku->stok += $pinjam->jumlah;
        $buku->save();

        // Simpan keuangan
        Keuangan::create([
            'peminjaman_id' => $pinjam->id,
            'total_denda'   => $totalDenda,
            'tanggal'       => now()
        ]);

        // Tandai sudah bayar
        $pinjam->is_paid = true;
        $pinjam->save();

        return back()->with('success', '✅ Denda Rp ' . number_format($totalDenda) . ' berhasil dibayar');
    }

    /**
     * LAPORAN & KEUANGAN
     */

    public function cetakBukti($id)
    {
        $pinjam = Peminjaman::with(['user.anggota', 'buku'])->findOrFail($id);
        return view('dashboard.petugas.peminjaman.bukti', compact('pinjam'));
    }
public function laporan(Request $request)
{
    // 1. Ambil data dengan eager loading agar tidak berat (N+1 Problem)
    $query = Peminjaman::with(['user.anggota', 'user.peminjaman', 'buku', 'keuangan']);

    // 🔍 FILTER TANGGAL
    if ($request->filled('tgl_mulai') && $request->filled('tgl_selesai')) {
        $query->whereBetween('tgl_pinjam', [
            $request->tgl_mulai,
            $request->tgl_selesai
        ]);
    }

    // 🔍 SEARCH
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->whereHas('user', function ($u) use ($search) {
                $u->where('name', 'like', "%$search%");
            })
            ->orWhereHas('buku', function ($b) use ($search) {
                $b->where('judul', 'like', "%$search%");
            });
        });
    }

    // 🔥 FILTER STATUS
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    $data = $query->latest()->get();

    // 🔥 PROSES DATA MAPPING
    $laporan = $data->map(function ($item) {
        // A. Hitung Denda
        $deadline = \Carbon\Carbon::parse($item->tgl_kembali);
        $tglAkhir = in_array($item->status, ['selesai', 'dikembalikan'])
                    ? \Carbon\Carbon::parse($item->updated_at)
                    : now();

        $hariTelat = $tglAkhir->gt($deadline) ? $tglAkhir->diffInDays($deadline) : 0;

        $item->hari_telat = $hariTelat;
        $item->total_denda = $hariTelat * 5000;

        // B. Ambil Variabel BUKU yang sedang dipinjam siswa (Total Buku di Tangan)
        // Ini menghitung berapa banyak transaksi 'dipinjam' yang dimiliki user tersebut
        $item->jumlah_buku_dihand = $item->user
            ? $item->user->peminjaman->where('status', 'dipinjam')->count()
            : 0;

        return $item;
    });

    // 🔥 TOTAL KAS
    $totalKas = Keuangan::sum('total_denda');

    // 🔥 STATISTIK
    $stats = [
        'dipinjam'      => $laporan->where('status', 'dipinjam')->count(),
        'selesai'       => $laporan->where('status', 'selesai')->count(),
        'denda'         => $laporan->where('hari_telat', '>', 0)->count(),
        'dikembalikan'  => $laporan->where('status', 'dikembalikan')->count(),
        'potensi_denda' => $laporan->sum('total_denda'),
        'kas_denda'     => $totalKas,
    ];

    return view('dashboard.petugas.laporan.index', compact('laporan', 'stats', 'totalKas'));
}
}
