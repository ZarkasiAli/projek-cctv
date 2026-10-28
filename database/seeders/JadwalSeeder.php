<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JadwalSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('jadwal')->delete();

                // `Ambil seluruh ID mata kuliah
        $mataKuliahIds = DB::table('master_mata_kuliah')
            ->pluck('id')
            ->toArray();

        // Ambil seluruh ID dosen
        $dosenIds = DB::table('master_dosen')
            ->pluck('id')
            ->toArray();

        // Pastikan data master tersedia
        if (empty($mataKuliahIds)) {
            throw new \RuntimeException('Data master mata kuliah masih kosong.');
        }

        if (empty($dosenIds)) {
            throw new \RuntimeException('Data master dosen masih kosong.');
        }

        $todayHari = $hariIndonesia[now()->format('l')] ?? 'Rabu';

        $schedules = [
            [
                'id' => 1,
                'ruangan_id' => 1,
                'mata_kuliah' => 'Pemrograman Web',
                'hari' => 'Rabu',
                'tanggal' => '2025-04-23',
                'jam_mulai' => '10:00:00',
                'jam_selesai' => '11:40:00',
                'dosen' => 'Dr. Budi Santoso',
                'prodi' => 'Teknik Informatika',
                'kelas' => 'TI-2A',
            ],
            [
                'id' => 2,
                'ruangan_id' => 2,
                'mata_kuliah' => 'Basis Data',
                'hari' => 'Rabu',
                'tanggal' => '2025-04-23',
                'jam_mulai' => '10:00:00',
                'jam_selesai' => '11:40:00',
                'dosen' => 'Siti Aminah, S.Kom',
                'prodi' => 'Sistem Informasi',
                'kelas' => 'SI-2B',
            ],
            [
                'id' => 3,
                'ruangan_id' => 3,
                'mata_kuliah' => 'Jaringan Komputer',
                'hari' => 'Rabu',
                'tanggal' => '2025-04-23',
                'jam_mulai' => '13:00:00',
                'jam_selesai' => '14:40:00',
                'dosen' => 'Andi Pratama, S.T',
                'prodi' => 'Teknik Komputer',
                'kelas' => 'TK-3A',
            ],
            [
                'id' => 4,
                'ruangan_id' => 4,
                'mata_kuliah' => 'Matematika Diskrit',
                'hari' => 'Rabu',
                'tanggal' => '2025-04-23',
                'jam_mulai' => '13:00:00',
                'jam_selesai' => '14:40:00',
                'dosen' => 'Rina Lestari, M.T',
                'prodi' => 'Teknik Informatika',
                'kelas' => 'TI-1B',
            ],
            [
                'id' => 5,
                'ruangan_id' => 5,
                'mata_kuliah' => 'Sistem Operasi',
                'hari' => 'Rabu',
                'tanggal' => '2025-04-23',
                'jam_mulai' => '15:00:00',
                'jam_selesai' => '16:40:00',
                'dosen' => 'Agus Wijaya, S.Kom',
                'prodi' => 'Teknik Informatika',
                'kelas' => 'TI-2B',
            ],
            [
                'id' => 6,
                'ruangan_id' => 1,
                'mata_kuliah' => 'Pemrograman Web',
                'hari' => $todayHari,
                'tanggal' => now()->toDateString(),
                'jam_mulai' => '07:00:00',
                'jam_selesai' => '23:59:00',
                'dosen' => 'Dr. Budi Santoso',
                'prodi' => 'Teknik Informatika',
                'kelas' => 'TI-3A',
            ],
            [
                'id' => 7,
                'ruangan_id' => 2,
                'mata_kuliah' => 'Basis Data Lanjut',
                'hari' => $todayHari,
                'tanggal' => now()->toDateString(),
                'jam_mulai' => '07:00:00',
                'jam_selesai' => '23:59:00',
                'dosen' => 'Siti Aminah, S.Kom',
                'prodi' => 'Sistem Informasi',
                'kelas' => 'SI-4A',
            ],
            [
                'id' => 8,
                'ruangan_id' => 4,
                'mata_kuliah' => 'Kecerdasan Buatan',
                'hari' => $todayHari,
                'tanggal' => now()->toDateString(),
                'jam_mulai' => '07:00:00',
                'jam_selesai' => '23:59:00',
                'dosen' => 'Rina Lestari, M.T',
                'prodi' => 'Teknik Informatika',
                'kelas' => 'TI-4B',
            ],
        ];

        foreach ($schedules as $item) {
            DB::table('jadwal')->insert([
                'id' => $item['id'],
                'ruangan_id' => $item['ruangan_id'],

                // Ambil ID secara random dari tabel master
                'mata_kuliah_id' => $mataKuliahIds[
                    array_rand($mataKuliahIds)
                ],

                'dosen_id' => $dosenIds[
                    array_rand($dosenIds)
                ],

                'hari' => $item['hari'],
                'jam_ke'=> 1,
                'tanggal' => $item['tanggal'],
                'jam_mulai' => $item['jam_mulai'],
                'jam_selesai' => $item['jam_selesai'],
                'prodi' => $item['prodi'],
                'kelas' => $item['kelas'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}