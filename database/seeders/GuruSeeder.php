<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Kelas;

class GuruSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $guruList = [
            ['name' => 'Ibu Siti Aminah, S.Pd', 'nip' => '197001011995122001', 'phone' => '081234567801', 'kelas' => '1A'],
            ['name' => 'Ibu Nur Khasanah, S.Pd', 'nip' => '197505052000122001', 'phone' => '081234567802', 'kelas' => '1B'],
            ['name' => 'Bapak Ahmad Fauzi, S.Pd', 'nip' => '198003031999031002', 'phone' => '081234567803', 'kelas' => '2A'],
            ['name' => 'Ibu Dewi Lestari, S.Pd', 'nip' => '198208082005012003', 'phone' => '081234567804', 'kelas' => '2B'],
            ['name' => 'Ibu Endang Suryani, S.Pd', 'nip' => '197906062002122001', 'phone' => '081234567805', 'kelas' => '3A'],
            ['name' => 'Bapak Heru Prasetyo, S.Pd', 'nip' => '198510102008011003', 'phone' => '081234567806', 'kelas' => '3B'],
            ['name' => 'Ibu Fitri Handayani, S.Pd', 'nip' => '198112122006042002', 'phone' => '081234567807', 'kelas' => '4A'],
            ['name' => 'Bapak Gunawan Santoso, S.Pd', 'nip' => '197707072001121001', 'phone' => '081234567808', 'kelas' => '4B'],
            ['name' => 'Ibu Heni Purwanti, S.Pd', 'nip' => '198404042009012004', 'phone' => '081234567809', 'kelas' => '5A'],
            ['name' => 'Bapak Imam Maulana, S.Pd', 'nip' => '198009092005011002', 'phone' => '081234567810', 'kelas' => '5B'],
            ['name' => 'Ibu Julaiha, S.Pd', 'nip' => '197811112003122002', 'phone' => '081234567811', 'kelas' => '6A'],
            ['name' => 'Bapak Khoirul Anwar, S.Pd', 'nip' => '198202022007011004', 'phone' => '081234567812', 'kelas' => '6B'],
        ];

        foreach ($guruList as $guru) {
            // Cari kelas berdasarkan nama_kelas
            $kelas = Kelas::where('nama_kelas', $guru['kelas'])->first();

            if ($kelas) {
                // Buat email dari nama (lowercase, tanpa gelar, tanpa spasi)
                $emailName = strtolower(str_replace([' ', ',', '.', 'Ibu ', 'Bapak '], '', explode('S.Pd', $guru['name'])[0]));
                $emailName = preg_replace('/[^a-z]/', '', $emailName);
                $email = $emailName . '@sdnslumbung1.sch.id';

                // Cek apakah guru sudah ada
                $userExists = User::where('email', $email)->first();

                if (!$userExists) {
                    $user = User::create([
                        'name' => $guru['name'],
                        'email' => $email,
                        'password' => Hash::make('guru123'), // Password default
                        'role' => 'guru',
                        'nip' => $guru['nip'],
                        'phone' => $guru['phone'],
                        'kelas_id' => $kelas->id,
                        'address' => 'Jember, Jawa Timur',
                        'email_verified_at' => now(),
                    ]);

                    // Update kelas dengan wali_kelas_id
                    $kelas->update(['wali_kelas_id' => $user->id]);

                    $this->command->info("✅ Guru {$guru['name']} → Kelas {$guru['kelas']} berhasil dibuat!");
                    $this->command->info("   📧 Email: {$email}");
                    $this->command->info("   🔑 Password: guru123");
                }
            }
        }

        $this->command->info('');
        $this->command->info('🎉 Semua guru berhasil dibuat!');
    }
}
