<?php

namespace App\Exports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SiswaExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $kelas_id;

    /**
     * Buat constructor untuk menerima kelas_id
     */
    public function __construct($kelas_id = null)
    {
        $this->kelas_id = $kelas_id;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $query = Siswa::with('kelas')
                    ->where('status', 'aktif')
                    ->join('kelas', 'siswa.kelas_id', '=', 'kelas.id');

        /**
         * Tambahkan filter WHERE jika kelas_id diberikan
         */
        if ($this->kelas_id) {
            $query->where('siswa.kelas_id', $this->kelas_id);
        }

        // Lanjutkan sisa query
        return $query->orderBy('kelas.tingkat', 'asc')
                    ->orderBy('kelas.nama_kelas', 'asc')
                    ->orderBy('siswa.nama_lengkap', 'asc')
                    ->select('siswa.*')
                    ->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'NIS',
            'NISN',
            'Nama Lengkap',
            'Kelas',
            'Jenis Kelamin',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Alamat',
            'Nama Wali',
            'No. HP Wali',
            'Status',
        ];
    }

    /**
     * @var Siswa $siswa
     */
    public function map($siswa): array
    {
        return [
            $siswa->nis,
            $siswa->nisn,
            $siswa->nama_lengkap,
            $siswa->kelas->nama_kelas ?? 'N/A',
            $siswa->jenis_kelamin,
            $siswa->tempat_lahir,
            $siswa->tanggal_lahir ? $siswa->tanggal_lahir->format('Y-m-d') : 'N/A',
            $siswa->alamat,
            $siswa->nama_wali,
            $siswa->phone_wali,
            ucfirst($siswa->status),
        ];
    }
}
