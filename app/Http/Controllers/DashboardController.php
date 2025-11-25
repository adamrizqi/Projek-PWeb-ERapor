<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\MataPelajaran;
use App\Models\Nilai;
use App\Models\Kehadiran;
use App\Models\Sikap;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Jobs\CreateBackupJob;

class DashboardController extends Controller
{
    /**
     * Redirect ke dashboard sesuai role
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'guru') {
            return redirect()->route('guru.dashboard');
        }

        // Fallback jika role tidak dikenali
        Auth::logout();
        return redirect()->route('login')->with('error', 'Role tidak valid.');
    }

    /**
     * Dashboard Admin
     */
    public function adminDashboard()
    {
        $data = [
            'total_kelas' => Kelas::count(),
            'total_siswa' => Siswa::where('status', 'aktif')->count(),
            'total_guru' => User::where('role', 'guru')->count(),
            'total_mapel' => MataPelajaran::count(),

            // Statistik per tingkat
            'siswa_per_tingkat' => Siswa::selectRaw('kelas.tingkat, COUNT(*) as total')
                ->join('kelas', 'siswa.kelas_id', '=', 'kelas.id')
                ->where('siswa.status', 'aktif')
                ->groupBy('kelas.tingkat')
                ->orderBy('kelas.tingkat')
                ->get(),

            // Kelas tanpa wali
            'kelas_tanpa_wali' => Kelas::whereNull('wali_kelas_id')->count(),

            // Guru tanpa kelas
            'guru_tanpa_kelas' => User::where('role', 'guru')
                ->whereNull('kelas_id')
                ->count(),

            // Recent activities (contoh)
            'recent_classes' => Kelas::with('waliKelas')
                ->latest()
                ->take(5)
                ->get(),
        ];

        return view('admin.dashboard', $data);
    }

    /**
     * Dashboard Guru
     */
    public function guruDashboard()
    {
        $user = Auth::user();
        $kelas = $user->kelas;

        if (!$kelas) {
            return view('guru.dashboard-no-class');
        }

        $data = [
            'kelas' => $kelas,
            'total_siswa' => $kelas->siswaAktif()->count(),
            'siswa_list' => $kelas->siswaAktif()->get(),

            // Statistik nilai
            'rata_rata_kelas' => $kelas->getRataRataNilaiKelas(),

            // Mata pelajaran
            'mata_pelajaran' => MataPelajaran::where('tingkat', $kelas->tingkat)->get(),

            // Progress kelengkapan data
            'progress_nilai' => $this->hitungProgressNilai($kelas->id),
            'progress_kehadiran' => $this->hitungProgressKehadiran($kelas->id),
            'progress_sikap' => $this->hitungProgressSikap($kelas->id),
        ];

        return view('guru.dashboard', $data);
    }

    /**
     * Laporan Nilai
     */
    public function laporanNilai(Request $request)
    {
        $kelasId = $request->input('kelas_id');
        $semester = $request->input('semester', 1);
        $tahunAjaran = $request->input('tahun_ajaran', '2024/2025');

        $query = Nilai::with(['siswa.kelas', 'mataPelajaran'])
            ->where('semester', $semester)
            ->where('tahun_ajaran', $tahunAjaran);

        if ($kelasId) {
            $query->whereHas('siswa', function ($q) use ($kelasId) {
                $q->where('kelas_id', $kelasId);
            });
        }

        $nilai = $query->get();

        // Statistik
        $statistik = [
            'total_nilai' => $nilai->count(),
            'rata_rata_keseluruhan' => round($nilai->avg('nilai_akhir'), 2),
            'tuntas' => $nilai->where('is_tuntas', true)->count(),
            'belum_tuntas' => $nilai->where('is_tuntas', false)->count(),
        ];

        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();

        return view('admin.laporan.nilai', compact('nilai', 'statistik', 'kelasList'));
    }

    /**
     * Laporan Kehadiran
     */
    public function laporanKehadiran(Request $request)
    {
        $kelasId = $request->input('kelas_id');
        $semester = $request->input('semester', 1);
        $tahunAjaran = $request->input('tahun_ajaran', '2024/2025');

        $query = Kehadiran::with('siswa.kelas')
            ->where('semester', $semester)
            ->where('tahun_ajaran', $tahunAjaran);

        if ($kelasId) {
            $query->whereHas('siswa', function ($q) use ($kelasId) {
                $q->where('kelas_id', $kelasId);
            });
        }

        $kehadiran = $query->get();

        // Statistik
        $statistik = [
            'total_hadir' => $kehadiran->sum('hadir'),
            'total_sakit' => $kehadiran->sum('sakit'),
            'total_izin' => $kehadiran->sum('izin'),
            'total_alpa' => $kehadiran->sum('alpa'),
            'rata_persentase' => round($kehadiran->avg('persentase_kehadiran'), 2),
        ];

        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();

        return view('admin.laporan.kehadiran', compact('kehadiran', 'statistik', 'kelasList'));
    }

