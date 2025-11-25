<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;


class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminExists = User::where('email', 'admin@sdnslumbung1.sch.id')->first();

        if (!$adminExists) {
            User::create([
                'name' => 'Admin SDN Slumbung 1',
                'email' => 'admin@sdnslumbung1.sch.id',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'nip' => '196501011986031001',
                'phone' => '081234567890',
                'address' => 'Jl. Pendidikan No. 1, Slumbung, Gandusari, Blitar, Jawa Timur',
                'email_verified_at' => now(),
            ]);

            $this->command->info('✅ Admin berhasil dibuat!');
            $this->command->info('📧 Email: admin@sdnslumbung1.sch.id');
            $this->command->info('🔑 Password: admin123');
        } else {
            $this->command->warn('⚠️ Admin sudah ada!');
        }
    }
}
