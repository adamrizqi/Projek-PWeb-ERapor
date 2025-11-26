<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Kelas;


class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tahunAjaran = '2024/2025';
        $kelasList = [
            ['nama_kelas' => '1A', 'tingkat' => 1],
            ['nama_kelas' => '1B', 'tingkat' => 1],

            ['nama_kelas' => '2A', 'tingkat' => 2],
            ['nama_kelas' => '2B', 'tingkat' => 2],

            ['nama_kelas' => '3A', 'tingkat' => 3],
            ['nama_kelas' => '3B', 'tingkat' => 3],

            ['nama_kelas' => '4A', 'tingkat' => 4],
            ['nama_kelas' => '4B', 'tingkat' => 4],

            ['nama_kelas' => '5A', 'tingkat' => 5],
            ['nama_kelas' => '5B', 'tingkat' => 5],

            ['nama_kelas' => '6A', 'tingkat' => 6],
            ['nama_kelas' => '6B', 'tingkat' => 6],
        ];

        foreach ($kelasList as $kelas) {
            Kelas::create([
                'nama_kelas' => $kelas['nama_kelas'],
                'tingkat' => $kelas['tingkat'],
                'tahun_ajaran' => $tahunAjaran,
                'wali_kelas_id' => null,
            ]);
        }

        $this->command->info('✅ ' . count($kelasList) . ' kelas berhasil dibuat!');
    }
}
