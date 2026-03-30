<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create_function(['name' => 'Super Admin', 'email' => 'superadmin@gmail.com', 'role' => 'superadmin', 'password' => 'password']);
        User::create_function(['name' => 'Admin', 'email' => 'admin@gmail.com', 'role' => 'admin', 'password' => 'password']);
        User::create_function(['name' => 'Pelanggan', 'email' => 'pelanggan@gmail.com', 'role' => 'pelanggan', 'password' => 'password']);  
        User::create_function(['name' => 'ijolull', 'email' => 'ijolull@gmail.com', 'role' => 'pelanggan', 'password' => 'password']);  
    }
}
