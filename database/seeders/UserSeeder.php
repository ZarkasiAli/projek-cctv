<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('users')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('users')->insert([
            [
                'id' => 1,
                'nama' => 'Admin',
                'email' => 'admin@monitoring.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'nama' => 'Manajemen Jurusan',
                'email' => 'manajemen@monitoring.com',
                'password' => Hash::make('manajemen123'),
                'role' => 'manajemen',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}