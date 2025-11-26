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
use Illuminate\Contracts\Queue\ShouldBeUnique;

class CreateBackupJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public User $user;

    /**
     *
     */
    public $uniqueFor = 900;

    /**
     * Buat instance job baru.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Jalankan job.
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

            $command = sprintf(
                'mysqldump --user=%s --host=%s --port=%s %s > %s',
                escapeshellarg($dbUser),
                escapeshellarg($dbHost),
                escapeshellarg($dbPort),
                escapeshellarg($dbName),
                escapeshellarg($filepath)
            );

            if (!empty($dbPass)) {
                $command = sprintf(
                    'mysqldump --user=%s --password=%s --host=%s --port=%s %s > %s',
                    escapeshellarg($dbUser),
                    escapeshellarg($dbPass),
                    escapeshellarg($dbHost),
                    escapeshellarg($dbPort),
                    escapeshellarg($dbName),
                    escapeshellarg($filepath)
                );
            }

            exec($command, $output, $return);

            if ($return !== 0) {
                throw new \Exception('mysqldump command failed. Return code: ' . $return);
            }

            Log::info("Backup database BERHASIL dibuat: {$filename}, di-request oleh: {$this->user->name}");

        } catch (\Exception $e) {
            Log::error('Proses backup GAGAL: ' . $e->getMessage());
            $this->fail($e);
        }
    }

    /**
     * Method ini akan dipanggil otomatis jika job GAGAL.
     */
    public function failed(Throwable $exception): void
    {
        Log::error("JOB BACKUP GAGAL TOTAL: {$exception->getMessage()}");
    }
}
