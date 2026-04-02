<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peminjamans', function (Blueprint $table) {
            $table->id();

            $table->string('id_pinjam')->unique();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('buku_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->date('tgl_pinjam');
            $table->date('tgl_kembali');
            $table->integer('jumlah')->default(1); // ✅ PENTING
            $table->enum('status', ['dipinjam', 'dikembalikan'])
                  ->default('dipinjam');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjamans');
    }
};
