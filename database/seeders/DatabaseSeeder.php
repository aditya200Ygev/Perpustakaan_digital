<?php

namespace Database\Seeders;

use App\Models\Anggota;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Anggota ──────────────────────────────────
        $anggota = User::create([
            'name'    => 'Budi Santoso',
            'email'   => 'anggota@demo.com',
            'password'=> Hash::make('password123'),
            'role'    => 'anggota',
            'no_telp' => '081234567890',
            'alamat'  => 'Jl. Merdeka No. 1, Jakarta',
        ]);

        Anggota::create([
            'user_id' => $anggota->id,
            'nis'     => '2024001',
            'kelas'   => 'XII IPA 1',
            'jurusan' => 'IPA',
        ]);

        // ── Petugas ──────────────────────────────────
        User::create([
            'name'    => 'Siti Rahayu',
            'email'   => 'petugas@demo.com',
            'password'=> Hash::make('password123'),
            'role'    => 'petugas',
            'no_telp' => '082345678901',
            'alamat'  => 'Jl. Pahlawan No. 5, Jakarta',
            'nip'     => 'NIP198001012010011001',
        ]);

        // ── Kepala Perpustakaan ───────────────────────
        User::create([\\
            'name'    => 'Dr. Ahmad Fauzi',
            'email'   => 'kepala@demo.com',
            'password'=> Hash::make('password123'),
            'role'    => 'kep_perpustakaan',
            'no_telp' => '083456789012',
            'alamat'  => 'Jl. Diponegoro No. 10, Jakarta',
            'nip'     => 'NIP197501012005011002',
        ]);
    }
}
