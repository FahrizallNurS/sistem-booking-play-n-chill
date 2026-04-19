<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
         Schema::create('penetapan_harga', function (Blueprint $table) {
        $table->id('id_penetapan_harga');
        $table->foreignId('id_ruangan')->constrained('ms_ruangan', 'id_ruangan');
        $table->foreignId('id_paket')->constrained('ms_paket', 'id_paket');
        $table->integer('harga')->nullable();
        $table->integer('durasi_jam')->nullable();
        $table->enum('tipe_hari', ['harian', 'akhir_pekan', 'liburan'])->nullable();
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penetapan_harga');
    }
};
