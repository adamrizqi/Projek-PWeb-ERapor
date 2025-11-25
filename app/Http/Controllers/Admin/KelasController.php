<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class KelasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kelas = Kelas::with('waliKelas', 'siswaAktif')
            ->orderBy('tingkat')
            ->orderBy('nama_kelas')
            ->paginate(12);

        return view('admin.kelas.index', compact('kelas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $guruTersedia = User::guru()
            ->tanpaKelas()
            ->orderBy('name')
            ->get();

        $tahunAjaranList = $this->generateTahunAjaran();

        return view('admin.kelas.create', compact('guruTersedia', 'tahunAjaranList'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kelas' => 'required|string|max:10',
            'tingkat' => 'required|integer|min:1|max:6',
            'tahun_ajaran' => 'required|string|max:9',
            'wali_kelas_id' => 'nullable|exists:users,id',
        ]);

        DB::beginTransaction();
        try {
            $exists = Kelas::where('nama_kelas', $validated['nama_kelas'])
                ->where('tahun_ajaran', $validated['tahun_ajaran'])
                ->exists();

            if ($exists) {
                return back()->withInput()
                    ->with('error', 'Kelas dengan nama dan tahun ajaran tersebut sudah ada.');
            }

            $kelas = Kelas::create($validated);

            if ($validated['wali_kelas_id']) {
                User::find($validated['wali_kelas_id'])
                    ->update(['kelas_id' => $kelas->id]);
            }

            DB::commit();

            return redirect()->route('admin.kelas.index')
                ->with('success', "Kelas {$kelas->nama_kelas} berhasil dibuat.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Gagal membuat kelas: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Kelas $kelas)
    {
        $kelas->load(['waliKelas', 'siswaAktif']);

        $statistik = [
            'jumlah_siswa' => $kelas->siswaAktif->count(),
            'jumlah_laki' => $kelas->siswaAktif->where('jenis_kelamin', 'L')->count(),
            'jumlah_perempuan' => $kelas->siswaAktif->where('jenis_kelamin', 'P')->count(),
            'rata_rata_nilai' => $kelas->getRataRataNilaiKelas(),
        ];

        $mataPelajaran = \App\Models\MataPelajaran::where('tingkat', $kelas->tingkat)->get();

        return view('admin.kelas.show', compact('kelas', 'statistik', 'mataPelajaran'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kelas $kelas)
    {
        $guruTersedia = User::guru()
            ->where(function ($query) use ($kelas) {
                $query->whereNull('kelas_id')
                    ->orWhere('id', $kelas->wali_kelas_id);
            })
            ->orderBy('name')
            ->get();

        $tahunAjaranList = $this->generateTahunAjaran();

        return view('admin.kelas.edit', compact('kelas', 'guruTersedia', 'tahunAjaranList'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kelas $kelas)
    {
        $validated = $request->validate([
            'nama_kelas' => 'required|string|max:10',
            'tingkat' => 'required|integer|min:1|max:6',
            'tahun_ajaran' => 'required|string|max:9',
            'wali_kelas_id' => 'nullable|exists:users,id',
        ]);

        DB::beginTransaction();
        try {
            $exists = Kelas::where('nama_kelas', $validated['nama_kelas'])
                ->where('tahun_ajaran', $validated['tahun_ajaran'])
                ->where('id', '!=', $kelas->id)
                ->exists();

            if ($exists) {
                return back()->withInput()
                    ->with('error', 'Kelas dengan nama dan tahun ajaran tersebut sudah ada.');
            }

            if ($kelas->wali_kelas_id && $kelas->wali_kelas_id != $validated['wali_kelas_id']) {
                User::find($kelas->wali_kelas_id)->update(['kelas_id' => null]);
            }

            $kelas->update($validated);

            if ($validated['wali_kelas_id']) {
                User::find($validated['wali_kelas_id'])
                    ->update(['kelas_id' => $kelas->id]);
            }

            DB::commit();

            return redirect()->route('admin.kelas.index')
                ->with('success', "Kelas {$kelas->nama_kelas} berhasil diperbarui.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Gagal memperbarui kelas: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kelas $kelas)
    {
        if ($kelas->siswa()->count() > 0) {
            return back()->with('error',
                'Tidak dapat menghapus kelas yang masih memiliki siswa. Pindahkan siswa terlebih dahulu.');
        }

        DB::beginTransaction();
        try {
            if ($kelas->wali_kelas_id) {
                User::find($kelas->wali_kelas_id)->update(['kelas_id' => null]);
            }

            $namaKelas = $kelas->nama_kelas;
            $kelas->delete();

            DB::commit();

            return redirect()->route('admin.kelas.index')
                ->with('success', "Kelas {$namaKelas} berhasil dihapus.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus kelas: ' . $e->getMessage());
        }
    }

    public function assignWali(Request $request, Kelas $kelas)
    {
        $validated = $request->validate([
            'wali_kelas_id' => 'required|exists:users,id',
        ]);

        DB::beginTransaction();
        try {
            $guru = User::find($validated['wali_kelas_id']);
            if ($guru->kelas_id && $guru->kelas_id != $kelas->id) {
                return back()->with('error',
                    'Guru tersebut sudah mengampu kelas lain.');
            }

            if ($kelas->wali_kelas_id && $kelas->wali_kelas_id != $validated['wali_kelas_id']) {
                User::find($kelas->wali_kelas_id)->update(['kelas_id' => null]);
            }

            $kelas->update(['wali_kelas_id' => $validated['wali_kelas_id']]);

            $guru->update(['kelas_id' => $kelas->id]);

            DB::commit();

            return back()->with('success',
                "{$guru->nama_pendek} berhasil ditugaskan sebagai wali kelas {$kelas->nama_kelas}.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menugaskan wali kelas: ' . $e->getMessage());
        }
    }

    private function generateTahunAjaran()
    {
        $currentYear = date('Y');
        $tahunAjaran = [];

        for ($i = -1; $i <= 2; $i++) {
            $tahun = $currentYear + $i;
            $tahunAjaran[] = $tahun . '/' . ($tahun + 1);
        }

        return $tahunAjaran;
    }
}
