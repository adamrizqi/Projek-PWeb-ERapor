<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MataPelajaran;
use Illuminate\Support\Facades\DB;

class MataPelajaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = MataPelajaran::query();

        if ($request->filled('tingkat')) {
            $query->where('tingkat', $request->tingkat);
        }

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        $mataPelajaran = $query->orderBy('tingkat')
            ->orderBy('nama_mapel')
            ->paginate(20);

        $statistik = [
            'total' => MataPelajaran::count(),
            'kelas_rendah' => MataPelajaran::kelasRendah()->count(),
            'kelas_tinggi' => MataPelajaran::kelasTinggi()->count(),
        ];

        return view('admin.mata-pelajaran.index', compact('mataPelajaran', 'statistik'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.mata-pelajaran.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_mapel' => 'required|string|max:100',
            'kode_mapel' => 'required|string|max:20|unique:mata_pelajaran,kode_mapel',
            'tingkat' => 'required|integer|min:1|max:6',
            'kkm' => 'required|integer|min:0|max:100',
        ]);

        $mataPelajaran = MataPelajaran::create($validated);

        return redirect()->route('admin.mata-pelajaran.index')
            ->with('success', "Mata pelajaran {$mataPelajaran->nama_mapel} berhasil ditambahkan.");
    }

    /**
     * Display the specified resource.
     */
    public function show(MataPelajaran $mataPelajaran)
    {
        $semester = 1;
        $tahunAjaran = '2024/2025';

        $statistik = [
            'total_nilai' => $mataPelajaran->nilai()->count(),
            'rata_rata' => round($mataPelajaran->nilai()
                ->whereNotNull('nilai_akhir')
                ->avg('nilai_akhir'), 2),
        ];

        return view('admin.mata-pelajaran.show', compact('mataPelajaran', 'statistik'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MataPelajaran $mataPelajaran)
    {
        return view('admin.mata-pelajaran.edit', compact('mataPelajaran'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MataPelajaran $mataPelajaran)
    {
        $validated = $request->validate([
            'nama_mapel' => 'required|string|max:100',
            'kode_mapel' => 'required|string|max:20|unique:mata_pelajaran,kode_mapel,' . $mataPelajaran->id,
            'tingkat' => 'required|integer|min:1|max:6',
            'kkm' => 'required|integer|min:0|max:100',
        ]);

        $mataPelajaran->update($validated);

        return redirect()->route('admin.mata-pelajaran.index')
            ->with('success', "Mata pelajaran {$mataPelajaran->nama_mapel} berhasil diperbarui.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MataPelajaran $mataPelajaran)
    {
        if ($mataPelajaran->nilai()->count() > 0) {
            return back()->with('error',
                'Tidak dapat menghapus mata pelajaran yang sudah memiliki data nilai.');
        }

        $nama = $mataPelajaran->nama_mapel;
        $mataPelajaran->delete();

        return redirect()->route('admin.mata-pelajaran.index')
            ->with('success', "Mata pelajaran {$nama} berhasil dihapus.");
    }
}
