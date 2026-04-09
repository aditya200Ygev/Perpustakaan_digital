//<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
{
    $user = Auth::user();

    $request->validate([
        'name' => 'required',
        'email' => 'required|email',
        'nis' => 'required',
        'kelas' => 'required',
        'jurusan' => 'nullable',
        'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
    ]);

    $dataUser = [
        'name' => $request->name,
        'email' => $request->email,
        'no_telp' => $request->no_telp,
    ];

    // FOTO
    if ($request->hasFile('photo')) {

        if ($user->photo && Storage::disk('public')->exists($user->photo)) {
            Storage::disk('public')->delete($user->photo);
        }

        $dataUser['photo'] = $request->file('photo')->store('users', 'public');
    }

    $user->update($dataUser);

    // UPDATE ANGOTA
    if ($user->anggota) {
        $user->anggota->update([
            'nis' => $request->nis,
            'kelas' => $request->kelas,
            'jurusan' => $request->jurusan ?? $user->anggota->jurusan,
        ]);
    }

    return back()->with('success', 'Profile berhasil diupdate');
}
}

