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
                'dosen_id' => 1,
                'mata_kuliah_id' => 1,
                'hari' => 'Senin',
                'jam_ke' => 1,
                'jam_mulai' => '08:00:00',
                'jam_selesai' => '10:00:00',
                'prodi' => 'RPL',
                'kelas' => '11 RPL 1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ruangan_id' => 1,
                'dosen_id' => 2,
                'mata_kuliah_id' => 2,
                'hari' => 'Senin',
                'jam_ke' => 2,
                'jam_mulai' => '13:00:00',
                'jam_selesai' => '15:00:00',
                'prodi' => 'RPL',
                'kelas' => '11 RPL 2',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ruangan_id' => 2,
                'dosen_id' => 3,
                'mata_kuliah_id' => 3,
                'hari' => 'Selasa',
                'jam_ke' => 1,
                'jam_mulai' => '08:00:00',
                'jam_selesai' => '10:00:00',
                'prodi' => 'TKJ',
                'kelas' => '11 TKJ 1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ruangan_id' => 3,
                'dosen_id' => 4,
                'mata_kuliah_id' => 4,
                'hari' => 'Rabu',
                'jam_ke' => 2,
                'jam_mulai' => '10:00:00',
                'jam_selesai' => '12:00:00',
                'prodi' => 'RPL',
                'kelas' => '10 RPL 1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}