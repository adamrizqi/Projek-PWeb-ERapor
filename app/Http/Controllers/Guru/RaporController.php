<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\MataPelajaran;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use ZipArchive;

class RaporController extends Controller
{
    protected $semester;
    protected $tahunAjaran;

    public function __construct()
    {
        $this->semester = 1;
        $this->tahunAjaran = '2024/2025';
    }
    /**
     * Display a listing of rapor
     */
    public function index()
    {
        $guru = Auth::user();
        $kelas = $guru->kelas;

        $siswa = Siswa::where('kelas_id', $kelas->id)
            ->where('status', 'aktif')
            ->orderBy('nama_lengkap')
            ->get();

        foreach ($siswa as $s) {
            $s->kelengkapan_rapor = $s->getPersentaseKelengkapanRapor($this->semester, $this->tahunAjaran);
            $s->rapor_lengkap = $s->isRaporLengkap($this->semester, $this->tahunAjaran);
        }

        return view('guru.rapor.index', compact('siswa', 'kelas'));
    }

    /**
     * Show rapor siswa
     */
    public function show(Siswa $siswa)
    {
        $this->validateKelasAccess($siswa);

        $data = $this->getRaporData($siswa);

        return view('guru.rapor.show', $data);
    }

    /**
     * Preview rapor PDF
     */
    public function preview(Siswa $siswa)
    {
        $this->validateKelasAccess($siswa);

        if (!$siswa->isRaporLengkap($this->semester, $this->tahunAjaran)) {
            return back()->with('warning', 'Data rapor belum lengkap.');
        }

        $data = $this->getRaporData($siswa);

        return view('guru.rapor.pdf', $data);
    }

    /**
     * Download rapor PDF
     */
    public function download(Siswa $siswa)
    {
        $this->validateKelasAccess($siswa);

        if (!$siswa->isRaporLengkap($this->semester, $this->tahunAjaran)) {
            return back()->with('error', 'Data rapor belum lengkap. Tidak dapat mengunduh.');
        }

        try {
            $data = $this->getRaporData($siswa);

            $pdf = PDF::loadView('guru.rapor.pdf', $data);

            $pdf->setPaper('A4', 'portrait');

            $pdf->setOption('margin-top', 10);
            $pdf->setOption('margin-right', 10);
            $pdf->setOption('margin-bottom', 10);
            $pdf->setOption('margin-left', 10);

            $filename = $this->generateFilename($siswa);

            return $pdf->download($filename);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal generate PDF: ' . $e->getMessage());
        }
    }

    /**
     * Download semua rapor kelas (ZIP)
     */
    public function downloadAll()
    {
        $guru = Auth::user();
        $kelas = $guru->kelas;

        try {
            $siswa = Siswa::where('kelas_id', $kelas->id)
                ->where('status', 'aktif')
                ->orderBy('nama_lengkap')
                ->get();

            $siswaLengkap = $siswa->filter(function ($s) {
                return $s->isRaporLengkap($this->semester, $this->tahunAjaran);
            });

            if ($siswaLengkap->isEmpty()) {
                return back()->with('error', 'Tidak ada rapor yang lengkap untuk diunduh.');
            }

            $tempDir = storage_path('app/temp/rapor_' . time());
            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0755, true);
            }

            foreach ($siswaLengkap as $s) {
                $data = $this->getRaporData($s);
                $pdf = PDF::loadView('guru.rapor.pdf', $data);
                $pdf->setPaper('A4', 'portrait');

                $filename = $this->generateFilename($s);
                $filepath = $tempDir . '/' . $filename;

                $pdf->save($filepath);
            }

            $zipFilename = 'Rapor_Kelas_' . str_replace('/', '-', $kelas->nama_kelas) .
                          '_Semester_' . $this->semester . '_' .
                          str_replace('/', '-', $this->tahunAjaran) . '.zip';
            $zipPath = storage_path('app/temp/' . $zipFilename);

