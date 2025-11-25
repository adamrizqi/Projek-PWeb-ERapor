<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('');
        $this->command->info('🚀 Memulai seeding database...');
        $this->command->info('=====================================');

        // Urutan seeding penting!
        $this->call([
            AdminSeeder::class,          // 1. Buat admin dulu
            KelasSeeder::class,          // 2. Buat kelas
            GuruSeeder::class,           // 3. Buat guru dan assign ke kelas
            MataPelajaranSeeder::class,  // 4. Buat mata pelajaran
        ]);

        $this->command->info('=====================================');
        $this->command->info('✅ Seeding selesai!');
        $this->command->info('');
        $this->command->info('📋 AKUN DEFAULT:');
        $this->command->info('');
        $this->command->info('👨‍💼 ADMIN:');
        $this->command->info('   Email: admin@sdnslumbung1.sch.id');
        $this->command->info('   Password: admin123');
        $this->command->info('');
        $this->command->info('👨‍🏫 GURU:');
        $this->command->info('   Email: [nama]@sdnslumbung1.sch.id');
        $this->command->info('   Password: guru123');
        $this->command->info('   Contoh: sitiaminah@sdnslumbung1.sch.id');
        $this->command->info('');
    }
}
