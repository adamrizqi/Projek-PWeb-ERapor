<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Sikap;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SikapController extends Controller
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
            $s->sikap_data = $s->getSikap($this->semester, $this->tahunAjaran);
        }

        return view('guru.sikap.index', compact('siswa', 'kelas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Siswa $siswa)
    {
        $this->validateKelasAccess($siswa);

        $validated = $request->validate([
            'sikap_spiritual' => 'required|string|max:1000',
            'sikap_sosial' => 'required|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            $sikap = Sikap::updateOrCreate(
                [
                    'siswa_id' => $siswa->id,
                    'semester' => $this->semester,
                    'tahun_ajaran' => $this->tahunAjaran,
                ],
                $validated
            );

            DB::commit();

            return redirect()->route('guru.sikap.index')
                ->with('success', "Sikap {$siswa->nama_lengkap} berhasil disimpan.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Gagal menyimpan sikap: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Siswa $siswa)
    {
        $this->validateKelasAccess($siswa);

        $sikap = $siswa->getSikap($this->semester, $this->tahunAjaran);

        return view('guru.sikap.show', compact('siswa', 'sikap'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sikap $sikap)
    {
        $this->validateKelasAccess($sikap->siswa);

        $validated = $request->validate([
            'sikap_spiritual' => 'required|string|max:1000',
            'sikap_sosial' => 'required|string|max:1000',
        ]);

        $sikap->update($validated);

        return back()->with('success', 'Sikap berhasil diperbarui.');
    }

    /**
     * Template sikap (untuk membantu guru)
     */
    public function template()
    {
        $templateSpiritual = Sikap::getTemplateSikapSpiritual();
        $templateSosial = Sikap::getTemplateSikapSosial();

        return view('guru.sikap.template', compact('templateSpiritual', 'templateSosial'));
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
}