            $zip = new ZipArchive;
            if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
                $files = glob($tempDir . '/*.pdf');
                foreach ($files as $file) {
                    $zip->addFile($file, basename($file));
                }
                $zip->close();
            }

            $this->deleteDirectory($tempDir);

            return response()->download($zipPath)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal generate ZIP: ' . $e->getMessage());
        }
    }

    /**
     * Validasi kelengkapan rapor
     */
    public function validasi()
    {
        $guru = Auth::user();
        $kelas = $guru->kelas;

        $siswa = Siswa::where('kelas_id', $kelas->id)
            ->where('status', 'aktif')
            ->orderBy('nama_lengkap')
            ->get();

        $mataPelajaran = MataPelajaran::where('tingkat', $kelas->tingkat)->get();

        $analisa = [];
        foreach ($siswa as $s) {
            $nilai = $s->getNilai($this->semester, $this->tahunAjaran);
            $kehadiran = $s->getKehadiran($this->semester, $this->tahunAjaran);
            $sikap = $s->getSikap($this->semester, $this->tahunAjaran);

            $nilaiLengkap = $nilai->count() == $mataPelajaran->count();
            $kehadiranLengkap = $kehadiran != null;
            $sikapLengkap = $sikap != null && $sikap->is_lengkap;

            $analisa[] = [
                'siswa' => $s,
                'nilai_lengkap' => $nilaiLengkap,
                'kehadiran_lengkap' => $kehadiranLengkap,
                'sikap_lengkap' => $sikapLengkap,
                'rapor_lengkap' => $nilaiLengkap && $kehadiranLengkap && $sikapLengkap,
                'persentase' => $s->getPersentaseKelengkapanRapor($this->semester, $this->tahunAjaran),
            ];
        }

        $statistik = [
            'total_siswa' => count($analisa),
            'rapor_lengkap' => collect($analisa)->where('rapor_lengkap', true)->count(),
            'rapor_belum_lengkap' => collect($analisa)->where('rapor_lengkap', false)->count(),
        ];

        return view('guru.rapor.validasi', compact('analisa', 'statistik', 'kelas'));
    }

    /**
     * Get data untuk rapor
     */
    private function getRaporData(Siswa $siswa)
    {
        $kelas = $siswa->kelas;

        $data['siswa'] = $siswa;
        $data['kelas'] = $kelas;
        $data['wali_kelas'] = $kelas->waliKelas;

        $data['semester'] = $this->semester;
        $data['tahun_ajaran'] = $this->tahunAjaran;

        $data['nilai'] = $siswa->getNilai($this->semester, $this->tahunAjaran);
        $data['rata_rata'] = $siswa->getRataRataNilai($this->semester, $this->tahunAjaran);
        $data['ranking'] = $siswa->getRankingKelas($this->semester, $this->tahunAjaran);

        $data['kehadiran'] = $siswa->getKehadiran($this->semester, $this->tahunAjaran);

        $data['sikap'] = $siswa->getSikap($this->semester, $this->tahunAjaran);

        $data['kepala_sekolah'] = [
            'nama' => 'Drs. H. Ahmad Fauzi, M.Pd',
            'nip' => '196501011986031001',
        ];

        $data['tanggal_cetak'] = now()->isoFormat('D MMMM Y');

        return $data;
    }

    /**
     * Generate filename untuk PDF
     */
    private function generateFilename(Siswa $siswa)
    {
        $nama = str_replace(' ', '_', $siswa->nama_lengkap);
        $kelas = str_replace('/', '-', $siswa->kelas->nama_kelas);
        $semester = $this->semester;
        $tahun = str_replace('/', '-', $this->tahunAjaran);

        return "Rapor_{$nama}_{$kelas}_Sem{$semester}_{$tahun}.pdf";
    }

    /**
     * Validate kelas access
     */
    private function validateKelasAccess(Siswa $siswa)
    {
        $guru = Auth::user();

        if ($siswa->kelas_id != $guru->kelas_id) {
            abort(403, 'Anda tidak memiliki akses ke siswa ini.');
        }
    }

    /**
     * Delete directory recursively
     */
    private function deleteDirectory($dir)
    {
        if (!file_exists($dir)) {
            return true;
        }

        if (!is_dir($dir)) {
            return unlink($dir);
        }

        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }

            if (!$this->deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }
        }

        return rmdir($dir);
    }
}
