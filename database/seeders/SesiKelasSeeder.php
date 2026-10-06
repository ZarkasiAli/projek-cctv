<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SesiKelasSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('sesi_kelas')->insert([
            [
                'jadwal_id' => 1,
                'tanggal' => '2026-09-21',
                'status_verifikasi' => 'terverifikasi',
                'diverifikasi_oleh' => 1,
                'diverifikasi_pada' => '2026-09-21 10:05:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'jadwal_id' => 2,
                'tanggal' => '2026-09-21',
                'status_verifikasi' => 'belum',
                'diverifikasi_oleh' => null,
                'diverifikasi_pada' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'jadwal_id' => 3,
                'tanggal' => '2026-09-22',
                'status_verifikasi' => 'terverifikasi',
                'diverifikasi_oleh' => 1,
                'diverifikasi_pada' => '2026-09-22 10:10:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'jadwal_id' => 4,
                'tanggal' => '2026-09-23',
                'status_verifikasi' => 'belum',
                'diverifikasi_oleh' => null,
                'diverifikasi_pada' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}