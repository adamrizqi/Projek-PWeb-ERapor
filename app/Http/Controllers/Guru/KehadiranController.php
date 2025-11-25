<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Kehadiran;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class KehadiranController extends Controller
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
    public function index()
    {
        $guru = Auth::user();
        $kelas = $guru->kelas;

        $siswa = Siswa::where('kelas_id', $kelas->id)
            ->where('status', 'aktif')
            ->orderBy('nama_lengkap')
            ->get();

        foreach ($siswa as $s) {
            $s->kehadiran_data = $s->getKehadiran($this->semester, $this->tahunAjaran);
        }

        return view('guru.kehadiran.index', compact('siswa', 'kelas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function harian(Request $request)
    {
        $guru = Auth::user();
        $kelas = $guru->kelas;

        $tanggal = $request->input('tanggal', now()->format('Y-m-d'));

        $siswa = Siswa::where('kelas_id', $kelas->id)
            ->where('status', 'aktif')
            ->orderBy('nama_lengkap')
            ->get();

        return view('guru.kehadiran.harian', compact('siswa', 'kelas', 'tanggal'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Siswa $siswa)
    {
        $this->validateKelasAccess($siswa);

        $validated = $request->validate([
            'hadir' => 'required|integer|min:0',
            'sakit' => 'required|integer|min:0',
            'izin' => 'required|integer|min:0',
            'alpa' => 'required|integer|min:0',
        ]);

        DB::beginTransaction();
        try {
            $kehadiran = Kehadiran::updateOrCreate(
                [
                    'siswa_id' => $siswa->id,
                    'semester' => $this->semester,
                    'tahun_ajaran' => $this->tahunAjaran,
                ],
                $validated
            );

            DB::commit();

            return redirect()->route('guru.kehadiran.index')
                ->with('success', "Kehadiran {$siswa->nama_lengkap} berhasil disimpan.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Gagal menyimpan kehadiran: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Siswa $siswa)
    {
        $this->validateKelasAccess($siswa);

        $kehadiran = $siswa->getKehadiran($this->semester, $this->tahunAjaran);

        return view('guru.kehadiran.show', compact('siswa', 'kehadiran'));
    }

    /**
     * Store kehadiran harian.
     */
    public function storeHarian(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'kehadiran' => 'required|array',
            'kehadiran.*.siswa_id' => 'required|exists:siswa,id',
            'kehadiran.*.status' => 'required|in:hadir,sakit,izin,alpa',
        ]);

        DB::beginTransaction();
        try {
            foreach ($validated['kehadiran'] as $data) {
                $siswa = Siswa::find($data['siswa_id']);
                $this->validateKelasAccess($siswa);

                $kehadiran = Kehadiran::firstOrCreate(
                    [
                        'siswa_id' => $data['siswa_id'],
                        'semester' => $this->semester,
                        'tahun_ajaran' => $this->tahunAjaran,
                    ],
                    [
                        'hadir' => 0,
                        'sakit' => 0,
                        'izin' => 0,
                        'alpa' => 0,
                    ]
                );

                $status = $data['status'];
                $kehadiran->$status += 1;
                $kehadiran->save();
            }

            DB::commit();

            return redirect()->route('guru.kehadiran.index')
                ->with('success', 'Kehadiran harian berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Gagal menyimpan kehadiran: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kehadiran $kehadiran)
    {
        $this->validateKelasAccess($kehadiran->siswa);

        $validated = $request->validate([
            'hadir' => 'required|integer|min:0',
            'sakit' => 'required|integer|min:0',
            'izin' => 'required|integer|min:0',
            'alpa' => 'required|integer|min:0',
        ]);

        $kehadiran->update($validated);

        return back()->with('success', 'Kehadiran berhasil diperbarui.');
    }

    /**
     * Validate kelas access.
     */
    private function validateKelasAccess(Siswa $siswa)
    {
        $guru = Auth::user();

        if ($siswa->kelas_id != $guru->kelas_id) {
            abort(403, 'Anda tidak memiliki akses ke siswa ini.');
        }
    }
}
