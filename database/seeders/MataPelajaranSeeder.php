<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MataPelajaran;

class MataPelajaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mataPelajaran = [
            // Kelas 1-3 (Kelas Rendah)
            [
                'tingkat' => [1, 2, 3],
                'mapel' => [
                    ['nama' => 'Pendidikan Agama Islam dan Budi Pekerti', 'kode' => 'PAI', 'kkm' => 75],
                    ['nama' => 'Pendidikan Pancasila', 'kode' => 'PP', 'kkm' => 75],
                    ['nama' => 'Bahasa Indonesia', 'kode' => 'BIND', 'kkm' => 70],
                    ['nama' => 'Matematika', 'kode' => 'MTK', 'kkm' => 70],
                    ['nama' => 'Pendidikan Jasmani, Olahraga dan Kesehatan', 'kode' => 'PJOK', 'kkm' => 75],
                    ['nama' => 'Seni Musik dan Tari', 'kode' => 'SBD', 'kkm' => 75],
                ]
            ],
            // Kelas 4-6 (Kelas Tinggi)
            [
                'tingkat' => [4, 5, 6],
                'mapel' => [
                    ['nama' => 'Pendidikan Agama Islam dan Budi Pekerti', 'kode' => 'PAI', 'kkm' => 75],
                    ['nama' => 'Pendidikan Pancasila', 'kode' => 'PP', 'kkm' => 75],
                    ['nama' => 'Bahasa Indonesia', 'kode' => 'BIND', 'kkm' => 70],
                    ['nama' => 'Matematika', 'kode' => 'MTK', 'kkm' => 70],
                    ['nama' => 'Ilmu Pengetahuan Alam', 'kode' => 'IPA', 'kkm' => 70],
                    ['nama' => 'Ilmu Pengetahuan Sosial', 'kode' => 'IPS', 'kkm' => 70],
                    ['nama' => 'Bahasa Inggris', 'kode' => 'BING', 'kkm' => 70],
                    ['nama' => 'Pendidikan Jasmani, Olahraga dan Kesehatan', 'kode' => 'PJOK', 'kkm' => 75],
                    ['nama' => 'Seni Musik dan Tari', 'kode' => 'SBD', 'kkm' => 75],
                ]
            ]
        ];

        $totalMapel = 0;

        foreach ($mataPelajaran as $group) {
            foreach ($group['tingkat'] as $tingkat) {
                foreach ($group['mapel'] as $mapel) {
                    // Cek apakah sudah ada
                    $exists = MataPelajaran::where('kode_mapel', $mapel['kode'])
                        ->where('tingkat', $tingkat)
                        ->first();

                    if (!$exists) {
                        MataPelajaran::create([
                            'nama_mapel' => $mapel['nama'],
                            'kode_mapel' => $mapel['kode'] . '-' . $tingkat,
                            'tingkat' => $tingkat,
                            'kkm' => $mapel['kkm'],
                        ]);
                        $totalMapel++;
                    }
                }
            }
        }

        $this->command->info('✅ ' . $totalMapel . ' mata pelajaran berhasil dibuat!');
    }
}
