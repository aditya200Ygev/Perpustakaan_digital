<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Anggota;
use App\Models\kategori;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\Keuangan; // Pastikan model Keuangan sudah di-import
use App\Models\User;
use App\Http\Controllers\Dashboard\AnggotaController;
use App\Http\Controllers\Dashboard\BukuController;
use App\Http\Controllers\PeminjamanController;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Dashboard untuk Anggota
     */
    public function anggota()
    {
        $user = auth()->user();

        $dipinjam = Peminjaman::with('buku')
            ->where('user_id', $user->id)
            ->where('status', 'dipinjam')
            ->get();

        $riwayat = Peminjaman::with('buku')
            ->where('user_id', $user->id)
            ->latest()
            ->limit(5)
            ->get();

        $totalPinjam = Peminjaman::where('user_id', $user->id)->count();

        return view('dashboard.anggota.index', compact(
            'user',
            'dipinjam',
            'riwayat',
            'totalPinjam'
        ));
    }

    /**
     * Dashboard untuk Petugas
     */
    public function petugas()
    {
        $user = Auth::user();

        return view('dashboard.petugas.index', [
            'user'             => $user,
            'totalAnggota'     => Anggota::count(),
            'totalBuku'        => Buku::count(),

            // 1. Menghitung denda yang BELUM dibayar (dari tabel peminjamans)
            'pendingDenda'     => Peminjaman::where('status', 'denda')
                                    ->where('is_paid', false)
                                    ->count(),

            // 2. Menghitung TOTAL KAS (dari tabel keuangans)
            // Menggunakan sum('total_denda') karena kolomnya ada di tabel keuangans
            'totalKas'         => Keuangan::sum('total_denda'),

            'recentBuku'       => Buku::with('kategori')->latest()->take(5)->get(),

            'pendingDendaList' => Peminjaman::with('user')
                                    ->where('status', 'denda')
                                    ->where('is_paid', false)
                                    ->latest()
                                    ->take(3)
                                    ->get(),

            'anggota'          => Anggota::with('user')->get(),
        ]);
    }

    /**
     * Dashboard untuk Kepala
     */
   public function kepala()
{
    $user = Auth::user();

    // Statistik Utama
    $stats = [
        'total_buku'      => Buku::count(),
        'total_anggota'   => Anggota::count(),
        'total_petugas'   => User::where('role', 'petugas')->count(),
        'buku_dipinjam'   => Peminjaman::where('status', 'dipinjam')->count(),
    ];

    // Data untuk Grafik atau Tren (Opsional)
    $peminjamanTerbaru = Peminjaman::with(['user', 'buku'])
                        ->latest()
                        ->take(5)
                        ->get();

    // Data kategori untuk melihat distribusi buku
    $kategoriStats = \App\Models\Kategori::withCount('bukus')->get();

    return view('dashboard.kepala.index', compact('user', 'stats', 'peminjamanTerbaru', 'kategoriStats'));
}

    /**
     * Fitur tambahan lainnya
     */
    public function pengembalian()
    {
        $user = auth()->user();
        $data = Peminjaman::with('buku')
            ->where('user_id', $user->id)
            ->whereIn('status', ['dipinjam', 'denda'])
            ->latest()
            ->get();

        return view('dashboard.anggota.pengembalian', compact('data'));
    }

    public function denda()
    {
        $user = auth()->user();
        $denda = Peminjaman::with('buku')
            ->where('user_id', $user->id)
            ->where('status', 'denda')
            ->get();

        return view('dashboard.anggota.denda', compact('denda'));
    }

    public function dataDenda()
    {
        $denda = Peminjaman::with(['user', 'buku'])
            ->where('status', 'denda')
            ->where('is_paid', false)
            ->latest()
            ->get();

        return view('dashboard.petugas.denda.index', compact('denda'));
    }
    public function dashboardKepala()
    {
        $user = Auth::user();

        // 1. Statistik Ringkas untuk Counter Cards
        $stats = [
            'total_buku'    => Buku::count(),
            'total_anggota' => Anggota::count(),
            'total_petugas' => User::where('role', 'petugas')->count(),
            'total_denda'   => Peminjaman::where('is_paid', true)->sum('denda'),
        ];

        // 2. Data Peminjaman Terbaru (untuk tabel aktivitas)
        $peminjamanTerbaru = Peminjaman::with(['user', 'buku'])
                            ->latest()
                            ->take(5)
                            ->get();

        // 3. Data Distribusi Buku per Kategori
        $kategoriStats = \App\Models\Kategori::withCount('bukus')->get();

        return view('dashboard.kepala.index', compact('user', 'stats', 'peminjamanTerbaru', 'kategoriStats'));
    }

    /**
     * Halaman Monitoring Anggota & Petugas
     */
    // File: app/Http/Controllers/DashboardController.php

