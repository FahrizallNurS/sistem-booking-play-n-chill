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
        $table->string('kode_booking', 12);
        $table->date('tanggal_booking');
        $table->time('waktu_mulai');
        $table->time('durasi_sewa');
        $table->decimal('total_harga', 10, 2);
        $table->enum('opsi_pembayaran', ['full', 'dp']);
        $table->decimal('jumlah_dp', 10, 2)->nullable();
        $table->enum('status_booking', ['pending', 'confirmed', 'cancelled', 'completed']);
        $table->enum('status_pembayaran', ['unpaid', 'partial', 'paid']);
        $table->text('catatan_pembayaran')->nullable();
        $table->decimal('harga_saat_transaksi', 10, 2)->nullable();
        $table->foreignId('ms_id_pengguna')->constrained('users', 'id');
        $table->foreignId('ms_id_ruangan')->constrained('ms_ruangan', 'id_ruangan');
        $table->foreignId('ms_id_paket')->constrained('ms_paket', 'id_paket');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tr_transaksi');
    }
};
