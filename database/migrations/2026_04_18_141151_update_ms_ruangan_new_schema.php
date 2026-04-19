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
        Schema::table('ms_ruangan', function (Blueprint $table) {
        // Hapus kolom lama
        $table->dropForeign(['ms_kategori_id_kategori']);
        $table->dropColumn(['ms_kategori_id_kategori', 'description']);

        // Tambah kolom baru
        $table->enum('kategori', ['REGULAR', 'VIP', 'VVIP'])->nullable()->after('nama_ruangan');
        $table->string('deskripsi', 60)->nullable()->after('kategori');
        $table->string('perangkat', 10)->nullable()->after('deskripsi');
        $table->string('galeri', 225)->nullable()->after('is_active');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ms_ruangan', function (Blueprint $table) {
        $table->dropColumn(['kategori', 'deskripsi', 'perangkat', 'galeri']);
    });
    }
};
