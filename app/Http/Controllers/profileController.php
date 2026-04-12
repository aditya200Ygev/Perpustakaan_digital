<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use App\Models\Anggota;

class ProfileController extends Controller
{
    /**
     * Show Edit Profile Form
     */
    public function edit()
    {
        $user = Auth::user();
        $user->load('anggota'); // ✅ Eager load relasi
        $anggota = $user->anggota;

        return view('profile.edit', compact('user', 'anggota'));
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

        // ✅ FIX: Update data Anggota jika ada (PAKAI PHP if, BUKAN Blade @if)
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
        return view('profile.password');
    }

    /**
     * Update Password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::min(8)->letters()->numbers(), 'confirmed'],
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

        return back()->with('success', '✅ Password berhasil diubah!');
    }
}
