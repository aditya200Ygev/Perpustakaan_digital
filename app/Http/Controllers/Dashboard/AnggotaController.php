<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Anggota;
use App\Models\kategori;
use App\Models\Buku;
use App\Models\User;
use App\Models\Peminjaman;
use App\Models\Keuangan;
use Illuminate\Support\Facades\Storage;

class AnggotaController extends Controller
{
    public function index()
    {
        $anggota = Anggota::with('user')->get();

        return view('dashboard.petugas.anggota.index', compact('anggota'));
    }

    public function edit($id)
    {
        $anggota = Anggota::with('user')->findOrFail($id);

        return view('dashboard.petugas.anggota.edit', compact('anggota'));
    }

    public function update(Request $request, $id)
    {
        $anggota = Anggota::with('user')->findOrFail($id);

        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'nis' => 'required',
            'kelas' => 'required',
            'jurusan' => 'required',
            'photo' => 'nullable|image'
        ]);

        // update user
        $user = $anggota->user;
        $user->name = $request->name;
        $user->email = $request->email;

        // upload foto
        if ($request->hasFile('photo')) {
            if ($user->photo) {
                Storage::delete('public/' . $user->photo);
            }

            $path = $request->file('photo')->store('users', 'public');
            $user->photo = $path;
        }

        $user->save();

        // update anggota
        $anggota->update([
            'nis' => $request->nis,
            'kelas' => $request->kelas,
            'jurusan' => $request->jurusan,
        ]);

        return redirect()->route('petugas.anggota')
            ->with('success', 'Data anggota berhasil diupdate');
    }
    public function updateProfile(Request $request)
{
    $user = auth()->user();
    $anggota = $user->anggota;

    $request->validate([
        'name' => 'required',
        'email' => 'required|email',
        'nis' => 'required',
        'kelas' => 'required',
        'jurusan' => 'required',
        'photo' => 'nullable|image'
    ]);

    // update user
    $user->name = $request->name;
    $user->email = $request->email;

    // upload foto
    if ($request->hasFile('photo')) {

        if ($user->photo && Storage::disk('public')->exists($user->photo)) {
            Storage::disk('public')->delete($user->photo);
        }

        $path = $request->file('photo')->store('users', 'public');
        $user->photo = $path;
    }

    $user->save();

    // update anggota
    if ($anggota) {
        $anggota->update([
            'nis' => $request->nis,
            'kelas' => $request->kelas,
            'jurusan' => $request->jurusan,
        ]);
    }

    return back()->with('success', 'Profile berhasil diupdate');
}
public function editProfile()
{
    $user = auth()->user();

    return view('profile.edit', compact('user'));
}

public function updatePetugas(Request $request)
{
    $user = auth()->user();

    $request->validate([
        'name' => 'required',
        'email' => 'required|email',
        'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
    ]);

    // update data user
    $user->name = $request->name;
    $user->email = $request->email;
if ($request->hasFile('photo')) {

    // HANYA jalankan kalau benar-benar ada dan valid
    if (!empty($user->photo) && is_string($user->photo)) {

        try {
            if (Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }
        } catch (\Exception $e) {
            // abaikan error lama (biar tidak crash)
        }


    // simpan file baru
    $path = $request->file('photo')->store('photos', 'public');

    $user->photo = $path;
}
    }

    $user->save();

    return back()->with('success', 'Profile petugas berhasil diupdate');
}
public function editPetugas()
{
    $user = auth()->user();

    return view('dashboard.petugas.profile.edit', compact('user'));
}


public function riwayat(Request $request)
{
    // ✅ VALIDASI: Pastikan user sudah login
    $user = Auth::user();

    if (!$user) {
        return redirect()->route('login')
            ->with('error', 'Silakan login terlebih dahulu.');
    }

    // ✅ Query dasar
    $query = Peminjaman::with(['buku', 'keuangan'])
        ->where('user_id', $user->id);  // Sekarang $user->id aman

    // 🔍 Filter Status (Opsional)
    if ($request->filled('filter_status')) {
        switch ($request->filter_status) {
            case 'dipinjam':
                $query->where('status', 'dipinjam');
                break;

            case 'selesai':
                // Selesai TANPA denda
                $query->where('status', 'selesai')
                      ->where(function($q) {
                          $q->where('is_denda', false)
                            ->orWhereNull('is_denda');
                      });
                break;

            case 'selesai_denda':
                // Selesai DENGAN riwayat denda
                $query->where('status', 'selesai')
                      ->where('is_denda', true);
                break;

            case 'denda':
                // Semua denda (aktif + historis)
                $query->where(function($q) {
                    $q->where('status', 'denda')
                      ->orWhere(function($sub) {
                          $sub->where('status', 'selesai')
                              ->where('is_denda', true);
                      });
                });
                break;

            case 'denda_belum':
                $query->where('status', 'denda')
                      ->where('is_paid', false);
                break;

            case 'denda_lunas':
                $query->where('status', 'selesai')
                      ->where('is_denda', true);
                break;
        }
    }

    // ✅ Ambil data
    $riwayat = $query->latest()->get();

    return view('dashboard.anggota.riwayat', compact('riwayat'));
}