public function dataSdmKepala()
{
    // Mengambil data petugas dari tabel users
    $petugas = User::where('role', 'petugas')->get();

    // Mengambil data anggota lengkap dengan relasi user-nya
    $anggota = Anggota::with('user')->latest()->get();

    // Pastikan path ini sesuai: resources/views/dashboard/kepala/anggota/index.blade.php
    return view('dashboard.kepala.anggota.index', compact('petugas', 'anggota'));
}

    /**
     * Halaman Laporan Denda untuk Kepala
     */
    public function laporanDendaKepala()
    {
        // Ambil data peminjaman yang memiliki denda
        $denda = Peminjaman::where('denda', '>', 0)
                    ->with(['user', 'buku'])
                    ->latest()
                    ->get();

        // Rekapitulasi Keuangan Denda
        $finance = [
            'total_masuk' => $denda->where('is_paid', true)->sum('denda'),
            'total_piutang' => $denda->where('is_paid', false)->sum('denda'),
        ];

        return view('dashboard.kepala.denda.index', compact('denda', 'finance'));
    }
public function bukuKepala(Request $request)
{
    // 1. Ambil kategori untuk filter (jika ada dropdown filter di view kepala)
    $kategoris = \App\Models\Kategori::all();

    // 2. Query Buku
    $query = \App\Models\Buku::with('kategori');

    // 3. Filter & Search (Opsional, agar dashboard kepala bisa mencari buku)
    if ($request->search) {
        $query->where('judul', 'like', '%' . $request->search . '%');
    }

    // 4. Eksekusi dengan paginate agar fungsi ->total() dan ->links() bekerja
    $bukus = $query->latest()->paginate(10);

    // 5. Pastikan compact menggunakan 'bukus' (sama dengan nama variabel)
    return view('dashboard.kepala.buku.index', compact('bukus', 'kategoris'));
}
public function laporanKepala(Request $request)
{
    // 1. Inisialisasi Query dengan relasi lengkap
    $query = Peminjaman::with(['user.anggota', 'buku', 'keuangan']);

    // 2. Filter Tanggal
    if ($request->filled('tgl_mulai') && $request->filled('tgl_selesai')) {
        $query->whereBetween('tgl_pinjam', [
            $request->tgl_mulai,
            $request->tgl_selesai
        ]);
    }

    // 3. Filter Status
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    // 4. Ambil Data
    $data = $query->latest()->get();

    // 5. Hitung Denda Otomatis (Logika identik dengan petugas)
    $laporan = $data->map(function ($item) {
        $deadline = \Carbon\Carbon::parse($item->tgl_kembali);

        $tglAkhir = $item->status == 'selesai'
            ? \Carbon\Carbon::parse($item->updated_at)
            : now();

        $hariTelat = $tglAkhir->gt($deadline)
            ? $tglAkhir->diffInDays($deadline)
            : 0;

        $item->hari_telat = $hariTelat;
        $item->denda_estimasi = $hariTelat * 5000; // Denda yang seharusnya

        return $item;
    });

    // 6. Ambil Total Kas dari Model Keuangan (Uang Real Masuk)
    $totalKas = Keuangan::sum('total_denda');

    return view('dashboard.kepala.laporan.index', compact('laporan', 'totalKas'));
}
}
