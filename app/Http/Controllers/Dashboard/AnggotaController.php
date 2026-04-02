<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Anggota;
use App\Models\User;
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
}
