<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /* ═══════════════════════════════════════
     |  SHOW FORMS
     ═══════════════════════════════════════ */

    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user()->role);
        }
        return view('auth.login');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user()->role);
        }
        return view('auth.register');
    }

    /* ═══════════════════════════════════════
     |  LOGIN
     ═══════════════════════════════════════ */

    public function login(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
            'role'     => ['required', 'in:anggota,petugas,kep_perpustakaan'],
        ], [
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Kata sandi wajib diisi.',
            'role.required'     => 'Pilih peran terlebih dahulu.',
        ]);

        $user = User::where('email', $request->email)
                    ->where('role', $request->role)
                    ->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => 'Email, kata sandi, atau peran tidak sesuai.',
            ]);
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()
            ->intended($this->dashboardRoute($user->role))
            ->with('success', 'Selamat datang kembali, ' . $user->name . '!');
    }

    /* ═══════════════════════════════════════
     |  REGISTER
     ═══════════════════════════════════════ */

    public function register(Request $request)
    {
        $role = $request->input('role');


        $rules = [
            'name'     => ['required', 'string', 'max:100'],
            'email'    => ['required', 'email', 'max:100', 'unique:users,email'],
            'password' => ['required', Password::min(8)->letters()->numbers(), 'confirmed'],
            'no_telp'  => ['required', 'string', 'max:20'],
            'alamat'   => ['required', 'string', 'max:255'],
            'role'     => ['required', 'in:anggota,petugas,kep_perpustakaan'],
        ];

        $messages = [
            'name.required'              => 'Nama lengkap wajib diisi.',
            'email.required'             => 'Email wajib diisi.',
            'email.unique'               => 'Email sudah terdaftar, gunakan email lain.',
            'email.email'                => 'Format email tidak valid.',
            'password.required'          => 'Kata sandi wajib diisi.',
            'password.min'               => 'Kata sandi minimal 8 karakter.',
            'password.letters'           => 'Kata sandi harus mengandung huruf.',
            'password.numbers'           => 'Kata sandi harus mengandung angka.',
            'password.confirmed'         => 'Konfirmasi kata sandi tidak cocok.',
            'no_telp.required'           => 'Nomor telepon wajib diisi.',
            'alamat.required'            => 'Alamat wajib diisi.',
        ];

        // ── Validasi tambahan per role ──
        if ($role === 'anggota') {
            $rules['nis']     = ['required', 'string', 'max:20', 'unique:anggota,nis'];
            $rules['kelas']   = ['required', 'string', 'max:20'];
            $rules['jurusan'] = ['required', 'string', 'max:50'];

            $messages['nis.required']     = 'NIS wajib diisi.';
            $messages['nis.unique']       = 'NIS sudah terdaftar.';
            $messages['kelas.required']   = 'Kelas wajib diisi.';
            $messages['jurusan.required'] = 'Jurusan wajib dipilih.';
        } else {
            $rules['nip'] = ['required', 'string', 'max:30', 'unique:users,nip'];
            $messages['nip.required'] = 'NIP wajib diisi.';
            $messages['nip.unique']   = 'NIP sudah terdaftar.';
        }

        $validated = $request->validate($rules, $messages);

        $user = User::create([
            'name'    => $validated['name'],
            'email'   => $validated['email'],
            'password'=> Hash::make($validated['password']),
            'role'    => $validated['role'],
            'no_telp' => $validated['no_telp'],
            'alamat'  => $validated['alamat'],
            'nip'     => $validated['nip'] ?? null,
        ]);


        if ($role === 'anggota') {
            Anggota::create([
                'user_id' => $user->id,
                'nis'     => $validated['nis'],
                'kelas'   => $validated['kelas'],
                'jurusan' => $validated['jurusan'],
            ]);
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect($this->dashboardRoute($user->role))
            ->with('success', 'Akun berhasil dibuat! Selamat datang, ' . $user->name . '!');
    }

    /* ═══════════════════════════════════════
     |  LOGOUT
     ═══════════════════════════════════════ */

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Anda telah berhasil keluar dari sistem.');
    }

    /* ═══════════════════════════════════════
     |  HELPERS
     ═══════════════════════════════════════ */

    private function dashboardRoute(string $role): string
    {
        return match($role) {
            'anggota'          => route('dashboard.anggota'),
            'petugas'          => route('dashboard.petugas'),
            'kep_perpustakaan' => route('dashboard.kepala'),
            default            => '/',
        };
    }

    private function redirectByRole(string $role)
    {
        return redirect($this->dashboardRoute($role));
    }
}
