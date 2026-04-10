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

    // 2. 🔍 SEARCH
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

    // 4. 🔥 FILTER STATUS (DIPERBAIKI)
    if ($request->filled('status')) {
        if ($request->status == 'denda') {
            // Tampilkan:
            // - Status 'denda' (aktif, belum lunas)
            // - ATAU status 'selesai' yang pernah kena denda (historis)
            $query->where(function($q) {
                $q->where('status', 'denda')  // Denda aktif
                  ->orWhere(function($sub) {
                      $sub->where('status', 'selesai')
                          ->where('is_denda', true);  // Historis: pernah telat
                  });
            });
        } else {
            $query->where('status', $request->status);
        }
    }

    // 5. 🔥 AUTO DENDA (Hanya ubah status, jangan hitung denda di sini)
    // Ini hanya mengubah status 'dipinjam' yang lewat tanggal jadi 'denda'
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
   /**
 * PROSES PENGEMBALIAN - ANGGOTA AJUKAN
 */
 public function pengembalian()
    {
        // ✅ Ambil data peminjaman milik user yang login saja
        $data = Peminjaman::with('buku')
            ->where('user_id', Auth::id())
            ->whereIn('status', [
                'dipinjam',
                'denda',
                'pengembalian_diajukan',
                'selesai'
            ])
            ->latest()
            ->get();

        return view('dashboard.anggota.pengembalian', compact('data'));
    }
public function ajukanKembali($id)
{
    $pinjam = Peminjaman::findOrFail($id);

    // Hanya bisa ajukan jika masih dipinjam atau dalam denda
    if (!in_array($pinjam->status, ['dipinjam', 'denda'])) {
        return back()->with('error', 'Tidak bisa ajukan pengembalian');
    }

    // 🔥 UBAH KE STATUS "MENUNGGU ACC" (BUKAN LANGSUNG DIKEMBALIKAN)
    $pinjam->status = 'pengembalian_diajukan';
    $pinjam->save();

    return back()->with('success','Pengajuan pengembalian dikirim, menunggu ACC petugas');
}

   /**
 * PROSES ACC PENGEMBALIAN - OLEH PETUGAS
 *//**
 * PROSES ACC PENGEMBALIAN - OLEH PETUGAS
 */
