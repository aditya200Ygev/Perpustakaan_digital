<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('buku', function (Blueprint $table) {
            $table->integer('id_buku')->autoIncrement();
            $table->string('judul_buku');
            $table->string('penulis');
            $table->string('penerbit');
            $table->string('thn_terbit');
        });
    }

    public function down()
    {
        Schema::dropIfExists('buku');
    }
};
