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

        foreach ($rows as $row)
        {
            $nis = (string) $row['nis'];
            $nisExists = Siswa::where('nis', $nis)->exists();

            if ($nisExists) {
                continue;
            }

            $jk_raw = $row['jenis_kelamin_lp'] ?? $row['jenis_kelamin'] ?? 'L';
            if(empty($jk_raw)) $jk_raw = 'L';

            $jenis_kelamin = strtoupper(substr(trim($jk_raw), 0, 1));
            if (!in_array($jenis_kelamin, ['L', 'P'])) {
                $jenis_kelamin = 'L';
            }


            $tgl_raw = $row['tanggal_lahir_yyyy_mm_dd']
                    ?? $row['tanggal_lahir_yyyymmdd']
                    ?? $row['tanggal_lahir']
                    ?? null;

            $tanggal_lahir = null;

            if (!empty($tgl_raw)) {
                try {
                    if (is_numeric($tgl_raw)) {
                        $tanggal_lahir = Date::excelToDateTimeObject($tgl_raw)->format('Y-m-d');
                    } else {
                        $tanggal_lahir = date('Y-m-d', strtotime($tgl_raw));
                    }
                } catch (\Exception $e) {
                    Log::warning('Gagal parse tanggal lahir NIS: ' . $nis);
                    $tanggal_lahir = now()->format('Y-m-d');
                }
            } else {
                 $tanggal_lahir = now()->format('Y-m-d');
            }

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

    public function rules(): array
    {
        return [
            'nis' => 'required',
            'nama_lengkap' => 'required',
        ];
    }

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
