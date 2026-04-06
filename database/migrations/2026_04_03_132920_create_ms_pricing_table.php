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
        Schema::create('ms_pricing', function (Blueprint $table) {
        $table->id('id_pricing');
        $table->enum('tipe_pricing', ['weekday', 'weekend', 'holiday']);
        $table->decimal('harga', 10, 2);
        $table->enum('hari_type', ['weekday', 'weekend', 'holiday']);
        $table->integer('durasi_menit')->nullable();
        $table->foreignId('ms_ruangan_id_ruangan')->constrained('ms_ruangan', 'id_ruangan');
        $table->foreignId('ms_paket_id_paket')->constrained('ms_paket', 'id_paket');
        $table->integer('durasi_rjsm')->nullable();
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ms_pricing');
    }
};
