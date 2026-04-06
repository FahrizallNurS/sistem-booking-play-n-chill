<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void

    {
    DB::table('ms_kategori')->insert([
        ['nama_kategori' => 'Reguler', 'deskripsi' => 'Ruangan PS biasa', 'created_at' => now(), 'updated_at' => now()],
        ['nama_kategori' => 'VIP', 'deskripsi' => 'Ruangan VIP', 'created_at' => now(), 'updated_at' => now()],
        ['nama_kategori' => 'VVIP', 'deskripsi' => 'Ruangan VVIP', 'created_at' => now(), 'updated_at' => now()],
    ]);
    }
}
