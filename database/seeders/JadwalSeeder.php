<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JadwalSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('jadwal')->delete();

        $hariIndonesia = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
        ];
        $todayHari = $hariIndonesia[now()->format('l')] ?? 'Rabu';

        $schedules = [
            [
                'id' => 1,
                'ruangan_id' => 1,
                'hari' => 'Rabu',
                'tanggal' => '2025-04-23',
                'jam_mulai' => '10:00:00',
                'jam_selesai' => '11:40:00',
                'mata_kuliah' => 'Pemrograman Web',
                'dosen' => 'Dr. Budi Santoso',
                'prodi' => 'Teknik Informatika',
                'kelas' => 'TI-2A',
            ],
            [
                'id' => 2,
                'ruangan_id' => 2,
                'hari' => 'Rabu',
                'tanggal' => '2025-04-23',
                'jam_mulai' => '10:00:00',
                'jam_selesai' => '11:40:00',
                'mata_kuliah' => 'Basis Data',
                'dosen' => 'Siti Aminah, S.Kom',
                'prodi' => 'Sistem Informasi',
                'kelas' => 'SI-2B',
            ],
            [
                'id' => 3,
                'ruangan_id' => 3,
                'hari' => 'Rabu',
                'tanggal' => '2025-04-23',
                'jam_mulai' => '13:00:00',
                'jam_selesai' => '14:40:00',
                'mata_kuliah' => 'Jaringan Komputer',
                'dosen' => 'Andi Pratama, S.T',
                'prodi' => 'Teknik Komputer',
                'kelas' => 'TK-3A',
            ],
            [
                'id' => 4,
                'ruangan_id' => 4,
                'hari' => 'Rabu',
                'tanggal' => '2025-04-23',
                'jam_mulai' => '13:00:00',
                'jam_selesai' => '14:40:00',
                'mata_kuliah' => 'Matematika Diskrit',
                'dosen' => 'Rina Lestari, M.T',
                'prodi' => 'Teknik Informatika',
                'kelas' => 'TI-1B',
            ],
            [
                'id' => 5,
                'ruangan_id' => 5,
                'hari' => 'Rabu',
                'tanggal' => '2025-04-23',
                'jam_mulai' => '15:00:00',
                'jam_selesai' => '16:40:00',
                'mata_kuliah' => 'Sistem Operasi',
                'dosen' => 'Agus Wijaya, S.Kom',
                'prodi' => 'Teknik Informatika',
                'kelas' => 'TI-2B',
            ],
            // Additional schedules for current day so dashboard always has live data
            [
                'id' => 6,
                'ruangan_id' => 1,
                'hari' => $todayHari,
                'tanggal' => now()->format('Y-m-d'),
                'jam_mulai' => '07:00:00',
                'jam_selesai' => '23:59:00',
                'mata_kuliah' => 'Pemrograman Web (Live)',
                'dosen' => 'Dr. Budi Santoso',
                'prodi' => 'Teknik Informatika',
                'kelas' => 'TI-3A',
            ],
            [
                'id' => 7,
                'ruangan_id' => 2,
                'hari' => $todayHari,
                'tanggal' => now()->format('Y-m-d'),
                'jam_mulai' => '07:00:00',
                'jam_selesai' => '23:59:00',
                'mata_kuliah' => 'Basis Data Lanjut',
                'dosen' => 'Siti Aminah, S.Kom',
                'prodi' => 'Sistem Informasi',
                'kelas' => 'SI-4A',
            ],
            [
                'id' => 8,
                'ruangan_id' => 4,
                'hari' => $todayHari,
                'tanggal' => now()->format('Y-m-d'),
                'jam_mulai' => '07:00:00',
                'jam_selesai' => '23:59:00',
                'mata_kuliah' => 'Kecerdasan Buatan',
                'dosen' => 'Rina Lestari, M.T',
                'prodi' => 'Teknik Informatika',
                'kelas' => 'TI-4B',
            ],
        ];

        foreach ($schedules as $item) {
            DB::table('jadwal')->insert([
                'id' => $item['id'],
                'ruangan_id' => $item['ruangan_id'],
                'hari' => $item['hari'],
                'tanggal' => $item['tanggal'],
                'jam_mulai' => $item['jam_mulai'],
                'jam_selesai' => $item['jam_selesai'],
                'mata_kuliah' => $item['mata_kuliah'],
                'dosen' => $item['dosen'],
                'prodi' => $item['prodi'],
                'kelas' => $item['kelas'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}