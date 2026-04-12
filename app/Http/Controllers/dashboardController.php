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
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules\Password as PasswordRule;

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

    // Hitung Stok & Pinjaman untuk Grafik
    $totalBuku = Buku::count(); // Total jenis buku atau total eksemplar
    $totalPinjamAktif = Peminjaman::where('status', 'dipinjam')->count();
    $bukuTersedia = $totalBuku - $totalPinjamAktif;

    // Ambil data pengembalian yang perlu di-ACC (Status: pengembalian_diajukan)
    // Variabel ini yang menyebabkan error "Undefined variable $pengembalian_diajukan" jika tidak ada
    $pengembalian_diajukan = Peminjaman::with(['user', 'buku'])
        ->where('status', 'pengembalian_diajukan')
        ->latest()
        ->get();

    return view('dashboard.petugas.index', [
        'user'              => $user,
        'totalAnggota'      => Anggota::count(),
        'totalBuku'         => $totalBuku,
        'totalPinjamAktif'  => $totalPinjamAktif,
        'bukuTersedia'      => $bukuTersedia,

        // Menghitung denda yang BELUM dibayar
        'pendingDenda'      => Peminjaman::where('status', 'denda')
                                    ->where('is_paid', false)
                                    ->count(),

        // Total Kas dari tabel keuangan
        'totalKas'          => Keuangan::sum('total_denda'),

        'recentBuku'        => Buku::with('kategori')->latest()->take(5)->get(),

        // List untuk konfirmasi denda
        'pendingDendaList'  => Peminjaman::with('user')
                                    ->where('status', 'denda')
                                    ->where('is_paid', false)
                                    ->latest()
                                    ->take(3)
                                    ->get(),

        // Variabel untuk Antrean Kembali di Blade baris 183
        'pengembalian_diajukan' => $pengembalian_diajukan,

        'anggota'           => Anggota::with('user')->get(),
    ]);
}

    /**
     * Dashboard untuk Kepala Perpustakaan
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
/**
 * LAPORAN LENGKAP - KEPALA PERPUSTAKAAN
 */
/**
 * LAPORAN LENGKAP KEPALA PERPUSTAKAAN
 * - Menampilkan denda real dari tabel keuangans per orang
 */
/**
 * LAPORAN LENGKAP KEPALA PERPUSTAKAAN
 * - Filter lengkap + Total denda akurat
 */
/**
 * LAPORAN LENGKAP KEPALA PERPUSTAKAAN
 * - Filter lengkap + Total denda akurat + Statistik dinamis
 */
/**
 * LAPORAN LENGKAP KEPALA PERPUSTAKAAN
 * - Filter "Denda" menampilkan SEMUA (lunas + belum) dengan badge status
 */
