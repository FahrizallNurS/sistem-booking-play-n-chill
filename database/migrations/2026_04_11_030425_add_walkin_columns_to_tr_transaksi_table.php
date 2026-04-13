<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
        public function up(): void
    {
        Schema::table('tr_transaksi', function (Blueprint $table) {
            $table->string('walkin_name', 100)->nullable()->after('ms_id_pengguna');
            $table->string('walkin_phone', 20)->nullable()->after('walkin_name');
        });
    }

    public function down(): void
    {
        Schema::table('tr_transaksi', function (Blueprint $table) {
            $table->dropColumn(['walkin_name', 'walkin_phone']);
        });
    }
};
