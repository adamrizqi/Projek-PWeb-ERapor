<?php

namespace App\Imports;

use App\Models\Siswa;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class SiswaImport implements
    ToCollection,
    WithHeadingRow,
    WithValidation,
    WithChunkReading,
    WithBatchInserts
{
    private $kelas_id;

    public function __construct(int $kelas_id)
    {
        $this->kelas_id = $kelas_id;
    }

    /**
    * @param Collection $rows
    */
    public function collection(Collection $rows)
    {
        // dd($rows); // <--- SUDAH DIHAPUS AGAR IMPORT BERJALAN

        foreach ($rows as $row)
        {
            // 1. Cek Duplikasi NIS
            $nis = (string) $row['nis'];
            $nisExists = Siswa::where('nis', $nis)->exists();

            if ($nisExists) {
                // Lewati data ini jika NIS sudah ada
                continue;
            }

            // 2. Logika Jenis Kelamin
            // Cek berbagai kemungkinan nama header
            $jk_raw = $row['jenis_kelamin_lp'] ?? $row['jenis_kelamin'] ?? 'L';
            if(empty($jk_raw)) $jk_raw = 'L';

            $jenis_kelamin = strtoupper(substr(trim($jk_raw), 0, 1));
            if (!in_array($jenis_kelamin, ['L', 'P'])) {
                $jenis_kelamin = 'L';
            }

            // 3. Logika Tanggal Lahir [BAGIAN INI DIPERBAIKI]
            // Mencari kunci yang sesuai dengan hasil debug Anda
            $tgl_raw = $row['tanggal_lahir_yyyy_mm_dd'] // <-- INI KUNCI UTAMANYA
                    ?? $row['tanggal_lahir_yyyymmdd']   // Cadangan
                    ?? $row['tanggal_lahir']            // Cadangan (file export)
                    ?? null;

            $tanggal_lahir = null;

            // Jika tanggal kosong, kita isi default hari ini atau null (agar tidak error)
            if (!empty($tgl_raw)) {
                try {
                    if (is_numeric($tgl_raw)) {
                        $tanggal_lahir = Date::excelToDateTimeObject($tgl_raw)->format('Y-m-d');
                    } else {
                        $tanggal_lahir = date('Y-m-d', strtotime($tgl_raw));
                    }
                } catch (\Exception $e) {
                    Log::warning('Gagal parse tanggal lahir NIS: ' . $nis);
                    $tanggal_lahir = now()->format('Y-m-d'); // Fallback aman
                }
            } else {
                 // Jika kosong, lewati baris ini (opsional) atau beri default
                 // continue;
                 $tanggal_lahir = now()->format('Y-m-d'); // Fallback aman
            }

            // 4. Simpan ke Database
            try {
                Siswa::create([
                    'nis' => $nis,
                    'nisn' => $row['nisn'] ?? null,
                    'nama_lengkap' => $row['nama_lengkap'],
                    'jenis_kelamin' => $jenis_kelamin,
                    'tempat_lahir' => $row['tempat_lahir'] ?? 'Indonesia',
                    'tanggal_lahir' => $tanggal_lahir,
                    'alamat' => $row['alamat'] ?? '-',
                    'nama_wali' => $row['nama_wali'] ?? '-',
                    'phone_wali' => $row['no_hp_wali'] ?? null,
                    'kelas_id' => $this->kelas_id,
                    'status' => 'aktif',
                ]);
            } catch (\Exception $e) {
                Log::error('Gagal insert siswa NIS ' . $nis . ': ' . $e->getMessage());
            }
        }
    }

    // Validasi kita longgarkan agar fleksibel
    public function rules(): array
    {
        return [
            'nis' => 'required',
            'nama_lengkap' => 'required',
        ];
    }

    // Pesan error
    public function customValidationMessages()
    {
        return [
            'nis.required' => 'Kolom NIS wajib ada.',
            'nama_lengkap.required' => 'Kolom Nama Lengkap wajib ada.',
        ];
    }

    public function chunkSize(): int
    {
        return 100;
    }

    public function batchSize(): int
    {
        return 100;
    }
}
