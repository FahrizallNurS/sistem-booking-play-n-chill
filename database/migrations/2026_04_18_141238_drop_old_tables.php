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
        Schema::dropIfExists('ms_paket_has_ms_fasilitas');
        Schema::dropIfExists('ms_fasilitas');
        Schema::dropIfExists('ms_ruangan_has_ms_game');
        Schema::dropIfExists('ms_game');
        Schema::dropIfExists('ms_gallery');
        Schema::dropIfExists('ms_informasi_bank');
        Schema::dropIfExists('ms_pricing');
        Schema::dropIfExists('ms_kategori');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
