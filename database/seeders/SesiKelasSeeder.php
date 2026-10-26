<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SesiKelasSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('sesi_kelas')->delete();

        $today = now()->format('Y-m-d');

        $sessions = [
            [
                'id' => 1,
                'jadwal_id' => 1,
                'tanggal' => '2025-04-23',
                'status_verifikasi' => 'terverifikasi',
                'catatan' => 'Kelas sudah berjalan sesuai jadwal',
                'diverifikasi_oleh' => 1,
                'diverifikasi_pada' => '2025-04-23 10:15:00',
            ],
            [
                'id' => 2,
                'jadwal_id' => 2,
                'tanggal' => '2025-04-23',
                'status_verifikasi' => 'terverifikasi',
                'catatan' => 'Dosen hadir tepat waktu dan mahasiswa aktif',
                'diverifikasi_oleh' => 1,
                'diverifikasi_pada' => '2025-04-23 10:20:00',
            ],
            [
                'id' => 3,
                'jadwal_id' => 3,
                'tanggal' => '2025-04-23',
                'status_verifikasi' => 'terverifikasi',
                'catatan' => 'Praktikum jaringan sedang berlangsung',
                'diverifikasi_oleh' => 1,
                'diverifikasi_pada' => '2025-04-23 13:05:00',
            ],
            [
                'id' => 4,
                'jadwal_id' => 4,
                'tanggal' => '2025-04-23',
                'status_verifikasi' => 'belum',
                'catatan' => null,
                'diverifikasi_oleh' => null,
                'diverifikasi_pada' => null,
            ],
            [
                'id' => 5,
                'jadwal_id' => 5,
                'tanggal' => '2025-04-23',
                'status_verifikasi' => 'belum',
                'catatan' => null,
                'diverifikasi_oleh' => null,
                'diverifikasi_pada' => null,
            ],
            // Today's sessions
            [
                'id' => 6,
                'jadwal_id' => 6,
                'tanggal' => $today,
                'status_verifikasi' => 'terverifikasi',
                'catatan' => 'Kelas berjalan dengan lancar',
                'diverifikasi_oleh' => 1,
                'diverifikasi_pada' => now()->subMinutes(15),
            ],
            [
                'id' => 7,
                'jadwal_id' => 7,
                'tanggal' => $today,
                'status_verifikasi' => 'terverifikasi',
                'catatan' => 'Kelas sudah berjalan sesuai jadwal',
                'diverifikasi_oleh' => 1,
                'diverifikasi_pada' => now()->subMinutes(10),
            ],
            [
                'id' => 8,
                'jadwal_id' => 8,
                'tanggal' => $today,
                'status_verifikasi' => 'belum',
                'catatan' => null,
                'diverifikasi_oleh' => null,
                'diverifikasi_pada' => null,
            ],
        ];

        foreach ($sessions as $session) {
            DB::table('sesi_kelas')->insert([
                'id' => $session['id'],
                'jadwal_id' => $session['jadwal_id'],
                'tanggal' => $session['tanggal'],
                'status_verifikasi' => $session['status_verifikasi'],
                'catatan' => $session['catatan'],
                'diverifikasi_oleh' => $session['diverifikasi_oleh'],
                'diverifikasi_pada' => $session['diverifikasi_pada'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}