/**
 * RIWAYAT SEMUA ANGGOTA - KEPALA PERPUSTAKAAN
 */
public function riwayatKepala(Request $request)
{
    // ✅ Validasi role
    if (Auth::user()->role !== 'kep_perpustakaan') {
        abort(403, 'Akses ditolak');
    }

    $query = Peminjaman::with(['user.anggota', 'buku', 'keuangan']);

    // 🔍 Filter Search
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->whereHas('user', function($u) use ($search) {
                $u->where('name', 'like', "%$search%")
                  ->orWhereHas('anggota', function($a) use ($search) {
                      $a->where('nis', 'like', "%$search%");
                  });
            })
            ->orWhereHas('buku', function($b) use ($search) {
                $b->where('judul', 'like', "%$search%");
            });
        });
    }

    // 🔍 Filter per Anggota
    if ($request->filled('anggota_id')) {
        $query->where('user_id', $request->anggota_id);
    }

    // 🔍 Filter Tanggal
    if ($request->filled('from') && $request->filled('to')) {
        $query->whereBetween('tgl_pinjam', [$request->from, $request->to]);
    }

    // 🔍 Filter Status
    if ($request->filled('status')) {
        switch ($request->status) {
            case 'denda':
                $query->where(function($q) {
                    $q->where('status', 'denda')
                      ->orWhere(fn($sub) => $sub->where('status', 'selesai')->where('is_denda', true));
                });
                break;
            case 'selesai':
                $query->where('status', 'selesai')
                      ->where(fn($q) => $q->where('is_denda', false)->orWhereNull('is_denda'));
                break;
            case 'selesai_denda':
                $query->where('status', 'selesai')->where('is_denda', true);
                break;
            default:
                $query->where('status', $request->status);
                break;
        }
    }

    // 🔍 Filter Hanya Denda
    if ($request->filled('only_denda') && $request->only_denda == '1') {
        $query->where(function($q) {
            $q->where('status', 'denda')
              ->orWhere('is_denda', true)
              ->orWhereHas('keuangan', fn($sub) => $sub->where('total_denda', '>', 0));
        });
    }

    $riwayat = $query->latest()->paginate(20);
    $listAnggota = \App\Models\Anggota::with('user')->orderBy('kelas')->orderBy('nis')->get();

    $stats = [
        'total_transaksi' => $riwayat->total(),
        'dipinjam' => (clone $query)->where('status', 'dipinjam')->count(),
        'selesai' => (clone $query)->where('status', 'selesai')->count(),
        'denda_aktif' => (clone $query)->where('status', 'denda')->count(),
    ];

    return view('dashboard.kepala.riwayat.index', compact('riwayat', 'listAnggota', 'stats'));
}

/**
 * RIWAYAT PER ANGGOTA SPESIFIK - KEPALA PERPUSTAKAAN
 */
public function riwayatPerAnggota(Request $request, $user_id)
{
    // ✅ Validasi role
    if (Auth::user()->role !== 'kep_perpustakaan') {
        abort(403, 'Akses ditolak');
    }

    // ✅ Validasi anggota ada
    $anggota = \App\Models\Anggota::with('user')->where('user_id', $user_id)->firstOrFail();

    $query = Peminjaman::with(['buku', 'keuangan'])
        ->where('user_id', $user_id);

    // 🔍 Filter Tanggal (opsional)
    if ($request->filled('from') && $request->filled('to')) {
        $query->whereBetween('tgl_pinjam', [$request->from, $request->to]);
    }

    // 🔍 Filter Status (opsional)
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    $riwayat = $query->latest()->paginate(20);

    return view('dashboard.kepala.riwayat.index', compact('riwayat', 'anggota'));
}
}

