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
        Schema::create('ms_ruangan_fasilitas', function (Blueprint $table) {
        $table->foreignId('ms_ruangan_id_ruangan')->constrained('ms_ruangan', 'id_ruangan');
        $table->foreignId('ms_fasilitas_id_fasilitas')->constrained('ms_fasilitas', 'id_fasilitas');
        $table->primary(['ms_ruangan_id_ruangan', 'ms_fasilitas_id_fasilitas']);
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ms_ruangan_fasilitas');
    }
};
