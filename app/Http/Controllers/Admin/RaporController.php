<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class RaporController extends Controller
{
    protected $semester;
    protected $tahunAjaran;

    public function __construct()
    {
        // Sesuaikan dengan settingan aktif
        $this->semester = 1;
        $this->tahunAjaran = '2024/2025';
    }

    /**
     * Preview Rapor (HTML)
     */
    public function preview(Siswa $siswa)
    {
        $data = $this->getRaporData($siswa);

        // Kita bisa menggunakan view yang sama dengan guru untuk konsistensi
        return view('guru.rapor.show', $data);
    }

    /**
     * Download PDF
     */
    public function download(Siswa $siswa)
    {
        try {
            $data = $this->getRaporData($siswa);

            // Gunakan view PDF yang sama dengan guru
            $pdf = Pdf::loadView('guru.rapor.pdf', $data);

            $pdf->setPaper('A4', 'portrait');
            $pdf->setOption('margin-top', 10);
            $pdf->setOption('margin-right', 10);
            $pdf->setOption('margin-bottom', 10);
            $pdf->setOption('margin-left', 10);

            $filename = "Rapor_{$siswa->nis}_{$siswa->nama_lengkap}.pdf";

            return $pdf->download($filename);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal generate PDF: ' . $e->getMessage());
        }
    }

    /**
     * Helper: Ambil Data Rapor
     */
    private function getRaporData(Siswa $siswa)
    {
        $kelas = $siswa->kelas;

        return [
            'siswa' => $siswa,
            'kelas' => $kelas,
            'wali_kelas' => $kelas->waliKelas,
            'semester' => $this->semester,
            'tahun_ajaran' => $this->tahunAjaran,
            'nilai' => $siswa->getNilai($this->semester, $this->tahunAjaran),
            'rata_rata' => $siswa->getRataRataNilai($this->semester, $this->tahunAjaran),
            'ranking' => $siswa->getRankingKelas($this->semester, $this->tahunAjaran),
            'kehadiran' => $siswa->getKehadiran($this->semester, $this->tahunAjaran),
            'sikap' => $siswa->getSikap($this->semester, $this->tahunAjaran),
            'kepala_sekolah' => [
                'nama' => 'Drs. H. Ahmad Fauzi, M.Pd',
                'nip' => '196501011986031001',
            ],
            'tanggal_cetak' => now()->isoFormat('D MMMM Y'),
        ];
    }
}
