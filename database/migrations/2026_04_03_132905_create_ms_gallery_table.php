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
        Schema::create('ms_gallery', function (Blueprint $table) {
        $table->id('id_gallery');
        $table->string('url_image', 255);
        $table->foreignId('ms_ruangan_id_ruangan')->constrained('ms_ruangan', 'id_ruangan');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ms_gallery');
    }
};
