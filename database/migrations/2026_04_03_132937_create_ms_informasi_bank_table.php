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
      Schema::create('ms_informasi_bank', function (Blueprint $table) {
        $table->id('id_informasi_bank');
        $table->string('nama_bank', 25);
        $table->string('nomor_akun', 25);
        $table->string('nama_akun', 45);
        $table->string('logo_bank', 255)->nullable();
        $table->string('kode_qr', 255)->nullable();
        $table->text('catatan')->nullable();
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ms_informasi_bank');
    }
};
