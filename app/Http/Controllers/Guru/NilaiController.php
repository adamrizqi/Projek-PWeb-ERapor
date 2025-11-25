<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Nilai;
use App\Models\MataPelajaran;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class NilaiController extends Controller
{
    protected $semester;
    protected $tahunAjaran;

    public function __construct()
    {
        $this->semester = 1;
        $this->tahunAjaran = '2024/2025';
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $guru = Auth::user();
        $kelas = $guru->kelas;

        if (!$kelas) {
            return redirect()->route('guru.dashboard')
                ->with('error', 'Anda belum ditugaskan ke kelas manapun.');
        }

        $query = Siswa::where('kelas_id', $kelas->id)
            ->where('status', 'aktif');

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        $siswa = $query->orderBy('nama_lengkap')->get();

        foreach ($siswa as $s) {
            $s->progress_nilai = $this->hitungProgressNilai($s);
            $s->rata_rata = $s->getRataRataNilai($this->semester, $this->tahunAjaran);
        }

        $mataPelajaran = MataPelajaran::where('tingkat', $kelas->tingkat)->get();

        return view('guru.nilai.index', compact('siswa', 'kelas', 'mataPelajaran'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function input(Siswa $siswa)
    {
        $this->validateKelasAccess($siswa);

        $mataPelajaran = MataPelajaran::where('tingkat', $siswa->kelas->tingkat)->get();

        $nilaiTersimpan = Nilai::where('siswa_id', $siswa->id)
            ->where('semester', $this->semester)
            ->where('tahun_ajaran', $this->tahunAjaran)
            ->pluck('mata_pelajaran_id')
            ->toArray();

        return view('guru.nilai.input', compact('siswa', 'mataPelajaran', 'nilaiTersimpan'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Siswa $siswa)
    {
        $this->validateKelasAccess($siswa);

        $validated = $request->validate([
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'nilai_pengetahuan' => 'required|integer|min:0|max:100',
            'nilai_keterampilan' => 'required|integer|min:0|max:100',
            'deskripsi' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $exists = Nilai::where('siswa_id', $siswa->id)
                ->where('mata_pelajaran_id', $validated['mata_pelajaran_id'])
                ->where('semester', $this->semester)
                ->where('tahun_ajaran', $this->tahunAjaran)
                ->exists();

            if ($exists) {
                return back()->withInput()
                    ->with('error', 'Nilai untuk mata pelajaran ini sudah ada. Gunakan fitur edit.');
            }

            $validated['siswa_id'] = $siswa->id;
            $validated['semester'] = $this->semester;
            $validated['tahun_ajaran'] = $this->tahunAjaran;

            $nilai = Nilai::create($validated);

            if (empty($validated['deskripsi'])) {
                $nilai->updateDeskripsi();
            }

            DB::commit();

            return redirect()->route('guru.nilai.show', $siswa)
                ->with('success', 'Nilai berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Gagal menyimpan nilai: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Siswa $siswa)
    {
        $this->validateKelasAccess($siswa);

        $nilai = $siswa->getNilai($this->semester, $this->tahunAjaran);
        $mataPelajaran = MataPelajaran::where('tingkat', $siswa->kelas->tingkat)->get();

        $statistik = [
            'total_mapel' => $mataPelajaran->count(),
            'sudah_dinilai' => $nilai->count(),
            'rata_rata' => $siswa->getRataRataNilai($this->semester, $this->tahunAjaran),
            'ranking' => $siswa->getRankingKelas($this->semester, $this->tahunAjaran),
        ];

        return view('guru.nilai.show', compact('siswa', 'nilai', 'mataPelajaran', 'statistik'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Nilai $nilai)
    {
        $this->validateKelasAccess($nilai->siswa);

        return view('guru.nilai.edit', compact('nilai'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Nilai $nilai)
    {
        $this->validateKelasAccess($nilai->siswa);

        $validated = $request->validate([
            'nilai_pengetahuan' => 'required|integer|min:0|max:100',
            'nilai_keterampilan' => 'required|integer|min:0|max:100',
            'deskripsi' => 'nullable|string|max:500',
        ]);

        $nilai->update($validated);

        if (empty($validated['deskripsi'])) {
            $nilai->updateDeskripsi();
        }

        return redirect()->route('guru.nilai.show', $nilai->siswa)
            ->with('success', 'Nilai berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Nilai $nilai)
    {
        $this->validateKelasAccess($nilai->siswa);

        $siswa = $nilai->siswa;
        $mapel = $nilai->mataPelajaran->nama_mapel;

        $nilai->delete();

        return redirect()->route('guru.nilai.show', $siswa)
            ->with('success', "Nilai {$mapel} berhasil dihapus.");
    }

    public function bulkInput(Request $request)
    {
        $guru = Auth::user();
        $kelas = $guru->kelas;

        $mataPelajaranId = $request->mata_pelajaran_id;
        $mataPelajaran = null;

        if ($mataPelajaranId) {
            $mataPelajaran = MataPelajaran::find($mataPelajaranId);
        }

        $siswa = Siswa::where('kelas_id', $kelas->id)
            ->where('status', 'aktif')
            ->orderBy('nama_lengkap')
            ->get();

        $mataPelajaranList = MataPelajaran::where('tingkat', $kelas->tingkat)->get();

        $nilaiTersimpan = [];
        if ($mataPelajaran) {
            $nilaiTersimpan = Nilai::where('mata_pelajaran_id', $mataPelajaran->id)
                ->where('semester', $this->semester)
                ->where('tahun_ajaran', $this->tahunAjaran)
                ->whereIn('siswa_id', $siswa->pluck('id'))
                ->get()
                ->keyBy('siswa_id');
        }

        return view('guru.nilai.bulk-input', compact(
            'siswa',
            'kelas',
            'mataPelajaranList',
            'mataPelajaran',
            'nilaiTersimpan'
        ));
    }

    public function bulkStore(Request $request)
    {
        $validated = $request->validate([
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'nilai' => 'required|array',
            'nilai.*.siswa_id' => 'required|exists:siswa,id',
            'nilai.*.nilai_pengetahuan' => 'required|integer|min:0|max:100',
            'nilai.*.nilai_keterampilan' => 'required|integer|min:0|max:100',
        ]);

        DB::beginTransaction();
        try {
            $totalBerhasil = 0;
            $totalDiupdate = 0;

            foreach ($validated['nilai'] as $nilaiData) {
                $siswa = Siswa::find($nilaiData['siswa_id']);
                $this->validateKelasAccess($siswa);

                $nilai = Nilai::where('siswa_id', $nilaiData['siswa_id'])
                    ->where('mata_pelajaran_id', $validated['mata_pelajaran_id'])
                    ->where('semester', $this->semester)
                    ->where('tahun_ajaran', $this->tahunAjaran)
                    ->first();

                if ($nilai) {
                    $nilai->update([
                        'nilai_pengetahuan' => $nilaiData['nilai_pengetahuan'],
                        'nilai_keterampilan' => $nilaiData['nilai_keterampilan'],
                    ]);
                    $nilai->updateDeskripsi();
                    $totalDiupdate++;
                } else {
                    $nilai = Nilai::create([
                        'siswa_id' => $nilaiData['siswa_id'],
                        'mata_pelajaran_id' => $validated['mata_pelajaran_id'],
                        'semester' => $this->semester,
                        'tahun_ajaran' => $this->tahunAjaran,
                        'nilai_pengetahuan' => $nilaiData['nilai_pengetahuan'],
                        'nilai_keterampilan' => $nilaiData['nilai_keterampilan'],
                    ]);
                    $nilai->updateDeskripsi();
                    $totalBerhasil++;
                }
            }

            DB::commit();

            $message = "Berhasil: {$totalBerhasil} nilai baru disimpan";
            if ($totalDiupdate > 0) {
                $message .= ", {$totalDiupdate} nilai diperbarui";
            }

            return redirect()->route('guru.nilai.index')
                ->with('success', $message . '.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Gagal menyimpan nilai: ' . $e->getMessage());
        }
    }

    public function dataSiswa()
    {
        $guru = Auth::user();
        $kelas = $guru->kelas;

        if (!$kelas) {
            return redirect()->route('guru.dashboard')
                ->with('error', 'Anda belum memiliki kelas.');
        }

        $siswa = Siswa::where('kelas_id', $kelas->id)
            ->where('status', 'aktif')
            ->orderBy('nama_lengkap')
            ->paginate(20);

        return view('guru.siswa.index', compact('siswa', 'kelas'));
    }

    public function detailSiswa(Siswa $siswa)
    {
        // Validasi akses (Middleware check.kelas sudah menangani, tapi double check aman)
        if ($siswa->kelas_id != Auth::user()->kelas_id) {
            abort(403, 'Akses ditolak');
        }

        $siswa->load('kelas');
        return view('guru.siswa.show', compact('siswa'));
    }

    private function validateKelasAccess(Siswa $siswa)
    {
        $guru = Auth::user();

        if ($siswa->kelas_id != $guru->kelas_id) {
            abort(403, 'Anda tidak memiliki akses ke siswa ini.');
        }
    }

    private function hitungProgressNilai(Siswa $siswa)
    {
        $totalMapel = MataPelajaran::where('tingkat', $siswa->kelas->tingkat)->count();
        $sudahDinilai = Nilai::where('siswa_id', $siswa->id)
            ->where('semester', $this->semester)
            ->where('tahun_ajaran', $this->tahunAjaran)
            ->whereNotNull('nilai_akhir')
            ->count();

        return $totalMapel > 0 ? round(($sudahDinilai / $totalMapel) * 100) : 0;
    }
}
