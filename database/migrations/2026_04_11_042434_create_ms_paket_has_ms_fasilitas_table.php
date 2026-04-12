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
    Schema::create('ms_paket_has_ms_fasilitas', function (Blueprint $table) {
        $table->foreignId('ms_paket_id_paket')
            ->constrained('ms_paket', 'id_paket')
            ->onDelete('cascade');

        $table->foreignId('ms_fasilitas_id_fasilitas')
            ->constrained('ms_fasilitas', 'id_fasilitas')
            ->onDelete('cascade');

        $table->primary(['ms_paket_id_paket', 'ms_fasilitas_id_fasilitas']);
    });
}

        public function down(): void
        {
            Schema::dropIfExists('ms_paket_has_ms_fasilitas');
        }
};
