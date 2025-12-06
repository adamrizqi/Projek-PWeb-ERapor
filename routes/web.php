<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\KelasController as AdminKelasController;
use App\Http\Controllers\Admin\SiswaController as AdminSiswaController;
use App\Http\Controllers\Admin\RaporController as AdminRaporController;
use App\Http\Controllers\Admin\MataPelajaranController;
use App\Http\Controllers\Admin\GuruController;
use App\Http\Controllers\Guru\NilaiController;
use App\Http\Controllers\Guru\KehadiranController;
use App\Http\Controllers\Guru\SikapController;
use App\Http\Controllers\Guru\RaporController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'guru') {
            return redirect()->route('guru.dashboard');
        }
    }
    return view('welcome');
})->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard');

    Route::resource('kelas', AdminKelasController::class)->parameters(['kelas' => 'kelas']);
    Route::post('kelas/{kelas}/assign-wali', [AdminKelasController::class, 'assignWali'])->name('kelas.assign-wali');

    Route::post('siswa/import', [AdminSiswaController::class, 'import'])->name('siswa.import');
    Route::get('siswa/export/{kelas?}', [AdminSiswaController::class, 'export'])->name('siswa.export');
    Route::resource('siswa', AdminSiswaController::class)->parameters(['siswa' => 'siswa']);
    Route::post('siswa/{siswa}/pindah-kelas', [AdminSiswaController::class, 'pindahKelas'])->name('siswa.pindah-kelas');
    Route::post('siswa/{siswa}/update-status', [AdminSiswaController::class, 'updateStatus'])->name('siswa.update-status');
    Route::get('siswa/{siswa}/rapor/preview', [AdminRaporController::class, 'preview'])->name('siswa.rapor.preview');
    Route::get('siswa/{siswa}/rapor/download', [AdminRaporController::class, 'download'])->name('siswa.rapor.download');

    Route::resource('mata-pelajaran', MataPelajaranController::class)->parameters(['mata_pelajaran' => 'mata_pelajaran']);

    Route::resource('guru', GuruController::class)->parameters(['guru' => 'guru']);
    Route::post('guru/{guru}/assign-kelas', [GuruController::class, 'assignKelas'])->name('guru.assign-kelas');
    Route::post('guru/import', [GuruController::class, 'import'])->name('guru.import');
    Route::post('guru/{guru}/reset-password', [GuruController::class, 'resetPassword'])->name('guru.reset-password');

    Route::get('laporan/nilai', [DashboardController::class, 'laporanNilai'])->name('laporan.nilai');
    Route::get('laporan/kehadiran', [DashboardController::class, 'laporanKehadiran'])->name('laporan.kehadiran');
    Route::get('laporan/rekap-kelas', [DashboardController::class, 'rekapKelas'])->name('laporan.rekap-kelas');

    Route::get('backup', [DashboardController::class, 'backup'])->name('backup');
    Route::post('backup/create', [DashboardController::class, 'createBackup'])->name('backup.create');
    Route::get('backup/download/{file}', [DashboardController::class, 'downloadBackup'])->name('backup.download');
});

Route::middleware(['auth', 'guru', 'check.kelas'])->prefix('guru')->name('guru.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'guruDashboard'])->name('dashboard');

    Route::get('nilai', [NilaiController::class, 'index'])->name('nilai.index');
    Route::get('nilai/siswa/{siswa}', [NilaiController::class, 'show'])->name('nilai.show');
    Route::get('nilai/siswa/{siswa}/input', [NilaiController::class, 'input'])->name('nilai.input');
    Route::post('nilai/siswa/{siswa}/store', [NilaiController::class, 'store'])->name('nilai.store');
    Route::get('nilai/{nilai}/edit', [NilaiController::class, 'edit'])->name('nilai.edit');
    Route::put('nilai/{nilai}', [NilaiController::class, 'update'])->name('nilai.update');
    Route::delete('nilai/{nilai}', [NilaiController::class, 'destroy'])->name('nilai.destroy');

    // Bulk input nilai
    Route::get('nilai/bulk-input', [NilaiController::class, 'bulkInput'])->name('nilai.bulk-input');
    Route::post('nilai/bulk-store', [NilaiController::class, 'bulkStore'])->name('nilai.bulk-store');

    // Kelola Kehadiran
    Route::get('kehadiran', [KehadiranController::class, 'index'])->name('kehadiran.index');
    Route::get('kehadiran/siswa/{siswa}', [KehadiranController::class, 'show'])->name('kehadiran.show');
    Route::post('kehadiran/siswa/{siswa}', [KehadiranController::class, 'store'])->name('kehadiran.store');
    Route::put('kehadiran/{kehadiran}', [KehadiranController::class, 'update'])->name('kehadiran.update');

    // Input kehadiran harian
    Route::get('kehadiran/harian', [KehadiranController::class, 'harian'])->name('kehadiran.harian');
    Route::post('kehadiran/harian/store', [KehadiranController::class, 'storeHarian'])->name('kehadiran.harian.store');

    // Kelola Sikap
    Route::get('sikap', [SikapController::class, 'index'])->name('sikap.index');
    Route::get('sikap/siswa/{siswa}', [SikapController::class, 'show'])->name('sikap.show');
    Route::post('sikap/siswa/{siswa}', [SikapController::class, 'store'])->name('sikap.store');
    Route::put('sikap/{sikap}', [SikapController::class, 'update'])->name('sikap.update');

    // Template sikap
    Route::get('sikap/template', [SikapController::class, 'template'])->name('sikap.template');

    // Generate & Cetak Rapor
    Route::get('rapor', [RaporController::class, 'index'])->name('rapor.index');
    Route::get('rapor/siswa/{siswa}', [RaporController::class, 'show'])->name('rapor.show');
    Route::get('rapor/siswa/{siswa}/preview', [RaporController::class, 'preview'])->name('rapor.preview');
    Route::get('rapor/siswa/{siswa}/download', [RaporController::class, 'download'])->name('rapor.download');
    Route::get('rapor/kelas/download-all', [RaporController::class, 'downloadAll'])->name('rapor.download-all');

    // Validasi kelengkapan rapor
    Route::get('rapor/validasi', [RaporController::class, 'validasi'])->name('rapor.validasi');

    // Data Siswa (Read Only untuk Guru)
    Route::get('siswa', [NilaiController::class, 'dataSiswa'])->name('siswa.index');
    Route::get('siswa/{siswa}', [NilaiController::class, 'detailSiswa'])->name('siswa.show');
});

    Route::get('/direct-reset-password', function () {
        return view('auth.direct-reset');
    })->name('password.direct');

    Route::post('/direct-reset-password', function (Request $request) {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8',
        ]);

        $user = User::where('email', $request->email)->first();
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('login')
            ->with('status', 'Berhasil! Password untuk ' . $request->email . ' telah diganti.');
    })->name('password.direct.store');

require __DIR__.'/auth.php';
