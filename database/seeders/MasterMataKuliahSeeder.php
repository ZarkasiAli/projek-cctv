<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterMataKuliahSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('master_mata_kuliah')->insert([
            [
                'kode_mata_kuliah' => 'BD101',
                'nama_mata_kuliah' => 'Basis Data',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_mata_kuliah' => 'PW201',
                'nama_mata_kuliah' => 'Pemrograman Web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_mata_kuliah' => 'JK301',
                'nama_mata_kuliah' => 'Jaringan Komputer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_mata_kuliah' => 'PD101',
                'nama_mata_kuliah' => 'Pemrograman Dasar',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}