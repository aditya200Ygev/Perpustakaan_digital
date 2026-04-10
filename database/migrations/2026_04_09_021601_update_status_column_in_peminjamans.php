<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ✅ METODE 1: Pakai raw SQL (tanpa perlu doctrine/dbal)
        DB::statement("ALTER TABLE peminjamans MODIFY COLUMN status VARCHAR(50) NOT NULL DEFAULT 'diajukan'");

        // ❌ METODE 2: Pakai Schema Builder (butuh package doctrine/dbal)
        // Schema::table('peminjamans', function (Blueprint $table) {
        //     $table->string('status', 50)->change();
        // });
    }

    public function down(): void
    {
        // Rollback: kembalikan ke VARCHAR(20) atau ENUM lama
        DB::statement("ALTER TABLE peminjamans MODIFY COLUMN status VARCHAR(20) NOT NULL DEFAULT 'diajukan'");
    }
};
