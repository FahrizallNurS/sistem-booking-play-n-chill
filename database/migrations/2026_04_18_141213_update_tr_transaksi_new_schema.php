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
        Schema::create('tr_transaksi', function (Blueprint $table) {
        $table->id('id_transaksi');
        $table->foreignId('id_penetapan_harga')->constrained('penetapan_harga', 'id_penetapan_harga');
        $table->foreignId('id_pengguna')->constrained('users', 'id');
        $table->string('kode_sewa', 15)->unique();
        $table->dateTime('waktu_mulai')->nullable();
        $table->dateTime('waktu_selesai')->nullable();
        $table->integer('total_harga')->nullable();
        $table->enum('opsi_pembayaran', ['full', 'dp'])->nullable();
        $table->integer('jumlah_dp')->nullable();
        $table->enum('status_sewa', ['ditahan', 'dikonfirmasi', 'dibatalkan', 'selesai'])->nullable();
        $table->enum('status_pembayaran', ['menunggu', 'dp', 'lunas'])->nullable();
        $table->string('catatan_pembayaran', 60)->nullable();
        $table->integer('sisa_bayar')->nullable();
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
