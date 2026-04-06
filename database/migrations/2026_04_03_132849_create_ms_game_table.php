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
        Schema::create('ms_game', function (Blueprint $table) {
        $table->id('id_game');
        $table->string('nama_game', 60);
        $table->string('gambar_game', 80)->nullable();
        $table->string('device_game', 20)->nullable();
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ms_game');
    }
};
