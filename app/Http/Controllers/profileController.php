<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Anggota;

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

        // VALIDASI
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'kelas' => 'required|in:X,XI,XII',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        // DATA USER
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
        ];

        // FOTO
      if ($request->hasFile('photo')) {

    if ($user->photo && Storage::disk('public')->exists($user->photo)) {
        Storage::disk('public')->delete($user->photo);
    }

    $user->photo = $request->file('photo')->store('photos', 'public');
}

        $user->update($data);

        // UPDATE ANGGOTA
        if ($user->anggota) {
            $user->anggota->update([
                'nis' => $request->nis,
                'kelas' => $request->kelas,
            ]);
        }

        return back()->with('success', 'Profile berhasil diupdate');
    }
}
