<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldBeUnique; // Kita tambahkan ini

class CreateBackupJob implements ShouldQueue, ShouldBeUnique // Kita implementasi
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // Menampung data user yang request backup
    public User $user;

    /**
     * Berapa lama job ini boleh unik? (misal: 15 menit)
     * Ini mencegah admin menekan tombol backup berkali-kali.
     */
    public $uniqueFor = 900; // 900 detik = 15 menit

    /**
     * Buat instance job baru.
     * Kita butuh User agar tahu siapa yang me-request.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Ini adalah inti dari "pekerjaan"
     * Logika exec('mysqldump') kita pindahkan ke sini.
     */
    public function handle(): void
    {
        Log::info("Memulai proses backup database, di-request oleh: {$this->user->name}...");

        try {
            $backupPath = storage_path('app/backups');

            if (!file_exists($backupPath)) {
                mkdir($backupPath, 0755, true);
            }

            $filename = 'backup_' . date('Y-m-d_His') . '.sql';
            $filepath = $backupPath . '/' . $filename;

            $dbName = config('database.connections.mysql.database');
            $dbUser = config('database.connections.mysql.username');
            $dbPass = config('database.connections.mysql.password');
            $dbHost = config('database.connections.mysql.host');
            $dbPort = config('database.connections.mysql.port', '3306');

            // ===============================================
            // INI BAGIAN YANG DIPERBAIKI (REVISI)
            // ===============================================

            // 1. Mulai dengan perintah dasar
            $command = sprintf(
                'mysqldump --user=%s --host=%s --port=%s %s > %s',
                escapeshellarg($dbUser),
                escapeshellarg($dbHost),
                escapeshellarg($dbPort),
                escapeshellarg($dbName),
                escapeshellarg($filepath)
            );

            // 2. Tambahkan password HANYA JIKA ADA
            if (!empty($dbPass)) {
                // Jika ada password, sisipkan flag --password
                // Kita ganti -p menjadi --password= agar lebih aman dengan escapeshellarg
                $command = sprintf(
                    'mysqldump --user=%s --password=%s --host=%s --port=%s %s > %s',
                    escapeshellarg($dbUser),
                    escapeshellarg($dbPass), // Flag --password aman dengan escapeshellarg
                    escapeshellarg($dbHost),
                    escapeshellarg($dbPort),
                    escapeshellarg($dbName),
                    escapeshellarg($filepath)
                );
            }

            // Jalankan perintah
            exec($command, $output, $return);

            if ($return !== 0) {
                // Jika gagal, lempar error agar ditangkap method failed()
                throw new \Exception('mysqldump command failed. Return code: ' . $return);
            }

            // Jika berhasil
            Log::info("Backup database BERHASIL dibuat: {$filename}, di-request oleh: {$this->user->name}");

        } catch (\Exception $e) {
            // Tangkap error dan lempar lagi agar job-nya gagal
            Log::error('Proses backup GAGAL: ' . $e->getMessage());
            $this->fail($e);
        }
    }

    /**
     * Method ini akan dipanggil otomatis jika job GAGAL.
     */
    public function failed(Throwable $exception): void
    {
        // Kirim notifikasi ke admin, atau log error
        Log::error("JOB BACKUP GAGAL TOTAL: {$exception->getMessage()}");

        // Hapus file .sql yang mungkin korup (0 byte)
        // (Logika tambahan bisa ditaruh di sini)
    }
}
