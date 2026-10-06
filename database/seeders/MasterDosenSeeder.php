<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterDosenSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('master_dosen')->insert([
            [
                'nip' => '198001012010011001',
                'nama' => 'Budi Santoso',
                'no_hp' => '081234567890',
                'status_aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nip' => '198502152012021002',
                'nama' => 'Andi Pratama',
                'no_hp' => '081234567891',
                'status_aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nip' => '197905102008031003',
                'nama' => 'Citra Lestari',
                'no_hp' => '081234567892',
                'status_aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nip' => '198308202011041004',
                'nama' => 'Dedi Kurniawan',
                'no_hp' => '081234567893',
                'status_aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}