    /**
     * Rekap Kelas
     */
    public function rekapKelas(Request $request)
    {
        $semester = $request->input('semester', 1);
        $tahunAjaran = $request->input('tahun_ajaran', '2024/2025');

        $kelas = Kelas::with(['waliKelas', 'siswaAktif'])->get();

        $rekap = [];
        foreach ($kelas as $k) {
            $siswa = $k->siswaAktif;

            $nilaiLengkap = 0;
            $kehadiranLengkap = 0;
            $sikapLengkap = 0;

            foreach ($siswa as $s) {
                if ($s->isRaporLengkap($semester, $tahunAjaran)) {
                    $nilaiLengkap++;
                    $kehadiranLengkap++;
                    $sikapLengkap++;
                }
            }

            $rekap[] = [
                'kelas' => $k,
                'total_siswa' => $siswa->count(),
                'nilai_lengkap' => $nilaiLengkap,
                'kehadiran_lengkap' => $kehadiranLengkap,
                'sikap_lengkap' => $sikapLengkap,
                'persentase_lengkap' => $siswa->count() > 0
                    ? round(($nilaiLengkap / $siswa->count()) * 100)
                    : 0,
            ];
        }

        return view('admin.laporan.rekap-kelas', compact('rekap'));
    }

    /**
     * Backup Database
     */
    public function backup()
    {
        $backupFiles = [];
        $backupPath = storage_path('app/backups');

        if (file_exists($backupPath)) {
            $files = glob($backupPath . '/*.sql');
            foreach ($files as $file) {
                $backupFiles[] = [
                    'name' => basename($file),
                    'size' => filesize($file),
                    'date' => date('Y-m-d H:i:s', filemtime($file)),
                    'path' => $file,
                ];
            }

            // Sort by date descending
            usort($backupFiles, function ($a, $b) {
                return $b['date'] <=> $a['date'];
            });
        }

        return view('admin.backup.index', compact('backupFiles'));
    }

    /**
     * Create Backup (Versi Asinkron/Queue)
     */
    public function createBackup()
    {
        try {
            // 1. Ambil user yang sedang login
            $user = Auth::user();

            // 2. KIRIM TUGAS ke Antrian
            CreateBackupJob::dispatch($user);

            // 3. Langsung kembalikan respons ke admin (tanpa menunggu)
            return back()->with('success',
                'Proses backup telah dimulai di latar belakang. File akan muncul setelah selesai.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Download Backup
     */
    public function downloadBackup($file)
    {
        $filepath = storage_path('app/backups/' . $file);

        if (!file_exists($filepath)) {
            return back()->with('error', 'File backup tidak ditemukan.');
        }

        return response()->download($filepath);
    }

    /**
     * Hitung progress input nilai
     */
    private function hitungProgressNilai($kelasId)
    {
        $siswa = Siswa::where('kelas_id', $kelasId)->where('status', 'aktif')->get();
        if ($siswa->isEmpty()) return 0;

        $totalSiswa = $siswa->count();
        $siswaLengkap = 0;

        foreach ($siswa as $s) {
            if ($s->isRaporLengkap(1, '2024/2025')) {
                $siswaLengkap++;
            }
        }

        return round(($siswaLengkap / $totalSiswa) * 100);
    }

    /**
     * Hitung progress input kehadiran
     */
    private function hitungProgressKehadiran($kelasId)
    {
        $siswa = Siswa::where('kelas_id', $kelasId)->where('status', 'aktif')->count();
        $kehadiran = \App\Models\Kehadiran::whereHas('siswa', function ($q) use ($kelasId) {
            $q->where('kelas_id', $kelasId);
        })->where('semester', 1)->where('tahun_ajaran', '2024/2025')->count();

        return $siswa > 0 ? round(($kehadiran / $siswa) * 100) : 0;
    }

    /**
     * Hitung progress input sikap
     */
    private function hitungProgressSikap($kelasId)
    {
        $siswa = Siswa::where('kelas_id', $kelasId)->where('status', 'aktif')->count();
        $sikap = \App\Models\Sikap::whereHas('siswa', function ($q) use ($kelasId) {
            $q->where('kelas_id', $kelasId);
        })->where('semester', 1)->where('tahun_ajaran', '2024/2025')->lengkap()->count();

        return $siswa > 0 ? round(($sikap / $siswa) * 100) : 0;
    }
}

