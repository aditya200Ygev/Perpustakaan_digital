<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Anggota;
use app\Models\kategori;
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



public function riwayat()
{
    $riwayat = \App\Models\Peminjaman::with('buku')
        ->where('user_id', auth()->id())
        ->latest()
        ->get();
         $query = Peminjaman::with(['user', 'buku', 'user.anggota', 'keuangan']);

    // ... (kode filter tetap sama) ...

    // 6. 🔥 AMBIL DATA DENGAN PAGINATION
    $peminjaman = $query->latest()->paginate(10);

    return view('dashboard.anggota.riwayat', compact('riwayat'));
}
}
