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
       Schema::create('ms_ruangan', function (Blueprint $table) {
        $table->id('id_ruangan');
        $table->string('nama_ruangan', 30);
        $table->text('description')->nullable();
        $table->tinyInteger('is_active')->default(1);
        $table->foreignId('ms_kategori_id_kategori')->constrained('ms_kategori', 'id_kategori');
        $table->timestamps();
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ms_ruangan');
    }
};
