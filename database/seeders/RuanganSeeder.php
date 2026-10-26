<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RuanganSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('ruangan')->delete();

        $rooms = [
            ['id' => 1, 'nama' => 'Ruang 101', 'kode_ruangan' => 'R101', 'lokasi' => 'Gedung A Lantai 1', 'link_stream_cctv' => 'https://cctv/101', 'status_cctv' => 'Online'],
            ['id' => 2, 'nama' => 'Ruang 102', 'kode_ruangan' => 'R102', 'lokasi' => 'Gedung A Lantai 1', 'link_stream_cctv' => 'https://cctv/102', 'status_cctv' => 'Online'],
            ['id' => 3, 'nama' => 'Ruang 103', 'kode_ruangan' => 'R103', 'lokasi' => 'Gedung A Lantai 1', 'link_stream_cctv' => 'https://cctv/103', 'status_cctv' => 'Offline'],
            ['id' => 4, 'nama' => 'Ruang 104', 'kode_ruangan' => 'R104', 'lokasi' => 'Gedung A Lantai 1', 'link_stream_cctv' => 'https://cctv/104', 'status_cctv' => 'Online'],
            ['id' => 5, 'nama' => 'Ruang 105', 'kode_ruangan' => 'R105', 'lokasi' => 'Gedung A Lantai 1', 'link_stream_cctv' => 'https://cctv/105', 'status_cctv' => 'Online'],
            ['id' => 6, 'nama' => 'Ruang 106', 'kode_ruangan' => 'R106', 'lokasi' => 'Gedung A Lantai 1', 'link_stream_cctv' => 'https://cctv/106', 'status_cctv' => 'Online'],
            ['id' => 7, 'nama' => 'Ruang 201', 'kode_ruangan' => 'R201', 'lokasi' => 'Gedung A Lantai 2', 'link_stream_cctv' => 'https://cctv/201', 'status_cctv' => 'Online'],
            ['id' => 8, 'nama' => 'Ruang 202', 'kode_ruangan' => 'R202', 'lokasi' => 'Gedung A Lantai 2', 'link_stream_cctv' => 'https://cctv/202', 'status_cctv' => 'Online'],
            ['id' => 9, 'nama' => 'Lab Komputer 1', 'kode_ruangan' => 'LK1', 'lokasi' => 'Gedung B Lantai 1', 'link_stream_cctv' => 'https://cctv/lk1', 'status_cctv' => 'Online'],
            ['id' => 10, 'nama' => 'Lab Jaringan', 'kode_ruangan' => 'LK2', 'lokasi' => 'Gedung B Lantai 2', 'link_stream_cctv' => 'https://cctv/lk2', 'status_cctv' => 'Online'],
        ];

        foreach ($rooms as $room) {
            DB::table('ruangan')->insert([
                'id' => $room['id'],
                'nama' => $room['nama'],
                'kode_ruangan' => $room['kode_ruangan'],
                'lokasi' => $room['lokasi'],
                'link_stream_cctv' => $room['link_stream_cctv'],
                'status_cctv' => $room['status_cctv'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}