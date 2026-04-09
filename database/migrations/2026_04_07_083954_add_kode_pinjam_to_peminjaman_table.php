<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('peminjamans', function (Blueprint $table) {
        // nullable agar data lama tidak error saat migrasi dijalankan
        $table->string('kode_pinjam')->nullable()->after('id')->unique();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            //
        });
    }
};
