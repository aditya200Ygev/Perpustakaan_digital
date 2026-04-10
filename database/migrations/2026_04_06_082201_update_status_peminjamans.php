<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // ✅ TAMBAHKAN INI!

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // ✅ TAMBAHKAN 'pengembalian_diajukan' ke daftar ENUM
        DB::statement("
            ALTER TABLE peminjamans
            MODIFY status ENUM(
                'diajukan',
                'dipinjam',
                'dikembalikan',
                'pengembalian_diajukan',  -- ← STATUS BARU
                'denda',
                'selesai',
                'ditolak'
            ) DEFAULT 'diajukan'
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // ✅ KEMBALIKAN KE STATE SEMULA (tanpa 'pengembalian_diajukan')
        DB::statement("
            ALTER TABLE peminjamans
            MODIFY status ENUM(
                'diajukan',
                'dipinjam',
                'dikembalikan',
                'denda',
                'selesai',        -- ← 'selesai' HARUS ADA
                'ditolak'
            ) DEFAULT 'diajukan'
        ");
    }
};
