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
        Schema::create('ms_ruangan_ms_permainan', function (Blueprint $table) {
        $table->foreignId('id_ruangan')->constrained('ms_ruangan', 'id_ruangan');
        $table->foreignId('id_permainan')->constrained('ms_permainan', 'id_permainan');
        $table->primary(['id_ruangan', 'id_permainan']);
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ms_ruangan_ms_permainan');
    }
};
