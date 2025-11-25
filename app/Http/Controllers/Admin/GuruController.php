<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\User;
use App\Models\Kelas;
use App\Imports\GuruImport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class GuruController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::guru()->with('kelas');

        if ($request->filled('kelas_id')) {
            if ($request->kelas_id == 'tanpa_kelas') {
                $query->whereNull('kelas_id');
            } else {
                $query->where('kelas_id', $request->kelas_id);
            }
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%")
                  ->orWhere('nip', 'like', "%{$request->search}%");
            });
        }

        $guru = $query->orderBy('name')->paginate(15);

        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();

        $statistik = [
            'total_guru' => User::guru()->count(),
            'dengan_kelas' => User::guru()->whereNotNull('kelas_id')->count(),
            'tanpa_kelas' => User::guru()->whereNull('kelas_id')->count(),
        ];

        return view('admin.guru.index', compact('guru', 'kelasList', 'statistik'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kelasTersedia = Kelas::whereNull('wali_kelas_id')
            ->orderBy('tingkat')
            ->orderBy('nama_kelas')
            ->get();

        return view('admin.guru.create', compact('kelasTersedia'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'nip' => 'required|string|max:20|unique:users,nip',
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string',
            'kelas_id' => 'nullable|exists:kelas,id',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        DB::beginTransaction();
        try {
            $validated['role'] = 'guru';
            $validated['password'] = Hash::make($validated['password']);
            $validated['email_verified_at'] = now();

            $guru = User::create($validated);

            if ($validated['kelas_id']) {
                Kelas::find($validated['kelas_id'])
                    ->update(['wali_kelas_id' => $guru->id]);
            }

            DB::commit();

            return redirect()->route('admin.guru.index')
                ->with('success', "Guru {$guru->nama_pendek} berhasil ditambahkan.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Gagal menambahkan guru: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $guru)
    {
         if ($guru->role !== 'guru') {
            abort(404);
        }

        $guru->load('kelas.siswaAktif');

        $statistik = [];

        if ($guru->kelas) {
            $statistik = [
                'total_siswa' => $guru->getTotalSiswaKelas(),
                'rata_rata_kelas' => $guru->kelas->getRataRataNilaiKelas(),
            ];
        }

        return view('admin.guru.show', compact('guru', 'statistik'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $guru)
    {
        if ($guru->role !== 'guru') {
            abort(404);
        }

        $kelasTersedia = Kelas::where(function ($query) use ($guru) {
            $query->whereNull('wali_kelas_id')
                  ->orWhere('wali_kelas_id', $guru->id);
        })
        ->orderBy('tingkat')
        ->orderBy('nama_kelas')
        ->get();

        return view('admin.guru.edit', compact('guru', 'kelasTersedia'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $guru)
    {
        if ($guru->role !== 'guru') {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $guru->id,
            'nip' => 'required|string|max:20|unique:users,nip,' . $guru->id,
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string',
            'kelas_id' => 'nullable|exists:kelas,id',
        ]);

        DB::beginTransaction();
        try {
            if ($guru->kelas_id && $guru->kelas_id != $validated['kelas_id']) {
                Kelas::find($guru->kelas_id)->update(['wali_kelas_id' => null]);
            }

            $guru->update($validated);

            if ($validated['kelas_id']) {
                Kelas::find($validated['kelas_id'])
                    ->update(['wali_kelas_id' => $guru->id]);
            }

            DB::commit();

            return redirect()->route('admin.guru.show', $guru)
                ->with('success', "Data guru {$guru->nama_pendek} berhasil diperbarui.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Gagal memperbarui data guru: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $guru)
    {
        if ($guru->role !== 'guru') {
            abort(404);
        }

        DB::beginTransaction();
        try {
            if ($guru->kelas_id) {
                Kelas::find($guru->kelas_id)->update(['wali_kelas_id' => null]);
            }

            $nama = $guru->nama_pendek;
            $guru->delete();

            DB::commit();

            return redirect()->route('admin.guru.index')
                ->with('success', "Guru {$nama} berhasil dihapus.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus guru: ' . $e->getMessage());
        }
    }

    public function import(Request $request)
    {
    $request->validate([
        'file' => 'required|mimes:xlsx,xls,csv|max:5120', // Maks 5MB
    ]);

    try {

        Excel::import(new GuruImport, $request->file('file'));

        return redirect()->route('admin.guru.index')
            ->with('success', 'Data guru berhasil diimpor.');

    } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
         $failures = $e->failures();
         $errorMessages = [];
         foreach ($failures as $failure) {
             $errorMessages[] = 'Baris ' . $failure->row() . ': ' . implode(', ', $failure->errors());
         }
         return back()->with('error', 'Gagal mengimpor data: ' . implode(' | ', $errorMessages));

    } catch (\Exception $e) {
        return back()->with('error', 'Terjadi kesalahan saat mengimpor: ' . $e->getMessage());
    }
}

    public function assignKelas(Request $request, User $guru)
    {
        if ($guru->role !== 'guru') {
            return back()->with('error', 'User bukan guru.');
        }

        $validated = $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        DB::beginTransaction();
        try {
            $kelas = Kelas::find($validated['kelas_id']);
            if ($kelas->wali_kelas_id && $kelas->wali_kelas_id != $guru->id) {
                return back()->with('error',
                    'Kelas tersebut sudah memiliki wali kelas.');
            }

            if ($guru->kelas_id && $guru->kelas_id != $validated['kelas_id']) {
                Kelas::find($guru->kelas_id)->update(['wali_kelas_id' => null]);
            }

            $guru->update(['kelas_id' => $validated['kelas_id']]);

            $kelas->update(['wali_kelas_id' => $guru->id]);

            DB::commit();

            return back()->with('success',
                "{$guru->nama_pendek} berhasil ditugaskan ke kelas {$kelas->nama_kelas}.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menugaskan kelas: ' . $e->getMessage());
        }
    }

    public function resetPassword(Request $request, User $guru)
    {
        if ($guru->role !== 'guru') {
            return back()->with('error', 'User bukan guru.');
        }

        $validated = $request->validate([
            'new_password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $guru->update([
            'password' => Hash::make($validated['new_password']),
        ]);

        return back()->with('success',
            "Password guru {$guru->nama_pendek} berhasil direset.");
    }
}
