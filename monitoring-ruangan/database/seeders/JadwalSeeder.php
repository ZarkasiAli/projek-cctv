<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JadwalSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('jadwal')->insert([
            [
                'ruangan_id' => 1,
                'hari' => 'Senin',
                'jam_mulai' => '08:00:00',
                'jam_selesai' => '10:00:00',
                'mata_kuliah' => 'Basis Data',
                'dosen' => 'Budi Santoso',
                'prodi' => 'RPL',
                'kelas' => '11 RPL 1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ruangan_id' => 1,
                'hari' => 'Senin',
                'jam_mulai' => '13:00:00',
                'jam_selesai' => '15:00:00',
                'mata_kuliah' => 'Pemrograman Web',
                'dosen' => 'Andi Pratama',
                'prodi' => 'RPL',
                'kelas' => '11 RPL 2',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ruangan_id' => 2,
                'hari' => 'Selasa',
                'jam_mulai' => '08:00:00',
                'jam_selesai' => '10:00:00',
                'mata_kuliah' => 'Jaringan Komputer',
                'dosen' => 'Citra Lestari',
                'prodi' => 'TKJ',
                'kelas' => '11 TKJ 1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ruangan_id' => 3,
                'hari' => 'Rabu',
                'jam_mulai' => '10:00:00',
                'jam_selesai' => '12:00:00',
                'mata_kuliah' => 'Pemrograman Dasar',
                'dosen' => 'Dedi Kurniawan',
                'prodi' => 'RPL',
                'kelas' => '10 RPL 1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}