public function accKembali($id)
{
    $pinjam = Peminjaman::findOrFail($id);

    // ✅ TERIMA KEDUA STATUS: baru ATAU legacy
    if (!in_array($pinjam->status, ['pengembalian_diajukan', 'dikembalikan'])) {
        return back()->with('error', 'Data tidak dalam status menunggu ACC');
    }

    $buku = Buku::findOrFail($pinjam->buku_id);
    $today = Carbon::now();
    $tglKembali = Carbon::parse($pinjam->tgl_kembali);

    // 🔥 INISIALISASI VARIABEL
    $totalDenda = 0;
    $isTerlambat = false;

    // 🔥 HITUNG DENDA JIKA TERLAMBAT
    if ($today->gt($tglKembali)) {
        $hariTelat = $tglKembali->diffInDays($today);
        $totalDenda = $hariTelat * 5000;
        $isTerlambat = true;

        $pinjam->status = 'selesai';
        $pinjam->is_denda = true;
    } else {
        $pinjam->status = 'selesai';
        $pinjam->is_denda = false;
    }

    // 🔥 CATAT KE KEUANGAN HANYA JIKA ADA DENDA
    if ($isTerlambat && $totalDenda > 0) {
        Keuangan::create([
            'peminjaman_id' => $pinjam->id,
            'total_denda'   => $totalDenda,
            'tanggal'       => now(),
        ]);
    }

    // 🔥 KEMBALIKAN STOK BUKU
    $buku->stok += $pinjam->jumlah;
    $buku->save();
    $pinjam->save();

    // ✅ PESAN SESUAI KONDISI
    $message = $isTerlambat
        ? "✅ Pengembalian diproses - Denda: Rp " . number_format($totalDenda)
        : "✅ Pengembalian diproses - Tidak ada denda";

    return back()->with('success', $message);
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
   public function dataDenda(Request $request)
{
    $query = Peminjaman::with(['user', 'buku'])
        ->where('status', 'denda');

    // ✅ Tambah filter: tampilkan yang sudah lunas juga
    if ($request->filled('include_lunas') && $request->include_lunas == 'true') {
        // Tampilkan semua denda (lunas + belum lunas)
    } else {
        // Default: hanya yang belum lunas (is_paid = false) atau menunggu ACC (is_paid = true)
        $query->where(function($q) {
            $q->where('is_paid', false)
              ->orWhere(function($sub) {
                  $sub->where('is_paid', true)
                      ->where('status', 'denda'); // Masih menunggu ACC petugas
              });
        });
    }

    $denda = $query->latest()->get();

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
            $q->whereHas('peminjaman', function ($pq) use ($request) {
                $pq->where(function ($sq) use ($request) {
                    $sq->whereHas('user', function ($u) use ($request) {
                        $u->where('name', 'like', '%' . $request->search . '%');
                    })
                    ->orWhereHas('buku', function ($b) use ($request) {
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

    /**
 * KONFIRMASI PEMBAYARAN DENDA - OLEH PETUGAS
 */
/**
 * KONFIRMASI PEMBAYARAN DENDA - OLEH PETUGAS
 */
public function accDenda($id)
{
    $pinjam = Peminjaman::findOrFail($id);
    $buku = Buku::findOrFail($pinjam->buku_id);

    // ✅ Validasi 1: Harus status denda
    if ($pinjam->status != 'denda') {
        return back()->with('error', 'Data bukan denda');
    }

    // ✅ Validasi 2: HARUS sudah diajukan bayar oleh anggota
    if (!$pinjam->is_paid) {
        return back()->with('error', 'Anggota belum mengajukan pembayaran!');
    }

    // 🔥 HITUNG DENDA
    $tglKembali = Carbon::parse($pinjam->tgl_kembali);
    $today = Carbon::now();
    $hariTelat = max(0, $tglKembali->diffInDays($today));
    $totalDenda = $hariTelat * 5000;

    // 🔥 TAMBAH STOK BUKU
    $buku->stok += $pinjam->jumlah;
    $buku->save();

    // 🔥 CATAT KE KEUANGAN (hanya jika ada denda)
    if ($hariTelat > 0 && $totalDenda > 0) {
        Keuangan::create([
            'peminjaman_id' => $pinjam->id,
            'total_denda'   => $totalDenda,
            'tanggal'       => now(),
            'keterangan'    => "Denda dikonfirmasi - Telat {$hariTelat} hari"
        ]);
    }

    // ✅ FIX UTAMA: Update status DAN pastikan is_denda benar
    $pinjam->status = 'selesai';

    // 🔥 PENTING: Set is_denda = true JIKA pernah telat (agar muncul di filter historis)
    if ($hariTelat > 0) {
        $pinjam->is_denda = true;  // ✅ Tandai sebagai "pernah kena denda"
    }
    // Jika tidak telat, is_denda bisa false (tidak masalah)

    $pinjam->save();

    // ✅ PESAN SESUAI KONDISI
    $message = $hariTelat > 0
        ? "✅ Denda Rp " . number_format($totalDenda) . " berhasil dikonfirmasi"
        : "✅ Pengembalian diproses - Tidak ada denda";

    return back()->with('success', $message);
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

    // 🔥 FILTER STATUS LENGKAP (FIX: Tambahkan filter denda aktif + historis)
    if ($request->filled('status')) {
        switch ($request->status) {
            case 'denda':
                // ✅ FIX UTAMA: Tampilkan SEMUA denda (aktif + historis)
                $query->where(function($q) {
                    $q->where('status', 'denda')  // Denda aktif (belum lunas)
                      ->orWhere(function($sub) {
                          $sub->where('status', 'selesai')  // Denda historis (sudah lunas)
                              ->where('is_denda', true);
                      });
                });
                break;

            case 'denda_belum':
                // Hanya denda aktif (belum lunas)
                $query->where('status', 'denda')
                      ->where('is_paid', false);
                break;

            case 'denda_lunas':
                // Hanya denda historis (sudah lunas)
                $query->where('status', 'selesai')
                      ->where('is_denda', true);
                break;

            default:
                // Filter status biasa
                $query->where('status', $request->status);
                break;
        }
    }

    $data = $query->latest()->get();

    // 🔥 PROSES DATA MAPPING
    $laporan = $data->map(function ($item) {
        // A. Hitung Denda
        $deadline = \Carbon\Carbon::parse($item->tgl_kembali);
        $tglAkhir = in_array($item->status, ['selesai', 'dikembalikan', 'pengembalian_diajukan'])
            ? \Carbon\Carbon::parse($item->updated_at)
            : now();

        $hariTelat = $tglAkhir->gt($deadline) ? $tglAkhir->diffInDays($deadline) : 0;
        $estimasiDenda = $hariTelat * 5000;

        // Ambil denda REAL dari tabel keuangans jika sudah lunas
        $dendaReal = null;
        $sudahDibayar = false;

        if ($item->keuangan && $item->keuangan->total_denda > 0) {
            $dendaReal = $item->keuangan->total_denda;
            $sudahDibayar = true;
        } elseif ($item->is_paid) {
            $dendaReal = $estimasiDenda;
            $sudahDibayar = true;
        }

        $item->hari_telat = $hariTelat;
        $item->total_denda = $estimasiDenda;        // Estimasi
        $item->denda_real = $dendaReal;             // Real dari keuangan
        $item->sudah_dibayar = $sudahDibayar;       // Flag sudah bayar
        $item->is_denda_lunas = $item->is_paid ?? false;

        // B. Ambil Variabel BUKU yang sedang dipinjam siswa
        $item->jumlah_buku_dihand = $item->user
            ? $item->user->peminjaman->where('status', 'dipinjam')->count()
            : 0;

        return $item;
    });

    // 🔥 TOTAL KAS (hanya yang sudah dibayar)
    $totalKas = Keuangan::whereHas('peminjaman', function($q) {
        $q->where('is_paid', true);
    })->sum('total_denda');

    // 🔥 STATISTIK LENGKAP
    $stats = [
        'dipinjam'      => $laporan->where('status', 'dipinjam')->count(),
        'selesai'       => $laporan->where('status', 'selesai')
                              ->where(function($q) {
                                  return $q->where('is_denda', false)->orWhereNull('is_denda');
                              })->count(),
        'selesai_denda' => $laporan->where('status', 'selesai')
                              ->where('is_denda', true)->count(),
        'denda_semua'   => $laporan->filter(function($item) {
                              return $item->status == 'denda'
                                  || ($item->status == 'selesai' && $item->is_denda);
                          })->count(),
        'denda_belum'   => $laporan->where('status', 'denda')
                              ->where('is_paid', false)->count(),
        'denda_lunas'   => $laporan->filter(function($item) {
                              return ($item->status == 'denda' && $item->is_paid)
                                  || ($item->status == 'selesai' && $item->is_denda);
                          })->count(),
        'pelanggar'     => $laporan->where('hari_telat', '>', 0)->count(),
        'potensi_denda' => $laporan->sum('total_denda'),
        'kas_denda'     => $totalKas,
    ];

    return view('dashboard.petugas.laporan.index', compact('laporan', 'stats', 'totalKas'));
}
}