/**
 * LAPORAN LENGKAP KEPALA PERPUSTAKAAN
 * - Filter "Denda" menampilkan SEMUA: aktif + historis (sudah lunas)
 */

    // ... method lainnya ...

    /**
     * LAPORAN LENGKAP KEPALA PERPUSTAKAAN
     * - Filter "Denda" menampilkan SEMUA: aktif + historis (sudah lunas)
     */

    /**
     * LAPORAN LENGKAP KEPALA PERPUSTAKAAN
     * - Filter "Denda" menampilkan SEMUA: aktif (status='denda') + historis (status='selesai' + is_denda=true)
     */
    /**
     * LAPORAN LENGKAP KEPALA PERPUSTAKAAN
     * - Filter "Denda" menampilkan SEMUA: aktif + historis
     */
    public function laporanKepala(Request $request)
    {
        // 1. Inisialisasi Query dengan relasi
        $query = Peminjaman::with(['user.anggota', 'buku', 'keuangan']);

        // 2. 🔍 Filter Tanggal
        if ($request->filled('tgl_mulai') && $request->filled('tgl_selesai')) {
            $query->whereBetween('tgl_pinjam', [
                $request->tgl_mulai,
                $request->tgl_selesai
            ]);
        }

        // 3. 🔍 Filter Status Lengkap - ✅ FIX UTAMA DI SINI
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'diajukan':
                    $query->where('status', 'diajukan');
                    break;

                case 'dipinjam':
                    $query->where('status', 'dipinjam');
                    break;

                case 'selesai':
                    // Selesai TANPA riwayat denda
                    $query->where('status', 'selesai')
                          ->where(function($q) {
                              $q->where('is_denda', false)
                                ->orWhereNull('is_denda');
                          });
                    break;

                case 'selesai_denda':
                    // Selesai DENGAN riwayat denda (pernah telat)
                    $query->where('status', 'selesai')
                          ->where('is_denda', true);
                    break;

                // ✅ FIX UTAMA: Filter "Denda" = Aktif + Historis
                case 'denda':
                    $query->where(function($q) {
                        // Ambil: status='denda' (aktif) ATAU (status='selesai' + is_denda=true)
                        $q->where('status', 'denda')
                          ->orWhere(function($sub) {
                              $sub->where('status', 'selesai')
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
            }
        }

        // 4. 🔍 Filter Checkbox: Hanya Tampilkan Data dengan Denda
        if ($request->filled('hanya_denda') && $request->hanya_denda == '1') {
            $query->where(function($q) {
                $q->where('hari_telat', '>', 0)
                  ->orWhere('status', 'denda')
                  ->orWhere('is_denda', true)
                  ->orWhereHas('keuangan', function($sub) {
                      $sub->where('total_denda', '>', 0);
                  });
            });
        }

        // 5. 🔍 Search Nama/Buku
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

        // 6. Ambil Data
        $data = $query->latest()->get();

        // 7. 🔥 Proses Data Mapping + Hitung Denda
        $laporan = $data->map(function ($item) {
            // Hitung hari telat
            $deadline = \Carbon\Carbon::parse($item->tgl_kembali);
            $tglAkhir = in_array($item->status, ['selesai', 'dikembalikan', 'pengembalian_diajukan'])
                ? \Carbon\Carbon::parse($item->updated_at)
                : \Carbon\Carbon::now();

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

            // Assign ke object untuk view
            $item->hari_telat = $hariTelat;
            $item->estimasi_denda = $estimasiDenda;
            $item->denda_real = $dendaReal;
            $item->sudah_dibayar = $sudahDibayar;
            $item->is_denda_lunas = $item->is_paid ?? false;

            return $item;
        });

        // 8. 💰 TOTAL KAS
        $totalKas = Keuangan::whereHas('peminjaman', function($q) {
            $q->where('is_paid', true);
        })->sum('total_denda');

        // 9. 📊 STATISTIK
        $stats = [
            'total_transaksi' => $laporan->count(),
            'diajukan'        => $laporan->where('status', 'diajukan')->count(),
            'sedang_dipinjam' => $laporan->where('status', 'dipinjam')->count(),
            'selesai'         => $laporan->where('status', 'selesai')
                                  ->where(function($q) {
                                      return $q->where('is_denda', false)->orWhereNull('is_denda');
                                  })->count(),
            'selesai_denda'   => $laporan->where('status', 'selesai')
                                  ->where('is_denda', true)->count(),
            'denda_semua'     => $laporan->filter(function($item) {
                                  return $item->status == 'denda'
                                      || ($item->status == 'selesai' && $item->is_denda);
                              })->count(),
            'denda_belum'     => $laporan->where('status', 'denda')
                                  ->where('is_paid', false)->count(),
            'denda_lunas'     => $laporan->filter(function($item) {
                                  return ($item->status == 'denda' && $item->is_paid)
                                      || ($item->status == 'selesai' && $item->is_denda);
                              })->count(),
            'pelanggar'       => $laporan->where('hari_telat', '>', 0)->count(),
            'potensi_denda'   => $laporan->sum('estimasi_denda'),
            'kas_denda'       => $totalKas,
        ];

        return view('dashboard.kepala.laporan.index', compact('laporan', 'stats', 'totalKas'));
    }


    /**
     * Show Edit Profile Form
     */
    public function edit()
    {
        $user = Auth::user();
        $user->load('anggota'); // ✅ Eager load relasi
        $anggota = $user->anggota;

        return view('dashboard.profile.edit', compact('user', 'anggota'));
    }

    /**
     * Update Profile Data
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $role = $user->role;

        // ✅ Rules dasar untuk semua role
        $rules = [
            'name'    => ['required', 'string', 'max:100'],
            'email'   => ['required', 'email', 'max:100', 'unique:users,email,' . $user->id],
            'no_telp' => ['required', 'string', 'max:20'],
            'alamat'  => ['required', 'string', 'max:255'],
            'photo'   => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ];

        $messages = [
            'name.required'    => 'Nama lengkap wajib diisi.',
            'email.required'   => 'Email wajib diisi.',
            'email.email'      => 'Format email tidak valid.',
            'email.unique'     => 'Email sudah digunakan oleh akun lain.',
            'no_telp.required' => 'Nomor telepon wajib diisi.',
            'alamat.required'  => 'Alamat wajib diisi.',
            'photo.image'      => 'File harus berupa gambar.',
            'photo.mimes'      => 'Format gambar: jpg, jpeg, png.',
            'photo.max'        => 'Ukuran gambar maksimal 2MB.',
        ];

        // ✅ Rules tambahan untuk Anggota
        if ($role === 'anggota' && $user->anggota) {
            $rules['nis']     = ['required', 'string', 'max:20', 'unique:anggota,nis,' . $user->anggota->id];
            $rules['kelas']   = ['required', 'string', 'max:20'];
            $rules['jurusan'] = ['required', 'string', 'max:50'];

            $messages['nis.required']     = 'NIS wajib diisi.';
            $messages['nis.unique']       = 'NIS sudah terdaftar.';
            $messages['kelas.required']   = 'Kelas wajib diisi.';
            $messages['jurusan.required'] = 'Jurusan wajib dipilih.';
        }

        // ✅ Rules tambahan untuk Petugas/Kepala (NIP)
        if (in_array($role, ['petugas', 'kep_perpustakaan'])) {
            $rules['nip'] = ['required', 'string', 'max:30', 'unique:users,nip,' . $user->id];
            $messages['nip.required'] = 'NIP wajib diisi.';
            $messages['nip.unique']   = 'NIP sudah terdaftar.';
        }

        $validated = $request->validate($rules, $messages);

        // ✅ Siapkan data untuk update User
        $dataUser = [
            'name'    => $validated['name'],
            'email'   => $validated['email'],
            'no_telp' => $validated['no_telp'],
            'alamat'  => $validated['alamat'],
        ];

        // ✅ Handle Upload Foto
        if ($request->hasFile('photo')) {
            if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }
            $dataUser['photo'] = $request->file('photo')->store('users', 'public');
        }

        // ✅ Update NIP untuk Petugas/Kepala
        if (in_array($role, ['petugas', 'kep_perpustakaan'])) {
            $dataUser['nip'] = $validated['nip'] ?? $user->nip;
        }

        // ✅ Update data User
        $user->update($dataUser);

        // ✅ Update data Anggota jika ada (PAKAI PHP if, BUKAN Blade @if)
        if ($role === 'anggota' && $user->anggota) {
            $user->anggota->update([
                'nis'     => $validated['nis'],
                'kelas'   => $validated['kelas'],
                'jurusan' => $validated['jurusan'],
            ]);
        }

        return back()->with('success', '✅ Profile berhasil diperbarui!');
    }

    /**
     * Show Change Password Form
     */
    public function editPassword()
    {
        return view('dashboard.profile.password');
    }

    /**
     * Update Password (Ganti Password untuk user yang sudah login)
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', PasswordRule::min(8)->letters()->numbers(), 'confirmed'],
        ], [
            'current_password.required' => 'Password lama wajib diisi.',
            'current_password.current_password' => 'Password lama tidak sesuai.',
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password baru minimal 8 karakter.',
            'password.letters' => 'Password baru harus mengandung huruf.',
            'password.numbers' => 'Password baru harus mengandung angka.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // ✅ Opsional: Logout agar login ulang dengan password baru (lebih aman)
        // Auth::logout();
        // return redirect()->route('login')->with('success', '✅ Password berhasil diubah. Silakan login ulang.');

        return back()->with('success', '✅ Password berhasil diubah!');
    }

    /**
     * Show Forgot Password Form (Lupa Password - untuk guest)
     */
    public function showForgotPassword()
    {
        return view('dashboard.petugas.anggota.-password');
    }

    /**
     * Send Reset Password Link
     */
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => ['required', 'email']], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
        ]);

        $status = Password::sendResetLink($request->only('email'));

        // ✅ Tampilkan link reset di log untuk local testing (MAIL_MAILER=log)
        if ($status === Password::RESET_LINK_SENT && config('mail.mailer') === 'log') {
            $user = User::where('email', $request->email)->first();
            if ($user) {
                $token = Password::createToken($user);
                $url = url(route('password.reset', ['token' => $token, 'email' => $request->email], false));
                \Log::info("🔐 RESET LINK: $url");
            }
        }

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }

    /**
     * Show Reset Password Form (dengan token)
     */
    public function showResetForm(Request $request, $token)
    {
        return view('dashboard.profile.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    /**
     * Reset Password (proses update password baru)
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', PasswordRule::min(8)->letters()->numbers(), 'confirmed'],
        ], [
            'token.required' => 'Token reset tidak valid.',
            'email.required' => 'Email wajib diisi.',
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password baru minimal 8 karakter.',
            'password.letters' => 'Password baru harus mengandung huruf.',
            'password.numbers' => 'Password baru harus mengandung angka.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('success', '✅ Password berhasil direset! Silakan login dengan password baru.')
            : back()->withErrors(['email' => [__($status)]]);
    }
}


