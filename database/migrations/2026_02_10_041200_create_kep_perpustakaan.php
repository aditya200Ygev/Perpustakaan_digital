<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kep_perpustakaan', function (Blueprint $table) {
            $table->integer('id_kep_perpustakaan')->autoIncrement();
            $table->string('nm_kep_perpustakaan');
            $table->string('nip');
            $table->string('email');
            $table->string('no_telp');
        });
    }

    public function down()
    {
        Schema::dropIfExists('kep_perpustakaan');
    }
};
