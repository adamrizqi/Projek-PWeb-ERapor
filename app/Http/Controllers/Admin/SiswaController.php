<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SiswaImport;
use App\Exports\SiswaExport;


class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Siswa::with('kelas');

        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->kelas_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('jenis_kelamin')) {
            $query->where('jenis_kelamin', $request->jenis_kelamin);
        }

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        $siswa = $query->orderBy('nama_lengkap')->paginate(20);

        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();

        return view('admin.siswa.index', compact('siswa', 'kelasList'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();

        return view('admin.siswa.create', compact('kelasList'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nis' => 'required|string|max:20|unique:siswa,nis',
            'nisn' => 'nullable|string|max:20|unique:siswa,nisn',
            'nama_lengkap' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:50',
            'tanggal_lahir' => 'required|date|before:today',
            'alamat' => 'required|string',
            'nama_wali' => 'required|string|max:100',
            'phone_wali' => 'nullable|string|max:15',
            'kelas_id' => 'required|exists:kelas,id',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        DB::beginTransaction();
        try {
            if ($request->hasFile('foto')) {
                $file = $request->file('foto');
                $filename = 'siswa_' . time() . '_' . Str::slug($validated['nama_lengkap']) . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('siswa', $filename, 'public');
                $validated['foto'] = $path;
            }

            $validated['status'] = 'aktif';

            $siswa = Siswa::create($validated);

            DB::commit();

            return redirect()->route('admin.siswa.index')
                ->with('success', "Siswa {$siswa->nama_lengkap} berhasil ditambahkan.");
        } catch (\Exception $e) {
            DB::rollBack();

            if (isset($validated['foto'])) {
                Storage::disk('public')->delete($validated['foto']);
            }

            return back()->withInput()
                ->with('error', 'Gagal menambahkan siswa: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Siswa $siswa)
    {
        $siswa->load('kelas.waliKelas');

        $semester = 1;
        $tahunAjaran = '2024/2025';

        $nilai = $siswa->getNilai($semester, $tahunAjaran);
        $kehadiran = $siswa->getKehadiran($semester, $tahunAjaran);
        $sikap = $siswa->getSikap($semester, $tahunAjaran);

        $statistik = [
            'rata_rata_nilai' => $siswa->getRataRataNilai($semester, $tahunAjaran),
            'ranking' => $siswa->getRankingKelas($semester, $tahunAjaran),
            'persentase_rapor' => $siswa->getPersentaseKelengkapanRapor($semester, $tahunAjaran),
        ];

        return view('admin.siswa.show', compact('siswa', 'nilai', 'kehadiran', 'sikap', 'statistik'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Siswa $siswa)
    {
        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();

        return view('admin.siswa.edit', compact('siswa', 'kelasList'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Siswa $siswa)
    {
        $validated = $request->validate([
            'nis' => 'required|string|max:20|unique:siswa,nis,' . $siswa->id,
            'nisn' => 'nullable|string|max:20|unique:siswa,nisn,' . $siswa->id,
            'nama_lengkap' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:50',
            'tanggal_lahir' => 'required|date|before:today',
            'alamat' => 'required|string',
            'nama_wali' => 'required|string|max:100',
            'phone_wali' => 'nullable|string|max:15',
            'kelas_id' => 'required|exists:kelas,id',
            'status' => 'required|in:aktif,lulus,pindah,keluar',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        DB::beginTransaction();
        try {
            if ($request->hasFile('foto')) {
                if ($siswa->foto) {
                    Storage::disk('public')->delete($siswa->foto);
                }

                $file = $request->file('foto');
                $filename = 'siswa_' . time() . '_' . Str::slug($validated['nama_lengkap']) . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('siswa', $filename, 'public');
                $validated['foto'] = $path;
            }

            $siswa->update($validated);

            DB::commit();

            return redirect()->route('admin.siswa.show', $siswa)
                ->with('success', "Data siswa {$siswa->nama_lengkap} berhasil diperbarui.");
        } catch (\Exception $e) {
            DB::rollBack();

            if (isset($validated['foto']) && $validated['foto'] != $siswa->foto) {
                Storage::disk('public')->delete($validated['foto']);
            }

            return back()->withInput()
                ->with('error', 'Gagal memperbarui data siswa: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Siswa $siswa)
    {
        DB::beginTransaction();
        try {
            if ($siswa->foto) {
                Storage::disk('public')->delete($siswa->foto);
            }

            $nama = $siswa->nama_lengkap;

            $siswa->delete();

            DB::commit();

            return redirect()->route('admin.siswa.index')
                ->with('success', "Siswa {$nama} beserta semua data terkait berhasil dihapus.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus siswa: ' . $e->getMessage());
        }
    }


    /**
     * Pindah kelas
     */
    public function pindahKelas(Request $request, Siswa $siswa)
    {
        $validated = $request->validate([
            'kelas_baru_id' => 'required|exists:kelas,id|different:' . $siswa->kelas_id,
        ]);

        DB::beginTransaction();
        try {
            $kelasLama = $siswa->kelas->nama_kelas;
            $siswa->update(['kelas_id' => $validated['kelas_baru_id']]);
            $kelasBaru = $siswa->kelas->nama_kelas;

            DB::commit();

            return back()->with('success',
                "{$siswa->nama_lengkap} berhasil dipindahkan dari kelas {$kelasLama} ke {$kelasBaru}.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memindahkan kelas: ' . $e->getMessage());
        }
    }

    /**
     * Update status siswa
     */
    public function updateStatus(Request $request, Siswa $siswa)
    {
        $validated = $request->validate([
            'status' => 'required|in:aktif,lulus,pindah,keluar',
        ]);

        $siswa->update($validated);

        return back()->with('success',
            "Status siswa {$siswa->nama_lengkap} berhasil diubah menjadi {$validated['status']}.");
    }

    /**
     * Import siswa dari Excel/CSV
     */
    public function import(Request $request)
    {
        $validated = $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:5120', // Maks 5MB
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        try {

            Excel::import(new SiswaImport($validated['kelas_id']), $request->file('file'));

            return redirect()->route('admin.siswa.index')
                ->with('success', 'Data siswa berhasil diimpor.');

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
             $failures = $e->failures();
             $errorMessages = [];
             foreach ($failures as $failure) {
                 $attribute = $failure->attribute();
                 $errorMessages[] = 'Baris ' . $failure->row() . ' (Kolom: ' . $attribute . '): ' . implode(', ', $failure->errors());
             }
             return back()->with('error', 'Gagal mengimpor data: ' . implode(' | ', $errorMessages));

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat mengimpor: ' . $e->getMessage());
        }
    }

/**
     * Export siswa ke Excel
     */
    public function export(Kelas $kelas = null)
    {
        $date = date('Y-m-d');

        if ($kelas) {
            $filename = "daftar_siswa_kelas_{$kelas->nama_kelas}_{$date}.xlsx";
            $export = new SiswaExport($kelas->id); // Kirim kelas_id ke constructor
        } else {
            $filename = "daftar_siswa_SEMUA_{$date}.xlsx";
            $export = new SiswaExport(null); // Kirim null
        }

        return Excel::download($export, $filename);
    }
}
