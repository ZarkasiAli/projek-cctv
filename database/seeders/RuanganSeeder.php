<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RuanganSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('ruangan')->insert([
            [
                'nama' => 'Lab RPL 1',
                'lokasi' => 'Gedung A Lantai 1',
                'link_stream_cctv' => 'http://localhost/cctv/lab-rpl-1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Lab RPL 2',
                'lokasi' => 'Gedung A Lantai 2',
                'link_stream_cctv' => 'http://localhost/cctv/lab-rpl-2',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Ruang 301',
                'lokasi' => 'Gedung B Lantai 3',
                'link_stream_cctv' => 'http://localhost/cctv/ruang-301',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}